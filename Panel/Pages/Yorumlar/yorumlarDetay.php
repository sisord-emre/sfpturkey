<?php
include ("../../System/Config.php");

$tabloPrimarySutun=$_POST['tabloPrimarySutun'];//primarykey

$baslik=$_POST['baslik'];

$tableName=$_POST['tableName'];//tabloadı istenirse burdan değiştirilebilir

$detayId=$_POST['detayId'];

//sayfayı görüntülenme logları
$fonk->logKayit(6,$_SERVER['REQUEST_URI']."?primaryId=".$detayId);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$detay = $db->get($tableName,[
	"[>]Diller" => ["Yorumlar.yorumDilId" => "dilId"],
	//"[>]Urunler" => ["Yorumlar.yorumKaynakId" => "urunId"]
], "*", [
	$tabloPrimarySutun => $detayId
]);

$adSoyad=$detay["yorumAdSoyad"];
$email=$detay["yorumEmail"];
if($detay['yorumUyeId']=="0"){
  $adSoyad=$sabitB['sabitBilgiSiteAdi'];
}
else if ($detay["yorumUyeId"]!="") {
	$uye = $db->get("Uyeler", "*", [
		"uyeId" =>$detay["yorumUyeId"]
	]);
	$adSoyad=$uye["uyeAdi"]." ".$uye["uyeSoyadi"]." <span style='color:green'>*</span>";
	$email=$uye["uyeMail"];
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
								<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Kodu")?></b></td>
								<td><?=$detay['yorumKodu']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Adı Soyadı")?></b></td>
								<td><?=$adSoyad?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Kaynak")?></b></td>
								<td><?=$detay['urunKodu']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Üst Yorum Kodu")?></b></td>
								<td>
									<?php
									if($detay['yorumUstYorumId']!=""){
										$ustYorum = $db->get("Yorumlar", "*", [
											"yorumId" =>$detay["yorumUstYorumId"]
										]);
										echo $ustYorum["yorumKodu"];
									}
									?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Email")?></b></td>
								<td><?=$email?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Puan")?></b></td>
								<td><?=$detay['yorumPuan']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("İçerik")?></b></td>
								<td><?=$detay['yorumIcerik']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Dil")?></b></td>
								<td><?=$detay['dilAdi']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Durumu")?></b></td>
								<td><?php if($detay['yorumOnay']==1){?><div class="badge badge-success"><?=$fonk->getPDil("Görünür")?></div><?php } else{ ?><div class="badge badge-danger"><?=$fonk->getPDil("Gizli")?></div><?php } ?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Onay Tarihi")?></b></td>
								<td><?=$fonk->sqlToDateTime($detay['yorumOnayTarihi']);?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Kayıt Tarihi")?></b></td>
								<td><?=$fonk->sqlToDateTime($detay['yorumKayitTarihi']);?></td>
							</tr>
							<tr>
								<td colspan="2" style="text-align:center"><b><?=$fonk->getPDil("Cevap Yorumlar")?></b></td>
							</tr>
							<?php
							$cevapYorumlar = $db->select("Yorumlar",[
								"[>]Diller" => ["Yorumlar.yorumDilId" => "dilId"],
								//"[>]Urunler" => ["Yorumlar.yorumKaynakId" => "urunId"]
							],"*",[
								"yorumUstYorumId" => $detay['yorumId']
							]);
							foreach ($cevapYorumlar as $value) {
								$adSoyad=$value["yorumAdSoyad"];
								$email=$value["yorumEmail"];
								if ($value["yorumUyeId"]!="") {
									$uye = $db->get("Uyeler", "*", [
										"uyeId" =>$value["yorumUyeId"]
									]);
									$adSoyad=$uye["uyeAdi"]." ".$uye["uyeSoyadi"]." <span style='color:green'>*</span>";
									$email=$uye["uyeMail"];
								}
								?>
								<tr>
									<td colspan="2"><hr></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Kodu")?></b></td>
									<td><?=$value['yorumKodu']?></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Ad Soyad")?></b></td>
									<td><?=$adSoyad?></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Email")?></b></td>
									<td><?=$email?></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("İçerik")?></b></td>
									<td><?=$value['yorumIcerik']?></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Dil")?></b></td>
									<td><?=$value['dilAdi']?></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Durumu")?></b></td>
									<td><?php if($value['yorumOnay']==1){?><div class="badge badge-success"><?=$fonk->getPDil("Görünür")?></div><?php } else{ ?><div class="badge badge-danger"><?=$fonk->getPDil("Gizli")?></div><?php } ?></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Onay Tarihi")?></b></td>
									<td><?=$fonk->sqlToDateTime($value['yorumOnayTarihi']);?></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Kayıt Tarihi")?></b></td>
									<td><?=$fonk->sqlToDateTime($value['yorumKayitTarihi']);?></td>
								</tr>
							<?php } ?>
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
