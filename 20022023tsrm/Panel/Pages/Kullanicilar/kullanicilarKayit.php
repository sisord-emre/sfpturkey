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
if(!$kullaniciYetki && !$eklemeYetki)
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

	$primaryId=$_POST['update'];

	if($_POST['formdan']!="1"){
		//sayfayı görüntülenme logları
		$fonk->logKayit(6,$_SERVER['REQUEST_URI']."?primaryId=".$primaryId);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
	}

	//Forumdan gelenler
	extract($_POST);//POST parametrelerini değişken olarak çevirir

	///yetkilendirme
	$menuler = $db->select("Menuler", "*", [
		"menuUstMenuId" =>0,
		"menuOzelGorunuruk" =>	1,
		"ORDER" => [
			"menuSirasi" => "ASC"
		]
	]);

	foreach($menuler as $menu){
		$listeleme=$_POST['listeleme_'.$menu['menuId']];
		$ekleme=$_POST['ekleme_'.$menu['menuId']];
		$duzenleme=$_POST['duzenle_'.$menu['menuId']];
		$silme=$_POST['silme_'.$menu['menuId']];
		$excel=$_POST['excel_'.$menu['menuId']];

		if($listeleme=="on" || $ekleme=="on" || $duzenleme=="on" || $silme=="on" || $excel=="on"){
			$kullaniciYetkiler=$kullaniciYetkiler.";".json_encode(array("menuYetkiID" => $menu['menuId'],"listeleme" => $listeleme, "ekleme" => $ekleme, "duzenleme" => $duzenleme, "silme" => $silme, "excel" => $excel));
		}
	}
	$kullaniciYetkiler=ltrim(trim($kullaniciYetkiler),"Array;");//ilk ; ü siliyoruz
	///-yetkilendirme

	if($_POST['formdan']=="1"){
		$fonk->csrfKontrol();
		if($kullaniciDurum=="on"){
			$kullaniciDurum=1;
		}else{
			$kullaniciDurum=0;
		}

		if($_POST['update']!=""){//güncelleme ise
			$parametreler=array(
				'kullaniciAdSoyad' => $kullaniciAdSoyad,
				'kullaniciEmail' => $kullaniciEmail,
				'kullaniciYetkiler' => $kullaniciYetkiler,
				'kullaniciDurum' => $kullaniciDurum
			);
		}else{//ekleme ise
			$parametreler=array(
				'kullaniciAdSoyad' => $kullaniciAdSoyad,
				'kullaniciEmail' => $kullaniciEmail,
				'kullaniciYetkiler' => $kullaniciYetkiler,
				'kullaniciDurum' => $kullaniciDurum,
				'kullaniciOzelYetki' => 0,
				'kullaniciGizle' => 0,
				'kullaniciSessionKey' => "",
				'kullaniciKayitTarihi' => date("Y-m-d H:i:s")
			);
		}

		if($kullaniciSifre!=""){//eğer duruma göre boş bırakılabiliyor ise parametre, sonradan arraye eklenir
			$parametreler=array_merge($parametreler,array('kullaniciSifre' => hash("sha256", md5($kullaniciSifre))));
		}

		if($_POST['update']!=""){
			$varmi = $db->get("Kullanicilar", "*", [
				"kullaniciEmail" => $kullaniciEmail,
				"kullaniciId[!]" => $primaryId
			]);

			if(!$varmi){
				$fonk->logKayit(2,$tableName.' ; '.$primaryId.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				///güncelleme
				$query = $db->update($tableName, $parametreler, [
					$tabloPrimarySutun => $primaryId
				]);
			}
		}
		else{
			$varmi = $db->get("Kullanicilar", "*", [
				"kullaniciEmail" => $kullaniciEmail
			]);

			if(!$varmi){
				$fonk->logKayit(1,$tableName.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				///ekleme
				$query = $db->insert($tableName, $parametreler);

				$primaryId=$db->id();
			}
		}

		if($varmi){
			echo '
			<div class="alert alert-danger alert-dismissible mb-2" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
			</button>
			<strong>'.$fonk->getPDil("Hata!").'</strong> '.$fonk->getPDil("Bu Mail Daha Önce Kullanılmıştır. Lütfen Tekrar Deneyiniz.").'('.$db->error.')
			</div>';
		}
		else if($query){//uyarı metinleri
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
	if($primaryId!=""){
		$Listeleme = $db->get($tableName, "*", [
			$tabloPrimarySutun => $primaryId
		]);
		$listYetki=explode(';',$Listeleme['kullaniciYetkiler']);
	}
	echo "<script>$('#ustYazi').html('&nbsp;-&nbsp;'+'".$fonk->getPDil($baslik)."');</script>";//Başlık Güncelleniyor
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
							<form id="formpost" class="form" action="" method="post" autocomplete="off">
								<div class="form-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="kullaniciAdSoyad"><?=$fonk->getPDil("Ad Soyad")?></label>
												<input type="text" id="kullaniciAdSoyad" class="form-control border-primary" placeholder="<?=$fonk->getPDil("Ad Soyad")?>" name="kullaniciAdSoyad" value="<?=$Listeleme['kullaniciAdSoyad']?>" autocomplete="off" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="kullaniciEmail"><?=$fonk->getPDil("Email")?></label>
												<input type="email" id="kullaniciEmail" class="form-control border-primary" placeholder="<?=$fonk->getPDil("Email")?>" name="kullaniciEmail" value="<?=$Listeleme['kullaniciEmail']?>" autocomplete="off" required>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="userinput1"><?=$fonk->getPDil("Yetkiler")?></label>
												<div class="row skin skin-square">
													<?php
													$menuler = $db->select("Menuler", "*", [
														"menuUstMenuId" =>0,
														"menuOzelGorunuruk" =>	1,
														"ORDER" => [
															"menuSirasi" => "ASC"
														]
													]);
													foreach($menuler as $menu){
														for($i=0;$i<Count($listYetki);$i++){//yetkisi varsa hangileri olduğuna bakıyoruzki tikli getirelim
															$yetki= json_decode($listYetki[$i], true);
															if($yetki['menuYetkiID']==$menu['menuId']){
																if($yetki['listeleme']=="on"){
																	$listelemeTik="checked";
																}
																if($yetki['ekleme']=="on"){
																	$eklemeTik="checked";
																}
																if($yetki['duzenleme']=="on"){
																	$duzenlemeTik="checked";
																}
																if($yetki['silme']=="on"){
																	$silmeTik="checked";
																}
																if($yetki['excel']=="on"){
																	$excelTik="checked";
																}
																if ($yetki['listeleme']=="on" && $yetki['ekleme']=="on" && $yetki['duzenleme']=="on" && $yetki['silme']=="on" && $yetki['excel']=="on") {
																	$tumuTik="checked";
																}
															}
														}
														if($kulBilgi['kullaniciOzelYetki']==0){
															$menuYetki=false;
															$listelemeOzelYetki=false;
															$silmeOzelYetki=false;
															$duzenlemeOzelYetki=false;
															$eklemeOzelYetki=false;
															$excelOzelYetki=false;

															$kullaniciOzelBilgi = $db->get("Kullanicilar", "*", [
																"kullaniciId" => $kulBilgi['kullaniciId']
															]);
															$kullaniciOzelYetkiler = explode(';', $kullaniciOzelBilgi['kullaniciYetkiler']);
															for($j=0;$j<Count($kullaniciOzelYetkiler);$j++){//kullanıcının yetkilerini sorguluyoruz
																$kullaniciYetki= json_decode($kullaniciOzelYetkiler[$j], true);
																if($kullaniciYetki['menuYetkiID']==$menu['menuId']){
																	$menuYetki=true;
																	if($kullaniciYetki['listeleme']=="on"){$listelemeOzelYetki=true;}//listeleme için menu gözükme
																	if($kullaniciYetki['silme']=="on"){$silmeOzelYetki=true;}//silmek için menu gözükme
																	if($kullaniciYetki['duzenleme']=="on"){$duzenlemeOzelYetki=true;}//duzenlemeiçin menu gözükme
																	if($kullaniciYetki['ekleme']=="on"){$eklemeOzelYetki=true;}//ekleme için menu gözükme
																	if($kullaniciYetki['excel']=="on"){$excelOzelYetki=true;}//ekleme için menu gözükme
																}
															}
														}else{//özel yetkisi var ise herşey serbest
															$menuYetki=true;
															$listelemeOzelYetki=true;
															$silmeOzelYetki=true;
															$duzenlemeOzelYetki=true;
															$eklemeOzelYetki=true;
															$excelOzelYetki=true;
														}
														if($menuYetki){
															?>
															<div class="col-md-3 col-sm-6" style="padding-top: 10px;">
																<p><b><?=$fonk->getPDil($menu['menuAdi'])?></b></p>
																<fieldset style="margin-bottom:0.5rem">
																	<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="tumu_<?=$menu['menuId']?>" id="tumu_<?=$menu['menuId']?>" <?=$tumuTik?> style="position: absolute; opacity: 0;"></div>
																	<label for="tumu_<?=$menu['menuId']?>" class=""><?=$fonk->getPDil("Tümü")?></label>
																</fieldset>
																<?php
																if($listelemeOzelYetki){
																	?>
																	<fieldset>
																		<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="listeleme_<?=$menu['menuId']?>" id="listeleme_<?=$menu['menuId']?>" <?=$listelemeTik?> style="position: absolute; opacity: 0;"></div>
																		<label for="listeleme_<?=$menu['menuId']?>" class=""><?=$fonk->getPDil("Listeleme")?></label>
																	</fieldset>
																<?php } if($eklemeOzelYetki){?>
																	<fieldset>
																		<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="ekleme_<?=$menu['menuId']?>" id="ekleme_<?=$menu['menuId']?>"  <?=$eklemeTik?> style="position: absolute; opacity: 0;"></div>
																		<label for="ekleme_<?=$menu['menuId']?>" class=""><?=$fonk->getPDil("Ekleme")?></label>
																	</fieldset>
																<?php } if($duzenlemeOzelYetki){?>
																	<fieldset>
																		<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="duzenle_<?=$menu['menuId']?>" id="duzenle_<?=$menu['menuId']?>"  <?=$duzenlemeTik?> style="position: absolute; opacity: 0;"></div>
																		<label for="duzenle_<?=$menu['menuId']?>" class=""><?=$fonk->getPDil("Düzenleme")?></label>
																	</fieldset>
																<?php } if($silmeOzelYetki){?>
																	<fieldset>
																		<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="silme_<?=$menu['menuId']?>" id="silme_<?=$menu['menuId']?>"  <?=$silmeTik?> style="position: absolute; opacity: 0;"></div>
																		<label for="silme_<?=$menu['menuId']?>" class=""><?=$fonk->getPDil("Silme")?></label>
																	</fieldset>
																<?php } if($excelOzelYetki){?>
																	<fieldset>
																		<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="excel_<?=$menu['menuId']?>" id="excel_<?=$menu['menuId']?>"  <?=$excelTik?> style="position: absolute; opacity: 0;"></div>
																		<label for="excel_<?=$menu['menuId']?>" class=""><?=$fonk->getPDil("Tam Excel")?></label>
																	</fieldset>
																<?php } ?>
															</div>
															<?php
															$tumuTik="";
															$silmeTik="";
															$duzenlemeTik="";
															$eklemeTik="";
															$listelemeTik="";
															$excelTik="";
														} }
														?>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="kullaniciSifre"><?=$fonk->getPDil("Şifre")?></label>
													<input type="password" id="kullaniciSifre" class="form-control border-primary" placeholder="<?=$fonk->getPDil("Şifre")?>" name="kullaniciSifre"  <?php if($Listeleme[$tabloPrimarySutun]==""){echo 'required';}?>   autocomplete="new-password">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for="kullaniciDurum"><?=$fonk->getPDil("Durumu")?></label>
													<fieldset>
														<div class="float-left">
															<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="kullaniciDurum" name="kullaniciDurum" <?php if($Listeleme['kullaniciDurum']==1){echo 'checked';}?> >
														</div>
													</fieldset>
												</div>
											</div>
											<?php if($Listeleme['kullaniciKayitTarihi']!=null){ ?>
												<div class="col-md-3">
													<div class="form-group">
														<label for="kullaniciDurum"><?=$fonk->getPDil("Kayıt Tarihi")?></label>
														<fieldset>
															<div class="float-left">
																<?=$fonk->sqlToDateTime($Listeleme['kullaniciKayitTarihi']);?>
															</div>
														</fieldset>
													</div>
												</div>
											<?php }?>
										</div>
									</div>
									<div class="form-group" style="text-align: center;margin-top:15px">
										<input type="hidden" name="update" value="<?=$Listeleme[$tabloPrimarySutun]?>">
										<input type="hidden" name="menuId" value="<?=$menuId?>">
										<input type="hidden" name="formdan" value="1">
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
	$('#formpost').submit(function (e) {
		e.preventDefault(); //submit postu kesyoruz
		var data=new FormData(this);
		var sifre=document.getElementById("kullaniciSifre").value;
		if (sifre.length!=0 && sifre.length<6) {
			alert("Şifre 6 Karakterden Kısa Olamaz.");
			return false;
		}
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

	$('input[type=checkbox]').on('ifChanged', function(event) {
		var Id=event.target.id.split("_")[1];
		if (event.target.id.split("_")[0]=="tumu") {
			if (event.target.checked) {
				$('#listeleme_'+Id).iCheck('check');
				$('#ekleme_'+Id).iCheck('check');
				$('#duzenle_'+Id).iCheck('check');
				$('#silme_'+Id).iCheck('check');
				$('#excel_'+Id).iCheck('check');
			}else {
				$('#listeleme_'+Id).iCheck('uncheck');
				$('#ekleme_'+Id).iCheck('uncheck');
				$('#duzenle_'+Id).iCheck('uncheck');
				$('#silme_'+Id).iCheck('uncheck');
				$('#excel_'+Id).iCheck('uncheck');
			}
		}
	});

	$(document).ready(function(){
		setTimeout(() => {
			document.getElementById("kullaniciSifre").value = "";
		},500);
	});
</script>
