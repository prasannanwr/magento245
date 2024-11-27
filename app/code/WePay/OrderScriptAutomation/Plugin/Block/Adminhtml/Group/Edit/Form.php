<?php
namespace WePay\OrderScriptAutomation\Plugin\Block\Adminhtml\Group\Edit;

use WePay\OrderScriptAutomation\Model\ResourceModel\CustomerGroupExtension;

class Form
{
    protected $customerGroupExtension;

    public function __construct(CustomerGroupExtension $customerGroupExtension) {
        $this->customerGroupExtension = $customerGroupExtension;
    }
    public function aroundGetFormHtml(
        \Magento\Customer\Block\Adminhtml\Group\Edit\Form $subject,
        \Closure $proceed
    )
    {
        $form = $subject->getForm();

        foreach ($form->getElements() as $element) {
            if($element->getId() == 'id') {
                $group_id = $element->getValue();
            }
        }
        if (is_object($form))
        {
            $fieldset = $form->getElement('base_fieldset');
            if ($fieldset) {
                $fieldset->addField(
                    'custom_script_mode',
                    'select',
                    [
                        'name' => 'custom_script_mode',
                        'label' => __('Custom Script Mode'),
                        'title' => __('Custom Script Mode'),
                        'required' => true,
                        'values' => $this->modeOptionsArray()
                    ]
                );
                //$subject->setForm($form);
            }
        }

        $field = $form->getElement('custom_script_mode');
        if ($field) {
            $group_value = $this->customerGroupExtension->getCustomScriptMode($group_id);
            // Set the desired value (assuming '2' is the value to set as selected)
            $field->setValue($group_value);
        }
        return $proceed();
    }

    public function modeOptionsArray()
    {
        return [
            ['value' => '1', 'label' => __('Manual')],
            ['value' => '2', 'label' => __('Automatic')]
        ];
    }
}
