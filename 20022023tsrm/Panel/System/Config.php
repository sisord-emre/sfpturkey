<?php
session_start();

error_reporting(E_ALL);//hata açma kodları
ini_set("display_errors", 0);
date_default_timezone_set('Europe/Istanbul');
//setlocale(LC_ALL, 'tr_TR.UTF-8');
require 'medoo.php';
include ('class.upload.php');
require 'PHPMailer/class.phpmailer.php';
require 'php-excel.class.php';
include ('fonksiyonlar.php');

$loginUrl="/sfpturkey/20022023tsrm/Panel/login.php";//login urli
$panelUrl="sfpturkey/20022023tsrm/Panel";//panel urli

use Medoo\Medoo;
//host,veritabanı adı,kullanıcı adı,şifre
$db = new Medoo([
	'type' => 'pgsql',
	'host' => '185.136.86.50',
	'database' => 'admin_sfp',
	'username' => 'admin_sfp_8282',
	'password' => 'Zk4#om613',
	'port' => 5432,
	'charset' => 'utf8',
	'collation' => 'utf8_general_ci',
	"logging" => false,
	'error' => PDO::ERRMODE_SILENT
]);

$sabitB = $db->get("SabitBilgiler", "*", [
	"sabitBilgiId" => 1
]);

$gondericiMail=explode(';',$sabitB['sabitBilgiMail']);
$siteMail=explode(';',$sabitB['sabitBilgiMail']);

$fonk=new Fonksiyonlar($db,$sabitB);//Fonksiyonlar

//İp kontrolu aktif ise kontroller yapılıyor
if($sabitB['sabitBilgiIpKontrol']==1 && strstr(strtolower($_SERVER['REQUEST_URI']), strtolower($panelUrl))){ //panelden ip kontrol aktif ise
	$ipler=explode(';',$sabitB['sabitBilgiIzinliIpler']);
	$guvenli=false;
	for($i=0;$i<Count($ipler);$i++){
		if($ipler[$i]==$_SERVER['REMOTE_ADDR']){
			$guvenli=true;
		}
	}
	if(!$guvenli) {
		unset($_SESSION['SessionKey']);
		session_regenerate_id(true); //sessionId sıfırlamak için
		session_destroy();
		session_start();
		$fonk->yonlendir("/");
	}
}

// ssl açık olarak ayarlanmış ise bu kısım çalışır ve http den https e yönlenridirir
if ($sabitB['sabitBilgiSsl'] == 1) {
	//If the HTTPS is not found to be "on"
	if (!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on") {
		//Tell the browser to redirect to the HTTPS URL.
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
		//Prevent the rest of the script from executing.
		exit;
	}
}

$_GET = $fonk->injKontrol($_GET,$panelUrl); //  GET verilerini temizledik.
$_POST = $fonk->injKontrol($_POST,$panelUrl); // POST verilerini temizledik.
$_SESSION = $fonk->injKontrol($_SESSION,$panelUrl); // SESSION verilerini temizledik.
$_COOKIE = $fonk->injKontrol($_COOKIE,$panelUrl); // COOKIE verilerini temizledik.

if (!isset($_SESSION['SessionKey']) || $_SESSION['SessionKey'] == "") {//oturum açılmışmı
	if (strstr(strtolower($_SERVER['REQUEST_URI']), strtolower($panelUrl)) && strtolower($_SERVER['REQUEST_URI']) != strtolower($loginUrl)) { //**Panel Klasör adı değişir ise buraradan günceleyiniz
		session_regenerate_id(true); //sessionId sıfırlamak için
		$fonk->yonlendir($loginUrl);
		exit;
	}
}
else {
	///yetkilendirme
	$kulBilgi = $db->get("Kullanicilar", "*", [
		"kullaniciDurum" => 1,
		"kullaniciGizle" => 0,
		"kullaniciSessionKey" => $_SESSION['SessionKey']
	]);

	if (!$kulBilgi && strstr(strtolower($_SERVER['REQUEST_URI']), strtolower($panelUrl))) {
		session_regenerate_id(true); //sessionId sıfırlamak için
		session_destroy();
		session_start();
		$fonk->yonlendir($loginUrl);
		exit;
	}else {
		$kullaniciYetkiler = explode(';', $kulBilgi['kullaniciYetkiler']);
		$fonk->setKullanici($kulBilgi['kullaniciId']);
	}
}

if (empty($_SESSION['token'])) {
	$_SESSION['token'] = bin2hex(random_bytes(32));
}

//Site dil bloğu başlangıç
if ($sabitB["sabitBilgiDilGosterim"]==1) {
	if ($_SESSION["dilId"]=="") {
		$dilSecim = $db->get("Diller", "*", [
			"dilId" => $sabitB["sabitBilgiVarsayilanDilId"],
			"dilDurumu" => 1
		]);
		$fonk->dilOturum($dilSecim);
	}
	if (strlen($_GET["dil"])>0 && strlen($_GET["dil"])<=3) {
		$dilSecim = $db->get("Diller", "*", [
			"dilKodu" => $_GET["dil"],
			"dilDurumu" => 1
		]);
		$fonk->dilOturum($dilSecim);
	}
}
//Site dil bloğu bitiş

//panel dil bloğu başlangıç
if ($sabitB["sabitBilgiPanelDilGosterim"]==1) {
	if ($_SESSION["panelDilId"]=="") {
		$dilSecim = $db->get("Diller", "*", [
			"dilId" => $sabitB["sabitBilgiPanelVarsayilanDilId"],
			"dilPanelDurumu" => 1
		]);
		$fonk->dilPOturum($dilSecim);
	}
	if (strlen($_GET["panelDil"])>0 && strlen($_GET["panelDil"])<=3) {
		$dilSecim = $db->get("Diller", "*", [
			"dilKodu" => $_GET["panelDil"],
			"dilPanelDurumu" => 1
		]);
		$fonk->dilPOturum($dilSecim);
	}
}
//panel dil bloğu bitiş

$_SESSION["paraBirimSembol"]="₺";
$_SESSION["paraBirimId"]=1;
$_SESSION["paraBirimKodu"]="TRY";

// $itemTableName="UrunGorselleri";
// $itemPar=array(
// 	'urunGorselBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/Urunler/",
// );
// $sql = $db->update($itemTableName, $itemPar);

// $itemTableName="Urunler";
// $itemPar=array(
// 	'urunBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/Urunler/",
// );
// $sql = $db->update($itemTableName, $itemPar);

// $itemTableName="Slider";
// $itemPar=array(
// 	'sliderBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/Slider/",
// );
// $sql = $db->update($itemTableName, $itemPar);

// $itemTableName="Kategoriler";
// $itemPar=array(
// 	'kategoriGorselBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/Kategori/",
// 	'kategoriGorselKareBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/Kategori-Kare/",
// );
// $sql = $db->update($itemTableName, $itemPar);

// $itemTableName="Kataloglar";
// $itemPar=array(
// 	'katalogGorselBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/Katalog/",
// 	'katalogDosyaBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/Katalog-Dosya/",
// );
// $sql = $db->update($itemTableName, $itemPar);

// $itemTableName="Brands";
// $itemPar=array(
// 	'brandsGorselBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/Brands/",
// );
// $sql = $db->update($itemTableName, $itemPar);
?>
