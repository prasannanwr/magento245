<?php
namespace Prasanna\Invitecode\Helper;

use Magento\Eav\Model\Config;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Attribute extends AbstractHelper
{
    public function __construct(Context $context, Config $eavConfig)
    {
        parent::__construct($context);
        $this->eavConfig = $eavConfig;
    }

    public function getAttributeCodeById($attributeId, $entityType = 'additionalcustomerattributes')
    {
        $attribute = $this->eavConfig->getAttribute($entityType, $attributeId);
        return $attribute->getAttributeCode();
    }
}
