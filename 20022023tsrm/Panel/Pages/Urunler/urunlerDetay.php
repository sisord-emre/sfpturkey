<?php
include ("../../System/Config.php");

$tabloPrimarySutun=$_POST['tabloPrimarySutun'];//primarykey

$baslik=$_POST['baslik'];

$tableName=$_POST['tableName'];//tabloadı istenirse burdan değiştirilebilir

$detayId=$_POST['detayId'];

//sayfayı görüntülenme logları
$fonk->logKayit(6,$_SERVER['REQUEST_URI']."?primaryId=".$detayId);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$sartlar=[$tabloPrimarySutun => $detayId];
if ($_SESSION["islemDilId"]!="") {
	$sartlar=array_merge($sartlar,["urunDilBilgiDilId" => $_SESSION["islemDilId"]]);
}
else {
	$sartlar=array_merge($sartlar,["urunDilBilgiDilId" => $sabitB["sabitBilgiPanelVarsayilanDilId"]]);
}
$detay = $db->get($tableName,[
	"[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
	"[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"]
], "*", $sartlar);

$kategoriler="";
$sartlar=["urunKategoriUrunId" => $detay[$tabloPrimarySutun]];
if ($_SESSION["islemDilId"]!="") {
	$sartlar=array_merge($sartlar,["kategoriDilBilgiDilId" => $_SESSION["islemDilId"]]);
}
else {
	$sartlar=array_merge($sartlar,["kategoriDilBilgiDilId" => $sabitB["sabitBilgiPanelVarsayilanDilId"]]);
}
$kategoriList = $db->select("UrunKategoriler",[
	"[>]Kategoriler" => ["UrunKategoriler.urunKategoriKategoriId" => "kategoriId"],
	"[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
],"*",$sartlar);
foreach ($kategoriList as $key => $value) {
	$kategoriler.="<p style='margin-bottom: 0rem;'>".$value["kategoriDilBilgiBaslik"]."</p>";
}
?>
<div class="modal fade text-left" id="fadeIn" role="dialog" aria-hidden="true">
	<!-- detay modalı -->
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="baslikModal"><?=$fonk->getPDil($baslik)?> <?=$fonk->getPDil("Detay")?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="icerikModal">

				<!-- Güncellenecek Kısımlar -->
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr>
								<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Gorsel")?></b></td>
								<td>
									<?php if ($detay['urunGorsel']!="") { ?>
										<a href="<?=$detay['urunBaseUrl'].$detay['urunGorsel']?>" target="_blank" style="margin-left: 1rem;"><img src="<?=$detay['urunBaseUrl'].$detay['urunGorsel']?>" style="height:60px;width: auto;"/></a>
									<?php	} ?>
								</td>
							</tr>
							<tr>
								<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Kodu")?></b></td>
								<td><?=$detay['urunKodu']?></td>
							</tr>
							<tr>
								<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Barkod")?></b></td>
								<td><?=$detay['urunBarkod']?></td>
							</tr>
							<tr>
								<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Adı")?></b></td>
								<td><?=$detay['urunDilBilgiAdi']?></td>
							</tr>
							<tr>
								<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Slug")?></b></td>
								<td><?=$detay['urunDilBilgiSlug']?></td>
							</tr>
							<tr>
								<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Link")?></b></td>
								<td><a href="<?=$sabitB["sabitBilgiSiteUrl"]."urun/".$detay['urunKodu']."-".$detay['urunDilBilgiSlug'];?>" target="_blank"><?=$sabitB["sabitBilgiSiteUrl"]."urun/".$detay['urunKodu']."-".$detay['urunDilBilgiSlug'];?></a></td>
							</tr>
							<tr>
								<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Description / Kısa Açıklama")?></b></td>
								<td><?=$detay['urunDilBilgiDescription']?></td>
							</tr>
							<tr>
								<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Kategori")?></b></td>
								<td><?=$kategoriler?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Stok")?></b></td>
								<td><div class="badge badge-glow badge-pill badge-info"><?=$detay['urunStok'];?></div></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Fiyat")?></b></td>
								<td><?=$detay['urunFiyat']." ".$detay['paraBirimSembol'];?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("İndirimli Fiyat")?></b></td>
								<td><?=$detay['urunIndirimliFiyat']." ".$detay['paraBirimSembol'];?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Beğeni/Sepet/Satış")?></b></td>
								<td><div class="badge badge-glow badge-pill badge-info"><?=$detay['urunBegeni']." / ".$detay['urunSepetMiktar']." / ".$detay['urunSatisMiktar'];?></div></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Varyantlar")?></b></td>
								<td>
									<?php
									$sartlar=["urunVaryantUrunId" => $detay[$tabloPrimarySutun]];
									if ($_SESSION["islemDilId"]!="") {
										$sartlar=array_merge($sartlar,["varyantDilBilgiDilId" => $_SESSION["islemDilId"]]);
									}
									else {
										$sartlar=array_merge($sartlar,["varyantDilBilgiDilId" => $sabitB["sabitBilgiPanelVarsayilanDilId"]]);
									}
									$urunVaryantlari = $db->select("UrunVaryantlari",[
										"[>]Varyantlar" => ["UrunVaryantlari.urunVaryantVaryantId" => "varyantId"],
										"[>]VaryantDilBilgiler" => ["Varyantlar.varyantId" => "varyantDilBilgiVaryatId"]
									],"*",$sartlar);
									foreach($urunVaryantlari as $item){
										echo $fonk->getPDil("Varyant Kodu").":".$item["varyanKodu"]." - ".$fonk->getPDil("Varyant").":".$item["urunVaryantFiyat"]." - ".$fonk->getPDil("Fiyat").":".$item["urunVaryantFiyat"]." - ".$fonk->getPDil("İndirimli Fiyat").":".$item["urunVaryantIndirimliFiyat"]."<br />";
										} ?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Detay Görselleri")?></b></td>
								<td>
									<?php
									$urunGorselleri = $db->select("UrunGorselleri","*",[
										"urunGorselUrunId" => $detay["urunId"],
										"ORDER" => [
											"urunGorselSirasi" => "ASC"
										]
									]);
									foreach($urunGorselleri as $item){
										?>
										<a href="<?=$detay['urunBaseUrl'].$item['urunGorselLink']?>" target="_blank" style="margin-left: 1rem;"><img class="card-img-top img-fluid" src="<?=$detay['urunBaseUrl'].$item['urunGorselLink']?>" style="height:60px;width: auto;"></a>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Durumu")?></b></td>
								<td><?php if($detay['urunDurum']==1){?><div class="badge badge-success"><?=$fonk->getPDil("Aktif")?></div><?php } else{ ?><div class="badge badge-danger"><?=$fonk->getPDil("Pasif")?></div><?php } ?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Kayıt Tarihi")?></b></td>
								<td><?=$fonk->sqlToDateTime($detay['urunKayitTarihi']);?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- /Güncellenecek Kısımlar -->

			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal"><?=$fonk->getPDil("Kapat")?></button>
			</div>
		</div>
	</div>
</div>
