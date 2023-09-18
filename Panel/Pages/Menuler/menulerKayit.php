<?php
include ("../../System/Config.php");

$menuId=$_POST['menuId'];//menu id alınıyor
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
	$baslik=$hangiMenu['menuAdi'];

	$gelenTablo=$_POST['tableName'];//tablodan menu oluşturma kısmı

	if($_POST['tableName']==""){
		//sayfayı görüntülenme logları
		$fonk->logKayit(6,$_SERVER['REQUEST_URI']);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
	}

	if($gelenTablo!=""){
		$key = $db->query("select column_name from information_schema.COLUMNS where table_name = '".$gelenTablo."'")->fetch();

		$menuadi=$_POST['menuadi'];
		$gelenKeySutun=$key['column_name'];
		$kayit=$_POST['kayit'];
		$listele=$_POST['listele'];
		$ikon=$_POST['ikon'];
		$kayitadi=$_POST['kayitadi'];
		$listelemeadi=$_POST['listelemeadi'];
		$sirasi=$_POST['sirasi'];

		//eski menuler var ise onların id sü güncellensin
		$eksiMenuID = $db->get("Menuler", "*", [
			"menuTabloAdi" => $gelenTablo,
			"menuTipi" =>	0
		]);

		$eksiKayitMenuID = $db->get("Menuler", "*", [
			"menuTabloAdi" => $gelenTablo,
			"menuTipi" =>	1
		]);

		$eksiListelemeMenuID = $db->get("Menuler", "*", [
			"menuTabloAdi" => $gelenTablo,
			"menuTipi" =>	2
		]);

		if($eksiMenuID){
			$ustMenuID=$eksiMenuID['menuId'];//Ana Menunun Idsi
		}else{
			$ustMenuID=null;
		}
		if($eksiKayitMenuID){
			$kayitMenuID=$eksiKayitMenuID['menuId'];//Kayit Menunun Idsi
		}else{
			$kayitMenuID=null;
		}
		if($eksiListelemeMenuID){
			$listelemeMenuID=$eksiListelemeMenuID['menuId'];//Listeleme Menunun Idsi
		}else{
			$listelemeMenuID=null;
		}

		//ilk olarak önceki menu kayıtlarını siliyoruz
		$query = $db->delete("Menuler", [
			"menuTabloAdi" => $gelenTablo,
			"menuTipi" => 0
		]);
		//ilk olarak önceki menu kayıtlarını siliyoruz
		$query = $db->delete("Menuler", [
			"menuTabloAdi" => $gelenTablo,
			"menuTipi" => 1
		]);
		//ilk olarak önceki menu kayıtlarını siliyoruz
		$query = $db->delete("Menuler", [
			"menuTabloAdi" => $gelenTablo,
			"menuTipi" => 2
		]);

		if($menuadi==""){
			//menu adı boşsa mnu yok demektir klasör siliniyor
			rmdir('../'.$gelenTablo);
		}

		if($sabitB['sabitBilgiLog']==1){
			///Loglama İşlemi
			$log = $db->insert("Log", [
				'logKullaniciId' => intval($kulBilgi['kullaniciId']),//oturum id
				'logIslemTipi' => 1,//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>excel,6=>gösterilme,7=>diger
				'logIslem' => 'Menuler ; '.json_encode(array(
					'menuTabloAdi' => $gelenTablo,
					'menuTabloPrimarySutun' =>$gelenKeySutun,
					'menuUstMenuId' => 0,
					'menuAdi' => $menuadi,
					'menuSayfa' => '#',
					'menuGorunurluk' => 1,
					'menuOzelGorunuruk' => 1,
					'menuSirasi' => $sirasi,
					'menuIcon' => $ikon,
					'menuTipi' => 0,//anamenu
					'menuTarih' => date('Y-m-d H:i:s')
				)),//yapılan işlme parametreleri
				'logTarih' => date("Y-m-d H:i:s")//yapılan zaman
			]);
		}

		//anamenuyu ekliyoruz
		$query = $db->insert("Menuler", [
			'menuTabloAdi' => $gelenTablo,
			'menuTabloPrimarySutun' =>$gelenKeySutun,
			'menuUstMenuId' => 0,
			'menuAdi' => $menuadi,
			'menuSayfa' => '#',
			'menuGorunurluk' => 1,
			'menuOzelGorunuruk' => 1,
			'menuSirasi' => $sirasi,
			'menuIcon' => $ikon,
			'menuTipi' => 0,//anamenu
			'menuTarih' => date('Y-m-d H:i:s')
		]);
		$ustMenuID=$db->id();

		if($kayit=="true"){//kayıt menusunu ekliyoruz
			$query = $db->insert("Menuler", [
				'menuTabloAdi' => $gelenTablo,
				'menuTabloPrimarySutun' =>$gelenKeySutun,
				'menuUstMenuId' => $ustMenuID,
				'menuAdi' => $kayitadi,
				'menuSayfa' => $gelenTablo.'/'.strtolower($gelenTablo).'Kayit.php',
				'menuGorunurluk' => 1,
				'menuOzelGorunuruk' => 1,
				'menuSirasi' => 1,
				'menuIcon' => "",
				'menuTipi' => 1,//kayıt sayfası
				'menuTarih' => date('Y-m-d H:i:s')
			]);

			//modül Kopyalama İşlemi
			mkdir('../'.$gelenTablo,0755);//klasör oluşturuyoruz
			copy('../../Moduller/kayitModul.php', '../'.$gelenTablo.'/'.strtolower($gelenTablo).'Kayit.php');
		}

		if($listele!=""){//listeleme menusunu ekliyoruz
			$query = $db->insert("Menuler", [
				'menuTabloAdi' => $gelenTablo,
				'menuTabloPrimarySutun' =>$gelenKeySutun,
				'menuUstMenuId' => $ustMenuID,
				'menuAdi' => $listelemeadi,
				'menuSayfa' => $gelenTablo.'/'.strtolower($gelenTablo).'Listeleme.php',
				'menuGorunurluk' => 1,
				'menuOzelGorunuruk' => 1,
				'menuSirasi' => 2,
				'menuIcon' => "",
				'menuTipi' => 2,//listeleme
				'menuTarih' => date('Y-m-d H:i:s')
			]);

			//modül Kopyalama İşlemi
			mkdir('../'.$gelenTablo,0755);//klasör oluşturuyoruz

			if($listele=="datatable"){ //listelemede kullanılacak yapıyı seçiyoruz
				copy('../../Moduller/listelemeModul.php', '../'.$gelenTablo.'/'.strtolower($gelenTablo).'Listeleme.php');
			}else {
				copy('../../Moduller/listelemeManuelModul.php', '../'.$gelenTablo.'/'.strtolower($gelenTablo).'Listeleme.php');
			}
			copy('../../Moduller/detayModul.php', '../'.$gelenTablo.'/'.strtolower($gelenTablo).'Detay.php');
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
	//////tablo ve database list
	if ($db->type=="pgsql") {
		$tablolar = $db->query("select * from information_schema.tables where table_schema = 'public' and table_type = 'BASE TABLE' order by table_name");
	}else {
		$tablolar = $db->query("select * from information_schema.tables where table_type = 'BASE TABLE' order by table_name");
	}
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

						</div>
						<div class="card-content collapse show">
							<div class="card-body card-dashboard">

								<div class="table-responsive">
									<table class="table table-striped table-bordered dataex-html5-export"  id="listTable">
										<thead>
											<tr>
												<th>Tablo Adı</th>
												<th>Menü Adı</th>
												<th>Kurulumlar</th>
												<th>Kayit Adi</th>
												<th>Listeleme Adi</th>
												<th>Menu İcon</th>
												<th>Menu Sırası</th>
												<th>İşlemler</th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach($tablolar as $tablo){
												//menu ve alt menuler hakkındaki sorgular
												$tabloBilgi = $db->get("Menuler", "*", [
													"menuTabloAdi" => $tablo['table_name'],
													"menuTipi" => 0
												]);

												$kayit = $db->get("Menuler", "*", [
													"menuTabloAdi" => $tablo['table_name'],
													"menuTipi" => 1
												]);

												$listeleme = $db->get("Menuler", "*", [
													"menuTabloAdi" => $tablo['table_name'],
													"menuTipi" => 2
												]);
												?>
												<form id="kurulum">
													<tr>
														<td><?=$tablo['table_name'];?></td>
														<td><input type="text" class="form-control" id="<?=$tablo['table_name']?>_menuadi" value="<?=$tabloBilgi['menuAdi']?>" placeholder="Menü Adı"></td>
														<td>
															<div class="row skin skin-flat" style="padding: inherit;">
																<fieldset>
																	<input type="checkbox" id="<?=$tablo['table_name']?>_kayit" <?php if ($kayit) {echo ' checked';}?>>
																	<label for="<?=$tablo['table_name']?>_kayit">Kayıt</label>
																</fieldset>
															</div>
															<div class="row skin skin-flat" style="padding: inherit;">
																<fieldset>
																	<input type="radio" name="<?=$tablo['table_name']?>_listele" id="<?=$tablo['table_name']?>_listele" value="datatable" <?php if($listeleme){ echo " checked";}?>>DataTable<br>
																	<input type="radio" name="<?=$tablo['table_name']?>_listele" id="<?=$tablo['table_name']?>_listele" value="manuel">Manuel<br>
																</fieldset>
															</div>
														</td>
														<td><input type="text" class="form-control" id="<?=$tablo['table_name']?>_kayitadi" placeholder="Ekleme Adı" value="<?=$kayit['menuAdi']?>"></td>
														<td><input type="text" class="form-control" id="<?=$tablo['table_name']?>_listeleadi" placeholder="Listeleme Adı" value="<?=$listeleme['menuAdi']?>"></td>
														<td>
															<select class="selectBox" id="<?=$tablo['table_name']?>_ikon" style="width:50px>important;">
																<?php
																$ikonlar = $db->select("Ikonlar", "*");
																foreach($ikonlar as $ikon){
																	?>
																	<option value="<?=$ikon['ikonKod']?>" data-text='<i class="la <?=$ikon['ikonKod']?>"></i>' <?php if($tabloBilgi['menuIcon']==$ikon['ikonKod']){echo "selected";}?>></option>
																<?php } ?>
															</select>

														</td>

														<td><input type="number" class="form-control" id="<?=$tablo['table_name']?>_sirasi" placeholder="Sırası" value="<?=$tabloBilgi['menuSirasi']?>"></td>

														<td>
															<button type="button" onclick="sayfaGonder('<?=$tablo['table_name']?>','<?=$menuId?>')" class="btn btn-icon btn-success"><i class="la la-cog"></i></button>
														</td>
													</tr>
												</form>
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
	function sayfaGonder(table_name,menuId){
		if(confirm('Kurulum Yapmak İstediğinize Emin misiniz ? NOT: Eğer Daha Önceden Bu Menü İle İlgili Çalışmalar Yaptısanız Onlar Silinecektir !!!')) {
			var kayit=document.getElementById(table_name+"_kayit").checked;
			var ikon=document.getElementById(table_name+"_ikon").value;
			var menuadi=document.getElementById(table_name+"_menuadi").value;
			var kayitadi=document.getElementById(table_name+"_kayitadi").value;
			var listelemeadi=document.getElementById(table_name+"_listeleadi").value;
			var sirasi=document.getElementById(table_name+"_sirasi").value;

			if(document.querySelector('input[name="'+table_name+'_listele"]:checked')==null){
				var listele="";
			}
			else{
				var listele=document.querySelector('input[name="'+table_name+'_listele"]:checked').value;
			}

			$('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
			$.ajax({
				type: "POST",
				url: "<?=$_SERVER['REQUEST_URI']?>",
				data:{'tableName':table_name,'menuId':menuId,'kayit':kayit,'listele':listele,'ikon':ikon,'menuadi':menuadi,'kayitadi':kayitadi,'listelemeadi':listelemeadi,'sirasi':sirasi},
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
