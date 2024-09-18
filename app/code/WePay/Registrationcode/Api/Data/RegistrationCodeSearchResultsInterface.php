<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace WePay\Registrationcode\Api\Data;

interface RegistrationCodeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get registration_code list.
     * @return \WePay\Registrationcode\Api\Data\RegistrationCodeInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \WePay\Registrationcode\Api\Data\RegistrationCodeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

