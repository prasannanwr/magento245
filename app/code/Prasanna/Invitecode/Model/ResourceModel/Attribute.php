<?php
namespace Prasanna\Invitecode\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Attribute extends AbstractDb
{
    protected $catalogEavAttributeTable;
    protected $optionWeightTable;

    public function __construct(Context $context, $connectionName = null)
    {
        parent::__construct($context, $connectionName);
        $this->catalogEavAttributeTable = $this->getTable('catalog_eav_attribute');
        $this->optionWeightTable = $this->getTable('attribute_weight');
    }

    protected function _construct()
    {
        $this->_init('attribute_weight', 'entity_id');
    }

    /**
     * Get position data of an attribute by attribute_id
     *
     * @param int $attributeId
     * @return int|null
     */
    public function getAttributePosition($attributeId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->catalogEavAttributeTable, ['position'])
            ->where('attribute_id = :attribute_id');

        $bind = ['attribute_id' => (int)$attributeId];

        return $connection->fetchOne($select, $bind);
    }

    /**
     * Get position of option item by option id
     *
     * @param int $optionId
     * @return int|null
     */
    public function getOptionWeight($optionId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->optionWeightTable, ['weightage'])
            ->where('option_id = :option_id');

        $bind = ['option_id' => (int)$optionId];

        return $connection->fetchOne($select, $bind);
    }
}
