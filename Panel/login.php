<?php
include ("System/Config.php");

if($_GET['exit']=="ok"){//oturumu kapatma
	unset($_SESSION['SessionKey']);
	session_regenerate_id(true); //sessionId sıfırlamak için
	session_destroy();
	session_start();
	$fonk->yonlendir($loginUrl);
	exit;
}

if(isset($_SESSION['SessionKey']) || $_SESSION['SessionKey']!=""){
	$fonk->yonlendir("./");
	exit;
}

if($_POST){
	$fonk->csrfKontrol();
	//recaptcha aktif ise kontroller yapılıyor
	// if($sabitB['sabitBilgiPublicRecaptcha']!="" && $sabitB['sabitBilgiPrivateRecaptcha']!=""){ //panelden recaptcha ayarlandıysa
	// 	if(isset($_POST['g-recaptcha-response'])){
	// 		$captcha=$_POST['g-recaptcha-response'];
	// 	}
	// 	if(!$captcha){
	// 		$hata = '<small style="color:red;">'.$fonk->getPDil("* Recaptcha Doğrulanamadı Lütfen Tekrar Deneyiniz.").'</small>';
	// 	}
	// 	$secretKey = $sabitB['sabitBilgiPrivateRecaptcha'];
	// 	$ip = $_SERVER['REMOTE_ADDR'];
	// 	// post request to server
	// 	$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
	// 	$response = file_get_contents($url);
	// 	$responseKeys = json_decode($response,true);
	// 	// should return JSON with success as true
	// 	if(!$responseKeys["success"]) {
	// 		$hata = '<small style="color:red;">'.$fonk->getPDil("* Recaptcha Doğrulanamadı Lütfen Tekrar Deneyiniz.").'</small>';
	// 	}
	// }

	if(true){//bir problem yok ise giriş yap
	
		$kullaniciGiris = $db->get("Kullanicilar", "*", [
			"kullaniciEmail" => $fonk->injKontrol($_POST['mail'],""),
			"kullaniciSifre" => hash("sha256", md5($_POST['sifre'])),
			"kullaniciDurum" =>	1,
			"kullaniciGizle" => 0
		]);

		if($kullaniciGiris){
			session_regenerate_id(true); //sessionId sıfırlamak için
			$sessionKey=uniqid();
			$_SESSION['SessionKey']=$sessionKey;

			if ($sabitB['sabitBilgiLog']==1) {
				$log = $db->insert("Log", [
					"logKullaniciId" => intval($kullaniciGiris['kullaniciId']),//oturum id
					"logIslemTipi" => 4,//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
					"logIslem" => 'Login Ip: '.$_SERVER['REMOTE_ADDR'],//yapılan işlme parametreleri
					"logTarih" => date("Y-m-d H:i:s")//yapılan zaman
				]);
			}

			$query = $db->update("Kullanicilar",[
				"kullaniciSessionKey" => $sessionKey,
				'kullaniciSonGirisTarihi' => date("Y-m-d H:i:s")
			],[
				"kullaniciId" => $kullaniciGiris['kullaniciId']
			]);

			$fonk->yonlendir("./");
			exit;
		}else{
			$hata = '<small style="color:red;">'.$fonk->getPDil("* Mail Yada Şifre Hatalı. Tekrar Deneyiniz.").'</small>';
		}
	}
}
?>
<!DOCTYPE html>
<html class="loading" lang="tr" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="<?=$sabitB['sabitBilgiDescription']?>">
	<meta name="keywords" content="<?=$sabitB['sabitBilgiKeywords']?>">
	<meta name="author" content="<?=$sabitB['sabitBilgiLisansFirmaAdi']?>">
	<title><?=$sabitB['sabitBilgiTitle']?></title>
	<link rel="apple-touch-icon" href="Images/Ayarlar/favicon.png">
	<link rel="shortcut icon" type="image/x-icon" href="Images/Ayarlar/favicon.png">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
	<!-- BEGIN: Vendor CSS-->
	<link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/vendors.min.css">
	<link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/forms/icheck/icheck.css">
	<link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/forms/icheck/custom.css">
	<!-- END: Vendor CSS-->
	<!-- BEGIN: Theme CSS-->
	<link rel="stylesheet" type="text/css" href="Assets/app-assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="Assets/app-assets/css/bootstrap-extended.css">
	<link rel="stylesheet" type="text/css" href="Assets/app-assets/css/colors.css">
	<link rel="stylesheet" type="text/css" href="Assets/app-assets/css/components.css">
	<!-- END: Theme CSS-->
	<!-- BEGIN: Page CSS-->
	<link rel="stylesheet" type="text/css" href="Assets/app-assets/css/core/menu/menu-types/vertical-menu-modern.css">
	<link rel="stylesheet" type="text/css" href="Assets/app-assets/css/core/colors/palette-gradient.css">
	<link rel="stylesheet" type="text/css" href="Assets/app-assets/css/pages/login-register.css">
	<!-- END: Page CSS-->

	<!-- BEGIN: Custom CSS-->
	<link rel="stylesheet" type="text/css" href="Assets/assets/css/style.css">
	<!-- END: Custom CSS-->
	<script src='https://www.google.com/recaptcha/api.js'></script>

	<meta name="robots" content="noindex, nofollow">
	<meta name="googlebot" content="noindex, nofollow">
</head>
<!-- END: Head-->

<!-- BEGIN: recaptcha mobil-->
<body class="vertical-layout vertical-menu-modern 1-column  bg-full-screen-image blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
	<!-- BEGIN: Content-->
	<div class="app-content content">
		<div class="content-wrapper">
			<div class="content-header row mb-1"></div>
			<div class="content-body">
				<section class="flexbox-container">
					<div class="col-12 d-flex align-items-center justify-content-center">
						<div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">
							<div class="card border-grey border-lighten-3 px-1 py-1 m-0">
								<div class="card-header border-0">
									<div class="card-title text-center">
										<img src="Images/Ayarlar/logo.png" alt="logo" style="max-width: 100%;">
									</div>
								</div>
								<div class="card-content">
									<p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1"><span><?=$fonk->getPDil("Yetkili Giriş")?></span></p>
									<div class="card-body">
										<form class="form-horizontal" action="" method="post" novalidate>
											<fieldset class="form-group position-relative has-icon-left">
												<input type="email" class="form-control" id="user-name" placeholder="<?=$fonk->getPDil("Email Adresiniz")?>" name="mail" required>
												<div class="form-control-position">
													<i class="ft-user"></i>
												</div>
											</fieldset>
											<fieldset class="form-group position-relative has-icon-left">
												<input type="password" class="form-control" id="user-password" placeholder="<?=$fonk->getPDil("Şifreniz")?>" name="sifre" required>
												<div class="form-control-position">
													<i class="la la-key"></i>
												</div>
											</fieldset>
											<!-- <?php if($sabitB['sabitBilgiPublicRecaptcha']!="" && $sabitB['sabitBilgiPrivateRecaptcha']!=""){ ?>
												<div class="form-group row">
													<div class="col-sm-12 col-12 text-center text-sm-center pr-0 recaptchaMobil">
														<div class="g-recaptcha" data-sitekey="<?=$sabitB['sabitBilgiPublicRecaptcha']?>" style="transform:scale(0.77);-webkit-transform:scale(0.77);text-align: -webkit-center;"></div>
													</div>
												</div>
											<?php } echo $hata;?> -->
											<input type="hidden" name="token" value="<?=$_SESSION['token']?>" />
											<button type="submit" class="btn btn-outline-info btn-block"><i class="ft-unlock"></i> <?=$fonk->getPDil("Giriş Yap")?></button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
	<!-- END: Content-->

	<!-- BEGIN: Vendor JS-->
	<script src="Assets/app-assets/vendors/js/vendors.min.js"></script>
	<!-- BEGIN Vendor JS-->
	<!-- BEGIN: Page Vendor JS-->
	<script src="Assets/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"></script>
	<script src="Assets/app-assets/vendors/js/forms/icheck/icheck.min.js"></script>
	<!-- END: Page Vendor JS-->
	<!-- BEGIN: Theme JS-->
	<script src="Assets/app-assets/js/core/app-menu.js"></script>
	<script src="Assets/app-assets/js/core/app.js"></script>
	<!-- END: Theme JS-->
	<!-- BEGIN: Page JS-->
	<script src="Assets/app-assets/js/scripts/forms/form-login-register.js"></script>
	<!-- END: Page JS-->
	<script> sessionStorage.setItem("menuId",""); sessionStorage.setItem("sayfa",""); sessionStorage.setItem("duzenleId",""); sessionStorage.setItem("dPage",""); sessionStorage.setItem("dSearch",""); sessionStorage.setItem("dLink",""); sessionStorage.setItem("editId",""); sessionStorage.setItem("orderDt","");</script>
</body>
<!-- END: Body-->
</html>
