<?php

namespace Prasanna\Invitecode\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
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
        $setup->endSetup();
    }
}
