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
		$fonk->logKayit(6,$_SERVER['REQUEST_URI']);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
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
	$sartlar=[];
	
	$sartlar=array_merge($sartlar,[
		"ORDER" => [
			$tabloPrimarySutun => "DESC"
		]
	]);
	$listeleme = $db->select($tableName,"*",$sartlar);
	//****** tam excel alma bas
	$sayac=1;
	$ExportData[0]=array('Ad Soyad','Email','Tel','Durum','Mesaj','Dil','Kayit Tarihi');///başlıklar
	foreach ($listeleme as $satir) {//içerikler
		$ExportData[$sayac]=array($satir['iletisimFormAdSoyad'],$satir['iletisimFormEmail'],$satir['iletisimFormTel'],$satir['iletisimFormDurum'],$satir['iletisimFormMesaj'],$satir['dilAdi'],$fonk->sqlToDateTime($satir['iletisimFormKayitTarihi']));
		$sayac++;
	}
	$_SESSION["excel"]=$ExportData;
	$_SESSION["excelTablo"]=$tableName;
	//****** tam excel alma bitis
	?>
	<!-- Datatable sıralaması için -->
	<script>
	$(document).ready(function () {
		var orderDt=[0, 'desc'];
		if ("<?=$_SERVER['REQUEST_URI']?>".indexOf(sessionStorage.getItem("dLink")) && sessionStorage.getItem("orderDt")!=null && sessionStorage.getItem("orderDt")!="" && sessionStorage.getItem("orderDt")!="null") {
			 sessionStorage.getItem("orderDt").split(",");
			 orderDt=[sessionStorage.getItem("orderDt").split(",")[0],sessionStorage.getItem("orderDt").split(",")[1]];
		}
		table = $('#listTable').DataTable({
			order: orderDt,
			dom: 'Bfrtip',
			pageLength: 10,
			lengthMenu: [
				[ 10, 25, 50, -1 ],
				['10 <?=$fonk->getPDil("satır")?>', '25 <?=$fonk->getPDil("satır")?>', '50 <?=$fonk->getPDil("satır")?>', '<?=$fonk->getPDil("Tamamı")?>']
			],
			buttons: [
				{
					extend: 'pageLength',
					text: '<?=$fonk->getPDil("satır")?>',
					titleAttr: '<?=$fonk->getPDil("Sayfada Gösterilecek Satir Sayısı")?>',

				},
				<?php if($tamExcelYetki){?>
					{
						extend: 'copyHtml5',
						title: '<?=$tableName."-".date("d.m.Y H:i:s")?>',
						text:'<?=$fonk->getPDil("Kopyala")?>',
						exportOptions: {
							columns: ':visible',
							stripHtml: true//html temizler
						}
					},
					{
						extend: 'excelHtml5',
						title: '<?=$tableName."-".date("d.m.Y H:i:s")?>',
						text:'<?=$fonk->getPDil("Excel")?>',
						exportOptions: {
							columns: ':visible',
							stripHtml: true//html temizler
						}
					},
					{
						extend: 'pdfHtml5',
						orientation: 'landscape',//yatay içi
						title: '<?=$tableName."-".date("d.m.Y H:i:s")?>',
						text:'<?=$fonk->getPDil("PDF")?>',
						exportOptions: {
							columns: ':visible',
							stripHtml: true//html temizler
						}
					},
					{
						extend: 'print',
						title: '<?=$tableName."-".date("d.m.Y H:i:s")?>',
						text:'<?=$fonk->getPDil("Yazdır")?>',
						exportOptions: {
							columns: ':visible',
							stripHtml: true//html temizler
						}
					},
					<?php } ?>
					{
						extend: 'colvis',
						text: '<?=$fonk->getPDil("Gizle")?>'
					},
				],
				language: {
					"emptyTable":     "<?=$fonk->getPDil("Tabloda veri yok")?>",
					"info":           "<?=$fonk->getPDil("Gösterilen")?> _START_ <?=$fonk->getPDil("den")?> _END_ <?=$fonk->getPDil("Toplam")?> _TOTAL_ <?=$fonk->getPDil("Adet")?>",
					"infoEmpty":      "<?=$fonk->getPDil("Gösterilen")?> 0 <?=$fonk->getPDil("den")?> 0 <?=$fonk->getPDil("Toplam")?> 0 <?=$fonk->getPDil("Adet")?>",
					"infoFiltered":   "(<?=$fonk->getPDil("Toplam")?> _MAX_ <?=$fonk->getPDil("Girişten Filtrelendi")?>)",
					"lengthMenu":     "<?=$fonk->getPDil("Gösterilen")?> _MENU_ <?=$fonk->getPDil("Adet")?>",
					"loadingRecords": "<?=$fonk->getPDil("Yükleniyor")?>...",
					"processing":     "<?=$fonk->getPDil("İşleniyor")?>...",
					"search":         "<?=$fonk->getPDil("Arama")?>:",
					"zeroRecords":    "<?=$fonk->getPDil("Eşleşme Bulunamadı.")?>",
					'paginate': {
						'previous': '<?=$fonk->getPDil("Önceki")?>',
						'next': '<?=$fonk->getPDil("Sonraki")?>'
					}
				}
			});
			if ("<?=$_SERVER['REQUEST_URI']?>".indexOf(sessionStorage.getItem("dLink"))) {
				if (sessionStorage.getItem("dSearch")!=null && sessionStorage.getItem("dSearch")!="" && sessionStorage.getItem("dSearch")!="null") {
					table.search(sessionStorage.getItem("dSearch")).draw();
				}
				if (sessionStorage.getItem("dPage")!=null && sessionStorage.getItem("dPage")!="" && sessionStorage.getItem("dPage")!="null") {
					table.page(parseInt(sessionStorage.getItem("dPage"))).draw(false);
				}
				if(sessionStorage.getItem("editId")!="" || sessionStorage.getItem("editId")!=null){
					if ($("#trSatir-"+sessionStorage.getItem("editId")).offset()!=null) {
						$('html, body').animate({
							scrollTop: $("#trSatir-"+sessionStorage.getItem("editId")).offset().top-200
						},700);
					}
				}
				setTimeout(function() {
					if(document.getElementById("trSatir-"+sessionStorage.getItem("editId"))!=null){
						document.getElementById("trSatir-"+sessionStorage.getItem("editId")).classList.add("editSatir");
					}
				},700);
			}
			$(".dataTables_filter input").on('keyup change',function(){
				var pageInfo = table.page.info();
				var dSearch = table.search();
				sessionStorage.setItem("dPage",pageInfo["page"]);
				sessionStorage.setItem("dSearch",dSearch);
				sessionStorage.setItem("dLink",_sayfa);
			});
			$(".dataTables_paginate").click(function(){
				var pageInfo = table.page.info();
				var dSearch = table.search();
				sessionStorage.setItem("dPage",pageInfo["page"]);
				sessionStorage.setItem("dSearch",dSearch);
				sessionStorage.setItem("dLink",_sayfa);
			});
		});
		</script>
		<!--/ Datatable sıralaması içine -->

		<!-- HTML5 export buttons table -->
		<section id="html5">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"><?=$fonk->getPDil($baslik)?></h4>
							<a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
							<div class="heading-elements">
								<?php if($tamExcelYetki){?>
									<a href="Pages/excel.php" class="btn mr-1 btn-outline-warning btn-sm"><i class="la la-print"></i> <?=$fonk->getPDil("Tam Excel")?></a>
								<?php } if($eklemeYetki){?>
								<?php } ?>
							</div>
						</div>
						<div class="card-content collapse show">
							<div class="card-body card-dashboard">

								<div class="table-responsive">
									<table class="table table-striped table-bordered dataex-html5-export" id="listTable">
										<thead>
											<tr>
												<th><?=$fonk->getPDil("ID")?></th>
												<th><?=$fonk->getPDil("Adı Soyadı")?></th>
												<th><?=$fonk->getPDil("Email")?></th>
												<th><?=$fonk->getPDil("Tel")?></th>
												<th><?=$fonk->getPDil("Durumu")?></th>
												<th><?=$fonk->getPDil("Kayıt Tarihi")?></th>
												<th style="width:150px;text-align:center"><?=$fonk->getPDil("İşlemler")?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach($listeleme as $list){
												?>
												<tr id="trSatir-<?=$list[$tabloPrimarySutun];?>">
													<td><?=$list[$tabloPrimarySutun];?></td>

													<!-- Güncellenecek Kısımlar -->
													<td><?=$list['iletisimFormAdSoyad'];?></td>
													<td><?=$list['iletisimFormEmail'];?></td>
													<td><?=$list['iletisimFormTel'];?></td>
													<td data-sort="<?=$list['iletisimFormDurum']?>">
														<select class="form-control" id="durum-<?=$list[$tabloPrimarySutun];?>" onchange="DurumDegistir(<?=$list[$tabloPrimarySutun];?>)">
															<option value="0" <?php if($list['iletisimFormDurum']==0){ echo "selected";}?>><?=$fonk->getPDil("Beklemede")?></option>
															<option value="1" <?php if($list['iletisimFormDurum']==1){ echo "selected";}?>><?=$fonk->getPDil("Okundu")?></option>
														</select>
													</td>
													<td data-sort="<?=$fonk->sqlToDateTimeTiresiz($list['iletisimFormKayitTarihi']);?>"><?=$fonk->sqlToDateTime($list['iletisimFormKayitTarihi']);?></td>
													<!-- /Güncellenecek Kısımlar -->

													<td style="text-align:center">
														<div class="btn-group btn-group-sm" role="group">
															<button type="button" onclick="veriDetay('<?=$list[$tabloPrimarySutun];?>');" class="btn btn-info"><i class="la la-external-link"></i> <?=$fonk->getPDil("Detay")?></button>
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
		<!--/ HTML5 export buttons table -->


		<!-- detay modalı -->
		<div id="detaylari">

		</div>

	<?php } include("../../Scripts/listelemeJs.php");?>
	<script type="text/javascript">
	function DurumDegistir(Id){
		var e = document.getElementById("durum-"+Id);
		var durum = e.value;
		$.ajax({
			type: "POST",
			url: "Pages/IletisimFormlari/durumDegistir.php",
			data:{'Id':Id,'durum':durum},
			success: function(res){
				if(res=='1'){
					toastr.success('<?=$fonk->getPDil("Güncelleme Sağlandı.")?>');
				}else{
					alert(res);
				}
			}
		});
	}

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
</script>
