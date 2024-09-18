<?php
namespace WePay\Inventory\Model;

class StatusLogs extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'wepay_status_log';

	protected $_cacheTag = 'wepay_status_log';

	protected $_eventPrefix = 'wepay_status_log';

	protected function _construct()
	{
		$this->_init('WePay\Inventory\Model\ResourceModel\StatusLogs');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}