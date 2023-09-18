<?php
include ("../../System/Config.php");

$menuId=$_POST['menuId'];//menu id alınıyor

///menu bilgileri alınıyor
$hangiMenu = $db->get("Menuler", "*", [
	"menuUstMenuId" => $menuId,
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

	$primaryId=$_POST['update'];//düzenle isteği ile gelen

	if($_POST['formdan']!="1"){
		//sayfayı görüntülenme logları
		$fonk->logKayit(6,$_SERVER['REQUEST_URI']."?primaryId=".$primaryId);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
	}

	////güncllenecek parametreler***
	//Forumdan gelenler
	extract($_POST);//POST parametrelerini değişken olarak çevirir
	////güncllenecek parametreler***

	if($_POST['formdan']=="1"){
		$fonk->csrfKontrol();
		//$gorselAdi=$fonk->toSeo($kullaniciAdSoyad)."-".mt_rand();
		//$kontrol=$fonk->imageResizeUpload($_FILES['siteLogo'],'../../../Images/Ayarlar/',$gorselAdi,128,128,jpg);//boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner

		/* etiket ile ilgili işlemlerde kullanılabilri
		$etiketler=$_POST['etiketler'];
		///etiket işlemleri
		for($i=0;$i<Count($etiketler);$i++){

		$etiket=strtolower(trim($etiketler[$i]));
		$etiketVarmi = $db->get("Etiketler", "*", [
			"etiketAdi" => $etiket
		]);
		if(!$etiketVarmi){
		$query = $db->insert("Etiketler", [
			'etiketAdi' => $etiket
		])
}
$yayinEtiketler=$yayinEtiketler.";".$etiket;
}
$yayinEtiketler=ltrim($yayinEtiketler,";");
///!etiket işlemleri	*/


if($primaryId!=""){
	//günclelemedeki parametreler
	$parametreler=array(
		'kullaniciAdSoyad' => $kullaniciAdSoyad,
		'kullaniciEmail' => $kullaniciEmail,
		'kullaniciYetkiler' => $kullaniciYetkiler,
		'kullaniciDurum' => $kullaniciDurum
	);
}else{
	//eklemedeki parametreler
	$parametreler=array(
		'kullaniciAdSoyad' => $kullaniciAdSoyad,
		'kullaniciEmail' => $kullaniciEmail,
		'kullaniciYetkiler' => $kullaniciYetkiler,
		'kullaniciDurum' => $kullaniciDurum,
		'kullaniciKayitTarihi' => date("Y-m-d H:i:s")
	);
}

if($kontrol==1){//eğer duruma göre boş bırakılabiliyor ise parametre, sonradan arraye eklenir
	$parametreler=array_merge($parametreler,array('siteLogo' => $gorselAdi.".jpg"));
}

if($primaryId!=""){
	$fonk->logKayit(2,$tableName.' ; '.$primaryId.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
	///güncelleme
	$query = $db->update($tableName, $parametreler, [
		$tabloPrimarySutun => $primaryId
	]);
}
else{
	$fonk->logKayit(1,$tableName.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
	///ekleme
	$query = $db->insert($tableName, $parametreler);

	$primaryId=$db->id();
}

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
						<?php if($eklemeYetki){?><button type="button" onclick="YeniEkle('<?=$menuId?>','<?=$duzenlemeSayfasi?>');" class="btn mr-1 btn-primary btn-sm"><i class="la la-plus-circle"></i></button><?php } ?>
						<?php if($listelemeYetki){?><button type="button" onclick="SayfaGetir('<?=$menuId?>','<?=$listelemeSayfasi?>');" class="btn mr-1 btn-primary btn-sm"><i class="la la-th-list"></i> <?=$fonk->getPDil("Listeleme")?></button><?php } ?>
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
											<label for="kullaniciAdSoyad"><?=$fonk->getPDil("Ad Soyad")?></label>
											<input type="text" class="form-control border-primary" id="kullaniciAdSoyad" name="kullaniciAdSoyad" value="<?=$Listeleme['kullaniciAdSoyad']?>" autocomplete="off" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="kullaniciEmail"><?=$fonk->getPDil("Email")?></label>
											<input type="email" class="form-control border-primary" id="kullaniciEmail" name="kullaniciEmail" value="<?=$Listeleme['kullaniciEmail']?>" autocomplete="off" required>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="kullaniciSifre"><?=$fonk->getPDil("Şifre")?></label>
											<input type="password" class="form-control border-primary" id="kullaniciSifre" name="kullaniciSifre" autocomplete="new-password" <?php if($Listeleme['kullaniciSifre']==""){ echo " required"; }?>>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="userinput1"><?=$fonk->getPDil("Checkboxlar")?></label>
											<div class="row skin skin-square">
												<div class="col-md-3 col-sm-6">
													<fieldset>
														<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="listeleme" id="listeleme" value="1" style="position: absolute; opacity: 0;" <?php if($Listeleme['sabitBilgiSsl']==1){echo "checked";}?>></div>
														<label for="listeleme" class=""><?=$fonk->getPDil("Listeleme")?></label>
													</fieldset>
													<fieldset>
														<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="ekleme" id="ekleme" value="1" style="position: absolute; opacity: 0;" <?php if($Listeleme['sabitBilgiSsl']==1){echo "checked";}?>></div>
														<label for="ekleme" class=""><?=$fonk->getPDil("Ekleme")?></label>
													</fieldset>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<fieldset>
												<h5>Date Mask
													<small class="text-muted">dd/mm/yyyy</small>
												</h5>
												<div class="form-group">
													<input name="datemask" class="form-control date-inputmask" type="text" data-inputmask="'alias': 'datetime','inputFormat': 'dd/mm/yyyy'">
												</div>
											</fieldset>
										</div>
									</div>
									<div class="col-md-6">
										<fieldset>
											<h5>Phone
												<small class="text-muted">(999) 999-9999</small>
											</h5>
											<div class="form-group">
												<input type="text" class="form-control phone-inputmask"  name="ekleme" id="phone-mask" />
											</div>
										</fieldset>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<fieldset>
												<h5>Email
													<small class="text-muted">xxx@xxx.xxx</small>
												</h5>
												<div class="form-group">
													<input type="text" class="form-control email-inputmask" name="emailmask" id="email-mask" im-insert="true">
												</div>
											</fieldset>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="input-radio-3">Radio Buttonlar</label>
											<div class="row skin skin-square">
												<div class="col-md-6 col-sm-12">
													<fieldset>
														<input type="radio" name="input-radio-3" id="input-radio-11">
														<label for="input-radio-11">Radio Button</label>
													</fieldset>
													<fieldset>
														<input type="radio" name="input-radio-3" id="input-radio-12" checked>
														<label for="input-radio-12">Radio Button Checked</label>
													</fieldset>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="sabitBilgiIzinliIpler"><?=$fonk->getPDil("Açıklama")?></label>
											<textarea class="form-control" id="sabitBilgiIzinliIpler" name="sabitBilgiIzinliIpler" rows="3" placeholder="..."><?=$Listeleme['sabitBilgiIzinliIpler']?></textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="yayinInstagram">Etiketler</label>
											<select id="selectize-state" multiple name="etiketler[]" class="selectize-event" style="width:100%">
												<option value="">Etiket Seçiniz</option>
												<?php
												$etiketDizi = explode(";",$Listeleme['yayinEtiketler']);
												$sorguList = $db->select("Etiketler","*");
												foreach($sorguList as $sorgu){
													for($i=0;$i<Count($etiketDizi);$i++){
														if($etiketDizi[$i]==$sorgu['etiketAdi']){
															$secili=" selected";
														}
													}
													?>
													<option value="<?=$sorgu['etiketAdi']?>" <?=$secili?>><?=$sorgu['etiketAdi']?></option>
													<?php $secili=""; }?>
												</select>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="siteLogo"><?=$fonk->getPDil("Görsel")?></label>
												<div class="custom-file">
													<input type="file" class="custom-file-input" name="siteLogo" id="siteLogo" accept=".jpg,.png,.jpeg,.gif,.bmp">
													<label class="custom-file-label" name="siteLogo" id="siteLogo" for="siteLogo" aria-describedby="siteLogo"><?=$fonk->getPDil("Dosya Seçiniz")?></label>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<label for="tarih"><?=$fonk->getPDil("Tarih")?></label>
											<input type="date" class="form-control" id="tarih" name="tarih" value="<?=date("Y-m-d")?>">
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="urunlerKategoriID"><?=$fonk->getPDil("Kategoriler")?></label>
												<select class="select2 form-control block" name="urunlerKategoriID" id="urunlerKategoriID">
													<optgroup label="Alaskan/Hawaiian Time Zone">
														<option value="AK">Alaska</option>
														<option value="HI">Hawaii</option>
													</optgroup>

													<option value=""><?=$fonk->getPDil("Seçiniz")?></option>
													<?php
													$sorguList = $db->select("Kategoriler","*");
													foreach($sorguList as $sorgu){
														?>
														<option value="<?=$sorgu['kategorilerID']?>" <?php if($sorgu['kategorilerID']==$Listeleme['urunlerKategoriID']){echo " selected";}?>><?=$sorgu['kategorilerAdi']?></option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<fieldset class="form-group">
												<h5>Çuklu seçim</h5>
												<div class="single-select-box selectivity-input">
													<div class="selectivity-multiple-input-container">
														<span class="selectivity-multiple-selected-item" data-item-id="Barcelona"><a class="selectivity-multiple-selected-item-remove"><i class="fa fa-remove">x</i></a>Barcelona</span>
														<input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" class="selectivity-multiple-input" placeholder="" style="width: 20px;"><span class="selectivity-multiple-input selectivity-width-detector"></span><div class="selectivity-clearfix"></div></div>
														<select name="traditional[multiple][]" multiple="" name="coklusecim" class="">
															<option value="Barcelona" selected="">Barcelona</option>
															<option value="Cologne" selected="">Cologne</option>
															<option value="Milan" selected="">Milan</option>
															<option value="Antwerp" selected="">Antwerp</option>
														</select>
													</div>
												</fieldset>
											</div>
										</div>

										<div class="row">
											<div class="col-md-8">
												<div class="form-group">
													<label for="blogText"><?=$fonk->getPDil("Editor")?></label>
													<textarea id="blogText" name="blogText">
														<?=$Listeleme['blogText']?>
													</textarea>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="kullaniciDurum"><?=$fonk->getPDil("Durumu")?></label>
													<fieldset>
														<div class="float-left">
															<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="kullaniciDurum" name="kullaniciDurum" value="1" <?php if($Listeleme['kullaniciDurum']==1){echo 'checked';}?> >
														</div>
													</fieldset>
												</div>
											</div>
										</div>
										<!-- /Güncellenecek Kısımlar -->

									</div>
									<div class="form-group" style="text-align: center;margin-top:15px">
										<input type="hidden" name="update" value="<?=$Listeleme[$tabloPrimarySutun]?>"/>
										<input type="hidden" name="menuId" value="<?=$menuId?>"/>
										<input type="hidden" name="formdan" value="1"/>
										<input type="hidden" name="token" value="<?=$_SESSION['token']?>" />
										<button type="submit" class="btn mb-1 btn-success"><i class="la la-floppy-o"></i> <?php if($primaryId!=""){ echo $fonk->getPDil("Güncelle");}else{ echo $fonk->getPDil("Kayıt");}?></button>
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
	/*
	CKEDITOR.replace('blogText', { //ckeditor kullanıldığında açılır
	height: '350px',
	extraPlugins: 'forms',
	uiColor: '#CCEAEE',
	//Dosya Yöneticisi resim gözat için
	filebrowserBrowseUrl : 'Assets/app-assets/fileman/index.html',// Öntanımlı Dosya Yöneticisi
	filebrowserImageBrowseUrl : 'Assets/app-assets/fileman/index.html?type=image',// Sadece Resim Dosyalarını Gösteren Dosya Yöneticisi
	removeDialogTabs : 'link:upload;image:upload' // Upload işlermlerini dosya Yöneticisi ile yapacağımız için upload butonlarını kaldırıyoruz
	});
	*/
	$('#formpost').submit(function (e) {
		e.preventDefault(); //submit postu kesyoruz
		var data=new FormData(this);
		//data.append('blogText', CKEDITOR.instances['blogText'].getData());//ckeditor kullanılacağı zaman açılır 'ckeditor' yazan kısmı post keyidir
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
