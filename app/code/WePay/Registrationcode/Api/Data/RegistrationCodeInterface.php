<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace WePay\Registrationcode\Api\Data;

interface RegistrationCodeInterface
{

    const CUSTOMER_GROUP = 'customer_group';
    const REGISTRATION_CODE_ID = 'registration_code_id';
    const ENTITY_ID = 'entity_id';
    const CASE_SENSITIVE = 'case_sensitive';
    const ATTRIBUTE_CODE = 'attribute_code';
    const CREATED_DATE = 'created_date';
    const COUNT = 'count';
    const CODE = 'code';
    const REUSABLE = 'reusable';

    /**
     * Get registration_code_id
     * @return string|null
     */
    public function getRegistrationCodeId();

    /**
     * Set registration_code_id
     * @param string $registrationCodeId
     * @return \WePay\Registrationcode\Registrationcode\Api\Data\RegistrationCodeInterface
     */
    public function setRegistrationCodeId($registrationCodeId);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \WePay\Registrationcode\Registrationcode\Api\Data\RegistrationCodeInterface
     */
    public function setEntityId($entityId);

    /**
     * Get attribute_code
     * @return string|null
     */
    public function getAttributeCode();

    /**
     * Set attribute_code
     * @param string $attributeCode
     * @return \WePay\Registrationcode\Registrationcode\Api\Data\RegistrationCodeInterface
     */
    public function setAttributeCode($attributeCode);

    /**
     * Get code
     * @return string|null
     */
    public function getCode();

    /**
     * Set code
     * @param string $code
     * @return \WePay\Registrationcode\Registrationcode\Api\Data\RegistrationCodeInterface
     */
    public function setCode($code);

    /**
     * Get reusable
     * @return string|null
     */
    public function getReusable();

    /**
     * Set reusable
     * @param string $reusable
     * @return \WePay\Registrationcode\Registrationcode\Api\Data\RegistrationCodeInterface
     */
    public function setReusable($reusable);

    /**
     * Get case_sensitive
     * @return string|null
     */
    public function getCaseSensitive();

    /**
     * Set case_sensitive
     * @param string $caseSensitive
     * @return \WePay\Registrationcode\Registrationcode\Api\Data\RegistrationCodeInterface
     */
    public function setCaseSensitive($caseSensitive);

    /**
     * Get customer_group
     * @return string|null
     */
    public function getCustomerGroup();

    /**
     * Set customer_group
     * @param string $customerGroup
     * @return \WePay\Registrationcode\Registrationcode\Api\Data\RegistrationCodeInterface
     */
    public function setCustomerGroup($customerGroup);

    /**
     * Get count
     * @return string|null
     */
    public function getCount();

    /**
     * Set count
     * @param string $count
     * @return \WePay\Registrationcode\Registrationcode\Api\Data\RegistrationCodeInterface
     */
    public function setCount($count);

    /**
     * Get created_date
     * @return string|null
     */
    public function getCreatedDate();

    /**
     * Set created_date
     * @param string $createdDate
     * @return \WePay\Registrationcode\Registrationcode\Api\Data\RegistrationCodeInterface
     */
    public function setCreatedDate($createdDate);
}

