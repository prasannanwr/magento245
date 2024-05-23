<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Prasanna\Invitecode\Controller\Adminhtml\Invitecode;

class Delete extends \Prasanna\Invitecode\Controller\Adminhtml\Invitecode
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
        $id = $this->getRequest()->getParam('invite_code_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Prasanna\Invitecode\Model\InviteCode::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Invite Code.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['invite_code_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Invite Code to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}

