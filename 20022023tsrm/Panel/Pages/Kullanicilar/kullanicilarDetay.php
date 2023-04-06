<?php
include ("../../System/Config.php");


$tabloPrimarySutun=$_POST['tabloPrimarySutun'];//primarykey

$baslik=$_POST['baslik'];

$tableName=$_POST['tableName'];//tabloadı istenirse burdan değiştirilebilir

$detayId=$_POST['detayId'];

//sayfayı görüntülenme logları
$fonk->logKayit(6,$_SERVER['REQUEST_URI']."?primaryId=".$detayId);//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$detay = $db->get($tableName, "*", [
	$tabloPrimarySutun => $detayId
]);
$listYetki=explode(';',$detay['kullaniciYetkiler']);
?>
<div class="modal fade text-left" id="fadeIn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
	<!-- detay modalı -->
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="baslikModal"><?=$fonk->getPDil($baslik)?> <?=$fonk->getPDil("Detay")?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="icerikModal">
				<div class="form-body">
					<h4 class="form-section"><?=$fonk->getPDil("Ad Soyad")?></h4>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<?=$detay['kullaniciAdSoyad']?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-body">
					<h4 class="form-section"><?=$fonk->getPDil("Email")?></h4>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<?=$detay['kullaniciEmail']?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-body">
					<h4 class="form-section"><?=$fonk->getPDil("Durumu")?></h4>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<?php if($detay['kullaniciDurum']==1){?><div class="badge badge-success"><?=$fonk->getPDil("Aktif")?></div><?php } else{ ?><div class="badge badge-danger"><?=$fonk->getPDil("Pasif")?></div><?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="form-body">
					<h4 class="form-section"><?=$fonk->getPDil("Yetkiler")?></h4>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="row skin skin-square">
									<?php
									$menuler = $db->select("Menuler", "*", [
					          "menuUstMenuId" =>0,
					          "menuOzelGorunuruk" =>	1,
					          "ORDER" => [
					            "menuSirasi" => "ASC"
					          ]
					        ]);
									foreach($menuler as $menu){
										for($i=0;$i<Count($listYetki);$i++){//yetkisi varsa hangileri olduğuna bakıyoruzki tikli getirelim
											$yetki= json_decode($listYetki[$i], true);
											if($yetki['menuYetkiID']==$menu['menuId']){
												if($yetki['listeleme']=="on"){
													$listelemeTik="checked";
												}
												else{
													$listelemeTik="";
												}

												if($yetki['ekleme']=="on"){
													$eklemeTik="checked";
												}
												else{
													$eklemeTik="";
												}

												if($yetki['duzenleme']=="on"){
													$duzenlemeTik="checked";
												}
												else{
													$duzenlemeTik="";
												}

												if($yetki['silme']=="on"){
													$silmeTik="checked";
												}
												else{
													$silmeTik="";
												}

												if($yetki['excel']=="on"){
													$excelTik="checked";
												}
												else{
													$excelTik="";
												}
											}
										}

										if($kulBilgi['kullaniciOzelYetki']==0){
											$menuYetki=false;
											$listelemeOzelYetki=false;
											$silmeOzelYetki=false;
											$duzenlemeOzelYetki=false;
											$eklemeOzelYetki=false;
											$excelOzelYetki=false;

											$kullaniciOzelBilgi = $db->get("Kullanicilar", "*", [
												"kullaniciId" => $kulBilgi['kullaniciId']
											]);
											$kullaniciOzelYetkiler = explode(';', $kullaniciOzelBilgi['kullaniciYetkiler']);
											for($j=0;$j<Count($kullaniciOzelYetkiler);$j++){//kullanıcının yetkilerini sorguluyoruz
												$kullaniciYetki= json_decode($kullaniciOzelYetkiler[$j], true);
												if($kullaniciYetki['menuYetkiID']==$menu['menuId']){
													$menuYetki=true;
													if($kullaniciYetki['listeleme']=="on"){$listelemeOzelYetki=true;}//listeleme için menu gözükme
													if($kullaniciYetki['silme']=="on"){$silmeOzelYetki=true;}//silmek için menu gözükme
													if($kullaniciYetki['duzenleme']=="on"){$duzenlemeOzelYetki=true;}//duzenlemeiçin menu gözükme
													if($kullaniciYetki['ekleme']=="on"){$eklemeOzelYetki=true;}//ekleme için menu gözükme
													if($kullaniciYetki['excel']=="on"){$excelOzelYetki=true;}//ekleme için menu gözükme
												}
											}
										}else{//özel yetkisi var ise herşey serbest
											$menuYetki=true;
											$listelemeOzelYetki=true;
											$silmeOzelYetki=true;
											$duzenlemeOzelYetki=true;
											$eklemeOzelYetki=true;
											$excelOzelYetki=true;
										}
										if($menuYetki){
											?>

											<div class="col-md-4 col-sm-6" style="padding-top: 10px;">
												<p><b><?=$fonk->getPDil($menu['menuAdi'])?></b></p>
												<?php
												if($listelemeOzelYetki){
													?>
													<fieldset>
														<div class="state icheckbox_square-red <?=$listelemeTik?> mr-1" style="position: relative;"><input type="checkbox" name="listeleme_<?=$menu['menuId']?>" id="listeleme_<?=$menu['menuId']?>" style="position: absolute; opacity: 0;"></div>
														<label for="listeleme_<?=$menu['menuId']?>" class=""><?=$fonk->getPDil("Listeleme")?></label>
													</fieldset>
												<?php } if($eklemeOzelYetki){?>
													<fieldset>
														<div class="state icheckbox_square-red <?=$eklemeTik?> mr-1" style="position: relative;"><input type="checkbox" name="ekleme_<?=$menu['menuId']?>" id="ekleme_<?=$menu['menuId']?>"  style="position: absolute; opacity: 0;"></div>
														<label for="ekleme_<?=$menu['menuId']?>" class=""><?=$fonk->getPDil("Ekleme")?></label>
													</fieldset>
												<?php } if($duzenlemeOzelYetki){?>
													<fieldset>
														<div class="state icheckbox_square-red <?=$duzenlemeTik?> mr-1" style="position: relative;"><input type="checkbox" name="duzenle_<?=$menu['menuId']?>" id="duzenle_<?=$menu['menuId']?>"  style="position: absolute; opacity: 0;"></div>
														<label for="duzenle_<?=$menu['menuId']?>" class=""><?=$fonk->getPDil("Düzenleme")?></label>
													</fieldset>
												<?php } if($silmeOzelYetki){?>
													<fieldset>
														<div class="state icheckbox_square-red <?=$silmeTik?> mr-1" style="position: relative;"><input type="checkbox" name="silme_<?=$menu['menuId']?>" id="silme_<?=$menu['menuId']?>"  style="position: absolute; opacity: 0;"></div>
														<label for="silme_<?=$menu['menuId']?>" class=""><?=$fonk->getPDil("Silme")?></label>
													</fieldset>
												<?php } if($excelOzelYetki){?>
													<fieldset>
														<div class="state icheckbox_square-red <?=$excelTik?> mr-1" style="position: relative;"><input type="checkbox" name="excel_<?=$menu['menuId']?>" id="excel_<?=$menu['menuId']?>"  style="position: absolute; opacity: 0;"></div>
														<label for="excel_<?=$menu['menuId']?>" class=""><?=$fonk->getPDil("Excel")?></label>
													</fieldset>
												<?php } ?>
											</div>

											<?php
											$silmeTik="";
											$duzenlemeTik="";
											$eklemeTik="";
											$listelemeTik="";
											$excelTik="";
										} } ?>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal"><?=$fonk->getPDil("Kapat")?></button>
				</div>
			</div>
		</div>
	</div>
