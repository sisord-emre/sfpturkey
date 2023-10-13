<?php
include("../Panel/System/Config.php");
extract($_POST);
if ($_POST["gelen"] == 1) 
{
    $parametreler = array(
        'iletisimFormAdSoyad' => $iletisimFormAdSoyad,
        'iletisimFormEmail' => $iletisimFormEmail,
        'iletisimFormTel' => $iletisimFormTel,
        'iletisimFormMesaj' => $iletisimFormMesaj,
        'iletisimFormDurum' => "0",
        'iletisimFormKayitTarihi' => date("Y-m-d H:i:s")
    );
    $query = $db->insert('IletisimFormlari', $parametreler);


    if ($query) {
        $baslik = "Iletisim Formu";
        include("../Mailtemplate/contactMailTemplate.php");
        $sonuc = $fonk->mailGonder($gondericiMail[1], $baslik, $body, $sabitB);
        $sonuc = $fonk->mailGonder("yunus.karaca@sisord.com", $baslik, $body, $sabitB);
        echo '1';
    } else {
        echo '2';
    }
}