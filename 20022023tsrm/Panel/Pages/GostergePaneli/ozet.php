<?php
include ("../../System/Config.php");

$menuId=$_POST['menuId'];//tabloadı istenirse burdan değiştirilebilir

///menu bilgileri alınıyor
$hangiMenu = $db->get("Menuler", "*", [
	"menuUstMenuId" => $menuId,
	"menuOzelGorunuruk" =>	1,
	"menuTipi" =>	2 //kayıt için 1 listeleme için 2 diğer sayfalar içim 3 yazılmalı****
]);

for($i=0;$i<Count($kullaniciYetkiler);$i++){//kullanıcının yetkilerini sorguluyoruz
	$kullaniciYetki= json_decode($kullaniciYetkiler[$i], true);

	if($kullaniciYetki['menuYetkiID']==$menuId){//menu id

		if($kullaniciYetki['listeleme']=="on")
		{$listelemeYetki=true;}//listeleme

		if($kullaniciYetki['ekleme']=="on")
		{$eklemeYetki=true;}//ekleme

		if($kullaniciYetki['silme']=="on")
		{$silmeYetki=true;}//silme

		if($kullaniciYetki['duzenleme']=="on")
		{$duzenlemeYetki=true;}//duzenleme

		if($kullaniciYetki['excel']=="on")
		{$tamExcelYetki=true;}//tam excel
	}
}
if(!$listelemeYetki)
{
	//yetki yoksa gözükecek yazi
	echo '<div class="alert alert-icon-right alert-warning alert-dismissible mb-2" role="alert">
	<span class="alert-icon"><i class="la la-warning"></i></span>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">×</span>
	</button>
	<strong>'.$fonk->getPDil("Yetki!").' </strong> '.$fonk->getPDil("Bu Menüye Erişim Yetkiniz Bulunmamaktadır.").'
	</div>';
	exit;
}
?>
<section>
	<div class="row">
		<div class="col-xl-2 col-md-4 col-12">
			<div class="card">
				<div class="card-content">
					<div class="card-body">
						<div class="media d-flex">
							<div class="media-body text-left">
								<h3 class="danger"><?=$db->count("Urunler");?></h3>
								<span><?=$fonk->getPDil("Ürünler")?></span>
							</div>
							<div class="align-self-center">
								<i class="la la-cubes danger font-large-2 float-right"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-md-4 col-12">
			<div class="card">
				<div class="card-content">
					<div class="card-body">
						<div class="media d-flex">
							<div class="media-body text-left">
								<h3 class="success"><?=$db->count("Kategoriler");?></h3>
								<span><?=$fonk->getPDil("Kategoriler")?></span>
							</div>
							<div class="align-self-center">
								<i class="la la-bed success font-large-2 float-right"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-md-4 col-12">
			<div class="card">
				<div class="card-content">
					<div class="card-body">
						<div class="media d-flex">
							<div class="media-body text-left">
								<h3 class="warning"><?=$db->count("Uyeler");?></h3>
								<span><?=$fonk->getPDil("Üyeler")?></span>
							</div>
							<div class="align-self-center">
								<i class="la la-smile-o warning font-large-2 float-right"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-xl-2 col-md-4 col-12">
			<div class="card">
				<div class="card-content">
					<div class="card-body">
						<div class="media d-flex">
							<div class="media-body text-left">
								<h3 class="info"><?=$db->count("SiparisSiparisDurumlari",["siparisSiparisDurumSiparisDurumId" => 2]);?></h3>
								<span><?=$fonk->getPDil("Siparişler")?></span>
							</div>
							<div class="align-self-center">
								<i class="la la-cart-plus info font-large-2 float-right"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-md-4 col-12">
			<div class="card">
				<div class="card-content">
					<div class="card-body">
						<div class="media d-flex">
							<div class="media-body text-left">
								<h3 class="primary"><?=$db->count("IletisimFormlari");?></h3>
								<span><?=$fonk->getPDil("İletişim Formları")?></span>
							</div>
							<div class="align-self-center">
								<i class="la la-building-o primary font-large-2 float-right"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-2 col-md-4 col-12">
			<div class="card">
				<div class="card-content">
					<div class="card-body">
						<div class="media d-flex">
							<div class="media-body text-left">
								<h3 style="color: midnightblue;"><?=$db->count("Brands");?></h3>
								<span><?=$fonk->getPDil("Referanslar")?></span>
							</div>
							<div class="align-self-center">
								<i class="la la-truck font-large-2 float-right" style="color: midnightblue;"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<section>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title" style="text-align: center;font-weight: 600;font-size: 20px;"><?=$fonk->getPDil("Sipariş İstatistikleri")?></h4>
					<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
					<div class="heading-elements">

					</div>
				</div>
				<div class="card-content collapse show">
					<div class="card-body">
						<form id="filtreSiparisPost" class="form" action="" method="post">
							<div class="row">
								<div class="col-md-3">
									<label for="userinput1" style="width: 100%;"><?=$fonk->getPDil("Tarih Aralığı")?></label>
									<div class="form-group" style="display: inline-flex;">
										<input type="date" style="width:auto" class="form-control border-primary" id="basTarihFiltre" name="basTarihFiltre" value="<?=date("Y-m-d", strtotime(date("Y-m-d")."-7 day"))?>" autocomplete="off">
										<input type="date" style="width:auto;margin-left: 1rem;" class="form-control border-primary" id="bitTarihFiltre" name="bitTarihFiltre" value="<?=date("Y-m-d")?>" autocomplete="off">
									</div>
								</div>
								<div class="col-md-3">
									<input type="hidden" name="filtrePost" value="1" />
									<button type="submit" class="btn btn-warning" title="Filtrele" style="margin-top: 2rem !important;"><i class="la la-filter"></i></button>
									<button type="reset" class="btn btn-info" title="Reset" onclick="javascript:SayfaYenile();" style="margin-top: 2rem !important;margin-left: 1rem;"><i class="la la-history"></i></button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="card-content collapse show">
					<div class="card-body" id="siparisGrafik">

					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<section>
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title" style="text-align: center;font-weight: 600;font-size: 20px;"><?=$fonk->getPDil("Son Siparişler")?></h4>
					<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
					<div class="heading-elements">

					</div>
				</div>
				<div class="card-content collapse show">
					<div class="card-body" >
						<?php
						$sartlar=[];
						$sartlar=array_merge($sartlar,[
							"ORDER" => [
								"siparisId" => "DESC"
							],
							"LIMIT" =>[0,10]
						]);
						$listeleme = $db->select("Siparisler",[
							"[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
							"[>]UyeAdresler" => ["Siparisler.siparisTeslimatUyeAdresId" => "uyeAdresId"],
							"[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
							"[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
							"[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
							"[>]OdemeTipleri" => ["Siparisler.siparisOdemeTipiId" => "odemeTipId"],
							"[>]Diller" => ["Siparisler.siparisDilId" => "dilId"],
							"[>]ParaBirimleri" => ["Siparisler.siparisParaBirimId" => "paraBirimId"]
						],"*",$sartlar);
						?>
						<table class="table table-striped table-bordered dataex-html5-export" id="listTable">
							<thead>
								<tr>
									<th><?=$fonk->getPDil("Kodu")?></th>
									<th><?=$fonk->getPDil("Uye")?></th>
									<th><?=$fonk->getPDil("Uye Mail")?></th>
									<th><?=$fonk->getPDil("İçerik")?></th>
									<th><?=$fonk->getPDil("Ödeme Tipi")?></th>
									<th><?=$fonk->getPDil("Ödenen Tutar")?></th>
									<th><?=$fonk->getPDil("Durumu")?></th>
									<th><?=$fonk->getPDil("Dil")?></th>
									<th><?=$fonk->getPDil("Kayıt Tarihi")?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($listeleme as $list){
									$satirRenk="";
									$siparisDurum = $db->get("SiparisSiparisDurumlari",[
										"[<]SiparisDurumlari" => ["SiparisSiparisDurumlari.siparisSiparisDurumSiparisDurumId" => "siparisDurumId"],
										"[<]SiparisDurumDilBilgiler" => ["SiparisDurumlari.siparisDurumId" => "siparisDurumDilBilgiSiparisDurumId"],
										"[>]KargoFirmalari" => ["SiparisSiparisDurumlari.siparisSiparisDurumKargoFirmaId" => "kargoFirmaId"]
									],"*",[
										"siparisSiparisDurumSiparisId" => $list["siparisId"],
										"siparisDurumDilBilgiDilId" => $list["siparisDilId"],
										"ORDER" => [
											"siparisSiparisDurumId" => "DESC",
										]
									]);
									if($siparisDurum["siparisSiparisDurumSiparisDurumId"]==1){//ödeme bekliyor ise atla
										continue;
									}
									if($siparisDurum["siparisSiparisDurumSiparisDurumId"]==4){//hazırlanıyor
										$satirRenk="durumBeklemede";
									}
									else if($siparisDurum["siparisSiparisDurumSiparisDurumId"]==6){//tamamlandı
										$satirRenk="durumBasarili";
									}
									else if($siparisDurum["siparisSiparisDurumSiparisDurumId"]==7){//iade edild
										$satirRenk="durumIptal";
									}
									$siparisIcerikleri = $db->select("SiparisIcerikleri",[
										"[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"]
									],"*",[
										"siparisIcerikSiparisId" => $list["siparisId"]
									]);

									$icerik="";
									$icerikSayac=0;
									$toplamTutar=0;
									foreach($siparisIcerikleri as $siparisIcerik){
										$icerikSayac++;
										if ($icerikSayac<=2) {
											$icerik.="<span style='display: flex;width: max-content;'>(".$siparisIcerik["urunKodu"].") ".$siparisIcerik["siparisIcerikUrunAdi"]."-".$siparisIcerik['siparisIcerikVaryantAdi']."</span>";
										}else{
											$icerik.="<span style='display: flex;width: max-content;'>...</span>";
										}
										$toplamTutar+=$siparisIcerik['siparisIcerikAdet']*$siparisIcerik['siparisIcerikFiyat'];
									}
									if($list['siparisIndirimKodu']!="" && $list['siparisIndirimYuzdesi']!=0){
										$toplamTutar-=($toplamTutar/100*$list['siparisIndirimYuzdesi']);
									}
									if($list['siparisKargoUcreti']!=0){
										$toplamTutar+=$list['siparisKargoUcreti'];
									}
									?>
									<tr class="<?=$satirRenk?>">
										<!-- Güncellenecek Kısımlar -->
										<th><?=$list['siparisKodu'];?></th>
										<td><?=$list['uyeAdi']." ".$list['uyeSoyadi'];?></td>
										<td><?=$list['uyeMail'];?></td>
										<td><?=$icerik;?></td>
										<td><?=$fonk->getPDil($list['odemeTipAdi']);?></td>
										<th><?=number_format($toplamTutar,2,",",".").$list["paraBirimSembol"];?></th>
										<td data-sort="<?=$siparisDurum['siparisDurumId'];?>"><?=$siparisDurum['siparisDurumDilBilgiBaslik'];?></td>
										<td><?=$list['dilAdi'];?></td>
										<td data-sort="<?=$fonk->sqlToDateTimeTiresiz($list['siparisKayitTarihi']);?>"><?=$fonk->sqlToDateTime($list['siparisKayitTarihi']);?></td>
										<!-- /Güncellenecek Kısımlar -->
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title" style="text-align: center;font-weight: 600;font-size: 20px;"><?=$fonk->getPDil("Son Üyeler")?></h4>
					<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
					<div class="heading-elements">

					</div>
				</div>
				<div class="card-content collapse show">
					<div class="card-body" >
						<?php
						$sartlar=[];
						$sartlar=array_merge($sartlar,[
							"ORDER" => [
								"uyeId" => "DESC"
							],
							"LIMIT" =>[0,10]
						]);
						$listeleme = $db->select("Uyeler",[
							"[>]Diller" => ["Uyeler.uyeId" => "dilId"]
						],"*",$sartlar);
						?>
						<table class="table table-striped table-bordered dataex-html5-export" id="listTable">
							<thead>
								<tr>
									<th><?=$fonk->getPDil("Kodu")?></th>
									<th><?=$fonk->getPDil("Adı")?></th>
									<th><?=$fonk->getPDil("Soyadı")?></th>
									<th><?=$fonk->getPDil("Mail")?></th>
									<th><?=$fonk->getPDil("Durumu")?></th>
									<th><?=$fonk->getPDil("Dil")?></th>
									<th><?=$fonk->getPDil("Kayıt Tarihi")?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($listeleme as $list){
									?>
									<tr>
										<!-- Güncellenecek Kısımlar -->
										<td><?=$list['uyeKodu'];?></td>
										<td><?=$list['uyeAdi'];?></td>
										<td><?=$list['uyeSoyadi'];?></td>
										<td><?=$list['uyeMail'];?></td>
										<td><?php if($list['uyeDurum']==1){?><div class="badge badge-success"><?=$fonk->getPDil("Aktif")?></div><?php } else{ ?><div class="badge badge-danger"><?=$fonk->getPDil("Pasif")?></div><?php } ?></td>
										<td><?=$list['dilAdi'];?></td>
										<td data-sort="<?=$fonk->sqlToDateTimeTiresiz($list['uyeKayitTarihi']);?>"><?=$fonk->sqlToDateTime($list['uyeKayitTarihi']);?></td>
										<!-- /Güncellenecek Kısımlar -->
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<script src="Assets/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="Assets/app-assets/js/scripts/forms/select/form-select2.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#filtreSiparisPost").submit();
});

$('#filtreSiparisPost').submit(function (e) {
	e.preventDefault(); //submit postu kesyoruz
	var data=new FormData(this);
	data.append("menuId","<?=$menuId?>");
	$('#siparisGrafik').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
	$.ajax({
		type: "POST",
		url: "Pages/GostergePaneli/siparisGrafik.php",
		data:data,
		contentType:false,
		processData:false,
		success: function(gelenSayfa){
			$('#siparisGrafik').html(gelenSayfa);
		},
		error: function (jqXHR, status, errorThrown) {
			alert("Result: "+status+" Status: "+jqXHR.status);
		}
	});
});
</script>
