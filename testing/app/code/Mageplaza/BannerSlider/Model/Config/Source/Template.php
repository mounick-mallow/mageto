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

namespace Mageplaza\BannerSlider\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Asset\Repository;
use Mageplaza\BannerSlider\Helper\Data;

class Template implements OptionSourceInterface
{
    public const DEMO1 = 'demo1.jpg';
    public const DEMO2 = 'demo2.jpg';
    public const DEMO3 = 'demo3.jpg';
    public const DEMO4 = 'demo4.jpg';
    public const DEMO5 = 'demo5.jpg';

    /**
     * @var Repository
     */
    private $_assetRepo;

    /**
     * Template constructor.
     *
     * @param Repository $assetRepo
     */
    public function __construct(Repository $assetRepo)
    {
        $this->_assetRepo = $assetRepo;
    }

    /**
     * Retrieve option array with empty value
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => self::DEMO1,
                'label' => __('Demo template 1')
            ],
            [
                'value' => self::DEMO2,
                'label' => __('Demo template 2')
            ],
            [
                'value' => self::DEMO3,
                'label' => __('Demo template 3')
            ],
            [
                'value' => self::DEMO4,
                'label' => __('Demo template 4')
            ],
            [
                'value' => self::DEMO5,
                'label' => __('Demo template 5')
            ],
        ];

        return $options;
    }

    /**
     * Get template html
     *
     * @return false|string
     */
    public function getTemplateHtml()
    {
        $imgTmp = '<div class="item" 
        style="background:url({{
            media url="mageplaza/bannerslider/banner/demo/{{imgName}}
            }}) center center no-repeat;background-size:cover;">
                            <div class="container" 
                            style="position:relative">
                                <img 
                                src="{{media url="mageplaza/bannerslider/banner/demo/{{imgName}}}}" 
                                alt="{{imgName}}">
                            </div>
                        </div>';
        $templates = [
            self::DEMO1 => [
                'tpl' => $imgTmp,
                'var' => '{{imgName}}'
            ],
            self::DEMO2 => [
                'tpl' => $imgTmp,
                'var' => '{{imgName}}'
            ],
            self::DEMO3 => [
                'tpl' => $imgTmp,
                'var' => '{{imgName}}'
            ],
            self::DEMO4 => [
                'tpl' => $imgTmp,
                'var' => '{{imgName}}'
            ],
            self::DEMO5 => [
                'tpl' => $imgTmp,
                'var' => '{{imgName}}'
            ],
        ];

        return Data::jsonEncode($templates);
    }

    /**
     * Get image urls
     *
     * @return false|string
     */
    public function getImageUrls()
    {
        $urls = [];
        foreach ($this->toOptionArray() as $template) {
            $urls[$template['value']] =
            $this->_assetRepo
            ->getUrl('Mageplaza_BannerSlider::images/' . $template['value']);
        }

        return Data::jsonEncode($urls);
    }
}
