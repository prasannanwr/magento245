<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Prasanna\Invitecode\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface InviteCodeRepositoryInterface
{

    /**
     * Save invite_code
     * @param \Prasanna\Invitecode\Api\Data\InviteCodeInterface $inviteCode
     * @return \Prasanna\Invitecode\Api\Data\InviteCodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Prasanna\Invitecode\Api\Data\InviteCodeInterface $inviteCode
    );

    /**
     * Retrieve invite_code
     * @param string $inviteCodeId
     * @return \Prasanna\Invitecode\Api\Data\InviteCodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($inviteCodeId);

    /**
     * Retrieve invite_code matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Prasanna\Invitecode\Api\Data\InviteCodeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete invite_code
     * @param \Prasanna\Invitecode\Api\Data\InviteCodeInterface $inviteCode
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Prasanna\Invitecode\Api\Data\InviteCodeInterface $inviteCode
    );

    /**
     * Delete invite_code by ID
     * @param string $inviteCodeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($inviteCodeId);
}

