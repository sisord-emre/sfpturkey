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

	if($_POST['formdan']=="1")
	{
		$fonk->csrfKontrol();
		
		if ($kategoriDurum=="") {
			$kategoriDurum=0;
		}

		if($primaryId!=""){
			//günclelemedeki parametreler
			$parametreler=array(
				'kategoriKodu' => $kategoriKodu,
				'kategoriDurum' => $kategoriDurum
			);
		}
		else{
			//eklemedeki parametreler
			$parametreler=array(
				'kategoriKodu' => $kategoriKodu,
				'kategoriSirasi' => 0,
				'kategoriUstMenuId' => 0,
				'kategoriDurum' => $kategoriDurum,
				'kategoriKayitTarihi' => date("Y-m-d H:i:s")
			);
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
		$itemTableName="KategoriDilBilgiler";
		$dilList = $db->select("Diller", "*");
		foreach($dilList as $dil)
		{
			$itemPrimaryId=$_POST["kategoriDilBilgiId-".$dil["dilId"]];//primary sutun
			if ($_POST["kategoriDilBilgiDurum-".$dil["dilId"]]=="") {
				$_POST["kategoriDilBilgiDurum-".$dil["dilId"]]=0;
			}
			$itemPar=array(
				'kategoriDilBilgiKategoriId' => $primaryId,
				'kategoriDilBilgiDilId' => $dil["dilId"],
				'kategoriDilBilgiBaslik' => $_POST["kategoriDilBilgiBaslik-".$dil["dilId"]],
				'kategoriDilBilgiSlug' => $_POST["kategoriDilBilgiSlug-".$dil["dilId"]],
				'kategoriDilBilgiDurum' => $_POST["kategoriDilBilgiDurum-".$dil["dilId"]]
			);
			if ($itemPrimaryId!="") {
				$fonk->logKayit(2,$itemTableName.' ; '.$itemPrimaryId.' ; '.json_encode($itemPar));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				///güncelleme
				$query = $db->update($itemTableName, $itemPar, [
					"kategoriDilBilgiId" => $itemPrimaryId
				]);
			}
			else {
				$fonk->logKayit(1,$itemTableName.' ; '.json_encode($itemPar));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				///ekleme
				$query = $db->insert($itemTableName, $itemPar);
			}
		}
		$primaryId="";
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
		$Listeleme['kategoriKodu']=mt_rand(100000000,999999999);
	}

	
	?>
	<!-- Basic form layout section start -->
	<section id="basic-form-layouts">
		<div class="row match-height">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="basic-layout-colored-form-control"><?=$fonk->getPDil($baslik)?></h4>
						
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
												<label for="kategoriKodu"><?=$fonk->getPDil("Kodu")?></label>
												<input type="text" class="form-control border-primary" id="kategoriKodu" name="kategoriKodu" value="<?=$Listeleme['kategoriKodu']?>" autocomplete="off" readonly required>
											</div>
										</div>
									
										<div class="col-md-3">
											<div class="form-group">
												<label for="kategoriDurum"><?=$fonk->getPDil("Durumu")?></label>
												<fieldset>
													<div class="float-left">
														<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="kategoriDurum" name="kategoriDurum" value="1" <?php if($Listeleme['kategoriDurum']==1){echo 'checked';}?> >
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
													$item = $db->get("KategoriDilBilgiler", "*", [
														"kategoriDilBilgiKategoriId" => $Listeleme['kategoriId'],
														"kategoriDilBilgiDilId" => $dil["dilId"]
													]);
													?>
													<input type="hidden" name="kategoriDilBilgiId-<?=$dil["dilId"]?>" id="kategoriDilBilgiId-<?=$dil["dilId"]?>" value="<?=$item["kategoriDilBilgiId"]?>" />
													<div id="headingCollapse64" data-toggle="collapse" data-target="#listDil-<?=$dil["dilId"]?>" class="card-header mt-1 border-info pointer" aria-expanded="true">
														<b><?=$dil["dilAdi"]?></b>
													</div>
													<div id="listDil-<?=$dil["dilId"]?>" role="tabpanel" aria-labelledby="headingCollapse64" class="border-info no-border-top card-collapse collapse <?php if($item["kategoriDilBilgiDurum"]==1){echo "show";}?>" aria-expanded="false">
														<div class="card-content">
															<div class="card-body">
																<div class="row">
																	<div class="col-md-5">
																		<div class="form-group">
																			<label for="kategoriDilBilgiBaslik-<?=$dil["dilId"]?>"><?=$fonk->getPDil("Başlık")?><small style="color:red;margin-left:1rem">*</small></label>
																			<input type="text" onkeyup="toSeo('kategoriDilBilgiBaslik-<?=$dil["dilId"]?>','kategoriDilBilgiSlug-<?=$dil["dilId"]?>')" class="form-control border-primary" id="kategoriDilBilgiBaslik-<?=$dil["dilId"]?>" name="kategoriDilBilgiBaslik-<?=$dil["dilId"]?>" value="<?=$item['kategoriDilBilgiBaslik']?>" autocomplete="off">
																		</div>
																	</div>
																	<div class="col-md-5">
																		<div class="form-group">
																			<label for="kategoriDilBilgiSlug-<?=$dil["dilId"]?>"><?=$fonk->getPDil("Link")?><small style="color:red;margin-left:1rem">*</small></label>
																			<input type="text" class="form-control border-primary" id="kategoriDilBilgiSlug-<?=$dil["dilId"]?>" name="kategoriDilBilgiSlug-<?=$dil["dilId"]?>" value="<?=$item['kategoriDilBilgiSlug']?>" autocomplete="off" readonly>
																		</div>
																	</div>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label for="kategoriDilBilgiDurum-<?=$dil["dilId"]?>"><?=$fonk->getPDil("Durumu")?></label>
																			<fieldset>
																				<div class="float-left">
																					<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="kategoriDilBilgiDurum-<?=$dil["dilId"]?>" name="kategoriDilBilgiDurum-<?=$dil["dilId"]?>" value="1" <?php if($item['kategoriDilBilgiDurum']==1){echo 'checked';}?> >
																				</div>
																			</fieldset>
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

			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="basic-layout-colored-form-control"><?=$fonk->getPDil("Kategori Düzeni")?></h4>
						<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
						<div class="heading-elements">

						</div>
					</div>
					<div class="card-content collapse show">
						<div class="card-body" id="duzen">

						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
	<!-- // Basic form layout section end -->

<?php } include("../../Scripts/kayitJs.php");?>
<link rel="stylesheet" type="text/css" href="Assets/nestable.css">
<script src="Assets/jquery.nestable.js"></script>
<script type="text/javascript">
var duzenList="";
$(document).ready(function(){
	Duzen();
});

function Duzen() {
	var data=new FormData();
	data.append("menuId",'<?=$menuId?>');
	data.append("duzenlemeLink",'<?=$tableName.'/'.strtolower($tableName).'Kayit.php'?>');
	data.append("eklemeYetki",'<?=$eklemeYetki?>');
	data.append("duzenlemeYetki",'<?=$duzenlemeYetki?>');
	data.append("silmeYetki",'<?=$silmeYetki?>');
	<?php if ($listelemeYetki) { ?>
		$.ajax({
			type: "POST",
			url: "Pages/Kategoriler/duzen.php",
			data:data,
			contentType:false,
			processData:false,
			success: function(res){
				$('#duzen').html(res);
				nestableInit();
			},
			error: function (jqXHR, status, errorThrown) {
				alert("Result: "+status+" Status: "+jqXHR.status);
			}
		});
		<?php } ?>
	}

	function nestableInit(){
		var updateOutput = function(e)
		{
			var list   = e.length ? e : $(e.target),output = list.data('output');
			if (window.JSON) {
				duzenList=window.JSON.stringify(list.nestable('serialize'));
			} else {
				duzenList='JSON browser support required for this demo.';
			}
		};
		$('#nestable').nestable({
			maxDepth: 3,
			group: 1
		})
		.on('change', updateOutput);
		updateOutput($('#nestable'));
		$('#nestable3').nestable();
	}

	function DuzenKayit() {
		var data=new FormData();
		data.append("duzenList",duzenList);
		$.ajax({
			type: "POST",
			url: "Pages/Kategoriler/duzenKayit.php",
			data:data,
			contentType:false,
			processData:false,
			success: function(res){
				if(res=='1'){
					toastr.success('<?=$fonk->getPDil("Güncelleme Sağlandı.")?>');
				}else{
					alert(res);
					Duzen();
				}
			},
			error: function (jqXHR, status, errorThrown) {
				alert("Result: "+status+" Status: "+jqXHR.status);
			}
		});
	}

	function DuzenSil(sil){
		if(confirm('<?=$fonk->getPDil("Silmek İstediğinize Emin misiniz ?")?>')) {
			$.ajax({
				type: "POST",
				url: "Pages/Kategoriler/duzenSil.php",
				data:{'sil':sil},
				success: function(res){
					if (res==1) {
						document.getElementById('duzenSatir-'+sil).style.display="none";
					}else {
						alert(res);
						menuDuzen();
					}
				},
				error: function (jqXHR, status, errorThrown) {
					alert("Result: "+status+" Status: "+jqXHR.status);
				}
			});
		}
	}

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

	function ExceleAktarma(menuId){
		var data=new FormData();
		data.append("menuId",menuId);
		data.append("ExceleAktar",'ok');
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
	}
</script>
