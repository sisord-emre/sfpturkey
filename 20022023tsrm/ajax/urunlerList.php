<?php
include "../Panel/System/Config.php";
$seo = $_GET['seo'];

$kategoriArray = array();
$kategori = $db->get("Kategoriler", [
	"[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
], "*", [
	"kategoriDilBilgiDilId" => $_SESSION["dilId"],
	"kategoriDilBilgiSlug" => $seo,
	"ORDER" => [
		"kategoriSirasi" => "ASC"
	]
]);
array_push($kategoriArray, $kategori["kategoriId"]);

$altKategoriler = $db->select("Kategoriler", [
	"[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
], "*", [
	"kategoriDilBilgiDilId" => $_SESSION["dilId"],
	"kategoriUstMenuId" => $kategori["kategoriId"],
	"ORDER" => [
		"kategoriSirasi" => "ASC"
	]
]);
foreach ($altKategoriler as $key => $value) {
	array_push($kategoriArray, $value["kategoriId"]);
}

// echo "<pre>";
// print_r($kategoriArray);
// echo"</pre>"; 

///----- Saylafama Sorgu
$sartlar = [];
//toplam veri
$sutunlar=[
	"urunId",
	"urunVaryantDilBilgiUrunId",
	"urunVaryantId",
	"urunBaseUrl",
	"urunGorsel",
	"urunVaryantKodu",
	"urunVaryantDilBilgiAdi",
	"urunVaryantDilBilgiSlug",
	"urunVaryantFiyat",
	"urunVaryantDilBilgiDilId",
	"urunVaryantVaryantId",
	"urunVaryantDefaultSecim",
	"urunVaryantDilBilgiDurum",
	"urunDurum",
];


$sartlar = array_merge($sartlar, [
	'GROUP' => $sutunlar,
	"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
	"urunKategoriKategoriId" => $kategoriArray,
	"urunVaryantDefaultSecim" => 1, //default seçili olanlar listelenecek
	"urunVaryantDilBilgiDurum" => 1,
	"urunDurum" => 1,
	"ORDER" => [
		"urunId" => "ASC"
	]
]);
$totalRecord = $db->select("Urunler", [
	"[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
	"[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
	"[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
	"[>]UrunVaryantDilBilgiler" => ["Urunler.urunId" => "urunVaryantDilBilgiUrunId"],
], $sutunlar, $sartlar);

$totalRecord = count($totalRecord);

$pageLimit = 8;
// sayfa parametresi? Örn: index.php?page=2 [page = $pageParam]
$pageParam = 'page';
// limit için start ve limit değerleri hesaplanıyor
$pagination = $fonk->paginationNormal($totalRecord, $pageLimit, $pageParam);

$sartlar = array_merge($sartlar, [
	'GROUP' => $sutunlar,
	"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
	"urunKategoriKategoriId" => $kategoriArray,
	"urunVaryantDefaultSecim" => 1, //default seçili olanlar listelenecek
	"urunVaryantDilBilgiDurum" => 1,
	"urunDurum" => 1,
	"ORDER" => [
		"urunId" => "ASC"
	],
	'LIMIT' => [$pagination['start'], $pagination['limit']]
]);
//normal sorgumuz
$urunler = $db->select("Urunler", [
	"[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
	"[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
	"[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
	"[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
], $sutunlar, $sartlar);
///----- Saylafama Sorgu

// echo "<pre>"; 
// print_r($urunler);
// echo "</pre>";

if ($_SESSION['uyeKodu'] != "") 
{
    $uyeVar = 1;
    $uye = $db->get("Uyeler", "*", [
        "uyeKodu" => $_SESSION['uyeKodu']
    ]);
}

foreach ($urunler as $value) {
	if ($value["urunGorsel"] == "") {
		$value["urunGorsel"] = "img-not-found.png";
	}
?>
	<script type="text/javascript">
		document.getElementById("toplamKayit").innerHTML = "<?= $totalRecord ?>";
	</script>
	<div class="col-lg-3 col-md-3 col-6 pr_animated done mt__30 pr_grid_item product nt_pr desgin__1">
		<div class="product-inner pr">
			<div class="product-image pr oh lazyload">
				<a class="d-block" href="product.php">
					<div class="pr_lazy_img main-img nt_img_ratio nt_bg_lz lazyload padding-top__127_571" data-bgset="<?= $value["urunBaseUrl"] . "" . $value["urunGorsel"]; ?>"></div>
				</a>
				<div class="hover_img pa pe_none t__0 l__0 r__0 b__0 op__0">
					<div class="pr_lazy_img back-img pa nt_bg_lz lazyload padding-top__127_571" data-bgset="<?= $value["urunBaseUrl"] . "" . $value["urunGorsel"]; ?>"></div>
				</div>
			</div>
			<input type="hidden" class="input-text qty text tc qty_pr_js qty_cart_js" id="adet_<?= $value["urunVaryantId"] ?>" name="quantity" value="1">
			<div class="product-info mt__15">
				<h3 class="product-title pr fs__14 mg__0 fwm">
					<a class="cd chp" href="product/<?= $value["urunVaryantKodu"] . "-" . $value["urunVaryantDilBilgiSlug"]; ?>">
						<?= $value["urunVaryantDilBilgiAdi"]; ?>
					</a>
				</h3>
				<?php if($uyeVar == 1){ ?>
				<span class="price dib mb__5">
					<?php $hesapla=$fonk->Hesapla($value["urunVaryantId"],"",$uye['uyeIndirimOrani']);?>
                    <?= $_SESSION["paraBirimSembol"] ?><?=$hesapla["birimFiyat"];?>
				</span>
				<?php } ?> 

				<button type="submit" onclick="SepeteEkle(<?= $value['urunVaryantId']; ?>);" id="sepetButton_<?= $value["urunVaryantId"]; ?>" data-time="6000" data-ani="shake" class="single_add_to_cart_button button truncate w__100 mt__10 order-4 d-inline-block animated">
					<span class="txt_add"><?= $fonk->getDil("Sepete Ekle"); ?></span>
				</button>

			</div>
		</div>
	</div>
<?php } ?>