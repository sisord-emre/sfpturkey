<?php
include("../../System/Config.php");
$duzenList = json_decode($_POST["duzenList"], true);
$sayac1 = 0;
foreach ($duzenList as $key => $value1) {
	$sayac1++;
	$query = $db->update("Kategoriler", [
		"kategoriUstMenuId" => 0,
		'kategoriSirasi' => $sayac1
	], [
		"kategoriId" => $value1["id"]
	]);
	$fonk->logKayit(2, "Kategoriler" . ' ; ' . $value1["id"] . ' ; ' . json_encode(['kategoriSirasi' => $sayac1])); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

	if (count($value1["children"]) > 0) {
		$sayac2 = 0;
		foreach ($value1["children"] as $key => $value2) {
			$sayac2++;
			$query = $db->update("Kategoriler", [
				"kategoriUstMenuId" => $value1["id"],
				'kategoriSirasi' => $sayac2
			], [
				"kategoriId" => $value2["id"]
			]);
			$fonk->logKayit(2, "Kategoriler" . ' ; ' . $value2["id"] . ' ; ' . json_encode(["kategoriUstMenuId" => $value1["id"], 'kategoriSirasi' => $sayac2])); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
		
			if (count($value2["children"]) > 0) {
				$sayac3 = 0;
				foreach ($value2["children"] as $key => $value3) {
					$sayac3++;
					$query = $db->update("Kategoriler", [
						"kategoriUstMenuId" => $value2["id"],
						'kategoriSirasi' => $sayac3
					], [
						"kategoriId" => $value3["id"]
					]);
					$fonk->logKayit(2, "Kategoriler" . ' ; ' . $value3["id"] . ' ; ' . json_encode(["kategoriUstMenuId" => $value2["id"], 'kategoriSirasi' => $sayac3])); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				}
			}
		
		}
	}
}

if ($query) {
	echo '1';
}
