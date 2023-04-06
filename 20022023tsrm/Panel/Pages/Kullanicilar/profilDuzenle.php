<?php
include ("../../System/Config.php");

$tableName="Kullanicilar";//tabloadı istenirse burdan değiştirilebilir

$tabloPrimarySutun="kullaniciId";//primarykey sutunu

$baslik="Profil";//başlıkta gözükecek yazı menu adi

$primaryId=$kulBilgi['kullaniciId'];

if($_POST['formdan']!="1"){
	//sayfayı görüntülenme logları
	$fonk->logKayit(6,$_SERVER['REQUEST_URI']."?primaryId=".$primaryId);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
}
//Forumdan gelenler
extract($_POST);//POST parametrelerini değişken olarak çevirir

if($_POST['formdan']=="1"){
	$fonk->csrfKontrol();
	if($kullaniciSifre!=""){
		$parametreler=array(
			'kullaniciAdSoyad' => $kullaniciAdSoyad,
			'kullaniciEmail' => $kullaniciEmail,
			'kullaniciSifre' => hash("sha256", md5($kullaniciSifre))
		);
	}else{
		$parametreler=array(
			'kullaniciAdSoyad' => $kullaniciAdSoyad,
			'kullaniciEmail' => $kullaniciEmail
		);
	}

	if($_POST['update']!=""){
		$varmi = $db->get("Kullanicilar", "*", [
			"kullaniciEmail" => $kullaniciEmail,
			"kullaniciId[!]" => $kulBilgi['kullaniciId']
		]);

		if(!$varmi){
			$fonk->logKayit(2,$tableName.' ; '.$primaryId.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
			///güncelleme
			$query = $db->update($tableName, $parametreler, [
				$tabloPrimarySutun => $primaryId
			]);
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
									<div class="col-md-6">
										<div class="form-group">
											<label for="kullaniciSifre"><?=$fonk->getPDil("Şifre")?></label>
											<input type="password" id="kullaniciSifre" class="form-control border-primary" placeholder="<?=$fonk->getPDil("Şifre")?>" name="kullaniciSifre"  autocomplete="new-password">
										</div>
									</div>
									<?php if($Listeleme['kullaniciSonGirisTarihi']!=null){ ?>
										<div class="col-md-6">
											<div class="form-group">
												<label for="kullaniciSifre"><?=$fonk->getPDil("Son Giriş Tarihi")?></label><br>
												<?php echo $fonk->sqlToDateTime($Listeleme['kullaniciSonGirisTarihi']);?>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group" style="text-align: center;margin-top:15px">
								<input type="hidden" name="update" value="<?=$Listeleme[$tabloPrimarySutun]?>" />
								<input type="hidden" name="menuId" value="<?=$menuId?>" />
								<input type="hidden" name="formdan" value="1" />
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
<?php include("../../Scripts/kayitJs.php");?>
<script type="text/javascript">
$('#formpost').submit(function (e) {
	e.preventDefault(); //submit postu kesyoruz
	var data=new FormData(this);
	var sifre=document.getElementById("kullaniciSifre").value;
	if (sifre.length!=0 && sifre.length<6) {
		alert("<?=$fonk->getPDil("Şifre 6 Karakterden Kısa Olamaz.")?>");
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
$(document).ready(function(){
	setTimeout(() => {
		document.getElementById("kullaniciSifre").value = "";
	},700);
});
</script>
