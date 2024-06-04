<?php

namespace WeltPixel\OwlCarouselSlider\Controller\Banner;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use WeltPixel\OwlCarouselSlider\Helper\Custom as OwlHelper;
use WeltPixel\OwlCarouselSlider\Model\Slider;

/**
 * @SuppressWarnings(PHPMD.AllPurposeAction)
 */
class Validity extends Action
{
    /**
     * @var Slider
     */
    protected $sliderModel;

    /**
     * @var OwlHelper
     */
    protected $owlHelper;

    /**
     * Labels constructor.
     * @param Context $context
     * @param Slider $sliderModel
     * @param OwlHelper $owlHelper
     */
    public function __construct(
        Context $context,
        Slider $sliderModel,
        OwlHelper $owlHelper
    ) {
        $this->sliderModel = $sliderModel;
        $this->owlHelper = $owlHelper;
        parent::__construct($context);
    }

    /**
     * Execute Validity
     *
     * @return void
     */
    public function execute()
    {
        $sliderId = $this->getRequest()->getParam('slider_id');
        $result = [];

        if (!$sliderId) {
            $this->prepareResult($result);
            return;
        }

        $slider = $this->sliderModel->load($sliderId);
        $sliderBannersCollection = $slider->getSliderBanerCollection();
        foreach ($sliderBannersCollection as $banner) {
            if (!$this->owlHelper->validateBannerDisplayDate($banner)) {
                $result['invalidBanners'][] = 'banner-' . $banner->getId();
            }
        }

        $this->prepareResult($result);
    }

    /**
     * PrepareResult
     *
     * @param array $result
     * @return void
     */
    protected function prepareResult($result)
    {
        $jsonData = json_encode($result);
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }
}
