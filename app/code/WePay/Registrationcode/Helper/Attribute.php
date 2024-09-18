<?php
namespace WePay\Registrationcode\Helper;

use Magento\Customer\Model\GroupFactory;
use Magento\Eav\Model\Config;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Attribute extends AbstractHelper
{
    protected $groupFactory;
    public function __construct(Context $context, Config $eavConfig, GroupFactory $groupFactory)
    {
        parent::__construct($context);
        $this->eavConfig = $eavConfig;
        $this->groupFactory = $groupFactory;
    }

    public function getAttributeCodeById($attributeId, $entityType = 'additionalcustomerattributes')
    {
        $attribute = $this->eavConfig->getAttribute($entityType, $attributeId);
        return $attribute->getAttributeCode();
    }

    public function getAttributeIdByCode($attribute_code, $entityType = 'additionalcustomerattributes')
    {
        $attribute = $this->eavConfig->getAttribute($entityType, $attribute_code);
        return $attribute ? $attribute->getId() : null;
    }

    public function getGroupIdByGroupCode($groupCode)
    {
        $group = $this->groupFactory->create()->load($groupCode, 'customer_group_code');
        if ($group->getId()) {
            return $group->getId();
        }
        return null; // Return null if group not found
    }
}
