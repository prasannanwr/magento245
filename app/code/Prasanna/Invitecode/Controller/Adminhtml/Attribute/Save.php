<?php
namespace Prasanna\Invitecode\Controller\Adminhtml\Attribute;

use Magento\Framework\Filesystem;
use Prasanna\Invitecode\Helper\Attribute;

class Save extends \FME\AdditionalCustomerAttributes\Controller\Adminhtml\Attribute\Save
{
    protected $attributeHelper;

    protected $customAttributeFactory;

//    public function __construct(\Magento\Backend\App\Action\Context $context, Attribute $attributeHelper, \Prasanna\Invitecode\Model\Attribute $customAttributeFactory)
//    {
//        $this->attributeHelper = $attributeHelper;
//        $this->customAttributeFactory = $customAttributeFactory;
//        parent::__construct($context);
//    }

    public function execute()
    {
        $result = parent::execute(); // TODO: Change the autogenerated stub
        $data = $this->getRequest()->getPostValue();
        $option = $data['option'];
        // Create a new custom model instance
        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        $attributeHelper = $objectManager->create('\Prasanna\Invitecode\Helper\Attribute');
        $attributeModel = $objectManager->create('\Prasanna\Invitecode\Model\Attribute');
        //$attributeModel = $customAttributeFactory->create();
        //echo "<pre>";

        if (isset($data['weightage'])) { // if there is weight
            $weightage = $data['weightage'];
            if (sizeof($weightage) > 0) {

                if (isset($data['attribute_id'])) { // update case
                    $attribute_code = $attributeHelper->getAttributeCodeById($data['attribute_id']);
                } else {
                    $attribute_code = $data['attribute_code'];
                }
                foreach ($weightage as $item=>$value) {
                    if (!empty($value)) {
                        //save code here
                        $attributeModel->setAttributeCode($attribute_code);
                        $attributeModel->setOptionId($item);
                        $attributeModel->setWeightage($value);
                        $attributeModel->save();
                    }
                }
            }
        }

        //exit;
        return $result;
    }
}
