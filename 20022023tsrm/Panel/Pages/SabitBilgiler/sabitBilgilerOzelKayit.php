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

	$fonk->imageResizeUpload($_FILES['siteLogo'],'../../Images/Ayarlar/','logo',128,128,png);//boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner

	$fonk->imageResizeUpload($_FILES['siteFavicon'],'../../Images/Ayarlar/','favicon',32,32,png);//doğrudan yükleme jpg yükleme başarılı ise 1 döner

	////güncllenecek parametreler***
	//Forumdan gelenler
	extract($_POST);//POST parametrelerini değişken olarak çevirir
	////güncllenecek parametreler***

	if($_POST['formdan']=="1"){
		$fonk->csrfKontrol();
		if($sabitBilgiSsl=="on"){$sabitBilgiSsl=1;}else{$sabitBilgiSsl=0;}
		if($sabitBilgiLog=="on"){$sabitBilgiLog=1;}else{$sabitBilgiLog=0;}
		if($sabitBilgiIpKontrol=="on"){$sabitBilgiIpKontrol=1;}else{$sabitBilgiIpKontrol=0;}
		if ($sabitBilgiPanelDilGosterim=="") {$sabitBilgiPanelDilGosterim=0;}
		if ($sabitBilgiDilGosterim=="") {$sabitBilgiDilGosterim=0;}
		//günclelemedeki parametreler
		$parametreler=array(
			'sabitBilgiSiteUrl' => $sabitBilgiSiteUrl,
			'sabitBilgiSiteAdi' => $sabitBilgiSiteAdi,
			'sabitBilgiTitle' => $sabitBilgiTitle,
			'sabitBilgiDescription' => $sabitBilgiDescription,
			'sabitBilgiKeywords' => $sabitBilgiKeywords,
			'sabitBilgiTel' => $sabitBilgiTel,
			'sabitBilgiMail' => $sabitBilgiMail,
			'sabitBilgiSifre' => $sabitBilgiSifre,
			'sabitBilgiBaslik' => $sabitBilgiBaslik,
			'sabitBilgiHost' => $sabitBilgiHost,
			'sabitBilgiPort' => $sabitBilgiPort,
			'sabitBilgiGuvenlik' => $sabitBilgiGuvenlik,
			'sabitBilgiPublicRecaptcha' => $sabitBilgiPublicRecaptcha,
			'sabitBilgiPrivateRecaptcha' => $sabitBilgiPrivateRecaptcha,
			'sabitBilgiSsl' => $sabitBilgiSsl,
			'sabitBilgiLog' => $sabitBilgiLog,
			'sabitBilgiIpKontrol' => $sabitBilgiIpKontrol,
			'sabitBilgiIzinliIpler' => $sabitBilgiIzinliIpler,
			'sabitBilgiDilGosterim' => $sabitBilgiDilGosterim,
			'sabitBilgiPanelDilGosterim' => $sabitBilgiPanelDilGosterim,
			'sabitBilgiEposFirma' => $sabitBilgiEposFirma,
			'sabitBilgiEposMagazaId' => $sabitBilgiEposMagazaId,
			'sabitBilgiEposPublicKey' => $sabitBilgiEposPublicKey,
			'sabitBilgiPrivateKey' => $sabitBilgiPrivateKey
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

							<form id="formpost" class="form" action="" method="post" enctype="multipart/form-data">
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
												<input type="text" id="sabitBilgiMail" class="form-control border-primary" name="sabitBilgiMail" value="<?=$Listeleme['sabitBilgiMail']?>" autocomplete="off" >
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
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiPublicRecaptcha"><?=$fonk->getPDil("Site Recaptcha Public")?></label>
												<input type="text" id="sabitBilgiPublicRecaptcha" class="form-control border-primary" name="sabitBilgiPublicRecaptcha" value="<?=$Listeleme['sabitBilgiPublicRecaptcha']?>" autocomplete="off" >
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiPrivateRecaptcha"><?=$fonk->getPDil("Site Recaptcha Private")?></label>
												<input type="text" id="sabitBilgiPrivateRecaptcha" class="form-control border-primary" name="sabitBilgiPrivateRecaptcha" value="<?=$Listeleme['sabitBilgiPrivateRecaptcha']?>" autocomplete="off" >
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="userinput1"><?=$fonk->getPDil("Kontroller")?></label>
												<div class="row skin skin-square">
													<div class="col-md-3 col-sm-6">
														<fieldset>
															<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="sabitBilgiSsl" id="sabitBilgiSsl" style="position: absolute; opacity: 0;" <?php if($Listeleme['sabitBilgiSsl']==1){echo "checked";}?>></div>
															<label for="sabitBilgiSsl" class=""><?=$fonk->getPDil("SSL Varmı")?></label>
														</fieldset>
														<fieldset>
															<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="sabitBilgiLog" id="sabitBilgiLog" style="position: absolute; opacity: 0;" <?php if($Listeleme['sabitBilgiLog']==1){echo "checked";}?>></div>
															<label for="sabitBilgiLog" class=""><?=$fonk->getPDil("Loglama")?></label>
														</fieldset>
														<fieldset>
															<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="sabitBilgiIpKontrol" id="sabitBilgiIpKontrol" style="position: absolute; opacity: 0;" <?php if($Listeleme['sabitBilgiIpKontrol']==1){echo "checked";}?>></div>
															<label for="sabitBilgiIpKontrol" class=""><?=$fonk->getPDil("IP Kontrolu")?></label>
														</fieldset>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="sabitBilgiGuvenlik"><?=$fonk->getPDil("Site İzinli İpler")?>  <small><?=$fonk->getPDil("( Noktalı Virgül ';' ile birden çok eklenebilir. )")?></small></label>
												<textarea class="form-control" id="sabitBilgiIzinliIpler" rows="3" name="sabitBilgiIzinliIpler"  placeholder="..."><?=$Listeleme['sabitBilgiIzinliIpler']?></textarea>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="siteLogo"><?=$fonk->getPDil("Site Logo (png)")?></label>
												<div class="custom-file">
													<input type="file" class="custom-file-input" name="siteLogo" id="siteLogo">
													<label class="custom-file-label" name="siteLogo" id="siteLogo" for="siteLogo" aria-describedby="siteLogo"><?=$fonk->getPDil("Dosya Seçiniz")?></label>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="siteFavicon"><?=$fonk->getPDil("Site Favicon (png)")?></label>
												<div class="custom-file">
													<input type="file" class="custom-file-input" name="siteFavicon" id="siteFavicon">
													<label class="custom-file-label" name="siteFavicon" id="siteFavicon" for="siteFavicon" aria-describedby="siteFavicon"><?=$fonk->getPDil("Dosya Seçiniz")?></label>
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="sabitBilgiDilGosterim"><?=$fonk->getPDil("Site Dil Kullanım")?></label>
												<fieldset>
													<div class="float-left">
														<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="sabitBilgiDilGosterim" value="1" name="sabitBilgiDilGosterim" <?php if($Listeleme['sabitBilgiDilGosterim']==1){echo 'checked';}?> >
													</div>
												</fieldset>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="sabitBilgiPanelDilGosterim"><?=$fonk->getPDil("Panel Dil Kullanım")?></label>
												<fieldset>
													<div class="float-left">
														<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="sabitBilgiPanelDilGosterim" value="1" name="sabitBilgiPanelDilGosterim" <?php if($Listeleme['sabitBilgiPanelDilGosterim']==1){echo 'checked';}?> >
													</div>
												</fieldset>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiEposFirma"><?=$fonk->getPDil("E-Pos Firması")?></label>
												<select class="select2 form-control block" name="sabitBilgiEposFirma" id="sabitBilgiEposFirma" required>
													<option value="1" <?php if ($Listeleme['sabitBilgiEposFirma']==1) { echo 'selected';} ?>><?=$fonk->getPDil("PayTR")?></option>
													<option value="2" <?php if ($Listeleme['sabitBilgiEposFirma']==2) { echo 'selected';} ?>><?=$fonk->getPDil("İysico")?></option>
													<option value="3" <?php if ($Listeleme['sabitBilgiEposFirma']==3) { echo 'selected';} ?>><?=$fonk->getPDil("Stripe")?></option>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiEposMagazaId"><?=$fonk->getPDil("Mağaza Id")?></label>
												<input type="text" id="sabitBilgiEposMagazaId" class="form-control border-primary" name="sabitBilgiEposMagazaId" value="<?=$Listeleme['sabitBilgiEposMagazaId']?>" autocomplete="off">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiEposPublicKey"><?=$fonk->getPDil("Public Key")?></label>
												<input type="text" id="sabitBilgiEposPublicKey" class="form-control border-primary" name="sabitBilgiEposPublicKey" value="<?=$Listeleme['sabitBilgiEposPublicKey']?>" autocomplete="off">
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sabitBilgiPrivateKey"><?=$fonk->getPDil("Private Key")?></label>
												<input type="text" id="sabitBilgiPrivateKey" class="form-control border-primary" name="sabitBilgiPrivateKey" value="<?=$Listeleme['sabitBilgiPrivateKey']?>" autocomplete="off">
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
									<button type="submit" class="btn mb-1 btn-success"><i class="la la-floppy-o"></i> <?=$fonk->getPDil("Kayıt")?></button>
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
