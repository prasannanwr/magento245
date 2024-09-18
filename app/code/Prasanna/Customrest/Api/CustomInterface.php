<?php
namespace Prasanna\Customrest\Api;

interface CustomInterface
{
    /**
     * GET for Custom API
     * @return string
     */
    public function getCustomData();

    /**
     * SET description
     * @return string
     */
    public function setDescription();

    /**
     * Get item
     */
    public function getItem();

}
