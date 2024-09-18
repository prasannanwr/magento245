<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Controller\Adminhtml\Attribute;

class Delete extends \WePay\AdditionalCustomerAttributes\Controller\Adminhtml\Attribute
{
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('attribute_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $model = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute');

            // entity type check
            $model->load($id);
            if ($model->getEntityTypeId() != $this->entityTypeId) {
                $this->messageManager->addError(__('We can\'t delete the attribute.'));
                return $resultRedirect->setPath('additionalcustomerattributes/*/');
            }

            try {
                $model->delete();
                $this->messageManager->addSuccess(__('You have successfully deleted the attribute.'));
                return $resultRedirect->setPath('additionalcustomerattributes/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath(
                    'additionalcustomerattributes/*/edit',
                    ['attribute_id' => $this->getRequest()->getParam('attribute_id')]
                );
            }
        }
        $this->messageManager->addError(__('We can\'t find an attribute to delete.'));
        return $resultRedirect->setPath('additionalcustomerattributes/*/');
    }
    
    /**
     * Determine if authorized to perform group actions.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('WePay_AdditionalCustomerAttributes::attributes_delete');
    }
}
