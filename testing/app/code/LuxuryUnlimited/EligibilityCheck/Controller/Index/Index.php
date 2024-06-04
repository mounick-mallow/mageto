<?php

namespace LuxuryUnlimited\EligibilityCheck\Controller\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;

class Index implements HttpPostActionInterface
{


    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $_json;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    public $request;

    /**
     * @var \LuxuryUnlimited\EligibilityCheck\Helper
     */
    public $helper;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     * @param \LuxuryUnlimited\EligibilityCheck\Helper $helper
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \LuxuryUnlimited\EligibilityCheck\Helper\Data $helper,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->logger = $logger;
        $this->_json = $json;
        $this->request = $request;
        $this->helper = $helper;
    }

    /**
     * Execute
     *
     * @return JsonFactory
     */
    public function execute()
    {
        $response = [];
        $data = $this->request->getParams();
        $erpResponse = false;

        try {
            switch ($data['type']) {
                case 'itemReturn':
                    $erpResponse = $this->checkOrderItemReturn(
                        $data['orderId'],
                        $data['productSku']
                    );
                    break
                    ;

                case 'itemExchange':
                    $erpResponse = $this->checkOrderItemExchange(
                        $data['website'],
                        $data['orderId'],
                        $data['productSku']
                    );
                    break
                    ;

                case 'orderReturn':
                    $erpResponse = $this->checkOrderReturn($data['orderId']);
                    break
                    ;

                case 'orderCancel':
                    $erpResponse = $this->checkOrderCancel($data['website'], $data['orderId']);
                    break
                    ;

                case 'itemCancel':
                    $erpResponse = $this->checkOrderItemCancel(
                        $data['website'],
                        $data['orderId'],
                        $data['productSku']
                    );
                    break
                    ;
            }

            $response = [
                'errors' => false,
                'erpResponse' => $erpResponse,
            ];
        } catch (\Exception $e) {
            $response = [
                'errors' => true,
                'message' => $e->getMessage()
            ];
        }

        return $this->resultJsonFactory->create()->setData($response);
    }

    /**
     * @param $incrementId
     * @param $productSku
     * @return bool
     */
    private function checkOrderItemReturn($incrementId, $productSku)
    {
        $this->logger->info("Checking if Item with SKU $productSku from Order #$incrementId is returnable");
        try {
            $response = $this->helper->checkItemEligibility($incrementId,$productSku);
            $this->logger->info($this->_json->serialize($response));
            return $response;
        } catch (\Exception $e) {
            $this->logger->info("Something went wrong, OrderId => $incrementId, SKU => $productSku");
            $this->logger->info($e->getMessage());
        }

        return false;
    }

    /**
     * Check if order item is exchangeable
     *
     * @param string $website
     * @param int $incrementId
     * @param string $productSku
     * @return bool
     */
    private function checkOrderItemExchange($incrementId, $productSku)
    {
        $this->logger->info("Checking if Item with SKU $productSku from Order #$incrementId is exchangeable");
        try {

            $response = $this->helper->checkItemEligibility($incrementId,$productSku);
            $this->logger->info("$incrementId  return flag : $response");
            return $response;

        } catch (\Exception $e) {
            $this->logger->info("Something went wrong, OrderId => $incrementId, SKU => $productSku");
            $this->logger->info($e->getMessage());
        }

        return false;
    }

    /**
     * @param $incrementId
     * @return bool
     */
    private function checkOrderReturn( $incrementId)
    {
        $this->logger->info("Checking if Order #$incrementId is returnable");
        $orderEligible = 0;
        try {
            $response = $this->helper->checkOrderEligibility($incrementId);
            $flagArray = [];

            if(is_array($response)){
                foreach($response as $sku => $flag){
                    if(!in_array($flag,$flagArray)){
                        $flagArray[] = $flag;
                    }
                }
                if(count($flagArray) == 1){
                    $orderEligible = $flagArray[0];
                }
            }
            return $orderEligible;

        } catch (\Exception $e) {
            $this->logger->info("Something went wrong, OrderId => $incrementId");
            $this->logger->info($e->getMessage());
        }

        return $orderEligible;
    }

    /**
     * Check if order is cancellable
     *
     * @param string $website
     * @param int $incrementId
     * @return bool
     */
    private function checkOrderCancel($website, $incrementId)
    {
        $this->logger->info("Checking if Order #$incrementId is cancellable");

        try {
            $response = $this->helper->checkCancelOrder($incrementId);

            $this->logger->info($this->_json->serialize($response));
            return $response;

        } catch (\Exception $e) {
            $this->logger->info("Something went wrong, OrderId => $incrementId");
            $this->logger->info($e->getMessage());
        }

        return false;
    }

    /**
     * Check if order item is cancellable
     *
     * @param string $website
     * @param int $incrementId
     * @param string $productSku
     * @return bool
     */
    private function checkOrderItemCancel($website, $incrementId, $productSku)
    {
        $this->logger->info("Checking if Item with SKU $productSku from Order #$incrementId is cancellable");

        $data = [
            "website" => $website,
            "order_id" => $incrementId,
            "product_sku" => $productSku,
            "type" => 'cancellation',
            "cancellation_type" => 'products'
        ];

        try {
            $response = $this->erpClient->checkOrderCancellation($data)[0];
            $this->logger->info($this->_json->serialize($response));

            if ($response && isset($response['code']) && isset($response['data']) && $response['code'] == 200) {
                return $response['data']['iscanceled'];
            }
        } catch (\Exception $e) {
            $this->logger->info("Something went wrong, OrderId => $incrementId, SKU => $productSku");
            $this->logger->info($e->getMessage());
        }

        return false;
    }
}
