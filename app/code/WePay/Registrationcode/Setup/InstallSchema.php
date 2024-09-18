<?php
namespace WePay\Registrationcode\Setup;

use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $setup->startSetup();
        $this->createRegistrationCodeTable($setup);
        $this->createAttributeWeightTable($setup);
        $this->addCustomEavAttribute($setup);
        $setup->endSetup();
    }

    protected function createRegistrationCodeTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('registration_code');
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
                    'Registration Code')
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
                    ['default' => 1],
                    ['nullable'=> true],
                    'Active')
                ->addColumn('created_date',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable'=>false],
                    'Created Date')
                ->setComment('Registration Code Table');
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

    protected function addCustomEavAttribute(SchemaSetupInterface $setup)
    {
        $setup->getConnection()
            ->addColumn(
                $setup->getTable('eav_attribute'),
                'input_weight',
                [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'default'  => 0,
                    'length'   => '11',
                    'comment'  => 'Weight of the input',
                ]
            );
        $setup->getConnection()->addColumn(
            $setup->getTable('eav_attribute'),
            'is_customer_group',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => true,
                'default'  => 0,
                'length'   => '1',
                'comment'  => 'Is the input is assigned to customer group',
            ]
        );
        $setup->getConnection()->addColumn(
            $setup->getTable('eav_attribute'),
            'has_code',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => true,
                'default'  => 0,
                'length'   => '1',
                'comment'  => 'Is the input has code',
            ]
        );
    }
}
