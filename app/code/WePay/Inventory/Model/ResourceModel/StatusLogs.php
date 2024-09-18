<?php
namespace WePay\Inventory\Model\ResourceModel;


class StatusLogs extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('wepay_status_log', 'id');
	}
	
}