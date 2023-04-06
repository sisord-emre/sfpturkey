<?php
include("../../System/Config.php");

$fonk->csrfKontrol();

extract($_POST); //POST parametrelerini değişken olarak çevirir


//varyant işlemleri
$varyantTableName = "UrunVaryantlari";
$itemPar = array(
	'urunVaryantUrunId' => $urunVaryantUrunId,
	'urunVaryantVaryantId' => $urunVaryantVaryantId,
	'urunVaryantFiyat' => $urunVaryantFiyat,
	'urunVaryantDefaultSecim' => $urunVaryantDefaultSecim
);
if ($urunVaryantId != "") {
	$fonk->logKayit(2, $varyantTableName . ' ; urunVaryantId ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
	///güncelleme
	$queryAlt = $db->update($varyantTableName, $itemPar, [
		"urunVaryantId" => $urunVaryantId 
	]);
} 
else {
	$urunVaryantKodu = mt_rand(100000000, 999999999);
	$itemPar = array_merge($itemPar, array('urunVaryantKodu' => $urunVaryantKodu));
	
	$fonk->logKayit(1, $varyantTableName . ' ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
	///ekleme
	$queryAlt = $db->insert($varyantTableName, $itemPar);
	$urunVaryantId = $db->id();
}


//dile göre değerlerin kayıt edilmesi
$itemTableName = "UrunVaryantDilBilgiler";
$dilList = $db->select("Diller", "*");
foreach ($dilList as $dil) {
	$itemPrimaryId = $_POST["urunVaryantDilBilgiId-" . $dil["dilId"]]; //primary sutun
	
	if ($_POST["urunVaryantDilBilgiDurum-" . $dil["dilId"]] == "") {
		$_POST["urunVaryantDilBilgiDurum-" . $dil["dilId"]] = 0;
	}
	$itemPar = array(
		'urunVaryantDilBilgiUrunId' => $urunVaryantUrunId,
		'urunVaryantDilBilgiVaryantId' => $urunVaryantId,
		'urunVaryantDilBilgiDilId' => $dil["dilId"],
		'urunVaryantDilBilgiAdi' => $_POST["urunVaryantDilBilgiAdi-" . $dil["dilId"]],
		'urunVaryantDilBilgiSlug' => $_POST["urunVaryantDilBilgiSlug-" . $dil["dilId"]],
		'urunVaryantDilBilgiDescription' => $_POST["urunVaryantDilBilgiDescription-" . $dil["dilId"]],
		'urunVaryantDilBilgiEtiketler' => $_POST["urunVaryantDilBilgiEtiketler-" . $dil["dilId"]],
		'urunVaryantDilBilgiAciklama' => $_POST["urunVaryantDilBilgiAciklama-" . $dil["dilId"]],
		'urunVaryantDilBilgiDurum' => $_POST["urunVaryantDilBilgiDurum-" . $dil["dilId"]]
	);

	// echo "<pre>"; 
	// print_r($itemPar);
	// echo "</pre>";

	if ($itemPrimaryId != "") {
		$fonk->logKayit(2, $itemTableName . ' ; ' . $itemPrimaryId . ' ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
		///güncelleme
		$queryAlt = $db->update($itemTableName, $itemPar, [
			"urunVaryantDilBilgiVaryantId" => $urunVaryantId,
			"urunVaryantDilBilgiId" => $itemPrimaryId
		]);
	} 
	else {
		$fonk->logKayit(1, $itemTableName . ' ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
		///ekleme
		$queryAlt = $db->insert($itemTableName, $itemPar);
	}
}
//!dile göre değerlerin kayıt edilmesi

if ($queryAlt) {
	echo '1';
}
