<?php
namespace WePay\Registrationcode\Plugin\Controller\Adminhtml\Attribute;

use Magento\Eav\Model\Config;
use Magento\Framework\App\RequestInterface;
use WePay\AdditionalCustomerAttributes\Controller\Adminhtml\Attribute\Save as SaveController;
use Magento\Framework\Controller\Result\Redirect;
use WePay\Registrationcode\Helper\Attribute;

class SavePlugin
{
    protected $request;
    protected $attributeHelper;

    protected $eavConfig;

    protected $attributeModel;

    public function __construct(RequestInterface $request, Attribute $attributeHelper, Config $eavConfig, \WePay\Registrationcode\Model\Attribute $attributeModel)
    {
        $this->request = $request;
        $this->attributeHelper = $attributeHelper;
        $this->eavConfig = $eavConfig;
        $this->attributeModel = $attributeModel;
    }
    /**
     * Around plugin for the save method
     *
     * @param SaveController $subject
     * @param callable $proceed
     * @return Redirect
     */
    public function aroundExecute(SaveController $subject, callable $proceed)
    {
        // Call the original save method
        $result = $proceed();

        // Post-processing after the original save action
        /* save the weight of attribute options if exists */
        $postData = $this->request->getPostValue();
        if(!empty($postData)) {
            if(isset($postData['weightage'])) {
                $weightage = $postData['weightage'];
                $weightage_id = $postData['weigtage_id'];
                $entityTypeId = 9;
                if (isset($postData['attribute_id'])) { // update case of attribute
                    $attribute_code = $this->attributeHelper->getAttributeCodeById($postData['attribute_id']);
                } else { // new entry
                    $attribute_code = $postData['attribute_code'];
                }
                $attribute = $this->eavConfig->getAttribute($entityTypeId, $attribute_code);
                $attribute->setData('input_weight', $postData['input_weight']);
                $attribute->setData('is_customer_group', $postData['is_customer_group']);
                $attribute->save();
                if (sizeof($weightage) > 0) { // may not be needed. cross check
                    if(sizeof($weightage_id) > 0) { // for edit case of item weight
                        $id_array = [];
                        $i = 0;
                        foreach ($weightage_id as $option => $id) {
                            $id_array[$i] = $id;
                            $i++;
                        }
                    }
                    if (!isset($postData['attribute_id'])) { // new case
                        /* get the option id from attribute options */
                        $options = $attribute->getSource()->getAllOptions();
                        $optionsArray = array();
                        foreach ($options as $option) {
                            if(!empty($option['value'])) {
                                array_push($optionsArray, $option['value']);
                            }
                        }
                    }
                    $j = 0;
                    foreach ($weightage as $item=>$value) {
                        if(!empty($value)) {
                            if(!empty($id_array[$j])) {
                                $attrModel = $this->loadAttribute($id_array[$j]);
                                if ($attrModel->getId()) {
                                    if (!empty($value)) {
                                        //save code here
                                        $attrModel->setData('weightage',$value);
                                        $attrModel->save();
                                    }
                                } else {
                                    $this->saveAttributeOption($attribute_code, $item, $value);
                                }
                            } else {
                                $this->saveAttributeOption($attribute_code, $optionsArray[$j], $value);
                            }
                        }
                        $j++;
                    }
                }
            }

        }
        return $result;
    }

    public function saveAttributeOption($attribute_code, $item, $value)
    {
        $this->attributeModel->setAttributeCode($attribute_code);
        $this->attributeModel->setOptionId($item);
        $this->attributeModel->setWeightage($value);
        $this->attributeModel->save();
        $this->attributeModel->unsetData();
    }

    public function loadAttribute($attributeId)
    {
        return $this->attributeModel->load($attributeId);
    }

}
