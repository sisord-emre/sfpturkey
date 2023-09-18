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

	$tableName="PanelDilKeyler";//tabloadı istenirse burdan değiştirilebilir

	$tabloPrimarySutun="panelDilKeyId";//primarykey sutunu

	$baslik="Dil Keyler";//başlıkta gözükecek yazı menu adi

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
		$dilDegeri=$panelDilKeyKodu;
		$panelDilKeyKodu=mb_substr($fonk->toSeo($panelDilKeyKodu), 0, 100, 'UTF-8');
		if($primaryId!=""){
			//günclelemedeki parametreler
			$parametreler=array(
				'panelDilKeyKodu' => $panelDilKeyKodu
			);
		}else{
			//eklemedeki parametreler
			$parametreler=array(
				'panelDilKeyKodu' => $panelDilKeyKodu
			);
		}
		if($primaryId!=""){
			$seoKontrol = $db->get($tableName, "*", [
				"panelDilKeyId[!]" => $primaryId,
				"panelDilKeyKodu" => $panelDilKeyKodu
			]);
			if ($seoKontrol) {
				echo '
				<div class="alert alert-warning alert-dismissible mb-2" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
				<strong>'.$fonk->getPDil("Uyarı!").'</strong> '.$fonk->getPDil("Bu Key Daha Önce Eklenmiştir. Lütfen Başka Bir Key Deneyiniz.").'
				</div>';
			}else {
				$fonk->logKayit(2,$tableName.' ; '.$primaryId.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				///güncelleme
				$query = $db->update($tableName, $parametreler, [
					$tabloPrimarySutun => $primaryId
				]);
			}
		}
		else{
			$seoKontrol = $db->get($tableName, "*", [
				"panelDilKeyKodu" => $panelDilKeyKodu
			]);
			if ($seoKontrol) {
				echo '
				<div class="alert alert-warning alert-dismissible mb-2" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
				<strong>'.$fonk->getPDil("Uyarı!").'</strong> '.$fonk->getPDil("Bu Key Daha Önce Eklenmiştir. Lütfen Başka Bir Key Deneyiniz.").'
				</div>';
			}else {
				$fonk->logKayit(1,$tableName.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				///ekleme
				$query = $db->insert($tableName, $parametreler);

				$primaryId=$db->id();
			}
		}

		if ($query && $dilDegerEkle==1) {
			$degerKontrol = $db->get("PanelDilDegerleri", "*", [
				"panelDilDegerDilId" => $sabitB["sabitBilgiVarsayilanDilId"],
				"panelDilDegerKeyId" => $primaryId
			]);

			if ($degerKontrol) {
				$silDeger = $db->delete("PanelDilDegerleri", [
					"panelDilDegerId" => $degerKontrol["panelDilDegerId"]
				]);
			}
			$degeriEkle = $db->insert("PanelDilDegerleri", [
				'panelDilDegerDilId' => $sabitB["sabitBilgiVarsayilanDilId"],
				'panelDilDegerKeyId' => $primaryId,
				'panelDilDegerYazi' => str_replace("\"","'",trim($dilDegeri))
			]);
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

	///silme
	if($_POST['sil']!="" && $silmeYetki){
		///Loglama İşlemi
		$logSilme = $db->get($tableName, "*", [
			$tabloPrimarySutun => $_POST['sil']
		]);
		$fonk->logKayit(3,$tableName.' ; '.json_encode($logSilme));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

		$sil = $db->delete($tableName, [
			$tabloPrimarySutun => $_POST['sil']
		]);
		if($sil){//uyarı metinleri
			echo 1;
			exit;
		}
		else{
			echo '
			<div class="alert alert-danger alert-dismissible mb-2" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
			</button>
			<strong>'.$fonk->getPDil("Hata!").' </strong> '.$fonk->getPDil("Silme Esnasında Bir Hata Oluştu. Lütfen Tekrar Deneyiniz.").'('.$db->error.')
			</div>';
		}
	}
	echo "<script>$('#ustYazi').html('&nbsp;-&nbsp;'+'".$fonk->getPDil($baslik)."');</script>";//Başlık Güncelleniyor
	//update ise bilgiler getiriliyor
	if($eklemeYetki || $duzenlemeYetki){
		?>
		<!-- Basic form layout section start -->
		<section id="basic-form-layouts">
			<div class="row match-height">
				<div class="col-md-12">
					<div class="card">
						<div class="card-content collapse show">
							<div class="card-body">
								<form id="formpost" class="form" action="" method="post">
									<div class="form-body">
										<!-- Güncellenecek Kısımlar -->
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="panelDilKeyKodu"><?=$fonk->getPDil("Dil Key Kodu")?></label>
													<input type="text" id="panelDilKeyKodu" class="form-control border-primary" placeholder="<?=$fonk->getPDil("ad-soyad,iletisim-formu")?>" name="panelDilKeyKodu" onkeyup="TirnakSil(this)" maxlength="250" autocomplete="off" required>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group" style="text-align:center">
													<label for="userinput1"><?=$fonk->getPDil("Varsayılan Dile, Değeri Ekle")?></label>
													<div class="row skin skin-square" style="text-align:center">
														<div class="col-md-12">
															<fieldset style="padding: 0.5rem;">
																<div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="dilDegerEkle" id="dilDegerEkle" value="1" style="position: absolute; opacity: 0;" checked></div>
															</fieldset>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group" style="margin-top:25px;">
													<input type="hidden" name="update" id="update" value="<?=$Listeleme[$tabloPrimarySutun]?>"/>
													<input type="hidden" name="menuId" value="<?=$menuId?>"/>
													<input type="hidden" name="formdan" value="1"/>
													<input type="hidden" name="token" value="<?=$_SESSION['token']?>" />
													<button type="submit" class="btn btn-success"><i class="la la-floppy-o"></i> <?=$fonk->getPDil("Kayıt")?></button>
												</div>
											</div>
										</div>
										<!-- /Güncellenecek Kısımlar -->
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- // Basic form layout section end -->
	<?php } if($listelemeYetki){?>
		<section id="html5">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"><?=$fonk->getPDil("Dil Keyleri")?></h4>
							<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
						</div>
						<div class="card-content collapse show">
							<div class="card-body card-dashboard">

								<div class="table-responsive">
									<table class="table table-striped table-bordered dataex-html5-export">
										<thead>
											<tr>
												<th style="width:40px;"><?=$fonk->getPDil("ID")?></th>
												<th><?=$fonk->getPDil("Dil Keyi")?></th>
												<th style="width:220px;text-align:center"><?=$fonk->getPDil("İşlemler")?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											$PanelDilKeyler = $db->select($tableName, "*", [
												"ORDER" => [
													"panelDilKeyId" => "DESC"
												]
											]);
											foreach($PanelDilKeyler as $list){
												?>
												<tr id="trSatir-<?=$list[$tabloPrimarySutun];?>">
													<td><?=$list[$tabloPrimarySutun];?></td>

													<!-- Güncellenecek Kısımlar -->
													<td onclick="kopyala('key-<?=$list[$tabloPrimarySutun];?>',2)" id="key-<?=$list[$tabloPrimarySutun];?>"><?=$list['panelDilKeyKodu'];?></td>
													<!-- /Güncellenecek Kısımlar -->

													<td style="text-align:center">
														<div class="btn-group btn-group-sm" role="group">
															<?php if($duzenlemeYetki){?><button type="button" onclick="veriDuzenle('<?=$list[$tabloPrimarySutun];?>','<?=$list['panelDilKeyKodu'];?>');" class="btn btn-success"><i class="la la-edit"></i> <?=$fonk->getPDil("Düzenle")?></button><?php } ?>
															<?php if($silmeYetki){?><button type="button" onclick="veriSil('<?=$menuId?>','<?=$list[$tabloPrimarySutun];?>');"  class="btn btn-danger"><i class="la la-trash-o"></i> <?=$fonk->getPDil("Sil")?></button><?php } ?>
														</div>
													</td>
												</tr>
											<?php } ?>
										</tbody>

									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php } }include("../../Scripts/kayitJs.php");?>
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

	function veriDuzenle(duzenleId,dilkodu){
		document.getElementById('update').value=duzenleId;
		document.getElementById('panelDilKeyKodu').value=dilkodu;
		$('html, body').animate({scrollTop:0}, '200');
	}

	function veriSil(menuId,sil){
		if(confirm('<?=$fonk->getPDil("Silmek İstediğinize Emin misiniz ?")?>')) {
			$.ajax({
				type: "POST",
				url: "<?=$_SERVER['REQUEST_URI']?>",
				data:{'menuId':menuId,'sil':sil},
				success: function(res){
					if (res==1) {
						document.getElementById('trSatir-'+sil).style.display="none";
					}else {
						$('#Sayfalar').html(res);
					}
				},
				error: function (jqXHR, status, errorThrown) {
					alert("Result: "+status+" Status: "+jqXHR.status);
				}
			});
		}
	}
</script>
