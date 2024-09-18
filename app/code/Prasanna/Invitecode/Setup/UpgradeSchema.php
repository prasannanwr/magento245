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
        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable('invite_code'),
                    'misc_1',
                    [
                        'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'length'   => '126',
                        'comment'  => 'Misc_1',
                    ]
                );
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable('invite_code'),
                    'misc_2',
                    [
                        'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'length'   => '126',
                        'comment'  => 'Misc_2',
                    ]
                );
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable('invite_code'),
                    'misc_3',
                    [
                        'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'length'   => '126',
                        'comment'  => 'Misc_3',
                    ]
                );
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable('invite_code'),
                    'misc_4',
                    [
                        'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'length'   => '126',
                        'comment'  => 'Misc_4',
                    ]
                );
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable('invite_code'),
                    'misc_5',
                    [
                        'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'length'   => '126',
                        'comment'  => 'Misc_5',
                    ]
                );
        }
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
