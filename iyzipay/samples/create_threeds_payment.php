<?php

require_once('config.php');

# create request class
$request = new \Iyzipay\Request\CreateThreedsPaymentRequest();
$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setConversationId($_POST['conversationId']);
$request->setPaymentId($_POST["paymentId"]);
$request->setConversationData($_POST['conversationData']);

# make request
$threedsPayment = \Iyzipay\Model\ThreedsPayment::create($request, Config::options());

/*
# print result
if ($threedsPayment->getStatus() == "success"){
  echo 1;
}else{
  echo $threedsPayment->getErrorMessage();
}
//status almak için; $degiskenAdi->getStatus();
//errorCode almak için; $degiskenAdi->getErrorCode();
//errorMessage almak için; $degiskenAdi->getErrorMessage();
//systemTime almak için; $degiskenAdi->getSystemTime();
//conversationId almak için; $degiskenAdi->getConversationId();
*/
