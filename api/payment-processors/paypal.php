<?php
/**
 * Snat's Framework - Snat's PHP framework of commonly used functions.
 * This part is for PayPal stuff.
 * 
 * @link      https://snat.co.uk/
 * @author    Snat
 * @copyright Copyright (c) Matthew Terra Ellis
 * @license   https://opensource.org/licenses/MIT MIT License
*/

function snat_framework_paypal_send_payment($client_id, $client_secret, $recipient_email, $amount, $currency = 'USD', $note = '') {
    // Set up the PayPal API client
    $context = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential($client_id, $client_secret)
    );
    
    // Create a new payout object
    $payout = new \PayPal\Api\Payout();
    $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
    
    // Set the batch header details
    $senderBatchHeader->setSenderBatchId(uniqid())
        ->setEmailSubject("Payment from your app");
    
    // Create the payout item
    $senderItem = new \PayPal\Api\PayoutItem();
    $senderItem->setRecipientType('Email')
        ->setReceiver($recipient_email)
        ->setAmount(new \PayPal\Api\Currency(array('value' => $amount, 'currency' => $currency)))
        ->setNote($note)
        ->setSenderItemId(uniqid());
    
    // Add the payout item to the payout object
    $payout->setSenderBatchHeader($senderBatchHeader)
        ->addItem($senderItem);
    
    // Send the payout request
    $request = clone $payout;
    try {
        $output = $payout->create(null, $context);
    } catch (\Exception $ex) {
        $message = $ex->getMessage();
        return $message;
    }
  
    return $output;
  }

  function snat_framework_paypal_create_subscription($client, $plan_id, $subscriber_email, $start_time = null, $custom_id = null) {
    $subscription = new \PayPal\Api\Subscription();

    // Set the plan ID
    $plan = new \PayPal\Api\Plan();
    $plan->setId($plan_id);
    $subscription->setPlanId($plan);

    // Set the subscriber email
    $subscriber = new \PayPal\Api\Subscriber();
    $subscriber->setEmail($subscriber_email);
    $subscription->setSubscriber($subscriber);

    // Set the optional start time
    if ($start_time !== null) {
        $subscription->setStartTime($start_time);
    }

    // Set the optional custom ID
    if ($custom_id !== null) {
        $subscription->setCustomId($custom_id);
    }

    // Create the subscription
    try {
        $result = $subscription->create($client);
        return $result;
    } catch (\Exception $e) {
        // Handle any errors
        echo $e->getMessage();
        return false;
    }
}

function snat_framework_paypal_create_order($client, $item_name, $item_price, $currency, $return_url, $cancel_url) {
    $order = new \PayPal\Api\Order();

    // Set the intent to capture
    $order->setIntent('CAPTURE');

    // Set the purchase unit
    $purchase_unit = new \PayPal\Api\PurchaseUnit();
    $purchase_unit->setDescription($item_name);

    // Set the amount
    $amount = new \PayPal\Api\Amount();
    $amount->setCurrency($currency);
    $amount->setTotal($item_price);
    $purchase_unit->setAmount($amount);

    // Add the purchase unit to the order
    $order->setPurchaseUnits(array($purchase_unit));

    // Set the URLs
    $redirect_urls = new \PayPal\Api\RedirectUrls();
    $redirect_urls->setReturnUrl($return_url);
    $redirect_urls->setCancelUrl($cancel_url);
    $order->setRedirectUrls($redirect_urls);

    // Create the order
    try {
        $result = $order->create($client);
        return $result;
    } catch (\Exception $e) {
        // Handle any errors
        echo $e->getMessage();
        return false;
    }
}


?>