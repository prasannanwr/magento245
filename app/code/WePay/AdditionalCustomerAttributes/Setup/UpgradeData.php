<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */

namespace WePay\AdditionalCustomerAttributes\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $additionalcustomerattributesSetupFactory;

    /**
     * Init
     *
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(AdditionalCustomerAttributesSetupFactory $additionalcustomerattributesSetupFactory)
    {
        $this->additionalcustomerattributesSetupFactory = $additionalcustomerattributesSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var \Magento\Catalog\Setup\AdditionalCustomerAttributesSetup $additionalcustomerattributesSetup */
        $additionalcustomerattributesSetup = $this->additionalcustomerattributesSetupFactory
            ->create(['setup' => $setup]);
        $additionalcustomerattributesSetup->installEntities();
        // Create Root Catalog Node
    }
}
