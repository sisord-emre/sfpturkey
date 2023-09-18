<?php
include("../Panel/System/Config.php");

extract($_POST);
$isFavoriControl = $db->get("Urunler",[
    "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
    "[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
    "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
    "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
    "[>]UrunFavoriler" => ["UrunVaryantlari.urunVaryantId" => "urunFavoriUrunVaryantId"],
    "[>]Uyeler" => ["UrunFavoriler.urunFavoriUyeId" => "uyeId"],
], "*", [
    "urunVaryantId" => $urunId,
    "uyeId" => $uyeId
]);


if($isFavoriControl)
{
    $silFavoriUrun = $db->delete("UrunFavoriler", [
        "urunFavoriUyeId" => $uyeId,
        "urunFavoriUrunVaryantId" => $urunId
    ]);

    echo "2";
}
else 
{
    $parametreler = array(
        'urunFavoriUyeId' => $uyeId,
        'urunFavoriUrunVaryantId' => $urunId
    );
    $query = $db->insert("UrunFavoriler", $parametreler);

    echo "1";
}
?>
