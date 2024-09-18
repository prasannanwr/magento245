<?php
namespace WePay\Inventory\Model;

class ErrorLogs extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'wepay_error_log';

	protected $_cacheTag = 'wepay_error_log';

	protected $_eventPrefix = 'wepay_error_log';

	protected function _construct()
	{
		$this->_init('WePay\Inventory\Model\ResourceModel\ErrorLogs');
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