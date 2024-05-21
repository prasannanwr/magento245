<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\AdditionalCustomerAttributes\Setup;

use Magento\Eav\Model\Entity\Setup\Context;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product\Type;

class AdditionalCustomerAttributesSetup extends EavSetup
{
    /**
     * Category model factory
     *
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * Init
     *
     * @param ModuleDataSetupInterface $setup
     * @param Context $context
     * @param CacheInterface $cache
     * @param CollectionFactory $attrGroupCollectionFactory
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(
        ModuleDataSetupInterface $setup,
        Context $context,
        CacheInterface $cache,
        CollectionFactory $attrGroupCollectionFactory
    ) {
        parent::__construct($setup, $context, $cache, $attrGroupCollectionFactory);
    }

    /**
     * Default entities and attributes
     *
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getDefaultEntities()
    {
        return [
            'additionalcustomerattributes' => [
                'entity_model' => 'FME\AdditionalCustomerAttributes\Model\ResourceModel\Attribute',
                'attribute_model' => 'Magento\Customer\Model\Attribute',
                'table' => 'customer_entity',
                'additional_attribute_table' => 'catalog_eav_attribute',
                'entity_attribute_collection' => 'FME\AdditionalCustomerAttributes\Model\ResourceModel\Attribute\Collection',
                'attributes' => [
                    
                ],
            ]
        ];
    }
}
