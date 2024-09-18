<?php

namespace WePay\Registrationcode\Observer;

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
     * @var \WePay\AdditionalCustomerAttributes\Helper\Data
     */
    private $helper;

    /**
     * @var \WePay\AdditionalCustomerAttributes\Model\AttributeFactory
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

    protected $registrationCodeCollection;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfiguration
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        CustomerRepositoryInterface $customerRepository,
        \WePay\AdditionalCustomerAttributes\Model\Attribute $attributeModel,
        StoreManagerInterface $storeManager,
        Config $eavConfig,
        CollectionFactory $optionCollectionFactory,
        GroupFactory $groupFactory,
        EntityFactory $entityFactory,
        \WePay\AdditionalCustomerAttributes\Model\ResourceModel\CustomerValues $resource,
        Attribute $catalogAttribute,
        \WePay\Registrationcode\Model\ResourceModel\Attribute $catalogAttributeResource,
        \WePay\Registrationcode\Helper\Attribute $attributeHelper,
        \WePay\Registrationcode\Model\ResourceModel\Registrationcode\Collection $registrationCodeCollection
    ) {
        $this->request = $request;
        $this->customerRepository = $customerRepository;
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
        $this->registrationCodeCollection = $registrationCodeCollection;
    }
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $post = $this->request->getPost();
        //log
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/registrationcode.log');
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
                //if the input is from the wepay custom attributes only
                if (stripos($key, 'wepay_') !== false) {
                    $attribute_code = str_replace('wepay_', '', $key);
                    //find the highest weightage among the inputs having options
                    $attribute_type = $this->getAttributeType($attribute_code);
                    //if ((in_array($attribute_type, $inputTypes) && $this->isCustomerGroup($attribute_code) == 1) || $post[$key] == "wepay_registration_code") {
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

            // now find the highest weight of option item for checkbox, multiselect
            if ($weight != '') {
                $highest_weight_input_wepay = "wepay_" . $highest_weight_input;
                if ($this->inputHasCode($highest_weight_input) == 1) {
                    $code = trim($post[$highest_weight_input_wepay]);

                    $registrationInfo = $this->compareAttributeCode($highest_weight_input, $code);

                    if (isset($registrationInfo) && !$registrationInfo->isEmpty()) {
                        if ($registrationInfo->getData('count') <= 0 || ($registrationInfo->getData('count') > 0 && $registrationInfo->getData('reusable') == 1)) {
                            //check usage limit
                            if ($registrationInfo->getData('count') <= $registrationInfo->getData('code_usage_limit')) {
                                $customerGroupId = $registrationInfo->getData('customer_group');
                                $entity_id = $registrationInfo->getData('entity_id');
                                $count = $registrationInfo->getData('count');
                                $this->updateUsesCount($entity_id, $count);
                            } else {
                                $logger->info('Code count limit exceeded');
                            }
                        } else {
                            $logger->info('Registration code use count exceeded.');
                        }
                    } else {
                        $logger->info('Registration data empty');
                    }
                } else {
                    $inputTypes = ["multiselect", "checkbox"];
                    if (in_array($highest_weight_input_type, $inputTypes)) {
                        $checkboxValues = [];
                        //$checkboxValues[] = $key2;
                        $highest_weight_post_item = $this->request->getParam($highest_weight_input_wepay);
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
                        /* fetch the group id of the selected option */
                        $adminValue = $this->getOptionValue($optionId); //get admin value of the selection option
                        /* get group id by the option admin value
                         * the option admin value should be the name of the group.
                        */
                        $customerGroupId = $this->getGroupIdByGroupCode($adminValue);
                    endif;
                }
            }
            $logger->info('customer group: '.$customerGroupId);
            if (!empty($customerGroupId)) :
                $logger->info('customer group not empty');
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
                \WePay\AdditionalCustomerAttributes\Model\Attribute::ENTITY
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

    public function updateUsesCount($id, $count)
    {
        $registrationData = $this->registrationCodeCollection->addFieldToFilter('entity_id', $id);
        foreach ($registrationData as $code) {
            $code->setData('count', $count+1);
            $code->save();
        }
    }

    public function compareAttributeCode($highest_weight_input, $code)
    {
        $registrationData = $this->registrationCodeCollection->addFieldToFilter('attribute_code', $highest_weight_input)->addFieldToFilter('active', 1);
        $registrationInfo = '';
        if ($registrationData) {
            $registrationItem = $registrationData->getFirstItem();
            $operation = $registrationItem->getData('comparison_operator');
            $compare_code = $registrationItem->getData('code');
            $post_code = $code;

            switch ($operation) {
                case 1:
                    //$registrationInfo = $registrationInfo->addFieldToFilter('code', ['in' => $post_code])->getFirstItem();
                    if (strpos($post_code, $compare_code)) {
                        $registrationInfo =  $registrationItem;
                    }
                    break;
                case 2:
                    if (!strpos($post_code, $compare_code)) {
                        $registrationInfo = $registrationItem;
                    }
                    break;
                case 3:
                    //$ends = substr($post_code, strpos($post_code, '.') + 1);
                    if (str_ends_with($post_code, $compare_code)) {
                        //if ($ends == $compare_code) {
                        $registrationInfo = $registrationItem;
                    }
                    break;
                case 4:
                    // $ends = substr($post_code, strpos($post_code, '.') + 1);
                    if (!str_ends_with($post_code, $compare_code)) {
                        //if ($ends !== $compare_code) {
                        $registrationInfo = $registrationItem;
                    }
                    break;
                case 5:
                    if (str_starts_with($post_code, $compare_code)) {
                        $registrationInfo = $registrationItem;
                    }
                    break;
                case 6:
                    if (!str_starts_with($post_code, $compare_code)) {
                        $registrationInfo = $registrationItem;
                    }
                    break;
                default:
                    //$registrationInfo = $registrationInfo->addFieldToFilter('code', $post_code)->getFirstItem();
                    if ($post_code == $compare_code) {
                        $registrationInfo = $registrationItem;
                    }
            }
        }
        return $registrationInfo;
    }
}
