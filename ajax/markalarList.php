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
	"urunStok",
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
	if($_GET['urun1Metre'] != ""  && $_GET['urun1Metre'] != 0){
		$sartlar=array_merge($sartlar,["urun1Metre" => ($_GET['urun1Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun2Metre'] != ""  && $_GET['urun2Metre'] != 0){
		$sartlar=array_merge($sartlar,["urun2Metre" => ($_GET['urun2Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun3Metre'] != ""  && $_GET['urun3Metre'] != 0){
		$sartlar=array_merge($sartlar,["urun3Metre" => ($_GET['urun3Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun510Metre'] != ""  && $_GET['urun510Metre'] != 0){
		$sartlar=array_merge($sartlar,["urun510Metre" => ($_GET['urun510Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun1020Metre'] != ""  && $_GET['urun1020Metre'] != 0){
		$sartlar=array_merge($sartlar,["urun1020Metre" => ($_GET['urun1020Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun2030Metre'] != ""  && $_GET['urun2030Metre'] != 0){
		$sartlar=array_merge($sartlar,["urun2030Metre" => ($_GET['urun2030Metre']=='1') ? '1' : '0' ]) ;
	}
}
$sartlar=array_merge($sartlar,[
	"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
	"urunVaryantVaryantId" => $marka["varyantId"],
	"urunVaryantDilBilgiDurum" => 1,
	"urunDurum" => 1,
	"ORDER" => [
		"urunId" => "DESC"
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
	"urunStok",
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
	if($_GET['urun1Metre'] != ""  && $_GET['urun1Metre'] != 0){
		$urunsartlar=array_merge($urunsartlar,["urun1Metre" => ($_GET['urun1Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun2Metre'] != ""  && $_GET['urun2Metre'] != 0){
		$urunsartlar=array_merge($urunsartlar,["urun2Metre" => ($_GET['urun2Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun3Metre'] != ""  && $_GET['urun3Metre'] != 0){
		$urunsartlar=array_merge($urunsartlar,["urun3Metre" => ($_GET['urun3Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun510Metre'] != ""  && $_GET['urun510Metre'] != 0){
		$urunsartlar=array_merge($urunsartlar,["urun510Metre" => ($_GET['urun510Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun1020Metre'] != ""  && $_GET['urun1020Metre'] != 0){
		$urunsartlar=array_merge($urunsartlar,["urun1020Metre" => ($_GET['urun1020Metre']=='1') ? '1' : '0' ]) ;
	}
	if($_GET['urun2030Metre'] != ""  && $_GET['urun2030Metre'] != 0){
		$urunsartlar=array_merge($urunsartlar,["urun2030Metre" => ($_GET['urun2030Metre']=='1') ? '1' : '0' ]) ;
	}
}
$urunsartlar=array_merge($urunsartlar,[
	"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
	"urunVaryantVaryantId" => $marka["varyantId"],
	"urunVaryantDilBilgiDurum" => 1,
	"urunDurum" => 1,
	"ORDER" => [
		"urunId" => "DESC"
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
// print_r($urunler);
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
					<a class="cd chp kisalt" href="product/<?= $value["urunVaryantKodu"] . "-" . $value["urunVaryantDilBilgiSlug"]; ?>">
						<?= $value["urunVaryantDilBilgiAdi"]; ?>
					</a>
				</h3>
				
				<?php if($uyeVar == 1){ ?>
					<span class="price dib mb__5 w-100 text-center">
						<?php if($uye['uyeIndirimOrani'] > 0 ): ?>
							<div class="button-liste w-100">
								<?= $fonk->getDil("Bayi Fiyat"); ?>
								<del style="color:white;"> 
									<?php $hesapla=$fonk->Hesapla($value["urunVaryantId"],"");?>
									<?= $value["paraBirimSembol"] ?><?=number_format($hesapla["birimFiyat"],2,',','.');?>
								</del>
							</div>
							<br>
							<div class="button-bayi mt-3 w-100">
								<?= $fonk->getDil("Liste Fiyat"); ?>
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