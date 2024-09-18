<?php
/**
 *
 * @category : WePay
 * @Package  : WePay_AdditionalCustomerAttributes
 * @Author   : WePay Extensions <support@wepayextensions.com>
 * @copyright Copyright 2018 © wepayextensions.com All right reserved
 * @license https://wepayextensions.com/LICENSE.txt
 */
namespace WePay\AdditionalCustomerAttributes\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Filesystem;

/**
 * Attribute model
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomerValues extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY = 'wepay_additionalcustomerattributes';

    /**
     * Product cache tag
     */
    const CACHE_TAG = 'wepay_additionalcustomerattributes';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * @var string
     */
    protected $_eventPrefix = 'wepay_additionalcustomerattributes';

    /**
     * @var string
     */
    protected $_eventObject = 'additionalcustomerattributes';

    /**
     * @var string
     */
    private $resource;

    /**
     * Entity factory1
     *
     * @var \Magento\Eav\Model\EntityFactory
     */
    private $eavEntityFactory;
    /**
     * File Uploader factory.
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $fileUploaderFactory;
 
    private $mediaDirectory;

    /**
     * Product constructor.
     * @param \WePay\AdditionalCustomerAttributes\Model\ResourceModel\Attribute $resource
     * @param \Magento\Eav\Model\EntityFactory $eavEntityFactory
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        \WePay\AdditionalCustomerAttributes\Model\ResourceModel\CustomerValues $resource,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
    ) {
        $this->resource = $resource;
        $this->eavEntityFactory = $eavEntityFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->fileUploaderFactory = $fileUploaderFactory;
    }

    /**
     * Initialize resources
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('WePay\AdditionalCustomerAttributes\Model\ResourceModel\CustomerValues');
    }

    /**
     * Retrieve Option Value by Id
     *
     * @return int
     */
    public function validateCustomerValues($data, $entityTypeId = null)
    {
        if ($entityTypeId == null || $entityTypeId < 1) {
            $entityTypeId = (int)$this->eavEntityFactory->create()->setType(
                \WePay\AdditionalCustomerAttributes\Model\Attribute::ENTITY
            )->getTypeId();
        }
        try {
            foreach ($data as $key => $value) {
                if (stripos($key, 'wepay_') !== false) {
                    $attribute_code = str_replace('wepay_', '', $key);
                    $value = is_array($value)?implode(",", $value):$value;
                    if (strpos($key, '_org_value') !== false) {
                        $attribute_code = str_replace('_org_value', '', $attribute_code);
                        $browseKey = 'wepay_'.$attribute_code.'_browsebutton';
                        $ret = $this->validateUploadFiles($attribute_code, $browseKey, $value, $entityTypeId);
                    }
                }
            }
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __($e->getMessage())
            );
            return false;
        }
        return true;
    }
    public function validateUploadFiles($code, $fileId, $existing = null, $entityTypeId = null)
    {
        if ($entityTypeId == null || $entityTypeId < 1) {
            $entityTypeId = (int)$this->eavEntityFactory->create()->setType(
                \WePay\AdditionalCustomerAttributes\Model\Attribute::ENTITY
            )->getTypeId();
        }
        $configurations = $this->resource->getFileParams($code, $entityTypeId);
        $allowed = explode(",", $configurations['wepay_extensions']);
        $isRequried = $configurations['is_required'];
        $label = $configurations['frontend_label'];
        try {
            $target = $this->mediaDirectory->getAbsolutePath('aca/image/');
            /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
            $uploader = $this->fileUploaderFactory->create(
                ['fileId' => $fileId]
            );
            if (is_array($allowed) && !empty($allowed)) {
                $uploader->setAllowedExtensions(
                    $allowed
                );
            }
            $uploader->validateFile();
            $fileSizeUploaded = $uploader->getFileSize();
            if ($configurations['wepay_max_size'] &&
                $configurations['wepay_max_size'] > 0 &&
                $fileSizeUploaded > ($configurations['wepay_max_size'] * 1048576)
            ) {
                throw new \Magento\Framework\Exception\LocalizedException(__(
                    "File size exceeds the limit (".$configurations['wepay_max_size']."Mb) for ".$label
                ));
            }
            return true;
        } catch (\Exception $e) {
            if ($e->getMessage() == '$_FILES array is empty' || $e->getCode() == 666) {
                if (!$isRequried || ($isRequried && $existing !='')) {
                    return true;
                } elseif ($isRequried && $existing =='') {
                throw new \Magento\Framework\Exception\LocalizedException(__($label." ".$e->getMessage()));
                }
            }
            throw new \Magento\Framework\Exception\LocalizedException(__($label .": " .$e->getMessage()));
        }
    }
    /**
     * Retrieve Option Value by Id
     *
     * @return int
     */
    public function saveCustomerValues($data, $customer_id, $entityTypeId = null)
    {
        if ($entityTypeId == null || $entityTypeId < 1) {
            $entityTypeId = (int)$this->eavEntityFactory->create()->setType(
                \WePay\AdditionalCustomerAttributes\Model\Attribute::ENTITY
            )->getTypeId();
        }
        foreach ($data as $key => $value) {
            if (stripos($key, 'wepay_') !== false) {
                $attribute_code = str_replace('wepay_', '', $key);
                if (is_array($value)) {
                    if ($this->getAttributeType($attribute_code) == "checkbox") {
                        $checkboxValues = [];
                        foreach ($value as $key2 => $val) {
                            if($val == 1) {
                                $checkboxValues[] = $key2;
                            }
                        }
                        $value = implode(",", $checkboxValues);

                    } else {
                        $value = implode(",", $value);
                    }
                }
                if (strpos($key, '_org_value') !== false) {
                    $attribute_code = str_replace('_org_value', '', $attribute_code);
                    $browseKey = 'wepay_'.$attribute_code.'_browsebutton';
                    $ret = $this->uploadFiles($attribute_code, $browseKey, $value, $entityTypeId);
                    if ($ret !== false && $ret != "") {
                        $value = $ret;
                    }
                    unset($data['wepay_'.$attribute_code.'_org_value']);
                }
                if ($key == 'wepay_'.$attribute_code.'_browsebutton') {
                    continue;
                }
                $this->saveCustomerValue($attribute_code, $value, $customer_id, $entityTypeId);
            }
        }
        return;
    }
    
    /**
     * Retrieve attribute element type
     *
     * @return string
     */
    public function getAttributeType($code, $entityTypeId = null)
    {
        if ($entityTypeId == null || $entityTypeId < 1) {
            $entityTypeId = (int)$this->eavEntityFactory->create()->setType(
                \WePay\AdditionalCustomerAttributes\Model\Attribute::ENTITY
            )->getTypeId();
        }
        return $this->resource->getAttributeType($code, $entityTypeId);
    }

    /**
     * Save values
     *
     * @return int
     */
    public function saveCustomerValue($code, $value, $customer_id, $entityTypeId = null)
    {
        if ($entityTypeId == null || $entityTypeId < 1) {
            $entityTypeId = (int)$this->eavEntityFactory->create()->setType(
                \WePay\AdditionalCustomerAttributes\Model\Attribute::ENTITY
            )->getTypeId();
        }
        return $this->resource->saveCustomerValues($code, $value, $customer_id, $entityTypeId);
    }

    /**
     * Upload files
     *
     * @return string
     */
    public function uploadFiles($code, $fileId, $existing = null, $entityTypeId = null)
    {
        if ($entityTypeId == null || $entityTypeId < 1) {
            $entityTypeId = (int)$this->eavEntityFactory->create()->setType(
                \WePay\AdditionalCustomerAttributes\Model\Attribute::ENTITY
            )->getTypeId();
        }
        $configurations = $this->resource->getFileParams($code, $entityTypeId);
        $allowed = explode(",", $configurations['wepay_extensions']);
        $isRequried = $configurations['is_required'];
        $label = $configurations['frontend_label'];
        
        try {
            $target = $this->mediaDirectory->getAbsolutePath('aca/image/');
            /** @var $uploader \Magento\MediaStorage\Model\File\Uploader */
           // echo "size is ". $fileId;exit;
            $uploader = $this->fileUploaderFactory->create(
                ['fileId' => $fileId]
            );

            if (is_array($allowed) && !empty($allowed)) {
                $uploader->setAllowedExtensions(
                    $allowed
                );
            }
            $uploader->validateFile();
            $fileSizeUploaded = $uploader->getFileSize();

            if ($configurations['wepay_max_size'] &&
                $configurations['wepay_max_size'] > 0 &&
                $fileSizeUploaded > ($configurations['wepay_max_size'] * 1048576)
            ) {
                throw new \Magento\Framework\Exception\LocalizedException(__(
                    "File size exceeds the limit (".$configurations['wepay_max_size']."Mb) for ".$label
                ));
            }
            $uploader->setAllowRenameFiles(true);
            $resul = $uploader->save($target);
            return $resul['file'];
        } catch (\Exception $e) {
            if ($e->getMessage() == '$_FILES array is empty' || $e->getCode() == 666) {
                if (!$isRequried || ($isRequried && $existing !='')) {
                    return $existing;
                } elseif ($isRequried && $existing =='') {
                    throw new \Magento\Framework\Exception\LocalizedException(__($label." ".$e->getMessage()));
                }
            }
            throw new \Magento\Framework\Exception\LocalizedException(__($label .": " .$e->getMessage()));
        }
    }
    /**
     * Retrieve dependent attributes
     *
     * @return array
     */
    public function getDependableAttributes()
    {
        
        $entityTypeId = (int)$this->eavEntityFactory->create()->setType(
            \WePay\AdditionalCustomerAttributes\Model\Attribute::ENTITY
        )->getTypeId();
        
        return $this->resource->getDependableAttributes($entityTypeId);
    }
    /**
     * Retrieve Option Value by Id
     *
     * @return int
     */
    public function getOptionValueById($id, $storeId)
    {
        return $this->resource->getOptionValueById($id, $storeId);
    }
    /**
     * Identifier getter
     *
     * @return int
     */
    public function getId()
    {
        return $this->_getData('entity_id');
    }

    /**
     * Set entity Id
     *
     * @param int $value
     * @return $this
     */
    public function setId($value)
    {
        return $this->setData('entity_id', $value);
    }
}
