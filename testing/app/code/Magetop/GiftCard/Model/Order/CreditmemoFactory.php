<?php

namespace Magetop\GiftCard\Model\Order;

use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Message\ManagerInterfaceFactory;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Sales\Model\Convert\OrderFactory;
use Magento\Tax\Model\Config;

class CreditmemoFactory extends \Magento\Sales\Model\Order\CreditmemoFactory
{

    /**
     * @var ManagerInterfaceFactory
     */
    private $managerInterfaceFactory;

    /**
     * @param OrderFactory $convertOrderFactory
     * @param Config $taxConfig
     * @param JsonSerializer|null $serializer
     * @param FormatInterface|null $localeFormat
     * @param ManagerInterfaceFactory $managerInterfaceFactory
     */
    public function __construct(
        OrderFactory $convertOrderFactory,
        Config $taxConfig,
        JsonSerializer $serializer = null,
        FormatInterface $localeFormat = null,
        ManagerInterfaceFactory $managerInterfaceFactory
    ) {
        $this->managerInterfaceFactory = $managerInterfaceFactory;
        parent::__construct($convertOrderFactory, $taxConfig, $serializer, $localeFormat);
    }

    /**
     * Prepare order creditmemo based on order items and requested params
     *
     * @param \Magento\Sales\Model\Order $order
     * @param array $data
     * @return Creditmemo
     */
    public function createByOrder(\Magento\Sales\Model\Order $order, array $data = [])
    {
        $totalQty = 0;
        $creditmemo = $this->convertor->toCreditmemo($order);
        $qtys = isset($data['qtys']) ? $data['qtys'] : [];
        $i = 0;
        foreach ($order->getAllItems() as $orderItem) {
            if (!$this->canRefundItem($orderItem, $qtys)) {
                continue;
            }
            if ($orderItem->getData()['product_type'] == 'giftcard') {
                $i++;
                if ($i == 1) {
                    $messageManger = $this->managerInterfaceFactory->create();
                    $messageManger->addWarning(__('We can\'t save the credit memo for giftcard type product.'));
                }
                continue;
            }

            $item = $this->convertor->itemToCreditmemoItem($orderItem);
            if ($orderItem->isDummy()) {
                $qty = 1;
                $orderItem->setLockedDoShip(true);
            } else {
                if (isset($qtys[$orderItem->getId()])) {
                    $qty = (double)$qtys[$orderItem->getId()];
                } elseif (!count($qtys)) {
                    $qty = $orderItem->getQtyToRefund();
                } else {
                    continue;
                }
            }
            $totalQty += $qty;
            $item->setQty($qty);
            $creditmemo->addItem($item);
        }
        $creditmemo->setTotalQty($totalQty);

        $this->initData($creditmemo, $data);

        $creditmemo->collectTotals();
        return $creditmemo;
    }
}
