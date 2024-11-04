<?php
include("../../System/Config.php");

$fonk->csrfKontrol();

extract($_POST); //POST parametrelerini değişken olarak çevirir

$urunVaryantDefaultSecim =($urunVaryantDefaultSecim == "" ? 0 : $urunVaryantDefaultSecim); 
$urunVaryantFiyat = str_replace(',', '.', $urunVaryantFiyat);
$urunVaryantKampanyasizFiyat = str_replace(',', '.', $urunVaryantKampanyasizFiyat);

//varyant işlemleri
$varyantTableName = "UrunVaryantlari";
$itemPar = array(
    'urunVaryantUrunId' => intval($urunVaryantUrunId),
    'urunVaryantVaryantId' => intval($urunVaryantVaryantId),
    'urunVaryantFiyat' => floatval($urunVaryantFiyat),
    'urunVaryantKampanyasizFiyat' => floatval($urunVaryantKampanyasizFiyat),
    'urunVaryantDefaultSecim' => $urunVaryantDefaultSecim
);

if ($urunVaryantId != "") {
    // İlk olarak urunVaryantUrunId ile bağlı tüm urunVaryantDefaultSecim değerlerini false yap
    $db->update($varyantTableName, ['urunVaryantDefaultSecim' => 0], [
        "urunVaryantUrunId" => $urunVaryantUrunId
    ]);

    // Ardından güncelleme işlemini gerçekleştir
    $fonk->logKayit(2, $varyantTableName . ' ; urunVaryantId ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
    $queryAlt = $db->update($varyantTableName, $itemPar, [
        "urunVaryantId" => $urunVaryantId 
    ]);
} 
else {
    $urunVaryantKodu = mt_rand(100000000, 999999999);
    $itemPar = array_merge($itemPar, array('urunVaryantKodu' => $urunVaryantKodu));
    
    $fonk->logKayit(1, $varyantTableName . ' ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
    $queryAlt = $db->insert($varyantTableName, $itemPar);
    $urunVaryantId = $db->id();
}

//dile göre değerlerin kayıt edilmesi
$itemTableName = "UrunVaryantDilBilgiler";
$dilList = $db->select("Diller", "*");
foreach ($dilList as $dil) {
    $itemPrimaryId = $_POST["urunVaryantDilBilgiId-" . $dil["dilId"]]; //primary sutun
    
    if ($_POST["urunVaryantDilBilgiDurum-" . $dil["dilId"]] == "") {
        $_POST["urunVaryantDilBilgiDurum-" . $dil["dilId"]] = 0;
    }
    $itemPar = array(
        'urunVaryantDilBilgiUrunId' => intval($urunVaryantUrunId),
        'urunVaryantDilBilgiVaryantId' =>  intval($urunVaryantId),
        'urunVaryantDilBilgiDilId' => $dil["dilId"],
        'urunVaryantDilBilgiAdi' => $_POST["urunVaryantDilBilgiAdi-" . $dil["dilId"]],
        'urunVaryantDilBilgiSlug' => $_POST["urunVaryantDilBilgiSlug-" . $dil["dilId"]],
        'urunVaryantDilBilgiDescription' => "",
        'urunVaryantDilBilgiEtiketler' => $_POST["urunVaryantDilBilgiEtiketler-" . $dil["dilId"]],
        'urunVaryantDilBilgiAciklama' => "",
        'urunVaryantDilBilgiDurum' => $_POST["urunVaryantDilBilgiDurum-" . $dil["dilId"]]
    );

    if ($itemPrimaryId != "") {
        $fonk->logKayit(2, $itemTableName . ' ; ' . $itemPrimaryId . ' ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
        $queryAlt = $db->update($itemTableName, $itemPar, [
            "urunVaryantDilBilgiVaryantId" => $urunVaryantId,
            "urunVaryantDilBilgiId" => $itemPrimaryId
        ]);
    } 
    else {
        $fonk->logKayit(1, $itemTableName . ' ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
        $queryAlt = $db->insert($itemTableName, $itemPar);
    }
}

if ($queryAlt) {
    echo '1';
}
?>
