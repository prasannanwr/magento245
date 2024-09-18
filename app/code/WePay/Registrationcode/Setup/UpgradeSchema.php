<?php

namespace WePay\Registrationcode\Setup;

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
                    $setup->getTable('registration_code'),
                    'code_usage_limit',
                    [
                        'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        'nullable' => true,
                        'default' => 100,
                        'comment'  => 'Code Uses limit',
                    ]
                );
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable('registration_code'),
                    'comparison_operator',
                    [
                        'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        'nullable' => true,
                        'default' => 0,
                        'comment'  => 'Comparison Type',
                    ]
                );

        }
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $setup->getConnection()
                ->addColumn(
                    $setup->getTable('registration_code'),
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
                    $setup->getTable('registration_code'),
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
                    $setup->getTable('registration_code'),
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
                    $setup->getTable('registration_code'),
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
                    $setup->getTable('registration_code'),
                    'misc_5',
                    [
                        'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'nullable' => true,
                        'length'   => '126',
                        'comment'  => 'Misc_5',
                    ]
                );
        }
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
        $setup->endSetup();
    }
}
