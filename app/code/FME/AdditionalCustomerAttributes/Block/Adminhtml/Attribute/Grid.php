<?php
/**
 *
 * @category : FME
 * @Package  : FME_AdditionalCustomerAttributes
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2018 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\AdditionalCustomerAttributes\Block\Adminhtml\Attribute;

use Magento\Eav\Block\Adminhtml\Attribute\Grid\AbstractGrid;

/**
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Grid extends AbstractGrid
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \FME\AdditionalCustomerAttributes\Model\ResourceModel\Attribute\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->_module = 'additionalcustomerattributes';
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Prepare product attributes grid collection object
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->collectionFactory->create(); //->addVisibleFilter();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare product attributes grid columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        
        $this->addColumn(
            'attribute_code',
            [
                'header' => __('Code'),
                'sortable' => true,
                'index' => 'attribute_code',
                'type' => 'text',
                'align' => 'left'
            ]
        );
        $this->addColumnAfter(
            'frontend_label',
            [
                'header' => __('Default Label'),
                'sortable' => true,
                'index' => 'frontend_label',
                'type' => 'text',
                'align' => 'left'
            ],
            'attribute_code'
        );
        
        $this->addColumnAfter(
            'is_visible',
            [
                'header' => __('Enabled'),
                'sortable' => true,
                'index' => 'is_visible_on_front',
                'type' => 'options',
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'align' => 'center'
            ],
            'frontend_label'
        );
        
        $this->addColumnAfter(
            'is_required',
            [
                'header' => __('Required'),
                'sortable' => true,
                'index' => 'is_required',
                'type' => 'options',
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'align' => 'center'
            ],
            'is_visible_on_front'
        );
        $scopes = [
            1 => __('Both'),
            2 => __('Registration Page Only'),
            3 => __('Account Page Only')
        ];
        $this->addColumnAfter(
            'is_global',
            [
                'header' => __('Show on'),
                'sortable' => true,
                'index' => 'is_global',
                'type' => 'options',
                'options' => $scopes,
                'align' => 'center'
            ],
            'is_visible'
        );

        $this->addColumn(
            'position',
            [
                'header' => __('Position'),
                'sortable' => true,
                'index' => 'position',
                'type' => 'text',
                'align' => 'center'
            ],
            'is_global'
        );
        $editable = [
            1 => 'Yes',
            2 => 'If Default values',
            3 => 'If Empty values',
            4 => 'If Default / Empty values',
            0 => 'No'
        ];
        $this->addColumnAfter(
            'is_visible_in_advanced_search',
            [
                'header' => __('Editable by customers'),
                'sortable' => true,
                'index' => 'is_visible_in_advanced_search',
                'type' => 'options',
                'options' => $editable,
                'align' => 'center'
            ],
            'position'
        );
        $this->addColumnAfter(
            'is_searchable',
            [
                'header' => __('Hide Field'),
                'sortable' => true,
                'index' => 'is_searchable',
                'type' => 'options',
                'options' => ['0' => __('Yes'), '1' => __('No')],
                'align' => 'center'
            ],
            'position'
        );

        $this->addColumnAfter(
            'fme_email',
            [
                'header' => __('Show in Email'),
                'sortable' => true,
                'index' => 'fme_email',
                'type' => 'options',
                'options' => ['1' => __('Yes'), '0' => __('No')],
                'align' => 'center'
            ],
            'is_searchable'
        );
        return $this;
    }
}
