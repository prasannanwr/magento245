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
        //$option = $data['option'];

        // Create a new custom model instance
        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
        $attributeHelper = $objectManager->create('\Prasanna\Invitecode\Helper\Attribute');
        $attributeModel = $objectManager->create('\Prasanna\Invitecode\Model\Attribute');
        $attributeFactory = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Eav\Attribute');
        $eavConfig = $objectManager->create('\Magento\Eav\Model\Config');
        $entityTypeId = 9;

        if (isset($data['attribute_id'])) { // update case of attribute
            $attribute_code = $attributeHelper->getAttributeCodeById($data['attribute_id']);
        } else { // new entry
            $attribute_code = $data['attribute_code'];
        }

        /* save the weight and group option of the input */
//        $custom_data['input_weight'] = $data['input_weight'];
//        $custom_data['is_customer_group'] = $data['is_customer_group'];
        //$custom_data['attribute_id'] = $data['attribute_id'];
        $attribute = $eavConfig->getAttribute($entityTypeId, $attribute_code);

        $attribute->setData('input_weight', $data['input_weight']);
        $attribute->setData('is_customer_group', $data['is_customer_group']);
        $attribute->save();
        //var_dump($attribute->getData('input_weight'));exit;

//        $attributeFactory->addData($custom_data);
//        $attributeFactory->save();

        /* save the weightage of the item options if exists */
        if (isset($data['weightage'])) { // if there is weight

            $weightage = $data['weightage'];
            $weightage_id = $data['weigtage_id'];

            if (sizeof($weightage) > 0) { // may not be needed. cross check

                if(sizeof($weightage_id) > 0) { // for edit case of item weight
                    $id_array = [];
                    $i = 0;
                    foreach ($weightage_id as $option => $id) {
                        $id_array[$i] = $id;
                        $i++;
                    }
                }

//                if (isset($data['attribute_id'])) { // update case of attribute
//                    $attribute_code = $attributeHelper->getAttributeCodeById($data['attribute_id']);
//                } else { // new entry
//                    $attribute_code = $data['attribute_code'];
//                }

                $j = 0;
                foreach ($weightage as $item=>$value) {
                    if(!empty($value)) {
                        if(!empty($id_array[$j])) {
                            $attrModel = $attributeModel->load($id_array[$j]);
                            if ($attrModel->getId()) {
                                if (!empty($value)) {
                                    //save code here
                                    $attrModel->setData('weightage',$value);
                                    $attrModel->save();
                                }
                            } else {
                                $attributeModel->setAttributeCode($attribute_code);
                                $attributeModel->setOptionId($item);
                                $attributeModel->setWeightage($value);
                                $attributeModel->save();
                            }

                        } else {
                            $attributeModel->setAttributeCode($attribute_code);
                            $attributeModel->setOptionId($item);
                            $attributeModel->setWeightage($value);
                            $attributeModel->save();
                        }
                    }
                    $j++;
                }

            }
        }
        return $result;
    }

    public function saveAttributeOption()
    {

    }


}
