<?php
/**
 * Strategery Infinitescroll - Magento 2 Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0),
 * available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * @license http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @copyright Copyright (c) 2016 Strategery Inc. (http://www.strategery.io/)
 * @author Damian A. Pastorini (damian.pastorini@strategery.io)
 */

namespace Strategery\Infinitescroll\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Strategery\Infinitescroll\ViewModel\ProductListScroll;

/**
 * Class block Init
 */
class Init extends Template
{
    /**
     * @var ProductListScroll
     */
    private ProductListScroll $productListScroll;

    /**
     * Init constructor.
     *
     * @param Context $context
     * @param ProductListScroll $productListScroll
     * @param array $data
     */
    public function __construct(
        Context $context,
        ProductListScroll $productListScroll,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productListScroll = $productListScroll;
    }

   /**
    * Function getLoaderImage()
    *
    * @return false|mixed
    */
    public function getLoaderImage(): mixed
    {
        $url = $this->productListScroll->getScrollConfig('design/loading_img');
        if (!empty($url)) {
            // phpstan:ignore "Call to an undefined method"
            $url = str_starts_with($url, 'http') ? $url : $this->getSkinUrl($url);
        }
        return empty($url) ? false : $url;
    }
}
