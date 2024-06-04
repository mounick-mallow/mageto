<?php
namespace LiveChat\LiveChat\Block\System\Config;

use \LiveChat\LiveChat\Helper\Data;

class LiveChatForm extends \Magento\Framework\View\Element\Template
{
    /**
     * Path to block template
     */
    public const CHECK_TEMPLATE = 'system/config/livechat_form.phtml';
    
    /**
     * Live chat data helper
     *
     * @var Data
     */
    private $dataHelper;

    /**
     * url interface class
     *
     * @var string
     */
    public $urlinterface;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Data $dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dataHelper = $dataHelper;
        $this->urlinterface = $context->getUrlBuilder();
    }
    
    /**
     * Prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::CHECK_TEMPLATE);
        }
        return $this;
    }
    
    /**
     * Get license id
     *
     * @return int|string
     */
    public function getLicenseId()
    {
        return $this->dataHelper->getLicenseId();
    }
    
    /**
     * Get license email
     *
     * @return string
     */
    public function getLicenseEmail()
    {
        return $this->dataHelper->getLicenseEmail();
    }
    
    /**
     * Is cart products
     *
     * @return boolean
     */
    public function isSetCartProducts()
    {
        return $this->dataHelper->showCustomParam(Data::LC_CP_SHOW_CART_PRODUCTS);
    }
    
    /**
     * Is set total cart value
     *
     * @return boolean
     */
    public function isSetTotalCartValue()
    {
        return $this->dataHelper->showCustomParam(Data::LC_CP_SHOW_TOTAL_CART_VALUE);
    }
    
    /**
     * Is set total order count
     *
     * @return boolean
     */
    public function isSetTotalOrdersCount()
    {
        return $this->dataHelper->showCustomParam(Data::LC_CP_SHOW_TOTAL_ORDERS_COUNT);
    }
    
    /**
     * Is set last order details
     *
     * @return boolean
     */
    public function isSetLastOrderDetalils()
    {
        return $this->dataHelper->showCustomParam(Data::LC_CP_SHOW_LAST_ORDER_DETAILS);
    }
}
