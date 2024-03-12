<?php
include("../../System/Config.php");

extract($_POST);

$siparisKontroller = $db->select("Siparisler", "*", [
    "siparisOdemeTipiId" => 0
]);

$count=0;
foreach ($siparisKontroller as $siparisKontrol) {
    $siparisIcerikleri = $db->select("SiparisIcerikleri",[
        "[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
        "[<]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
    ], "*", [
        "siparisIcerikSiparisId" => $siparisKontrol["siparisId"]
    ]);

    $itemTableName = "SiparisIcerikleri";
    foreach ($siparisIcerikleri as $siparisIcerik) {
        $siparisIcerikFiyat = $fonk->paraCevir($siparisIcerik["siparisIcerikPanelFiyat"],$siparisIcerik["paraBirimKodu"],"TRY");
        $itemPar = array(
            'siparisIcerikFiyat' =>$siparisIcerikFiyat
        );

        $fonk->logKayit(2, $itemTableName . ' ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
        $queryAlt = $db->update($itemTableName, $itemPar, [
            "siparisIcerikId" => $siparisIcerik["siparisIcerikId"]
        ]);

        //echo $siparisIcerik["siparisIcerikId"]."------".$siparisIcerikFiyat."<br>";
        $siparisIcerikFiyat=0;
    }
    $count++;
   
}
if($count > 0){
    echo "1";
}
