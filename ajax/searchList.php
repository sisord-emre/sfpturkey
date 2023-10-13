<?php
include "../Panel/System/Config.php";

$ara=$_GET['ara'];

///----- Saylafama Sorgu
$sutunlar=[
	"@urunId",
	"urunModel",
	"urunVaryantId",
	"urunBaseUrl",
	"urunGorsel",
	"urunVaryantDilBilgiSlug",
	"urunVaryantFiyat",
	"urunVaryantDilBilgiDilId",
	"urunVaryantVaryantId",
	"urunVaryantDefaultSecim",
	"urunVaryantDilBilgiDurum",
	"urunDurum",
	"paraBirimKodu",
	"paraBirimSembol",
	"urunVaryantDilBilgiAdi",
	"urunVaryantKodu",
	"urunVaryantDilBilgiEtiketler",
	"varyantDilBilgiBaslik"
];
$sartlar=[];
//toplam veri
$sartlar=array_merge($sartlar,[
	"AND" => [
		"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
		"urunVaryantDilBilgiDurum" => 1,
		//"urunVaryantDefaultSecim" => 1, //default seçili olanlar listelenecek
		"OR" => [
			"urunVaryantDilBilgiAdi[~]" => $ara,
			"urunModel[~]" => $ara,
			"urunVaryantDilBilgiEtiketler[~]" => $ara,
			"varyantDilBilgiBaslik" => $ara
		]
	],
	"ORDER" => [
		"urunVaryantDilBilgiAdi" => "ASC"
	]
]);
$totalRecord = $db->select("Urunler", [
	"[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
	"[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
	"[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
	"[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
	"[>]VaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantVaryantId" => "varyantDilBilgiVaryatId"],
],$sutunlar,$sartlar);

$totalRecord = count($totalRecord);

$pageLimit = 8;
// sayfa parametresi? Örn: index.php?page=2 [page = $pageParam]
$pageParam = 'page';
// limit için start ve limit değerleri hesaplanıyor
$pagination = $fonk->paginationNormal($totalRecord, $pageLimit, $pageParam);

$sutunlar=[
	"@urunId",
	"urunModel",
	"urunVaryantId",
	"urunBaseUrl",
	"urunGorsel",
	"urunVaryantDilBilgiSlug",
	"urunVaryantFiyat",
	"urunVaryantDilBilgiDilId",
	"urunVaryantVaryantId",
	"urunVaryantDefaultSecim",
	"urunVaryantDilBilgiDurum",
	"urunDurum",
	"paraBirimKodu",
	"paraBirimSembol",
	"urunVaryantDilBilgiAdi",
	"urunVaryantKodu",
	"urunVaryantDilBilgiEtiketler",
	"varyantDilBilgiBaslik"
];

$sartlar=array_merge($sartlar,[
	"AND" => [
		"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
		"urunVaryantDilBilgiDurum" => 1,
		//"urunVaryantDefaultSecim" => 1, //default seçili olanlar listelenecek
		"OR" => [
			"urunVaryantDilBilgiAdi[~]" => $ara,
			"urunModel[~]" => $ara,
			"urunVaryantDilBilgiEtiketler[~]" => $ara,
			"varyantDilBilgiBaslik" => $ara
		]
	],
	"ORDER" => [
		"urunVaryantDilBilgiAdi" => "ASC"
	],
	'LIMIT' => [$pagination['start'], $pagination['limit']]
]);
//normal sorgumuz
$urunler = $db->select("Urunler", [
	"[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
	"[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
	"[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
	"[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
	"[>]VaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantVaryantId" => "varyantDilBilgiVaryatId"],
],$sutunlar,$sartlar);
///----- Saylafama Sorgu

if ($_SESSION['uyeSessionKey'] != "") 
{
    $uyeVar = 1;
    $uye = $db->get("Uyeler", "*", [
        "uyeSessionKey" => $_SESSION['uyeSessionKey']
    ]);
}

foreach ($urunler as $value) {
	if ($value["urunGorsel"] == "") {
		$value["urunGorsel"] = "img-not-found.jpg";
		$value["urunBaseUrl"] = "Images/";
	}

	$favoriDurum = $db->get("UrunFavoriler",[
		"[<]UrunVaryantlari" => ["UrunFavoriler.urunFavoriUrunVaryantId" => "urunVaryantId"],
		"[<]Uyeler" => ["UrunFavoriler.urunFavoriUyeId" => "uyeId"]
	],"*",[
		"uyeId" => $uye["uyeId"],
		"urunFavoriUrunVaryantId" => $value["urunVaryantId"],
		"ORDER" => [
			"urunFavoriId" => "DESC",
		]
	]);
?>
	<script type="text/javascript">
		document.getElementById("toplamKayit").innerHTML = "<?= $totalRecord ?>";
	</script>
	<div class="col-lg-3 col-md-3 col-6 pr_animated done mt__30 pr_grid_item product nt_pr desgin__1">
		<div class="product-inner pr">
			<div class="product-image pr oh lazyload">
				<a class="d-block" href="product/<?= $value["urunVaryantKodu"] . "-" . $value["urunVaryantDilBilgiSlug"]; ?>">
					<div class="pr_lazy_img main-img nt_img_ratio nt_bg_lz lazyload padding-top__70_571" data-bgset="<?= $value["urunBaseUrl"] . "" . $value["urunGorsel"]; ?>"></div>
				</a>
				<div class="hover_img pa pe_none t__0 l__0 r__0 b__0 op__0">
					<div class="pr_lazy_img back-img pa nt_bg_lz lazyload padding-top__70_571" data-bgset="<?= $value["urunBaseUrl"] . "" . $value["urunGorsel"]; ?>"></div>
				</div>
				<div class="nt_add_w ts__03 pa <?=($favoriDurum) ? 'favori_added' : ''; ?>">
					<a onclick="FavoriEkle(<?= $value['urunVaryantId']; ?>,<?= $uye['uyeId']; ?>);" id="favoriButton_<?= $value["urunVaryantId"]; ?>" class="wishlistadd cb chp ttip_nt tooltip_right">
						<span class="tt_txt"><?= $fonk->getDil("Favori Ekle"); ?></span>
						<i class="facl facl-heart-o"></i>
					</a>
				</div>
			</div>
			<input type="hidden" class="input-text qty text tc qty_pr_js qty_cart_js" id="adet_<?= $value["urunVaryantId"] ?>" name="quantity" value="1">
			<div class="product-info mt__15">
				<h3 class="product-title pr fs__14 mg__0 fwm">
					<a class="cd chp kisalt" href="product/<?= $value["urunVaryantKodu"] . "-" . $value["urunVaryantDilBilgiSlug"]; ?>">
						<?= $value["urunVaryantDilBilgiAdi"]; ?>
					</a>
				</h3>
				
				<?php if($uyeVar == 1){ ?>
				<span class="price dib mb__5 w-100">
					<?php if($uye['uyeIndirimOrani'] > 0 ): ?>
						<div class="button-liste w-100">
							<?= $fonk->getDil("Liste Özel Fiyat"); ?>:
							<del style="color:white;"> 
								<?php $hesapla=$fonk->Hesapla($value["urunVaryantId"],"");?>
								<?= $value["paraBirimSembol"] ?><?=number_format($hesapla["birimFiyat"],2,',','.');?>
							</del>
						</div>
						<br>
						<div class="button-bayi mt-3 w-100">
							<?= $fonk->getDil("Bayi Özel Fiyat"); ?>: 
							<ins style="color:white;"> 
								<?php $hesapla2=$fonk->Hesapla($value["urunVaryantId"],"",$uye['uyeIndirimOrani']);?>
								<?= $value["paraBirimSembol"] ?><?=number_format($hesapla2["birimFiyat"],2,',','.');?>
							</ins>
						</div>
					<?php else: ?>
						<?php $hesapla=$fonk->Hesapla($value["urunVaryantId"],"");?>
						<ins> <?= $value["paraBirimSembol"] ?><?=number_format($hesapla["birimFiyat"],2,',','.');?></ins>
					<?php endif; ?>
				</span>
				<?php } ?> 

				<button type="submit" onclick="SepeteEkle(<?= $value['urunVaryantId']; ?>);" id="sepetButton_<?= $value["urunVaryantId"]; ?>" data-time="6000" data-ani="shake" class="single_add_to_cart_button button truncate w__100 mt__10 order-4 d-inline-block animated mt-3">
					<span class="txt_add"><?= $fonk->getDil("Sepete Ekle"); ?></span>
				</button>

			</div>
		</div>
	</div>
<?php } ?>