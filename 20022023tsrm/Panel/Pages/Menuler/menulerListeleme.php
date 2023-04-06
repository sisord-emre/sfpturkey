<?php
include ("../../System/Config.php");

$menuId=$_POST['menuId'];//tabloadı istenirse burdan değiştirilebilir

///menu bilgileri alınıyor
$hangiMenu = $db->get("Menuler", "*", [
	"menuUstMenuId" => $menuId,
	"menuGorunurluk" =>	1,
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

		if($kullaniciYetki['excel']=="on")
		{$tamExcelYetki=true;}//duzenleme
	}
}
if(!$listelemeYetki)
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
else{
	$tableName=$hangiMenu['menuTabloAdi'];//tabloadı istenirse burdan değiştirilebilir

	$tabloPrimarySutun=$hangiMenu['menuTabloPrimarySutun'];//primarykey sutunu

	$baslik=$hangiMenu['menuAdi'];

	$duzenlemeSayfasi=$tableName.'/'.strtolower($tableName).'Kayit.php';
	$detaysayfasi=$tableName.'/'.strtolower($tableName).'Detay.php';

	if($_POST['update']==""){
		//sayfayı görüntülenme logları
		$fonk->logKayit(6,$_SERVER['REQUEST_URI']);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
	}

	//Forumdan gelenler
	$menuAdi=$_POST['menuadi'];
	$menuGorunurluk=$_POST['gorunurluk'];
	$menuSirasi=$_POST['sirasi'];
	$menuIcon=$_POST['ikon'];

	if($menuGorunurluk=="true"){
		$menuGorunurluk=true;
	}else{
		$menuGorunurluk=false;
	}

	if($_POST['update']){

		if($sabitB['sabitBilgiLog']==1){
			///Loglama İşlemi
			$log = $db->insert("Log", [
				'logKullaniciId' => intval($kulBilgi['kullaniciId']),//oturum id
				'logIslemTipi' => 2,//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				'logIslem' => $tableName.' ; '.$_POST['update'].' ; '.json_encode(array(
					'menuAdi' => $menuAdi,
					'menuGorunurluk' => $menuGorunurluk,
					'menuSirasi' => $menuSirasi,
					'menuIcon' => $menuIcon
				)),//yapılan işlme parametreleri
				'logTarih' => date("Y-m-d H:i:s")//yapılan zaman
			]);
		}

		$query = $db->update($tableName, [
			'menuAdi' => $menuAdi,
			'menuGorunurluk' => $menuGorunurluk,
			'menuSirasi' => $menuSirasi,
			'menuIcon' => $menuIcon
		], [
			$tabloPrimarySutun => $_POST['update']
		]);
		if($query){//uyarı metinleri
			echo '
			<div class="alert alert-success alert-dismissible mb-2" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
			</button>
			<strong>Başarılı!</strong> Güncelleme İşlemi Başarıyla Gerçekleşmiştir.
			</div>';
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

	///silme
	if($_POST['sil']!=""){

		if($sabitB['sabitBilgiLog']==1){
			///Loglama İşlemi
			$logSilme = $db->get($tableName, "*", [
				$tabloPrimarySutun => $_POST['sil']
			]);
			$fonk->logKayit(3,$tableName.' ; '.json_encode($logSilme));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
		}

		$sil = $db->delete($tableName, [
			$tabloPrimarySutun => $_POST['sil']
		]);

		if($query){//uyarı metinleri
			echo '
			<div class="alert alert-success alert-dismissible mb-2" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
			</button>
			<strong>Başarılı!</strong> Silme İşlemi Başarıyla Gerçekleşmiştir.
			</div>';
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
	$listeleme = $db->select($tableName, "*", [
		"menuOzelGorunuruk" =>	1,
		"ORDER" => [
			$tabloPrimarySutun => "DESC"
		]
	]);

	//****** tam excel alma bas
	$sayac=1;
	$ExportData[0]=array('Tablo Adı','Primary Sütun','Ust Menü ID','Menü Adı','Sayfa','Görünrülük','Sırası');///başlıklar
	foreach ($listeleme as $satir) {//içerikler

		$ExportData[$sayac]=array($satir['menuTabloAdi'],$satir['menuTabloPrimarySutun'],$satir['menuUstMenuId'],$satir['menuAdi'],$satir['menuSayfa'],$satir['menuGorunurluk'],$satir['menuSirasi']);
		$sayac++;
	}
	$_SESSION["excel"]=$ExportData;
	$_SESSION["excelTablo"]=$tableName;
	//****** tam excel alma bitis
	?>
	<script>
	$(document).ready(function () {
		$('#listTable').dataTable({
			"order": [0, 'desc'],
			dom: 'Bfrtip',
			pageLength: 10,
			buttons: [
				<?php if($tamExcelYetki){?>
					{
						extend: 'copyHtml5',
						exportOptions: {
							columns: ':visible'
						}
					},
					{
						extend: 'excelHtml5',
						exportOptions: {
							columns: ':visible'
						}
					},
					{
						extend: 'pdfHtml5',
						exportOptions: {
							columns: ':visible'
						}
					},
					<?php } ?>
					'colvis'
				]
			});
		});
		</script>

		<!-- HTML5 export buttons table -->
		<section id="html5">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"><?=$baslik;?> Tablosu</h4>
							<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
							<div class="heading-elements">
								<?php if($tamExcelYetki){?>
									<a href="Pages/excel.php" class="btn mr-1 btn-outline-warning btn-sm"><i class="la la-print"></i> <?=$fonk->getPDil("Tam Excel")?></a>
								<?php } ?>
							</div>
						</div>
						<div class="card-content collapse show">
							<div class="card-body card-dashboard">

								<div class="table-responsive">
									<table class="table table-striped table-bordered dataex-html5-export"  id="listTable">
										<thead>
											<tr>
												<th>ID</th>
												<th>Menü Adı</th>
												<th>Görünürlük</th>
												<th>Sırası</th>
												<th>İcon / Üst Menü ID</th>
												<th style="width:220px;">İşlemler</th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach($listeleme as $list){
												?>
												<tr>
													<td><?=$list[$tabloPrimarySutun];?></td>
													<td><input type="text" class="form-control" id="menuadi_<?=$list[$tabloPrimarySutun];?>" value="<?=$list['menuAdi'];?>" placeholder="Menü Adı"></td>
													<td>
														<div class="row skin skin-flat" style="padding: inherit;">
															<fieldset>
																<input type="checkbox" id="gorunurluk_<?=$list[$tabloPrimarySutun];?>" <?php if ($list['menuGorunurluk']=="1") {echo ' checked';}?>>
																<label for="gorunurluk_<?=$list[$tabloPrimarySutun];?>">Gösterilsin</label>
															</fieldset>
														</div>
													</td>
													<td><input type="number" class="form-control" id="sirasi_<?=$list[$tabloPrimarySutun];?>" placeholder="Sırası" value="<?=$list['menuSirasi']?>"></td>
													<td>
														<?php if($list['menuTipi']==0){?>
															<select class="selectBox" id="ikon_<?=$list[$tabloPrimarySutun];?>" style="width:50px>important;">
																<?php
																$ikonlar = $db->select("Ikonlar", "*");
																foreach($ikonlar as $ikon){
																	?>
																	<option value="<?=$ikon['ikonKod']?>" data-text='<i class="la <?=$ikon['ikonKod']?>"></i>' <?php if($list['menuIcon']==$ikon['ikonKod']){echo "selected";}?>></option>
																<?php } ?>
															</select>
														<?php } else{
															echo $list['menuUstMenuId'];
															?>
															<input type="hidden" id="ikon_<?=$list[$tabloPrimarySutun];?>" value=""/>
														<?php } ?>
													</td>
													<td>
														<div class="form-group">
															<div class="btn-group btn-group-sm" role="group">
																<button type="button" onclick="veriDuzenle('<?=$menuId?>','<?=$list[$tabloPrimarySutun];?>');" class="btn btn-success"><i class="la la-edit"></i> Güncelle</button>
																<button type="button" onclick="veriSil('<?=$menuId?>','<?=$list[$tabloPrimarySutun];?>');"  class="btn btn-danger"><i class="la la-trash-o"></i> <?=$fonk->getPDil("Sil")?></button>
															</div>
															<?php if($list['menuTipi']==0){ echo '<small>&emsp;*</small>';} ?>
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
		<!--/ HTML5 export buttons table -->


		<!-- detay modalı -->
		<div class="modal animated fadeIn text-left" id="fadeIn" tabindex="-1" role="dialog" aria-labelledby="baslikModal" aria-hidden="true">
			<!-- detay modalı -->
			<div class="modal-dialog" role="document" id="detaylari">
			</div>
		</div>

	<?php } include("../../Scripts/listelemeJs.php");?>

	<script type="text/javascript">

	function veriDuzenle(menuId,duzenleId){
		var menuadi=document.getElementById("menuadi_"+duzenleId).value;
		var gorunurluk=document.getElementById("gorunurluk_"+duzenleId).checked;
		var sirasi=document.getElementById("sirasi_"+duzenleId).value;
		var ikon=document.getElementById("ikon_"+duzenleId).value;

		$('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER['REQUEST_URI']?>",
			data:{'menuId':menuId,'update':duzenleId,'menuadi':menuadi,'gorunurluk':gorunurluk,'sirasi':sirasi,'ikon':ikon},
			success: function(res){
				$('#Sayfalar').html(res);
			},
			error: function (jqXHR, status, errorThrown) {
				alert("Result: "+status+" Status: "+jqXHR.status);
			}
		});
	}


	function veriSil(menuId,sil){
		if(confirm('<?=$fonk->getPDil("Silmek İstediğinize Emin misiniz ?")?>')) {
			$('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
			$.ajax({
				type: "POST",
				url: "<?=$_SERVER['REQUEST_URI']?>",
				data:{'menuId':menuId,'sil':sil},
				success: function(res){
					$('#Sayfalar').html(res);
				},
				error: function (jqXHR, status, errorThrown) {
					alert("Result: "+status+" Status: "+jqXHR.status);
				}
			});
		}
	}
</script>
