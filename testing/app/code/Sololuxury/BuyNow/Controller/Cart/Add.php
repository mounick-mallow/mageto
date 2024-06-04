<?php

namespace Sololuxury\BuyNow\Controller\Cart;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Controller\Cart\Add as AddToCartController;
use Magento\Checkout\Helper\Cart;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Checkout\Model\Cart\RequestQuantityProcessor;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filter\LocalizedToNormalized;
use Magento\Framework\Locale\ResolverInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Sololuxury\BuyNow\Model\Config\Provider;
use Magento\Framework\Controller\Result\JsonFactory;
/**
 * Class Controller Add
 */
class Add extends AddToCartController
{
    /**
     * @var ResolverInterfaceFactory
     */
    private ResolverInterfaceFactory $resolverInterface;

    /**
     * @var Provider
     */
    private Provider $configProvider;

    /**
     * @var Escaper
     */
    private Escaper $escaper;

    /**
     * @var Cart
     */
    private Cart $checkoutCart;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    private JsonFactory $resultJsonFactory;

    /**
     * Add constructor.
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $checkoutSession
     * @param StoreManagerInterface $storeManager
     * @param Validator $formKeyValidator
     * @param CustomerCart $cart
     * @param ProductRepositoryInterface $productRepository
     * @param Provider $configProvider
     * @param ResolverInterfaceFactory $resolverInterface
     * @param LoggerInterface $logger
     * @param Cart $checkoutCart
     * @param Escaper $escaper
     * @param JsonFactory $resultJsonFactory
     * @param RequestQuantityProcessor|null|null $quantityProcessor
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        CustomerCart $cart,
        ProductRepositoryInterface $productRepository,
        Provider $configProvider,
        ResolverInterfaceFactory $resolverInterface,
        LoggerInterface $logger,
        Cart $checkoutCart,
        Escaper $escaper,
        JsonFactory $resultJsonFactory,
        ?RequestQuantityProcessor $quantityProcessor = null
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart,
            $productRepository,
            $quantityProcessor
        );
        $this->resolverInterface = $resolverInterface;
        $this->configProvider = $configProvider;
        $this->escaper = $escaper;
        $this->checkoutCart = $checkoutCart;
        $this->logger = $logger;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Add product to shopping cart action
     *
     * @return ResponseInterface|Redirect|ResultInterface|void
     */
    public function execute()
    {
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage(
                __('Your session has expired')
            );
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $params = $this->getRequest()->getParams();
        return $this->saveToCart($params);
    }

    /**
     * Save To Cart function
     *
     * @param mixed $params
     * @return ResponseInterface|ResultInterface|void
     */
    protected function saveToCart($params)
    {
        try {
            if (isset($params['qty'])) {
                $filter = new LocalizedToNormalized(
                    ['locale' => $this->resolverInterface->create()->getLocale()]
                );
                $params['qty'] = $filter->filter($params['qty']);
            }

            $product = $this->_initProduct();
            $related = $this->getRequest()->getParam('related_product');

            if (!$product) {
                $response = ['error'=>true,'message'=>'something went wrong'];
                $resultJson = $this->resultJsonFactory->create();
                return $resultJson->setData($response);
            }

            $cartProducts = $this->configProvider->keepCartProducts();
            if (!$cartProducts) {
                $this->cart->truncate();
            }

            $this->cart->addProduct($product, $params);
            if (!empty($related)) {
                $productIds = array_map('intval', explode(',', $related));
                $this->cart->addProductsByIds($productIds);
            }

            $this->cart->save();

            $this->_eventManager->dispatch(
                'checkout_cart_add_product_complete',
                ['product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse()]
            );

            if (!$this->_checkoutSession->getData('no_cart_redirect', true)) {
                $response = ['error'=>false,'message'=>'success'];
                $resultJson = $this->resultJsonFactory->create();
                return $resultJson->setData($response);

            }
        } catch (LocalizedException $e) {
            if ($this->_checkoutSession->getData('use_notice', true)) {
                $this->messageManager->addNoticeMessage(
                    $this->escaper->escapeHtml($e->getMessage())
                );
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->messageManager->addErrorMessage(
                        $this->escaper->escapeHtml($message)
                    );
                }
            }

            $response = ['error'=>true,'message'=>'something went wrong'];
            $resultJson = $this->resultJsonFactory->create();
            return $resultJson->setData($response);
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('We can\'t add this item to your shopping cart right now.')
            );
            $this->logger->critical($e);
            $response = ['error'=>true,'message'=>'something went wrong'];
            $resultJson = $this->resultJsonFactory->create();
            return $resultJson->setData($response);
        }
    }
}
