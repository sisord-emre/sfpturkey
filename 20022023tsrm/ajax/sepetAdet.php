<?php
include("../Panel/System/Config.php");
header('Content-Type: application/json; charset=utf-8');

extract($_POST);

if($_SESSION['Sepet']=="" || $_SESSION['Sepet']=="null" || $_SESSION['Sepet']==null || !isset($_SESSION['Sepet'])){
    $_SESSION['Sepet']="[]";
}
$sepet=json_decode($_SESSION['Sepet'],true);
$json=array("status"=>"error","result"=>"");
$toplamTutar=0;
for ($i=0; $i <count($sepet) ; $i++) {
    if ($i==$sira) {
        $json["status"]="success";
        $sepet[$i]["adet"]=$adet;
    }
    
    $urun = $db->get("Urunler", [
        "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
        "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
        "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
        "[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
        "[>]KategoriDilBilgiler" => ["UrunKategoriler.urunKategoriKategoriId" => "kategoriDilBilgiKategoriId"],
    ],"*",[
        "urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
        "urunVaryantDilBilgiVaryantId" => $sepet[$i]["urunId"],
        "urunVaryantDilBilgiDurum" => 1,
        "ORDER" => [
            "urunId" => "ASC"
        ]
    ]);

    $hesapla=$fonk->Hesapla($sepet[$i]["urunId"],$sepet[$i]["varyantId"],$uyeIndirimOrani);
    $toplamTutar+=($hesapla["birimFiyat"]+($hesapla["birimFiyat"]/100*$urun["urunKdv"]))*$sepet[$i]["adet"];
    $araTutar+=($hesapla["birimFiyat"])*$sepet[$i]["adet"];
    $kdvTutar+=($hesapla["birimFiyat"]/100*$urun["urunKdv"])*$sepet[$i]["adet"];
}

$_SESSION['Sepet']=json_encode($sepet);
$json["result"]=array("toplamTutar"=>$toplamTutar,"araTutar"=>$araTutar,"kdvTutar"=>$kdvTutar);
print_r(json_encode($json));
?>
