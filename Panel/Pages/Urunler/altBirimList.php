<?php
include("../../System/Config.php");
$primaryId = $_POST['Id'];
$sartlar = [];
if ($_SESSION["islemDilId"] != "") {
	$sartlar = array_merge($sartlar, ["varyantDilBilgiDilId" => $_SESSION["islemDilId"]]);
} else {
	$sartlar = array_merge($sartlar, ["varyantDilBilgiDilId" => $sabitB["sabitBilgiPanelVarsayilanDilId"]]);
}
$sartlar = array_merge($sartlar, [
	"urunVaryantUrunId" => $primaryId
]);

// echo "<pre>"; 
// print_r($sartlar);
// echo "</pre>";
?>
<div class="card-body">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-content collapse show">
					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th><?= $fonk->getPDil("Marka Kodu") ?></th>
									<th><?= $fonk->getPDil("Marka Adı") ?></th>
									<th><?= $fonk->getPDil("Markaya Göre Satış Fiyat/Kampanya Satış Fiyatı") ?></th>
									<th><?= $fonk->getPDil("Liste Fiyat") ?></th>
									<th><?= $fonk->getPDil("Default mı?") ?></th>
									<th style="width:235px;"><?= $fonk->getPDil("İslem") ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								$list = $db->select("UrunVaryantlari", [
									"[>]VaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantVaryantId" => "varyantDilBilgiVaryatId"]
								], "*", $sartlar);
								$satir = 0;
								foreach ($list as $item) {
									$satir++;
								?>
									<tr id="satirAltBirim_<?= $item['urunVaryantId'] ?>">
										<th scope="row"><?= $satir ?></th>
										<td><?= $item['urunVaryantKodu'] ?></td>
										<td><?= $item['varyantDilBilgiBaslik'] ?></td>
										<td><?= str_replace('.', ',', $item['urunVaryantFiyat']) ?></td>
										<td><?= str_replace('.', ',', $item['urunVaryantKampanyasizFiyat']) ?></td>
										<td><?= $item['urunVaryantDefaultSecim'] ? "Evet" : "Hayır"; ?></td>
										<td>
											<button type="button" onclick="altBirimGuncelle('<?= $item['urunVaryantId'] ?>');" class="btn btn-warning btn-sm">
												<i class="la la-upload"></i>
												<?= $fonk->getPDil("Güncelle") ?>
											</button>
											<button type="button" onclick="altBirimSil('<?= $item['urunVaryantId'] ?>');" class="btn btn-danger btn-sm">
												<i class="la la-trash-o"></i>
												<?= $fonk->getPDil("Sil") ?>
											</button>
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>