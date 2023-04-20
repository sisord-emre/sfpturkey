<?php
require_once('config.php');

$tutar=$_POST['tutar'];
$siparisNo=$_POST['siparisNo'];

$kartSahibi=$_POST['name'];
$kartNo=str_replace(" ", '', $_POST['number']);
$kartAyYil=explode('/',$_POST['expiry']);
$kartAy=intval(str_replace(" ", '', $kartAyYil[0]));
$kartYil=intval(str_replace(" ", '', $kartAyYil[1]));
$kartCvc=$_POST['cvc'];

$musteriId=$_POST['musteriId'];
$musteriAdi=$_POST['musteriAdi'];
$musteriSoyadi=$_POST['musteriSoyadi'];
$musteriEmail=$_POST['musteriEmail'];
$musteriTcNo=$_POST['musteriTcNo'];
$musteriUlke=$_POST['musteriUlke'];
$musteriIl=$_POST['musteriIl'];
$musteriAdres=$_POST['musteriAdres'];

$urunId=$_POST['urunId'];
$urunAdi=$_POST['urunAdi'];
$urunKategori=$_POST['urunKategori'];


# create request class
$request = new \Iyzipay\Request\CreatePaymentRequest();
//$request->setLocale(\Iyzipay\Model\Locale::TR);
$request->setLocale($_SESSION["dilKodu"]);
$request->setConversationId($siparisNo);//sipariş no yu yaz dönüşten al
$request->setPrice($tutar);
$request->setPaidPrice($tutar);
//$request->setCurrency(\Iyzipay\Model\Currency::TL);
$request->setCurrency($_SESSION["dilParaBirimi"]);
$request->setInstallment(1);
$request->setCallbackUrl("https://".$_SERVER['HTTP_HOST']."/sfpturkey/20022023tsrm/qcevapIysOdeme.php");///cevabın döneceği adres

$paymentCard = new \Iyzipay\Model\PaymentCard();
$paymentCard->setCardHolderName($kartSahibi);
$paymentCard->setCardNumber($kartNo);
$paymentCard->setExpireMonth($kartAy);
$paymentCard->setExpireYear($kartYil);
$paymentCard->setCvc($kartCvc);
$paymentCard->setRegisterCard(0);
$request->setPaymentCard($paymentCard);

$buyer = new \Iyzipay\Model\Buyer();
$buyer->setId($musteriId);
$buyer->setName($musteriAdi);
$buyer->setSurname($musteriSoyadi);
$buyer->setEmail($musteriEmail);
$buyer->setIdentityNumber($musteriTcNo);
$buyer->setRegistrationAddress($musteriAdres);
$buyer->setIp($_SERVER["REMOTE_ADDR"]);
$buyer->setCity($musteriIl);
$buyer->setCountry($musteriUlke);
$request->setBuyer($buyer);

$shippingAddress = new \Iyzipay\Model\Address();
$shippingAddress->setContactName($musteriAdi." ".$musteriSoyadi);
$shippingAddress->setCity($musteriIl);
$shippingAddress->setCountry($musteriUlke);
$shippingAddress->setAddress($musteriAdres);
$request->setShippingAddress($shippingAddress);

$billingAddress = new \Iyzipay\Model\Address();
$billingAddress->setContactName($musteriAdi." ".$musteriSoyadi);
$billingAddress->setCity($musteriIl);
$billingAddress->setCountry($musteriUlke);
$billingAddress->setAddress($musteriAdres);
$request->setBillingAddress($billingAddress);

$basketItems = array();
$firstBasketItem = new \Iyzipay\Model\BasketItem();
$firstBasketItem->setId($urunId);
$firstBasketItem->setName($urunAdi);
$firstBasketItem->setCategory1($urunKategori);
$firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
$firstBasketItem->setPrice($tutar);
$basketItems[0] = $firstBasketItem;


$request->setBasketItems($basketItems);

# make request
$threedsInitialize = \Iyzipay\Model\ThreedsInitialize::create($request, Config::options());
//logModuleCall('iyzipay3ds', '3dsInit', print_r($request, true), $threedsInitialize->getRawResult());
# print result

if($threedsInitialize->getStatus()=='failure'){
	print_r($threedsInitialize->getErrorMessage());
}else{
	$threedsInitialize->getRawResult();
	print_r($threedsInitialize->getHtmlContent());
}

?>
