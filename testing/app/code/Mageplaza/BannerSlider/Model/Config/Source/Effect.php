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

use Magento\Framework\Option\ArrayInterface;

class Effect implements ArrayInterface
{
    public const SLIDER = 'slider';
    public const FADE_OUT = 'fadeOut';
    public const ROTATE_OUT = 'rotateOut';
    public const FLIP_OUT = 'flipOutX';
    public const ROLL_OUT = 'rollOut';
    public const ZOOM_OUT = 'zoomOut';
    public const SLIDER_OUT_LEFT = 'slideOutLeft';
    public const SLIDER_OUT_RIGHT = 'slideOutRight';
    public const LIGHT_SPEED_OUT = 'lightSpeedOut';

    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => self::SLIDER,
                'label' => __('No')
            ],
            [
                'value' => self::FADE_OUT,
                'label' => __('fadeOut')
            ],
            [
                'value' => self::ROTATE_OUT,
                'label' => __('rotateOut')
            ],
            [
                'value' => self::FLIP_OUT,
                'label' => __('flipOut')
            ],
            [
                'value' => self::ROLL_OUT,
                'label' => __('rollOut')
            ],
            [
                'value' => self::ZOOM_OUT,
                'label' => __('zoomOut')
            ],
            [
                'value' => self::SLIDER_OUT_LEFT,
                'label' => __('slideOutLeft')
            ],
            [
                'value' => self::SLIDER_OUT_RIGHT,
                'label' => __('slideOutRight')
            ],
            [
                'value' => self::LIGHT_SPEED_OUT,
                'label' => __('lightSpeedOut')
            ],
        ];

        return $options;
    }
}
