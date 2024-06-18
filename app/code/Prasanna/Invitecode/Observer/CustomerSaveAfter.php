<?php

namespace Prasanna\Invitecode\Observer;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\GroupFactory;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\EntityFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;

class CustomerSaveAfter implements ObserverInterface
{
    /** @var CustomerRepositoryInterface */
    private $customerRepository;

    /**
     * @var \FME\AdditionalCustomerAttributes\Helper\Data
     */
    private $helper;

    /**
     * @var \FME\AdditionalCustomerAttributes\Model\AttributeFactory
     */
    private $attributeFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;
    private $messageManager;

    private $attributeModel;

    private $_storeManager;

    private $eavConfig;

    private $optionCollectionFactory;

    private $groupFactory;

    private $eavEntityFactory;

    private $resource;

    private $catalogAttribute;
    private $catalogAttributeResource;
    private $attributeHelper;

    protected $inviteCodeCollection;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfiguration
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        CustomerRepositoryInterface $customerRepository,
        \FME\AdditionalCustomerAttributes\Model\Attribute $attributeModel,
        StoreManagerInterface $storeManager,
        Config $eavConfig,
        CollectionFactory $optionCollectionFactory,
        GroupFactory $groupFactory,
        EntityFactory $entityFactory,
        \FME\AdditionalCustomerAttributes\Model\ResourceModel\CustomerValues $resource,
        Attribute $catalogAttribute,
        \Prasanna\Invitecode\Model\ResourceModel\Attribute $catalogAttributeResource,
        \Prasanna\Invitecode\Helper\Attribute $attributeHelper,
        \Prasanna\Invitecode\Model\ResourceModel\Invitecode\Collection $inviteCodeCollection
    ) {
        $this->request = $request;
        $this->customerRepository = $customerRepository;
        // $this->attributeFactory = $attributeFactory;
        // $this->helper          = $helper;
        $this->messageManager = $messageManager;
        $this->attributeModel = $attributeModel;
        $this->_storeManager = $storeManager;
        $this->eavConfig = $eavConfig;
        $this->optionCollectionFactory = $optionCollectionFactory;
        $this->groupFactory = $groupFactory;
        $this->eavEntityFactory = $entityFactory;
        $this->resource = $resource;
        $this->catalogAttribute = $catalogAttribute;
        $this->catalogAttributeResource = $catalogAttributeResource;
        $this->attributeHelper = $attributeHelper;
        $this->inviteCodeCollection = $inviteCodeCollection;
    }
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $post = $this->request->getPost();
        //log
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/custom.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        try {
            $customerId = $observer->getEvent()->getCustomer()->getId();
            $customerObj = $this->customerRepository->getById($customerId);
            $optionId = '';

            /* find the highest weightage among the inputs that can have options mapped to customer groups */
            $weight = '';
            $highest_weight_input = '';
            $highest_weight_input_type = '';
            $customerGroupId = '';
            //$inputTypes = ["select", "multiselect", "checkbox", "radio"];

            foreach ($post as $key => $value) {

                //if the input is from the fme custom attributes only
                if (stripos($key, 'fme_') !== false) {
                    $attribute_code = str_replace('fme_', '', $key);
                    //find the highest weightage among the inputs having options
                    $attribute_type = $this->getAttributeType($attribute_code);
                    //if ((in_array($attribute_type, $inputTypes) && $this->isCustomerGroup($attribute_code) == 1) || $post[$key] == "fme_invite_code") {
                    if ($this->isCustomerGroup($attribute_code) == 1) {
                        //find the weight of the input
                        if ($value != '') {
                            // $attribute_id = $this->getAttributeIdByCode($attribute_code);
                            $input_weight = $this->getInputWeight($attribute_code);
                            if ($input_weight > $weight) {
                                $highest_weight_input = $attribute_code;
                                $highest_weight_input_type = $attribute_type;
                                $weight = $input_weight;
                            }
                        }
                    }
                }
            }

            $logger->info('highest weight: ' . $weight);
            // now find the highest weight of option item for checkbox, multiselect
            if ($weight != '') {
                $highest_weight_input_fme = "fme_" . $highest_weight_input;
                $logger->info('high wt input: ' . $highest_weight_input);
                //if($highest_weight_input == "fme_invite_code") {
                $logger->info('input has code: ' . $this->inputHasCode($highest_weight_input));
                if ($this->inputHasCode($highest_weight_input) == 1) {
                    //$code = $post["fme_invite_code"];
                    $logger->info('highest wt int fme:' . $highest_weight_input_fme);
                    $code = trim($post[$highest_weight_input_fme]);
                    $logger->info('invite code:' . $code);
                    //$inviteInfo = $this->inviteCodeCollection->addFieldToFilter('attribute_code', $highest_weight_input)->addFieldToFilter('code', $code)->addFieldToFilter('active', 1)->getFirstItem();
                    $inviteInfo = $this->codeInfo($highest_weight_input, $code);
                    if (!$inviteInfo->isEmpty()) {
                        $logger->info('code match:');
                        $logger->info('count:' . $inviteInfo->getData('count'));
                        $logger->info('reusable:' . $inviteInfo->getData('reusable'));

                        if ($inviteInfo->getData('count') <= 0 || ($inviteInfo->getData('count') > 0 && $inviteInfo->getData('reusable') == 1)) {
                            $customerGroupId = $inviteInfo->getData('customer_group');
                            $logger->info('customer group:' . $customerGroupId);
                            $count = $inviteInfo->getData('count');
                            //update the count
//                            $inviteData = $this->inviteCodeCollection->addFieldToFilter('code', $code);
//                            foreach ($inviteData as $code) {
//                                $code->setData('count', $count+1);
//                                $code->save();
//                            }
                            $this->updateUsesCount($code, $count);
                        } else {
                            $logger->info('invite code use count exceeded.');
                        }
                    } else {
                        $logger->info('code dont  match');
                    }
                } else {
                    $inputTypes = ["multiselect", "checkbox"];
                    if (in_array($highest_weight_input_type, $inputTypes)) {
                        $checkboxValues = [];
                        //$checkboxValues[] = $key2;
                        $highest_weight_post_item = $this->request->getParam($highest_weight_input_fme);
                        $option_weight_max = '';

                        foreach ($highest_weight_post_item as $key => $value) {
                            if ($value > 0) {
                                $option_weight = $this->getOptionWeight($value);
                                if ($option_weight > $option_weight_max) {
                                    //$highest_weight_option = $value;
                                    $option_weight_max = $option_weight;
                                    $optionId = $value;
                                }
                            }
                        }
                        //$optionId = implode(",", $checkboxValues);
                    } else {
                        $optionId = $value;
                    }
                    if (!empty($optionId)) :
                        //$storeId = $this->_storeManager->getStore()->getId();
                        /* fetch the group id of the selected option */
                        $adminValue = $this->getOptionValue($optionId); //get admin value of the selection option
                    //$adminValue = $this->attributeModel->getOptionValueById($optionId, $storeId); //dont work
                    /* get group id by the option admin value
                     * the option admin value should be the name of the group.
                    */
                    $customerGroupId = $this->getGroupIdByGroupCode($adminValue);
                    endif;
                }
            }

            if (!empty($customerGroupId)) :
                //$customerGroupId = 4; //STD_Cust
                $customerObj->setGroupId($customerGroupId);
            $this->customerRepository->save($customerObj);
            endif;
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        //return $this;
    }

    /**
     * @param $optionId
     * @param $storeId
     * @return void
     * Get attribute option value by option id and store id
     */
    public function getOptionValue($optionId, $storeId = 0)
    {
        $optionCollection = $this->optionCollectionFactory->create()
            ->setPositionOrder('asc')
            ->setStoreFilter($storeId)
            ->addFieldToFilter('main_table.option_id', $optionId)
            ->load();

        foreach ($optionCollection as $option) {
            return $option->getValue(); // Return option value
        }
        return null;
    }

    public function getGroupIdByGroupCode($groupCode)
    {
        $group = $this->groupFactory->create()->load($groupCode, 'customer_group_code');
        if ($group->getId()) {
            return $group->getId();
        }
        return null; // Return null if group not found
    }

    public function getAttributeType($code, $entityTypeId = null)
    {
        if ($entityTypeId == null || $entityTypeId < 1) {
            $entityTypeId = (int)$this->eavEntityFactory->create()->setType(
                \FME\AdditionalCustomerAttributes\Model\Attribute::ENTITY
            )->getTypeId();
        }
        return $this->resource->getAttributeType($code, $entityTypeId);
    }

    public function getInputWeight($input)
    {
        $attributeInfo = $this->eavConfig->getAttribute(9, $input);
        return $attributeInfo->getData('input_weight');
    }

    public function getAttributeIdByCode($attribute_code)
    {
        return $this->attributeHelper->getAttributeIdByCode($attribute_code);
    }

    public function getOptionWeight($option)
    {
        return $this->catalogAttributeResource->getOptionWeight($option);
    }

    public function isCustomerGroup($input)
    {
        $attributeInfo = $this->eavConfig->getAttribute(9, $input);
        return $attributeInfo->getData('is_customer_group');
    }

    public function inputHasCode($input)
    {
        $attributeInfo = $this->eavConfig->getAttribute(9, $input);
        return $attributeInfo->getData('has_code');
    }

    public function codeInfo($highest_weight_input, $code)
    {
        $inviteInfo = $this->inviteCodeCollection->addFieldToFilter('attribute_code', $highest_weight_input)->addFieldToFilter('code', $code)->addFieldToFilter('active', 1)->getFirstItem();
        return $inviteInfo;
    }

    public function updateUsesCount($code, $count)
    {
        $inviteData = $this->inviteCodeCollection->addFieldToFilter('code', $code);
        foreach ($inviteData as $code) {
            $code->setData('count', $count+1);
            $code->save();
        }
    }
}
