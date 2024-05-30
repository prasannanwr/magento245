<?php
namespace Prasanna\Invitecode\Setup;

use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $setup->startSetup();
        $this->createInviteCodeTable($setup);
        $this->createAttributeWeightTable($setup);
        $setup->endSetup();
    }

    protected function createInviteCodeTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('invite_code');
        if($setup->getConnection()->isTableExists($tableName) !== true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Entity ID')
                ->addColumn(
                    'attribute_code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    126,
                    ['nullable' => true],
                    'Attribute code')
                ->addColumn(
                    'code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    126,
                    ['nullable'=>false],
                    'Invite Code')
                ->addColumn('reusable',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['nullable'=> true],
                    'Re-usable')
                ->addColumn('case_sensitive',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['nullable'=> true],
                    'Case Sensitive')
                ->addColumn('customer_group',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable'=>true])
                ->addColumn('count',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable'=>true])
                ->addColumn('active',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['nullable'=> true],
                    'Active')
                ->addColumn('created_date',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable'=>false],
                    'Created Date')
                ->setComment('Invite Code Table');
            $setup->getConnection()->createTable($table);
        }
    }

    protected function createAttributeWeightTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('attribute_weight');
        if($setup->getConnection()->isTableExists($tableName) !== true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Entity ID')
                ->addColumn(
                    'attribute_code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    126,
                    ['nullable' => true],
                    'Attribute code')
                ->addColumn(
                    'option_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    126,
                    ['nullable'=>false],
                    'Option Id')
                ->addColumn('weightage',
                    \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    null,
                    ['nullable'=> true],
                    'Weight of the item')
                ->setComment('Attribute Weight Table');
            $setup->getConnection()->createTable($table);
        }
    }
}
