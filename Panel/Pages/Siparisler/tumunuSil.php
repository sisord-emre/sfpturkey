<?php
include ("../../System/Config.php");

$tableName="Siparisler";
$listeleme = $db->select($tableName,[
    "[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
    "[>]UyeAdresler" => ["Siparisler.siparisTeslimatUyeAdresId" => "uyeAdresId"],
    "[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
    "[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
    "[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
    "[>]OdemeTipleri" => ["Siparisler.siparisOdemeTipiId" => "odemeTipId"],
    "[>]Diller" => ["Siparisler.siparisDilId" => "dilId"],
    "[>]ParaBirimleri" => ["Siparisler.siparisParaBirimId" => "paraBirimId"]
],"*");
foreach($listeleme as $list){
    $siparisDurum = $db->get("SiparisSiparisDurumlari",[
        "[<]SiparisDurumlari" => ["SiparisSiparisDurumlari.siparisSiparisDurumSiparisDurumId" => "siparisDurumId"],
        "[<]SiparisDurumDilBilgiler" => ["SiparisDurumlari.siparisDurumId" => "siparisDurumDilBilgiSiparisDurumId"],
        "[>]KargoFirmalari" => ["SiparisSiparisDurumlari.siparisSiparisDurumKargoFirmaId" => "kargoFirmaId"]
    ],"*",[
        "siparisSiparisDurumSiparisId" => $list["siparisId"],
        "siparisDurumDilBilgiDilId" => $list["siparisDilId"],
        "ORDER" => [
            "siparisSiparisDurumId" => "DESC",
        ]
    ]);
    if($siparisDurum["siparisSiparisDurumSiparisDurumId"]!=1){//ödeme bekliyor ise atla
        continue;
    }

    $fonk->logKayit(3,$tableName.' ; '.json_encode($list));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

    $sil = $db->delete($tableName, [
        "siparisId" => $list["siparisId"]
    ]);

    $silIcerik = $db->delete("SiparisIcerikleri", [
        "siparisIcerikSiparisId" => $list["siparisId"]
    ]);

    $silIcerik = $db->delete("SiparisSiparisDurumlari", [
        "siparisSiparisDurumSiparisId" => $list["siparisId"]
    ]);
}

if ($sil){
    echo '1';
}else{
    echo '0';
}
?>
