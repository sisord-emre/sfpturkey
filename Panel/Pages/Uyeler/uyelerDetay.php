<?php
include ("../../System/Config.php");

$tabloPrimarySutun=$_POST['tabloPrimarySutun'];//primarykey

$baslik=$_POST['baslik'];

$tableName=$_POST['tableName'];//tabloadı istenirse burdan değiştirilebilir

$detayId=$_POST['detayId'];

//sayfayı görüntülenme logları
$fonk->logKayit(6,$_SERVER['REQUEST_URI']."?primaryId=".$detayId);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$detay = $db->get($tableName,[
	"[>]Diller" => ["Uyeler.uyeId" => "dilId"]
],"*",[
	$tabloPrimarySutun => $detayId
]);
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
								<td><?=$detay['uyeKodu']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Tc/Vergi No")?></b></td>
								<td><?=$detay['uyeTcVergiNo']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Ad Soyad")?></b></td>
								<td><?=$detay['uyeAdi']." ".$detay['uyeSoyadi']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Mail")?></b></td>
								<td><?=$detay['uyeMail']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Tel")?></b></td>
								<td><?=$detay['uyeTel']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Durumu")?></b></td>
								<td><?php if($detay['uyeDurum']==1){?><div class="badge badge-success"><?=$fonk->getPDil("Aktif")?></div><?php } else{ ?><div class="badge badge-danger"><?=$fonk->getPDil("Pasif")?></div><?php } ?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Son Giriş Tarihi")?></b></td>
								<td><?=$fonk->sqlToDateTime($detay['uyeSonGirisTarihi']);?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Kayıt Tarihi")?></b></td>
								<td><?=$fonk->sqlToDateTime($detay['uyeKayitTarihi']);?></td>
							</tr>
							<tr>
								<td colspan="2" style="text-align:center"><b><?=$fonk->getPDil("Üye Adresleri")?></b></td>
							</tr>
							<?php
							$adresler = $db->select("UyeAdresler",[
								"[>]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
								"[>]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
								"[>]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"]
							],"*",[
								"uyeAdresUyeId" => $detay['uyeId']
							]);
							foreach ($adresler as $value) {
								?>
								<tr>
									<td colspan="2"><hr></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Adres Adı")?></b></td>
									<td><?=$value['uyeAdresAdi']?></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Adres Ülke / İl / İlçe")?></b></td>
									<td><?=$value["ulkeAdi"]." / ".$value['ilAdi']." / ".$value['ilceAdi']?></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Adres Bilgi")?></b></td>
									<td><?=$value['uyeAdresBilgi']?></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Adres Kayıt Tarihi")?></b></td>
									<td><?=$fonk->sqlToDateTime($value['uyeAdresKayitTarihi']);?></td>
								</tr>
							<?php } ?>

							<tr>
								<td colspan="2" style="text-align:center"><b><?=$fonk->getPDil("Vergi Levhası")?></b></td>
							</tr>
							<?php
							$vergiLevhasi = $db->select("UyeVergiLevhasi","*",[
								"uyeVergiLevhasiUyeId" => $detay['uyeId']
							]);
							foreach ($vergiLevhasi as $value) {?>
							
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Vergi Levhası")?></b></td>
								<td>

									<a href="<?=$value['uyeVergiLevhasiBaseUrl']?><?=$value['uyeVergiLevhasiDosya']?>" class="btn btn-info btn-sm" style="padding:0.1rem 0.3rem;" target="_blank"><i class="la la-search"></i></a>
									
								</td>
							</tr>
							<?php } ?>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Ticaret Sicil Gazetesi")?></b></td>
								<td>

									<a href="<?=$detay['uyeTicaretSicilGazetesiBaseUrl']?><?=$detay['uyeTicaretSicilGazetesi']?>" class="btn btn-info btn-sm" style="padding:0.1rem 0.3rem;" target="_blank"><i class="la la-search"></i></a>
									
									
								</td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Mükerrer İmza")?></b></td>
								<td>

									<a href="<?=$detay['uyeMukerrerImzaBaseUrl']?><?=$detay['uyeMukerrerImza']?>" class="btn btn-info btn-sm" style="padding:0.1rem 0.3rem;" target="_blank"><i class="la la-search"></i></a>
									
								</td>
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
