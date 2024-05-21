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

class Dependable extends \FME\AdditionalCustomerAttributes\Block\Element\Select
{
    /**
     * Return array of select options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->getCurrentAttribute()->getSource()->getAllOptions(false);
    }

    /**
     * Return array of values
     *
     * @return array
     */
    public function getValues()
    {
        $value = $this->getValue();
        return explode(',', $value);
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
        return empty($classes) ? ' required-entry ' : ' ' . implode(' ', $classes);
    }

    /**
     * Check is value selected
     *
     * @param string $value
     * @return boolean
     */
    public function isSelected($value)
    {
        return in_array($value, $this->getValues());
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
        return sprintf($format, 'fme_dvalue', $index);
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
        return sprintf($format, 'fme_dvalue', $index);
    }

    public function getIsEditable()
    {
        return true;
    }
}
