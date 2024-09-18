<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
// @codingStandardsIgnoreFile

namespace WePay\AdditionalCustomerAttributes\Model\ResourceModel\Eav;

use Magento\Catalog\Model\Attribute\LockValidatorInterface;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface;

/**
 * Catalog attribute model
 *
 * @method \Magento\Catalog\Model\ResourceModel\Attribute _getResource()
 * @method \Magento\Catalog\Model\ResourceModel\Attribute getResource()
 * @method \Magento\Catalog\Model\ResourceModel\Eav\Attribute getFrontendInputRenderer()
 * @method string setFrontendInputRenderer(string $value)
 * @method int setIsGlobal(int $value)
 * @method int getSearchWeight()
 * @method int setSearchWeight(int $value)
 * @method bool getIsUsedForPriceRules()
 * @method int setIsUsedForPriceRules(int $value)
 * @method \Magento\Eav\Api\Data\AttributeExtensionInterface getExtensionAttributes()
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Attribute extends \Magento\Catalog\Model\ResourceModel\Eav\Attribute
{
    

    /**
     * Event object name
     *
     * @var string
     */
    protected $_eventObject = 'additionalcustomerattributes';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'catalog_entity_additionalcustomerattributes';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('WePay\AdditionalCustomerAttributes\Model\ResourceModel\Attribute');
    }

    /**
     * Processing object before save data
     *
     * @return \Magento\Framework\Model\AbstractModel
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function beforeSave()
    {
        $this->setData('modulePrefix', self::MODULE_NAME);
        if(in_array($this->getFrontendInput(),['checkbox','radio'])){
            if(is_array($this->getDefault())){
                $this->setDefaultValue(implode(",",$this->getDefault()));
            }else{
                $this->setDefaultValue($this->getDefault());
            }
        }

        $visible = $this->getIsVisibleInAdvancedSearch();
        parent::beforeSave();
        $this->setIsVisibleInAdvancedSearch($visible);
        return $this;
    }

    /**
     * Detect default value using frontend input type
     *
     * @param string $type frontend_input field name
     * @return string default_value field value
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getDefaultValueByInput($type)
    {
        $field = '';
        switch ($type) {
            case 'select':
            case 'gallery':
            case 'media_image':
                break;
            case 'multiselect':
                $field = null;
                break;

            case 'text':
            case 'price':
            case 'weight':
                $field = 'default_value_text';
                break;

            case 'textarea':
                $field = 'default_value_textarea';
                break;

            case 'date':
                $field = 'default_value_date';
                break;

            case 'boolean':
                $field = 'default_value_yesno';
                break;

            case 'image':
                $field = 'default_value_image';
                break;
            case 'file':
                $field = 'default_value_file';
                break;
            default:
                break;
        }

        return $field;
    }
}
