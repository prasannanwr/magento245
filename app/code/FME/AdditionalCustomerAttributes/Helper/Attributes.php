<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 � fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\AdditionalCustomerAttributes\Helper;

class Attributes extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    private $storeManager;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    private $eavAttributeRepository;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * Helper
     *
     * @var \FME\AdditionalCustomerAttributes\Helper\Data
     */
    private $helper;
    /**
     * CustomerValues
     *
     * @var \FME\AdditionalCustomerAttributes\Model\CustomerValues
     */
    private $customerValues;
    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \FME\AdditionalCustomerAttributes\Helper\Data $helper,
        \FME\AdditionalCustomerAttributes\Model\ResourceModel\Attribute\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session\Proxy $customerSession,
        \FME\AdditionalCustomerAttributes\Model\CustomerValues $customerValues,
        \Magento\Eav\Api\AttributeRepositoryInterface $eavAttributeRepository
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->storeManager      = $storeManager;
        $this->helper            = $helper;
        $this->customerSession   = $customerSession;
        $this->customerValues    = $customerValues;
        $this->eavAttributeRepository = $eavAttributeRepository;
        parent::__construct($context);
    }

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->helper->getStatus();
    }//end getConfig()

    public function getAllAttributes($page = 1, $withCustomerVaues = false, $customerId = 0, $onlyEmail = false)
    {
        $groupId = 0;
        if ($this->customerSession->isLoggedIn()) {
            $groupId = $this->customerSession->getCustomer()->getGroupId();
            $customerId = $this->customerSession->getCustomer()->getId();
        }
        $collection = $this->collectionFactory->create()
            ->addStoreFilters($this->storeManager->getStore()->getId())
            ->addCustomerGroupFilter($groupId);
        if ($withCustomerVaues == true && $customerId > 0) {
            $collection->getCustomerValue($customerId);
        }
        if ($page > 1) {
            $collection->addPageFilter($page);
        }
        if ($onlyEmail == true) {
            $collection->applyEmailFilter();
        }
        return $collection->applySort();
    }
    public function getAllAttributesForAdmin($withCustomerVaues = true, $customerId = 0, $onlyEmail = false,$groupId = 0)
    {
        $collection = $this->collectionFactory->create()
            ->addStoreFilters($this->storeManager->getStore()->getId());
        if ($groupId > 0) {
            $collection->addCustomerGroupFilter($groupId);
        }
        if ($withCustomerVaues == true && $customerId > 0) {
            $collection->getCustomerValue($customerId);
        }
        if ($onlyEmail == true) {
            $collection->applyEmailFilter();
        }
        return $collection->applySort();
    }

    public function getCurrentStoreLabel($attribute)
    {
        $storeLabels = $attribute->getStoreLabels();
        if (!empty($storeLabels) && isset($storeLabels[$this->storeManager->getStore()->getId()])) {
            return $storeLabels[$this->storeManager->getStore()->getId()];
        }
        return $attribute->getFrontendLabel();
    }
    public function getValue($attribute)
    {
        $value = $attribute->hasData('customer_value') && $attribute->getCustomerValue() != null?
            $attribute->getCustomerValue():$attribute->getDefaultValue();
        return $value;
    }

    public function getOptionValueById($optionId, $storeId = 0)
    {
        return $this->customerValues->getOptionValueById($optionId, $storeId);
    }

    public function getAttributeOptions($attributeCode, $mode = 0)
    {
        $attribute = $this->eavAttributeRepository->get(
            \FME\AdditionalCustomerAttributes\Model\Attribute::ENTITY,
            $attributeCode
        );
        $attribute->setStoreId($this->storeManager->getStore()->getId());
        $options = $attribute->getSource()->getAllOptions(false);
        $optionLabel = [];
        foreach ($options as $value) {
            if ($mode == 0) {
                $optionLabel[$value['value']] = $value['label'];
            } elseif ($mode == 1) {
                $optionLabel[] = ['value' => $value['label'], 'label' => $value['label']];
            } else {
                $optionLabel[] = ['value' => $value['value'], 'label' => $value['label']];
            }
        }
        if ($attribute->getFrontendInput() == "boolean") {
            $optionLabel = [];
            if ($mode == 0) {
                $optionLabel[0] = __('No');
                $optionLabel[1] = __('Yes');
            } elseif ($mode == 1) {
                $optionLabel[] = ['value' => __('No'), 'label' => __('No')];
                $optionLabel[] = ['value' => __('Yes'), 'label' => __('Yes')];
            } else {
                $optionLabel[] = ['value' => 0, 'label' => __('No')];
                $optionLabel[] = ['value' => 1, 'label' => __('Yes')];
            }
        }
        return $optionLabel;
    }
}
