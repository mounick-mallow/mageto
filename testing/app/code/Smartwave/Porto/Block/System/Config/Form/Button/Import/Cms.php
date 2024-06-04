<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Smartwave\Porto\Block\System\Config\Form\Button\Import;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use Smartwave\Porto\Helper\Data;

/**
 * Class Cms Field
 */
class Cms extends Field
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
    protected string $_importType;

    /**
     * @var Data
     */
    private Data $dataHelper;

    /**
     * Construct
     *
     * @param Data $dataHelper
     * @param Context $context
     */
    public function __construct(
        Data $dataHelper,
        Context $context
    ) {
        parent::__construct(
            $context
        );
        $this->dataHelper = $dataHelper;
    }
    
    /**
     * Set Button Label
     *
     * @param string $buttonLabel
     * @return Cms
     */
    public function setButtonLabel(string $buttonLabel): Cms
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
     * @param string $actionUrl
     * @return Cms
     */
    public function setActionUrl(string $actionUrl): Cms
    {
        $this->_actionUrl = $actionUrl;

        return $this;
    }
    
    /**
     * Get Import Type
     *
     * @return string
     */
    public function getImportType(): string
    {
        return $this->_importType;
    }

    /**
     * Set Validate VAT Button Label
     *
     * @param string $importType
     * @return Cms
     */
    public function setImportType(string $importType): Cms
    {
        $this->_importType = $importType;

        return $this;
    }
    
    /**
     * Set template to itself
     *
     * @return Cms
     */
    protected function _prepareLayout(): Cms
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('system/config/cms_button.phtml');
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
     * Get the button and scripts contents
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $originalData = $element->getOriginalData();
        $buttonLabel = !empty($originalData['button_label']) ?
            $originalData['button_label'] : $this->_buttonLabel;
        $action = !empty($originalData['action_url']) ?
            $originalData['action_url'] : '';
        if ($action) {
            $this->setActionUrl($action);
        }
        $type = !empty($originalData['import_type']) ?
            $originalData['import_type'] : '';
        if ($type) {
            $this->setImportType($type);
        }
        $afterHtml = "";
        $buttonClass = "";
        if (!$this->dataHelper->checkPurchaseCode()) {
            $buttonClass = "disabled";
            $afterHtml = '<em style="color:#f00;font-size:10px;';
            $afterHtml .='line-height:1;">Activation is required.</em>';
        }
        $this->addData(
            [
                'button_label' => __($buttonLabel),
                'import_type' => $type,
                'button_class' => $buttonClass,
                'html_id' => $element->getHtmlId(),
                'ajax_url' => $this->_urlBuilder->getUrl($action),
            ]
        );

        return $this->_toHtml() . $afterHtml;
    }
}
