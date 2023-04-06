<?php
include ("../../System/Config.php");

$menuId=$_POST['menuId'];//menu id alınıyor

///menu bilgileri alınıyor
$hangiMenu = $db->get("Menuler", "*", [
	"menuUstMenuId" => $menuId,
	"menuOzelGorunuruk" =>	1,
	"menuTipi" =>	1 //kayıt için 1 listeleme için 2 diğer bloglar içim 3 yazılmalı****
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

	$duzenlemeBlogsi=$tableName.'/'.strtolower($tableName).'Kayit.php';
	$listelemeBlogsi=$tableName."/".strtolower($tableName)."Listeleme.php";

	$primaryId=$_POST['update'];//düzenle isteği ile gelen

	if($_POST['formdan']!="1"){
		//blogyı görüntülenme logları
		$fonk->logKayit(6,$_SERVER['REQUEST_URI']."?primaryId=".$primaryId);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
	}

	////güncllenecek parametreler***
	//Forumdan gelenler
	extract($_POST);//POST parametrelerini değişken olarak çevirir
	////güncllenecek parametreler***
	$x=585;
	$y=390;
	if($_POST['formdan']=="1"){
		$fonk->csrfKontrol();
		$gorselAdi="blog_".mt_rand();
		$kontrol=$fonk->imageResizeUpload($_FILES['blogGorsel'],'../../../Images/Blog/',$gorselAdi,$x,$y,png);//boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner

		if ($blogDurum==""){
			$blogDurum=0;
		}
		if($primaryId!=""){
			//günclelemedeki parametreler
			$parametreler=array(
				'blogDurum' => $blogDurum,
				'blogSirasi' => $blogSirasi,
				'blogBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/Blog/"
			);
		}else{
			//eklemedeki parametreler
			$parametreler=array(
				'blogKodu' => $blogKodu,
				'blogSirasi' => $blogSirasi,
				'blogDurum' => $blogDurum,
				'blogBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/Blog/",
				'blogKayitTarihi' => date("Y-m-d H:i:s")
			);
		}

		if($kontrol==1){//eğer duruma göre boş bırakılabiliyor ise parametre, sonradan arraye eklenir
			$parametreler=array_merge($parametreler,array('blogGorsel' => $gorselAdi.".png"));
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

		//dile göre değerlerin kayıt edilmesi
		$itemTableName="BlogDilBilgiler";
		$dilList = $db->select("Diller", "*");
		foreach($dilList as $dil){
			$itemPrimaryId=$_POST["blogDilBilgiId-".$dil["dilId"]];//primary sutun
			if ($_POST["blogDilBilgiDurum-".$dil["dilId"]]=="") {
				$_POST["blogDilBilgiDurum-".$dil["dilId"]]=0;
			}
			$itemPar=array(
				'blogDilBilgiBlogId' => $primaryId,
				'blogDilBilgiDilId' => $dil["dilId"],
				'blogDilBilgiBaslik' => $_POST["blogDilBilgiBaslik-".$dil["dilId"]],
				'blogDilBilgiSlug' => $_POST["blogDilBilgiSlug-".$dil["dilId"]],
				'blogDilBilgiDescription' => $_POST["blogDilBilgiDescription-".$dil["dilId"]],
				'blogDilBilgiIcerik' => $_POST["blogDilBilgiIcerik-".$dil["dilId"]],
				'blogDilBilgiDurum' => $_POST["blogDilBilgiDurum-".$dil["dilId"]]
			);
			if ($itemPrimaryId!="") {
				$fonk->logKayit(2,$itemTableName.' ; '.$itemPrimaryId.' ; '.json_encode($itemPar));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				///güncelleme
				$query = $db->update($itemTableName, $itemPar, [
					"blogDilBilgiId" => $itemPrimaryId
				]);
			}else {
				$fonk->logKayit(1,$itemTableName.' ; '.json_encode($itemPar));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				///ekleme
				$query = $db->insert($itemTableName, $itemPar);
			}
		}
		//!dile göre değerlerin kayıt edilmesi

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
	}else {
		$Listeleme['blogKodu']=mt_rand(100000000,999999999);
		if ($_SESSION["islemDilId"]!="") {
			$Listeleme['blogDilId']=$_SESSION["islemDilId"];
		}
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
							<?php if($eklemeYetki){?><button type="button" onclick="YeniEkle('<?=$menuId?>','<?=$duzenlemeBlogsi?>');" class="btn mr-1 btn-primary btn-sm"><i class="la la-plus-circle"></i></button><?php } ?>
							<?php if($listelemeYetki){?><button type="button" onclick="BlogGetir('<?=$menuId?>','<?=$listelemeBlogsi?>');" class="btn mr-1 btn-primary btn-sm"><i class="la la-th-list"></i> <?=$fonk->getPDil("Listeleme")?></button><?php } ?>
						</div>
					</div>
					<div class="card-content collapse show">
						<div class="card-body">

							<form id="formpost" class="form" action="" method="post">
								<div class="form-body">

									<!-- Güncellenecek Kısımlar -->
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="blogKodu"><?=$fonk->getPDil("Kodu")?></label>
												<input type="text" class="form-control border-primary" id="blogKodu" name="blogKodu" value="<?=$Listeleme['blogKodu']?>" autocomplete="off" readonly required>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="blogGorsel"><?=$fonk->getPDil("Görsel")?> <?=$fonk->getPDil("(Önerilen:".$x."x".$y."px)")?></label>
												<div class="custom-file">
													<input type="file" class="custom-file-input" name="blogGorsel" id="blogGorsel" accept=".png">
													<label class="custom-file-label" name="blogGorsel" id="blogGorsel" for="blogGorsel" aria-describedby="blogGorsel"><?=$fonk->getPDil("Dosya Seçiniz")?></label>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="blogSirasi"><?=$fonk->getPDil("Sırası")?></label>
												<input type="text" class="form-control border-primary" id="blogSirasi" name="blogSirasi" value="<?=$Listeleme['blogSirasi']?>" autocomplete="off" required>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="blogDurum"><?=$fonk->getPDil("Durumu")?></label>
												<fieldset>
													<div class="float-left">
														<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="blogDurum" name="blogDurum" value="1" <?php if($Listeleme['blogDurum']==1){echo 'checked';}?> >
													</div>
												</fieldset>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">
											<div class="card collapse-icon accordion-icon-rotate">
												<?php
												$dilList = $db->select("Diller", "*");
												foreach($dilList as $dil){
													$item = $db->get("BlogDilBilgiler", "*", [
														"blogDilBilgiBlogId" => $Listeleme['blogId'],
														"blogDilBilgiDilId" => $dil["dilId"]
													]);
													?>
													<input type="hidden" name="blogDilBilgiId-<?=$dil["dilId"]?>" id="blogDilBilgiId-<?=$dil["dilId"]?>" value="<?=$item["blogDilBilgiId"]?>" />
													<div id="headingCollapse64" data-toggle="collapse" data-target="#listDil-<?=$dil["dilId"]?>" class="card-header mt-1 border-info pointer" aria-expanded="true">
														<b><?=$dil["dilAdi"]?></b>
													</div>
													<div id="listDil-<?=$dil["dilId"]?>" role="tabpanel" aria-labelledby="headingCollapse64" class="border-info no-border-top card-collapse collapse <?php if($item["blogDilBilgiDurum"]==1){echo "show";}?>" aria-expanded="false">
														<div class="card-content">
															<div class="card-body">
																<div class="row">
																	<div class="col-md-5">
																		<div class="form-group">
																			<label for="blogDilBilgiBaslik-<?=$dil["dilId"]?>"><?=$fonk->getPDil("Başlık")?><small style="color:red;margin-left:1rem">*</small></label>
																			<input type="text" onkeyup="toSeo('blogDilBilgiBaslik-<?=$dil["dilId"]?>','blogDilBilgiSlug-<?=$dil["dilId"]?>')" class="form-control border-primary" id="blogDilBilgiBaslik-<?=$dil["dilId"]?>" name="blogDilBilgiBaslik-<?=$dil["dilId"]?>" value="<?=$item['blogDilBilgiBaslik']?>" autocomplete="off">
																		</div>
																	</div>
																	<div class="col-md-5">
																		<div class="form-group">
																			<label for="blogDilBilgiSlug-<?=$dil["dilId"]?>"><?=$fonk->getPDil("Link")?><small style="color:red;margin-left:1rem">*</small></label>
																			<input type="text" class="form-control border-primary" id="blogDilBilgiSlug-<?=$dil["dilId"]?>" name="blogDilBilgiSlug-<?=$dil["dilId"]?>" value="<?=$item['blogDilBilgiSlug']?>" autocomplete="off" readonly>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="blogDilBilgiDurum-<?=$dil["dilId"]?>"><?=$fonk->getPDil("Durumu")?></label>
																			<fieldset>
																				<div class="float-left">
																					<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="blogDilBilgiDurum-<?=$dil["dilId"]?>" name="blogDilBilgiDurum-<?=$dil["dilId"]?>" value="1" <?php if($item['blogDilBilgiDurum']==1){echo 'checked';}?> >
																				</div>
																			</fieldset>
																		</div>
																	</div>
																	<div class="col-md-12">
																		<div class="form-group">
																			<label for="blogDilBilgiDescription-<?=$dil["dilId"]?>"><?=$fonk->getPDil("Description / Kısa Açıklama")?></label>
																			<textarea class="form-control" id="blogDilBilgiDescription-<?=$dil["dilId"]?>" name="blogDilBilgiDescription-<?=$dil["dilId"]?>" rows="3" placeholder="..."><?=$item['blogDilBilgiDescription']?></textarea>
																		</div>
																	</div>
																	<div class="col-md-12">
																		<div class="form-group">
																			<label for="blogDilBilgiIcerik-<?=$dil["dilId"]?>"><?=$fonk->getPDil("İçerik")?></label>
																			<textarea class="editorCk" id="blogDilBilgiIcerik-<?=$dil["dilId"]?>" name="blogDilBilgiIcerik-<?=$dil["dilId"]?>">
																				<?=$item['blogDilBilgiIcerik']?>
																			</textarea>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												<?php } ?>
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
$(".editorCk").each(function () {
	let editorId = $(this).attr('id');
	CKEDITOR.replace(editorId, { //ckeditor kullanıldığında açılır
		height: '350px',
		extraPlugins: 'forms',
		uiColor: '#CCEAEE',
		//Dosya Yöneticisi resim gözat için
		filebrowserBrowseUrl : 'Assets/app-assets/fileman/index.html',// Öntanımlı Dosya Yöneticisi
		filebrowserImageBrowseUrl : 'Assets/app-assets/fileman/index.html?type=image',// Sadece Resim Dosyalarını Gösteren Dosya Yöneticisi
		removeDialogTabs : 'link:upload;image:upload' // Upload işlermlerini dosya Yöneticisi ile yapacağımız için upload butonlarını kaldırıyoruz
	});
});
$('#formpost').submit(function (e) {
	e.preventDefault(); //submit postu kesyoruz
	var data=new FormData(this);
	$(".editorCk").each(function () {
		let editorId = $(this).attr('id');
		data.append(editorId, CKEDITOR.instances[editorId].getData());//ckeditor kullanılacağı zaman açılır 'ckeditor' yazan kısmı post keyidir
	});
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
