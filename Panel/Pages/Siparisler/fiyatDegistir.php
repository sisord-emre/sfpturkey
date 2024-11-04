<?php
include("../../System/Config.php");

header('Content-Type: application/json; charset=utf-8');

extract($_POST);

$iskontoFiyat = str_replace(',', '.', $iskontoFiyat);

$fonk->logKayit(2, "Siparisler" . ' ; ' . $Id . ' ; ' . json_encode(["siparisIskontoUcreti" => $iskontoFiyat])); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$json = array("status" => "error", "result" => "");
$query = $db->update("Siparisler", [
    "siparisIskontoUcreti" => $iskontoFiyat
], [
    "siparisId" => $Id
]);

if($query){
    $json["status"]="success";
}

$siparisKontrol = $db->get("Siparisler", "*", [
    "siparisId" =>  $Id
]);

$siparisIcerikleri = $db->select("SiparisIcerikleri", [
    "[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
    "[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
], "*", [
    "urunDilBilgiDilId" => $siparisKontrol["siparisDilId"],
    "urunDurum" => 1,
    "urunDilBilgiDurum" => 1,
    "siparisIcerikSiparisId" => $Id
]);

$toplamTutar = 0;
$siparisIskontoUcreti = 0;
foreach ($siparisIcerikleri as $siparisIcerik) {
    $toplamTutar += $siparisIcerik['siparisIcerikAdet'] * $siparisIcerik['siparisIcerikFiyat'];
}
if ($siparisKontrol['siparisIndirimKodu'] != "" && $siparisKontrol['siparisIndirimYuzdesi'] != 0) {
    $toplamTutar -= ($toplamTutar / 100 * $siparisKontrol['siparisIndirimYuzdesi']);
}
if ($siparisKontrol['siparisKargoUcreti'] != 0) {
    $toplamTutar += $siparisKontrol['siparisKargoUcreti'];
}
if ($siparisKontrol["siparisIskontoUcreti"] > 0) {
    $siparisIskontoUcreti = $fonk->paraCevir($siparisKontrol["siparisIskontoUcreti"], "USD", "TRY");
    $toplamTutar -= $siparisIskontoUcreti;
}

$json["result"] = array(
    "tutar" => $toplamTutar
);
print_r(json_encode($json));
