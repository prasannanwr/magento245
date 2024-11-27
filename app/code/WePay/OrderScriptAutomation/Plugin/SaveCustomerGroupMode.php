<?php
namespace WePay\OrderScriptAutomation\Plugin;

use Magento\Customer\Api\Data\GroupInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use WePay\OrderScriptAutomation\Model\ResourceModel\CustomerGroupExtension;

class SaveCustomerGroupMode
{
    protected $customerGroupExtension;
    protected $request;

    public function __construct(CustomerGroupExtension $customerGroupExtension,RequestInterface $request)
    {
        $this->customerGroupExtension = $customerGroupExtension;
        $this->request = $request;
    }

//    public function afterGetExtensionAttributes(
//        GroupRepositoryInterface $subject, GroupInterface $customerGroup
//    ) {
//        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/registrationcode.log');
//        $logger = new \Zend_Log();
//        $logger->addWriter($writer);
//        $logger->info("after get extenstion plgin called");
//    }

    public function afterGet(GroupRepositoryInterface $subject, GroupInterface $customerGroup)
    {
        $extensionAttributes = $customerGroup->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionAttributesFactory->create();
        }
        $customFieldValue = $this->customerGroupExtension->getCustomScriptMode($customerGroup->getId());
        $extensionAttributes->setCustomScriptMode($customFieldValue);
        $customerGroup->setExtensionAttributes($extensionAttributes);
        return $customerGroup;
    }

    public function beforeSave(GroupRepositoryInterface $subject, GroupInterface $customerGroup)
    {
        $extensionAttributes = $customerGroup->getExtensionAttributes();
        //$value = $extensionAttributes->getCustomScriptMode();
        $value = $this->request->getParam('custom_script_mode');
        //if ($extensionAttributes && $extensionAttributes->getCustomScriptMode()) {
            $this->customerGroupExtension->saveCustomScriptMode(
                $customerGroup->getId(),
                $value
            );
        //}
    }
}
