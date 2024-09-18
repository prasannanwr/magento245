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
namespace WePay\AdditionalCustomerAttributes\Block\Element;

/**
 * EAV entity Attribute Form Renderer Abstract Block
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
abstract class AbstractRenderer extends \Magento\Framework\View\Element\Template
{
    /**
     * Attribute instance
     *
     * @var \Magento\Eav\Model\Attribute
     */
    protected $_attribute;

    /**
     * EAV Entity Model
     *
     * @var \Magento\Framework\Model\AbstractModel
     */
    protected $_entity;

    /**
     * Format for HTML elements id attribute
     *
     * @var string
     */
    protected $_fieldIdFormat = '%1$s';

    /**
     * Format for HTML elements name attribute
     *
     * @var string
     */
    protected $_fieldNameFormat = '%1$s';

    /**
     * Set attribute instance
     *
     * @param \Magento\Eav\Model\Attribute $attribute
     * @return $this
     */
    public function setCurrentAttribute($attribute)
    {
        $this->_attribute = $attribute;
        return $this;
    }

    /**
     * Return Attribute instance
     *
     * @return \Magento\Eav\Model\Attribute
     */
    public function getCurrentAttribute()
    {
        return $this->_attribute;
    }

    /**
     * Set Entity object
     *
     * @param \Magento\Framework\Model\AbstractModel $entity
     * @return $this
     */
    public function setEntity(\Magento\Framework\Model\AbstractModel $entity)
    {
        $this->_entity = $entity;
        return $this;
    }

    /**
     * Return Entity object
     *
     * @return \Magento\Framework\Model\AbstractModel
     */
    public function getEntity()
    {
        return $this->_entity;
    }

    /**
     * Return Data Form Filter or false
     *
     * @return \Magento\Framework\Data\Form\Filter\FilterInterface|false
     */
    protected function _getFormFilter()
    {
        $filterCode = $this->getCurrentAttribute()->getInputFilter();
        if ($filterCode) {
            $filterClass = 'Magento\\Framework\\Data\\Form\\Filter\\' . ucfirst($filterCode);
            if ($filterCode == 'date') {
                $format = $this->_localeDate->getDateFormat(
                    \IntlDateFormatter::SHORT
                );
                $filter = new $filterClass($format);
            } else {
                $filter = new $filterClass();
            }
            return $filter;
        }
        return false;
    }

    /**
     * Apply output filter to value
     *
     * @param string $value
     * @return string
     */
    protected function _applyOutputFilter($value)
    {
        $filter = $this->_getFormFilter();
        if ($filter) {
            $value = $filter->outputFilter($value);
        }

        return $value;
    }

    /**
     * Return validate class by attribute input validation rule
     *
     * @return string|false
     */
    protected function _getInputValidateClass()
    {
        $class = $this->getCurrentAttribute()->getFrontendClass();
        return $class;
    }

    /**
     * Return array of validate classes
     *
     * @param bool $withRequired
     * @return array
     */
    protected function _getValidateClasses($withRequired = true)
    {
        $classes = [];
        if ($withRequired && $this->isRequired()) {
            $classes[] = 'required-entry';
        }
        $inputRuleClass = $this->_getInputValidateClass();
        if ($inputRuleClass) {
            $classes[] = $inputRuleClass;
        }
        return $classes;
    }

    /**
     * Return original entity value
     * Value didn't escape and filter
     *
     * @return string
     */
    public function getValue()
    {
        $value = $this->getCurrentAttribute()->hasData('customer_value')  && $this->getCurrentAttribute()->getCustomerValue() != null?
            $this->getCurrentAttribute()->getCustomerValue():$this->getCurrentAttribute()->getDefaultValue();
        return $value;
    }

    /**
     * Return HTML id for element
     *
     * @param string|null $index
     * @return string
     */
    public function getAttributeId($index = null)
    {
        $format = $this->_fieldIdFormat;
        if ($index !== null) {
            $format .= '_%2$s';
        }
        return sprintf($format, $this->getCurrentAttribute()->getAttributeCode(), $index);
    }

    /**
     * Return HTML id for element
     *
     * @param string|null $index
     * @return string
     */
    public function getAttributeName($index = null)
    {
        $format = $this->_fieldNameFormat;
        if ($index !== null) {
            $format .= '[%2$s]';
        }
        return sprintf($format, 'wepay_'.$this->getCurrentAttribute()->getAttributeCode(), $index);
    }

    /**
     * Return HTML class attribute value
     * Validate and rules
     *
     * @return string
     */
    public function getValidateClasses()
    {
        $classes = $this->_getValidateClasses(true);
        return empty($classes) ? '' : ' ' . implode(' ', $classes);
    }

    /**
     * Check is attribute value required
     *
     * @return bool
     */
    public function isRequired()
    {
        return $this->getCurrentAttribute()->getIsRequired();
    }

    /**
     * Return attribute label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->getCurrentAttribute()->getStoreLabel();
    }

    /**
     * Return attribute Notice
     *
     * @return string
     */
    public function getTooltip()
    {
        return $this->getCurrentAttribute()->getNote();
    }
    /**
     * Is the field editable
     *
     * @return string
     */
    public function getIsEditable()
    {
        $config = $this->getCurrentAttribute()->getIsVisibleInAdvancedSearch();
        $customerValue = $this->getValue();
        if ($config == 0) {
            return false;
        } elseif ($config == 1) {
            return true;
        } elseif ($config == 2 && $this->getCurrentAttribute()->getDefaultValue() == $customerValue ) {
            return true;
        } elseif ($config == 3 && $customerValue == '') {
            return true;
        } elseif ($config == 4 && ($customerValue == '' || $customerValue == $this->getCurrentAttribute()->getDefaultValue())) {
            return true;
        }
        return false;
    }

}
