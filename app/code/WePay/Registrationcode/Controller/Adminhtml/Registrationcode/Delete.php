<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace WePay\Registrationcode\Controller\Adminhtml\Registrationcode;

class Delete extends \WePay\Registrationcode\Controller\Adminhtml\Registrationcode
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('registration_code_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\WePay\Registrationcode\Model\Registrationcode::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Registration Code.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['registration_code_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Registration Code to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

