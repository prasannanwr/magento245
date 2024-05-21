<?php

namespace Prasanna\Invitecode\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class Index extends \Magento\Framework\View\Element\Template
{
    private $scopeConfig;

    public function __construct(Template\Context $context, ScopeConfigInterface $scopeConfig, array $data = [])
    {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function getMinimumAge()
    {
        return $this->scopeConfig->getValue('age_restriction/general/minimum_age', ScopeInterface::SCOPE_STORE);
    }
}
