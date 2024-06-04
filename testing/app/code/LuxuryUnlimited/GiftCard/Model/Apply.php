<?php declare(strict_types=1);

namespace LuxuryUnlimited\GiftCard\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magetop\GiftCard\Model\GiftUser;
use Magetop\GiftCard\Model\GiftDetail;
use Magento\Store\Model\Store;

/**
 * Apply gift card discount
 *
 * @SuppressWarnings(PHPMD)
 */
class Apply
{
    /**
     * @param \Magetop\GiftCard\Helper\Data $giftCardHelper
     * @param \Magetop\GiftCard\Model\ResourceModel\GiftUser\CollectionFactory $giftUserCollectionFactory
     * @param \Magetop\GiftCard\Model\ResourceModel\GiftUser $giftUserResource
     * @param \Magetop\GiftCard\Model\GiftDetailFactory $giftDetailFactory
     * @param \Magetop\GiftCard\Model\ResourceModel\GiftDetail $giftDetailResource
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $ruleCollectionFactory
     * @param \Magento\SalesRule\Model\ResourceModel\Rule $ruleResource
     * @param \Magento\SalesRule\Model\RuleFactory $ruleFactory
     * @param \Magento\QuoteGraphQl\Model\Cart\GetCartForUser $getCartForUser
     * @param \Magento\Quote\Api\CouponManagementInterface $couponManagement
     */
    public function __construct(
        private \Magetop\GiftCard\Helper\Data $giftCardHelper,
        private \Magetop\GiftCard\Model\ResourceModel\GiftUser\CollectionFactory $giftUserCollectionFactory,
        private \Magetop\GiftCard\Model\ResourceModel\GiftUser $giftUserResource,
        private \Magetop\GiftCard\Model\GiftDetailFactory $giftDetailFactory,
        private \Magetop\GiftCard\Model\ResourceModel\GiftDetail $giftDetailResource,
        private \Magento\Customer\Model\Session $customerSession,
        private \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $ruleCollectionFactory,
        private \Magento\SalesRule\Model\ResourceModel\Rule $ruleResource,
        private \Magento\SalesRule\Model\RuleFactory $ruleFactory,
        private \Magento\QuoteGraphQl\Model\Cart\GetCartForUser $getCartForUser,
        private \Magento\Quote\Api\CouponManagementInterface $couponManagement
    ) {
    }

    /**
     * Apply gift card discount
     *
     * @param array $data
     * @param Store $store
     * @param int $userId
     * @return Phrase
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlInputException
     * @throws \Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException
     */
    public function execute(array $data, Store $store, int $userId): Phrase
    {
        $giftUserCart = $this->getGiftCardByUser(trim($data['code']));
        if (!$giftUserCart || $giftUserCart->getIsActive() !== 'yes') { // @phpstan-ignore-line
            throw new LocalizedException(__('The gift code is not valid.'));
        }

        $giftCard = $this->getGiftCard((int)$giftUserCart->getGiftcodeid()); // @phpstan-ignore-line
        if (!$giftCard) {
            throw new LocalizedException(__('The gift code is not valid.'));
        }

        $isExpire = $this->giftCardHelper->checkExpirationOfGiftCard(
            $giftUserCart->getAlloted(), // @phpstan-ignore-line
            $giftCard->getDuration() // @phpstan-ignore-line
        );
        if ($isExpire) {
            $giftUserCart->setIsExpire(1); // @phpstan-ignore-line
            $this->giftUserResource->save($giftUserCart);
            throw new LocalizedException(__('The gift card is expired.'));
        }

        if ($data['amount'] > (float)$giftUserCart->getRemainingAmt()) { // @phpstan-ignore-line
            throw new LocalizedException(
                // // @phpstan-ignore-next-line
                __('Limit exceeded, you can use more %1', (float)$giftUserCart->getRemainingAmt())
            );
        }

        if (!$this->isExistSalesRule($data['code'])) {
            $this->createSalesRule($data, (int)$store->getWebsiteId());
        }

        $cart = $this->getCartForUser->execute($data['cart_id'], $userId, (int)$store->getStoreId());
        $cartId = $cart->getId();
        $appliedCouponCode = $this->couponManagement->get($cartId);
        if (!empty($appliedCouponCode)) {
            throw new LocalizedException(
                __('A coupon is already applied to the cart. Please remove it to apply another')
            );
        }
        $this->couponManagement->set($cartId, $data['code']);
        return __('Gift Card Discount Applied Successfully');
    }

    /**
     * Create cart price rule
     *
     * @param array $data
     * @param int $websiteId
     * @return void
     */
    private function createSalesRule(array $data, int $websiteId): void
    {
        $rule = $this->ruleFactory->create();
        $rule->setName($data['code']) // @phpstan-ignore-line
            ->setCouponCode($data['code'])
            ->setDescription('')
            ->setIsActive(1)
            ->setWebsiteIds([$websiteId])
            ->setCustomerGroupIds([1])
            ->setFromDate('')
            ->setCouponType(2)
            ->setToDate('')
            ->setSimpleAction('cart_fixed')
            ->setDiscountAmount($data['amount'])
            ->setStopRulesProcessing(0)
            ->setIsVisibleInList(0);
        $this->ruleResource->save($rule);
    }

    /**
     * Get cart price rule
     *
     * @param string $code
     * @return bool
     */
    private function isExistSalesRule(string $code): bool
    {
        $collection = $this->ruleCollectionFactory->create();
        $collection->addFieldToFilter('rule_coupons.code', $code);
        return (bool)$collection->getFirstItem();
    }

    /**
     * Get gift card
     *
     * @param int $id
     * @return GiftDetail
     */
    private function getGiftCard(int $id): GiftDetail
    {
        $giftCard = $this->giftDetailFactory->create();
        $this->giftDetailResource->load($giftCard, $id, $giftCard->getIdFieldName());
        return $giftCard;
    }

    /**
     * Get gift card by user
     *
     * @param string $code
     * @return GiftUser
     */
    private function getGiftCardByUser(string $code): GiftUser
    {
        $customerEmail = (string)$this->customerSession->getCustomer()?->getEmail();
        $collection = $this->giftUserCollectionFactory->create();
        $collection->addFieldToFilter('code', $code)
            ->addFieldToFilter('email', $customerEmail)
            ->setPageSize(1);
        return $collection->getFirstItem(); // @phpstan-ignore-line
    }
}
