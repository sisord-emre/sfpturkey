<?php
include("../../System/Config.php");

$tableName = "UrunVaryantlari";
$silID = $_POST['silID'];

if ($sabitB['sabitBilgiLog'] == 1) {
	$logSilme = $db->get($tableName, "*", [
		"urunVaryantId" => $silID
	]);
	$fonk->logKayit(3, $tableName . ' ; ' . json_encode($logSilme)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
}

$sil = $db->delete($tableName, [
	"urunVaryantId" => $silID
]);

if ($sil) {
	$itemTableName = "UrunVaryantDilBilgiler";
	$itemSil = $db->delete($itemTableName, [
		"urunVaryantDilBilgiVaryantId" => $silID
	]);
	if($itemSil){
		echo '1';
	}
	else {
		echo '0';
	}
} 
else {
	echo '0';
}
