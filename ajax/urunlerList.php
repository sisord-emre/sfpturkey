<?php
include "../Panel/System/Config.php";
$seo = intval(explode('-', $_GET['seo'])[0]);
$kategoriArray = array();
$kategori = $db->get("Kategoriler", [
	"[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
], "*", [
	"kategoriDilBilgiDilId" => $_SESSION["dilId"],
	"kategoriKodu" => $seo,
	'kategoriDurum' => 1, //aktif
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


$sutunlar = [
	"@urunId",
	"urunVaryantId",
	"urunBaseUrl",
	"urunGorsel",
	"urunStok",
	"urunVaryantDilBilgiSlug",
	"urunVaryantFiyat",
	"urunVaryantDilBilgiDilId",
	"urunVaryantVaryantId",
	"urunVaryantDefaultSecim",
	"urunVaryantDilBilgiDurum",
	"urunDurum",
	"paraBirimKodu",
	"paraBirimSembol",
	//"urunVaryantKodu" => $db->raw('(SELECT "urunVaryantKodu" FROM "UrunVaryantlari" WHERE "UrunVaryantlari"."urunVaryantUrunId" = "Urunler"."urunId" LIMIT 1)'),
	//"urunVaryantDilBilgiAdi" => $db->raw('(SELECT "urunVaryantDilBilgiAdi" FROM "UrunVaryantDilBilgiler" WHERE "UrunVaryantDilBilgiler"."urunVaryantDilBilgiUrunId" = "Urunler"."urunId" LIMIT 1)'),
];

$sartlar=[];
$orSartlar = [];
$andSartlar = [];
if ($_GET["filtre"]=="1") 
{
	if($_GET['urunSFPPort'] != "" && $_GET['urunSFPPort'] != 0){
		$orSartlar=array_merge($orSartlar,["urunSFPPort" => ($_GET['urunSFPPort']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun1GSFPPort'] != ""  && $_GET['urun1GSFPPort'] != 0){
		$orSartlar=array_merge($orSartlar,["urun1GSFPPort" => ($_GET['urun1GSFPPort']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunSFPPortBirlikte'] != ""  && $_GET['urunSFPPortBirlikte'] != 0){
		$orSartlar=array_merge($orSartlar,["urunSFPPortBirlikte" => ($_GET['urunSFPPortBirlikte']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunSFP28Port'] != ""  && $_GET['urunSFP28Port'] != 0){
		$orSartlar=array_merge($orSartlar,["urunSFP28Port" => ($_GET['urunSFP28Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunQSFPPort'] != ""  && $_GET['urunQSFPPort'] != 0){
		$orSartlar=array_merge($orSartlar,["urunQSFPPort" => ($_GET['urunQSFPPort']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunQSFP28Port'] != ""  && $_GET['urunQSFP28Port'] != 0){
		$orSartlar=array_merge($orSartlar,["urunQSFP28Port" => ($_GET['urunQSFP28Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunEndustriyelTip'] != ""  && $_GET['urunEndustriyelTip'] != 0){
		$orSartlar=array_merge($orSartlar,["urunEndustriyelTip" => ($_GET['urunEndustriyelTip']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun100MegabitRJ45Port'] != ""  && $_GET['urun100MegabitRJ45Port'] != 0){
		$orSartlar=array_merge($orSartlar,["urun100MegabitRJ45Port" => ($_GET['urun100MegabitRJ45Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun1GigabitRJ45Port'] != ""  && $_GET['urun1GigabitRJ45Port'] != 0){
		$orSartlar=array_merge($orSartlar,["urun1GigabitRJ45Port" => ($_GET['urun1GigabitRJ45Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun10GigabitRJ45Port'] != ""  && $_GET['urun10GigabitRJ45Port'] != 0){
		$orSartlar=array_merge($orSartlar,["urun10GigabitRJ45Port" => ($_GET['urun10GigabitRJ45Port']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun1Metre'] != ""  && $_GET['urun1Metre'] != 0){
		$orSartlar=array_merge($orSartlar,["urun1Metre" => ($_GET['urun1Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun2Metre'] != ""  && $_GET['urun2Metre'] != 0){
		$orSartlar=array_merge($orSartlar,["urun2Metre" => ($_GET['urun2Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun3Metre'] != ""  && $_GET['urun3Metre'] != 0){
		$orSartlar=array_merge($orSartlar,["urun3Metre" => ($_GET['urun3Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun510Metre'] != ""  && $_GET['urun510Metre'] != 0){
		$orSartlar=array_merge($orSartlar,["urun510Metre" => ($_GET['urun510Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun1020Metre'] != ""  && $_GET['urun1020Metre'] != 0){
		$orSartlar=array_merge($orSartlar,["urun1020Metre" => ($_GET['urun1020Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun2030Metre'] != ""  && $_GET['urun2030Metre'] != 0){
		$orSartlar=array_merge($orSartlar,["urun2030Metre" => ($_GET['urun2030Metre']=='1') ? '1' : '0' ]) ;
	}
}

$andSartlar=array_merge($andSartlar,[
	"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
	"urunKategoriKategoriId" => $kategoriArray,
	"urunVaryantDefaultSecim" => 1, //default seçili olanlar listelenecek
	"urunVaryantDilBilgiDurum" => 1,
	"urunDurum" => 1
]);

if (count($orSartlar) > 0) {
	$sartlar = [
		"AND" => [
			"OR" => $orSartlar,
		]
	];
	$sartlar["AND"] = array_merge($sartlar["AND"], $andSartlar);
} else {
	$sartlar = $andSartlar;
}

$sartlar=array_merge($sartlar,[
	"ORDER" => [
		"urunId" => "DESC"
	]
]);

///----- Saylafama Sorgu
$totalRecord=$db->select("Urunler",[
	"[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
	"[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
	"[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
	"[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
	"[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
],$sutunlar,$sartlar);


$totalRecord = count($totalRecord);
$pageLimit = 24;
// sayfa parametresi? Örn: index.php?page=2 [page = $pageParam]
$pageParam = 'page';

// limit için start ve limit değerleri hesaplanıyor
$pagination = $fonk->paginationNormal($totalRecord, $pageLimit, $pageParam);

$urunSutunlar = [
	"@urunId",
	"urunVaryantId",
	"urunBaseUrl",
	"urunGorsel",
	"urunStok",
	"urunVaryantDilBilgiSlug",
	"urunVaryantFiyat",
	"urunVaryantDilBilgiDilId",
	"urunVaryantVaryantId",
	"urunVaryantDefaultSecim",
	"urunVaryantDilBilgiDurum",
	"urunDurum",
	"paraBirimKodu",
	"paraBirimSembol",
	//"urunVaryantKodu" => $db->raw('(SELECT "urunVaryantKodu" FROM "UrunVaryantlari" WHERE "UrunVaryantlari"."urunVaryantUrunId" = "Urunler"."urunId" LIMIT 1)'),
	//"urunVaryantDilBilgiAdi" => $db->raw('(SELECT "urunVaryantDilBilgiAdi" FROM "UrunVaryantDilBilgiler" WHERE "UrunVaryantDilBilgiler"."urunVaryantDilBilgiUrunId" = "Urunler"."urunId" LIMIT 1)'),

];

$urunSartlar=[];
$urunOrSartlar = [];
$urunAndSartlar = [];
if ($_GET["filtre"]=="1") 
{
	if($_GET['urunSFPPort'] != "" && $_GET['urunSFPPort'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urunSFPPort" => ($_GET['urunSFPPort']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun1GSFPPort'] != ""  && $_GET['urun1GSFPPort'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urun1GSFPPort" => ($_GET['urun1GSFPPort']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunSFPPortBirlikte'] != ""  && $_GET['urunSFPPortBirlikte'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urunSFPPortBirlikte" => ($_GET['urunSFPPortBirlikte']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunSFP28Port'] != ""  && $_GET['urunSFP28Port'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urunSFP28Port" => ($_GET['urunSFP28Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunQSFPPort'] != ""  && $_GET['urunQSFPPort'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urunQSFPPort" => ($_GET['urunQSFPPort']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunQSFP28Port'] != ""  && $_GET['urunQSFP28Port'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urunQSFP28Port" => ($_GET['urunQSFP28Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunEndustriyelTip'] != ""  && $_GET['urunEndustriyelTip'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urunEndustriyelTip" => ($_GET['urunEndustriyelTip']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun100MegabitRJ45Port'] != ""  && $_GET['urun100MegabitRJ45Port'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urun100MegabitRJ45Port" => ($_GET['urun100MegabitRJ45Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun1GigabitRJ45Port'] != ""  && $_GET['urun1GigabitRJ45Port'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urun1GigabitRJ45Port" => ($_GET['urun1GigabitRJ45Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun10GigabitRJ45Port'] != ""  && $_GET['urun10GigabitRJ45Port'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urun10GigabitRJ45Port" => ($_GET['urun10GigabitRJ45Port']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun1Metre'] != ""  && $_GET['urun1Metre'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urun1Metre" => ($_GET['urun1Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun2Metre'] != ""  && $_GET['urun2Metre'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urun2Metre" => ($_GET['urun2Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun3Metre'] != ""  && $_GET['urun3Metre'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urun3Metre" => ($_GET['urun3Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun510Metre'] != ""  && $_GET['urun510Metre'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urun510Metre" => ($_GET['urun510Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun1020Metre'] != ""  && $_GET['urun1020Metre'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urun1020Metre" => ($_GET['urun1020Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun2030Metre'] != ""  && $_GET['urun2030Metre'] != 0){
		$urunOrSartlar=array_merge($urunOrSartlar,["urun2030Metre" => ($_GET['urun2030Metre']=='1') ? '1' : '0' ]) ;
	}
}

$urunAndSartlar=array_merge($urunAndSartlar,[
	"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
	"urunKategoriKategoriId" => $kategoriArray,
	"urunVaryantDefaultSecim" => 1, //default seçili olanlar listelenecek
	"urunVaryantDilBilgiDurum" => 1,
	"urunDurum" => 1
]);


if (count($urunOrSartlar) > 0) {
	$urunSartlar = [
		"AND" => [
			"OR" => $urunOrSartlar,
		]
	];
	$urunSartlar["AND"] = array_merge($urunSartlar["AND"], $urunAndSartlar);
} else {
	$urunSartlar = $urunAndSartlar;
}

$urunSartlar=array_merge($urunSartlar,[
	"ORDER" => [
		"urunId" => "DESC"
	],
	'LIMIT' => [$pagination['start'], $pagination['limit']]
]);

$urunler=$db->select("Urunler",[
"[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
"[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
"[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
"[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
"[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
],$urunSutunlar,$urunSartlar);

	

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

	$urunBaslik = $db->get("UrunVaryantDilBilgiler",[
		"[<]UrunVaryantlari" => ["UrunVaryantDilBilgiler.urunVaryantDilBilgiVaryantId" => "urunVaryantId"]
	],"*",[
		"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
		"urunVaryantDilBilgiUrunId" => $value["urunId"],
		"ORDER" => [
			"urunVaryantDilBilgiId" => "ASC",
		]
	]);

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
				<a class="d-block" href="product/<?= $urunBaslik["urunVaryantKodu"] . "-" . $value["urunVaryantDilBilgiSlug"]; ?>">
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
					<a class="cd chp kisalt" href="product/<?= $urunBaslik["urunVaryantKodu"] . "-" . $value["urunVaryantDilBilgiSlug"]; ?>">
						<?= $urunBaslik["urunVaryantDilBilgiAdi"]; ?>
					</a>
				</h3>
				
				<?php if($uyeVar == 1){ ?>
					<span class="price dib mb__5 w-100">
						<?php if($uye['uyeIndirimOrani'] > 0 ): ?>
							<div class="button-liste w-100">
								<?= $fonk->getDil("Ürün Satış Fiyat"); ?>:
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
							<div class="button-liste w-100">
								<ins style="color:white;"> <?= $value["paraBirimSembol"] ?><?=number_format($hesapla["birimFiyat"],2,',','.');?></ins>
							</div>
						<?php endif; ?>
					</span>
				<?php } ?> 
				
				<?php if($value["urunStok"] > 0){ ?>
					<button type="submit" onclick="SepeteEkle(<?= $value['urunVaryantId']; ?>);" id="sepetButton_<?= $value["urunVaryantId"]; ?>" data-time="6000" data-ani="shake" class="single_add_to_cart_button button truncate w__100 mt__10 mt-3 order-4 d-inline-block animated">
						<span class="txt_add"><?= $fonk->getDil("Sepete Ekle"); ?></span>
					</button>
				<?php } else { ?>
					<button onClick="javascript:window.location.href = 'contact';" class="single_add_to_cart_button button truncate w__100 mt__10 mt-3 order-4 d-inline-block animated">
						<span class="txt_add"><?= $fonk->getDil("Talep Et"); ?></span>
					</button>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } ?>