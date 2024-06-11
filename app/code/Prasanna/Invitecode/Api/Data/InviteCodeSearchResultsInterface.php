<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Prasanna\Invitecode\Api\Data;

interface InviteCodeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get invite_code list.
     * @return \Prasanna\Invitecode\Api\Data\InviteCodeInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Prasanna\Invitecode\Api\Data\InviteCodeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

