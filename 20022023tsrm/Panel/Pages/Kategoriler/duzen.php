<?php
include("../../System/Config.php");

$menuId = $_POST['menuId']; //menu id alınıyor
$duzenlemeLink = $_POST['duzenlemeLink'];
$eklemeYetki = $_POST['eklemeYetki'];
$duzenlemeYetki = $_POST['duzenlemeYetki'];
$silmeYetki = $_POST['silmeYetki'];
?>
<div class="cf nestable-lists">
	<div class="dd" id="nestable">
		<ol class="dd-list">
			<?php
			$list = $db->select("Kategoriler", "*", [
				"kategoriUstMenuId" => 0,
				"ORDER" => [
					"kategoriSirasi" => "ASC"
				]
			]);
			$primarySutun = "kategoriId";
			foreach ($list as $item) {
				$altBilgiler = "";
				$altItemList = $db->select("KategoriDilBilgiler", [
					"[>]Diller" => ["KategoriDilBilgiler.kategoriDilBilgiDilId" => "dilId"]
				], "*", [
					"kategoriDilBilgiKategoriId" => $item['kategoriId']
				]);
				foreach ($altItemList as $key => $value) {
					if ($value["kategoriDilBilgiBaslik"] != "") {
						$durum = $fonk->getPDil("Pasif");
						if ($value["kategoriDilBilgiDurum"] == 1) {
							$durum = $fonk->getPDil("Aktif");
						}
						$altBilgiler .= " , " . $value["dilAdi"] . ":" . $value["kategoriDilBilgiBaslik"] . "/" . $durum;
					}
				}
				$altBilgiler = ltrim($altBilgiler, " , ");
			?>
				<li class="dd-item dd3-item" data-id="<?= $item[$primarySutun] ?>" id="duzenSatir-<?= $item[$primarySutun] ?>">
					<div class="dd-handle dd3-handle"></div>
					<div class="dd3-content">(<?= $altBilgiler ?>) - <?= $item["kategoriKodu"] ?></div>
					<?php if ($duzenlemeYetki) { ?>
						<button type="button" onclick="SayfaGetir('<?= $menuId ?>','<?= $duzenlemeLink ?>','<?= $item[$primarySutun]; ?>');" class="btn btn-warning net-button" style="margin-top: -2.3rem;margin-right: 4.5rem;"><i class="la la-edit"></i></button>
					<?php }
					if ($silmeYetki) { ?>
						<button type="button" onclick="DuzenSil('<?= $item[$primarySutun]; ?>')" class="btn btn-danger net-button" style="margin-top: -2.3rem;margin-right: 2rem;"><i class="la la-trash-o"></i></button>
					<?php }
					$listAlt = $db->select("Kategoriler", "*", [
						"kategoriUstMenuId" => $item["kategoriId"],
						"ORDER" => [
							"kategoriSirasi" => "ASC"
						]
					]);
					$primarySutun = "kategoriId";

					if (Count($listAlt) > 0) {
					?>
						<ol class="dd-list">
							<?php
							foreach ($listAlt as $itemAlt) {
								$altBilgiler2 = "";
								$altItemList2 = $db->select("KategoriDilBilgiler", [
									"[>]Diller" => ["KategoriDilBilgiler.kategoriDilBilgiDilId" => "dilId"]
								], "*", [
									"kategoriDilBilgiKategoriId" => $itemAlt['kategoriId']
								]);
								foreach ($altItemList2 as $key => $value) {
									if ($value["kategoriDilBilgiBaslik"] != "") {
										$durum = $fonk->getPDil("Pasif");
										if ($value["kategoriDilBilgiDurum"] == 1) {
											$durum = $fonk->getPDil("Aktif");
										}
										$altBilgiler2 .= " , " . $value["dilAdi"] . ":" . $value["kategoriDilBilgiBaslik"] . "/" . $durum;
									}
								}
								$altBilgiler2 = ltrim($altBilgiler2, " , ");
							?>
								<li class="dd-item dd3-item" data-id="<?= $itemAlt[$primarySutun] ?>" id="duzenSatir-<?= $itemAlt[$primarySutun] ?>">
									<div class="dd-handle dd3-handle"></div>
									<div class="dd3-content">(<?= $altBilgiler2 ?>) - <?= $itemAlt["kategoriKodu"] ?></div>
									<?php if ($duzenlemeYetki) { ?>
										<button type="button" onclick="SayfaGetir('<?= $menuId ?>','<?= $duzenlemeLink ?>','<?= $itemAlt[$primarySutun]; ?>');" class="btn btn-warning net-button" style="margin-top: -2.3rem;margin-right: 4.5rem;"><i class="la la-edit"></i></button>
									<?php }
									if ($silmeYetki) { ?>
										<button type="button" onclick="DuzenSil('<?= $itemAlt[$primarySutun]; ?>')" class="btn btn-danger net-button" style="margin-top: -2.3rem;margin-right: 2rem;"><i class="la la-trash-o"></i></button>
									<?php }
									$listAlt3 = $db->select("Kategoriler", "*", [
										"kategoriUstMenuId" => $itemAlt["kategoriId"],
										"ORDER" => [
											"kategoriSirasi" => "ASC"
										]
									]);
									$primarySutun = "kategoriId";

									if (Count($listAlt3) > 0) {
									?>
										<ol class="dd-list">
											<?php
											foreach ($listAlt3 as $itemAlt3) {
												$altBilgiler3 = "";
												$altItemList3 = $db->select("KategoriDilBilgiler", [
													"[>]Diller" => ["KategoriDilBilgiler.kategoriDilBilgiDilId" => "dilId"]
												], "*", [
													"kategoriDilBilgiKategoriId" => $itemAlt3['kategoriId']
												]);
												foreach ($altItemList3 as $key => $value) {
													if ($value["kategoriDilBilgiBaslik"] != "") {
														$durum = $fonk->getPDil("Pasif");
														if ($value["kategoriDilBilgiDurum"] == 1) {
															$durum = $fonk->getPDil("Aktif");
														}
														$altBilgiler3 .= " , " . $value["dilAdi"] . ":" . $value["kategoriDilBilgiBaslik"] . "/" . $durum;
													}
												}
												$altBilgiler3 = ltrim($altBilgiler3, " , ");
											?>
												<li class="dd-item dd3-item" data-id="<?= $itemAlt3[$primarySutun] ?>" id="duzenSatir-<?= $itemAlt3[$primarySutun] ?>">
													<div class="dd-handle dd3-handle"></div>
													<div class="dd3-content">(<?= $altBilgiler3 ?>) - <?= $itemAlt3["kategoriKodu"] ?></div>
													<?php if ($duzenlemeYetki) { ?>
														<button type="button" onclick="SayfaGetir('<?= $menuId ?>','<?= $duzenlemeLink ?>','<?= $itemAlt3[$primarySutun]; ?>');" class="btn btn-warning net-button" style="margin-top: -2.3rem;margin-right: 4.5rem;"><i class="la la-edit"></i></button>
													<?php }
													if ($silmeYetki) { ?>
														<button type="button" onclick="DuzenSil('<?= $itemAlt3[$primarySutun]; ?>')" class="btn btn-danger net-button" style="margin-top: -2.3rem;margin-right: 2rem;"><i class="la la-trash-o"></i></button>
													<?php } ?>
												</li>
											<?php } ?>
										</ol>
									<?php } ?>
								</li>
							<?php } ?>
						</ol>
					<?php } ?>
				</li>
			<?php } ?>
		</ol>
	</div>
	
</div>
<?php if (count($list) > 0 && $duzenlemeYetki) { ?>
	<div class="col-md-12" style="text-align: center;padding: 2rem;">
		<button type="button" class="btn btn-success" onclick="DuzenKayit()"><i class="la la-floppy-o"></i> <?php echo $fonk->getPDil("Düzeni Kaydet"); ?></button>
	</div>
<?php } ?>