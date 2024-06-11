<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Prasanna\Invitecode\Api;

interface InvitecodeManagementInterface
{

    /**
     * GET for Invitecode api
     * @param string $param
     * @return string
     */
    public function getInvitecode($param);
}

