<?php
include("../../System/Config.php");

$Id = $_POST['Id'];
$durum = $_POST['durum'];

$fonk->logKayit(2, "Uyeler" . ' ; ' . $Id . ' ; ' . json_encode(["uyeDurum" => $durum])); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$query = $db->update("Uyeler", [
    "uyeDurum" => $durum
], [
    "uyeId" => $Id
]);

if ($query) 
{
    $uye = $db->get("Uyeler", [
        "[>]Diller" => ["Uyeler.uyeId" => "dilId"]
    ], "*", [
        "uyeId" => $Id
    ]);

    if ($durum == 1) 
    {
        $baslik = "Uyelik Durum Bilgisi";
        $mesaj = "Üyeliğiniz aktif edilmiştir. <br /> Kayıtlı e-posta adresiniz ve şifreniz ile bayi girişi yapabilirsiniz. <br /> " . $sabitB["sabitBilgiSiteUrl"]."account.php";
        include("../bildirimMailTemplate.php");
        $fonk->mailGonder($uye["uyeMail"], $baslik, $body);
    }
    echo '1';
}
