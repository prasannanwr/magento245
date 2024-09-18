<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Block\Adminhtml\Attribute\Edit\Options;

use Magento\Store\Model\ResourceModel\Store\Collection;

class Options extends \Magento\Eav\Block\Adminhtml\Attribute\Edit\Options\Options
{
    /**
     * @var string
     */
    protected $_template = 'WePay_AdditionalCustomerAttributes::attribute/options.phtml';

    protected $attributeRepository;

    public function __construct(\Magento\Backend\Block\Template\Context $context, \Magento\Framework\Registry $registry, \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory, \Magento\Framework\Validator\UniversalFactory $universalFactory, \Magento\Eav\Block\Adminhtml\Attribute\Edit\Main\AbstractMain $attributeRepository, array $data = [])
    {
        $this->attributeRepository = $attributeRepository;
        parent::__construct($context, $registry, $attrOptionCollectionFactory, $universalFactory, $data);
    }

    public function beforeToHtml()
    {
        $subject->setTemplate('WePay_AdditionalCustomerAttributes::attribute/options.phtml');
    }

    /*
     * Custom added for retrieving the current attribute code for the ajax call
     * by prasanna
    */
    public function getCurrentAttributeCode()
    {
        $attribute = $this->attributeRepository->getAttributeObject();
        return $attribute->getAttributeCode();
    }
}
