<?php
include("../Panel/System/Config.php");

extract($_POST);

$uye = $db->get("Uyeler", "*", [
	"uyeSessionKey" => $_SESSION['uyeSessionKey']
]);
if (!$uye) {
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . '"; </script>';
	exit;
}
if ($uyeAdresId != "") {
	$uyeAdres = $db->get("UyeAdresler", [
		"[<]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
		"[<]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
		"[<]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"]
	], "*", [
		"uyeAdresUyeId" => $uye["uyeId"],
		"uyeAdresId" => $uyeAdresId
	]);
}
?>
<div class="modal fade text-left" id="fadeIn" role="dialog" aria-hidden="true">
	<!-- detay modalı -->
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="baslikModal">Adres Bilgileri</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="icerikModal">

				<form id="adresKayitForm" class="form" action="" method="post">
					<table class="checkout-review-order-table" style="margin: unset;">
						<tbody>
							<tr class="cart_item">
								<td colspan="3">
									<input type="text" class="form-control" placeholder="İsim" name="uyeAdresAdi" id="uyeAdresAdi" value="<?= $uyeAdres["uyeAdresAdi"] ?>" required>
								</td>
							</tr>
							<tr>
								<td>
									<select class="form-control" id="uyeAdresUlkeId" name="uyeAdresUlkeId" onchange="ulkeIl('uyeAdresUlkeId','uyeAdresIlId')" required>
										<option value="">Ülke Seç</option>
										<?php
										$sorguList = $db->select("Ulkeler", "*", [
											'ulkeDurum' => 1
										]);
										foreach ($sorguList as $item) {
										?>
											<option value="<?= $item['ulkeId'] ?>" <?php if ($item["ulkeId"] == $uyeAdres["uyeAdresUlkeId"]) {echo "selected";} ?>><?= $item['ulkeAdi'] ?></option>
										<?php } ?>
									</select>
								</td>
								<td>
									<select class="form-control" id="uyeAdresIlId" name="uyeAdresIlId" onchange="ilIlce('uyeAdresIlId','uyeAdresIlceId')" required>
										<option value="">Şehir seç</option>
										<?php
										$sorguList = $db->select("Iller", "*", [
											'ilUlkeId' => $uyeAdres["uyeAdresUlkeId"]
										]);
										foreach ($sorguList as $item) {
										?>
											<option value="<?= $item['ilId'] ?>" <?php if ($item["ilId"] == $uyeAdres["uyeAdresIlId"]) {echo "selected";} ?>><?= $item['ilAdi'] ?></option>
										<?php } ?>
									</select>
								</td>
								<td>
									<select class="form-control" id="uyeAdresIlceId" name="uyeAdresIlceId">
										<option value="">İlçe seç</option>
										<?php
										$sorguList = $db->select("Ilceler", "*", [
											'ilceIlId' => $uyeAdres["uyeAdresIlId"]
										]);
										foreach ($sorguList as $item) {
										?>
											<option value="<?= $item['ilceId'] ?>" <?php if ($item["ilceId"] == $uyeAdres["uyeAdresIlceId"]) {echo "selected";} ?>><?= $item['ilceAdi'] ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<textarea class="form-control" rows="5" placeholder="Açık Adres Bilgileri" name="uyeAdresBilgi" id="uyeAdresBilgi" required><?= $uyeAdres["uyeAdresBilgi"] ?></textarea>
								</td>
							</tr>
							<tr>
								<td colspan="3">
									<input type="hidden" name="uyeAdresId" id="uyeAdresId" value="<?= $uyeAdres["uyeAdresId"] ?>" />
									<input type="hidden" name="link" id="link" value="<?= $link ?>" />
									<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
									<button type="submit" class="btn_checkout button button_primary tu mt__10 mb__10 js_add_ld w__100">Kaydet</button>
								</td>
							</tr>
						</tbody>
					</table>
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Kapat</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#adresKayitForm').submit(function(e) {
		e.preventDefault(); //submit postu kesyoruz
		var data = new FormData(this);
		var formId = this.id;
		submitButKontrol(formId, 0);
		$.ajax({
			type: "POST",
			url: "ajax/adresKayit.php",
			data: data,
			contentType: false,
			processData: false,
			success: function(res) {
				if (res == 1) {
					swal(getDil('Başarılı'), getDil('Adres Eklendi'), "success");
					$("#fadeIn").modal("hide");
					var link = document.getElementById("link").value;
					if (link != "") {
						setTimeout(function() {
							window.location.href = link;
						}, 1500);
					}
					document.getElementById(formId).reset();
				} 
				else {
					swal(getDil('Error!'), res, "warning", {
						button: getDil('OK')
					});
				}
				submitButKontrol(formId, 1);
			},
			error: function(jqXHR, status, errorThrown) {
				alert("Result: " + status + " Status: " + jqXHR.status);
			}
		});
	});
</script>