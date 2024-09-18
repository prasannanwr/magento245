<?php
namespace Prasanna\Customrest\Api;

interface ProductRepositoryInterface
{
    /**
     * Return a filtered product.
     *
     * @param int $id
     * @return \Prasanna\Customrest\Api\ResponseItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItem(int $id);
    /**
     * Set descriptions for the products.
     *
     * @param \Prasanna\Customrest\Api\RequestItemInterface[] $products
     * @return void
     */
    public function setDescription(array $products);
}
