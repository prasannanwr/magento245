<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\AdditionalCustomerAttributes\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Catalog\Model\Entity\Attribute;
use Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class Front extends Generic
{
    /**
     * @var Yesno
     */
    private $yesNo;

    /**
     * @var PropertyLocker
     */
    private $propertyLocker;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Yesno $yesNo
     * @param PropertyLocker $propertyLocker
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Yesno $yesNo,
        PropertyLocker $propertyLocker,
        array $data = []
    ) {
        $this->yesNo = $yesNo;
        $this->propertyLocker = $propertyLocker;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * {@inheritdoc}
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /** @var Attribute $attributeObject */
        $attributeObject = $this->_coreRegistry->registry('entity_attribute');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $yesnoSource = $this->yesNo->toOptionArray();

        $fieldset = $form->addFieldset(
            'front_fieldset',
            ['legend' => __('Storefront Properties'), 'collapsable' => $this->getRequest()->has('popup')]
        );
        $fieldset->addField(
            'is_visible_on_front',
            'select',
            [
                'name' => 'is_visible_on_front',
                'label' => __('Enabled'),
                'title' => __('Enabled'),
                'values' => $yesnoSource
            ]
        );

        $scopes = [
            1 => __('Both'),
            2 => __('Registration Page Only'),
            3 => __('Account Page Only')
        ];

        $fieldset->addField(
            'is_global',
            'select',
            [
                'name' => 'is_global',
                'label' => __('Show on'),
                'title' => __('Show on'),
                'values' => $scopes
            ]
        );
        $fieldset->addField(
            'position',
            'text',
            [
                'name' => 'position',
                'label' => __('Position'),
                'title' => __('Position'),
                'required' => true,
                'note' => __(
                    'This will be used to sort the fields, if the field is dependent better to set the position after its parent field.'
                ),
                'class' => 'validate-digits'
            ]
        );
        $fieldset->addField(
            'note',
            'text',
            [
                'name' => 'note',
                'label' => __('Notice / Comments'),
                'title' => __('Notice / Comments'),
                'required' => false,
                'note' => __(
                    'Enter content to show additional information for this  attribute, leave empty to hide'
                ),
                'class' => ''
            ]
        );
        $hide = [
            1 => __('No'),
            0=> __('Yes'),
        ];
        $fieldset->addField(
            'is_searchable',
            'select',
            [
                'name' => 'is_searchable',
                'label' => __('Hide Field'),
                'title' => __('Hide Field'),
                'values' => $hide,
                'note' => __(
                    'Do you want to hide the field once customer has entered/selected the values. Admin can still see it.'
                ),
            ]
        );
        $editable = [
            1 => 'Yes',
            2 => 'If Default values',
            3 => 'If Empty values',
            4 => 'If Default / Empty values',
            0 => 'No'
        ];
        $fieldset->addField(
            'is_visible_in_advanced_search',
            'select',
            [
                'name' => 'is_visible_in_advanced_search',
                'label' => __('Editable by customers'),
                'title' => __('Editable by customers'),
                'values' => $editable,
                'note' => __(
                    'This will work if the field is not set as hidden.'
                ),
            ]
        );

        $fieldset->addField(
            'is_comparable',
            'hidden',
            [
                'name' => 'is_comparable',
                'label' => __('Comparable on Storefront'),
                'title' => __('Comparable on Storefront'),
                'value' => $attributeObject->getData('is_comparable') ?: 0,
            ]
        );

        $this->_eventManager->dispatch(
            'additionalcustomerattributes_attribute_form_build_front_tab',
            ['form' => $form]
        );

        $fieldset->addField(
            'is_used_for_promo_rules',
            'hidden',
            [
                'name' => 'is_used_for_promo_rules',
                'label' => __('Use for Promo Rule Conditions'),
                'title' => __('Use for Promo Rule Conditions'),
                'value' => $attributeObject->getData('is_used_for_promo_rules') ?: 0,
            ]
        );

        $fieldset->addField(
            'is_wysiwyg_enabled',
            'hidden',
            [
                'name' => 'is_wysiwyg_enabled',
                'label' => __('Enable WYSIWYG'),
                'title' => __('Enable WYSIWYG'),
                'value' => $attributeObject->getData('is_wysiwyg_enabled') ?: 0,
            ]
        );

        $fieldset->addField(
            'is_html_allowed_on_front',
            'hidden',
            [
                'name' => 'is_html_allowed_on_front',
                'label' => __('Allow HTML Tags on Storefront'),
                'title' => __('Allow HTML Tags on Storefront'),
                'value' => $attributeObject->getData('is_html_allowed_on_front') ?: 0,
            ]
        );

        $fieldset->addField(
            'used_in_product_listing',
            'hidden',
            [
                'name' => 'used_in_product_listing',
                'label' => __('Used in Product Listing'),
                'title' => __('Used in Product Listing'),
                'note' => __('Depends on design theme.'),
                'value' => $attributeObject->getData('used_in_product_listing') ?: 0,
            ]
        );

        $fieldset->addField(
            'used_for_sort_by',
            'hidden',
            [
                'name' => 'used_for_sort_by',
                'label' => __('Used for Sorting in Product Listing'),
                'title' => __('Used for Sorting in Product Listing'),
                'note' => __('Depends on design theme.'),
                'value' => $attributeObject->getData('used_for_sort_by') ?: 0,
            ]
        );

        /* custom added for weightage of inputs by prasanna */
        $fieldset->addField(
            'input_weight',
            'text',
            [
                'name' => 'input_weight',
                'label' => __('Weight'),
                'title' => __('Weight'),
                'required' => true,
                'note' => __(
                    'Weight of the input field'
                ),
                'class' => ''
            ]
        );

        $hide = [
            1 => __('Yes'),
            0=> __('No'),
        ];
        $fieldset->addField(
            'is_customer_group',
            'select',
            [
                'name' => 'is_customer_group',
                'label' => __('Assigned to Customer Group'),
                'title' => __('Assigned to Customer Group'),
                'values' => $hide,
                'note' => __(
                    'Select yes if the input has options that are assigned to Customer group'
                ),
            ]
        );

        $fieldset->addField(
            'has_code',
            'select',
            [
                'name' => 'has_code',
                'label' => __('Input has code assigned'),
                'title' => __('Input has code assigned'),
                'values' => $hide,
                'note' => __(
                    'Select yes if the input has code assigned to it'
                ),
            ]
        );

        $this->_eventManager->dispatch(
            'adminhtml_catalog_product_attribute_edit_frontend_prepare_form',
            ['form' => $form, 'attribute' => $attributeObject]
        );

        $this->setForm($form);
        $form->setValues($attributeObject->getData());
        $this->propertyLocker->lock($form);
        return parent::_prepareForm();
    }
}
