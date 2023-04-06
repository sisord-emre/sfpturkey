<?php
include ("../../System/Config.php");

$menuId=$_POST['menuId'];//menu id alınıyor

///menu bilgileri alınıyor
$hangiMenu = $db->get("Menuler", "*", [
	"menuUstMenuId" => $menuId,
	"menuGorunurluk" =>	1,
	"menuOzelGorunuruk" =>	1,
	"menuTipi" =>	1 //kayıt için 1 listeleme için 2 diğer sayfalar içim 3 yazılmalı****
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

	}
}
if(!$eklemeYetki && !$duzenlemeYetki)
{
	//yetki yoksa gözükecek yazi
	echo '<div class="alert alert-icon-right alert-warning alert-dismissible mb-2" role="alert">
	<span class="alert-icon"><i class="la la-warning"></i></span>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">×</span>
	</button>
	<strong>'.$fonk->getPDil("Yetki!").' </strong> '.$fonk->getPDil("Bu Menüye Erişim Yetkiniz Bulunmamaktadır.").'
	</div>';
}
else{//Listeleme Yetkisi Var

	$tableName=$hangiMenu['menuTabloAdi'];//tabloadı istenirse burdan değiştirilebilir

	$tabloPrimarySutun=$hangiMenu['menuTabloPrimarySutun'];//primarykey sutunu

	$baslik=$hangiMenu['menuAdi'];//başlıkta gözükecek yazı menu adi

	$duzenlemeSayfasi=$tableName.'/'.strtolower($tableName).'Kayit.php';
	$listelemeSayfasi=$tableName."/".strtolower($tableName)."Listeleme.php";

	$primaryId=1;//düzenle isteği ile gelen

	if($_POST['formdan']!="1"){
		//sayfayı görüntülenme logları
		$fonk->logKayit(6,$_SERVER['REQUEST_URI']."?primaryId=".$primaryId);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
	}

	////güncllenecek parametreler***
	//Forumdan gelenler
	extract($_POST);//POST parametrelerini değişken olarak çevirir
	////güncellenecek parametreler***
	if($_POST['formdan']=="1"){
		$fonk->csrfKontrol();
		//günclelemedeki parametreler
		if ($sabitBilgiKrediKarti=="") {
			$sabitBilgiKrediKarti=0;
		}
		if ($sabitBilgiHavaleEft=="") {
			$sabitBilgiHavaleEft=0;
		}
		if ($sabitBilgiKapidaOdeme=="") {
			$sabitBilgiKapidaOdeme=0;
		}
		if ($sabitBilgiOtoKurGuncelle=="") {
			$sabitBilgiOtoKurGuncelle=0;
		}
		$parametreler=array(
			'sabitBilgiSiteUrl' => $sabitBilgiSiteUrl,
			'sabitBilgiSiteAdi' => $sabitBilgiSiteAdi,
			'sabitBilgiTitle' => $sabitBilgiTitle,
			'sabitBilgiDescription' => $sabitBilgiDescription,
			'sabitBilgiKeywords' => $sabitBilgiKeywords,
			'sabitBilgiTel' => $sabitBilgiTel,
			'sabitBilgiAdres' => $sabitBilgiAdres,
			'sabitBilgiMail' => $sabitBilgiMail,
			'sabitBilgiSifre' => $sabitBilgiSifre,
			'sabitBilgiBaslik' => $sabitBilgiBaslik,
			'sabitBilgiHost' => $sabitBilgiHost,
			'sabitBilgiPort' => $sabitBilgiPort,
			'sabitBilgiGuvenlik' => $sabitBilgiGuvenlik,
			'sabitBilgiVarsayilanDilId' => $sabitBilgiVarsayilanDilId,
			'sabitBilgiPanelVarsayilanDilId' => $sabitBilgiPanelVarsayilanDilId,
			'sabitBilgiKrediKarti' => $sabitBilgiKrediKarti,
			'sabitBilgiHavaleEft' => $sabitBilgiHavaleEft,
			'sabitBilgiKapidaOdeme' => $sabitBilgiKapidaOdeme,
			'sabitBilgiOtoKurGuncelle' => $sabitBilgiOtoKurGuncelle,
			'sabitBilgiDolar' => $sabitBilgiDolar,
			'sabitBilgiEuro' => $sabitBilgiEuro,
			'sabitBilgiAdres2' => $sabitBilgiAdres2,
			'sabitBilgiHarita' => $sabitBilgiHarita,
			'sabitBilgiFacebook' => $sabitBilgiFacebook,
			'sabitBilgiTwitter' => $sabitBilgiTwitter,
			'sabitBilgiInstagram' => $sabitBilgiInstagram,
			'sabitBilgiLinkedin' => $sabitBilgiLinkedin,
			'sabitBilgiWhatsApp' => $sabitBilgiWhatsApp,
			'sabitBilgiBildirimMail' => $sabitBilgiBildirimMail,
			'sabitBilgiUstYazi' => $sabitBilgiUstYazi
		);

		$fonk->logKayit(2,$tableName.' ; '.$primaryId.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

		///güncelleme
		$query = $db->update($tableName, $parametreler, [
			$tabloPrimarySutun => $primaryId
		]);

		if($query){//uyarı metinleri
			echo '
			<div class="alert alert-success alert-dismissible mb-2" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
			</button>
			<strong>'.$fonk->getPDil("Başarılı!").'</strong> '.$fonk->getPDil("Kayıt İşlemi Başarıyla Gerçekleşmiştir.").'
			</div>';
		}
		else{
			echo '
			<div class="alert alert-danger alert-dismissible mb-2" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
			</button>
			<strong>'.$fonk->getPDil("Hata!").'</strong> '.$fonk->getPDil("Kayıt Esnasında Bir Hata Oluştu. Lütfen Tekrar Deneyiniz.").'('.$db->error.')
			</div>';
		}
	}
	echo "<script>$('#ustYazi').html('&nbsp;-&nbsp;'+'".$fonk->getPDil($baslik)."');</script>";//Başlık Güncelleniyor
	//update ise bilgiler getiriliyor
	if($primaryId!=""){
		$Listeleme = $db->get($tableName, "*", [
			$tabloPrimarySutun => $primaryId
		]);
	}
	?>
	<!-- Basic form layout section start -->
	<section id="basic-form-layouts">
		<div class="row match-height">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="basic-layout-colored-form-control"><?=$fonk->getPDil($baslik)?></h4>
						<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
						<div class="heading-elements">
						</div>
					</div>
					<div class="card-content collapse show">
						<div class="card-body">

							<form id="formpost" class="form" action="" method="post">
								<div class="form-body">

									<!-- Güncellenecek Kısımlar -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiSiteUrl"><?=$fonk->getPDil("Site Url")?></label>
												<input type="text" id="sabitBilgiSiteUrl" class="form-control border-primary" name="sabitBilgiSiteUrl" value="<?=$Listeleme['sabitBilgiSiteUrl']?>" autocomplete="off" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiSiteAdi"><?=$fonk->getPDil("Site Adı")?></label>
												<input type="text" id="sabitBilgiSiteAdi" class="form-control border-primary" name="sabitBilgiSiteAdi" value="<?=$Listeleme['sabitBilgiSiteAdi']?>" autocomplete="off" required>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiTitle"><?=$fonk->getPDil("Site Title")?></label>
												<input type="text" id="sabitBilgiTitle" class="form-control border-primary" name="sabitBilgiTitle" value="<?=$Listeleme['sabitBilgiTitle']?>" autocomplete="off" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiDescription"><?=$fonk->getPDil("Site Description")?></label>
												<input type="text" id="sabitBilgiDescription" class="form-control border-primary" name="sabitBilgiDescription" value="<?=$Listeleme['sabitBilgiDescription']?>" autocomplete="off" required>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiKeywords"><?=$fonk->getPDil("Site Keywords")?></label>
												<input type="text" id="sabitBilgiKeywords" class="form-control border-primary" name="sabitBilgiKeywords" value="<?=$Listeleme['sabitBilgiKeywords']?>" autocomplete="off" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiTel"><?=$fonk->getPDil("Site Tel")?></label>
												<input type="tel" id="sabitBilgiTel" class="form-control border-primary" name="sabitBilgiTel" value="<?=$Listeleme['sabitBilgiTel']?>" autocomplete="off">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiMail"><?=$fonk->getPDil("Site Mail")?></label>&emsp;<small><?=$fonk->getPDil("( Noktalı virgül ';' ile birden çok mail eklenebilir.İlk mail gönderen mail adresidir. )")?></small>
												<input type="text" id="sabitBilgiMail" class="form-control border-primary"  name="sabitBilgiMail" value="<?=$Listeleme['sabitBilgiMail']?>" autocomplete="off">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiSifre"><?=$fonk->getPDil("Site Mail Şifre")?></label>
												<input type="text" id="sabitBilgiSifre" class="form-control border-primary" name="sabitBilgiSifre" value="<?=$Listeleme['sabitBilgiSifre']?>" autocomplete="off">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiBaslik"><?=$fonk->getPDil("Site From Name")?></label>
												<input type="text" id="sabitBilgiBaslik" class="form-control border-primary" name="sabitBilgiBaslik" value="<?=$Listeleme['sabitBilgiBaslik']?>" autocomplete="off">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiHost"><?=$fonk->getPDil("Site Mail Host")?></label>
												<input type="text" id="sabitBilgiHost" class="form-control border-primary" name="sabitBilgiHost" value="<?=$Listeleme['sabitBilgiHost']?>" autocomplete="off">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiPort"><?=$fonk->getPDil("Site Mail Port")?></label>
												<input type="number" id="sabitBilgiPort" class="form-control border-primary" name="sabitBilgiPort" value="<?=$Listeleme['sabitBilgiPort']?>" autocomplete="off">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiGuvenlik"><?=$fonk->getPDil("Site Mail Güvenlik")?></label>
												<input type="text" id="sabitBilgiGuvenlik" class="form-control border-primary" placeholder="ssl/tls" name="sabitBilgiGuvenlik" value="<?=$Listeleme['sabitBilgiGuvenlik']?>" autocomplete="off">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiAdres"><?=$fonk->getPDil("Adres")?></label>
												<textarea class="form-control" id="sabitBilgiAdres" rows="3" name="sabitBilgiAdres"  placeholder="..."><?=$Listeleme['sabitBilgiAdres']?></textarea>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiAdres2"><?=$fonk->getPDil("Adres 2")?></label>
												<textarea class="form-control" id="sabitBilgiAdres2" rows="3" name="sabitBilgiAdres2"  placeholder="..."><?=$Listeleme['sabitBilgiAdres2']?></textarea>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="userinput1"><?=$fonk->getPDil("Site Varsayılan Dil")?></label>
												<select class="select2 form-control block" name="sabitBilgiVarsayilanDilId" id="sabitBilgiVarsayilanDilId" required>
													<?php
													$sorguList = $db->select("Diller","*",[
														"dilDurumu" => 1
													]);
													foreach($sorguList as $sorgu){
														?>
														<option value="<?=$sorgu['dilId']?>" <?php if($sorgu['dilId']==$Listeleme['sabitBilgiVarsayilanDilId']){echo " selected";}?>><?=$sorgu['dilAdi']." - ".$sorgu['dilKodu']?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiPanelVarsayilanDilId"><?=$fonk->getPDil("Panel Varsayılan Dil")?></label>
												<select class="select2 form-control block" name="sabitBilgiPanelVarsayilanDilId" id="sabitBilgiPanelVarsayilanDilId" required>
													<?php
													$sorguList = $db->select("Diller","*",[
														"dilPanelDurumu" => 1
													]);
													foreach($sorguList as $sorgu){
														?>
														<option value="<?=$sorgu['dilId']?>" <?php if($sorgu['dilId']==$Listeleme['sabitBilgiPanelVarsayilanDilId']){echo " selected";}?>><?=$sorgu['dilAdi']." - ".$sorgu['dilKodu']?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiHarita"><?=$fonk->getPDil("Harita")?></label>
												<textarea class="form-control" id="sabitBilgiHarita" rows="3" name="sabitBilgiHarita" onkeyup="IframeToLink('sabitBilgiHarita')" placeholder="..."><?=$Listeleme['sabitBilgiHarita']?></textarea>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiUstYazi"><?=$fonk->getPDil("Header Üst Yazı")?></label>
												<input type="text" id="sabitBilgiUstYazi" class="form-control border-primary" name="sabitBilgiUstYazi" value="<?=$Listeleme['sabitBilgiUstYazi']?>" autocomplete="off">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiWhatsApp"><?=$fonk->getPDil("WhatsApp")?></label>
												<input type="text" id="sabitBilgiWhatsApp" class="form-control border-primary" placeholder="+90XXXXXXXXX" name="sabitBilgiWhatsApp" value="<?=$Listeleme['sabitBilgiWhatsApp']?>" autocomplete="off">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiBildirimMail"><?=$fonk->getPDil("Bildirim Maili")?></label>
												<input type="email" id="sabitBilgiBildirimMail" class="form-control border-primary" placeholder="<?=$fonk->getPDil("Yorum,İletişim Formu vs.")?>" name="sabitBilgiBildirimMail" value="<?=$Listeleme['sabitBilgiBildirimMail']?>" autocomplete="off">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiFacebook"><?=$fonk->getPDil("Facebook")?></label>
												<input type="text" id="sabitBilgiFacebook" class="form-control border-primary" name="sabitBilgiFacebook" value="<?=$Listeleme['sabitBilgiFacebook']?>" autocomplete="off">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiTwitter"><?=$fonk->getPDil("Twitter")?></label>
												<input type="text" id="sabitBilgiTwitter" class="form-control border-primary" name="sabitBilgiTwitter" value="<?=$Listeleme['sabitBilgiTwitter']?>" autocomplete="off">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiInstagram"><?=$fonk->getPDil("Instagram")?></label>
												<input type="text" id="sabitBilgiInstagram" class="form-control border-primary" name="sabitBilgiInstagram" value="<?=$Listeleme['sabitBilgiInstagram']?>" autocomplete="off">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiLinkedin"><?=$fonk->getPDil("Linkedin")?></label>
												<input type="text" id="sabitBilgiLinkedin" class="form-control border-primary" name="sabitBilgiLinkedin" value="<?=$Listeleme['sabitBilgiLinkedin']?>" autocomplete="off">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-2">
											<div class="form-group">
												<label for="sabitBilgiKrediKarti"><?=$fonk->getPDil("Kredi Kartı")?></label>
												<fieldset>
													<div class="float-left">
														<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="sabitBilgiKrediKarti" value="1" name="sabitBilgiKrediKarti" <?php if($Listeleme['sabitBilgiKrediKarti']==1){echo 'checked';}?> >
													</div>
												</fieldset>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="sabitBilgiHavaleEft"><?=$fonk->getPDil("Havale & Eft")?></label>
												<fieldset>
													<div class="float-left">
														<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="sabitBilgiHavaleEft" value="1" name="sabitBilgiHavaleEft" <?php if($Listeleme['sabitBilgiHavaleEft']==1){echo 'checked';}?> >
													</div>
												</fieldset>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="sabitBilgiKapidaOdeme"><?=$fonk->getPDil("Kapıda Ödeme")?></label>
												<fieldset>
													<div class="float-left">
														<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="sabitBilgiKapidaOdeme" value="1" name="sabitBilgiKapidaOdeme" <?php if($Listeleme['sabitBilgiKapidaOdeme']==1){echo 'checked';}?> >
													</div>
												</fieldset>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="sabitBilgiOtoKurGuncelle"><?=$fonk->getPDil("Oto Kur Güncelle")?></label>
												<fieldset>
													<div class="float-left">
														<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="sabitBilgiOtoKurGuncelle" value="1" name="sabitBilgiOtoKurGuncelle" <?php if($Listeleme['sabitBilgiOtoKurGuncelle']==1){echo 'checked';}?> >
													</div>
												</fieldset>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="sabitBilgiDolar"><?=$fonk->getPDil("Dolar")?></label>
												<input type="text" step="0.0001" id="sabitBilgiDolar" class="form-control border-primary" placeholder="$" name="sabitBilgiDolar" value="<?=$Listeleme['sabitBilgiDolar']?>" autocomplete="off" required>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="sabitBilgiEuro"><?=$fonk->getPDil("Euro")?></label>
												<input type="number" step="0.0001" id="sabitBilgiEuro" class="form-control border-primary" placeholder="€" name="sabitBilgiEuro" value="<?=$Listeleme['sabitBilgiEuro']?>" autocomplete="off" required>
											</div>
										</div>
									</div>
									<!-- /Güncellenecek Kısımlar -->

								</div>
								<div class="form-group" style="text-align: center;margin-top:15px">
									<input type="hidden" name="update" value="<?=$Listeleme[$tabloPrimarySutun]?>">
									<input type="hidden" name="menuId" value="<?=$menuId?>">
									<input type="hidden" name="formdan" value="1">
									<input type="hidden" name="token" value="<?=$_SESSION['token']?>" />
									<button type="submit" class="btn mb-1 btn-success"><i class="la la-floppy-o"></i> <?=$fonk->getPDil("Güncelle")?></button>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- // Basic form layout section end -->

<?php } include("../../Scripts/kayitJs.php");?>
<script type="text/javascript">
$('#formpost').submit(function (e) {
	e.preventDefault(); //submit postu kesyoruz
	var data=new FormData(this);
	$('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
	$.ajax({
		type: "POST",
		url: "<?=$_SERVER['REQUEST_URI']?>",
		data:data,
		contentType:false,
		processData:false,
		success: function(res){
			$('#Sayfalar').html(res);
		},
		error: function (jqXHR, status, errorThrown) {
			alert("Result: "+status+" Status: "+jqXHR.status);
		}
	});
});
</script>
