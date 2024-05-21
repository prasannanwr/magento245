<?php
namespace Prasanna\Invitecode\Controller\Adminhtml\Ajax;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultFactory;

class Index extends Context implements CsrfAwareActionInterface
{
    protected $resultJsonFactory;

//    public function __construct(Context $context, JsonFactory $resultJsonFactory)
//    {
//        $this->resultJsonFactory = $resultJsonFactory;
//        parent::__construct($context);
//    }
    public function __construct(\Magento\Framework\App\RequestInterface $request, \Magento\Framework\App\ResponseInterface $response, \Magento\Framework\ObjectManagerInterface $objectManager, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\Framework\UrlInterface $url, \Magento\Framework\App\Response\RedirectInterface $redirect, \Magento\Framework\App\ActionFlag $actionFlag, \Magento\Framework\App\ViewInterface $view, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory, ResultFactory $resultFactory, \Magento\Backend\Model\Session $session, \Magento\Framework\AuthorizationInterface $authorization, \Magento\Backend\Model\Auth $auth, \Magento\Backend\Helper\Data $helper, \Magento\Backend\Model\UrlInterface $backendUrl, \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator, \Magento\Framework\Locale\ResolverInterface $localeResolver, JsonFactory $resultJsonFactory, $canUseBaseUrl = false)
    {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($request, $response, $objectManager, $eventManager, $url, $redirect, $actionFlag, $view, $messageManager, $resultRedirectFactory, $resultFactory, $session, $authorization, $auth, $helper, $backendUrl, $formKeyValidator, $localeResolver, $canUseBaseUrl);
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

        return $resultJson->setData([
            'success' => true,
            'resultData' => 'test'
        ]);
    exit;
        //$data = $this->getRequest()->getParam('data');

        /* Custom added for retrieving the Weightage value of items */
        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        $attributeRepository = $objectManager->create('\Magento\Eav\Block\Adminhtml\Attribute\Edit\Main\AbstractMain');
        $optionCollection = $objectManager->create('\Prasanna\Invitecode\Model\ResourceModel\Attribute\Collection');
        $attribute = $attributeRepository->getAttributeObject();
        $currentAttributeCode = $attribute->getAttributeCode();
        $optionCollection = $optionCollection->addAttributeToFilter('attribute_code', $currentAttributeCode);
        $optionArr = array();
        foreach ($optionCollection as $option):
            $item = array("id" => $option->getData('option_id'),
                "weight" => $option->getData('weightage'));
            array_push($optionArr, $item);
        endforeach;
        //var_dump($optionArr);exit;

        return $resultJson->setData([
            'success' => true,
            'resultData' => '1'
        ]);
        exit;

    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Prasanna_Invitecode::ajax');
    }

}
