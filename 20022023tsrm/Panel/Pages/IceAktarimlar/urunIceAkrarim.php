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
	exit;
}
//Listeleme Yetkisi Var
$tableName="Urunler";//tabloadı istenirse burdan değiştirilebilir

$tabloPrimarySutun="";//primarykey sutunu

$baslik=$hangiMenu['menuAdi'];//başlıkta gözükecek yazı menu adi

$duzenlemeSayfasi='IceAktarimlar/urunIceAkrarim.php';

$primaryId=$_POST['update'];//düzenle isteği ile gelen

if($_POST['formdan']!="1"){
	//sayfayı görüntülenme logları
	$fonk->logKayit(6,$_SERVER['REQUEST_URI']."?primaryId=".$primaryId);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
}

////güncllenecek parametreler***
//Forumdan gelenler
extract($_POST);//POST parametrelerini değişken olarak çevirir
////güncllenecek parametreler***

echo "<script>$('#ustYazi').html('&nbsp;-&nbsp;'+'".$fonk->getPDil($baslik)."');</script>";//Başlık Güncelleniyor

require 'simpleXlsx.php';
use Shuchkin\SimpleXLSX;
if($_POST['formdan']=="1"){
	$fonk->csrfKontrol();

	$dosyaAdi=mt_rand();
	$kontrol=$fonk->dosyaUploadArr($_FILES['exelDosya'],'../../Images/',$dosyaAdi,20,array("xlsx"));//boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner
	if($kontrol[0]==1){
		if ($xlsx=SimpleXLSX::parse('../../Images/'.$kontrol[1])){
			if(count($xlsx->rows())>1){
				$sayac=0;
				foreach($xlsx->rows() as $r){
					if(count($r)==10){
						$sayac++;
						if($sayac<=1){
							continue;
						}
						if ($urunDurum=="") {
							$urunDurum=0;
						}
						$urunKodu=mt_rand(100000000,999999999);
						$parametreler=array(
							'urunKodu' => $urunKodu,
							'urunBarkod' => $r[0],
							'urunFiyat' => floatval($r[2]),
							'urunIndirimliFiyat' => floatval($r[3]),
							'urunParaBirimId' => $urunParaBirimId,
							'urunKdv' => intval($r[4]),
							'urunBegeni' => 0,
							'urunSepetMiktar' => 0,
							'urunSatisMiktar' => 0,
							'urunStok' => intval($r[1]),
							'urunDurum' => $urunDurum,
							'urunBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/Urunler/",
							'urunKayitTarihi' => date("Y-m-d H:i:s")
						);
						$fonk->logKayit(1,$tableName.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
						if($r[5]!=""){
							$gorselAdi=$urunKodu."_".$fonk->toSeo($r[6])."-".mt_rand().".jpg";
							if (file_put_contents('../../../Images/Urunler/'.$gorselAdi, file_get_contents($r[5])))
							{
								$parametreler=array_merge($parametreler,array('urunGorsel' => $gorselAdi));
							}
						}
						///ekleme
						$query = $db->insert($tableName, $parametreler);
						$urunId=$db->id();

						if($r[9]!=""){//detay görselleri
							$gorseller=explode(",",$r[9]);
							$sayac=0;
							foreach ($gorseller as $key => $gorsel) {
								$sayac++;
								$gorselAdi=$urunKodu."_".$fonk->toSeo($r[6])."-".mt_rand()."-".$sayac.".jpg";
								if (file_put_contents('../../../Images/Urunler/'.$gorselAdi, file_get_contents($gorsel)))
								{
									$parametreler=array_merge($parametreler,array('urunGorsel' => $gorselAdi));
									$goselEkle = $db->insert("UrunGorselleri", [
										'urunGorselUrunId' => $urunId,
										'urunGorselLink' => $gorselAdi,
										'urunGorselBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/Urunler/",
										'urunGorselSirasi' => 0
									]);
								}
							}
						}

						$itemPar=array(
							'urunDilBilgiUrunId' => $urunId,
							'urunDilBilgiDilId' => $urunDilId,
							'urunDilBilgiAdi' => $r[6],
							'urunDilBilgiSlug' => $fonk->toSeo($r[6]),
							'urunDilBilgiDescription' => $r[7],
							'urunDilBilgiAciklama' => $r[8],
							'urunDilBilgiDurum' => $urunDurum
						);
						$fonk->logKayit(1,"UrunDilBilgiler".' ; '.json_encode($itemPar));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
						///ekleme
						$queryAlt = $db->insert("UrunDilBilgiler", $itemPar);

						$silKategoriler = $db->delete("UrunKategoriler", [
							"urunKategoriUrunId" => $urunId
						]);
						foreach ($kategoriIdList as $key => $value) {
							$kategoriEkle = $db->insert("UrunKategoriler", [
								'urunKategoriUrunId' => $urunId,
								'urunKategoriKategoriId' => $value
							]);
						}
						if($query){//uyarı metinleri
							echo '
							<div class="alert alert-success alert-dismissible" role="alert" style="margin-bottom: 0.3rem;padding: 0.3rem 1.3rem;">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding: 0.4rem 1rem;">
							<span aria-hidden="true">×</span>
							</button>
							<strong>'.$fonk->getPDil("Başarılı!").'</strong> '.$fonk->getPDil("Kayıt İşlemi Başarıyla Gerçekleşmiştir.").' ('.$r[6].')
							</div>';
						}
					}else{
						echo '<div class="alert alert-icon-right alert-warning alert-dismissible mb-2" role="alert">
						<span class="alert-icon"><i class="la la-warning"></i></span>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">×</span>
						</button>
						<strong>'.$fonk->getPDil("Uyarı!").' </strong> '.$fonk->getPDil("Excel Sütun Sayısı Hatalıdır. Örnek Exceli Üzerinden Kontrol Sağlayabilirsiniz.").'
						</div>';
						break;
					}
				}
			}else{
				echo '<div class="alert alert-icon-right alert-warning alert-dismissible mb-2" role="alert">
				<span class="alert-icon"><i class="la la-warning"></i></span>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
				<strong>'.$fonk->getPDil("Uyarı!").' </strong> '.$fonk->getPDil("Excelde Veri Bulunmamaktadır.").'
				</div>';
			}
			unlink('../../Images/'.$kontrol[1]);
		}else{
			echo SimpleXLSX::parseError();
		}
	}else{
		echo '<div class="alert alert-icon-right alert-warning alert-dismissible mb-2" role="alert">
		<span class="alert-icon"><i class="la la-warning"></i></span>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
		</button>
		<strong>'.$fonk->getPDil("Uyarı!").' </strong> '.$kontrol[1].'
		</div>';
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
						<a href="Pages/IceAktarimlar/ornek.xlsx" class="btn mr-1 btn-info btn-sm"  target="_blank"><i class="la la-download"></i> <?=$fonk->getPDil("Örnek Excel")?></a>
						<?php if($eklemeYetki){?><button type="button" onclick="YeniEkle('<?=$menuId?>','<?=$duzenlemeSayfasi?>');" class="btn mr-1 btn-primary btn-sm"><i class="la la-plus-circle"></i></button><?php } ?>
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
											<label for="kategoriIdList"><?=$fonk->getPDil("Kategoriler")?><small style="color:red;margin-left:1rem">*</small></label>
											<select class="select2 form-control block" name="kategoriIdList[]" id="kategoriIdList" multiple required>
												<?php
												$sartlar=[];
												if ($_SESSION["islemDilId"]!="") {
													$sartlar=array_merge($sartlar,["kategoriDilBilgiDilId" => $_SESSION["islemDilId"]]);
												}
												else {
													$sartlar=array_merge($sartlar,["kategoriDilBilgiDilId" => $sabitB["sabitBilgiPanelVarsayilanDilId"]]);
												}
												$sartlar=array_merge($sartlar,[
													"kategoriUstMenuId" => 0
												]);
												$kategoriList = $db->select("Kategoriler",[
													"[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
												],"*",$sartlar);
												foreach($kategoriList as $val){
													$check="";
													$kontrol = $db->get("UrunKategoriler", "*", [
														"urunKategoriUrunId" => $Listeleme['urunId'],
														"urunKategoriKategoriId" =>$val["kategoriId"]
													]);
													if ($kontrol) {
														$check="selected";
													}
													?>
													<option value="<?=$val['kategoriId']?>" <?=$check?>><?=$val['kategoriDilBilgiBaslik']?></option>
													<?php
													$sartlar=array_merge($sartlar,[
														"kategoriUstMenuId" => $val["kategoriId"]
													]);
													$kategoriAltList = $db->select("Kategoriler",[
														"[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
													],"*",$sartlar);
													foreach($kategoriAltList as $val){
														$check="";
														$kontrol = $db->get("UrunKategoriler", "*", [
															"urunKategoriUrunId" => $Listeleme['urunId'],
															"urunKategoriKategoriId" =>$val["kategoriId"]
														]);
														if ($kontrol) {
															$check="selected";
														}
														?>
														<option value="<?=$val['kategoriId']?>" <?=$check?>>&nbsp;&nbsp;&nbsp;&nbsp;--> <?=$val['kategoriDilBilgiBaslik']?></option>
													<?php }
												} ?>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="exelDosya"><?=$fonk->getPDil("Excel Dosyası")?></label>
											<div class="custom-file">
												<input type="file" class="custom-file-input" name="exelDosya" id="exelDosya" accept=".xlsx" required>
												<label class="custom-file-label" name="exelDosya" id="exelDosya" for="exelDosya" aria-describedby="exelDosya"><?=$fonk->getPDil("Dosya Seçiniz")?></label>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="urunParaBirimId"><?=$fonk->getPDil("Para Birimi")?><small style="color:red;margin-left:1rem">*</small></label>
											<select class="select2 form-control block" name="urunParaBirimId" id="urunParaBirimId" required>
												<?php
												$sorguList = $db->select("ParaBirimleri","*");
												foreach($sorguList as $sorgu){
													?>
													<option value="<?=$sorgu['paraBirimId']?>" <?php if($sorgu['paraBirimId']==$Listeleme['urunParaBirimId']){echo " selected";}?>><?=$sorgu['paraBirimAdi']?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="urunDilId"><?=$fonk->getPDil("Diller")?><small style="color:red;margin-left:1rem">*</small></label>
											<select class="select2 form-control block" name="urunDilId" id="urunDilId" required>
												<?php
												$sorguList = $db->select("Diller","*");
												foreach($sorguList as $sorgu){
													?>
													<option value="<?=$sorgu['dilId']?>" <?php if($sorgu['dilId']==$Listeleme['urunDilId']){echo " selected";}?>><?=$sorgu['dilAdi']?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="urunDurum"><?=$fonk->getPDil("Durumu")?></label>
											<fieldset>
												<div class="float-left">
													<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="urunDurum" name="urunDurum" value="1" <?php if($Listeleme['urunDurum']==1){echo 'checked';}?> >
												</div>
											</fieldset>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<div class="alert alert-icon-right alert-warning alert-dismissible mb-1 mt-1" role="alert">
												<span class="alert-icon"><i class="la la-warning"></i></span>
												<strong><?=$fonk->getPDil("Ürün Görsel Çözünürlükleri 'Ürünler>Ürün Kayıt' Sayfasında Yer Alan Önerilen Ölçüler Yapılmalıdır.")?></strong>
											</div>
											<div class="alert alert-icon-right alert-warning alert-dismissible mb-1" role="alert">
												<span class="alert-icon"><i class="la la-warning"></i></span>
												<strong><?=$fonk->getPDil("Görsel ve İndirimli Fiyat Zorunlu Değildir Boş Bırakılabilir.")?></strong>
											</div>
											<div class="alert alert-icon-right alert-warning alert-dismissible mb-1" role="alert">
												<span class="alert-icon"><i class="la la-warning"></i></span>
												<strong><?=$fonk->getPDil("Ürün Bilgileri Sadece Bir Dil İçin İçeri Aktarılabilir.Diğer Diller İçin Manuel Giriş Yapılmalıdır.")?></strong>
											</div>
											<div class="alert alert-icon-right alert-warning alert-dismissible mb-1" role="alert">
												<span class="alert-icon"><i class="la la-warning"></i></span>
												<strong><?=$fonk->getPDil("Ürün Varyantları Var İse Manuel Eklenmelidir.")?></strong>
											</div>
											<div class="alert alert-icon-right alert-warning alert-dismissible mb-1" role="alert">
												<span class="alert-icon"><i class="la la-warning"></i></span>
												<strong><?=$fonk->getPDil("Ürün Detay Görselleri Virgül(,) İle Ayrılmış Olmalıdır.Maksimum 10 Adet.")?></strong>
											</div>
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

<!-- Alt Birim -->
<section id="sizing">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title pointer" data-toggle="collapse" data-target="#altBirimDiv"><?=$fonk->getPDil("Geçici Görseller")?></h4>
					<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>

					<div class="heading-elements">
						<ul class="list-inline mb-0">
							<li><button type="button" onclick="altBirimSil('','');" class="btn btn-danger btn-sm" style="padding: 0.2rem 0.75rem;"><i class="la la-trash-o"></i> <?=$fonk->getPDil("Tüm Görselleri Sil")?></button></li>
							<li><button type="button" onclick="altBirimModal();" class="btn btn-success btn-sm" style="padding: 0.2rem 0.75rem;"><i class="la la-plus"></i> <?=$fonk->getPDil("Yeni Ekle")?></button></li>
							<li><a data-toggle="collapse" data-target="#altBirimDiv"><i class="la la-arrows-v"></i></a></li><!-- id leri unutma -->
						</ul>
					</div>
				</div>
				<div class="card-content collapse show" id="altBirimDiv"><!-- id leri unutma  liste açık gelmesini istiyorsan collapse "show"  ekle gizli gelmesini istiyorsahn "show" kaldır -->
					<div id="altBirimList"><!-- Listeleme Gelicek --> </div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ekleme -->
<div class="modal fade text-left" id="altBirimEkle" role="dialog" aria-hidden="true"><!-- id leri unutma -->
	<!-- ekleme modalı -->
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?=$fonk->getPDil("Geçici Görsel Ekle")?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="card-body">
				<form id="altBirimPost" class="form" action="" method="post">
					<div class="row">
						<div class="form-group">
							<label for="gorseller"><?=$fonk->getDil("Dosya (Uzantı:.jpg,.jpeg,.png)(Max:20mb)(Max:20Adet)")?></label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" name="gorseller[]" id="gorseller" accept=".jpg,.jpeg,.png" multiple>
								<label class="custom-file-label" name="gorseller" id="gorseller" for="gorseller" aria-describedby="gorseller"><?=$fonk->getDil("Dosya Seçiniz")?></label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group" style="text-align: center;margin-top:10px">
								<input type="hidden" name="token" value="<?=$_SESSION['token']?>" />
								<button type="submit" class="btn btn-success" id="altBirimButton"><i class="la la-floppy-o"></i> <?=$fonk->getPDil("Kayıt")?></button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal"><?=$fonk->getPDil("Kapat")?></button>
			</div>
		</div>
	</div>
</div>
<script  type="text/javascript">
$(document).ready(function(){
	altBirimListele();
});
</script>
<!-- /Alt Birim -->
<?php include("../../Scripts/kayitJs.php");?>
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
////// Alt Birim İşlemler
$('#altBirimPost').submit(function (e) {
	document.getElementById('altBirimButton').disabled=true;
	e.preventDefault(); //submit postu kesyoruz
	var data=new FormData(this);
	$('#altBirimList').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
	$.ajax({
		type: "POST",
		url: "Pages/IceAktarimlar/altBirimEkle.php",
		data:data,
		contentType:false,
		processData:false,
		success: function(res){
			document.getElementById('altBirimButton').disabled=false;
			if(res==1){
				altBirimListele();
				altBirimTemizle();
			}else{
				alert('<?=$fonk->getPDil("Kayıt Esnasında Bir Hata Oluştu")?>');
			}
		}
	});
});
function altBirimSil(silID,dosya){
	if(confirm('<?=$fonk->getPDil("Silmek İstediğinize Emin misiniz ?")?>')) {
		$.ajax({
			type: "POST",
			url: "Pages/IceAktarimlar/altBirimSil.php",
			data:{'silID':silID,'dosya':dosya},
			success: function(res){
				if(res==1){
					if(silID!="" && dosya!=""){
						document.getElementById("satirAltBirim_"+silID).style.display = "none";
					}else{
						altBirimListele();
					}
				}
			}
		});
	}
}
function altBirimListele(){
	$.ajax({
		type: "POST",
		url: "Pages/IceAktarimlar/altBirimList.php",
		success: function(res){
			$('#altBirimList').html(res);
		}
	});
}
function altBirimModal(){
	altBirimTemizle();
	$("#altBirimEkle").modal("show");
}
function altBirimTemizle(){
	fileInputTemizleMulti('gorseller');
}
////// -Alt Birim İşlemler
</script>
