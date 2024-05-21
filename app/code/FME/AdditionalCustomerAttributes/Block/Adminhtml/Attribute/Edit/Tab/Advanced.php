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

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker;
use Magento\Eav\Helper\Data;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\System\Store;
use Magento\Customer\Model\Customer\Source\Group;
use Magento\Store\Model\StoreManagerInterface;

class Advanced extends \Magento\Catalog\Block\Adminhtml\Product\Attribute\Edit\Tab\Advanced
{

    /**
     * @var PropertyLocker
     */
    private $propertyAttributeLocker;
    
    /**
     * @var PropertyLocker
     */
    private $systemStore;
    /**
     * @var PropertyLocker
     */
    private $customerGroup;
    /**
     * @var PropertyLocker
     */
    private $storeManager;

    /**
     * Adding product form elements for editing attribute
     *
     * @return $this
     * @SuppressWarnings(PHPMD)
     */
    protected function _prepareForm()
    {
        $attributeObject = $this->getAttributeObject();
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset(
            'advanced_fieldset',
            ['legend' => __('Advanced Attribute Properties'), 'collapsable' => false]
        );

        $yesno = $this->_yesNo->toOptionArray();

        $validateClass = sprintf(
            'validate-code validate-length maximum-length-%d',
            \Magento\Eav\Model\Entity\Attribute::ATTRIBUTE_CODE_MAX_LENGTH
        );
        $fieldset->addField(
            'attribute_code',
            'text',
            [
                'name' => 'attribute_code',
                'label' => __('Attribute Code'),
                'title' => __('Attribute Code'),
                'required' => true,
                'note' => __(
                    'This is used internally. Make sure you don\'t use spaces or more than %1 symbols.',
                    \Magento\Eav\Model\Entity\Attribute::ATTRIBUTE_CODE_MAX_LENGTH
                ),
                'class' => $validateClass
            ]
        );

        $fieldset->addField(
            'default_value_text',
            'text',
            [
                'name' => 'default_value_text',
                'label' => __('Default Value'),
                'title' => __('Default Value'),
                'value' => $attributeObject->getDefaultValue()
            ]
        );
        $url = $this->getStoreUrl();
        
        $class = "";
        if ($attributeObject->getDefaultValue() =='') {
            $class = "display:none;";
        }
        $fieldset->addField(
            'default_value_file',
            'file',
            [
                'name' => 'default_value_file',
                'label' => __('Default File'),
                'title' => __('Default File'),
                'value' => $attributeObject->getDefaultValue(),
                'note' => '<span style="'.$class.'"><a target="_blank" href="'.$url.'pub/media/aca/image/'.$attributeObject->getDefaultValue().'">'.$attributeObject->getDefaultValue().'</a><br /><input type="checkbox" value="1" name="remove_default_file">Remove</span>'
            ]
        );
        $fieldset->addField(
            'default_value_video',
            'file',
            [
                'name' => 'default_value_video',
                'label' => __('Default Video'),
                'title' => __('Default Video'),
                'value' => $attributeObject->getDefaultValue(),
                'note' => '<span style="'.$class.'"> <video controls><source src="'.$url.'pub/media/aca/image/'.$attributeObject->getDefaultValue().'" type="video/webm;codecs="vp8, vorbis"" /><source src="'.$url.'pub/media/aca/image/'.$attributeObject->getDefaultValue().'" type="video/mp4;codecs="avc1.42E01E, mp4a.40.2"" /> </video>
                    <br />
                    <input type="checkbox" value="1" name="remove_default_file">Remove</span>'
            ]
        );

        $fieldset->addField(
            'default_value_audio',
            'file',
            [
                'name' => 'default_value_audio',
                'label' => __('Default Audio'),
                'title' => __('Default Audio'),
                'value' => $attributeObject->getDefaultValue(),
                'note' => '<span style="'.$class.'"> <audio controls style="height:50px;"><source src="'.$url.'pub/media/aca/image/'.$attributeObject->getDefaultValue().'" type="audio/ogg" /><source src="'.$url.'pub/media/aca/image/'.$attributeObject->getDefaultValue().'" type="audio/mpeg" /> </audio>
                    <br />
                    <input type="checkbox" value="1" name="remove_default_file">Remove</span>'
            ]
        );
        $fieldset->addField(
            'default_value_image',
            'file',
            [
                'name' => 'default_value_image',
                'label' => __('Default Image'),
                'title' => __('Default Image'),
                'value' => $attributeObject->getDefaultValue(),
                'note' => '<img style="'.$class.'" src="'.$url.'pub/media/aca/image/'.$attributeObject->getDefaultValue().'" width="50%" alt="default image" /><br/><input type="checkbox" value="1" name="remove_default_file">Remove'
            ]
        );
        $fieldset->addField(
            'fme_extensions',
            'text',
            [
                'name' => 'fme_extensions',
                'label' => __('Allowed file extensions'),
                'title' => __('Allowed file extensions'),
                'value' => $attributeObject->getFmeExtensions(),
                'note' => 'Please enter comma separated allowed extensions e.g. jpg,jpeg,png Or leave empty to allow all'
            ]
        );

        $fieldset->addField(
            'fme_max_size',
            'text',
            [
                'name' => 'fme_max_size',
                'label' => __('Allowed file max size'),
                'title' => __('Allowed file  max size'),
                'value' => $attributeObject->getFmeMaxSize(),
                'note' => 'Please enter max file size (MB\'s) you want to allow Or enter 0 / leave emtpy for no restriction'
            ]
        );

        $fieldset->addField(
            'default_value_yesno',
            'select',
            [
                'name' => 'default_value_yesno',
                'label' => __('Default Value'),
                'title' => __('Default Value'),
                'values' => $yesno,
                'value' => $attributeObject->getDefaultValue()
            ]
        );

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $fieldset->addField(
            'default_value_date',
            'date',
            [
                'name' => 'default_value_date',
                'label' => __('Default Value'),
                'title' => __('Default Value'),
                'value' => $attributeObject->getDefaultValue(),
                'date_format' => $dateFormat
            ]
        );

        $fieldset->addField(
            'default_value_textarea',
            'textarea',
            [
                'name' => 'default_value_textarea',
                'label' => __('Default Value'),
                'title' => __('Default Value'),
                'value' => $attributeObject->getDefaultValue()
            ]
        );

        $fieldset->addField(
            'is_unique',
            'hidden',
            [
                'name' => 'is_unique',
                'label' => __('Unique Value'),
                'title' => __('Unique Value (not shared with other products)'),
                'note' => __('Not shared with other products.'),
                'value' => $attributeObject->getData('is_unique') ?: 0,
            ]
        );

        $fieldset->addField(
            'frontend_class',
            'select',
            [
                'name' => 'frontend_class',
                'label' => __('Input Validation for Store Owner'),
                'title' => __('Input Validation for Store Owner'),
                'values' => $this->_eavData->getFrontendClasses($attributeObject->getEntityType()->getEntityTypeCode())
            ]
        );

        $fieldset->addField(
            'is_used_in_grid',
            'hidden',
            [
                'name' => 'is_used_in_grid',
                'label' => __('Add to Column Options'),
                'title' => __('Add to Column Options'),
                'value' => $attributeObject->getData('is_used_in_grid') ?: 0,
                'note' => __('Select "Yes" to add this attribute to the list of column options in the product grid.'),
            ]
        );

        $fieldset->addField(
            'is_visible_in_grid',
            'hidden',
            [
                'name' => 'is_visible_in_grid',
                'value' => $attributeObject->getData('is_visible_in_grid') ?: 0,
            ]
        );

        $fieldset->addField(
            'is_filterable_in_grid',
            'hidden',
            [
                'name' => 'is_filterable_in_grid',
                'label' => __('Use in Filter Options'),
                'title' => __('Use in Filter Options'),
                'value' => $attributeObject->getData('is_filterable_in_grid') ?: 0,
                'note' => __('Select "Yes" to add this attribute to the list of filter options in the product grid.'),
            ]
        );

        if ($attributeObject->getId()) {
            $form->getElement('attribute_code')->setDisabled(1);
            if (!$attributeObject->getIsUserDefined()) {
                $form->getElement('is_unique')->setDisabled(1);
            }
        }
        $fieldset->addField(
            'fme_email',
            'select',
            [
                'name' => 'fme_email',
                'label' => __('Show in Email'),
                'title' => __('Show in Email'),
                'values' => $yesno,
            ]
        );
        
        $fieldset->addField(
            'store_ids',
            'multiselect',
            [
              'name'     => 'store_ids[]',
              'label'    => __('Store Views'),
              'title'    => __('Store Views'),
              'required' => true,
              'values'   => $this->getSystemStore(),
            ]
        );
        $groups = $this->getCustomerGroup();
        unset($groups[0]);
        $fieldset->addField(
            'customer_group',
            'multiselect',
            [
                'name' => 'customer_group[]',
                'label' => __('Customer Group'),
                'title' => __('Customer Group'),
                'values' => $groups,
                'required' => true,
            ]
        );

        $this->_eventManager->dispatch('product_attribute_form_build', ['form' => $form]);
        if (in_array($attributeObject->getAttributeCode(), $this->disableScopeChangeList)) {
            $form->getElement('is_global')->setDisabled(1);
        }
        $this->setForm($form);
        $this->getPropertyAttributeLocker()->lock($form);
        return $this;
    }

    /**
     * Initialize form fileds values
     *
     * @return $this
     */
    protected function _initFormValues()
    {
        $this->getForm()->addValues($this->getAttributeObject()->getData());
        return parent::_initFormValues();
    }

    /**
     * Retrieve attribute object from registry
     *
     * @return mixed
     */
    private function getAttributeObject()
    {
        return $this->_coreRegistry->registry('entity_attribute');
    }

    /**
     * Get property locker
     *
     * @return PropertyLocker
     */
    private function getStoreUrl()
    {
        if (null === $this->storeManager) {
            $this->storeManager = ObjectManager::getInstance()->get(StoreManagerInterface::class);
        }
        return $this->storeManager->getStore(1) ->getBaseUrl();
    }

    /**
     * Get property locker
     *
     * @return PropertyLocker
     */
    private function getSystemStore()
    {
        if (null === $this->systemStore) {
            $this->systemStore = ObjectManager::getInstance()->get(Store::class);
        }
        return $this->systemStore->getStoreValuesForForm(false, true);
    }

    /**
     * Get property locker
     *
     * @return PropertyLocker
     */
    private function getCustomerGroup()
    {
        if (null === $this->customerGroup) {
            $this->customerGroup = ObjectManager::getInstance()->get(Group::class);
        }
        return $this->customerGroup->toOptionArray();
    }

    /**
     * Get property locker
     *
     * @return PropertyLocker
     */
    private function getPropertyAttributeLocker()
    {
        if (null === $this->propertyAttributeLocker) {
            $this->propertyAttributeLocker = ObjectManager::getInstance()->get(PropertyLocker::class);
        }
        return $this->propertyAttributeLocker;
    }
}
