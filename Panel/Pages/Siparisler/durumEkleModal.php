<?php
include ("../../System/Config.php");

$tabloPrimarySutun=$_POST['tabloPrimarySutun'];//primarykey

$baslik=$_POST['baslik'];

$tableName=$_POST['tableName'];//tabloadı istenirse burdan değiştirilebilir

$detayId=$_POST['detayId'];

$silmeYetki=$_POST['silmeYetki'];
$duzenlemeYetki=$_POST['duzenlemeYetki'];

//sayfayı görüntülenme logları
$fonk->logKayit(6,$_SERVER['REQUEST_URI']."?primaryId=".$detayId);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$detay = $db->get($tableName,[
	"[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
	"[>]UyeAdresler" => ["Siparisler.siparisTeslimatUyeAdresId" => "uyeAdresId"],
	"[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
	"[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
	"[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
	"[>]OdemeTipleri" => ["Siparisler.siparisOdemeTipiId" => "odemeTipId"],
	"[>]Diller" => ["Siparisler.siparisDilId" => "dilId"],
	"[>]ParaBirimleri" => ["Siparisler.siparisParaBirimId" => "paraBirimId"]
],"*",[
	$tabloPrimarySutun => $detayId
]);

$siparisIcerikleri = $db->select("SiparisIcerikleri",[
	"[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
	"[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
],"*",[
	"urunDilBilgiDilId" => $detay["siparisDilId"],
	"urunDurum" => 1,
	"urunDilBilgiDurum" => 1,
	"siparisIcerikSiparisId" => $detay["siparisId"]
]);

$siparisDurumlar = $db->select("SiparisSiparisDurumlari",[
	"[<]SiparisDurumlari" => ["SiparisSiparisDurumlari.siparisSiparisDurumSiparisDurumId" => "siparisDurumId"],
	"[<]SiparisDurumDilBilgiler" => ["SiparisDurumlari.siparisDurumId" => "siparisDurumDilBilgiSiparisDurumId"],
	"[>]KargoFirmalari" => ["SiparisSiparisDurumlari.siparisSiparisDurumKargoFirmaId" => "kargoFirmaId"]
],"*",[
	"siparisSiparisDurumSiparisId" => $detay["siparisId"],
	"siparisDurumDilBilgiDilId" => $detay["siparisDilId"],
	"ORDER" => [
		"siparisSiparisDurumId" => "ASC",
	]
]);
$siparisDurumTable='<table class="table table-striped table-bordered dataex-html5-export">
<thead>
<tr>
<th>'.$fonk->getPDil("Durum").'</th>
<th>'.$fonk->getPDil("Tarihi").'</th>
<th>'.$fonk->getPDil("Kargo Firma").'</th>
<th>'.$fonk->getPDil("Kargo Takip Kodu").'</th>
<th>#</th>
</tr>
</thead>
<tbody>';
foreach($siparisDurumlar as $siparisDurum){
	$buttonDurum="enabled";
	if($siparisDurum["siparisSiparisDurumSiparisDurumId"]==1){//ödeme bekliyor ise atla
		$buttonDurum="disabled";
	}
	if ($silmeYetki==false) {
		$buttonDurum="disabled";
	}
	$siparisDurumTable.='
	<tr id="durumTr-'.$siparisDurum["siparisSiparisDurumId"].'">
	<td>'.$siparisDurum['siparisDurumDilBilgiBaslik'].'</td>
	<td>'.$fonk->sqlToDateTime($siparisDurum['siparisSiparisDurumKayitTarihi']).'</td>
	<td>'.$siparisDurum['kargoFirmaAdi'].'</td>
	<td>'.$siparisDurum['siparisSiparisDurumKargoTakipKodu'].'</td>
	<td>
	<button type="button" onclick="DurumSil('.$siparisDurum["siparisSiparisDurumId"].');" '.$buttonDurum.' class="btn btn-danger btn-sm"><i class="la la-trash-o"></i></button>
	</td>
	</tr>';
}
$siparisDurumTable.='
<tbody>
</table>';

$icerikTable='<table class="table table-striped table-bordered dataex-html5-export">
<thead>
<tr>
<th>'.$fonk->getPDil("Ürün").'</th>
<th>'.$fonk->getPDil("Varyant").'</th>
<th>'.$fonk->getPDil("Adet").'</th>
</tr>
</thead>
<tbody>';
foreach($siparisIcerikleri as $siparisIcerik){
	$icerikTable.='
	<tr>
	<td>'.$siparisIcerik['siparisIcerikUrunAdi'].'</td>
	<td>'.$siparisIcerik['siparisIcerikUrunVaryantDilBilgiAdi'].'</td>
	<td>'.$siparisIcerik['siparisIcerikAdet'].'</td>
	</tr>';
}
$icerikTable.='
<tbody>
</table>';
?>
<div class="modal fade text-left" id="fadeIn" role="dialog" aria-hidden="true">
	<!-- detay modalı -->
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="baslikModal"><?=$fonk->getPDil($baslik)?> <?=$fonk->getPDil("Durum Ekle")?></h4>
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
								<td style="width: 15%;vertical-align: middle;"><b><?=$fonk->getPDil("Kodu")?></b></td>
								<td><?=$detay['siparisKodu']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Üye")?></b></td>
								<td><?=$detay['uyeAdi']." ".$detay['uyeSoyadi']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Üye Mail")?></b></td>
								<td><?=$detay['uyeMail']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Durumular")?></b></td>
								<td><?=$siparisDurumTable?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("İçerik")?></b></td>
								<td><?=$icerikTable?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Kayıt Tarihi")?></b></td>
								<td><?=$fonk->sqlToDateTime($detay['siparisKayitTarihi']);?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Teslimat Tarihi")?></b></td>
								<td><b><?=$fonk->sqlToDate($detay['siparisTeslimatTarihi'])?></b></td>
							</tr>
						</tbody>
					</table>

					<form id="formpost" class="form" action="" method="post">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th colspan="2" style="text-align:center"><?=$fonk->getPDil("Yeni Durum Ekle")?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="width: 20%;vertical-align: middle;"><b><?=$fonk->getPDil("Sipariş Durumları")?></b></td>
									<td>
										<select class="select2 form-control block" onchange="SiparisDurumDegistir();" name="siparisSiparisDurumSiparisDurumId" id="siparisSiparisDurumSiparisDurumId" style="width:100%" required>
											<option value=""><?=$fonk->getPDil("Seçiniz")?></option>
											<?php
											$sorguList = $db->select("SiparisDurumlari",[
												"[<]SiparisDurumDilBilgiler" => ["SiparisDurumlari.siparisDurumId" => "siparisDurumDilBilgiSiparisDurumId"]
											],"*",[
												"siparisDurumDilBilgiDilId" => $detay["siparisDilId"],
												"siparisDurumDurum" => 1,
												"ORDER" => [
													"siparisDurumSirasi" => "ASC",
												]
											]);
											foreach($sorguList as $sorgu){
												?>
												<option value="<?=$sorgu['siparisDurumId']?>"><?=$sorgu['siparisDurumDilBilgiBaslik']?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								<tr>
									<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Mail Gönderim")?></b></td>
									<td>
										<input type="checkbox" class="form-control border-primary" id="mailGonderim" name="mailGonderim" value="1" autocomplete="off" style="width: 25px;" checked>
									</td>
								</tr>
								<tr id="kargoTr" style="display: none">
									<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Kargo Firmaları")?></b></td>
									<td>
										<select class="select2 form-control block" name="siparisSiparisDurumKargoFirmaId" id="siparisSiparisDurumKargoFirmaId" style="width:100%">
											<option value=""><?=$fonk->getPDil("Seçiniz")?></option>
											<?php
											$sorguList = $db->select("KargoFirmalari","*",[
												"kargoFirmaDurum" => 1
											]);
											foreach($sorguList as $sorgu){
												?>
												<option value="<?=$sorgu['kargoFirmaId']?>"><?=$sorgu['kargoFirmaAdi']?></option>
											<?php } ?>
										</select>
									</td>
								</tr>
								<tr id="takipKodTr" style="display: none">
									<td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Kargo Takip Kodu")?></b></td>
									<td>
										<input type="text" class="form-control border-primary" id="siparisSiparisDurumKargoTakipKodu" name="siparisSiparisDurumKargoTakipKodu" value="<?=$Listeleme['siparisSiparisDurumKargoTakipKodu']?>" autocomplete="off">
									</td>
								</tr>
								
								<tr>
									<td colspan="2" style="text-align:center">
										<?php if ($duzenlemeYetki==true) { ?>
											<input type="hidden" name="siparisSiparisDurumSiparisId" id="siparisSiparisDurumSiparisId" value="<?=$detay["siparisId"]?>" />
											<input type="hidden" name="token" value="<?=$_SESSION['token']?>" />
											<button type="submit" class="btn btn-success"><i class="la la-floppy-o"></i> <?=$fonk->getPDil("Kayıt");?></button>
										<?php } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
				<!-- /Güncellenecek Kısımlar -->

			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal"><?=$fonk->getPDil("Kapat")?></button>
			</div>
		</div>
	</div>
</div>
<script src="Assets/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="Assets/app-assets/js/scripts/forms/select/form-select2.js"></script>
<script type="text/javascript">
$('#formpost').submit(function (e) {
	<?php if ($duzenlemeYetki==true){ ?>
		e.preventDefault(); //submit postu kesyoruz
		var data=new FormData(this);
		var formId=this.id;
		submitButKontrol(formId,0);
		$.ajax({
			type: "POST",
			url: "Pages/Siparisler/durumEkleKayit.php",
			data:data,
			contentType:false,
			processData:false,
			success: function(res){
				if (res==1){
					$("#fadeIn").modal("hide");
					toastr.success(getDil("Başarılı"));
					var siparisid = document.getElementById("siparisSiparisDurumSiparisId").value;
					// akinSoftStokDusurme(siparisid);
				}
				else if(res==2){
					alert("Bu siparişin durumu daha önce kargoya verildi olarak güncellenmiştir.");
				}
				else{
					alert(res);
				}
				submitButKontrol(formId,1);
			},
			error: function (jqXHR, status, errorThrown) {
				alert("Result: "+status+" Status: "+jqXHR.status);
			}
		});
		<?php } ?>
	});

	function DurumSil(Id){
		<?php if ($silmeYetki==true){ ?>
			if(confirm('<?=$fonk->getPDil("Silmek İstediğinize Emin misiniz ?")?>')) {
				var data=new FormData();
				data.append("Id",Id);
				$.ajax({
					type: "POST",
					url: "Pages/Siparisler/durumEkleSil.php",
					data:data,
					contentType:false,
					processData:false,
					success: function(res){
						if (res==1){
							document.getElementById("durumTr-"+Id).style.display="none";
						}
						else{
							alert(res);
						}
					},
					error: function (jqXHR, status, errorThrown) {
						alert("Result: "+status+" Status: "+jqXHR.status);
					}
				});
			}
			<?php } ?>
		}

		function SiparisDurumDegistir(){
			var e = document.getElementById("siparisSiparisDurumSiparisDurumId");
			var Id = e.value;
			if (Id==5) {//kargolandı
				document.getElementById("kargoTr").style.display="table-row";
				document.getElementById("takipKodTr").style.display="table-row";
				document.getElementById("takipLinkTr").style.display="table-row";
			}else{
				document.getElementById("kargoTr").style.display="none";
				document.getElementById("takipKodTr").style.display="none";
				document.getElementById("takipLinkTr").style.display="none";
			}
		}
	</script>
