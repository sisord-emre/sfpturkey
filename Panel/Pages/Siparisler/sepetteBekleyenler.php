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

	$baslik="Sepette Bekleyen Ürünler";//başlıkta gözükecek yazı menu adi

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
	if ($Ara!="") {
		$sartlar=array_merge($sartlar,["siparisKodu[~]" => $Ara]);
	}
	$sartlar=array_merge($sartlar,[
		"siparisOdemeTipiId" => 0,//yani herhangi bir ödeme tipi olmayan
		"ORDER" => [
			$tabloPrimarySutun => "DESC"
		]
	]);
	$listeleme = $db->select($tableName,[
		"[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
		"[>]OdemeTipleri" => ["Siparisler.siparisOdemeTipiId" => "odemeTipId"],
		"[>]Diller" => ["Siparisler.siparisDilId" => "dilId"],
		"[>]ParaBirimleri" => ["Siparisler.siparisParaBirimId" => "paraBirimId"]
	],"*",$sartlar);
	//****** tam excel alma bas
	$sayac=1;
	$ExportData[0]=array('Ad Soyad','Email');///başlıklar
	foreach ($listeleme as $satir) {//içerikler
		$ExportData[$sayac]=array($satir['kullaniciAdSoyad'],$satir['kullaniciEmail']);
		$sayac++;
	}
	//!otomatik excel alma
	$_SESSION["excel"]=$ExportData;
	$_SESSION["excelTablo"]=$tableName;
	//****** tam excel alma bitis
	?>
	<!-- Datatable sıralaması için -->
	<script>
	$(document).ready(function () {
		$('#listTable thead tr').clone(true).appendTo('#listTable thead');
		$('#listTable thead tr:eq(1) th').each(function(i){
			var title = $(this).text();
			if (title=="<?=$fonk->getPDil("İşlemler")?>") {
				$(this).html('');
			}
			else {
				var width="100%";
				if (title=="ID") {
					var width="50px";
				}
				$(this).html('<input type="text" placeholder="'+title+'" style="width: '+width+';"/>');
				$('input',this).on('keyup change',function(){
					if (table.column(i).search()!==this.value){
						table.column(i).search(this.value).draw();
					}
				});
			}
		});
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
								<?php } ?>
								<?php if($duzenlemeYetki){?>
									<button class="btn mr-1 btn-outline-success btn-sm" onclick="siparisBekleyenKurGuncelle();"> <?=$fonk->getPDil("Kura Göre Fiyat Güncelle")?></a>
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
												<th><?=$fonk->getPDil("Kodu")?></th>
												<th><?=$fonk->getPDil("Uye Firma Adi")?></th>
												<th><?=$fonk->getPDil("Ürün Kodu")?></th>
												<th><?=$fonk->getPDil("İçerik")?></th>
												<th><?=$fonk->getPDil("Adet")?></th>
												<th><?=$fonk->getPDil("İskonto")?> ($)</th>
												<th><?=$fonk->getPDil("Ödenecek Tutar")?></th>
												<th><?=$fonk->getPDil("Durumu")?></th>
												<th><?=$fonk->getPDil("Dil")?></th>
												<th><?=$fonk->getPDil("Kayıt Tarihi")?></th>
												<th style="width:250px;text-align:center"><?=$fonk->getPDil("İşlemler")?></th>
											</tr>
										</thead>
										<tbody>
											<?php
										
											foreach($listeleme as $list){
												$satirRenk="";
												$siparisDurum = $db->get("SiparisSiparisDurumlari",[
													"[<]SiparisDurumlari" => ["SiparisSiparisDurumlari.siparisSiparisDurumSiparisDurumId" => "siparisDurumId"],
													"[<]SiparisDurumDilBilgiler" => ["SiparisDurumlari.siparisDurumId" => "siparisDurumDilBilgiSiparisDurumId"],
													"[>]KargoFirmalari" => ["SiparisSiparisDurumlari.siparisSiparisDurumKargoFirmaId" => "kargoFirmaId"]
												],"*",[
													"siparisSiparisDurumSiparisId" => $list["siparisId"],
													"siparisDurumDilBilgiDilId" => $list["siparisDilId"],
													"ORDER" => [
														"siparisSiparisDurumId" => "DESC",
													]
												]);

												if($siparisDurum["siparisSiparisDurumSiparisDurumId"]==4){//hazırlanıyor
													$satirRenk="durumBeklemede";
												}
												else if($siparisDurum["siparisSiparisDurumSiparisDurumId"]==6){//tamamlandı
													$satirRenk="durumBasarili";
												}
												else if($siparisDurum["siparisSiparisDurumSiparisDurumId"]==7){//iade edild
													$satirRenk="durumIptal";
												}

												$siparisIcerikleri = $db->select("SiparisIcerikleri",[
													"[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
													"[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
												],"*",[
													"urunDilBilgiDilId" => $list["siparisDilId"],
													"urunDurum" => 1,
													"urunDilBilgiDurum" => 1,
													"siparisIcerikSiparisId" => $list["siparisId"]
												]);
												// echo "<pre>"; 
												// print_r($siparisIcerikleri);
												// echo "</pre>";
												$icerik="";
												$urunKodu="";
												$icerikSayac=0;
												$toplamTutar=0;
												$siparisIskontoUcreti=0;
												$toplamUrunAdet=0;
												foreach($siparisIcerikleri as $siparisIcerik){
													$icerikSayac++;
													$icerik.="<span style='display: flex;width: max-content;'>
													".$siparisIcerik["siparisIcerikUrunVaryantDilBilgiAdi"]." x ".$siparisIcerik["siparisIcerikAdet"]."
													</span>";

													$urunKodu.="<span style='display: flex;width: max-content;'>
													".$siparisIcerik["urunModel"]."
													</span>";
													
													$toplamTutar+=$siparisIcerik['siparisIcerikAdet']*$siparisIcerik['siparisIcerikFiyat'];
													$toplamUrunAdet+=$siparisIcerik['siparisIcerikAdet'];
												}
												if($list['siparisIndirimKodu']!="" && $list['siparisIndirimYuzdesi']!=0){
													$toplamTutar-=($toplamTutar/100*$list['siparisIndirimYuzdesi']);
												}
												if($list['siparisKargoUcreti']!=0){
													$toplamTutar+=$list['siparisKargoUcreti'];
												}
												if($list["siparisIskontoUcreti"] > 0){
													$siparisIskontoUcreti=$fonk->paraCevir($list["siparisIskontoUcreti"],"USD","TRY");
													$toplamTutar-=$siparisIskontoUcreti;
												}
												?>
												<tr id="trSatir-<?=$list[$tabloPrimarySutun];?>" class="<?=$satirRenk?>">
													<td><?=$list[$tabloPrimarySutun];?></td>

													<!-- Güncellenecek Kısımlar -->
													<th><?=$list['siparisKodu'];?></th>
													<td><?=$list['uyeFirmaAdi'];?></td>
													<td><?=$urunKodu;?></td>
													<td><?=$icerik;?></td>
													<td><?=$toplamUrunAdet;?></td>
													<th>
														<input type="text" onchange="FiyatDegistir('<?=$list[$tabloPrimarySutun];?>');" name="siparisIskontoUcreti" id="durum-<?=$list[$tabloPrimarySutun];?>" value="<?=$list['siparisIskontoUcreti']?>">
													</th>
													<th id="tutar-<?=$list[$tabloPrimarySutun];?>">
														<?=$list["paraBirimSembol"].number_format($toplamTutar,2,',','.');?>
													</th>
													<td data-sort="<?=$siparisDurum['siparisDurumId'];?>"><?=$siparisDurum['siparisDurumDilBilgiBaslik'];?></td>
													<td><?=$list['dilAdi'];?></td>
													<td data-sort="<?=$fonk->sqlToDateTimeTiresiz($list['siparisKayitTarihi']);?>"><?=$fonk->sqlToDateTime($list['siparisKayitTarihi']);?></td>
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

	Number.prototype.formatMoney = function (c, d, t) {
		var n = this,
			c = isNaN((c = Math.abs(c))) ? 2 : c,
			d = d == undefined ? "." : d,
			t = t == undefined ? "," : t,
			s = n < 0 ? "-" : "",
			i = parseInt((n = Math.abs(+n || 0).toFixed(c))) + "",
			j = (j = i.length) > 3 ? j % 3 : 0;
		return (
			s +
			(j ? i.substr(0, j) + t : "") +
			i.substr(j).replace(/(d{3})(?=d)/g, "$1" + t) +
			(c
				? d +
				Math.abs(n - i)
					.toFixed(c)
					.slice(2)
				: "")
		);
	};

	function siparisBekleyenKurGuncelle(){
		if(confirm('<?=$fonk->getPDil("Güncellemek İstediğinize Emin misiniz ?")?>')) {
			$.ajax({
				type: "POST",
				url: "Pages/Siparisler/siparisBekleyenKurGuncelle.php",
				success: function(res){
					if (res==1) {
						SayfaYenile();
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

	function FiyatDegistir(Id){
		var e = document.getElementById("durum-"+Id);
		var durum = e.value;
		$.ajax({
			type: "POST",
			url: "Pages/Siparisler/fiyatDegistir.php",
			data:{'Id':Id,'durum':durum},
			success: function(res){
				if(res.status == "success"){
					toastr.success('<?=$fonk->getPDil("Güncelleme Sağlandı.")?>');
					document.getElementById("tutar-"+Id).innerHTML = "₺"+ parseFloat(res.result.tutar).formatMoney(2, ",", ".");
				}else{
					alert(res);
				}
			}
		});
	}
	
	function TumunuTemizle(){
		if(confirm('<?=$fonk->getPDil("Tüm Ödeme Bekleyenleri Silmek İstediğinize Emin misiniz ?")?>')) {
			document.getElementById("tumunuSilButton").disabled = true;
			$.ajax({
				type: "POST",
				url: "Pages/Siparisler/tumunuSil.php",
				success: function(res){
					if (res==1) {
						SayfaYenile();
					}else {
						alert(res);
					}
				},
				error: function (jqXHR, status, errorThrown) {
					alert("Result: "+status+" Status: "+jqXHR.status);
				}
			});
		}
	}

	function DurumEkleModal(detayId){
		document.getElementById("durumModal-"+detayId).disabled = true;
		$.ajax({
			type: "POST",
			url: "Pages/Siparisler/durumEkleModal.php",
			data:{'baslik':'<?=$fonk->getPDil($baslik)?>','tableName':'<?=$tableName?>','tabloPrimarySutun':'<?=$tabloPrimarySutun?>','detayId':detayId,'silmeYetki':'<?=$silmeYetki?>','duzenlemeYetki':'<?=$duzenlemeYetki?>'},
			success: function(res){
				$('#detaylari').html(res);
				$("#fadeIn").modal("show");
				document.getElementById("durumModal-"+detayId).disabled = false;
			},
			error: function (jqXHR, status, errorThrown) {
				alert("Result: "+status+" Status: "+jqXHR.status);
			}
		});
	}

	function veriDetay(detayId){
		document.getElementById("detay-"+detayId).disabled = true;
		$.ajax({
			type: "POST",
			url: "Pages/<?=$detaysayfasi?>",
			data:{'baslik':'<?=$fonk->getPDil($baslik)?>','tableName':'<?=$tableName?>','tabloPrimarySutun':'<?=$tabloPrimarySutun?>','detayId':detayId,'silmeYetki':'<?=$silmeYetki?>','duzenlemeYetki':'<?=$duzenlemeYetki?>'},
			success: function(res){
				$('#detaylari').html(res);
				$("#fadeIn").modal("show");
				document.getElementById("detay-"+detayId).disabled = false;
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
