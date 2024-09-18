<?php
//declare(strict_types=1);
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
 
namespace WePay\AdditionalCustomerAttributes\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.Generic.CodeAnalysis.UnusedFunctionParameter)
     */
     
    // @codingStandardsIgnoreStart
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) // @codingStandardsIgnoreEnd
    {
        $installer = $setup;
        $installer->startSetup();
        $setup->getConnection()
            ->addColumn(
                $installer->getTable('eav_attribute'),
                'wepay_email',
                [
                    'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => true,
                    'default'  => 0,
                    'length'   => '1',
                    'comment'  => 'Add attribute to Email',
                ]
            );
        $setup->getConnection()->addColumn(
            $installer->getTable('eav_attribute'),
            'wepay_extensions',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'default'  => '',
                'length'   => '255',
                'comment'  => 'Allowed file extensions',
            ]
        );
        $setup->getConnection()->addColumn(
            $installer->getTable('eav_attribute'),
            'wepay_max_size',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'default'  => '',
                'length'   => '255',
                'comment'  => 'Allowed file max size',
            ]
        );
        $setup->getConnection()->addColumn(
            $installer->getTable('eav_attribute'),
            'wepay_dependable',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                'nullable' => true,
                'default'  => 0,
                'length'   => '1',
                'comment'  => 'Is Dependable',
            ]
        );

        $setup->getConnection()->addColumn(
            $installer->getTable('eav_attribute'),
            'wepay_dpath',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'default'  => '',
                'length'   => '255',
                'comment'  => 'Dependency Path',
            ]
        );

        $setup->getConnection()->addColumn(
            $installer->getTable('eav_attribute'),
            'wepay_dcode',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'default'  => '',
                'length'   => '255',
                'comment'  => 'Dependency Code',
            ]
        );
        $setup->getConnection()->addColumn(
            $installer->getTable('eav_attribute'),
            'wepay_did',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'default'  => 0,
                'length'   => '11',
                'comment'  => 'Dependable id',
            ]
        );

        $setup->getConnection()->addColumn(
            $installer->getTable('eav_attribute'),
            'wepay_dvalue',
            [
                'type'     => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable' => true,
                'default'  => '',
                'length'   => '255',
                'comment'  => 'Dependency Value',
            ]
        );
        
        if (!$installer->tableExists('wepay_additionalcustomerattributes_stores')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('wepay_additionalcustomerattributes_stores'));
            $table->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                5,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Store ID'
            )
            ->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                11,
                [
                    'unsigned'  => true,
                    'nullable'  => false,
                    'primary'   => true,
                ],
                'Store ID'
            ) ->addIndex(
                $installer->getIdxName('wepay_additionalcustomerattributes_stores', ['attribute_id']),
                ['attribute_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'wepay_additionalcustomerattributes_stores',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                Table::ACTION_CASCADE,
                Table::ACTION_CASCADE
            )
            ->setComment('Assigned stores');
                
            $installer->getConnection()->createTable($table);
        }
        if (!$installer->tableExists('wepay_additionalcustomerattributes_customer_group')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('wepay_additionalcustomerattributes_customer_group'));
            $table->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                5,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Attribute Id'
            )
            ->addColumn(
                'group_id',
                Table::TYPE_INTEGER,
                11,
                [
                    'unsigned'  => true,
                    'nullable'  => false,
                    'primary'   => true,
                ],
                'Customer Group ID'
            ) ->addIndex(
                $installer->getIdxName('wepay_additionalcustomerattributes_customer_group', ['attribute_id']),
                ['attribute_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'wepay_additionalcustomerattributes_customer_group',
                    'attribute_id',
                    'eav_attribute',
                    'attribute_id'
                ),
                'attribute_id',
                $installer->getTable('eav_attribute'),
                'attribute_id',
                Table::ACTION_CASCADE,
                Table::ACTION_CASCADE
            )
            ->setComment('Assigned Customers Group');
            $installer->getConnection()->createTable($table);
        }
        if (!$installer->tableExists('wepay_additionalcustomerattributes')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('wepay_additionalcustomerattributes'));
            $table->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Record Id'
            )->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                10,
                [
                    'unsigned' => true,
                    'nullable' => false,
                ],
                'Customer Id'
            )
            ->addColumn(
                'attribute_id',
                Table::TYPE_SMALLINT,
                5,
                [
                    'unsigned' => true,
                    'nullable' => false,
                ],
                'Attribute Id'
            )
            ->addColumn(
                'attribute_code',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false,
                ],
                'Attribute Code'
            )
            ->addColumn(
                'value',
                Table::TYPE_TEXT,
                null,
                [
                ],
                'Attribute Value'
            )->addIndex(
                $installer->getIdxName('wepay_additionalcustomerattributes', ['customer_id']),
                ['customer_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'wepay_additionalcustomerattributes',
                    'customer_id',
                    'customer_entity',
                    'entity_id'
                ),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id',
                Table::ACTION_CASCADE,
                Table::ACTION_CASCADE
            )
            ->setComment('Attribute Values for Customers');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
