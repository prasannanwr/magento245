<?php

//code practice

/* Decrypt the password from the encoded one
* output = aP1pL5e
*/

function decryptPassword($s)
{
    $length = strlen($s);
    $password = '';
    $nums = '';
    for($i=0;$i<=$length;$i++)
    {
        if (is_numeric($s[$i])) {
            if ($s[$i] == 0) {
                //replace it with last number at begining of string
                $password .= substr($nums,-1);
                $nums = substr_replace($nums,'',-1);
            } else {
                $nums .= $s[$i];
                //$password .= $s[$i];
            }
        }
        $next = $i+1;
        if(ctype_upper($s[$i]) && ctype_lower($s[$next])) {
            $password .= $s[$next] . $s[$i];
            $i++;
        } elseif($i == $length-1) {
            $password .=$s[$i];
        }
    }
    return $password;
}

$string = "51Pa*0Lp*0e";
$output = "aP1pL5e";
//51Pa * 0Lp * 0e
//$pass = decryptPassword($string);
//echo $pass;
//exit;

use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
//$objectManager = $bootstrap->getObjectManager();
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//get an order details
$orderId = 15; //pass order id
try {
    $order = $objectManager->create('\Magento\Sales\Model\OrderRepository')->get($orderId);
    $status = $order->getStatus();
    foreach ($order->getAllVisibleItems() as $item) {
        $sku = $item->getSku();
        $price = $item->getRowTotal();
        $options = $item->getProductOptions();
        $itemData = [
            'item_id' => $item->getItemId(),
            'product_id' => $item->getProductId(),
            'name' => $item->getName(),
            'sku' => $item->getSku(),
            'price' => $item->getPrice(),
            'quantity_ordered' => $item->getQtyOrdered(),
        ];
        if (isset($options['options'])) {
            foreach ($options['options'] as $customOption) {
                $itemData['custom_options'][] = [
                    'label' => $customOption['label'],
                    'value' => $customOption['value'],
                ];
            }
        }
//        foreach ($options['attributes_info'] as $option) { //get options
//            $optionLabel = $option['label'];
//            $value = $option['value'];
//            $optionId = $option['option_id'];
//            $optionValue = $option['option_value'];
//            if ($optionLabel == 'recipient_email') {
//                $recipient_email = $optionValue;
//            }
//            $itemData['custom_options'][] = [
//                'label' => $optionLabel,
//                'value' => $optionValue,
//            ];
//        }
    }
    print_r($itemData);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

exit;
//ends here

$eavConfig = $objectManager->get(\Magento\Eav\Model\Config::class);
//get checkbox options
$attributeCode = 'cg_radio';
// Check if the attribute is a checkbox type
$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
//$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
$attributeRepository = $objectManager->create('\Magento\Eav\Block\Adminhtml\Attribute\Edit\Main\AbstractMain');
$optionCollection = $objectManager->create('\Prasanna\Invitecode\Model\ResourceModel\Attribute\Collection');

$eavEntityFactory = $objectManager->create('\Magento\Eav\Model\Config');
$inviteCodeCollection = $objectManager->create('\Prasanna\Invitecode\Model\ResourceModel\Invitecode\Collection');
$registrationCodeCollection = $objectManager->create('\WePay\Registrationcode\Model\ResourceModel\Registrationcode\Collection');

//get attribute options
$attribute_code = 'test_dd3';
$attribute = $eavConfig->getAttribute(9, $attribute_code);
$options = $attribute->getSource()->getAllOptions();
$optionsArray = array();
foreach ($options as $option) {
    if(!empty($option['value'])) {
        array_push($optionsArray, $option['value']);
    }
}
var_dump($optionsArray);
exit;

$highest_weight_post_item = "SUMMER";
$post_code = "xyz@gmail.com.np";
$attributeCode = 'company_email';
$registrationInfo = $registrationCodeCollection->addFieldToFilter('attribute_code', $attributeCode)->addFieldToFilter('active', 1);
$registrationItem = $registrationInfo->getFirstItem();
$operation = $registrationItem->getData('comparison_operator');
$compare_code = $registrationItem->getData('code');
//echo $operation;
switch ($operation) {
    case 1:
//        $registrationInfo = $registrationInfo->addFieldToFilter('code', ['in' => $post_code])->getFirstItem();
//        $registrationInfo = $registrationInfo->addFieldToFilter('code', ['in' => $post_code])->getSelect();
     //   echo $registrationInfo;exit;
        if(strpos($post_code, $compare_code)) {
            echo 'exits';
        } else {
            echo 'dont exist';
        }
exit;

//echo $registrationInfo;exit;
        break;
    case 2:
       // $registrationInfo = $registrationInfo->addFieldToFilter('code', $post_code)->getFirstItem();
        if(!strpos($post_code, $compare_code)) {
            echo 'dont exist';
        } else {
            echo 'exist';
        }
        exit;
        break;
    case 3:
        //$registrationInfo = $registrationInfo->addFieldToFilter('code', $post_code)->getFirstItem();
        $ends = substr($post_code, strpos($post_code, '.') + 1);
        //if (str_ends_with($post_code, $compare_code)) {
        if($ends == $compare_code)
        {
            echo "ends with";
        } else {
            echo "dont ends with";
        }
        exit;
        break;
    case 4:
        //$registrationInfo = $registrationInfo->addFieldToFilter('code', $post_code)->getFirstItem();
        $ends = substr($post_code, strpos($post_code, '.') + 1);
        //if (!str_ends_with($post_code, $compare_code)) {
        //echo $ends;exit;
        if($ends !== $compare_code)
        {
            echo "dont ends with";
        } else {
            echo "ends with";
        }
        exit;
        break;
    case 5:
        //$registrationInfo = $registrationInfo->addFieldToFilter('code', $post_code)->getFirstItem();
        //begins with
        if(str_starts_with($post_code, $compare_code))
        {
            echo "starts with";
        } else {
            echo "doesnot starts with";
        }
        exit;
        break;
    case 6:
        //$registrationInfo = $registrationInfo->addFieldToFilter('code', $post_code)->getFirstItem();
        if(!str_starts_with($post_code, $compare_code))
        {
            echo "doesnot starts with";
        } else {
            echo "starts with";
        }
        exit;
        break;
    default:
        $registrationInfo = $registrationInfo->addFieldToFilter('code', $post_code)->getFirstItem();
}
echo $registrationInfo->getSelectSql(true);
exit;
echo $registrationInfo->getData('attribute_code');
exit;

$inviteInfo = $inviteCodeCollection->addFieldToFilter('code', $highest_weight_post_item)->getFirstItem();
if(!$inviteInfo->isEmpty()){
    echo $inviteInfo->getData('reusable');
} else {
    echo "empty";
}
exit;

$attrInfo = $eavEntityFactory->getAttribute(9, 'group_checkobx');
var_dump($attrInfo->getData('input_weight'));exit;

//$attribute = $attributeRepository->getAttributeObject();
//$currentAttributeCode = $attribute->getAttributeCode();
$currentAttributeCode = "test_multiselect";
$optionCollection = $optionCollection->addAttributeToFilter('attribute_code', $currentAttributeCode);
$optionArr = array();
foreach ($optionCollection as $option):
    $item = array("id" => $option->getData('option_id'),
        "weight" => $option->getData('weightage'));
    array_push($optionArr, $item);
endforeach;
foreach ($optionArr as $option){
    var_dump($option['id']);exit;
}
exit;
$attributeRepository = $objectManager->create('\Magento\Eav\Model\Config');
$optionCollectionFactory = $objectManager->create('\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory');
$attribute = $attributeRepository->getAttribute('additionalcustomerattributes', $attributeCode);
//$options = $attribute->getAttributeId();
$attributeid = $attribute->getAttributeId();
$storeId = 2;
$optionId = 16;

$optionCollection = $optionCollectionFactory->create()
    ->setPositionOrder('asc')
    ->setAttributeFilter($attributeid)
    ->setStoreFilter($storeId)
    //->addFieldToFilter('main_table.option_id', $optionId)
    ->load();
foreach ($optionCollection as $option) {
//    print("label: ".$option['label']); // Return option value (store label)
//    print("value: ".$option['value']);
    var_dump($option->getData());
}
//var_dump($options);
exit;
//
//var_dump($options);
//exit;

// Usage
// Initialize necessary classes
$optionManagement = $objectManager->get(\Magento\Eav\Api\AttributeOptionManagementInterface::class);
$optionInterfaceFactory = $objectManager->get(\Magento\Eav\Api\Data\AttributeOptionInterfaceFactory::class);
$optionCollectionFactory = $objectManager->get(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory::class);

$fmeAttributeModel = $objectManager->get(\FME\AdditionalCustomerAttributes\Model\Attribute::class);
$groupFactory = $objectManager->get(\Magento\Customer\Model\GroupFactory::class);
//$objectManager->get(\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory::class);


//$options = $attributeInfo>getAllOptions();

//get group id by group code
$groupCode = 'SPC_Cust';
$group = $groupFactory->create()->load($groupCode, 'customer_group_code');
var_dump($group->getId());
exit;
if ($group->getId()) {
    echo $group->getId();
}
exit;
//get option value by option id
$optionCollection = $optionCollectionFactory->create()
    ->setPositionOrder('asc')
    ->setStoreFilter(0)
    ->addFieldToFilter('main_table.option_id', 5)
    ->load();

foreach ($optionCollection as $option) {
    var_dump($option->getValue()); // Return option value
    echo "<br>";
}
exit;
// Your AttributeOptionValueGetter class definition and usage goes here
class AttributeOptionValueGetter
{
    protected $optionManagement;
    protected $optionInterfaceFactory;
    protected $optionCollectionFactory;

    public function __construct(
        \Magento\Eav\Api\AttributeOptionManagementInterface $optionManagement,
        \Magento\Eav\Api\Data\AttributeOptionInterfaceFactory $optionInterfaceFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $optionCollectionFactory
    ) {
        $this->optionManagement = $optionManagement;
        $this->optionInterfaceFactory = $optionInterfaceFactory;
        $this->optionCollectionFactory = $optionCollectionFactory;
    }

    public function getAdminValueForOption($attributeCode, $optionId)
    {
        try {
            // Load attribute option
            $optionCollection = $this->optionCollectionFactory->create()
                ->setAttributeFilter($attributeCode)
                ->setIdFilter($optionId)
                ->setPositionOrder('asc', true)
                ->load();

            // Get admin value
            $adminValue = '';

            foreach ($optionCollection as $option) {
                $adminValue = $option->getAdminValue();
                echo $adminValue;
                echo "<br>";
            }
            exit;

            return $adminValue;
        } catch (\Exception $e) {
            // Handle exception
            echo "Error: " . $e->getMessage();
            return '';
        }
    }
}

// Usage
$attributeCode = 'fme_customer_group';
$optionId = 4; // Option ID

//$valueGetter = new AttributeOptionValueGetter(
//    $optionManagement,
//    $optionInterfaceFactory,
//    $optionCollectionFactory
//);
//
//$adminValue = $valueGetter->getAdminValueForOption($attributeCode, $optionId);
$adminValue = $fmeAttributeModel->getOptionValueById($optionId);
echo "Admin Value: " . $adminValue;
