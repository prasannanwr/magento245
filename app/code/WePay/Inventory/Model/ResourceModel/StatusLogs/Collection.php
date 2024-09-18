<?php
namespace WePay\Inventory\Model\ResourceModel\StatusLogs;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'wepay_inventory_statuslogs_collection';
	protected $_eventObject = 'statuslogs_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('WePay\Inventory\Model\StatusLogs', 'WePay\Inventory\Model\ResourceModel\StatusLogs');
	}
}
