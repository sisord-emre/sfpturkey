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
		if($primaryId!=""){
			//günclelemedeki parametreler
			$parametreler=array(
				'siteMenuBaslik' => $siteMenuBaslik,
				'siteMenuLink' => $siteMenuLink,
				'siteMenuDilId' => $siteMenuDilId
			);
		}else{
			//eklemedeki parametreler
			$parametreler=array(
				'siteMenuUstMenuId' => 0,
				'siteMenuBaslik' => $siteMenuBaslik,
				'siteMenuLink' => $siteMenuLink,
				'siteMenuSirasi' => 0,
				'siteMenuDilId' => $siteMenuDilId
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
		$primaryId="";
	}
	echo "<script>$('#ustYazi').html('&nbsp;-&nbsp;'+'".$fonk->getPDil($baslik)."');</script>";//Başlık Güncelleniyor
	//update ise bilgiler getiriliyor
	if($primaryId!=""){
		$Listeleme = $db->get($tableName, "*", [
			$tabloPrimarySutun => $primaryId
		]);
	}else {
		if ($_SESSION["islemDilId"]!="") {
			$Listeleme['siteMenuDilId']=$_SESSION["islemDilId"];
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
						</div>
					</div>
					<div class="card-content collapse show">
						<div class="card-body">

							<form id="formpost" class="form" action="" method="post">
								<div class="form-body">

									<!-- Güncellenecek Kısımlar -->
									<div class="row">
										<div class="col-md-5">
											<div class="form-group">
												<label for="siteMenuBaslik"><?=$fonk->getPDil("Başlık")?><small style="color:red;margin-left:1rem">*</small></label>
												<input type="text" class="form-control border-primary" id="siteMenuBaslik" name="siteMenuBaslik" value="<?=$Listeleme['siteMenuBaslik']?>" autocomplete="off" required>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label for="siteMenuLink"><?=$fonk->getPDil("Link")?><small style="color:red;margin-left:1rem">*</small></label>
												<input type="text" class="form-control border-primary" id="siteMenuLink" name="siteMenuLink" value="<?=$Listeleme['siteMenuLink']?>" autocomplete="off" required>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="siteMenuDilId"><?=$fonk->getPDil("Diller")?><small style="color:red;margin-left:1rem">*</small></label>
												<select class="select2 form-control block" name="siteMenuDilId" id="siteMenuDilId" required>
													<?php
													$sorguList = $db->select("Diller","*");
													foreach($sorguList as $sorgu){
														?>
														<option value="<?=$sorgu['dilId']?>" <?php if($sorgu['dilId']==$Listeleme['siteMenuDilId']){echo " selected";}?>><?=$sorgu['dilAdi']?></option>
													<?php } ?>
												</select>
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
						<h4 class="card-title" id="basic-layout-colored-form-control"><?=$fonk->getPDil("Menü Düzeni")?></h4>
						<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
						<div class="heading-elements">
							<select class="select2 form-control block" name="siteMenuDilIdDuzen" id="siteMenuDilIdDuzen" onchange="menuDuzen();" required>
								<?php
								$sorguList = $db->select("Diller","*");
								foreach($sorguList as $sorgu){
									?>
									<option value="<?=$sorgu['dilId']?>" <?php if($sorgu['dilId']==$Listeleme['siteMenuDilId']){echo " selected";}?>><?=$sorgu['dilAdi']?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="card-content collapse show">
						<div class="card-body" id="menuDuzen">

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
var siteMenuDuzen="";
$(document).ready(function(){
	menuDuzen();
});

function menuDuzen() {
	var siteMenuDilId=$('#siteMenuDilIdDuzen').val();
	var data=new FormData();
	data.append("menuId",'<?=$menuId?>');
	data.append("siteMenuDilId",siteMenuDilId);
	data.append("eklemeYetki",'<?=$eklemeYetki?>');
	data.append("duzenlemeYetki",'<?=$duzenlemeYetki?>');
	data.append("silmeYetki",'<?=$silmeYetki?>');
	<?php if ($listelemeYetki) { ?>
		$.ajax({
			type: "POST",
			url: "Pages/SiteMenuler/menuDuzen.php",
			data:data,
			contentType:false,
			processData:false,
			success: function(res){
				$('#menuDuzen').html(res);
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
			siteMenuDuzen=window.JSON.stringify(list.nestable('serialize'));
		} else {
			siteMenuDuzen='JSON browser support required for this demo.';
		}
	};
	$('#nestable').nestable({
		group: 1
	})
	.on('change', updateOutput);
	updateOutput($('#nestable'));
	$('#nestable3').nestable();
}

function menuDuzenKayit() {
	var data=new FormData();
	data.append("siteMenuDuzen",siteMenuDuzen);
	$.ajax({
		type: "POST",
		url: "Pages/SiteMenuler/menuDuzenKayit.php",
		data:data,
		contentType:false,
		processData:false,
		success: function(res){
			menuDuzen();
			if(res=='1'){
				toastr.success('<?=$fonk->getPDil("Güncelleme Sağlandı.")?>');
			}else{
				alert(res);
			}
		},
		error: function (jqXHR, status, errorThrown) {
			alert("Result: "+status+" Status: "+jqXHR.status);
		}
	});
}

function menuSil(sil){
	if(confirm('<?=$fonk->getPDil("Silmek İstediğinize Emin misiniz ? Eğer Alt Menüler Var İse Onlarda Silinecektir.")?>')) {
		$.ajax({
			type: "POST",
			url: "Pages/SiteMenuler/menuSil.php",
			data:{'sil':sil},
			success: function(res){
				if (res==1) {
					document.getElementById('menuSatir-'+sil).style.display="none";
				}else {
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
</script>
