<?php
namespace Prasanna\Invitecode\Model;

use Magento\Framework\Model\AbstractModel;

class Attribute extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Prasanna\Invitecode\Model\ResourceModel\Attribute::class);
//        parent::_construct(); // TODO: Change the autogenerated stub
    }

}