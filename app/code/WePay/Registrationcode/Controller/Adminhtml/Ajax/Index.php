<?php
namespace WePay\Registrationcode\Controller\Adminhtml\Ajax;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use WePay\Registrationcode\Model\ResourceModel\Attribute\Collection;

class Index extends Action implements CsrfAwareActionInterface
{
    protected $resultJsonFactory;

    protected $optionCollection;

    public function __construct(Context $context, JsonFactory $resultJsonFactory, Collection $optionCollection)
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->optionCollection = $optionCollection;
        parent::__construct($context);
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $data = $this->getRequest()->getParam('attribute_code');
        $response = $this->getAttributeOptionsData($data);
        return $resultJson->setData([
            $response
        ]);
    }

    /*
     * Weightage value of items
     * table attribute_weight
     */
    public function getAttributeOptionsData($attribute_code)
    {
        $optionCollection = $this->optionCollection->addAttributeToFilter('attribute_code', $attribute_code);
        $optionArr = array();
        foreach ($optionCollection as $option):
            $item = array("id" => $option->getData('option_id'),
                "weight" => $option->getData('weightage'),
                "entity_id" => $option->getData('entity_id'));
            array_push($optionArr, $item);
        endforeach;
        return $optionArr;

    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('WePay_Registrationcode::ajax');
    }

}
