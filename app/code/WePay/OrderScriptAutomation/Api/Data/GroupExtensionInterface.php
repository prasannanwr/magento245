<?php

namespace WePay\OrderScriptAutomation\Api\Data;

use Magento\Framework\Api\ExtensionAttributesInterface;

interface GroupExtensionInterface extends ExtensionAttributesInterface
{
    /**
     * Get custom field value
     *
     * @return string|null
     */
    public function getCustomScriptMode();

    /**
     * Set custom field value
     *
     * @param string $customField
     * @return $this
     */
    public function setCustomScriptMode($customField);
}
