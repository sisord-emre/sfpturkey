<?php
include("../../System/Config.php");

$tabloPrimarySutun = $_POST['tabloPrimarySutun']; //primarykey

$baslik = "Fatura Ekleme Sayfası";

$tableName = $_POST['tableName']; //tabloadı istenirse burdan değiştirilebilir

$detayId = $_POST['detayId'];

$silmeYetki = $_POST['silmeYetki'];
$duzenlemeYetki = $_POST['duzenlemeYetki'];

//sayfayı görüntülenme logları
$fonk->logKayit(6, $_SERVER['REQUEST_URI'] . "?primaryId=" . $detayId); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$detay = $db->get($tableName, [
	"[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
	"[>]UyeAdresler" => ["Siparisler.siparisTeslimatUyeAdresId" => "uyeAdresId"],
	"[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
	"[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
	"[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
	"[>]OdemeTipleri" => ["Siparisler.siparisOdemeTipiId" => "odemeTipId"],
	"[>]Diller" => ["Siparisler.siparisDilId" => "dilId"],
	"[>]ParaBirimleri" => ["Siparisler.siparisParaBirimId" => "paraBirimId"]
], "*", [
	$tabloPrimarySutun => $detayId
]);

?>
<div class="modal fade text-left" id="fadeIn" role="dialog" aria-hidden="true">
	<!-- detay modalı -->
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="baslikModal"><?= $fonk->getPDil($baslik) ?> <?= $fonk->getPDil("Detay") ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="icerikModal">

				<!-- Güncellenecek Kısımlar -->
				<form id="formpost" class="form" action="" method="post">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="siparisFatura"><?= $fonk->getPDil("sadece .pdf formatında") ?></label>
								<div class="custom-file">
									<input type="file" class="custom-file-input" name="siparisFatura" id="siparisFatura" accept=".pdf">
									<label class="custom-file-label" name="siparisFatura" id="siparisFatura" for="siparisFatura" aria-describedby="siparisFatura"><?= $fonk->getPDil("Dosya Seçiniz") ?></label>
								</div>
							</div>
						</div>
					</div>
			
					<div class="form-group" style="text-align: center;margin-top:15px">
						<?php if ($duzenlemeYetki == true) { ?>
							<input type="hidden" name="siparisId" value="<?= $detay["siparisId"] ?>" />
							<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
							<button type="submit" class="btn btn-success"><i class="la la-floppy-o"></i> <?= $fonk->getPDil("Kayıt"); ?></button>
						<?php } ?>
					</div>
				</form>
				<!-- /Güncellenecek Kısımlar -->

			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal"><?= $fonk->getPDil("Kapat") ?></button>
			</div>
		</div>
	</div>
</div>
<script>
	$('#formpost').submit(function(e) {
		e.preventDefault(); //submit postu kesyoruz
		var data = new FormData(this);
		var formId = this.id;
		submitButKontrol(formId, 0);
		$.ajax({
			type: "POST",
			url: "Pages/Siparisler/siparislerFaturaEkleKayit.php",
			data: data,
			contentType: false,
			processData: false,
			success: function(res) {
				if (res == 1) {
					$("#fadeIn").modal("hide");
					toastr.success(getDil("Başarılı"));
				} else {
					alert(res);
				}
				submitButKontrol(formId, 1);
			},
			error: function(jqXHR, status, errorThrown) {
				alert("Result: " + status + " Status: " + jqXHR.status);
			}
		});
	});
</script>