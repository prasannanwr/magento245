<?php
namespace Prasanna\Invitecode\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AdminAttributeSaveAfter implements ObserverInterface
{
    private $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function execute(Observer $observer)
    {
        // TODO: Implement execute() method.
        print("attribute save after called");
        exit;
    }
}
