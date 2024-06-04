<?php
declare(strict_types=1);

namespace LuxuryUnlimited\GiftCard\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\GraphQl\Model\Query\ContextInterface;

class ApplyGiftCard implements ResolverInterface
{
    /**
     * @param \LuxuryUnlimited\GiftCard\Model\Apply $applyGiftCard
     */
    public function __construct(
        private \LuxuryUnlimited\GiftCard\Model\Apply $applyGiftCard
    ) {
    }

    /**
     * Apply gift card discount
     *
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws GraphQlAuthorizationException
     * @throws GraphQlInputException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (empty($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(__('Missing input value.'));
        }

        if (empty($args['input']['cart_id'])) {
            throw new GraphQlInputException(__('Missing \'cart_id\' field.'));
        }

        /** @var ContextInterface $context */
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__(
                'The current customer isn\'t authorized.'
            ));
        }

        if (empty($args['input']['amount'])) {
            throw new GraphQlInputException(__('Missing amount value.'));
        }

        if ($args['input']['amount'] <= 0) {
            throw new GraphQlInputException(__('The amount should be greater then 0.'));
        }

        if (empty($args['input']['code'])) {
            throw new GraphQlInputException(__('Missing gift card code.'));
        }

        try {
            $store = $context->getExtensionAttributes()->getStore();
            $userId = (int)$context->getUserId();
            $message = $this->applyGiftCard->execute($args['input'], $store, $userId);
        } catch (\Exception $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }

        return ['message' => $message];
    }
}
