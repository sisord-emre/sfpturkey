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
        "[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
    ],"*",[
        "urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
        "urunVaryantDilBilgiVaryantId" => $sepet[$i]["urunId"],
        "urunVaryantDilBilgiDurum" => 1,
        "ORDER" => [
            "urunId" => "ASC"
        ]
    ]);
}

$_SESSION['Sepet']=json_encode($sepet);
$json["result"]=array(
    "araToplam"=>$fonk->paraCevir($birimFiyat*$adet,$urun["paraBirimKodu"],"TRY")
);
print_r(json_encode($json));
?>
