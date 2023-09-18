<?php
include "../Panel/System/Config.php";
$seo = $_GET['seo'];

$marka = $db->get("Varyantlar", [
	"[>]VaryantDilBilgiler" => ["Varyantlar.varyantId" => "varyantDilBilgiVaryatId"]
], "*", [
	"varyantDilBilgiDilId" => $_SESSION["dilId"],
	"varyantDilBilgiSlug" => $seo,
	"ORDER" => [
		"varyantId" => "ASC"
	]
]);

$sutunlar = [
	"@urunId",
	"urunVaryantId",
	"urunBaseUrl",
	"urunGorsel",
	"urunVaryantDilBilgiSlug",
	"urunVaryantFiyat",
	"urunVaryantDilBilgiDilId",
	"urunVaryantDilBilgiVaryantId",
	"urunVaryantDilBilgiDurum",
	"urunDurum",
	"paraBirimKodu",
	"paraBirimSembol",
    "urunVaryantVaryantId",
	"urunVaryantKodu",
	"urunVaryantDilBilgiAdi",
];

$sartlar=[];
if ($_GET["filtre"]=="1") 
{
	if($_GET['urunSFPPort'] == 1){
		$sartlar=array_merge($sartlar,["urunSFPPort" => ($_GET['urunSFPPort']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun1GSFPPort'] == 1){
		$sartlar=array_merge($sartlar,["urun1GSFPPort" => ($_GET['urun1GSFPPort']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunSFPPortBirlikte'] == 1){
		$sartlar=array_merge($sartlar,["urunSFPPortBirlikte" => ($_GET['urunSFPPortBirlikte']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunSFP28Port'] == 1){
		$sartlar=array_merge($sartlar,["urunSFP28Port" => ($_GET['urunSFP28Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunQSFPPort'] == 1){
		$sartlar=array_merge($sartlar,["urunQSFPPort" => ($_GET['urunQSFPPort']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunQSFP28Port'] == 1){
		$sartlar=array_merge($sartlar,["urunQSFP28Port" => ($_GET['urunQSFP28Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunEndustriyelTip'] == 1){
		$sartlar=array_merge($sartlar,["urunEndustriyelTip" => ($_GET['urunEndustriyelTip']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun100MegabitRJ45Port'] == 1){
		$sartlar=array_merge($sartlar,["urun100MegabitRJ45Port" => ($_GET['urun100MegabitRJ45Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun1GigabitRJ45Port'] == 1){
		$sartlar=array_merge($sartlar,["urun1GigabitRJ45Port" => ($_GET['urun1GigabitRJ45Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun10GigabitRJ45Port'] == 1){
		$sartlar=array_merge($sartlar,["urun10GigabitRJ45Port" => ($_GET['urun10GigabitRJ45Port']=='1') ? '1' : '0' ]) ;
	}
}
$sartlar=array_merge($sartlar,[
	"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
	"urunVaryantVaryantId" => $marka["varyantId"],
	"urunVaryantDilBilgiDurum" => 1,
	"urunDurum" => 1,
	"ORDER" => [
		"urunId" => "ASC"
	]
]);

///----- Saylafama Sorgu
$totalRecord=$db->select("Urunler",[
    "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
    "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
    "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
    "[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
],$sutunlar,$sartlar);

$totalRecord = count($totalRecord);
$pageLimit = 8;
// sayfa parametresi? Örn: index.php?page=2 [page = $pageParam]
$pageParam = 'page';
// limit için start ve limit değerleri hesaplanıyor
$pagination = $fonk->paginationNormal($totalRecord, $pageLimit, $pageParam);


$urunSutunlar = [
	"@urunId",
	"urunVaryantId",
	"urunBaseUrl",
	"urunGorsel",
	"urunVaryantDilBilgiSlug",
	"urunVaryantFiyat",
	"urunVaryantDilBilgiDilId",
	"urunVaryantDilBilgiVaryantId",
	"urunVaryantDilBilgiDurum",
	"urunDurum",
	"paraBirimKodu",
	"paraBirimSembol",
    "urunVaryantVaryantId",
	"urunVaryantKodu" ,
	"urunVaryantDilBilgiAdi",
];

$urunsartlar=[];
if ($_GET["filtre"]=="1") 
{
	if($_GET['urunSFPPort'] == 1){
		$urunsartlar=array_merge($urunsartlar,["urunSFPPort" => ($_GET['urunSFPPort']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun1GSFPPort'] == 1){
		$urunsartlar=array_merge($urunsartlar,["urun1GSFPPort" => ($_GET['urun1GSFPPort']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunSFPPortBirlikte'] == 1){
		$urunsartlar=array_merge($urunsartlar,["urunSFPPortBirlikte" => ($_GET['urunSFPPortBirlikte']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunSFP28Port'] == 1){
		$urunsartlar=array_merge($urunsartlar,["urunSFP28Port" => ($_GET['urunSFP28Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunQSFPPort'] == 1){
		$urunsartlar=array_merge($urunsartlar,["urunQSFPPort" => ($_GET['urunQSFPPort']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunQSFP28Port'] == 1){
		$urunsartlar=array_merge($urunsartlar,["urunQSFP28Port" => ($_GET['urunQSFP28Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urunEndustriyelTip'] == 1){
		$urunsartlar=array_merge($urunsartlar,["urunEndustriyelTip" => ($_GET['urunEndustriyelTip']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun100MegabitRJ45Port'] == 1){
		$urunsartlar=array_merge($urunsartlar,["urun100MegabitRJ45Port" => ($_GET['urun100MegabitRJ45Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun1GigabitRJ45Port'] == 1){
		$urunsartlar=array_merge($urunsartlar,["urun1GigabitRJ45Port" => ($_GET['urun1GigabitRJ45Port']=='1') ? '1' : '0' ]);
	}
	if($_GET['urun10GigabitRJ45Port'] == 1){
		$urunsartlar=array_merge($urunsartlar,["urun10GigabitRJ45Port" => ($_GET['urun10GigabitRJ45Port']=='1') ? '1' : '0' ]) ;
	}
}
$urunsartlar=array_merge($urunsartlar,[
	"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
	"urunVaryantVaryantId" => $marka["varyantId"],
	"urunVaryantDilBilgiDurum" => 1,
	"urunDurum" => 1,
	"ORDER" => [
		"urunId" => "ASC"
	],
	'LIMIT' => [$pagination['start'], $pagination['limit']]
]);

$urunler=$db->select("Urunler",[
    "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
    "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
    "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
    "[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
],$urunSutunlar,$urunsartlar);

// echo "<pre>";
// print_r($urunsartlar);
// echo "</pre>";

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
					<a class="cd chp" href="product/<?= $value["urunVaryantKodu"] . "-" . $value["urunVaryantDilBilgiSlug"]; ?>">
						<?= $value["urunVaryantDilBilgiAdi"]; ?>
					</a>
				</h3>
				
				<?php if($uyeVar == 1){ ?>
					<span class="price dib mb__5 w-100 text-center">
						<?php if($uye['uyeIndirimOrani'] > 0 ): ?>
							Bayi Özel Fiyat:
							<del> 
								<?php $hesapla=$fonk->Hesapla($urun["urunVaryantId"],"");?>
								<?= $value["paraBirimSembol"] ?><?=$hesapla["birimFiyat"];?>
							</del>
							<br>
                            Liste Özel Fiyat:
							<ins> 
								<?php $hesapla2=$fonk->Hesapla($urun["urunVaryantId"],"",$uye['uyeIndirimOrani']);?>
								<?= $value["paraBirimSembol"] ?><?=$hesapla2["birimFiyat"];?>
							</ins>
						<?php else: ?>
							<?php $hesapla=$fonk->Hesapla($urun["urunVaryantId"],"");?>
							<ins> <?= $value["paraBirimSembol"] ?><?=$hesapla["birimFiyat"];?></ins>
						<?php endif; ?>
					</span>
				<?php } ?> 

				<button type="submit" onclick="SepeteEkle(<?= $value['urunVaryantId']; ?>);" id="sepetButton_<?= $value["urunVaryantId"]; ?>" data-time="6000" data-ani="shake" class="single_add_to_cart_button button truncate w__100 mt__10 order-4 d-inline-block animated">
					<span class="txt_add"><?= $fonk->getDil("Sepete Ekle"); ?></span>
				</button>

			</div>
		</div>
	</div>
<?php } ?>