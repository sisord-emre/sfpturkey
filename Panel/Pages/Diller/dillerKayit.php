<?php
include ("../../System/Config.php");

$menuId=$_POST['menuId'];//menu id alınıyor

///menu bilgileri alınıyor
$hangiMenu = $db->get("Menuler", "*", [
	"menuUstMenuId" => $menuId,
	"menuOzelGorunuruk" =>	1,
	"menuTipi" =>	2 //kayıt için 1 listeleme için 2 diğer sayfalar içim 3 yazılmalı****
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
		$kontrol=$fonk->imageResizeUpload($_FILES['dilGorsel'],'../../Images/Ayarlar/',$dilKodu,160,110,png);//boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner

		if ($dilDurumu==0) {
			$dilDurumu=0;
		}
		$dilKodu=$fonk->toSeo($dilKodu);
		if($primaryId!=""){
			//günclelemedeki parametreler
			$parametreler=array(
				'dilKodu' => $dilKodu,
				'dilAdi' => $dilAdi,
				'dilDurumu' => $dilDurumu
			);
		}else{
			//eklemedeki parametreler
			$parametreler=array(
				'dilKodu' => $dilKodu,
				'dilAdi' => $dilAdi,
				'dilDurumu' => $dilDurumu,
				'dilPanelDurumu' => 0,
				'dilKayitTarihi' => date("Y-m-d H:i:s")
			);
		}

		if($kontrol==1){//eğer duruma göre boş bırakılabiliyor ise parametre, sonradan arraye eklenir
			$parametreler=array_merge($parametreler,array('dilGorsel' => $dilKodu.".png"));
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
												<label for="dilAdi"><?=$fonk->getPDil("Dil Adı")?></label>
												<input type="text" id="dilAdi" class="form-control border-primary" placeholder="<?=$fonk->getPDil("Türkçe,İngilizce")?>..." name="dilAdi" value="<?=$Listeleme['dilAdi']?>" maxlength="250" autocomplete="off" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="dilKodu"><?=$fonk->getPDil("Dil Kodu")?></label>
												<input type="text" id="dilKodu" class="form-control border-primary" placeholder="<?=$fonk->getPDil("tr,eng")?>..." name="dilKodu" maxlength="3" value="<?=$Listeleme['dilKodu']?>" autocomplete="off" required>
											</div>
										</div>
									</div>

									<div class="row" style="margin-top: 15px;">
										<div class="col-md-6">
											<div class="form-group">
												<label for="dilGorsel"><?=$fonk->getPDil("Dil Görseli (160x110px-png)")?></label>
												<div class="custom-file">
													<input type="file" class="custom-file-input" accept=".png" name="dilGorsel" id="dilGorsel" <?php if ($primaryId=="") {echo "required";}?>>
													<label class="custom-file-label" name="dilGorsel" id="dilGorsel" for="dilGorsel" aria-describedby="dilGorsel"><?=$fonk->getPDil("Dosya Seçiniz")?></label>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="dilDurumu"><?=$fonk->getPDil("Dil Durumu")?></label>
												<fieldset>
													<div class="float-left">
														<input type="checkbox" class="switch hidden" data-on-label="<?=$fonk->getPDil("Aktif")?>" data-off-label="<?=$fonk->getPDil("Pasif")?>" id="dilDurumu" name="dilDurumu" value="1" <?php if($Listeleme['dilDurumu']==1){echo 'checked';}?> >
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

	<?php
	if ($primaryId!="" && $duzenlemeYetki) {
		?>
		<section id="html5">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"><?=$fonk->getPDil("Dil Değerleri")?></h4>
							<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
						</div>
						<div class="card-content collapse show">
							<div class="card-body card-dashboard">

								<div class="table-responsive">
									<form id="degerpost" class="form" action="" method="post">
										<table class="table table-striped table-bordered dataex-html5-export">
											<thead>
												<tr>
													<th style="width:40px;"><?=$fonk->getPDil("ID")?></th>
													<th style="width: 30%;"><?=$fonk->getPDil("Dil Keyi")?></th>
													<th><?=$fonk->getPDil("Dil Değeri")?></th>
												</tr>
											</thead>
											<tbody>
												<?php
												$DilKeyler = $db->select("DilKeyler", "*");
												foreach($DilKeyler as $list){
													$dilDeger = $db->get("DilDegerleri", "*", [
														"dilDegerKeyId" => $list["dilKeyId"],
														"dilDegerDilId" => $primaryId
													]);

													$varsayilanDilDeger = $db->get("DilDegerleri",[
					                  "[>]Diller" => ["DilDegerleri.dilDegerDilId" => "dilId"]
					                ],"*",[
														"dilDegerKeyId" => $list["dilKeyId"],
														"dilDegerDilId" => $sabitB["sabitBilgiVarsayilanDilId"]
													]);
													?>
													<tr id="trSatir-<?=$list["dilKeyId"];?>">
														<td><?=$list["dilKeyId"];?></td>

														<!-- Güncellenecek Kısımlar -->
														<td class="pointer" onclick="kopyala('key-<?=$list["dilKeyId"];?>',2)" id="key-<?=$list["dilKeyId"];?>">
															<span data-toggle="popover" data-original-title="<?=$varsayilanDilDeger["dilAdi"]?> <?=$fonk->getPDil("Karşılığı")?>" data-content="<?=$varsayilanDilDeger["dilDegerYazi"]?>" data-trigger="hover"><?=$list['dilKeyKodu'];?></span>
														</td>
														<td><input type="text" name="dilDegerYazi-<?=$list["dilKeyId"];?>" id="dilDegerYazi-<?=$list["dilKeyId"];?>" class="form-control border-primary" value="<?=$dilDeger['dilDegerYazi']?>" onkeyup="TirnakSil(this)" autocomplete="off"></td>
														<!-- /Güncellenecek Kısımlar -->
													</tr>
												<?php } ?>
											</tbody>
											<tfoot>
												<tr>
													<th colspan="3" style="text-align:center">
														<input type="hidden" name="dilDegerDilId" value="<?=$primaryId?>"/>
														<input type="hidden" name="token" value="<?=$_SESSION['token']?>" />
														<button type="submit" class="btn mb-1 mt-1 btn-success" id="degerButton"><i class="la la-floppy-o"></i> <?=$fonk->getPDil("Değerleri Güncelle")?></button>
													</th>
												</tr>
											</tfoot>
										</table>
									</form>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

	<?php }  }include("../../Scripts/kayitJs.php");?>
	<script type="text/javascript">
	$('#formpost').submit(function (e) {
		e.preventDefault(); //submit postu kesyoruz
		var data=new FormData(this);
		//data.append('ckeditor', CKEDITOR.instances['ckeditor'].getData());//ckeditor kullanılacağı zaman açılır 'ckeditor' yazan kısmı post keyidir
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

	$('#degerpost').submit(function (e) {
		e.preventDefault(); //submit postu kesyoruz
		var data=new FormData(this);
		document.getElementById('degerButton').disabled=true;
		$.ajax({
			type: "POST",
			url: "Pages/Diller/dildegerKayit.php",
			data:data,
			contentType:false,
			processData:false,
			success: function(res){
				if (res==1) {
					alert("<?=$fonk->getPDil("Değerler Başarıyla Kaydedilmiştir.")?>");
				}else {
					alert(res);
				}
				document.getElementById('degerButton').disabled=false;
			},
			error: function (jqXHR, status, errorThrown) {
				alert("Result: "+status+" Status: "+jqXHR.status);
			}
		});
	});
	</script>
