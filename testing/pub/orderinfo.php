<?php
use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';
$params = $_SERVER;
$bootstrap = Bootstrap::create(BP, $params);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

$storeManager = $objectManager->create('Magento\Store\Api\StoreRepositoryInterface');


// $filenName = date("Y-m-d").'contacts.csv';
$directoryList = $objectManager->get('Magento\Framework\App\Filesystem\DirectoryList');
$orderFailedFilePath = $directoryList->getPath('log');
$filenName = $orderFailedFilePath.'/'.date("Y-m-d").'failedOrders.csv';
$csvFile = file($filenName);
$data = [];
$i = 0;
foreach ($csvFile as $line) {
    $data[] = str_getcsv($line);
    $orderRealId = $data[$i][0];
    // echo $data[$i][0].'<br/>';
    // $orderRealId = $orders->getIncrementId();

    $orders = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderRealId);
    // echo '<pre>';
    // print_r($orders->getData());exit;
    $billingAddress = $orders->getBillingAddress();
    $shippingAddress = $orders->getShippingAddress();

    $websiteName = 'Suv&Nat';

    $websiteid = $orders->getStore()->getWebsiteId();
    $orderBaseCurrencyCode = $orders->getBaseCurrencyCode();
    $baseDiscountAmount = $orders->getBaseDiscountAmount();
    $baseGrandTotal = $orders->getBaseGrandTotal();
    $baseDiscountTaxCompensationAmount = $orders->getBaseDiscountTaxCompensationAmount();
    $baseShippingAmount = $orders->getBaseShippingAmount();
    $baseShippingDiscountAmount = $orders->getBaseShippingDiscountAmount();
    $baseShippingDiscountTaxCompensationAmnt = $orders->getBaseShippingDiscountTaxCompensationAmnt();
    $baseShippingInclTax = $orders->getBaseShippingInclTax();
    $baseShippingTaxAmount = $orders->getBaseShippingTaxAmount();
    $baseSubtotal = $orders->getBaseSubtotal();
    $baseSubtotalInclTax = $orders->getBaseSubtotalInclTax();
    $baseTaxAmount = $orders->getBaseTaxAmount();
    $baseTotalDue = $orders->getBaseTotalDue();
    $baseToGlobalRate = $orders->getBaseToGlobalRate();
    $baseToOrderRate = $orders->getBaseToOrderRate();
    $billingAddressId = $orders->getBillingAddressId();
    $createdAt = $orders->getCreatedAt();
    $customerEmail = $orders->getCustomerEmail();
    $customerFirstname = $orders->getCustomerFirstname();
    $customerGroupId = $orders->getCustomerGroupId();
    $customerId = $orders->getCustomerId();
    $customerIsGuest = $orders->getCustomerIsGuest();
    $customerLastname = $orders->getCustomerLastname();
    $customerNoteNotify = $orders->getCustomerNoteNotify();
    $discountAmount = $orders->getDiscountAmount();
    $emailSent = $orders->getEmailSent();
    $entityId = $orders->getEntityId();
    $globalCurrencyCode = $orders->getGlobalCurrencyCode();
    $grandTotal = $orders->getGrandTotal();
    $discountTaxCompensationAmount = $orders->getDiscountTaxCompensationAmount();
    $incrementId = $orders->getIncrementId();
    $isVirtual = $orders->getIsVirtual();
    $orderCurrencyCode = $orders->getOrderCurrencyCode();
    $protectCode = $orders->getProtectCode();
    $quoteId = $orders->getQuoteId();
    $remoteIp = $orders->getRemoteIp();
    $shippingAmount = $orders->getShippingAmount();
    $shippingDescription = $orders->getShippingDescription();
    $shippingDiscountAmount = $orders->getShippingDiscountAmount();
    $shippingDiscountTaxCompensationAmount = $orders->getShippingDiscountTaxCompensationAmount();
    $shippingInclTax = $orders->getShippingInclTax();
    $shippingTaxAmount = $orders->getShippingTaxAmount();
    $state = $orders->getState();
    $status = $orders->getStatus();
    $storeCurrencyCode = $orders->getStoreCurrencyCode();
    $storeId = $orders->getStoreId();
    $storeName = $orders->getStoreName();
    $storeToBaseRate = $orders->getStoreToBaseRate();
    $storeToOrderRate = $orders->getStoreToOrderRate();
    $subtotal = $orders->getSubtotal();
    $subtotalInclTax = $orders->getSubtotalInclTax();
    $taxAmount = $orders->getTaxAmount();
    $totalDue = $orders->getTotalDue();
    $totalItemCount = $orders->getTotalItemCount();
    $totalQtyOrdered = $orders->getTotalQtyOrdered();
    $updatedAt = $orders->getUpdatedAt();
    $weight = $orders->getWeight();
    $xForwardedFor = $orders->getXForwardedFor();

    $amountRefunded = $orders->getAmountRefunded();
    $baseAmountRefunded = $orders->getBaseAmountRefunded();
    $baseDiscountAmount = $orders->getBaseDiscountAmount();
    $baseDiscountInvoiced = $orders->getBaseDiscountInvoiced();
    $baseDiscountTaxCompensation = $orders->getBaseDiscountTaxCompensation();
    $baseOriginalPrice = $orders->getBaseOriginalPrice();
    $basePrice = $orders->getBasePrice();
    $basePriceInclTax = $orders->getBasePriceInclTax();
    $baseRowInvoiced = $orders->getBaseRowInvoiced();
    $baseRowTotal = $orders->getBaseRowTotal();
    $baseRowTotalIncl = $orders->getBaseRowTotalIncl();
    $baseTaxAmount = $orders->getBaseTaxAmount();
    $baseTaxInvoiced = $orders->getBaseTaxInvoiced();
    $createdAt = $orders->getCreatedAt();
    $discountAmount = $orders->getDiscountAmount();
    $discountInvoiced = $orders->getDiscountInvoiced();
    $discountPercent = $orders->getDiscountPercent();
    $freeShipping = $orders->getFreeShipping();
    $discountTaxCompensationAmount = $orders->getDiscountTaxCompensationAmount();
    $isQtyDecimal = $orders->getIsQtyDecimal();
    $isVirtual = $orders->getIsVirtual();
    $itemId = $orders->getItemId();
    $name = $orders->getName();
    $noDiscount = $orders->getNoDiscount();
    $orderId = $orders->getOrderId();
    $originalPrice = $orders->getOriginalPrice();
    $price = $orders->getPrice();
    $priceInclTax = $orders->getPriceInclTax();
    $productId = $orders->getProductId();
    $productType = $orders->getProductType();
    $qtyCanceled = $orders->getQtyCanceled();
    $qtyInvoiced = $orders->getQtyInvoiced();
    $qtyOrdered = $orders->getQtyOrdered();
    $qtyRefunded = $orders->getQtyRefunded();
    $qtyShipped = $orders->getQtyShipped();
    $quoteItemId = $orders->getQuoteItemId();
    $rowInvoiced = $orders->getRowInvoiced();
    $rowTotal = $orders->getRowTotal();
    $rowTotalInclTax = $orders->getRowTotalInclTax();
    $rowWeight = $orders->getRowWeight();
    $sku = $orders->getSku();
    $storeId = $orders->getStoreId();
    $taxAmount = $orders->getTaxAmount();
    $taxInvoiced = $orders->getTaxInvoiced();
    $taxPercent = $orders->getTaxPercent();
    $updatedAt = $orders->getUpdatedAt();
    $weight = $orders->getWeight();

    $allItems = $orders->getAllItems();

    //billing information
    $addressTypeBilling = $billingAddress->getAddressType();
    $cityBilling = $billingAddress->getCity();
    $countryIdBilling = $billingAddress->getCountryId();
    $customerIdBilling = $billingAddress->getCustomerId();
    $emailBilling = $billingAddress->getEmail();
    $entityIdBilling = $billingAddress->getEntityId();
    $firstnameBilling = $billingAddress->getFirstname();
    $lastnameBilling = $billingAddress->getLastname();
    $parentIdBilling = $billingAddress->getParentId();
    $postcodeBilling = $billingAddress->getPostcode();
    $streetBilling = $billingAddress->getStreet();
    $telephoneBilling = $billingAddress->getTelephone();
    //end

    //shipping information
    $addressTypeShipping = $shippingAddress->getAddressType();
    $cityShipping = $shippingAddress->getCity();
    $countryIdShipping = $shippingAddress->getCountryId();
    $customerIdShipping = $shippingAddress->getCustomerId();
    $emailShipping = $shippingAddress->getEmail();
    $entityIdShipping = $shippingAddress->getEntityId();
    $firstnameShipping = $shippingAddress->getFirstname();
    $lastnameShipping = $shippingAddress->getLastname();
    $parentIdShipping = $shippingAddress->getParentId();
    $postcodeShipping = $shippingAddress->getPostcode();
    $streetShipping = $shippingAddress->getStreet();
    $telephoneShipping = $shippingAddress->getTelephone();

    $shippingMethodTitle = $orders->getShippingDescription();
    $shippingMethod = $orders->getShippingMethod();
    //end


    //payment information
    $payment = $orders->getPayment();
    $paymentMethod = $payment->getMethodInstance();
    $paymentMethodTitle = $paymentMethod->getTitle();
    $paymentMethodCode = $paymentMethod->getCode();
    $amountOrdered = $payment->getAmountOrdered();
    $baseAmountOrdered = $payment->getBaseAmountOrdered();
    $cclast4 = $payment->getCcLast4();
    // echo '<pre>';
    // print_r($amountOrdered.'='.$baseAmountOrdered.'='.$cclast4.'='.$paymentMethodCode.'='.$paymentMethodTitle);exit;
    //end

    $items = array();
    $itemArray = array();
    $tableVal = '';

    $failedOrderList = array();

    foreach($allItems as $item){

      $itemArray['amount_refunded'] = $item->getAmountRefunded();
      $itemArray['base_amount_refunded'] = $item->getBaseAmountRefunded();
      $itemArray['base_discount_amount'] = $item->getBaseDiscountAmount();
      $itemArray['base_discount_invoiced'] = $item->getBaseDiscountInvoiced();
      $itemArray['base_discount_tax_compensation_amount'] = $item->getBaseDiscountTaxCompensationAmount();
      $itemArray['base_original_price'] = $item->getBaseOriginalPrice();
      $itemArray['base_price'] = $item->getBasePrice();
      $itemArray['base_price_incl_tax'] = $item->getBasePriceInclTax();
      $itemArray['base_row_invoiced'] = $item->getBaseRowInvoiced();
      $itemArray['base_row_total'] = $item->getBaseRowTotal();
      $itemArray['base_row_total_incl_tax'] = $item->getBaseRowTotalInclTax();
      $itemArray['base_tax_amount'] = $item->getBaseTaxAmount();
      $itemArray['base_tax_invoiced'] = $item->getBaseTaxInvoiced();
      $itemArray['created_at'] = $item->getCreatedAt();
      $itemArray['discount_amount'] = $item->getDiscountAmount();
      $itemArray['discount_invoiced'] = $item->getDiscountInvoiced();
      $itemArray['discount_percent'] = $item->getDiscountPercent();
      $itemArray['free_shipping'] = $item->getFreeShipping();
      $itemArray['discount_tax_compensation_amount'] = $item->getDiscountTaxCompensationAmount();
      $itemArray['is_qty_decimal'] = $item->getIsQtyDecimal();
      $itemArray['is_virtual'] = $item->getIsVirtual();
      $itemArray['item_id'] = $item->getItemId();
      $itemArray['name'] = $item->getName();
      $itemArray['no_discount'] = $item->getNoDiscount();
      $itemArray['order_id'] = $item->getOrderId();
      $itemArray['original_price'] = $item->getOriginalPrice();
      $itemArray['price'] = $item->getPrice();
      $itemArray['price_incl_tax'] = $item->getPriceInclTax();
      $itemArray['product_id'] = $item->getProductId();
      $itemArray['product_type'] = $item->getProductType();
      $itemArray['qty_canceled'] = $item->getQtyCanceled();
      $itemArray['qty_invoiced'] = $item->getQtyInvoiced();
      $itemArray['qty_ordered'] = $item->getQtyOrdered();
      $itemArray['qty_refunded'] = $item->getQtyRefunded();
      $itemArray['qty_shipped'] = $item->getQtyShipped();
      $itemArray['quote_item_id'] = $item->getQuoteItemId();
      $itemArray['row_invoiced'] = $item->getRowInvoiced();
      $itemArray['row_total'] = $item->getRowTotal();
      $itemArray['row_total_incl_tax'] = $item->getRowTotalInclTax();
      $itemArray['row_weight'] = $item->getRowWeight();
      $itemArray['sku'] = $item->getSku();
      $itemArray['store_id'] = $item->getStoreId();
      $itemArray['tax_amount'] = $item->getTaxAmount();
      $itemArray['tax_invoiced'] = $item->getTaxInvoiced();
      $itemArray['tax_percent'] = $item->getTaxPercent();
      $itemArray['updated_at'] = $item->getUpdatedAt();
      $itemArray['weight'] = $item->getWeight();

      $items[] = $itemArray;
      $productSku = $item->getSku();
      $productName = $item->getName();
      $prodQtyOrdered = $item->getQtyOrdered();
      $tableVal .= '<tr>
        <td>
          <table border="0" cellspacing="0" cellpadding="0" class="container-social" align="left" width="100%" style="text-align:left;">
          <tr>
          <td width="20"></td>
          <td width="560">
            <p style="font-size:14px;color:#000;font-family:helvetica;text-align:left;font-weight:bold;">Product SKU: '.$productSku.'</p>
            <p style="font-size:14px;color:#000;font-family:helvetica;text-align:left;font-weight:bold;">Product Name: '.$productName.'</p>
            <p style="font-size:14px;color:#000;font-family:helvetica;text-align:left;font-weight:bold;">Product Quantity Ordered: '.$prodQtyOrdered.'</p>
          </td>
          <td width="20"></td>

          </tr>
          </table>
        </td>
      </tr>';
    }

    $billingAddressArray = array(

      'address_type' => $addressTypeBilling,
      'city' => $cityBilling,
      'country_id' => $countryIdBilling,
      'customer_id' => $customerIdBilling,
      'email' => $emailBilling,
      'entity_id' => $entityIdBilling,
      'firstname' => $firstnameBilling,
      'lastname' => $lastnameBilling,
      'parent_id' => $parentIdBilling,
      'postcode' => $postcodeBilling,
      'street' => $streetBilling,
      'telephone' => $telephoneBilling

    );

    $shippingAddressArray = array(

      'address_type' => $addressTypeShipping,
      'city' => $cityShipping,
      'country_id' => $countryIdShipping,
      'customer_id' => $customerIdShipping,
      'email' => $emailShipping,
      'entity_id' => $entityIdShipping,
      'firstname' => $firstnameShipping,
      'lastname' => $lastnameShipping,
      'parent_id' => $parentIdShipping,
      'postcode' => $postcodeShipping,
      'street' => $streetShipping,
      'telephone' => $telephoneShipping

    );

    $paymentArray = array(
      'payment_title' => $paymentMethodTitle,
      'amount_ordered' => $amountOrdered,
      'base_amount_ordered' => $baseAmountOrdered,
      'base_shipping_amount' => $baseShippingAmount,
      'cc_last4' => $cclast4,
      'entity_id' => $entityId,
      'method' => $paymentMethodCode,
      'shipping_amount' => $shippingAmount
    );

    //The JSON data.
    $newjsonData = array(

    'website' => $websiteName,
    'base_currency_code' => $orderBaseCurrencyCode,
    'base_discount_amount' => $baseDiscountAmount,
    'base_grand_total' => $baseGrandTotal,
    'base_discount_tax_compensation_amount' => $baseDiscountTaxCompensationAmount,
    'base_shipping_amount' => $baseShippingAmount,
    'base_shipping_discount_amount' => $baseShippingDiscountAmount,
    'base_shipping_discount_tax_compensation_amnt' => $baseShippingDiscountTaxCompensationAmnt,
    'base_shipping_incl_tax' => $baseShippingInclTax,
    'base_shipping_tax_amount' => $baseShippingTaxAmount,
    'base_subtotal' => $baseSubtotal,
    'base_subtotal_incl_tax' => $baseSubtotalInclTax,
    'base_tax_amount' => $baseTaxAmount,
    'base_total_due' => $baseTotalDue,
    'base_to_global_rate' => $baseToGlobalRate,
    'base_to_order_rate' => $baseToOrderRate,
    'billing_address_id' => $billingAddressId,
    'created_at' => $createdAt,
    'customer_email' => $customerEmail,
    'customer_firstname' => $customerFirstname,
    'customer_group_id' => $customerGroupId,
    'customer_id' => $customerId,
    'customer_is_guest' => $customerIsGuest,
    'customer_lastname' => $customerLastname,
    'customer_note_notify' => $customerNoteNotify,
    'discount_amount' => $discountAmount,
    'email_sent' => $emailSent,
    'entity_id' => $entityId,
    'global_currency_code' => $globalCurrencyCode,
    'grand_total' => $grandTotal,
    'discount_tax_compensation_amount' => $discountTaxCompensationAmount,
    'increment_id' => $incrementId,
    'is_virtual' => $isVirtual,
    'order_currency_code' => $orderCurrencyCode,
    'protect_code' => $protectCode,
    'quote_id' => $quoteId,
    'remote_ip' => $remoteIp,
    'shipping_amount' => $shippingAmount,
    'shipping_description' => $shippingDescription,
    'shipping_discount_amount' => $shippingDiscountAmount,
    'shipping_discount_tax_compensation_amount' => $shippingDiscountTaxCompensationAmount,
    'shipping_incl_tax' => $shippingInclTax,
    'shipping_tax_amount' => $shippingTaxAmount,
    'state' => $state,
    'status' => $status,
    'store_currency_code' => $storeCurrencyCode,
    'store_id' => $storeId,
    'store_name' => $storeName,
    'store_to_base_rate' => $storeToBaseRate,
    'store_to_order_rate' => $storeToOrderRate,
    'subtotal' => $subtotal,
    'subtotal_incl_tax' => $subtotalInclTax,
    'tax_amount' => $taxAmount,
    'total_due' => $totalDue,
    'total_item_count' => $totalItemCount,
    'total_qty_ordered' => $totalQtyOrdered,
    'updated_at' => $updatedAt,
    'weight' => $weight,
    'x_forwarded_for' => $xForwardedFor,
    'items' => $items,
    'billing_address' => $billingAddressArray,
    'shipping_address' => $shippingAddressArray,
    'payment' => $paymentArray
    );



    $url = 'https://erp.theluxuryunlimited.com/api/magento/order-create';

    //Initiate cURL.
    $ch = curl_init($url);

    //Encode the array into JSON.
    $jsonDataEncoded = json_encode($newjsonData);

    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);

    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    // Return response instead of outputting
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //Execute the request
    try{
      $result = curl_exec($ch);
      // $result = 'success';
      $result = json_decode($result, true);
      $status = $result['status'];
      // $status = 'failed';
      $message = $result['message'];
      $err = curl_error($ch);
      if($status == 'true'){
        echo "Order with ID #".$orderRealId.' pushed to ERP';
      }
      else{
        echo "Order with ID #".$orderRealId.' was not pushed to ERP. Kindly repush it.';
      }
      $err = curl_error($ch);
      curl_close($ch);
      // $logger->log(Zend\Log\Logger::INFO, $result.date('Y-m-d H:i:s'));
    }
    catch(Exception $e){
      // $logger->log(Zend\Log\Logger::INFO, $e.'=='.date('Y-m-d H:i:s'));
    }
    // echo '<pre>';
    // print_r($newjsonData);
    $i++;
}
