<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Adminhtml VAT ID validation block
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace Smartwave\Porto\Block\System\Config\Form\Button\Import;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use Smartwave\Porto\Helper\Data;

/**
 * Class block Demo to render demos
 */
class Demo extends Field
{
    /**
     * @var string
     */
    protected string $_buttonLabel = 'Import';

    /**
     * @var string
     */
    protected string $_actionUrl;

    /**
     * @var string
     */
    protected string $_demoVersion;

    /**
     * @var Data
     */
    private Data $dataHelper;

    /**
     * Construct
     *
     * @param Data $dataHelper
     * @param Context $context
     * @param array $data
     * @param SecureHtmlRenderer|null $secureRenderer
     */
    public function __construct(
        Data $dataHelper,
        Context $context,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        parent::__construct(
            $context,
            $data,
            $secureRenderer
        );
        $this->dataHelper = $dataHelper;
    }

    /**
     * Set Button Label
     *
     * @param string $buttonLabel
     * @return Demo
     */
    public function setButtonLabel(string $buttonLabel): Demo
    {
        $this->_buttonLabel = $buttonLabel;
        return $this;
    }

    /**
     * Get Action Url
     *
     * @return string
     */
    public function getActionUrl(): string
    {
        return $this->_actionUrl;
    }

    /**
     * Set Validate VAT Button Label
     *
     * @param string $_actionUrl
     * @return Demo
     */
    public function setActionUrl(string $_actionUrl): Demo
    {
        $this->_actionUrl = $_actionUrl;

        return $this;
    }
    
    /**
     * Get Import Type
     *
     * @return string
     */
    public function getDemoVersion(): string
    {
        return $this->_demoVersion;
    }

    /**
     * Set Validate VAT Button Label
     *
     * @param string $_demoVersion
     * @return Demo
     */
    public function setDemoVersion(string $_demoVersion): Demo
    {
        $this->_demoVersion = $_demoVersion;

        return $this;
    }
    
    /**
     * Set template to itself
     *
     * @return Demo
     */
    protected function _prepareLayout(): Demo
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('system/config/demo_button.phtml');
        }

        return $this;
    }

    /**
     * Unset some non-related element parameters
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element): string
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        return parent::render($element);
    }

    /**
     * Get Demos
     *
     * @return array[]
     */
    public function getDemos(): array
    {
        return  [
            ['demo_version' => 'demo01', 'label' => __('Demo 1')],
            ['demo_version' => 'demo02', 'label' => __('Demo 2')],
            ['demo_version' => 'demo03', 'label' => __('Demo 3')],
            ['demo_version' => 'demo04', 'label' => __('Demo 4')],
            ['demo_version' => 'demo05', 'label' => __('Demo 5')],
            ['demo_version' => 'demo06', 'label' => __('Demo 6')],
            ['demo_version' => 'demo07', 'label' => __('Demo 7')],
            ['demo_version' => 'demo08', 'label' => __('Demo 8')],
            ['demo_version' => 'demo09', 'label' => __('Demo 9')],
            ['demo_version' => 'demo10', 'label' => __('Demo 10')],
            ['demo_version' => 'demo11', 'label' => __('Demo 11')],
            ['demo_version' => 'demo12', 'label' => __('Demo 12')],
            ['demo_version' => 'demo13', 'label' => __('Demo 13')],
            ['demo_version' => 'demo14', 'label' => __('Demo 14')],
            ['demo_version' => 'demo15', 'label' => __('Demo 15')],
            ['demo_version' => 'demo16', 'label' => __('Demo 16')],
            ['demo_version' => 'demo17', 'label' => __('Demo 17')],
            ['demo_version' => 'demo18', 'label' => __('Demo 18')],
            ['demo_version' => 'demo19', 'label' => __('Demo 19')],
            ['demo_version' => 'demo20', 'label' => __('Demo 20')],
            ['demo_version' => 'demo21', 'label' => __('Demo 21')],
            ['demo_version' => 'demo22', 'label' => __('Demo 22')],
            ['demo_version' => 'demo23', 'label' => __('Demo 23')],
            ['demo_version' => 'demo24', 'label' => __('Demo 24')],
            ['demo_version' => 'demo25', 'label' => __('Demo 25')],
            ['demo_version' => 'demo01_old', 'label' => __('Old - Demo 1')],
            ['demo_version' => 'demo02_old', 'label' => __('Old - Demo 2')],
            ['demo_version' => 'demo03_old', 'label' => __('Old - Demo 3')],
            ['demo_version' => 'demo04_old', 'label' => __('Old - Demo 4')],
            ['demo_version' => 'demo05_old', 'label' => __('Old - Demo 5')],
            ['demo_version' => 'demo06_old', 'label' => __('Old - Demo 6')],
            ['demo_version' => 'demo07_old', 'label' => __('Old - Demo 7')],
            ['demo_version' => 'demo08_old', 'label' => __('Old - Demo 8')],
            ['demo_version' => 'demo09_old', 'label' => __('Old - Demo 9')],
            ['demo_version' => 'demo10_old', 'label' => __('Old - Demo 10')],
            ['demo_version' => 'demo11_old', 'label' => __('Old - Demo 11')],
            ['demo_version' => 'demo12_old', 'label' => __('Old - Demo 12')],
            ['demo_version' => 'demo13_old', 'label' => __('Old - Demo 13')],
            ['demo_version' => 'demo14_old', 'label' => __('Old - Demo 14')],
            ['demo_version' => 'demo15_old', 'label' => __('Old - Demo 15')],
            ['demo_version' => 'demo16_old', 'label' => __('Old - Demo 16')],
            ['demo_version' => 'demo17_old', 'label' => __('Old - Demo 17')],
            ['demo_version' => 'demo18_old', 'label' => __('Old - Demo 18')],
            ['demo_version' => 'demo19_old', 'label' => __('Old - Demo 19')],
            ['demo_version' => 'demo20_old', 'label' => __('Old - Demo 20')],
        ];
    }

    /**
     * Get the button and scripts contents
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $originalData = $element->getOriginalData();
        $_buttonLabel = !empty($originalData['button_label']) ?
            $originalData['button_label'] : $this->_buttonLabel;
        $action = !empty($originalData['action_url']) ?
            $originalData['action_url'] : '';
        if ($action) {
            $this->setActionUrl($action);
        }

        $afterHtml = "";
        $buttonClass = "";
        if (!$this->dataHelper->checkPurchaseCode()) {
            $buttonClass = "disabled";
            $afterHtml = '<em style="color:#f00;font-size:10px;';
            $afterHtml .= 'line-height:1;">Activation is required.</em>';
        }

        $this->addData(
            [
                'button_label' => __($_buttonLabel),
                'button_class' => $buttonClass,
                'html_id' => $element->getHtmlId(),
                'ajax_url' => $this->_urlBuilder->getUrl($action),
            ]
        );

        return $this->_toHtml().$afterHtml;
    }
}
