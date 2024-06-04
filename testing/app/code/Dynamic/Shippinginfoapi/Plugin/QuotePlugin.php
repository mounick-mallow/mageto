<?php

/**
 * Copyright © 2022 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Dynamic\Shippinginfoapi\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Api\CartTotalRepositoryInterface;

class QuotePlugin
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var Item
     */
    protected $quoteItem;

    /**
     * Constructor
     *
     * @param ProductRepositoryInterface $productRepository
     * @param Item $quoteItem
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        Item $quoteItem
    ) {
        $this->productRepository  = $productRepository;
        $this->quoteItem   = $quoteItem;
    }

    /**
     * Add attribute values
     *
     * @param CartTotalRepositoryInterface $subject
     * @param mixed $quotetotals
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        CartTotalRepositoryInterface $subject,
        $quotetotals
    ) {
        $quoteData = $this->setAttributeValue($quotetotals);
        return $quoteData;
    }

    /**
     * Set value of attributes
     *
     * @param mixed $quoteTotals
     * @return mixed
     */
    public function setAttributeValue($quoteTotals)
    {
        if (count($quoteTotals->getItems())) {
            foreach ($quoteTotals->getItems() as $item) {
                $extensionAttributes = $item->getExtensionAttributes();

                $quoteItem = $this->quoteItem->load($item->getItemId());
                $productData = $this->productRepository->getById($quoteItem->getProductId());
                $extensionAttributes->setImage($productData->getThumbnail());

                $item->setExtensionAttributes($extensionAttributes);
            }
        }

        return $quoteTotals;
    }
}
