<?php
include("../Panel/System/Config.php");
$seo=$_GET['ara'];

$urunler=$db->select("Urunler",[
	"[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
	"[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
	"[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
	"[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
	"[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
	],[
		"@urunId",
		"urunVaryantId",
		"urunBaseUrl",
		"urunGorsel",
		"urunVaryantKodu",
		"urunVaryantDilBilgiSlug",
		"urunVaryantFiyat",
		"urunVaryantDilBilgiDilId",
		"urunVaryantVaryantId",
		"urunVaryantDefaultSecim",
		"urunVaryantDilBilgiDurum",
		"urunDurum",
		"urunModel",
		"paraBirimSembol",
		"paraBirimKodu",
		"urunVaryantDilBilgiEtiketler",
		"urunVaryantKodu" => $db->raw('(SELECT "urunVaryantKodu" FROM "UrunVaryantlari" WHERE "UrunVaryantlari"."urunVaryantUrunId" = "Urunler"."urunId" LIMIT 1)'),
		"urunVaryantDilBilgiAdi" => $db->raw('(SELECT "urunVaryantDilBilgiAdi" FROM "UrunVaryantlari" LEFT JOIN "UrunVaryantDilBilgiler" ON "UrunVaryantlari"."urunVaryantId" = "UrunVaryantDilBilgiler"."urunVaryantDilBilgiVaryantId" WHERE "UrunVaryantlari"."urunVaryantUrunId" = "Urunler"."urunId" LIMIT 1)'),
	],[
		"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
		"urunVaryantDefaultSecim" => 1, //default seçili olanlar listelenecek
		"urunVaryantDilBilgiDurum" => 1,
		"OR" => [
			"urunVaryantDilBilgiAdi[~]" => $seo,
			"urunModel[~]" => $seo,
			"urunVaryantDilBilgiEtiketler[~]" => $seo,
		],
		"urunDurum" => 1,
		"ORDER" => [
			"urunId" => "ASC"
		]
	]);
// echo "<pre>"; 
// print_r($urunler);
// echo "</pre>";
foreach($urunler as $value){
	if($value["urunGorsel"]==""){
		$value["urunGorsel"] = "img-not-found.jpg";
		$value["urunBaseUrl"] = "Images/";
	}
?>
<div class="s-ajax"
	data-h="<?= $value["urunVaryantKodu"] . "-" . $value["urunVaryantDilBilgiSlug"]; ?>"
	data-pcode="<?=$value['urunModel']?>"
	data-t="<?=$value['urunVaryantDilBilgiAdi']?>"
	data-p="<?php $hesapla=$fonk->Hesapla($value["urunVaryantId"],"");?><?= $value["paraBirimSembol"] ?><?=$hesapla["birimFiyat"];?>"
	data-src="<?= $value["urunBaseUrl"] . "" . $value["urunGorsel"]; ?>"
	data-sku="<?=$value['urunVaryantDilBilgiEtiketler']?>">
</div>
<?php } ?>
