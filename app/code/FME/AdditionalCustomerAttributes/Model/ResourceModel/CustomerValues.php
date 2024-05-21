<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\AdditionalCustomerAttributes\Model\ResourceModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Attribute\LockValidatorInterface;
use Magento\Framework\Exception\AlreadyExistsException;

/**
 * Additional Customer Attribute resource model
 *
 * @author  FME Extensions <support@fmeextensions.com>
 */
class CustomerValues extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('fme_additionalcustomerattributes', 'entity_id');
    }

    /**
     * Save attribute values for customer
     *
     * @param string $code
     * @param void $value
     * @param int $customer_id
     * @param int $entityTypeId
     * @return void
     */
    public function saveCustomerValues($code, $value, $customer_id, $entityTypeId)
    {
        if ($customer_id > 0 && $entityTypeId > 0 && $code != '') {
            $connection = $this->getConnection();
            $select = $connection->select()->from(
                $this->getTable('fme_additionalcustomerattributes'),
                ['entity_id']
            )
            ->where('attribute_code = ?', $code)
            ->where('customer_id = ?', $customer_id);
            $existingData = $connection->fetchOne($select);
            if ($existingData > 0) {
                $entityRow = ['value' => $value];
                $this->getConnection()->update(
                    $this->getTable('fme_additionalcustomerattributes'),
                    $entityRow,
                    ['entity_id = ?' => (int)$existingData]
                );
            } else {
                $select = $connection->select()->from(
                    $this->getTable('eav_attribute'),
                    ['attribute_id']
                )
                ->where('attribute_code = ?', $code)
                ->where('entity_type_id = ?', $entityTypeId);
                $attribute_id = $connection->fetchOne($select);
                $entityRow = ['customer_id' => $customer_id, 'attribute_id' => $attribute_id,
                 'attribute_code' => $code, 'value' => $value];
                $this->getConnection()->insert(
                    $this->getTable('fme_additionalcustomerattributes'),
                    $entityRow
                );
            }
        }
    }
    /**
     * Retrieve attribute element type
     *
     * @param string $code
     * @param int $entityTypeId
     * @return string
     */
    public function getAttributeType($code, $entityTypeId)
    {
        if ($code != "") {
            $connection = $this->getConnection();
            $select = $connection->select()->from(
                $this->getTable('eav_attribute'),
                ['frontend_input']
            )
            ->where('attribute_code = ?', $code)
            ->where('entity_type_id = ?', $entityTypeId);
            return $connection->fetchOne($select);
        }
    }

    /**
     * Retrieve attribute option values
     *
     * @param int $id
     * @param int $storeId
     * @return string
     */
    public function getOptionValueById($id, $storeId)
    {
        if ($id > 0) {
            $connection = $this->getConnection();
            $select = $connection->select()->from(
                $this->getTable('eav_attribute_option_value')
            )->where(
                'option_id in ('.$id.')'
            )->where(
                'store_id in ('.$storeId.',0)'
            )->order('FIELD(store_id, "'.$storeId.'","0")');
            $result = $connection->fetchAll($select);
            if ($result) {
                $default = [];
                foreach ($result as $option) {
                    if (!isset($default[$option['option_id']])) {
                        $default[$option['option_id']] = $option['value'];
                    }
                }
                return implode(",", $default);
            }
        }
    }

    /**
     * Retrieve File parameters
     *
     * @param string $code
     * @param int $entityTypeId
     * @return array
     */
    public function getFileParams($code, $entityTypeId)
    {
        if ($code != "") {
            $connection = $this->getConnection();
            $select = $connection->select()->from(
                $this->getTable('eav_attribute'),
                ['fme_extensions','fme_max_size','is_required','frontend_label']
            )
            ->where('attribute_code = ?', $code)
            ->where('entity_type_id = ?', $entityTypeId);
            return $connection->fetchRow($select);
        }
    }

    /**
     * Retrieve attribute Id and label
     *
     * @param int $entityTypeId
     * @return array
     */
    public function getDependableAttributes($entityTypeId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('eav_attribute'),
            ['attribute_id','frontend_label']
        )
        ->where('entity_type_id = ?', $entityTypeId)
        ->where("frontend_input in ('select','multiselect','radio','checkbox','boolean')");
        return $connection->fetchAll($select);
    }
}
