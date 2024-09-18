<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace WePay\Registrationcode\Api;

interface RegistrationCodeManagementInterface
{

    /**
     * GET for Registrationcode api
     * @param string $param
     * @return string
     */
    public function getRegistrationcode($param);
}

