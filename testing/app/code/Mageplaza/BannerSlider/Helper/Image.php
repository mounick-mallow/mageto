<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_BannerSlider
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\BannerSlider\Helper;

use Mageplaza\Core\Helper\Media;

class Image extends Media
{
    public const TEMPLATE_MEDIA_PATH = 'mageplaza/bannerslider';
    public const TEMPLATE_MEDIA_TYPE_BANNER = 'banner/image';
    public const TEMPLATE_MEDIA_TYPE_SLIDER = 'slider/image';
}
