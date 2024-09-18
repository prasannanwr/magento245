<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Model\ResourceModel\Attribute;

/**
 * AdditionalCustomerAttributes additional attribute resource collection
 */
class Collection extends \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection
{

    /**
     * Resource model initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Magento\Catalog\Model\ResourceModel\Eav\Attribute',
            'Magento\Eav\Model\ResourceModel\Entity\Attribute'
        );
    }

    /**
     * Initialize select object
     *
     * @return $this
     */
    public function _initSelect()
    {
        $entityTypeId = (int)$this->_eavEntityFactory->create()->setType(
            \WePay\AdditionalCustomerAttributes\Model\Attribute::ENTITY
        )->getTypeId();
        $columns = $this->getConnection()->describeTable($this->getResource()->getMainTable());
        unset($columns['attribute_id']);
        $retColumns = [];
        foreach ($columns as $labelColumn => $columnData) {
            $retColumns[$labelColumn] = $labelColumn;
            if ($columnData['DATA_TYPE'] == \Magento\Framework\DB\Ddl\Table::TYPE_TEXT) {
                $retColumns[$labelColumn] = 'main_table.' . $labelColumn;
            }
        }
        $this->getSelect()->from(
            ['main_table' => $this->getResource()->getMainTable()],
            $retColumns
        )->join(
            ['additional_table' => $this->getTable('catalog_eav_attribute')],
            'additional_table.attribute_id = main_table.attribute_id'
        )
        // ->joinLeft(
        //     ['additionalcustomerattributes_table' => $this->getTable('wepay_additionalcustomerattributes')],
        //     'additionalcustomerattributes_table.attribute_id = main_table.attribute_id',
        //     ['customer_value'=>'value']
        // )
        ->join(
            ['store_table' => $this->getTable('wepay_additionalcustomerattributes_stores')],
            'store_table.attribute_id = main_table.attribute_id',
            new \Zend_Db_Expr("GROUP_CONCAT(`store_table`.`store_id` ORDER BY `store_table`.`store_id` SEPARATOR ',') as 'store_id'")
        )->join(
            ['group_table' => $this->getTable('wepay_additionalcustomerattributes_customer_group')],
            'group_table.attribute_id = main_table.attribute_id',
            new \Zend_Db_Expr("GROUP_CONCAT(`group_table`.`group_id` ORDER BY `group_table`.`group_id` SEPARATOR ',') as 'group_id'")
        )
        ->where(
            'main_table.entity_type_id = ?',
            $entityTypeId
        )
        ->group('attribute_id');
        return $this;
    }

    /**
     * Specify attribute entity type filter.
     * Entity type is defined.
     *
     * @param  int $typeId
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setEntityTypeFilter($typeId)
    {
        return $this;
    }
    
    /**
     * Specify filter by "Billing" step
     *
     * @return $this
     */
    public function addPageFilter($page)
    {
        $pages = [1, $page];
        return $this->addFieldToFilter('additional_table.is_global', ['in' => implode(',', $pages)]);
    }

    /**
     * Specify filter by "shipping method" step
     *
     * @return $this
     */
    public function addStoreFilters($store_id)
    {
        $stores = [0, $store_id];
        return $this->addFieldToFilter('store_table.store_id', ['in' => implode(',', $stores)]);
    }

    /**
     * Specify filter by "shipping method" step
     *
     * @return $this
     */
    public function addCustomerGroupFilter($group_id = 0)
    {
        return $this->addFieldToFilter('group_table.group_id', $group_id);
    }

    /**
     * apply Sort
     *
     * @return $this
     */
    public function applySort()
    {
        return $this->setOrder('additional_table.position', 'ASC');
    }
    /**
     * apply Sort
     *
     * @return $this
     */
    public function applyGroup()
    {
        $this->getSelect()->group('main_table.attribute_id');
    }
    /**
     * get customer stored value
     *
     * @return $this
     */
    public function getCustomerValue($customerId = 0)
    {
        $this->getSelect()
        ->joinLeft(
            ['additionalcustomerattributes_table' => $this->getTable('wepay_additionalcustomerattributes')],
            '`additionalcustomerattributes_table`.`attribute_id` = `main_table`.`attribute_id` AND `additionalcustomerattributes_table`.`customer_id` = '. $customerId,
            ['customer_value'=>'value']
        );
    }
    /**
     * Specify "is_searchable" filter
     *
     * @return $this
     */
    public function addIsSearchableFilter()
    {
        return $this->addFieldToFilter('additional_table.is_searchable', 0);
    }

    /**
     * Specify "is_searchable" filter
     *
     * @return $this
     */
    public function applyEmailFilter()
    {
        return $this->addFieldToFilter('main_table.wepay_email', 1);
    }

}
