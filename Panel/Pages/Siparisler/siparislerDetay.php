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

$siparisTeslimatUyeAdres = $db->get($tableName,[
	"[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
	"[>]UyeAdresler" => ["Siparisler.siparisTeslimatUyeAdresId" => "uyeAdresId"],
	"[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
	"[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
	"[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
],"*",[
	$tabloPrimarySutun => $detayId
]);

$siparisFaturaUyeAdres = $db->get($tableName,[
	"[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
	"[>]UyeAdresler" => ["Siparisler.siparisFaturaUyeAdresId" => "uyeAdresId"],
	"[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
	"[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
	"[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
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
<th>'.$fonk->getPDil("Birim Fiyat").'</th>
<th>'.$fonk->getPDil("Ara Toplam").'</th>
<th style="width: 200px;">'.$fonk->getPDil("Teslim Durumu").'</th>
</tr>
</thead>
<tbody>';

$toplamTutar=0;
$araTutar = 0;
$kdvTutar = 0;
foreach($siparisIcerikleri as $siparisIcerik){
	$toplamTutar+=$siparisIcerik['siparisIcerikAdet']*$siparisIcerik['siparisIcerikFiyat'];
	$araTutar += $siparisIcerik['siparisIcerikAdet'] * $siparisIcerik['siparisIcerikKdvsizFiyat'];
	$kdvTutar += $siparisIcerik['siparisIcerikAdet'] * $siparisIcerik['siparisIcerikKdv'];
	$beklemede="";
	$teslimEdildi="";
	$iadeEdildi="";
	if ($siparisIcerik["siparisIcerikTeslimatDurumu"]==1) {
		$beklemede=" selected";
	}else if ($siparisIcerik["siparisIcerikTeslimatDurumu"]==2) {
		$teslimEdildi=" selected";
	}else if ($siparisIcerik["siparisIcerikTeslimatDurumu"]==3) {
		$iadeEdildi=" selected";
	}
	$buttonDurum="enabled";
	if ($silmeYetki==false) {
		$buttonDurum="disabled";
	}

	$icerikTable.='
	<tr>
	<td>'.$siparisIcerik['siparisIcerikUrunAdi'].'</td>
	<td>'.$siparisIcerik['siparisIcerikUrunVaryantDilBilgiAdi'].'</td>
	<td>'.$siparisIcerik['siparisIcerikAdet'].'</td>
	<td>'.$detay["paraBirimSembol"].number_format($siparisIcerik['siparisIcerikFiyat'],2,',','.').'</td>
	<td>'.$detay["paraBirimSembol"].number_format(($siparisIcerik['siparisIcerikAdet']*$siparisIcerik['siparisIcerikFiyat']),2,',','.').'</td>
	<td>
	<select class="form-control" '.$buttonDurum.' id="teslimDurumu-'.$siparisIcerik["siparisIcerikId"].'" onchange="TeslimDurumu('.$siparisIcerik["siparisIcerikId"].')">
	<option value="1" '.$beklemede.'>'.$fonk->getPDil("Bekliyor").'</option>
	<option value="2" '.$teslimEdildi.'>'.$fonk->getPDil("Teslim Edildi").'</option>
	<option value="3" '.$iadeEdildi.'>'.$fonk->getPDil("İade Edildi").'</option>
	</select>
	</td>
	</tr>';
}
$icerikTable.='
<tr>
<th colspan="4" style="text-align:right;">'.$fonk->getPDil("Toplam Tutar").':</th>
<th colspan="2">'.$detay["paraBirimSembol"].number_format($toplamTutar,2,',','.').'</th>
</tr>';

if($detay['siparisIndirimKodu']!="" && $detay['siparisIndirimYuzdesi']!=0){
	$indirimMiktar=$toplamTutar/100*$detay['siparisIndirimYuzdesi'];
	$toplamTutar-=$indirimMiktar;
	$icerikTable.='<tr>
	<th colspan="4" style="text-align:right;">'.$fonk->getPDil("İndirim").'('.$detay['siparisIndirimKodu'].')'.':</th>
	<th colspan="2">'.$detay["paraBirimSembol"].number_format($indirimMiktar,2,',','.').'</th>
	</tr>';
}
if($detay['siparisKargoUcreti'] !=0){
	$toplamTutar+=$detay['siparisKargoUcreti'];
	$icerikTable.='<tr>
	<th colspan="4" style="text-align:right;">'.$fonk->getPDil("Kargo Ücreti").':</th>
	<th colspan="2">'.$detay["paraBirimSembol"].number_format($detay['siparisKargoUcreti'],2,',','.').'</th>
	</tr>';
}
if($detay['siparisOdenenIskontoUcreti'] > 0){
	$toplamTutar-=$detay['siparisOdenenIskontoUcreti'];
	$icerikTable.='<tr>
	<th colspan="4" style="text-align:right;">'.$fonk->getPDil("Proje İskonto Ücreti").':</th>
	<th colspan="2">'.$detay["paraBirimSembol"].number_format($detay['siparisOdenenIskontoUcreti'],2,',','.').'</th>
	</tr>';
}
$icerikTable.='
<tr>
<th colspan="4" style="text-align:right;">'.$fonk->getPDil("Ödenen Tutar").':</th>
<th colspan="2">'.$detay["paraBirimSembol"].number_format($detay['siparisToplam'],2,',','.').'</th>
</tr>
<tbody>
</table>';
?>
<div class="modal fade text-left" id="fadeIn" role="dialog" aria-hidden="true">
	<!-- detay modalı -->
	<div class="modal-dialog modal-xl" role="document">
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
								<td style="width: 15%;vertical-align: middle;"><b><?=$fonk->getPDil("Sipariş Kodu")?></b></td>
								<td><?=$detay['siparisKodu']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Üye Kodu")?></b></td>
								<td><?=$detay['uyeKodu']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Üye Tc/Vergi No")?></b></td>
								<td><?=$detay['uyeTcVergiNo']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Uye Firma Adi")?></b></td>
								<td><?=$detay['uyeFirmaAdi']?></td>
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
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Üye Tel")?></b></td>
								<td><?=$detay['uyeTel']?></td>
							</tr>
							
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Teslimat Tarihi")?></b></td>
								<td><b><?=$fonk->sqlToDate($detay['siparisTeslimatTarihi'])?></b></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Teslimat Adresi")?></b></td>
								<td>
									<select class="select2 form-control block" onchange="SiparisTeslimatAdresiDurumDegistir(<?=$detay['siparisId']?>);" name="siparisTeslimatUyeAdresId" id="siparisTeslimatUyeAdresId" style="width:100%" required>
										<option value=""><?=$fonk->getPDil("Seçiniz")?></option>
										<?php
										$sorguList = $db->select("UyeAdresler",[
											"[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
											"[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
											"[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
										],"*",[
											"uyeAdresUyeId" => $detay["siparisUyeId"],
											"ORDER" => [
												"uyeAdresId" => "ASC",
											]
										]);
										foreach($sorguList as $sorgu){
											?>
											<option value="<?=$sorgu['uyeAdresId']?>" <?=($sorgu['uyeAdresId'] == $siparisTeslimatUyeAdres['siparisTeslimatUyeAdresId']) ? 'selected' : '' ?>><?=$sorgu['uyeAdresBilgi']?> - <?=$sorgu['ilceAdi']?> - <?=$sorgu['ilAdi']?> - <?=$sorgu['ulkeAdi']?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Fatura Adresi")?></b></td>
								<td>
									<select class="select2 form-control block" onchange="SiparisFaturaAdresiDurumDegistir(<?=$detay['siparisId']?>);" name="siparisFaturaUyeAdresId" id="siparisFaturaUyeAdresId" style="width:100%" required>
										<option value=""><?=$fonk->getPDil("Seçiniz")?></option>
										<?php
										$sorguList2 = $db->select("UyeAdresler",[
											"[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
											"[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
											"[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
										],"*",[
											"uyeAdresUyeId" => $detay["siparisUyeId"],
											"ORDER" => [
												"uyeAdresId" => "ASC",
											]
										]);
										foreach($sorguList2 as $sorgu){
										?>
											<option value="<?=$sorgu['uyeAdresId']?>" <?=($sorgu['uyeAdresId'] == $siparisFaturaUyeAdres['siparisFaturaUyeAdresId']) ? 'selected' : '' ?>><?=$sorgu['uyeAdresBilgi']?> - <?=$sorgu['ilceAdi']?> - <?=$sorgu['ilAdi']?> - <?=$sorgu['ulkeAdi']?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Sipariş Not")?></b></td>
								<td><?=$detay['siparisNot']?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Ödeme Tipi")?></b></td>
								<td><?=$detay['odemeTipAdi']?></td>
							</tr>
							<?php if($detay['siparisIndirimKodu']!=""){ ?>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("İndirim Kodu")?></b></td>
									<td><?=$detay['siparisIndirimKodu']?></td>
								</tr>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("İndirim Yüzdesi")?></b></td>
									<td><?=$detay['siparisIndirimYuzdesi']?></td>
								</tr>
							<?php } ?>
							<?php if($detay['siparisKargoUcreti']!=0){ ?>
								<tr>
									<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Kargo Ücreti")?></b></td>
									<td><?=$detay['siparisKargoUcreti']?></td>
								</tr>
							<?php } ?>
							<?php if($detay['siparisFatura']) { ?>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Fatura Bilgisi")?></b></td>
								<td><a href="<?=$detay['siparisFaturaBaseUrl']?><?=$detay['siparisFatura']?>" target="_blank"><?=$fonk->getPDil("Fatura Görüntüle")?></a></td>
							</tr>
							<?php } ?>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Kayıt Tarihi")?></b></td>
								<td><?=$fonk->sqlToDateTime($detay['siparisKayitTarihi']);?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("Durumular")?></b></td>
								<td><?=$siparisDurumTable?></td>
							</tr>
							<tr>
								<td style="vertical-align: middle;"><b><?=$fonk->getPDil("İçerik")?></b></td>
								<td><?=$icerikTable?></td>
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
<script type="text/javascript">
function TeslimDurumu(siparisIcerikId){
	<?php if ($duzenlemeYetki==true){ ?>
		var e = document.getElementById("teslimDurumu-"+siparisIcerikId);
		var durumId = e.value;
		var data=new FormData();
		data.append("siparisIcerikId",siparisIcerikId);
		data.append("durumId",durumId);
		$.ajax({
			type: "POST",
			url: "Pages/Siparisler/siparisIcerikTeslimDurum.php",
			data:data,
			contentType:false,
			processData:false,
			success: function(res){
				if (res==1){
					toastr.success(getDil("Başarılı"));
				}else{
					alert(res);
				}
			},
			error: function (jqXHR, status, errorThrown) {
				alert("Result: "+status+" Status: "+jqXHR.status);
			}
		});
		<?php } ?>
	}

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
						}else{
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
		</script>
