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
    'siparisSiparisDurumKargoTakipLink' => "",
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

if($siparisSiparisDurumSiparisDurumId==5)//kargolandı
{
  /*   $UrunListBotlariListeleme = $db->get("UrunListBotlari", "*",[
        "urunListBotTip" => 2,
        "ORDER" => [
            "urunListBotId" => "DESC",
        ]
    ]);

    if($UrunListBotlariListeleme["urunListBotDataJson"])
    {
        // Sample array
        $xmlData = [
            'AYAR' => [
                'TRSVER' => '<![CDATA[ASWSH1.02.03]]>',
                'SERVERNAME' => '<![CDATA[195.174.216.24]]>',
                'DBFILENAME' => '<![CDATA[C:\AKINSOFT\Wolvox8\Database_FB\01\2022\WOLVOX.FDB]]>',
                'LCTYPE' => '<![CDATA[WIN1254]]>',
                'COLLATE' => '<![CDATA[PXW_TURK_CI_AI]]>',
                'PERSUSER' => '<![CDATA[MUHASEBE]]>',
                'SUBE_KODU' => '<![CDATA[ARGESUBE1]]>'
            ],
            'STOKHAREKET' => []
        ];

        $data = json_decode($UrunListBotlariListeleme["urunListBotDataJson"], true);

        $m=0;
        $array_count = count($data["report"]["table"]["row"]);
        for($i=0; $i<$array_count; $i++)
        {
            $urunModelleri = $db->get("SiparisIcerikleri",[
                "[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
                "[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
                "[<]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
            ],"*",[
                "urunDilBilgiDilId" => $siparis["siparisDilId"],
                "urunModel" => $data["report"]["table"]["row"][$i]["STOKKODU"]["__cdata"],
                "urunDurum" => 1,
                "urunDilBilgiDurum" => 1,
                "siparisIcerikSiparisId" => $siparisSiparisDurumSiparisId
            ]);
            
            if($urunModelleri)
            {
                $m++;
                $data["report"]["table"]["row"][$i]["STOKKODU"]["__cdata"];
                array_push(
                    $xmlData['STOKHAREKET'], 
                    [
                        'HAREKET' => 
                        [
                            'BLSTKODU' => '<![CDATA['.$data["report"]["table"]["row"][$i]["BLKODU"]["__cdata"].']]>',
                            'DEPO_ADI' => '<![CDATA[MERKEZ]]>',
                            'KPB_FIYATI' => '<![CDATA['.$urunModelleri["urunVaryantFiyat"].']]>',
                            'MIKTAR_2' => '<![CDATA['.$urunModelleri["siparisIcerikAdet"].']]>',
                            'TUTAR_TURU' => '<![CDATA[0]]>'
                        ]
                    ]
                );
            }
        }
        $xmlString = $fonk->arrayToXml($xmlData);
        $xmlString=$fonk->parseToXML($xmlString);
    }

    $parametreSiparisStok=array(
        'siparisStokPostSiparisId' => $siparisSiparisDurumSiparisId,
        'siparisStokPostDataXml' => str_replace("\n","",$xmlString),
        'siparisStokPostDurum' => 1,
        'siparisStokPostIslemYapan' => $kulBilgi['kullaniciAdSoyad'],
        'siparisStokPostKayitTarihi' => date("Y-m-d H:i:s")
    );

    $siparisStokPostList = $db->get("SiparisStokPost","*",[
        "siparisStokPostSiparisId" => $siparisSiparisDurumSiparisId
    ]);

    if(!$siparisStokPostList)
    {
        $siparisStokPostInsert = $db->insert("SiparisStokPost", $parametreSiparisStok);
    }
    else {
        echo '2';
        exit;
    } */
}

if ($query)
{
    echo '1';
}
if ($mailGonderim==1) 
{
    $baslik = "Siparis No: ".$siparis["siparisKodu"]."";
    $mesaj="Değerli İş Ortağımız;";
    if ($siparisSiparisDurumSiparisDurumId==5) 
    {
        //Sipariş Kargolandı
        $siparisKargoFirmaBilgisi = $db->get("SiparisSiparisDurumlari",[
            "[<]KargoFirmalari" => ["SiparisSiparisDurumlari.siparisSiparisDurumKargoFirmaId" => "kargoFirmaId"]
        ],"*",[
            "siparisSiparisDurumSiparisId" => $siparisSiparisDurumSiparisId,
            "siparisSiparisDurumSiparisDurumId" => 5,
        ]);

        $mesaj.="<br /> Siparişiniz kargoya verilmiştir. <br /> Kargo firması: ".$siparisKargoFirmaBilgisi["kargoFirmaAdi"]."<br /> Takip kodu: ".$siparisSiparisDurumKargoTakipKodu;
    }
    if($siparisSiparisDurumSiparisDurumId==6)
    {
        //Sipariş Teslim Edildi
        $mesaj.="<br /> Siparişiniz teslim edilmiştir. <br /> Alışverişiniz için teşekkür eder, iyi günler dileriz.";
    }
    if($siparisSiparisDurumSiparisDurumId==2)
    {
        //Ödeme Yapıldı
        $mesaj.="<br /> Ödemeniz alınmıştır.";
    }
    if($siparisSiparisDurumSiparisDurumId==3)
    {
        //Sipariş Onaylandı
        $mesaj.="<br /> Siparişiniz onaylanmıştır.";
    }
    if($siparisSiparisDurumSiparisDurumId==7)
    {
        //Sipariş İade Edildi
        $mesaj.="<br /> Ödemeniz iade edilmiştir.";
    }
    if($siparisSiparisDurumSiparisDurumId==8)
    {
        //Sipariş İptal Edildi
        $mesaj.="<br /> Siparişiniz iptal edilmiştir.";
    }
    include ("../bildirimMailTemplate.php");
    $fonk->mailGonder($siparis["uyeMail"],$baslik,$body);
}
?>
