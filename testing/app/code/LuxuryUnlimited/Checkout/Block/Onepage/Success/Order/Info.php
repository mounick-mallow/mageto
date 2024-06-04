<?php

namespace LuxuryUnlimited\Checkout\Block\Onepage\Success\Order;

use Dynamic\OrderTracking\Helper\Data as OrderTrackingHelper;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address\Renderer as AddressRenderer;

class Info extends \Magento\Sales\Block\Order\Info
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected \Magento\Checkout\Model\Session $_checkoutSession;

    /**
     * @var TimezoneInterface
     */
    protected TimezoneInterface $timezone;

    /**
     * @var OrderTrackingHelper
     */
    private OrderTrackingHelper $orderTrackingHelper;

    /**
     * @var DateTime
     */
    private DateTime $dateTime;

    /**
     * @param TemplateContext                 $context
     * @param Registry                        $registry
     * @param PaymentHelper                   $paymentHelper
     * @param AddressRenderer                 $addressRenderer
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param TimezoneInterface               $timezone
     * @param OrderTrackingHelper             $orderTrackingHelper
     * @param DateTime                        $dateTime
     * @param array                           $data
     */
    public function __construct(
        TemplateContext $context,
        Registry $registry,
        PaymentHelper $paymentHelper,
        AddressRenderer $addressRenderer,
        \Magento\Checkout\Model\Session $checkoutSession,
        TimezoneInterface $timezone,
        OrderTrackingHelper $orderTrackingHelper,
        DateTime $dateTime,
        array $data = []
    ) {
        parent::__construct($context, $registry, $paymentHelper, $addressRenderer, $data);
        $this->_checkoutSession = $checkoutSession;
        $this->timezone = $timezone;
        $this->orderTrackingHelper = $orderTrackingHelper;
        $this->dateTime = $dateTime;
    }

    /**
     * Prepare Layout
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        $infoBlock = $this->paymentHelper->getInfoBlock($this->getOrder()->getPayment(), $this->getLayout());
        $this->setChild('payment_info', $infoBlock);
    }

    /**
     * Get Order
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->_checkoutSession->getLastRealOrder();
    }

    /**
     * Get Converted Date
     *
     * @param  string $createdAt
     * @return string
     * @throws \Exception
     */
    public function getConvertedDate($createdAt)
    {
        return $this->timezone->date(new \DateTime($createdAt))->format('M d, Y');
    }

    /**
     * Get order status list
     *
     * @param Order $order
     *
     * @return array
     */
    public function getOrderStatusList(Order $order)
    {
        return $this->orderTrackingHelper->getOrderStatusList($order);
    }

    /**
     * Get Expected Shipment Date
     *
     * @param Order $order
     *
     * @return string
     */
    public function getExpectedShipmentDate(Order $order)
    {
        $orderItems = $order->getAllItems();
        $minDays = [];
        $maxDays = [];
        $minDay = 0;
        $maxDay = 0;
        foreach ($orderItems as $item) {
            if ($item->getParentItem()) {
                continue;
            }
            $product = $item->getProduct();
            if ($product->getResource()->getAttribute('estimated_minimum_days')) {
                $minDays[] = $product->getResource()->getAttribute('estimated_minimum_days')
                    ->getFrontend()->getValue($product);
            }

            if ($product->getResource()->getAttribute('estimated_maximum_days')) {
                $maxDays[] = $product->getResource()->getAttribute('estimated_maximum_days')
                    ->getFrontend()->getValue($product);
            }
        }
        if (!empty($minDays)) {
            $minDay = min($minDays);
        }
        if (!empty($maxDays)) {
            $maxDay = max($maxDays);
        }
        $date = $this->dateTime->date('Y-m-d');
        $minDay = $this->dateTime->date('M j', strtotime($date . " +" . $minDay . " days"));
        $maxDay = $this->dateTime->date('M j', strtotime($date . " +" . $maxDay . " days"));
        return $minDay . " - " . $maxDay;
    }
}
