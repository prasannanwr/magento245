<?php
//declare(strict_types=1);
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
 
namespace FME\AdditionalCustomerAttributes\Setup;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;
use Magento\Config\Model\ResourceModel\Config\Data;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;

/**
 * @codeCoverageIgnore
 */
 
class Uninstall implements UninstallInterface
{
    /**
     * @var CollectionFactory
     */
    public $collectionFactory;
    /**
     * @var Data
     */
    public $configResource;
    /**
     * @param CollectionFactory $collectionFactory
     * @param Data $configResource
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Data $configResource
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->configResource    = $configResource;
    }
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.Generic.CodeAnalysis.UnusedFunctionParameter)
     */
    // @codingStandardsIgnoreStart
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context) // @codingStandardsIgnoreEnd
    {
        //remove tables
        if ($setup->tableExists('fme_additionalcustomerattributes_stores')) {
            $setup->getConnection()->dropTable('fme_additionalcustomerattributes_stores');
        }
        //remove config settings if any
        $collection = $this->collectionFactory->create()
            ->addPathFilter('fme_additionalcustomerattributes_stores');
        foreach ($collection as $config) {
            $this->deleteConfig($config);
        }
    }
    /**
     * @param AbstractModel $config
     * @throws \Exception
     */
    public function deleteConfig(AbstractModel $config)
    {
        $this->configResource->delete($config);
    }
}
