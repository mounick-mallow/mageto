<?php

namespace Smartwave\Porto\Block;

use Magento\Catalog\Block\Product\Context as ProductContext;
use Magento\Catalog\Block\Product\Image;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template as ViewTemplate;
use Magento\Framework\View\Element\Template\Context;
use Magento\Review\Model\Review\Summary;
use Magento\Review\Model\Review\SummaryFactory;

class RichSnippet extends ViewTemplate
{
    /**
     * @var Registry
     */
    protected Registry $coreRegistry;

    /**
     * @var ImageBuilder
     */
    protected ImageBuilder $imageBuilder;

    /**
     * @var SummaryFactory
     */
    protected SummaryFactory $reviewSummaryFactory;

    /**
     * Construct
     *
     * @param ProductContext $productContext
     * @param SummaryFactory $reviewSummaryFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        ProductContext $productContext,
        SummaryFactory $reviewSummaryFactory,
        Context $context,
        array $data = []
    ) {
        $this->coreRegistry = $productContext->getRegistry();
        $this->reviewSummaryFactory = $reviewSummaryFactory;
        $this->imageBuilder = $productContext->getImageBuilder();
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Get Product
     *
     * @return mixed|null
     */
    public function getProduct(): mixed
    {
        return $this->coreRegistry->registry('product');
    }

    /**
     * Get Image
     *
     * @param mixed $product
     * @param string $imageId
     * @param array $attributes
     * @return Image
     */
    public function getImage(
        mixed $product,
        string $imageId,
        array $attributes = []
    ): Image {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }

    /**
     * Get Review Summary
     *
     * @return Summary
     * @throws NoSuchEntityException
     */
    public function getReviewSummary(): Summary
    {
        $storeId = $this->_storeManager->getStore()->getId();
        $reviewSummary = $this->reviewSummaryFactory->create();
        $reviewSummary->setData('store_id', $storeId);

        return $reviewSummary->load($this->getProduct()->getId());
    }
}
