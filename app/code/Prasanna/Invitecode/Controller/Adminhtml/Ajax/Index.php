<?php
namespace Prasanna\Invitecode\Controller\Adminhtml\Ajax;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Index extends Action implements CsrfAwareActionInterface
{
    protected $resultJsonFactory;

    public function __construct(Context $context, JsonFactory $resultJsonFactory)
    {
        $this->resultJsonFactory = $resultJsonFactory;
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
//        return $resultJson->setData([
//            'success' => true,
//            'resultData' => $optionArr
//        ]);
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
        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        //$attributeRepository = $objectManager->create('\Magento\Eav\Block\Adminhtml\Attribute\Edit\Main\AbstractMain');
        $optionCollection = $objectManager->create('\Prasanna\Invitecode\Model\ResourceModel\Attribute\Collection');
        //$attribute = $attributeRepository->getAttributeObject();

        $optionCollection = $optionCollection->addAttributeToFilter('attribute_code', $attribute_code);
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
        return $this->_authorization->isAllowed('Prasanna_Invitecode::ajax');
    }

}
