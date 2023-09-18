<?php
include ("../../System/Config.php");

$tabloPrimarySutun=$_POST['tabloPrimarySutun'];//primarykey

$baslik=$_POST['baslik'];

$tableName=$_POST['tableName'];//tabloadı istenirse burdan değiştirilebilir

$detayId=$_POST['detayId'];

//sayfayı görüntülenme logları
$fonk->logKayit(6,$_SERVER['REQUEST_URI']."?primaryId=".$detayId);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$detay = $db->get($tableName, "*", [
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
								<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Adı Soyadı")?></b></td>
								<td><?=$detay['iadeTalepAdi']?> <?=$detay['iadeTalepSoyadi']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Email")?></b></td>
								<td><?=$detay['iadeTalepEmail']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Telefon")?></b></td>
								<td><?=$detay['iadeTalepTelefon']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Sipariş Numarası")?></b></td>
								<td><?=$detay['iadeTalepSiparisNo']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Sipariş Tarihi")?></b></td>
								<td><?=$fonk->sqlToDateTime($detay['iadeTalepSiparisTarihi']);?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Ürün Kodu")?></b></td>
								<td><?=$detay['iadeTalepUrunKodu']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Ürün Adı")?></b></td>
								<td><?=$detay['iadeTalepUrunAdi']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Adet")?></b></td>
								<td><?=$detay['iadeTalepUrunAdet']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("İade Nedeni")?></b></td>
								<td>
									<?php 
									if($detay['iadeTalepIadeNeden']==1){?>
									<div><?=$fonk->getPDil("Diğer")?></div>
									<?php } else if($detay['iadeTalepIadeNeden']==2){ ?>
									<div><?=$fonk->getPDil("Kargoda hasar görmüş")?></div>
									<?php } else { ?>
									<div><?=$fonk->getPDil("Yanlış ürün gönderildi")?></div>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Ürün Açıldı mı?")?></b></td>
								<td>
									<?php 
									if($detay['iadeTalepUrunAcildimi']==1){?>
									<div class="badge badge-success"><?=$fonk->getPDil("Evet")?></div>
									<?php } else{ ?>
									<div class="badge badge-danger"><?=$fonk->getPDil("Hayır")?></div>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Arıza ya da diğer detaylar")?></b></td>
								<td><?=$detay['iadeTalepDetay']?></td>
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
