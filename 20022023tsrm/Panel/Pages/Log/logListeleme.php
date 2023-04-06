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
			///Loglama İşlemi
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
	if ($_POST["page"]>ceil($totalRecord/$pageLimit)) {
		$_POST["page"]=ceil($totalRecord/$pageLimit);
	}
	if ($_POST["page"]=="" || $_POST["page"]==0) {
		$_POST["page"]=1;
	}

	if ($Ara!="" || $_POST["kullaniciIdPost"]!="" || $_POST["logIslemTipiPost"]!="" || $_POST["logIslemBasPost"]!="" || $_POST["logIslemBitPost"]!="") {
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
	if ($_POST["logIslemTipiPost"]!="") {
		$sartlar=array_merge($sartlar,["logIslemTipi" => $_POST["logIslemTipiPost"]]);
	}
	if ($_POST["logIslemBasPost"]!="") {
		$sartlar=array_merge($sartlar,["logTarih[>]" => $_POST["logIslemBasPost"]]);
	}
	if ($_POST["logIslemBitPost"]!="") {
		$sartlar=array_merge($sartlar,["logTarih[<]" => $_POST["logIslemBitPost"]]);
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
		$ExportData[0]=array($fonk->getPDil('Id'),$fonk->getPDil('Ad Soyad'),$fonk->getPDil('Durum'),$fonk->getPDil('Tablo Adı'),$fonk->getPDil('İşlem'),$fonk->getPDil('Kayıt Tarihi'));///başlıklar
		foreach ($listelemeExcel as $satir) {//içerikler
			$logTablo="";
			$logProses="";
			$durum="";
			switch ($satir['logIslemTipi']) {
				case 1:
				$logIslem=explode(' ; ',$satir['logIslem']);
				$logTablo=$logIslem[0];
				$logProses=$logIslem[1];
				$durum= $fonk->getPDil("Ekleme");
				break;
				case 2:
				$logIslem=explode(' ; ',$satir['logIslem']);
				$logTablo=$logIslem[0];
				$logProses="primaryId:".$logIslem[1]." - ".$logIslem[2];
				$durum= $fonk->getPDil("Güncelleme");
				break;
				case 3:
				$logIslem=explode(' ; ',$satir['logIslem']);
				$logTablo=$logIslem[0];
				$logProses=$logIslem[1];
				$durum= $fonk->getPDil("Silme");
				break;
				case 4:
				$logProses=$satir['logIslem'];
				$durum= $fonk->getPDil("Oturum Açma");
				break;
				case 5:
				$logIslem=explode(' ; ',$satir['logIslem']);
				$logTablo=$logIslem[1];
				$logProses=$logIslem[2];
				$durum= $fonk->getPDil("Excel Aktarım");
				break;
				case 6:
				$logProses=$satir['logIslem'];
				$durum= $fonk->getPDil("Gösterim");
				break;
				default:
				$logProses=$satir['logIslem'];
				$durum= $fonk->getPDil("Diğer");
			}
			$ExportData[$sayac]=array($satir['logId'],$satir['kullaniciAdSoyad'],$durum,$logTablo,$logProses,$fonk->sqlToDateTime($satir['logTarih']));
			$sayac++;
		}
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
							<?php } ?>
						</div>

					</div>
					<div class="card-content collapse show">
						<div class="card-body card-dashboard">
							<form id="formpost" class="form" action="" method="post">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="userinput1"><?=$fonk->getPDil("Kullanıcılar")?></label>
											<select class="select2 form-control block" name="kullaniciIdPost" id="kullaniciIdPost" >
												<option value=""><?=$fonk->getPDil("Seçiniz")?></option>
												<?php
												$sorguList = $db->select("Kullanicilar","*",[
													"ORDER" => [
														"kullaniciAdSoyad" => "ASC"
													]
												]);
												foreach($sorguList as $sorgu){
													?>
													<option value="<?=$sorgu['kullaniciId']?>" <?php if($sorgu['kullaniciId']==$_POST['kullaniciIdPost']){echo " selected";}?>><?=$sorgu['kullaniciAdSoyad']?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="userinput1"><?=$fonk->getPDil("Durum")?></label>
											<select class="select2 form-control block" name="logIslemTipiPost" id="logIslemTipiPost" >
												<option value=""><?=$fonk->getPDil("Seçiniz")?></option>
												<option value="1" <?php if($_POST['logIslemTipiPost']==1){echo " selected";}?>><?=$fonk->getPDil("Ekleme")?></option>
												<option value="2" <?php if($_POST['logIslemTipiPost']==2){echo " selected";}?>><?=$fonk->getPDil("Güncelleme")?></option>
												<option value="3" <?php if($_POST['logIslemTipiPost']==3){echo " selected";}?>><?=$fonk->getPDil("Silme")?></option>
												<option value="4" <?php if($_POST['logIslemTipiPost']==4){echo " selected";}?>><?=$fonk->getPDil("Oturum Açma")?></option>
												<option value="5" <?php if($_POST['logIslemTipiPost']==5){echo " selected";}?>><?=$fonk->getPDil("Excel Aktarım")?></option>
												<option value="6" <?php if($_POST['logIslemTipiPost']==6){echo " selected";}?>><?=$fonk->getPDil("Gösterim")?></option>
												<option value="7" <?php if($_POST['logIslemTipiPost']==7){echo " selected";}?>><?=$fonk->getPDil("Diğer")?></option>
											</select>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="userinput1"><?=$fonk->getPDil("Başlangıç T.")?></label>
											<input type="date" class="form-control" name="logIslemBasPost" id="logIslemBasPost" value="<?=$_POST['logIslemBasPost']?>">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="userinput1"><?=$fonk->getPDil("Bitiş T.")?></label>
											<input type="date" class="form-control" name="logIslemBitPost" id="logIslemBitPost" value="<?=$_POST['logIslemBitPost']?>">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<input type="hidden" name="token" value="<?=$_SESSION['token']?>" />
											<button type="submit" class="btn btn-warning" style="margin-top: 2rem !important;"><i class="la la-filter"></i> <?=$fonk->getPDil("Filtrele")?></button>
										</div>
									</div>
								</form>
								<!-- Hoverable rows start -->
								<div class="table-responsive">
									<div class="col-12">
										<div class="card">
											<div class="card-content collapse show">
												<div class="table-responsive">
													<table class="table table-hover mb-0">
														<thead>
															<tr>
																<th style="width:50px;"><?=$fonk->getPDil("ID")?></th>
																<th style="width:180px;"><?=$fonk->getPDil("Adı Soyadı")?></th>
																<th style="width:180px;"><?=$fonk->getPDil("Durum")?></th>
																<th style="width:180px;"><?=$fonk->getPDil("Tablo Adı")?></th>
																<th><?=$fonk->getPDil("İşlem")?></th>
																<th style="width:180px;"><?=$fonk->getPDil("Kayıt Tarihi")?></th>
																<th style="width:180px;text-align:center"><?=$fonk->getPDil("İşlemler")?></th>
															</tr>
														</thead>
														<tbody>
															<?php
															foreach($listeleme as $list){
																?>
																<tr id="trSatir-<?=$list[$tabloPrimarySutun];?>">
																	<td><?=$list[$tabloPrimarySutun];?></td>

																	<!-- Güncellenecek Kısımlar -->
																	<td><?=$list['kullaniciAdSoyad'];?></td>
																	<td>
																		<?php
																		$logTablo="";
																		$logProses="";
																		switch ($list['logIslemTipi']) {
																			case 1:
																			$logIslem=explode(' ; ',$list['logIslem']);
																			$logTablo=$logIslem[0];
																			$logProses=$logIslem[1];
																			echo $fonk->getPDil("Ekleme");
																			break;
																			case 2:
																			$logIslem=explode(' ; ',$list['logIslem']);
																			$logTablo=$logIslem[0];
																			$logProses="primaryId:".$logIslem[1]." - ".$logIslem[2];
																			echo $fonk->getPDil("Güncelleme");
																			break;
																			case 3:
																			$logIslem=explode(' ; ',$list['logIslem']);
																			$logTablo=$logIslem[0];
																			$logProses=$logIslem[1];
																			echo $fonk->getPDil("Silme");
																			break;
																			case 4:
																			$logProses=$list['logIslem'];
																			echo $fonk->getPDil("Oturum Açma");
																			break;
																			case 5:
																			$logIslem=explode(' ; ',$list['logIslem']);
																			$logTablo=$logIslem[1];
																			$logProses=$logIslem[2];
																			echo $fonk->getPDil("Excel Aktarım");
																			break;
																			case 6:
																			$logProses=$list['logIslem'];
																			echo $fonk->getPDil("Gösterim");
																			break;
																			default:
																			$logProses=$list['logIslem'];
																			echo $fonk->getPDil("Diğer");
																		}
																		?>
																	</td>
																	<td><?=$logTablo;?></td>
																	<td><?php print_r($logProses);?></td>
																	<td><?=$fonk->sqlToDateTime($list['logTarih']);?></td>
																	<!-- /Güncellenecek Kısımlar -->

																	<td style="text-align:center">
																		<div class="btn-group btn-group-sm" role="group">
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
	<!-- BEGIN: Page Vendor JS-->
	<script src="Assets/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
	<!-- END: Page Vendor JS-->
	<!-- BEGIN: Page JS-->
	<script src="Assets/app-assets/js/scripts/forms/select/form-select2.js"></script>
	<!-- END: Page JS-->

	<script type="text/javascript">
	$('#formpost').submit(function (e) {
		e.preventDefault(); //submit postu kesyoruz
		var data=new FormData(this);
		data.append("menuId",<?=$menuId?>);
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
		var kullaniciIdPost = $('#kullaniciIdPost').val();
		var logIslemTipiPost = $('#logIslemTipiPost').val();
		var logIslemBasPost= $('#logIslemBasPost').val();
		var logIslemBitPost= $('#logIslemBitPost').val();
		var Ara=document.getElementById("Ara").value;
		$('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
		sessionStorage.setItem("dPage",page);
		sessionStorage.setItem("dSearch",Ara);
		sessionStorage.setItem("dLink",_sayfa);
		var data=new FormData();
		data.append("menuId",menuId);
		data.append("page",page);
		data.append("Ara",Ara);
		data.append("kullaniciIdPost",kullaniciIdPost);
		data.append("logIslemTipiPost",logIslemTipiPost);
		data.append("logIslemBasPost",logIslemBasPost);
		data.append("logIslemBitPost",logIslemBitPost);
		data.append("token",'<?=$_SESSION['token']?>');
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
		$('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
		var data=new FormData();
		data.append('menuId',menuId);
		data.append('ExceleAktar','ok');
		data.append("token",'<?=$_SESSION['token']?>');
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
				data:{'menuId':'<?=$menuId?>','Ara':Ara,'token':'<?=$_SESSION['token']?>'},
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
		}
	});
	</script>
