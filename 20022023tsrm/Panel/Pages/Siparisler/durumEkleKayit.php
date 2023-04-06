<?php
include ("../../System/Config.php");

$fonk->csrfKontrol();

$tableName="SiparisSiparisDurumlari";

extract($_POST);//POST parametrelerini değişken olarak çevirir

$siparis = $db->get("Siparisler",[
    "[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"]
],"*",[
    "siparisId" => $siparisSiparisDurumSiparisId
]);

$siparisDurum = $db->get("SiparisDurumlari",[
    "[<]SiparisDurumDilBilgiler" => ["SiparisDurumlari.siparisDurumId" => "siparisDurumDilBilgiSiparisDurumId"]
],"*",[
    "siparisDurumId" => $siparisSiparisDurumSiparisDurumId,
    "siparisDurumDilBilgiDilId" => $siparis["siparisDilId"]
]);

$parametreler=array(
    'siparisSiparisDurumSiparisId' => intval($siparisSiparisDurumSiparisId),
    'siparisSiparisDurumSiparisDurumId' => intval($siparisSiparisDurumSiparisDurumId),
    'siparisSiparisDurumKargoFirmaId' => intval($siparisSiparisDurumKargoFirmaId),
    'siparisSiparisDurumKargoTakipKodu' => $siparisSiparisDurumKargoTakipKodu,
    'siparisSiparisDurumKargoTakipLink' => $siparisSiparisDurumKargoTakipLink,
    'siparisSiparisDurumKayitTarihi' => date("Y-m-d H:i:s")
);

if ($siparisSiparisDurumSiparisDurumId==6) {//teslim Edildi
    $icerikUpdate = $db->update("SiparisIcerikleri",[
        'siparisIcerikTeslimatDurumu' => 2
    ],[
        "siparisIcerikSiparisId" => $siparisSiparisDurumSiparisId
    ]);
}else if ($siparisSiparisDurumSiparisDurumId==7) {//iade Edildi
    $icerikUpdate = $db->update("SiparisIcerikleri",[
        'siparisIcerikTeslimatDurumu' => 3
    ],[
        "siparisIcerikSiparisId" => $siparisSiparisDurumSiparisId
    ]);
}else{//bekliyor
    $icerikUpdate = $db->update("SiparisIcerikleri",[
        'siparisIcerikTeslimatDurumu' => 1
    ],[
        "siparisIcerikSiparisId" => $siparisSiparisDurumSiparisId
    ]);
}

if($siparisSiparisDurumId==""){
    $fonk->logKayit(1,$tableName.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
    ///ekleme
    $query = $db->insert($tableName, $parametreler);
}else{
    $fonk->logKayit(2,$tableName.' ; '.$siparisSiparisDurumId.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
    ///güncelleme
    $query = $db->update($tableName, $parametreler, [
        "siparisSiparisDurumId" => $siparisSiparisDurumId
    ]);
}

if ($query){
    echo '1';
}
if ($mailGonderim==1) {
    $baslik=$fonk->getDil("OrderInformation");
    $mesaj=$fonk->getDil("Order code").":".$siparis["siparisKodu"]."<br />".$fonk->getDil("Order Status").":".$siparisDurum["siparisDurumDilBilgiBaslik"];
    if ($siparisSiparisDurumSiparisDurumId==5) {//kargolandı
        $mesaj.="<br />".$fonk->getDil("Tracking Code").":".$siparisSiparisDurumKargoTakipKodu."<br />".$fonk->getDil("Follow Link").":".$siparisSiparisDurumKargoTakipLink;
    }
    include ("../bildirimMailTemplate.php");
    $fonk->mailGonder($siparis["uyeMail"],$baslik,$body);
}
?>
