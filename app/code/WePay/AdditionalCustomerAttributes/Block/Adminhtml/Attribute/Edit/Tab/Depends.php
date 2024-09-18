<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Catalog\Model\Entity\Attribute;
use Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class Depends extends Generic
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
     * @var Dependable Attributes
     */
    private $dependableAttributes;
    /**
     * @var Dependable Attributes
     */
    private $dependableOptions;

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
        \WePay\AdditionalCustomerAttributes\Model\CustomerValues $dependableAttributes,
        FormFactory $formFactory,
        Yesno $yesNo,
        PropertyLocker $propertyLocker,
        array $data = []
    ) {
        $this->yesNo = $yesNo;
        $this->dependableAttributes = $dependableAttributes;
        $this->dependableOptions = $this->dependableAttributes->getDependableAttributes();
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

        
        $depAttributes = [];
        foreach ($this->dependableOptions as $dependableOption) {
            $depAttributes[$dependableOption['attribute_id']] = $dependableOption['frontend_label'];
        }
        if (!empty($depAttributes)) :
            if ($attributeObject->getId()) {
                unset($depAttributes[$attributeObject->getId()]);
            }
            //echo "<pre>";print_r($depAttributes);exit;
            $yesnoSource = $this->yesNo->toOptionArray();

            $fieldset = $form->addFieldset(
                'front_fieldset',
                ['legend' => __('Dependable Properties'), 'collapsable' => $this->getRequest()->has('popup')]
            );
            

            $this->_eventManager->dispatch(
                'additionalcustomerattributes_attribute_form_build_depends_before',
                ['form' => $form]
            );
            
            $fieldset->addField(
                'wepay_dependable',
                'select',
                [
                    'name' => 'wepay_dependable',
                    'label' => __('Is Dependent'),
                    'title' => __('Is Dependent'),
                    'values' => $yesnoSource
                ]
            );

            $fieldset->addField(
                'wepay_did',
                'select',
                [
                    'name' => 'wepay_did',
                    'label' => __('Select Guardian Attribute'),
                    'title' => __('Select Guardian Attribute'),
                    'values' => $depAttributes,
                    'note' => __('Please avoid creating deadlocks, make sure the dependable parent/child relationship doesn\'t form a loop but a tree structure, Moreover, please try to set the position of the dependables accordingly for better visual.'),
                ]
            );
            
            $this->setChild(
               'form_after',
               $this->getLayout()->createBlock(
                   'Magento\Backend\Block\Widget\Form\Element\Dependence'
               )
               ->addFieldMap(
                   "wepay_dependable",
                   'dependable'
               )
               ->addFieldMap(
                   "wepay_did",
                   'dependent'
               )
               ->addFieldDependence(
                   'dependent',
                   'dependable',
                   '1'
               )
            );
            $this->_eventManager->dispatch(
                'additionalcustomerattributes_attribute_form_build_depends_after',
                ['form' => $form, 'attribute' => $attributeObject, 'object' => $this]
            );
        else:
            $fieldset = $form->addFieldset(
                'front_fieldset',
                ['legend' => __('No Dependable Attributes Available'), 'collapsable' => $this->getRequest()->has('popup')]
            );
            $fieldset->addField(
                'wepay_dependable',
                'hidden',
                [
                    'name' => 'wepay_dependable',
                    'label' => __('Is Dependent'),
                    'title' => __('Is Dependent'),
                    'value' => 0
                ]
            );
        endif;

        

        $this->setForm($form);
        $form->setValues($attributeObject->getData());
        $this->propertyLocker->lock($form);
        return parent::_prepareForm();
    }
    /**
     * Prepare the layout.
     *
     * @return $this
     */
    public function getFormHtml()
    {
        $attributeObject = $this->_coreRegistry->registry('entity_attribute');
        $html = parent::getFormHtml();
        $html .= $this->getLayout()->createBlock(
            'WePay\AdditionalCustomerAttributes\Block\Adminhtml\Attribute\Edit\Tab\DependableOptions'
        )->setCurrentAttribute($attributeObject)
        ->setTemplate('WePay_AdditionalCustomerAttributes::dependableOptions.phtml')->toHtml();
        return $html;
    }
}
