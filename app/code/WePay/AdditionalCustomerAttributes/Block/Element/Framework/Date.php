<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Block\Element\Framework;

/**
 * Date element block
 */
class Date extends \Magento\Framework\View\Element\Html\Date
{
    /**
     * Render block HTML
     *
     * @return string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _toHtml()
    {
        $html = '<input type="text" name="' . $this->getName() . '" id="' . $this->getId() . '" ';
        $html .= 'value="' . $this->escapeHtml($this->getValue()) . '" ';
        if ($this->getDisabled() == true) {
            $html .= 'disabled="disabled" ';
        }
        if ($this->getDataFormPart() != null) {
             $html .= 'data-form-part="'.$this->getDataFormPart().'" ';
        }
        $html .= 'class="' . $this->getClass() . '" ' . $this->getExtraParams() . '/> ';
        $calendarYearsRange = $this->getYearsRange();
        $changeMonth = $this->getChangeMonth();
        $changeYear = $this->getChangeYear();
        $maxDate = $this->getMaxDate();
        $showOn = $this->getShowOn();
        $firstDay = $this->getFirstDay();

        $html .= '<script type="text/javascript">
            require(["jquery", "mage/calendar"], function($){
                    $("#' .
            $this->getId() .
            '").calendar({
                        showsTime: ' .
            ($this->getTimeFormat() ? 'true' : 'false') .
            ',
                        ' .
            ($this->getTimeFormat() ? 'timeFormat: "' .
            $this->getTimeFormat() .
            '",' : '') .
            '
                        dateFormat: "' .
            $this->getDateFormat() .
            '",
                        buttonImage: "' .
            $this->getImage() .
            '",
                        ' .
            ($calendarYearsRange ? 'yearRange: "' .
            $calendarYearsRange .
            '",' : '') .
            '
                        buttonText: "' .
            (string)new \Magento\Framework\Phrase(
                'Select Date'
            ) .
            '"' . ($maxDate ? ', maxDate: "' . $maxDate . '"' : '') .
            ($changeMonth === null ? '' : ', changeMonth: ' . $changeMonth) .
            ($changeYear === null ? '' : ', changeYear: ' . $changeYear) .
            ($showOn ? ', showOn: "' . $showOn . '"' : '') .
            ($firstDay ? ', firstDay: ' . $firstDay : '') .
            '})
            });
            </script>';

        return $html;
    }
}
