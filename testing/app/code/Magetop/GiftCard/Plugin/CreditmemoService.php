<?php

namespace Magetop\GiftCard\Plugin;

class CreditmemoService
{
    /**
     * After Refund
     *
     * @param \Magento\Sales\Model\Service\CreditmemoService $subject
     * @param mixed $result
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterRefund(\Magento\Sales\Model\Service\CreditmemoService $subject, $result)
    {
        $haveGiftCardItems = 0;
        $haveProductForRefund = 0;
        foreach ($result->getOrder()->getAllItems() as $items) {
            if ($haveGiftCardItems == 0) {
                if ($items->getProductType() == "giftcard") {
                    $haveGiftCardItems = 1;
                    continue;
                }
            }
            if (!($items->getQtyOrdered() == $items->getQtyRefunded())) {
                $haveProductForRefund = 1;
            }
        }
        if ($haveGiftCardItems==1 && $haveProductForRefund == 0) {
            $result->getOrder()->setStatus('closed');
            $result->getOrder()->setState('closed');
            $result->getOrder()->save();
        }
        return $result ;
    }
}
