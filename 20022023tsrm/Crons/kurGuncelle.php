<?php
include("../Panel/System/Config.php");
if ($_GET["ApiKey"] == "8bYuhtCv5997aGgCxzsLpXgJuCRMFqEp") {
	if ($sabitB['sabitBilgiOtoKurGuncelle'] == 1) {
		$contextOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false,],];
		$tcmbResponse = file_get_contents('https://www.tcmb.gov.tr/kurlar/today.xml', false, stream_context_create($contextOptions));
		$xml = simplexml_load_string($tcmbResponse);

		$finans = array();
		foreach ($xml as $k) {
			$kurKodu = $k->attributes()->CurrencyCode{0}; //kur kodu
			if ($kurKodu != 'XDR') {
				$finans = array_merge($finans, array((string)$kurKodu => array('kur' => (string)$kurKodu, 'adi' => (string)$k->Isim, 'alis' => (string)$k->ForexBuying, 'satis' => (string)$k->ForexSelling, 'efektif_alis' => (string)$k->BanknoteBuying, 'efektif_satis' => (string)$k->BanknoteSelling)));
			}
		}
		//print("<pre>".print_r($finans,true)."</pre>");

		$dolar = round(str_replace(",", ".", $finans["USD"]["efektif_alis"]), 2);
		$euro = round(str_replace(",", ".", $finans["EUR"]["efektif_alis"]), 2);
		$guncelle = $db->update("SabitBilgiler", [
			"sabitBilgiDolar" => $dolar,
			"sabitBilgiEuro" => $euro,
			'sabitBilgiOtoKurGuncelleTarihi' => date("Y-m-d H:i:s")
		], [
			"sabitBilgiId" => 1
		]);
		if ($guncelle) {
			echo "Güncelleme Başarılı.(" . $dolar . "," . $euro . ")";
		} else {
			echo "Kur Güncelleme Sırasında Bir Hata oluştu. (" . $db->error . ")";
		}
	} else {
		echo "Oto Güncelleme Kapalı.";
	}
} else {
	echo "Api Key Haralı.";
}
