<?php
include("../../System/Config.php");

$menuId = $_POST['menuId']; //menu id alınıyor

///menu bilgileri alınıyor
$hangiMenu = $db->get("Menuler", "*", [
	"menuUstMenuId" => $menuId,
	"menuOzelGorunuruk" =>	1,
	"menuTipi" =>	1 //kayıt için 1 listeleme için 2 diğer sayfalar içim 3 yazılmalı****
]);

for ($i = 0; $i < Count($kullaniciYetkiler); $i++) { //kullanıcının yetkilerini sorguluyoruz
	$kullaniciYetki = json_decode($kullaniciYetkiler[$i], true);

	if ($kullaniciYetki['menuYetkiID'] == $menuId) { //menu id

		if ($kullaniciYetki['listeleme'] == "on") {
			$listelemeYetki = true;
		} //listeleme

		if ($kullaniciYetki['ekleme'] == "on") {
			$eklemeYetki = true;
		} //ekleme

		if ($kullaniciYetki['silme'] == "on") {
			$silmeYetki = true;
		} //silme

		if ($kullaniciYetki['duzenleme'] == "on") {
			$duzenlemeYetki = true;
		} //duzenleme

	}
}
if (!$eklemeYetki && !$duzenlemeYetki) {
	//yetki yoksa gözükecek yazi
	echo '<div class="alert alert-icon-right alert-warning alert-dismissible mb-2" role="alert">
	<span class="alert-icon"><i class="la la-warning"></i></span>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">×</span>
	</button>
	<strong>' . $fonk->getPDil("Yetki!") . ' </strong> ' . $fonk->getPDil("Bu Menüye Erişim Yetkiniz Bulunmamaktadır.") . '
	</div>';
} else { //Listeleme Yetkisi Var

	$tableName = $hangiMenu['menuTabloAdi']; //tabloadı istenirse burdan değiştirilebilir

	$tabloPrimarySutun = $hangiMenu['menuTabloPrimarySutun']; //primarykey sutunu

	$baslik = $hangiMenu['menuAdi']; //başlıkta gözükecek yazı menu adi

	$duzenlemeSayfasi = $tableName . '/' . strtolower($tableName) . 'Kayit.php';
	$listelemeSayfasi = $tableName . "/" . strtolower($tableName) . "Listeleme.php";

	$primaryId = $_POST['update']; //düzenle isteği ile gelen

	if ($_POST['formdan'] != "1") {
		//sayfayı görüntülenme logları
		$fonk->logKayit(6, $_SERVER['REQUEST_URI'] . "?primaryId=" . $primaryId); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
	}

	////güncllenecek parametreler***
	//Forumdan gelenler
	extract($_POST); //POST parametrelerini değişken olarak çevirir
	////güncllenecek parametreler***

	$width = 360;
	$height = 460;
	if ($_POST['formdan'] == "1") {
		$fonk->csrfKontrol();
		$gorselAdi = $urunKodu . "-" . mt_rand();

		$gorselPath="";
		array_unshift($kategoriIdList, $parent);
		foreach ($kategoriIdList as $key => $val) {
			$gorselPath .= $val."/";
		}

		$gorselUploadPath = '../../../Images/Urunler/'.$gorselPath;
		$kontrol = $fonk->imageResizeUpload($_FILES['urunGorsel'], $gorselUploadPath, $gorselAdi, 0, 0, jpg); //boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner
		
		$urunDurum = ($urunDurum == "") ? 0 : 1;
		$urunKampanya = ($urunKampanya == "") ? 0 : 1;
		$urunEnCokSatan = ($urunEnCokSatan == "") ? 0 : 1;
		$urunSFPPort = ($urunSFPPort == "") ? 0 : 1;
		$urunSFPPortBirlikte = ($urunSFPPortBirlikte == "") ? 0 : 1;
		$urunSFP28Port = ($urunSFP28Port == "") ? 0 : 1;
		$urunQSFPPort = ($urunQSFPPort == "") ? 0 : 1;
		$urunQSFP28Port = ($urunQSFP28Port == "") ? 0 : 1;
		$urunEndustriyelTip = ($urunEndustriyelTip == "") ? 0 : 1;
		$urun100MegabitRJ45Port = ($urun100MegabitRJ45Port == "") ? 0 : 1;
		$urun1GigabitRJ45Port = ($urun1GigabitRJ45Port == "") ? 0 : 1;
		$urun10GigabitRJ45Port = ($urun10GigabitRJ45Port == "") ? 0 : 1;
		$urun1GSFPPort = ($urun1GSFPPort == "") ? 0 : 1;
		$urun1Metre = ($urun1Metre == "") ? 0 : 1;
		$urun2Metre = ($urun2Metre == "") ? 0 : 1;
		$urun3Metre = ($urun3Metre == "") ? 0 : 1;
		$urun510Metre = ($urun510Metre == "") ? 0 : 1;
		$urun1020Metre = ($urun1020Metre == "") ? 0 : 1;
		$urun2030Metre = ($urun2030Metre == "") ? 0 : 1;
		

		if ($primaryId != "") {
			//günclelemedeki parametreler
			$parametreler = array(
				'urunFiyat' => floatval($urunFiyat),
				'urunParaBirimId' => $urunParaBirimId,
				'urunKdv' => floatval($urunKdv),
				'urunStok' => floatval($urunStok),
				'urunDurum' => $urunDurum,
				'urunModel' => $urunModel,
				'urunSFPPort' => $urunSFPPort,
				'urunSFPPortBirlikte' => $urunSFPPortBirlikte,
				'urunBaseUrl' => $sabitB["sabitBilgiSiteUrl"] . "Images/Urunler/".$gorselPath,
				'urunDataSheetBaseUrl' => $sabitB["sabitBilgiSiteUrl"] . "Dokuman/Sheet/",
				'urunSFP28Port' => $urunSFP28Port,
				'urunQSFPPort' => $urunQSFPPort,
				'urunQSFP28Port' => $urunQSFP28Port,
				'urunEndustriyelTip' => $urunEndustriyelTip,
				'urun100MegabitRJ45Port' => $urun100MegabitRJ45Port,
				'urun1GigabitRJ45Port' => $urun1GigabitRJ45Port,
				'urun10GigabitRJ45Port' => $urun10GigabitRJ45Port,
				'urun1GSFPPort' => $urun1GSFPPort,
				'urun1Metre' => $urun1Metre,
				'urun2Metre' => $urun2Metre,
				'urun3Metre' => $urun3Metre,
				'urun510Metre' => $urun510Metre,
				'urun1020Metre' => $urun1020Metre,
				'urun2030Metre' => $urun2030Metre,
				"urunKampanya" => $urunKampanya,
				"urunEnCokSatan" => $urunEnCokSatan
			);
		} else {
			//eklemedeki parametreler
			$parametreler = array(
				'urunKodu' => $urunKodu,
				'urunFiyat' => floatval($urunFiyat),
				'urunParaBirimId' => $urunParaBirimId,
				'urunKdv' => floatval($urunKdv),
				'urunBegeni' => 0,
				'urunSepetMiktar' => 0,
				'urunSatisMiktar' => 0,
				'urunStok' => floatval($urunStok),
				'urunDurum' => $urunDurum,
				'urunModel' => $urunModel,
				'urunBaseUrl' => $sabitB["sabitBilgiSiteUrl"] . "Images/Urunler/".$gorselPath,
				'urunDataSheetBaseUrl' => $sabitB["sabitBilgiSiteUrl"] . "Dokuman/Sheet/",
				'urunSFPPort' => $urunSFPPort,
				'urunSFPPortBirlikte' => $urunSFPPortBirlikte,
				'urunSFP28Port' => $urunSFP28Port,
				'urunQSFPPort' => $urunQSFPPort,
				'urunQSFP28Port' => $urunQSFP28Port,
				'urunEndustriyelTip' => $urunEndustriyelTip,
				'urun100MegabitRJ45Port' => $urun100MegabitRJ45Port,
				'urun1GigabitRJ45Port' => $urun1GigabitRJ45Port,
				'urun10GigabitRJ45Port' => $urun10GigabitRJ45Port,
				'urun1GSFPPort' => $urun1GSFPPort,
				'urun1Metre' => $urun1Metre,
				'urun2Metre' => $urun2Metre,
				'urun3Metre' => $urun3Metre,
				'urun510Metre' => $urun510Metre,
				'urun1020Metre' => $urun1020Metre,
				'urun2030Metre' => $urun2030Metre,
				"urunKampanya" => $urunKampanya,
				"urunEnCokSatan" => $urunEnCokSatan,
				'urunKayitTarihi' => date("Y-m-d H:i:s")
			);
		}
		
		if ($kontrol == 1) { //eğer duruma göre boş bırakılabiliyor ise parametre, sonradan arraye eklenir
			$parametreler = array_merge($parametreler, array('urunGorsel' => $gorselAdi . ".jpg"));
		}
		$files = array_filter($_FILES['urunDataSheet']['name']); 
		$fileName = $_FILES['urunDataSheet']['name'];
		$tmpFilePath = $_FILES['urunDataSheet']['tmp_name'];
		if ($tmpFilePath != "")
		{
			$newFilePath = "../../../Dokuman/Sheet/".$fileName;
			if(move_uploaded_file($tmpFilePath, $newFilePath)) 
			{
				$parametreler=array_merge($parametreler,array('urunDataSheet' => $fileName));
			}		
		}

		if ($primaryId != "") {
			$fonk->logKayit(2, $tableName . ' ; ' . $primaryId . ' ; ' . json_encode($parametreler)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
			///güncelleme
			
			$query = $db->update($tableName, $parametreler, [
				$tabloPrimarySutun => $primaryId
			]);
		} 
		else {
			$fonk->logKayit(1, $tableName . ' ; ' . json_encode($parametreler)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
			///ekleme
			$query = $db->insert($tableName, $parametreler);
			$primaryId = $db->id();

			// otomatik generic isimli varyant oluşacak
			$varyantTableName = "UrunVaryantlari";
			$itemPar = array(
				'urunVaryantUrunId' => $primaryId,
				'urunVaryantVaryantId' => 20, //generic
				'urunVaryantFiyat' => floatval($urunFiyat),
				'urunVaryantKampanyasizFiyat' => 0,
				'urunVaryantDefaultSecim' => 1
			);
			$urunVaryantKodu = mt_rand(100000000, 999999999);
			$itemPar = array_merge($itemPar, array('urunVaryantKodu' => $urunVaryantKodu));
			$queryAlt = $db->insert($varyantTableName, $itemPar);
			$urunVaryantId = $db->id();

			$itemTableName = "UrunVaryantDilBilgiler";
			$dilList = $db->select("Diller", "*");
			foreach ($dilList as $dil) {
				$itemPrimaryId = $_POST["urunVaryantDilBilgiId-" . $dil["dilId"]]; //primary sutun
				
				if ($_POST["urunDilBilgiDurum-" . $dil["dilId"]] == "") {
					$_POST["urunDilBilgiDurum-" . $dil["dilId"]] = 0;
				}
				$itemPar = array(
					'urunVaryantDilBilgiUrunId' => $primaryId,
					'urunVaryantDilBilgiVaryantId' => $urunVaryantId,
					'urunVaryantDilBilgiDilId' => $dil["dilId"],
					'urunVaryantDilBilgiAdi' => $_POST["urunDilBilgiAdi-" . $dil["dilId"]],
					'urunVaryantDilBilgiSlug' => $_POST["urunDilBilgiSlug-" . $dil["dilId"]],
					'urunVaryantDilBilgiDescription' => "",
					'urunVaryantDilBilgiEtiketler' => "",
					'urunVaryantDilBilgiAciklama' => "",
					'urunVaryantDilBilgiDurum' => 1
				);
				$fonk->logKayit(1, $itemTableName . ' ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				$queryAlt = $db->insert($itemTableName, $itemPar);
			}
			// otomatik sfp isimli varyant oluşacak
		}
		
		//dile göre değerlerin kayıt edilmesi
		$itemTableName = "UrunDilBilgiler";
		$dilList = $db->select("Diller", "*");
		foreach ($dilList as $dil) {
			$itemPrimaryId = $_POST["urunDilBilgiId-" . $dil["dilId"]]; //primary sutun
			if ($_POST["urunDilBilgiDurum-" . $dil["dilId"]] == "") {
				$_POST["urunDilBilgiDurum-" . $dil["dilId"]] = 0;
			}
			$itemPar = array(
				'urunDilBilgiUrunId' => $primaryId,
				'urunDilBilgiDilId' => $dil["dilId"],
				'urunDilBilgiAdi' => $_POST["urunDilBilgiAdi-" . $dil["dilId"]],
				'urunDilBilgiSlug' => $_POST["urunDilBilgiSlug-" . $dil["dilId"]],
				'urunDilBilgiDescription' => $_POST["urunDilBilgiDescription-" . $dil["dilId"]],
				'urunDilBilgiAciklama' => $_POST["urunDilBilgiAciklama-" . $dil["dilId"]],
				'urunDilBilgiEtiketler' => "",
				'urunDilBilgiDurum' => 1
			);
			if ($itemPrimaryId != "") {
				$fonk->logKayit(2, $itemTableName . ' ; ' . $itemPrimaryId . ' ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				///güncelleme
				$queryAlt = $db->update($itemTableName, $itemPar, [
					"urunDilBilgiId" => $itemPrimaryId
				]);
			} else {
				$fonk->logKayit(1, $itemTableName . ' ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				///ekleme
				$queryAlt = $db->insert($itemTableName, $itemPar);
			}
		}
		//!dile göre değerlerin kayıt edilmesi
		

		$silKategoriler = $db->delete("UrunKategoriler", [
			"urunKategoriUrunId" => $primaryId
		]);
		foreach ($kategoriIdList as $key => $value) {
			$kategoriEkle = $db->insert("UrunKategoriler", [
				'urunKategoriUrunId' => $primaryId,
				'urunKategoriKategoriId' => $value
			]);
		}

		//çoklu Görsel işlemleri
		$files = array();
		foreach ($_FILES['urunDetayGorsel'] as $k => $l) {
			foreach ($l as $i => $v) {
				if (!array_key_exists($i, $files))
					$files[$i] = array();
				$files[$i][$k] = $v;
			}
		}
		$sayac = 0;
		foreach ($files as $file) { //max 10 adete göre ayarlandı
			$sayac++;
			if ($_POST["urunDilBilgiAdi-" . $sabitB["sabitBilgiVarsayilanDilId"]] != "") {
				$gorselAd = $urunKodu . "_" . $fonk->toSeo($_POST["urunDilBilgiAdi-" . $sabitB["sabitBilgiVarsayilanDilId"]]) . "-" . mt_rand();
			} else {
				$gorselAd = $urunKodu . "-" . mt_rand();
			}
			if ($sayac <= 10) {
				$kontrol = $fonk->imageResizeUpload($file, $gorselUploadPath, $gorselAd, 0, 0, jpg); //boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner
				if ($kontrol == 1) {
					$goselEkle = $db->insert("UrunGorselleri", [
						'urunGorselUrunId' => $primaryId,
						'urunGorselLink' => $gorselAd . ".jpg",
						'urunGorselBaseUrl' => $sabitB["sabitBilgiSiteUrl"] . "Images/Urunler/".$gorselPath,
						'urunGorselSirasi' => 0
					]);
				}
			} else {
				break;
			}
		}
		//!çoklu Görsel işlemleri

		if ($query) { //uyarı metinleri
			echo '
			<div class="alert alert-success alert-dismissible mb-2" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
			</button>
			<strong>' . $fonk->getPDil("Başarılı!") . '</strong> ' . $fonk->getPDil("Kayıt İşlemi Başarıyla Gerçekleşmiştir.") . '
			</div>';
		} else {
			echo '
			<div class="alert alert-danger alert-dismissible mb-2" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
			</button>
			<strong>' . $fonk->getPDil("Hata!") . '</strong> ' . $fonk->getPDil("Kayıt Esnasında Bir Hata Oluştu. Lütfen Tekrar Deneyiniz.") . '(' . $db->error . ')
			</div>';
		}
		
		
	}
	echo "<script>$('#ustYazi').html('&nbsp;-&nbsp;'+'" . $fonk->getPDil($baslik) . "');</script>"; //Başlık Güncelleniyor
	//update ise bilgiler getiriliyor
	if ($primaryId != "") {
		$Listeleme = $db->get($tableName, "*", [
			$tabloPrimarySutun => $primaryId
		]);
	} 
	else {
		$Listeleme['urunKodu'] = mt_rand(100000000, 999999999);
		$Listeleme['urunKdv'] = 0;
	}
	
?>
	<!-- Basic form layout section start -->
	<section id="basic-form-layouts">
		<div class="row match-height">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="basic-layout-colored-form-control"><?= $fonk->getPDil($baslik) ?></h4>
						<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
						<div class="heading-elements">
							<?php if ($eklemeYetki) { ?><button type="button" onclick="YeniEkle('<?= $menuId ?>','<?= $duzenlemeSayfasi ?>');" class="btn mr-1 btn-primary btn-sm"><i class="la la-plus-circle"></i></button><?php } ?>
							<?php if ($listelemeYetki) { ?><button type="button" onclick="SayfaGetir('<?= $menuId ?>','<?= $listelemeSayfasi ?>');" class="btn mr-1 btn-primary btn-sm"><i class="la la-th-list"></i> <?= $fonk->getPDil("Listeleme") ?></button><?php } ?>
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
												<label for="urunKodu"><?= $fonk->getPDil("Kodu") ?></label>
												<input type="text" class="form-control border-primary" id="urunKodu" name="urunKodu" value="<?= $Listeleme['urunKodu'] ?>" autocomplete="off" readonly required>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="urunStok"><?= $fonk->getPDil("Stok") ?><small style="color:red;margin-left:1rem">*</small></label>
												<input type="number" min="0" class="form-control border-primary" id="urunStok" name="urunStok" value="<?= $Listeleme['urunStok'] ?>" autocomplete="off">
											</div>
										</div>
										<input type="hidden" name="urunId" id="urunId" value="<?=$Listeleme['urunId']?>">
										<div class="col-md-3">
											<div class="form-group">
												<label for="parent"><?= $fonk->getPDil("Ana Kategoriler") ?><small style="color:red;margin-left:1rem">*</small></label>
												<select class="select2 form-control block" name="parent" id="parent" onchange="parentChildCategory('urunId','parent','kategoriIdList')"  required>
													<?php
													$sartlar = [];
													if ($_SESSION["islemDilId"] != "") {
														$sartlar = array_merge($sartlar, ["kategoriDilBilgiDilId" => $_SESSION["islemDilId"]]);
													} else {
														$sartlar = array_merge($sartlar, ["kategoriDilBilgiDilId" => $sabitB["sabitBilgiPanelVarsayilanDilId"]]);
													}
													$sartlar = array_merge($sartlar, [
														"kategoriUstMenuId" => 0
													]);
													$kategoriList = $db->select("Kategoriler", [
														"[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
													], "*", $sartlar);
													foreach ($kategoriList as $val) {
														$check = "";
														$kontrol = $db->get("UrunKategoriler", "*", [
															"urunKategoriUrunId" => $Listeleme['urunId'],
															"urunKategoriKategoriId" => $val["kategoriId"]
														]);
														if ($kontrol) {
															$check = "selected";
														}
													?>
														<option value="<?= $val['kategoriId'] ?>" <?= $check ?>><?= $val['kategoriDilBilgiBaslik'] ?></option>
													<?php } ?>
												</select>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="kategoriIdList"><?= $fonk->getPDil("Alt Kategoriler") ?><small style="color:red;margin-left:1rem">*</small></label>
												<select class="select2 form-control block" name="kategoriIdList[]" id="kategoriIdList" multiple required>
													
												</select>
											</div>
										</div>

										<div class="col-md-12">
											<div class="form-group row">
												<label class="col-12" for="checkboxlar">Opsiyonel Veriler *</label>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urunSFPPort" id="urunSFPPort" <?php if ($Listeleme['urunSFPPort'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urunSFPPort">
															100Mbit SFP Port
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urun1GSFPPort" id="urun1GSFPPort" <?php if ($Listeleme['urun1GSFPPort'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urun1GSFPPort">
															1Gigabit SFP Port
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urunSFPPortBirlikte" id="urunSFPPortBirlikte" <?php if ($Listeleme['urunSFPPortBirlikte'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urunSFPPortBirlikte">
															SFP+ Port
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urunSFP28Port" id="urunSFP28Port" <?php if ($Listeleme['urunSFP28Port'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urunSFP28Port">
															SFP28 Port
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urunQSFPPort" id="urunQSFPPort" <?php if ($Listeleme['urunQSFPPort'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urunQSFPPort">
															QSFP+ Port
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urunQSFP28Port" id="urunQSFP28Port" <?php if ($Listeleme['urunQSFP28Port'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urunQSFP28Port">
															QSFP28 Port
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urunEndustriyelTip" id="urunEndustriyelTip" <?php if ($Listeleme['urunEndustriyelTip'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urunEndustriyelTip">
															Endüstriyel Tip
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urun100MegabitRJ45Port" id="urun100MegabitRJ45Port" <?php if ($Listeleme['urun100MegabitRJ45Port'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urun100MegabitRJ45Port">
															100Mbit RJ45 Port
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urun1GigabitRJ45Port" id="urun1GigabitRJ45Port" <?php if ($Listeleme['urun1GigabitRJ45Port'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urun1GigabitRJ45Port">
															1 Gigabit RJ45 Port
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urun10GigabitRJ45Port" id="urun10GigabitRJ45Port" <?php if ($Listeleme['urun10GigabitRJ45Port'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urun10GigabitRJ45Port">
															10 Gigabit RJ45 Port
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urun1Metre" id="urun1Metre" <?php if ($Listeleme['urun1Metre'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urun1Metre">
															1Mt ve altı
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urun2Metre" id="urun2Metre" <?php if ($Listeleme['urun2Metre'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urun2Metre">
															2Mt
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urun3Metre" id="urun3Metre" <?php if ($Listeleme['urun3Metre'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urun3Metre">
															3Mt
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urun510Metre" id="urun510Metre" <?php if ($Listeleme['urun510Metre'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urun510Metre">
															5-10Mt
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urun1020Metre" id="urun1020Metre" <?php if ($Listeleme['urun1020Metre'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urun1020Metre">
															10-20Mt
														</label>
													</div>
												</fieldset>

												<fieldset class="col-2">
													<div class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" value="1" name="urun2030Metre" id="urun2030Metre" <?php if ($Listeleme['urun2030Metre'] == 1) {echo "checked";} ?>>
														<label class="custom-control-label" for="urun2030Metre">
															20Mt ve üzeri
														</label>
													</div>
												</fieldset>

											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="urunModel"><?= $fonk->getPDil("Model") ?></label>
												<input type="text" class="form-control border-primary" id="urunModel" name="urunModel" value="<?= $Listeleme['urunModel'] ?>" autocomplete="off" required>
											</div>
										</div>
									
										<div class="col-md-3">
											<div class="form-group">
												<label for="urunParaBirimId"><?= $fonk->getPDil("Para Birimi") ?><small style="color:red;margin-left:1rem">*</small></label>
												<select class="select2 form-control block" name="urunParaBirimId" id="urunParaBirimId" required>
													<?php
													$sorguList = $db->select("ParaBirimleri", "*",[
														'paraBirimId' => 2
													]);
													foreach ($sorguList as $sorgu) {
													?>
														<option value="<?= $sorgu['paraBirimId'] ?>" <?php if ($sorgu['paraBirimId'] == $Listeleme['urunParaBirimId']) {echo " selected";} ?>>
															<?= $sorgu['paraBirimAdi'] ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="urunFiyat"><?= $fonk->getPDil("Fiyat") ?><small style="color:red;margin-left:1rem">*</small></label>
												<input type="number" min="0" step="0.01" placeholder="0.00" class="form-control border-primary" id="urunFiyat" name="urunFiyat" value="<?= $Listeleme['urunFiyat'] ?>" autocomplete="off" required>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="urunKdv"><?= $fonk->getPDil("KDV Oranı (%)") ?></label>
												<input type="number" min="0" placeholder="18" class="form-control border-primary" id="urunKdv" name="urunKdv" value="<?= $Listeleme['urunKdv'] ?>" autocomplete="off">
											</div>
										</div>
																		
										<div class="col-md-3">
											<div class="form-group">
												<label for="urunGorsel"><?= $fonk->getPDil("Liste Görseli") ?> <?= $fonk->getPDil("(Önerilen:" . $width . "x" . $height . "px)") ?> .jpg formatında</label>
												<div class="custom-file">
													<input type="file" class="custom-file-input" name="urunGorsel" id="urunGorsel" accept=".jpg">
													<label class="custom-file-label" name="urunGorsel" id="urunGorsel" for="urunGorsel" aria-describedby="urunGorsel"><?= $fonk->getPDil("Dosya Seçiniz") ?></label>
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="urunDetayGorsel">
													<?= $fonk->getPDil("Detay Görselleri") ?> <?= $fonk->getPDil("(Önerilen:540x690px)") ?> .jpg formatında
													<?= $fonk->getPDil("(Çoklu ekleme işleminde ctrl tuşuna basılı tutunuz)") ?>
												</label>
												<div class="custom-file">
													<input type="file" class="custom-file-input" name="urunDetayGorsel[]" id="urunDetayGorsel" accept=".jpg" multiple>
													<label class="custom-file-label" name="urunDetayGorsel" id="urunDetayGorsel" for="urunDetayGorsel" aria-describedby="urunDetayGorsel"><?= $fonk->getPDil("Dosya Seçiniz") ?></label>
												</div>
											</div>
										</div>

										<div class="col-md-3">
											<div class="form-group">
												<label for="urunDataSheet"><?= $fonk->getPDil("Data Sheet") ?> </label>
												<div class="custom-file">
													<input type="file" class="custom-file-input" name="urunDataSheet" id="urunDataSheet" accept=".pdf">
													<label class="custom-file-label" name="urunDataSheet" id="urunDataSheet" for="urunDataSheet" aria-describedby="urunDataSheet"><?= $fonk->getPDil("Dosya Seçiniz") ?></label>
												</div>
											</div>
										</div>

										<div class="col-md-3" style="display: flex;">
											<div class="form-group">
												<label for="urunKampanya"><?= $fonk->getPDil("Kampanyalı Ürün") ?></label>
												<fieldset>
													<div class="float-left">
														<input type="checkbox" class="switch hidden" data-on-label="<?= $fonk->getPDil("Evet") ?>" data-off-label="<?= $fonk->getPDil("Hayır") ?>" id="urunKampanya" name="urunKampanya" value="1" <?php if ($Listeleme['urunKampanya'] == 1) {echo 'checked';} ?>>
													</div>
												</fieldset>
											</div>
										</div>

										<div class="col-md-3" style="display: flex;">
											<div class="form-group">
												<label for="urunEnCokSatan"><?= $fonk->getPDil("En Çok Satan Ürün") ?></label>
												<fieldset>
													<div class="float-left">
														<input type="checkbox" class="switch hidden" data-on-label="<?= $fonk->getPDil("Evet") ?>" data-off-label="<?= $fonk->getPDil("Hayır") ?>" id="urunEnCokSatan" name="urunEnCokSatan" value="1" <?php if ($Listeleme['urunEnCokSatan'] == 1) {echo 'checked';} ?>>
													</div>
												</fieldset>
											</div>
										</div>

										<div class="col-md-3" style="display: flex;">
											<div class="form-group">
												<label for="urunDurum"><?= $fonk->getPDil("Durumu") ?></label>
												<fieldset>
													<div class="float-left">
														<input type="checkbox" class="switch hidden" data-on-label="<?= $fonk->getPDil("Aktif") ?>" data-off-label="<?= $fonk->getPDil("Pasif") ?>" id="urunDurum" name="urunDurum" value="1" <?php if ($Listeleme['urunDurum'] == 1) {echo 'checked';} ?>>
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
												foreach ($dilList as $dil) {
													$item = $db->get("UrunDilBilgiler", "*", [
														"urunDilBilgiUrunId" => $Listeleme['urunId'],
														"urunDilBilgiDilId" => $dil["dilId"]
													]);
												?>
													<input type="hidden" name="urunDilBilgiId-<?= $dil["dilId"] ?>" id="urunDilBilgiId-<?= $dil["dilId"] ?>" value="<?= $item["urunDilBilgiId"] ?>" />
													<div id="headingCollapse64" data-toggle="collapse" data-target="#listItem-<?= $dil["dilId"] ?>" class="card-header mt-1 border-info pointer" aria-expanded="true">
														<b><?= $dil["dilAdi"] ?></b>
													</div>
													<div id="listItem-<?= $dil["dilId"] ?>" role="tabpanel" aria-labelledby="headingCollapse64" class="border-info no-border-top card-collapse collapse <?php if ($item["urunDilBilgiDurum"] == 1) {echo "show";} ?>" aria-expanded="false">
														<div class="card-content">
															<div class="card-body">
																<div class="row">
																	<div class="col-md-6">
																		<div class="form-group">
																			<label for="urunDilBilgiAdi-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Adi") ?><small style="color:red;margin-left:1rem">*</small></label>
																			<input type="text" onkeyup="toSeo('urunDilBilgiAdi-<?= $dil["dilId"] ?>','urunDilBilgiSlug-<?= $dil["dilId"] ?>')" class="form-control border-primary" id="urunDilBilgiAdi-<?= $dil["dilId"] ?>" name="urunDilBilgiAdi-<?= $dil["dilId"] ?>" value="<?= $item['urunDilBilgiAdi'] ?>" autocomplete="off">
																		</div>
																	</div>
																	<div class="col-md-6">
																		<div class="form-group">
																			<label for="urunDilBilgiSlug-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Link") ?><small style="color:red;margin-left:1rem">*</small></label>
																			<input type="text" class="form-control border-primary" id="urunDilBilgiSlug-<?= $dil["dilId"] ?>" name="urunDilBilgiSlug-<?= $dil["dilId"] ?>" value="<?= $item['urunDilBilgiSlug'] ?>" autocomplete="off" readonly>
																		</div>
																	</div>
																	<!-- <div class="col-md-2">
																		<div class="form-group">
																			<label for="urunDilBilgiDurum-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Durumu") ?></label>
																			<fieldset>
																				<div class="float-left">
																					<input type="checkbox" class="switch hidden" data-on-label="<?= $fonk->getPDil("Aktif") ?>" data-off-label="<?= $fonk->getPDil("Pasif") ?>" id="urunDilBilgiDurum-<?= $dil["dilId"] ?>" name="urunDilBilgiDurum-<?= $dil["dilId"] ?>" value="1" <?php if ($item['urunDilBilgiDurum'] == 1) {echo 'checked';} ?>>
																				</div>
																			</fieldset>
																		</div>
																	</div> -->
																	
																	<div class="col-md-12">
																		<div class="form-group">
																			<label for="urunDilBilgiDescription-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Description / Kısa Açıklama") ?></label>
																			<textarea class="form-control" id="urunDilBilgiDescription-<?= $dil["dilId"] ?>" name="urunDilBilgiDescription-<?= $dil["dilId"] ?>" rows="3" placeholder="..."><?= $item['urunDilBilgiDescription'] ?></textarea>
																		</div>
																	</div>
																	<div class="col-md-12">
																		<div class="form-group">
																			<label for="urunDilBilgiAciklama-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Detay Açıklama") ?></label>
																			<textarea class="editorCk" id="urunDilBilgiAciklama-<?= $dil["dilId"] ?>" name="urunDilBilgiAciklama-<?= $dil["dilId"] ?>"><?= $item['urunDilBilgiAciklama'] ?></textarea>
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

									<div class="form-group" style="text-align: center;margin-top:15px">
										<input type="hidden" name="update" value="<?= $Listeleme[$tabloPrimarySutun] ?>" />
										<input type="hidden" name="menuId" value="<?= $menuId ?>" />
										<input type="hidden" name="formdan" value="1" />
										<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
										<button type="submit" class="btn mb-1 btn-success"><i class="la la-floppy-o"></i>
											<?php if ($primaryId != "") {
												echo $fonk->getPDil("Güncelle");
											} else {
												echo $fonk->getPDil("Kayıt");
											} ?></button>
									</div>

								</div>
								
							</form>

						</div>
					</div>
				</div>
			</div>


			<div class="col-md-12">
				<?php
				if ($primaryId != "") {
				?>
					<!-- Alt Birim -->

					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title pointer" data-toggle="collapse" data-target="#altBirimDiv"><?= $fonk->getPDil("Ürün Marka Bilgileri") ?></h4>
									<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>

									<div class="heading-elements">
										<ul class="list-inline mb-0">
											<li><button type="button" onclick="altBirimEkleForm(<?= $primaryId ?>);" class="btn btn-success btn-sm" style="padding: 0.2rem 0.75rem;"><i class="la la-plus"></i> <?=$fonk->getPDil("Yeni Ekle")?></button></li>
											<li><a data-toggle="collapse" data-target="#altBirimDiv"><i class="la la-arrows-v"></i></a></li>
										</ul>
									</div>
								</div>
								<div class="card-content collapse show" id="altBirimDiv">
									<div id="altBirimList">
									</div>
								</div>


								<div id="altBirimGuncelleList">
									<div class="card-body">
										<form id="altBirimPost" class="form" action="" method="post">
											<div class="row">
												<div class="col-md-3">
													<div class="form-group" style="width:100%!important">
														<label for="userinput1"><?= $fonk->getPDil("Marka") ?></label>
														<select class="select2 form-control block" name="urunVaryantVaryantId" style="width:100%!important">
															<option value=""><?= $fonk->getPDil("Marka Seçiniz") ?></option>
															<?php
															$sartlar = [];
															if ($_SESSION["islemDilId"] != "") {
																$sartlar = array_merge($sartlar, ["varyantDilBilgiDilId" => $_SESSION["islemDilId"]]);
															} else {
																$sartlar = array_merge($sartlar, ["varyantDilBilgiDilId" => $sabitB["sabitBilgiPanelVarsayilanDilId"]]);
															}
															$sorguList = $db->select("Varyantlar", [
																"[>]VaryantDilBilgiler" => ["Varyantlar.varyantId" => "varyantDilBilgiVaryatId"]
															], "*", $sartlar);
															foreach ($sorguList as $sorgu) {
																$durum = $fonk->getPDil("Pasif");
																if ($sorgu["varyanDurum"] == 1) {
																	$durum = $fonk->getPDil("Aktif");
																}
															?>
																<option value="<?= $sorgu['varyantId'] ?>">
																	<?= $sorgu['varyantDilBilgiBaslik'] . " (" . $durum . ")" ?>
																</option>
															<?php } ?>
														</select>
													</div>
												</div>

												<div class="col-md-3">
													<div class="form-group" style="width:100%!important">
														<label for="userinput1"><?= $fonk->getPDil("Markaya Göre Fiyat") ?></label>
														<input type="number" min="0" step="0.01" placeholder="<?= $fonk->getPDil("Fiyat") ?> (0.00)" class="form-control border-primary" id="urunVaryantFiyat" name="urunVaryantFiyat" autocomplete="off">
													</div>
												</div>

												<div class="col-md-3">
													<div class="form-group" style="width:100%!important">
														<label for="urunVaryantKampanyasizFiyat"><?= $fonk->getPDil("Liste Fiyat") ?></label>
														<input type="number" min="0" step="0.01" placeholder="<?= $fonk->getPDil("Fiyat") ?> (0.00)" class="form-control border-primary" id="urunVaryantKampanyasizFiyat" name="urunVaryantKampanyasizFiyat" autocomplete="off">
													</div>
												</div>

												<div class="col-md-3">
													<div class="form-group">
														<label for="userinput1"></label>
														<div class="row skin skin-square">
															<div class="col-md-12">
																<fieldset>
																	<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="urunVaryantDefaultSecim" id="urunVaryantDefaultSecim" value="1" style="position: absolute; opacity: 0;"></div>
																	<label for="urunVaryantDefaultSecim" class=""><?=$fonk->getPDil("Default Marka Olsun")?></label>
																</fieldset>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-12">
													<div class="card collapse-icon accordion-icon-rotate">
														<?php
														$dilList = $db->select("Diller", "*");
														foreach ($dilList as $dil) {
														?>
															<div id="headingCollapse64" data-toggle="collapse" data-target="#listItem2-<?= $dil["dilId"] ?>" class="card-header mt-1 border-info pointer" aria-expanded="true">
																<b><?= $dil["dilAdi"] ?></b>
															</div>
															<div id="listItem2-<?= $dil["dilId"] ?>" role="tabpanel" aria-labelledby="headingCollapse64" class="border-info no-border-top card-collapse collapse" aria-expanded="false">
																<div class="card-content">
																	<div class="card-body">
																		<div class="row">
																			<div class="col-md-5">
																				<div class="form-group">
																					<label for="urunVaryantDilBilgiAdi-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Adi") ?><small style="color:red;margin-left:1rem">*</small></label>
																					<input type="text" onkeyup="toSeo('urunVaryantDilBilgiAdi-<?= $dil['dilId'] ?>','urunVaryantDilBilgiSlug-<?= $dil['dilId'] ?>')" class="form-control border-primary" id="urunVaryantDilBilgiAdi-<?= $dil["dilId"] ?>" name="urunVaryantDilBilgiAdi-<?= $dil["dilId"] ?>" autocomplete="off">
																				</div>
																			</div>
																			<div class="col-md-5">
																				<div class="form-group">
																					<label for="urunVaryantDilBilgiSlug-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Link") ?><small style="color:red;margin-left:1rem">*</small></label>
																					<input type="text" class="form-control border-primary" id="urunVaryantDilBilgiSlug-<?= $dil["dilId"] ?>" name="urunVaryantDilBilgiSlug-<?= $dil["dilId"] ?>" autocomplete="off" readonly>
																				</div>
																			</div>
																			<div class="col-md-2">
																				<div class="form-group">
																					<label for="urunVaryantDilBilgiDurum-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Durumu") ?></label>
																					<fieldset>
																						<div class="float-left">
																							<input type="checkbox" class="switch hidden" data-on-label="<?= $fonk->getPDil("Aktif") ?>" data-off-label="<?= $fonk->getPDil("Pasif") ?>" id="urunVaryantDilBilgiDurum-<?= $dil["dilId"] ?>" name="urunVaryantDilBilgiDurum-<?= $dil["dilId"] ?>" value="1">
																						</div>
																					</fieldset>
																				</div>
																			</div>
																			
																			<div class="col-md-12">
																				<div class="form-group">
																					<label for="urunVaryantDilBilgiEtiketler-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Etiketler") ?><small style="color:red;margin-left:1rem">*</small></label>
																					<input type="text" class="form-control border-primary" id="urunVaryantDilBilgiEtiketler-<?= $dil["dilId"] ?>" name="urunVaryantDilBilgiEtiketler-<?= $dil["dilId"] ?>" autocomplete="off">
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

											<div class="row">
												<div class="col-md-12">
													<div class="form-group" style="text-align: center;margin-top:10px">
														<input type="hidden" name="urunVaryantUrunId" id="urunVaryantUrunId" value="<?= $primaryId ?>" />
														<input type="hidden" name="urunVaryantId" id="urunVaryantId" /><!-- update ise doludur -->
														<input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
														<button type="submit" class="btn btn-success" id="altBirimButton"><i class="la la-floppy-o"></i> <?= $fonk->getPDil("Kayıt") ?></button>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>

							</div>
						</div>
					</div>

					<script type="text/javascript">
						$(document).ready(function() {
							altBirimListele(<?= $primaryId ?>);
						});
					</script>
					<!-- /Alt Birim -->
				<?php } ?>
			</div>


			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="basic-layout-colored-form-control"><?= $fonk->getPDil("Detay Görsel Düzeni") ?></h4>
						<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
						<div class="heading-elements">

						</div>
					</div>
					<div class="card-content collapse show">
						<div class="card-body">
							<div class="row" id="card-drag-area">
								<?php
								$urunGorselleri = $db->select("UrunGorselleri", "*", [
									"urunGorselUrunId" => $primaryId,
									"ORDER" => [
										"urunGorselSirasi" => "ASC"
									]
								]);
								foreach ($urunGorselleri as $item) {
								?>
									<div class="col-md-2" id="sliderSatir-<?= $item["urunGorselId"]; ?>">
										<div class="card grab" style="border: 1px solid #cacfe7;">
											<div class="card-content">
												<div class="card-content">
													<img class="card-img-top img-fluid" src="<?= $Listeleme['urunBaseUrl'] . $item['urunGorselLink'] ?>?v=<?= uniqid(); ?>">
													<div class="card-footer" style="text-align: right;padding: 0.5rem 0.5rem;">
														<?php if ($duzenlemeYetki) { ?>
															<button type="button" onclick="GorselSecimModal(<?= $item["urunGorselId"]; ?>,2,1100,1400)" class="btn btn-sm btn-primary net-button"><i class="la la-crop"></i></button>
														<?php } ?>
														<?php if ($silmeYetki) { ?>
															<button type="button" onclick="DetayGorselSil('<?= $item["urunGorselId"]; ?>')" class="btn btn-sm btn-danger net-button"><i class="la la-trash-o"></i></button>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
							<?php if (count($urunGorselleri) > 0 && $duzenlemeYetki) { ?>
								<div class="col-md-12" style="text-align: center;padding: 2rem;">
									<button type="button" class="btn btn-success" onclick="DetayDuzenKayit()"><i class="la la-floppy-o"></i> <?php echo $fonk->getPDil("Düzeni Kaydet"); ?></button>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>



	<div id="detayDiv"></div>

	<!-- // Basic form layout section end -->
	<script src="Assets/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
	<script src="Assets/app-assets/js/scripts/forms/form-repeater.js"></script>
<?php }
include("../../Scripts/kayitJs.php"); ?>
<script type="text/javascript">
	$( document ).ready(function() {
		parentChildCategory('urunId','parent','kategoriIdList');
    });
	function GorselSecimModal(Id, tip, width, height) {
		$.ajax({
			type: "POST",
			url: "Pages/Urunler/gorselSecimModal.php",
			data: {
				'Id': Id,
				'tip': tip,
				'width': width,
				'height': height
			},
			success: function(res) {
				$('#detayDiv').html(res);
				$("#fadeIn").modal("show");
			},
			error: function(jqXHR, status, errorThrown) {
				alert("Result: " + status + " Status: " + jqXHR.status);
			}
		});
	}

	function DetayDuzenKayit() {
		var duzenList = [];
		var list = document.getElementById("card-drag-area").children;
		for (var i = 0; i < list.length; i++) {
			duzenList.push(list[i].id.split("-")[1]);
		}
		var data = new FormData();
		data.append("duzenList", duzenList);
		$.ajax({
			type: "POST",
			url: "Pages/Urunler/duzenKayit.php",
			data: data,
			contentType: false,
			processData: false,
			success: function(res) {
				if (res == '1') {
					toastr.success('<?= $fonk->getPDil("Güncelleme Sağlandı.") ?>');
				} else {
					alert(res);
				}
			},
			error: function(jqXHR, status, errorThrown) {
				alert("Result: " + status + " Status: " + jqXHR.status);
			}
		});
	}

	function DetayGorselSil(sil) {
		if (confirm('<?= $fonk->getPDil("Silmek İstediğinize Emin misiniz ?") ?>')) {
			$.ajax({
				type: "POST",
				url: "Pages/Urunler/detayGorselSil.php",
				data: {
					'sil': sil
				},
				success: function(res) {
					if (res == 1) {
						document.getElementById('sliderSatir-' + sil).style.display = "none";
					} else {
						menuDuzen();
					}
				},
				error: function(jqXHR, status, errorThrown) {
					alert("Result: " + status + " Status: " + jqXHR.status);
				}
			});
		}
	}

	$(".editorCk").each(function() {
		let editorId = $(this).attr('id');
		CKEDITOR.replace(editorId, { //ckeditor kullanıldığında açılır
			height: '350px',
			extraPlugins: 'forms',
			uiColor: '#CCEAEE',
			//Dosya Yöneticisi resim gözat için
			filebrowserBrowseUrl: 'Assets/app-assets/fileman/index.html', // Öntanımlı Dosya Yöneticisi
			filebrowserImageBrowseUrl: 'Assets/app-assets/fileman/index.html?type=image', // Sadece Resim Dosyalarını Gösteren Dosya Yöneticisi
			removeDialogTabs: 'link:upload;image:upload' // Upload işlermlerini dosya Yöneticisi ile yapacağımız için upload butonlarını kaldırıyoruz
		});
	});

	$('#formpost').submit(function(e) {
		e.preventDefault(); //submit postu kesyoruz
		var data = new FormData(this);
		$(".editorCk").each(function() {
			let editorId = $(this).attr('id');
			data.append(editorId, CKEDITOR.instances[editorId].getData()); //ckeditor kullanılacağı zaman açılır 'ckeditor' yazan kısmı post keyidir
		});
		$('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
		$.ajax({
			type: "POST",
			url: "<?= $_SERVER['REQUEST_URI'] ?>",
			data: data,
			contentType: false,
			processData: false,
			success: function(res) {
				$('#Sayfalar').html(res);
			},
			error: function(jqXHR, status, errorThrown) {
				alert("Result: " + status + " Status: " + jqXHR.status);
			}
		});
	});

	$("#repeater-button").click(function() {
		setTimeout(function() {
			$(".select2").select2({});
		}, 5);
	});

	////// Alt Birim İşlemler
	$('#altBirimPost').submit(function(e) { 
		document.getElementById('altBirimButton').disabled = true;
		e.preventDefault(); //submit postu kesyoruz
		var data = new FormData(this);
		$('#altBirimList').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
		$.ajax({
			type: "POST",
			url: "Pages/Urunler/altBirimEkle.php",
			data: data,
			contentType: false,
			processData: false,
			success: function(res) {
				document.getElementById('altBirimButton').disabled = false;
				if (res == 1) {
					altBirimListele(<?= $primaryId ?>);
					altBirimEkleForm(<?= $primaryId ?>);
				} else {
					alert('<?= $fonk->getPDil("Kayıt Esnasında Bir Hata Oluştu") ?>');
				}
			}
		});
	});

	function altBirimSil(silID) {
		if (confirm('<?= $fonk->getPDil("Silmek İstediğinize Emin misiniz ?") ?>')) {
			$.ajax({
				type: "POST",
				url: "Pages/Urunler/altBirimSil.php",
				data: {
					'silID': silID
				},
				success: function(res) {
					if (res == 1) {
						document.getElementById("satirAltBirim_" + silID).style.display = "none";
					}
				}
			});
		}
	}

	function altBirimListele(Id) {
		$.ajax({
			type: "POST",
			url: "Pages/Urunler/altBirimList.php",
			data: {
				'Id': Id
			},
			success: function(res) {
				$('#altBirimList').html(res);
			}
		});
	}

	function altBirimGuncelle(Id) {
		$.ajax({
			type: "POST",
			url: "Pages/Urunler/altBirimGuncelle.php",
			data: {
				'Id': Id
			},
			success: function(res) {
				$('#altBirimGuncelleList').html(res);
			}
		});
	}

	function altBirimEkleForm(Id) {
		$.ajax({
			type: "POST",
			url: "Pages/Urunler/altBirimEkleForm.php",
			data: {
				'Id': Id
			},
			success: function(res) {
				$('#altBirimGuncelleList').html(res);
			}
		});
	}
	////// -Alt Birim İşlemler
</script>