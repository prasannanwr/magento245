<?php
namespace WePay\OrderScriptAutomation\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // Define the custom table
        if (!$setup->tableExists('customer_group_extension')) {
            $table = $setup->getConnection()
                ->newTable($setup->getTable('customer_group_extension'))
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Entity ID'
                )
                ->addColumn(
                    'custom_script_mode',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => true, 'default' => null],
                    'Script Run mode'
                )
                ->addForeignKey(
                    $setup->getFkName('customer_group_extension', 'entity_id', 'customer_group', 'customer_group_id'),
                    'entity_id',
                    $setup->getTable('customer_group'),
                    'customer_group_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Set the script mode for Customer Group');
            $setup->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}
