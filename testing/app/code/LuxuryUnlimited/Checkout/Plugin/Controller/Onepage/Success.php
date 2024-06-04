<?php

namespace LuxuryUnlimited\Checkout\Plugin\Controller\Onepage;

class Success
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @param \Magento\Framework\Registry     $coreRegistry
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_checkoutSession = $checkoutSession;
    }

    /**
     * *
     *
     * @param  \Magento\Checkout\Controller\Onepage\Success $subject
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeExecute(\Magento\Checkout\Controller\Onepage\Success $subject)
    {
        $currentOrder = $this->_checkoutSession->getLastRealOrder();
        $this->_coreRegistry->register('current_order', $currentOrder);
    }
}
