<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace WePay\Registrationcode\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface RegistrationCodeRepositoryInterface
{

    /**
     * Save registration_code
     * @param \WePay\Registrationcode\Api\Data\RegistrationCodeInterface $registrationCode
     * @return \WePay\Registrationcode\Api\Data\RegistrationCodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \WePay\Registrationcode\Api\Data\RegistrationCodeInterface $registrationCode
    );

    /**
     * Retrieve registration_code
     * @param string $registrationCodeId
     * @return \WePay\Registrationcode\Api\Data\RegistrationCodeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($registrationCodeId);

    /**
     * Retrieve registration_code matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \WePay\Registrationcode\Api\Data\RegistrationCodeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete registration_code
     * @param \WePay\Registrationcode\Api\Data\RegistrationCodeInterface $registrationCode
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \WePay\Registrationcode\Api\Data\RegistrationCodeInterface $registrationCode
    );

    /**
     * Delete registration_code by ID
     * @param string $registrationCodeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($registrationCodeId);
}

