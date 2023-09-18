<?php
include ("../../System/Config.php");

$menuId=$_POST['menuId'];//tabloadı istenirse burdan değiştirilebilir

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

		if($kullaniciYetki['excel']=="on")
		{$tamExcelYetki=true;}//tam excel
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
else{//Listeleme Yetkisi Var

	$tableName=$hangiMenu['menuTabloAdi'];//tabloadı istenirse burdan değiştirilebilir

	$tabloPrimarySutun=$hangiMenu['menuTabloPrimarySutun'];//primarykey sutunu

	$baslik=$hangiMenu['menuAdi'];//başlıkta gözükecek yazı menu adi

	$duzenlemeSayfasi=$tableName.'/'.strtolower($tableName).'Kayit.php';
	$detaysayfasi=$tableName.'/'.strtolower($tableName).'Detay.php';

	if($_POST['sil']==""){
		//sayfayı görüntülenme logları
		if($sabitB['sabitBilgiLog']==1 && $_POST["page"]==""){
			$fonk->logKayit(6,$_SERVER['REQUEST_URI']);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
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
	if ($_POST["page"]=="" || $_POST["page"]==0) {
		$_POST["page"]=1;
	}
	if ($Ara!="") {
		$fonk->csrfKontrol();
	}
	///----- Saylafama Sorgu
	$Ara=$_POST['Ara'];
	$sartlar=[];
	if ($Ara!="") {
		$sartlar=array_merge($sartlar,["logIslem[~]" => $Ara]);
	}
	if ($_POST["kullaniciIdPost"]!="") {
		$sartlar=array_merge($sartlar,["logKullaniciId" => $_POST["kullaniciIdPost"]]);
	}
	//toplam veri
	$totalRecord = $db->count($tableName, $sartlar);

	$pageLimit = 20;
	// sayfa parametresi? Örn: index.php?page=2 [page = $pageParam]
	$pageParam = 'page';
	// limit için start ve limit değerleri hesaplanıyor
	$pagination = $fonk->paginationAjax($totalRecord, $pageLimit, $pageParam);

	$sartlar=array_merge($sartlar,[
		"ORDER" => [
			$tabloPrimarySutun => "DESC"
		],
		'LIMIT' => [$pagination['start'], $pagination['limit']]
	]);
	//normal sorgumuz
	$listeleme = $db->select($tableName, [
		"[>]Kullanicilar" => ["Log.logKullaniciId" => "kullaniciId"]
	],"*",$sartlar);
	///----- Saylafama Sorgu
	if($Ara!=""){echo '<script type="text/javascript">document.getElementById("Ara").value="'.$Ara.'"; $("#Ara").focus();</script>';}
	//****** tam excel alma bas
	if($_POST['ExceleAktar']=='ok')
	{
		$listelemeExcel = $db->select($tableName, [
			"[>]Kullanicilar" => ["Log.logKullaniciId" => "kullaniciId"]
		],"*",[
			"ORDER" => [
				$tabloPrimarySutun => "DESC"
			]
		]);
		$sayac=1;
		$ExportData[0]=array('Ad Soyad','Email');///başlıklar
		foreach ($listelemeExcel as $satir) {//içerikler
			$ExportData[$sayac]=array($satir['ikonKod'],$satir['kullaniciEmail']);
			$sayac++;
		}
		//otomatik excel alma
		/*
		$basliklar=array();
		$ExportData=array();
		foreach ($listelemeExcel[0] as $baslikKey => $baslik) {
		if ($baslikKey!="' '") {//excelde gösterilmeyecekler atlanıyor
		array_push($basliklar,$baslikKey);
	}
	}
	$ExportData[0]=$basliklar;
	$sayac=1;
	foreach ($listelemeExcel as $key => $satir) {
	$satirArray=array();
	foreach ($ExportData[0] as $key => $baslik) {
	array_push($satirArray,$satir[$baslik]);
	}
	$ExportData[$sayac]=$satirArray;
	$sayac++;
	}
	*/
//!otomatik excel alma
$_SESSION["excel"]=$ExportData;
$_SESSION["excelTablo"]=$tableName;
echo '<script type="text/javascript">window.location = "Pages/excel.php"</script>';
}
//****** tam excel alma bitis
?>
<!-- Listeleme Tablosu-->
<section id="html5">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title"><?=$fonk->getPDil($baslik)?></h4>
					<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
					<div class="heading-elements">
						<input type="text" onkeyup="AramaYap(event)" name="Ara" id="Ara" class="form-control form-control-sm" style="display: initial;width: auto;margin-right: 0.75rem;" placeholder="<?=$fonk->getPDil("Arama")?>">
						<?php if($tamExcelYetki){?>
							<button type="button" onclick="ExceleAktarma('<?=$menuId?>');" class="btn mr-1 btn-outline-warning btn-sm"><i class="la la-print"></i> <?=$fonk->getPDil("Tam Excel")?></button>
						<?php } if($eklemeYetki){?>
							<button type="button" onclick="SayfaGetir('<?=$menuId?>','<?=$duzenlemeSayfasi?>');" class="btn mr-1 btn-primary btn-sm"><i class="la la-plus-circle"></i> <?=$fonk->getPDil("Yeni Ekle")?></button>
						<?php } ?>
					</div>

				</div>
				<div class="card-content collapse show">
					<div class="card-body card-dashboard">
						<!-- Hoverable rows start -->
						<div class="table-responsive">
							<div class="col-12">
								<div class="card">
									<div class="card-content collapse show">
										<div class="table-responsive">
											<table class="table table-hover mb-0">
												<thead>
													<tr>
														<th><?=$fonk->getPDil("ID")?></th>
														<th><?=$fonk->getPDil("Adı Soyadı")?></th>
														<th><?=$fonk->getPDil("Durumu")?></th>
														<th><?=$fonk->getPDil("Kayıt Tarihi")?></th>
														<th style="width:250px;text-align:center"><?=$fonk->getPDil("İşlemler")?></th>
													</tr>
												</thead>
												<tbody>
													<?php
													foreach($listeleme as $list){
														?>
														<tr id="trSatir-<?=$list[$tabloPrimarySutun];?>">
															<td><?=$list[$tabloPrimarySutun];?></td>

															<!-- Güncellenecek Kısımlar -->
															<td><?=$list['ikonKod'];?></td>
															<td><?php if($list['kullaniciDurum']==1){?><div class="badge badge-success"><?=$fonk->getPDil("Aktif")?></div><?php } else{ ?><div class="badge badge-danger"><?=$fonk->getPDil("Pasif")?></div><?php } ?></td>
															<td><?=$fonk->sqlToDateTime($list['musteriKayitTarihi']);?></td>
															<!-- /Güncellenecek Kısımlar -->

															<td style="text-align:center">
																<div class="btn-group btn-group-sm" role="group">
																	<button type="button" onclick="veriDetay('<?=$list[$tabloPrimarySutun];?>');" class="btn btn-info"><i class="la la-external-link"></i> <?=$fonk->getPDil("Detay")?></button>
																	<?php if($duzenlemeYetki){?><button type="button" onclick="SayfaGetir('<?=$menuId?>','<?=$duzenlemeSayfasi?>','<?=$list[$tabloPrimarySutun];?>');" class="btn btn-success edit-button"><i class="la la-edit"></i> <?=$fonk->getPDil("Düzenle")?></button><?php } ?>
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
								<div class="text-center mb-2">
									<nav aria-label="Page navigation">
										<ul class="pagination justify-content-center pagination-separate pagination-round">
											<?php echo $fonk->showPaginationAjax('javascript:sayfalama('.$menuId.',[page]);');?>
										</ul>
									</nav>
								</div>
								<small style="float:left"><?=$fonk->getPDil("Toplam Kayıt")?>: <?=$totalRecord?></small> <small style="float:right"><?=$fonk->getPDil("Toplam Sayfa")?>: <?=ceil($totalRecord/$pageLimit)?></small>
							</div>
						</div>
						<!-- Hoverable rows end -->
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!--/ Listeleme Tablosu -->

<!-- detay modalı -->
<div id="detaylari">

</div>
<?php } ?>
<script type="text/javascript">
function veriDetay(detayId){
	$.ajax({
		type: "POST",
		url: "Pages/<?=$detaysayfasi?>",
		data:{'baslik':'<?=$fonk->getPDil($baslik)?>','tableName':'<?=$tableName?>','tabloPrimarySutun':'<?=$tabloPrimarySutun?>','detayId':detayId},
		success: function(res){
			$('#detaylari').html(res);
			$("#fadeIn").modal("show");
		},
		error: function (jqXHR, status, errorThrown) {
			alert("Result: "+status+" Status: "+jqXHR.status);
		}
	});
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

function sayfalama(menuId,page){
	var Ara=document.getElementById("Ara").value;
	$('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
	sessionStorage.setItem("dPage",page);
	sessionStorage.setItem("dSearch",Ara);
	sessionStorage.setItem("dLink",_sayfa);
	var data=new FormData();
	data.append("menuId",menuId);
	data.append("page",page);
	data.append("Ara",Ara);
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

function AramaYap(e){
	var Ara=document.getElementById("Ara").value;
	if(e.keyCode == 13){
		$('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
		sessionStorage.setItem("dPage",1);
		sessionStorage.setItem("dSearch",Ara);
		sessionStorage.setItem("dLink",_sayfa);
		$.ajax({
			type: "POST",
			url: "<?=$_SERVER['REQUEST_URI']?>",
			data:{'menuId':'<?=$menuId?>','Ara':Ara},
			success: function(res){
				$('#Sayfalar').html(res);
			},
			error: function (jqXHR, status, errorThrown) {
				alert("Result: "+status+" Status: "+jqXHR.status);
			}
		});
	}
}


$(document).ready(function() {
	var Ara=document.getElementById("Ara").value;
	if ("<?=$_SERVER['REQUEST_URI']?>".indexOf(sessionStorage.getItem("dLink")) && sessionStorage.getItem("dPage")!="" && sessionStorage.getItem("dPage")!=<?=$_POST["page"]?>) {
		document.getElementById("Ara").value=sessionStorage.getItem("dSearch");
		sayfalama(<?=$menuId?>,parseInt(sessionStorage.getItem("dPage")));
		setTimeout(function() {
			if(sessionStorage.getItem("editId")!="" && sessionStorage.getItem("editId")!=null){
				$('html, body').animate({
					scrollTop: $("#trSatir-"+sessionStorage.getItem("editId")).offset().top-200
				},700);
				document.getElementById("trSatir-"+sessionStorage.getItem("editId")).classList.add("editSatir");
			}
		},700);
	}
});
</script>
