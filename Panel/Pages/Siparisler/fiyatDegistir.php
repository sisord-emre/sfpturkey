<?php
include("../../System/Config.php");

header('Content-Type: application/json; charset=utf-8');

extract($_POST);

$iskontoFiyat = str_replace(',', '.', $iskontoFiyat);
$fonk->logKayit(2, "Siparisler" . ' ; ' . $Id . ' ; ' . json_encode(["siparisIskontoUcreti" => $iskontoFiyat])); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$json = array("status" => "error", "result" => "");

$siparisIskontoUcreti = $fonk->paraCevir($iskontoFiyat, "USD", "TRY");
$siparisKontrol = $db->get("Siparisler", "*", [
    "siparisId" => $Id
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

$toplamTutar = array_reduce($siparisIcerikleri, function($carry, $item) {
    return $carry + ($item['siparisIcerikAdet'] * $item['siparisIcerikFiyat']);
}, 0);

if($toplamTutar > $siparisIskontoUcreti) 
{
    $query = $db->update("Siparisler", [
        "siparisIskontoUcreti" => $iskontoFiyat
    ], [
        "siparisId" => $Id
    ]);
    
    if ($query) {
        $json["status"] = "success";
        //eğer güncelleme başarılı ise toplam tutardan iskonto tutarını çıkarabiliriz
        $toplamTutar -= $siparisIskontoUcreti;
    }

    if ($siparisKontrol['siparisIndirimKodu'] != "" && $siparisKontrol['siparisIndirimYuzdesi'] != 0) {
        $toplamTutar -= ($toplamTutar / 100 * $siparisKontrol['siparisIndirimYuzdesi']);
    }
    if ($siparisKontrol['siparisKargoUcreti'] != 0) {
        $toplamTutar += $siparisKontrol['siparisKargoUcreti'];
    }
    
    $json["result"] = array(
        "tutar" => $toplamTutar
    );
}
else 
{
    $json["status"] = "error";
    $json["result"] = "Uyarı: İskonto ücreti toplam tutardan büyük olamaz!";
}

echo json_encode($json);
?>
