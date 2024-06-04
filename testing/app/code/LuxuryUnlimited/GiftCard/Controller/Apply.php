<?php declare(strict_types=1);

namespace LuxuryUnlimited\GiftCard\Controller;

use Magento\Framework\App\Action\Context;
use Magetop\GiftCard\Controller\GiftCard\UpdateGiftCard;

/**
 * @SuppressWarnings(PHPMD)
 */
class Apply extends UpdateGiftCard
{
    /**
     * @param Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magetop\GiftCard\Model\GiftUserFactory $giftUser
     * @param \Magetop\GiftCard\Model\GiftDetailFactory $giftDetail
     * @param \Magetop\GiftCard\Helper\Data $dataHelper
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\SalesRule\Model\Rule $salesRule
     * @param \Magento\Checkout\Model\Cart $quote
     * @param \Magento\Framework\Session\SessionManagerInterface $backendSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct( // @phpcs:ignore
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magetop\GiftCard\Model\GiftUserFactory $giftUser,
        \Magetop\GiftCard\Model\GiftDetailFactory $giftDetail,
        \Magetop\GiftCard\Helper\Data $dataHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\SalesRule\Model\Rule $salesRule,
        \Magento\Checkout\Model\Cart $quote,
        \Magento\Framework\Session\SessionManagerInterface $backendSession,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        parent::__construct(
            $context,
            $storeManager,
            $giftUser,
            $giftDetail,
            $dataHelper,
            $customerSession,
            $salesRule,
            $quote,
            $backendSession,
            $checkoutSession
        );
    }

    /**
     * Execute
     *
     * @return false|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings(PHPMD)
     */
    // @codingStandardsIgnoreStart
    public function execute() // @phpstan-ignore-line
    {
        $param=$this->getRequest()->getParams();
        $rates = $this->_dataHelper->getCurrentCurrencyRate();
        $price = $param['amount'];
        $price=$price/$rates;
        $param['amount']=$price;
        if ((float)$param['amount']>0) {
            $whom="";
            $collections=$this->_giftuser->create()->getCollection();
            $model=$collections->addFieldToFilter("code", $param["code"]);
            if ($model->getSize()) {
                // @phpstan-ignore-next-line
                $duration = $this->_giftDetail->create()->load($model->getColumnValues('giftcodeid')[0])->getDuration();
                $isExpire = $this->_dataHelper->checkExpirationOfGiftCard(
                    $model->getColumnValues('alloted')[0],
                    $duration
                );
                if ($isExpire) {
                    foreach ($model as $giftUserModel) {
                        $giftUserModel->setIsExpire(1);
                        $giftUserModel->save();
                    }
                    $this->messageManager->addError(__("The gift code %1 is expired", trim($param['code'])));
                    return false;
                }
                if ($model->getColumnValues('is_active')[0] != "yes") {
                    $this->messageManager->addError(
                        __(
                            "The gift code %1 is disable.Please contact administration.",
                            trim($param['code'])
                        )
                    );
                    return false;
                }
                foreach ($model as $m) {
                    $whom=$m->getEmail();
                }
                $customerEmail=$this->_customerSession->getCustomer()->getEmail();
                $usermodel=$collections->addFieldToFilter("email", $customerEmail)
                    ->addFieldToFilter("code", trim($param['code']));
                $acamm=0;
                if ($usermodel->getSize() > 0) {
                    foreach ($usermodel as $u) {
                        $acamm=(float)$u->getRemainingAmt();
                    }
                    if ((float)$param['amount']>$acamm) {
                        $param['amount']=$acamm;
                    }
                }
                if ((float)$param['amount']==0) {
                    $collection = $this->_salesRule->getCollection();
                    foreach ($collection as $mo) {
                        // Delete coupon
                        if ($mo->getName() == trim($param['code'])) {
                            $mo->delete();
                            $this->_backendSession->setCoupancode(null); // @phpstan-ignore-line
                            $this->_backendSession->setReduceprice(null); // @phpstan-ignore-line
                        }
                    }
                    $this->messageManager->addError(__("Gift code has been expired."));
                } elseif ((float)$param['amount']<=$acamm) {
                    if (!empty($param['code'])) {
                        $model=$collections->addFieldToFilter("code", trim($param['code']));
                        $giftcode = '';
                        foreach ($model as $m) {
                            $giftcode=$m->getCode();
                        }
                        if ($giftcode==trim($param['code'])) {
                            $this->_backendSession->setReducedprice((float)$param['amount']); // @phpstan-ignore-line
                            $this->_backendSession->setCoupancode(trim($param['code'])); // @phpstan-ignore-line
                            $name = trim($param['code']);
                            $websiteId = $this->_storeManager->getStore()->getWebsiteId();
                            $customerGroupId = 1;
                            $actionType = 'cart_fixed';
                            $rates = $this->_dataHelper->getCurrentCurrencyRate();
                            $discount = (float)$param['amount']*$rates;
                            $shoppingCartPriceRule = $this->_salesRule;
                            $collection = $this->_salesRule->getCollection();
                            foreach ($collection as $model) {
                                if ($model->getName() == trim($param['code'])) {
                                    $model->delete();
                                }
                            }
                            $shoppingCartPriceRule // @phpstan-ignore-line
                                ->setName($name)
                                ->setCouponCode($name)
                                ->setDescription('')
                                ->setIsActive(1)
                                ->setWebsiteIds([$websiteId])
                                ->setCustomerGroupIds([$customerGroupId])
                                ->setFromDate('')
                                ->setCouponType(2)
                                ->setToDate('')
                                ->setSortOrder('')
                                ->setSimpleAction($actionType)
                                ->setDiscountAmount($discount)
                                ->setStopRulesProcessing(0)
                                // START CUSTOMIZATION
                                ->setIsVisibleInList(0);
                                // END CUSTOMIZATION
                            try {
                                $shoppingCartPriceRule->save();
                            } catch (\Exception $e) {
                                $this->messageManager->addError(__($e->getMessage()));
                                return;
                            }
                            $this->_quote
                                ->getQuote()
                                ->setCouponCode(trim($param['code']))
                                ->collectTotals()
                                ->save();
                            $this->_checkoutSession->setCartWasUpdated(true); // @phpstan-ignore-line
                            $price = $param['amount'];
                            $param['amount']=$price*$rates;
                            $this->messageManager->addSuccess(__('Gift Card Discount Applied Successfully'));
                            if ($param['amount']==0) {
                                $this->_backendSession->setCoupancode(null); // @phpstan-ignore-line
                            }
                        } else {
                            $collection = $this->_salesRule->getCollection()->load();
                            foreach ($collection as $model) {
                                // Delete coupon
                                if ($model->getName() == trim($param['code'])) {
                                    $model->delete();
                                }
                            }
                            $this->messageManager->addError(__("The gift code %1 is not valid", trim($param['code'])));
                        }
                    } else {
                        $collection = $this->_salesRule->getCollection()->load();
                        foreach ($collection as $model) {
                            // Delete coupon
                            if ($model->getName() == trim($param['code'])) {
                                $model->delete();
                            }
                        }
                        $this->messageManager->addError(__("code is required"));
                    }
                } else {
                    $collection = $this->_salesRule->getCollection()->load();
                    foreach ($collection as $mo) {
                        // Delete coupon
                        if ($mo->getName() == trim($param['code'])) {
                            $mo->delete();
                            $this->_backendSession->setCoupancode(null); // @phpstan-ignore-line
                            $this->_backendSession->setReduceprice(null); // @phpstan-ignore-line
                        }
                    }
                    $this->messageManager->addError(__("Please enter a valid amount"));
                }
            } else {
                $this->messageManager->addError(__("The gift code %1 is not valid", trim($param['code'])));
            }
        } else {
            $collection = $this->_salesRule->getCollection()->load();
            foreach ($collection as $mo) {
                // Delete coupon
                if ($mo->getName() == trim($param['code'])) {
                    $mo->delete();
                    $this->_backendSession->setCoupancode(null); // @phpstan-ignore-line
                    $this->_backendSession->setReduceprice(null); // @phpstan-ignore-line
                }
            }
            $this->messageManager->addError(__("Please enter a valid amount"));
        }
    }
    // @codingStandardsIgnoreEnd
}
