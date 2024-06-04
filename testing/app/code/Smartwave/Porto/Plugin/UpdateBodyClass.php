<?php

namespace Smartwave\Porto\Plugin;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Result\Page;
use Smartwave\Porto\ViewModel\Data;

/**
 * Class plugin for update body class
 */
class UpdateBodyClass
{
    /**
     * @var Context
     */
    protected Context $context;

    /**
     * @var Data
     */
    protected Data $dataViewModel;

    /**
     * Construct
     *
     * @param Context $context
     * @param Data $dataViewModel
     */
    public function __construct(
        Context $context,
        Data $dataViewModel
    ) {
        $this->context = $context;
        $this->dataViewModel = $dataViewModel;
    }

    /**
     * Before Render Result
     *
     * @param Page $subject
     * @param ResponseInterface $response
     * @return ResponseInterface[]
     */
    public function beforeRenderResult(
        Page $subject,
        ResponseInterface $response
    ): array {
        $pageLayout = $this->dataViewModel->getConfig(
            'porto_settings/general/layout'
        );
        if ($pageLayout == "full_width") {
            $pageLayout = "layout-fullwidth";
        } elseif ($pageLayout == "1140") {
            $pageLayout = "layout-1140";
        } elseif ($pageLayout == "1280") {
            $pageLayout = "layout-1280";
        }
        if ($pageLayout) {
            $subject->getConfig()->addBodyClass($pageLayout);
        }

        $boxed = $this->dataViewModel->getConfig('porto_settings/general/boxed');
        if ($boxed) {
            $subject->getConfig()->addBodyClass($boxed);
        }

        if ($this->dataViewModel->getConfig(
            'porto_settings/header/mobile_sticky_header'
        )) {
            $subject->getConfig()->addBodyClass("mobile-sticky");
        }

        if ($this->dataViewModel->getConfig(
            'porto_settings/header/header_type'
        ) == "10") {
            $subject->getConfig()->addBodyClass("side-header");
        }

        return [$response];
    }
}
