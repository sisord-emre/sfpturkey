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
								<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("baslik")?></b></td>
								<td><?=$detay['musteriIletisimAdi']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("baslik")?></b></td>
								<td><?=$detay['musteriIletisimAdi']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("baslik")?></b></td>
								<td><?=$detay['musteriIletisimAdi']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Görsel")?></b></td>
								<td><a href="../Images/Haberler/<?=$detay['haberListGorsel']?>" target="_blank"><img src="../Images/Haberler/<?=$detay['haberListGorsel']?>" width="150"></a></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Durumu")?></b></td>
								<td><?php if($detay['ikonKod']==1){?><div class="badge badge-success"><?=$fonk->getPDil("Aktif")?></div><?php } else{ ?><div class="badge badge-danger"><?=$fonk->getPDil("Pasif")?></div><?php } ?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Kayıt Tarihi")?></b></td>
								<td><?=$fonk->sqlToDateTime($detay['musteriKayitTarihi']);?></td>
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
