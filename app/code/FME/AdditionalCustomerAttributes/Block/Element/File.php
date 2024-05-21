<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\AdditionalCustomerAttributes\Block\Element;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;

/**
 * EAV Entity Attribute Form Renderer Block for File
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class File extends \FME\AdditionalCustomerAttributes\Block\Element\AbstractRenderer
{
    /**
     * @var \Magento\Framework\Url\EncoderInterface
     */
    private $urlEncoder;
    /**
     * @var \Magento\Framework\Url\EncoderInterface
     */
    private $storeManager;

    /**
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->urlEncoder = $urlEncoder;
        $this->storeManager  = $storeManager;
        parent::__construct($context, $data);
    }
    /**
     * Return escaped value
     *
     * @return string
     */
    public function getEscapedValue()
    {
        if ($this->getValue()) {
            if ($this->storeManager->getStore()->getId() > 0) {
                return $this->escapeHtml(
                    $this->storeManager->getStore()->getBaseUrl(). "pub/media/aca/"
                )."image/".$this->getValue();
            } else {
                return $this->escapeHtml(
                    $this->storeManager->getStore(1)->getBaseUrl(). "pub/media/aca/"
                )."image/".$this->getValue();
            }
        }
        return '';
    }

    /**
     * Return escaped value
     *
     * @return string
     */
    public function getAllowedFileExtensions()
    {
        return $this->getCurrentAttribute()->getFmeExtensions();
    }
    /**
     * Return escaped value
     *
     * @return string
     */
    public function getMaxSize()
    {
        return $this->getCurrentAttribute()->getFmeMaxSize();
    }
}
