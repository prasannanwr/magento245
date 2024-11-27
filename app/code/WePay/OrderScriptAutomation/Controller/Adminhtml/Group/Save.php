<?php
namespace WePay\OrderScriptAutomation\Controller\Adminhtml\Group;

class Save extends \Magento\Customer\Controller\Adminhtml\Group\Save
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $custom_script_mode = $this->getRequest()->getParam('custom_script_mode');

        //log
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/registrationcode.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        $logger->info("group save called ");

        $customerGroup = $this->groupRepository->getById((int)$id);
       //var_dump($customerGroup->getExtensionAttributes('custom_script_mode'));exit;
        var_dump($this->getRequest()->getParam('custom_script_mode'));exit;

//        $customerGroup->setExtensionAttributes('custom_script_mode', !empty($custom_script_mode) ? $custom_script_mode : null);
        $customerGroup->setExtensionAttributes(!empty($custom_script_mode) ? $custom_script_mode : null);
        $this->groupRepository->save($customerGroup);
        return parent::execute();
    }
}
