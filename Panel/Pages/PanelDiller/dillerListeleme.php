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

	$duzenlemeSayfasi='PanelDiller/'.strtolower($tableName).'Kayit.php';
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

		$degerSil = $db->delete("PanelDilDegerleri", [
			"panelDilDegerDilId" => $_POST['sil']
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
	$listeleme = $db->select($tableName, "*", [
		"ORDER" => [
			$tabloPrimarySutun => "DESC"
		]
	]);

	//****** tam excel alma bas
	$sayac=1;
	$ExportData[0]=array($fonk->getPDil("Dil Adı"),$fonk->getPDil("Dil Kodu"),$fonk->getPDil("Durumu"),$fonk->getPDil("Kayıt Tarihi"));///başlıklar
	foreach ($listeleme as $satir) {//içerikler
		$ExportData[$sayac]=array($satir['dilAdi'],$satir['dilKodu'],$satir['dilDurumu'],$fonk->sqlToDateTime($satir['dilKayitTarihi']));
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
								<?php if($eklemeYetki){?>
									<button type="button" onclick="SayfaGetir('<?=$menuId?>','<?=$duzenlemeSayfasi?>');" class="btn mr-1 btn-primary btn-sm"><i class="la la-plus-circle"></i> <?=$fonk->getPDil("Yeni Ekle")?></button>
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
												<th><?=$fonk->getPDil("Dil Adı")?></th>
												<th><?=$fonk->getPDil("Dil Kodu")?></th>
												<th><?=$fonk->getPDil("Dil Görsel")?></th>
												<th><?=$fonk->getPDil("Site Durumu")?></th>
												<th><?=$fonk->getPDil("Panel Durumu")?></th>
												<th><?=$fonk->getPDil("Kayıt Tarihi")?></th>
												<th style="width:220px;text-align:center"><?=$fonk->getPDil("İşlemler")?></th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach($listeleme as $list){
												?>
												<tr id="trSatir-<?=$list[$tabloPrimarySutun];?>">
													<td><?=$list[$tabloPrimarySutun];?></td>

													<!-- Güncellenecek Kısımlar -->
													<td><?=$list['dilAdi'];?></td>
													<td><?=$list['dilKodu'];?></td>
													<td><a href="./Images/Ayarlar/<?=$list['dilGorsel']?>" target="_blank"><img src="./Images/Ayarlar/<?=$list['dilGorsel']?>" width="40"></a></td>
													<td><?php if($list['dilDurumu']==1){?><div class="badge badge-success"><?=$fonk->getPDil("Aktif")?></div><?php } else{ ?><div class="badge badge-danger"><?=$fonk->getPDil("Pasif")?></div><?php } ?></td>
													<td><?php if($list['dilPanelDurumu']==1){?><div class="badge badge-success"><?=$fonk->getPDil("Aktif")?></div><?php } else{ ?><div class="badge badge-danger"><?=$fonk->getPDil("Pasif")?></div><?php } ?></td>
													<td><?=$fonk->sqlToDateTime($list['dilKayitTarihi']);?></td>
													<!-- /Güncellenecek Kısımlar -->

													<td style="text-align:center">
														<div class="btn-group btn-group-sm" role="group">
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
					</div>
				</div>
			</div>
		</section>
		<!--/ HTML5 export buttons table -->

	<?php } include("../../Scripts/listelemeJs.php");?>
	<script type="text/javascript">
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