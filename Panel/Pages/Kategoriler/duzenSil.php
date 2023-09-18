<?php
include("../../System/Config.php");
$sil = $_POST['sil'];

$isControlUrunKategori = $db->get("UrunKategoriler", [
	"[>]Kategoriler" => ["UrunKategoriler.urunKategoriKategoriId" => "kategoriId"]
], "*", [
	"urunKategoriKategoriId" => $sil
]);

if ($isControlUrunKategori) 
{
	$logSilme = $db->get("Kategoriler", "*", [
		"Kategoriler" => $sil
	]);
	$fonk->logKayit(2, "Kategoriler" . ' ; ' . json_encode($logSilme)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

	$query = $db->update("Kategoriler", [
		'kategoriDurum' => 0 //pasif
	], [
		"kategoriId" => $sil
	]);

	$query = $db->update("KategoriDilBilgiler", [
		'kategoriDilBilgiDurum' => 0 //pasif
	], [
		"kategoriDilBilgiKategoriId" => $sil
	]);
} 
else 
{
	$logSilme = $db->get("Kategoriler", "*", [
		"Kategoriler" => $sil
	]);
	$fonk->logKayit(3, "Kategoriler" . ' ; ' . json_encode($logSilme)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

	$silAltItem = $db->delete("KategoriDilBilgiler", [
		"kategoriDilBilgiKategoriId" => $sil
	]);

	$silUrunKategori = $db->delete("UrunKategoriler", [
		"urunKategoriKategoriId" => $sil
	]);

	$silItem = $db->delete("Kategoriler", [
		"kategoriId" => $sil
	]);
}

if ($silItem) {
	echo "1";
}