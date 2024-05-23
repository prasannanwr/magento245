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
        \Prasanna\Invitecode\Helper\Attribute $attributeHelper
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
    }
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $post = $this->request->getPost();

        try {
            $customerId = $observer->getEvent()->getCustomer()->getId();
            $customerObj = $this->customerRepository->getById($customerId);
            //var_dump($post);exit;
            $optionId = '';

            foreach ($post as $key => $value) {
                //if the input is from the fme custom attributes only
                if (stripos($key, 'fme_') !== false) {
                    $attribute_code = str_replace('fme_', '', $key);

                    if ($this->getAttributeType($attribute_code) == "checkbox") {
                        $checkboxValues = [];
                        var_dump($value);
                        echo "<br>";
                        foreach ($value as $key2 => $val) {
                            echo $key2;
                            echo "<br>";
                            echo $val;
                            exit;
                            if ($val == 1) {
                                $checkboxValues[] = $key2;
                            }
                        }
                        $optionId = implode(",", $checkboxValues);
                    }

                    $attribute_id = $this->getAttributeIdByCode($attribute_code);
                    /*if ($key == "fme_customer_group") {
                        if (is_array($value)) { // if value is array type
                            if ($this->getAttributeType($attribute_code) == "checkbox") {
                                $checkboxValues = [];
                                foreach ($value as $key2 => $val) {
                                    if ($val == 1) {
                                        $checkboxValues[] = $key2;
                                    }
                                }
                                $optionId = implode(",", $checkboxValues);
                            }
                        } else {
                            $optionId = $value;
                        }
                    }*/
                    //select
                    $selected_customer_group = '';

                    //find highest weightage among the inputs having options
                    $inputTypes = array("select", "multiselect", "checkbox", "radio");
                    if (in_array($this->getAttributeType($attribute_code), $inputTypes)) {
                        $weight = '';
                        $highest_weight_input = '';
                        //find weight of the input
                        $input_weight = $this->getInputWeight($attribute_id);
                        if($input_weight > $weight)
                        {
                            $highest_weight_input = $attribute_code;
                            $weight = $input_weight;
                        }

                    }

                    //checkbox
                    if ($this->getAttributeType($highest_weight_input) == "checkbox") {
                        $checkboxValues = [];
                        foreach ($value as $key2 => $val) {
                            if ($val == 1) {
                                $checkboxValues[] = $key2;
                            }
                        }
                        $optionId = implode(",", $checkboxValues);
                    }
                    if ($this->getAttributeType($highest_weight_input) == "multiselect") {
                        $checkboxValues = [];
                        foreach ($value as $key2 => $val) {
                            if ($val == 1) {
                                $checkboxValues[] = $key2;
                                //$selected_customer_group = $key2;
                            }
                        }
                        $optionId = implode(",", $checkboxValues);
                    }

                }
            }

            if (!empty($optionId)) :
                //fetch the group id of the selected option
                //$storeId = $this->_storeManager->getStore()->getId();
                //get admin value of the selection option
                $adminValue = $this->getOptionValue($optionId);
                //$adminValue = $this->attributeModel->getOptionValueById($optionId, $storeId); //dont work
                //get group id by the option admin value
                //the option admin value should be the name of the group.

                $customerGroupId = $this->getGroupIdByGroupCode($adminValue);
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
        //$weight = 5;
        $weight = $this->catalogAttributeResource->getAttributePosition($input);
        //$this->catalogAttribute->getCollection()->addFieldToFilter('attribute_id', $input)->load();
        //return ($weight > $max_weight?$weight:$max_weight);
        return $weight;
    }

    public function getAttributeIdByCode($attribute_code)
    {
        return $this->attributeHelper->getAttributeIdByCode($attribute_code);
    }
}
