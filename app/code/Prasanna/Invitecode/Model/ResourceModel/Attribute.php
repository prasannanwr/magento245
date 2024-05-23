<?php
namespace Prasanna\Invitecode\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Attribute extends AbstractDb
{
    protected $catalogEavAttributeTable;

    public function __construct(Context $context, $connectionName = null)
    {
        parent::__construct($context, $connectionName);
        $this->catalogEavAttributeTable = $this->getTable('catalog_eav_attribute');
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
}
