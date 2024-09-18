<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Model\ResourceModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Attribute\LockValidatorInterface;

/**
 * AdditionalCustomerAttributes attribute resource model
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Attribute extends \Magento\Catalog\Model\ResourceModel\Attribute
{
    /**
     * Perform actions before object save
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $applyTo = $object->getApplyTo();
        if (is_array($applyTo)) {
            $object->setApplyTo(implode(',', $applyTo));
        }
        if (is_array($object->getDefaultValue())) {
            $object->setDefaultValue(implode(',', $object->getDefaultValue()));
        }
        return parent::_beforeSave($object);
    }

    /**
     * Perform actions after object save
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_updateRelatedSave($object);
        $this->_clearUselessAttributeValues($object);
        return parent::_afterSave($object);
    }

    /**
     * Perform actions after object save
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _updateRelatedSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $delCondition = [
            'attribute_id = ?' => $object->getId()
        ];
        $this->getConnection()->delete(
            $this->getTable('wepay_additionalcustomerattributes_customer_group'),
            $delCondition
        );
        $this->getConnection()->delete(
            $this->getTable('wepay_additionalcustomerattributes_stores'),
            $delCondition
        );
        $store_ids = $object->getStoreIds();
        foreach ($store_ids as $store_id) {
            $entityRow = ['attribute_id' => $object->getId(),'store_id' => $store_id];
            $this->getConnection()->insert($this->getTable('wepay_additionalcustomerattributes_stores'), $entityRow);
        }
        $customer_groups = $object->getCustomerGroup();
        foreach ($customer_groups as $customer_group) {
            $entityRow = ['attribute_id' => $object->getId(),'group_id' => $customer_group];
            $this->getConnection()->insert(
                $this->getTable('wepay_additionalcustomerattributes_customer_group'),
                $entityRow
            );
        }
    }
    /**
     * Clear useless attribute values
     *
     * @param  \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _clearUselessAttributeValues(\Magento\Framework\Model\AbstractModel $object)
    {
        return $this;
    }
    /**
     * Perform actions after object save
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param int
     * @param int
     * @param int/string
     * @return $this
     */
    public function _updateDefaultValue($object, $optionId, $intOptionId, &$defaultValue)
    {
        if (in_array($optionId, $object->getDefault())) {
            $frontendInput = $object->getFrontendInput();
            if ($frontendInput === 'multiselect' || $frontendInput === 'checkbox') {
                $defaultValue[] = $intOptionId;
            } elseif ($frontendInput === 'select' || $frontendInput === 'radio') {
                $defaultValue = [$intOptionId];
            }
        }
    }
    /**
     * Perform actions after object load
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        /** @var $entityType \Magento\Eav\Model\Entity\Type */
        $entityType = $object->getData('entity_type');
        if ($entityType) {
            $additionalTable = $entityType->getAdditionalAttributeTable();
        } else {
            $additionalTable = $this->getAdditionalAttributeTable($object->getEntityTypeId());
        }

        if ($additionalTable) {
            $connection = $this->getConnection();
            $bind = [':attribute_id' => $object->getId()];
            $select = $connection->select()->from(
                $this->getTable($additionalTable)
            )->where(
                'attribute_id = :attribute_id'
            );

            $result = $connection->fetchRow($select, $bind);
            if ($result) {
                $object->addData($result);
            }
        }
        $connection = $this->getConnection();
        $bind = [':attribute_id' => $object->getId()];
        $select = $connection->select()->from(
            ['wepay_stores' => $this->getTable('wepay_additionalcustomerattributes_stores')],
            ['store_id']
        )->where(
            'attribute_id = :attribute_id'
        );

        $result['store_ids'] = $connection->fetchCol($select, $bind);

        $select = $connection->select()->from(
            
            ['wepay_group' => $this->getTable('wepay_additionalcustomerattributes_customer_group')],
            ['group_id']
        )->where(
            'attribute_id = :attribute_id'
        );

        $result['customer_group'] = $connection->fetchCol($select, $bind);

        $object->addData($result);
        return $this;
    }
    /**
     * Retrieve option value
     *
     * @param int id
     * @param int $storeId
     * @return string
     */
    public function getOptionValueById($id, $storeId)
    {
        if ($id > 0) {
            $connection = $this->getConnection();
            $bind = [':option_id' => $id];
            $select = $connection->select()->from(
                $this->getTable('eav_attribute_option_value')
            )->where(
                'option_id = :option_id'
            );
            $result = $connection->fetchRow($select, $bind);
            if ($result) {
                $default = '';
                foreach ($result as $option) {
                    if (isset($option['store_id']) and $option['store_id'] == $storeId) {
                        return $option['value'];
                    } elseif (isset($option['store_id']) and $option['store_id'] == 0) {
                        $default = $option['value'];
                    }
                }
                return $default;
            }
        }
    }
}
