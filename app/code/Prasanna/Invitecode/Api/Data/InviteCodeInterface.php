<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Prasanna\Invitecode\Api\Data;

interface InviteCodeInterface
{

    const CUSTOMER_GROUP = 'customer_group';
    const INVITE_CODE_ID = 'invite_code_id';
    const ENTITY_ID = 'entity_id';
    const CASE_SENSITIVE = 'case_sensitive';
    const ATTRIBUTE_CODE = 'attribute_code';
    const CREATED_DATE = 'created_date';
    const COUNT = 'count';
    const CODE = 'code';
    const REUSABLE = 'reusable';

    /**
     * Get invite_code_id
     * @return string|null
     */
    public function getInviteCodeId();

    /**
     * Set invite_code_id
     * @param string $inviteCodeId
     * @return \Prasanna\Invitecode\InviteCode\Api\Data\InviteCodeInterface
     */
    public function setInviteCodeId($inviteCodeId);

    /**
     * Get entity_id
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     * @param string $entityId
     * @return \Prasanna\Invitecode\InviteCode\Api\Data\InviteCodeInterface
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
     * @return \Prasanna\Invitecode\InviteCode\Api\Data\InviteCodeInterface
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
     * @return \Prasanna\Invitecode\InviteCode\Api\Data\InviteCodeInterface
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
     * @return \Prasanna\Invitecode\InviteCode\Api\Data\InviteCodeInterface
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
     * @return \Prasanna\Invitecode\InviteCode\Api\Data\InviteCodeInterface
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
     * @return \Prasanna\Invitecode\InviteCode\Api\Data\InviteCodeInterface
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
     * @return \Prasanna\Invitecode\InviteCode\Api\Data\InviteCodeInterface
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
     * @return \Prasanna\Invitecode\InviteCode\Api\Data\InviteCodeInterface
     */
    public function setCreatedDate($createdDate);
}

