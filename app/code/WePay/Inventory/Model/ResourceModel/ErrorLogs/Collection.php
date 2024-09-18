<?php
namespace WePay\Inventory\Model\ResourceModel\ErrorLogs;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'wepay_inventory_errorlogs_collection';
	protected $_eventObject = 'errorlogs_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('WePay\Inventory\Model\ErrorLogs', 'WePay\Inventory\Model\ResourceModel\ErrorLogs');
	}
}
