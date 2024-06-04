<?php


namespace Sololuxury\BuyNow\Block\Product;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\ProductList\Item\Block;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;

class ListProduct extends Block
{
    /**
     * @var UrlHelper
     */
    protected UrlHelper $urlHelper;

    /**
     * ListProduct constructor.
     *
     * @param UrlHelper $urlHelper
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        UrlHelper $urlHelper,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->urlHelper = $urlHelper;
    }

    /**
     * Get post parameters
     *
     * @param Product $product
     * @return array
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function getAddToCartPostParams(Product $product): array
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }
}
