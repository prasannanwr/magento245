<?php
use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();

$eavConfig = $objectManager->get(\Magento\Eav\Model\Config::class);
//get checkbox options
$attributeCode = 'cg_radio';
// Check if the attribute is a checkbox type
$objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
//$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
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
