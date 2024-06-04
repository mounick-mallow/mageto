<?php

namespace Magetop\GiftCard\Observer;

use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfigInterfaceAlias;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AfterInvoiceGeneration implements ObserverInterface
{
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_salesOrder;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_catalogProduct;

    /**
     * @var \Magento\SalesRule\Model\Rule
     */
    protected $_magentoSalesRule;

    /**
     * @var \Magento\Sales\Model\Order\ItemFactory
     */
    protected $_magentoSalesOrderItem;

    /**
     * @var \Magetop\GiftCard\Helper\Data
     */
    protected $_helperData;

    /**
     * @var \Magetop\GiftCard\Model\GiftDetailFactory
     */
    protected $_giftDetailFactory;

    /**
     * @var \Magetop\GiftCard\Model\GiftUserFactory
     */
    protected $_giftUserFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_timezoneInterface;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    public const XML_PATH_ERP_API_URL = "custom/erp_api/erp_api_url";

    /**
     * @var ScopeConfigInterfaceAlias
     */
    private $scopeConfig;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param \Magento\Sales\Model\Order $salesOrder
     * @param \Magento\Catalog\Model\Product $catalogProduct
     * @param \Magento\SalesRule\Model\Rule $magentoSalesRule
     * @param \Magento\Sales\Model\Order\ItemFactory $magentoSalesOrderItem
     * @param \Magetop\GiftCard\Helper\Data $helperData
     * @param \Magetop\GiftCard\Model\GiftDetailFactory $giftDetailFactory
     * @param \Magetop\GiftCard\Model\GiftUserFactory $giftUserFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param ScopeConfigInterfaceAlias $scopeConfig
     * @param LoggerInterface $logger
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Sales\Model\Order                           $salesOrder,
        \Magento\Catalog\Model\Product                       $catalogProduct,
        \Magento\SalesRule\Model\Rule                        $magentoSalesRule,
        \Magento\Sales\Model\Order\ItemFactory               $magentoSalesOrderItem,
        \Magetop\GiftCard\Helper\Data                        $helperData,
        \Magetop\GiftCard\Model\GiftDetailFactory            $giftDetailFactory,
        \Magetop\GiftCard\Model\GiftUserFactory              $giftUserFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface,
        \Magento\Framework\Message\ManagerInterface          $messageManager,
        ScopeConfigInterfaceAlias                            $scopeConfig,
        LoggerInterface                                      $logger
    ) {
        $this->_salesOrder = $salesOrder;
        $this->_catalogProduct = $catalogProduct;
        $this->_magentoSalesRule = $magentoSalesRule;
        $this->_magentoSalesOrderItem = $magentoSalesOrderItem;
        $this->_helperData = $helperData;
        $this->_giftDetailFactory = $giftDetailFactory;
        $this->_giftUserFactory = $giftUserFactory;
        $this->_timezoneInterface = $timezoneInterface;
        $this->_messageManager = $messageManager;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * This is the method that fires when the event runs.
     *
     * @param Observer $observer
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute(Observer $observer) // phpcs:ignore
    {
        $invoice = $observer->getEvent()->getInvoice();
        $oids = $invoice->getOrderId();
        $sl = $this->_salesOrder->load($oids);
        //$realOrderId = $sl->getIncrementId();
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_Symbol = $_objectManager->create(\Magento\Directory\Model\CurrencyFactory::class)
            ->create()->load($this->_helperData->getBaseCurrencyCode());
        $couponCode=$sl->getCouponCode();
        $discountAmt=$sl->getDiscountAmount();
        foreach ($invoice->getAllItems() as $item) {
            $productid = $item->getProductId();
            $gcqty=$item->getQty();
            for ($i=0; $i < (int)$gcqty; $i++) {
                $giftmodel = $this->_catalogProduct->load($productid);
                if ($giftmodel->getTypeId() == 'giftcard') {
                    $userEmail = "";
                    $userMessage = "";
                    $options = $this->_magentoSalesOrderItem->create()->load($item->getOrderItemId())
                        ->getProductOptions();
                    if (!empty($options['options'])) {
                        foreach ($options['options'] as $option) {
                            if ($option['label'] == 'Email To') {
                                $userEmail = $option['value'];
                            }
                            if ($option['label'] == 'Message') {
                                $userMessage = $option['value'];
                            }
                        }
                    }
                    $customer=$sl->getCustomerEmail();
                    $customer_name=$sl->getCustomerFirstname() . " " . $sl->getCustomerLastname();
                    $mailData=[];
                    /* Assign values for your template variables  */
                    $emailTemplateVariables = [];
                    // $price= $giftmodel->getPrice();
                    $price= $item->getPrice();
                    $mailData['price']=$price;
                    $emailTemplateVariables['myvar1'] = $price;
                    // $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    // $_Symbol = $_objectManager->create('Magento\Directory\Model\CurrencyFactory')->create()->load($this->_helperData->getBaseCurrencyCode());
                    $emailTemplateVariables['myvar8'] = $_Symbol->getCurrencySymbol();
                    // $des= $giftmodel->getShortDescription();
                    $des = $giftmodel->getDescription();
                    $mailData['description']=$des;
                    $emailTemplateVariables['myvar2'] = $des;
                    $email = $userEmail;
                    $mailData['reciever']=$email;
                    /* Receiver Detail  */
                    $receiverInfo = [
                        'name' => 'Reciver Name',
                        'email' => $email
                    ];
                    $emailTemplateVariables['myvar6'] = 'Reciver Name';
                    $emailTemplateVariables['myvar7'] = $email;
                    $emailTemplateVariables['myvar9'] = $userMessage;

                    $giftcode=$this->_helperData->getRandId(12);
                    $mailData['sender']=$customer;
                    $mailData['sender_name']=$customer_name;
                    $emailTemplateVariables['myvar4'] = $customer;
                    $emailTemplateVariables['myvar5'] = $customer_name;
                    /* Sender Detail  */
                    $senderInfo = [
                        'name' => $customer_name,
                        'email' => $customer
                    ];
                    $usageDurationOfGiftCard = $this->_helperData->getGiftCardActiveDuration();
                    $expiryDate = null;
                    if ($email) {
                        $data = [$price => "price",
                            "description"=>$des,
                            "email"=>$email,
                            "from"=>$customer,
                            "message"=>$userMessage,
                            "duration"=>$usageDurationOfGiftCard,
                            'order_id'=>$oids
                        ];
                        $model=$this->_giftDetailFactory->create()->setData($data);
                        $dateTimeAsTimeZone = $this->_timezoneInterface
                                        ->date(new \DateTime(date("Y/m/d h:i:sa")))
                                        ->format('Y/m/d H:i:s');
                        $emailTemplateVariables['myvar10'] = $this->_helperData
                            ->createExpirationDateOfGiftCard($usageDurationOfGiftCard, $dateTimeAsTimeZone);
                        $expiryDate = $this->_helperData
                            ->createExpirationDateOfGiftCard($usageDurationOfGiftCard, $dateTimeAsTimeZone);
                        $expiryDate = date('Y-m-d', strtotime(str_replace('.', '/', $expiryDate)));
                        try {
                            $id=$model->save()->getGiftId();
                            $model2=$this->_giftUserFactory->create()->setData([
                                "giftcodeid" => $id,
                                "amount" => $price,
                                "alloted" => $dateTimeAsTimeZone,
                                "email" => $email,
                                "from" => $customer,
                                "remaining_amt" => $price,
                                "is_active" => "yes",
                                "is_expire" => 0
                            ]);
                            $id2=$model2->save()->getGiftuserid();
                            $this->_giftDetailFactory->create()->load($id)->setGiftCode($id2 . $giftcode)->save();
                            $this->_giftUserFactory->create()->load($id2)->setCode($id2 . $giftcode)->save();
                            $emailTemplateVariables['myvar3'] = $id2 . $giftcode;
                            $mailData['code']=$id2 . $giftcode;
                            try {
                                $this->_helperData->customMailSendMethod(
                                    $emailTemplateVariables,
                                    $senderInfo,
                                    $receiverInfo
                                );
                            } catch (\Exception $e) {
                                $this->_messageManager->addError(__($e->getMessage()));
                                return false;
                            }
                        } catch (\Exception $e) {
                            $this->_messageManager->addError(__($e->getMessage()));
                            return false;
                        }
                    }
                    $giftAmount = (int)$price;
                    // echo 'Customer Name:'.$customer.'<br/>'.$customer_name.'<br/>'.$userEmail.'<br/>'.$userMessage.'<br/>'.$giftcode.'<br/>'.$expiryDate.'<br/>'.$des.'<br/>'.$giftAmount;
                    //post gift card data using start
                    //API Url
                    //$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    //$directory = $objectManager->get(\Magento\Framework\Filesystem\DirectoryList::class);
                    //$base = $directory->getRoot();
                    //$date = date('Y-m-d');
                    $url = $this->getErpApiUrl() . 'api/giftcards/add';

                    //Initiate cURL.
                    $ch = curl_init($url); //phpcs:ignore

                    //The JSON data.
                    $jsonData = [
                        'sender_name' => $customer_name,
                        'sender_email' => $customer,
                        'receiver_name' => $userEmail,
                        'receiver_email' => $userEmail, //required, email
                        'gift_card_coupon_code' => $giftcode, //required, unique, upto 50 chars
                        'gift_card_description' => $des, //required, length maxminum 1000
                        'gift_card_amount' => $giftAmount, //required, integer
                        'gift_card_message' => $userMessage, //length maxminum 200
                        'expiry_date' => $expiryDate, //required, date after yesterday
                        'website' => "www.brands-labels.com" //required, must be a website in store websites
                    ];
                    //Encode the array into JSON.
                    $jsonDataEncoded = json_encode($jsonData);

                    //Tell cURL that we want to send a POST request.
                    curl_setopt($ch, CURLOPT_POST, 1); //phpcs:ignore

                    //Attach our encoded JSON string to the POST fields.
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded); //phpcs:ignore

                    //Set the content type to application/json
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']); //phpcs:ignore
                    //Execute the request
                    try {
                        $result = curl_exec($ch); //phpcs:ignore
                        // $result = 'success';
                        //$err = curl_error($ch);
                        curl_close($ch); //phpcs:ignore

                        $this->logger->info($result . date('Y-m-d H:i:s'));
                    } catch (Exception $e) {
                        $this->logger->info($e . '==' . date('Y-m-d H:i:s'));
                    }
                    //API code end
                }
            }
        }

        $cc=$this->_giftUserFactory->create()->getCollection()->addFieldToFilter('code', $couponCode);
        if ($cc->getSize()) {
            $gift_user_data=[];
            $giftCodeId = null;
            $customerName=$sl->getCustomerFirstname() . " " . $sl->getCustomerLastname();
            $customerEmail=$sl->getCustomerEmail();
            $gift_user_data["orderId"]=$sl->getIncrementId();
            $gift_user_data["reciever_email"]=$customerEmail;
            $gift_user_data["reciever_name"]=$customerName;
            $gift_user_data["reduced_ammount"]=$discountAmt;
            $emailTemplateVariablesForLeftAmt["myvar1"]=$sl->getIncrementId();
            $emailTemplateVariablesForLeftAmt["myvar2"]=$customerEmail;
            $emailTemplateVariablesForLeftAmt["myvar3"]=$customerName;
            $emailTemplateVariablesForLeftAmt["myvar4"]=$discountAmt;
            $emailTemplateVariablesForLeftAmt['myvar8'] = $_Symbol->getCurrencySymbol();
            $model3=$this->_giftUserFactory->create()
            ->getCollection()
            ->addFieldToFilter("code", $couponCode);
            $date = null;
            foreach ($model3 as $m3) {
                $gift_user_data["previous_ammount"]=$amnt=$m3->getAmount();
                $gift_user_data["gift_code"]=$m3->getCode();
                $emailTemplateVariablesForLeftAmt["myvar5"]=$amnt=$m3->getAmount();
                $emailTemplateVariablesForLeftAmt["myvar6"]=$m3->getCode();
                $m3->setAmount($amnt+$discountAmt)->save();
                $gift_user_data["result_ammount"]=$m3->getAmount();
                $emailTemplateVariablesForLeftAmt["myvar7"]=$m3->getAmount();
                $giftCodeId = $m3->getGiftcodeid();
                $date = $m3->getAlloted();
            }
            $duration = null;
            $myvar10 = null;
            if ($giftCodeId) {
                $giftDetailModel = $this->_giftDetailFactory->create()->load($giftCodeId);
                $duration = $giftDetailModel->getDuration();
            }
            $emailTemplateVariablesForLeftAmt["myvar9"] = $date;
            if (!empty($date)) {
                $myvar10 = $this->_helperData->createExpirationDateOfGiftCard($duration, $date);
            }
            $emailTemplateVariablesForLeftAmt["myvar10"] = $myvar10;
            $collection = $this->_magentoSalesRule->getCollection()->load();
            foreach ($collection as $m) {
                if ($m->getName() == $couponCode) {
                    $m->delete();
                }
            }
            $receiverInfo = [
                'name' => $customerName,
                'email' => $customerEmail
            ];
            $adminName = $this->_helperData->getAdminNameFromConfig();
            $adminEmail = $this->_helperData->getAdminEmailFromConfig();
            if ($adminName == "") {
                $adminName = $this->_helperData->getStorename();
            }
            if ($adminEmail == "") {
                $adminEmail = $this->_helperData->getStoreEmail();
            }
            $senderInfo = [
                'name' => $adminName,
                'email' => $adminEmail
            ];
            $emailTemplateVariablesForLeftAmt['myvar8'] = $this->_helperData->getBaseCurrencyCode();
            $this->_helperData->customMailSendMethodForLeftAmt(
                $emailTemplateVariablesForLeftAmt,
                $senderInfo,
                $receiverInfo
            );
            $coupon_model = $this->_magentoSalesRule->getCollection()->load();
            foreach ($coupon_model as $cpn) {
                if (trim($cpn->getName()) == trim($couponCode)) {
                    $cpn->delete();
                }
            }
        }
    }

    /**
     * Get config
     *
     * @param string $path
     * @param int|null|string $storeId
     * @return mixed
     */
    public function getConfig($path, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get erp uri
     *
     * @return mixed
     */
    public function getErpApiUrl()
    {
        return $this->getConfig(self::XML_PATH_ERP_API_URL);
    }
}
