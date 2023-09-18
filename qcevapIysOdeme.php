<?php
//error_reporting(E_ALL);
///ini_set("display_errors", 1);

include("Panel/System/Config.php");

if ($_POST['mdStatus'] == 0) {
	$durum = "3-D Secure imzası geçersiz veya doğrulama<br>";
} else if ($_POST['mdStatus'] == 2) {
	$durum = "Kart sahibi veya bankası sisteme kayıtlı değil<br>";
} else if ($_POST['mdStatus'] == 3) {
	$durum = "Kartın bankası sisteme kayıtlı değil<br>";
} else if ($_POST['mdStatus'] == 4) {
	$durum = "Doğrulama denemesi, kart sahibi sisteme daha sonra kayıt olmayı seçmiş<br>";
} else if ($_POST['mdStatus'] == 5) {
	$durum = "Doğrulama yapılamıyor<br>";
} else if ($_POST['mdStatus'] == 6) {
	$durum = "3-D Secure hatası<br>";
} else if ($_POST['mdStatus'] == 7) {
	$durum = "Sistem hatası<br>";
} else if ($_POST['mdStatus'] == 8) {
	$durum = "Bilinmeyen kart no<br>";
}
echo $durum;



if ($_POST['mdStatus'] == 1) {
	include("iyzipay/samples/create_threeds_payment.php");

	//hatalı ise
	if ($threedsPayment->getStatus() == "failure") {
		$errorMessage = $threedsPayment->getErrorMessage();
		$errorCode = $threedsPayment->getErrorCode();
		$status = $threedsPayment->getStatus();

		$odemeLogHata = $errorMessage . " errorCode: " . $errorCode . " status: " . $status;

		$odemeLoglari = $db->insert('OdemeLoglari', [
			'odemeLogSiparisKod' => $_POST['conversationId'],
			'odemeLogHata' => $odemeLogHata,
			'odemeLogStatus' => $status,
			'odemeLogTarih' => date("Y-m-d H:i:s")
		]);

		$durum = $threedsPayment->getErrorMessage();
		header("Location: " . $sabitB["sabitBilgiSiteUrl"] . "thanks?e=" . $errorMessage . "&sk=" . $_POST['conversationId']);
	} 
	else {
		$errorMessage = $threedsPayment->getErrorMessage();
		$errorCode = $threedsPayment->getErrorCode();
		$status = $threedsPayment->getStatus();

		$odemeLogHata = $errorMessage . " errorCode: " . $errorCode . " status: " . $status;

		$odemeLoglari = $db->insert('OdemeLoglari', [
			'odemeLogSiparisKod' => $_POST['conversationId'],
			'odemeLogHata' => $odemeLogHata,
			'odemeLogStatus' => $status,
			'odemeLogTarih' => date("Y-m-d H:i:s")
		]);

		$durum = $threedsPayment->getErrorMessage();

		$siparis = $db->get("Siparisler", [
			"[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
			"[>]UyeAdresler" => ["Uyeler.uyeId" => "uyeAdresUyeId"],
			"[>]ParaBirimleri" => ["Siparisler.siparisParaBirimId" => "paraBirimId"]
		], "*", [
			"siparisKodu" => $_POST['conversationId']
		]);

		$_SESSION['uyeSessionKey'] = $siparis["uyeSessionKey"];

		$siparisGuncelle = $db->update('Siparisler', [
			'siparisOdemeBilgileri' => $durum . "mdStatus:" . $_POST['mdStatus'] . " paymentId:" . $_POST['conversationId'],
			'siparisOdemeTipiId' => 1 //İyzico
		], [
			"siparisKodu" => $_POST['conversationId']
		]);

		$siparisDurum = $db->insert('SiparisSiparisDurumlari', [
			'siparisSiparisDurumSiparisId' => $siparis["siparisId"],
			'siparisSiparisDurumSiparisDurumId' => 2, //ödeme yapıldı
			'siparisSiparisDurumKargoFirmaId' => 0,
			'siparisSiparisDurumKargoTakipKodu' => "",
			'siparisSiparisDurumKargoTakipLink' => "",
			'siparisSiparisDurumKayitTarihi' => date("Y-m-d H:i:s")
		]);

		$siparis = $db->get("Siparisler", [
			"[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
		], "*", [
			"siparisKodu" => $_POST['conversationId'],
			"siparisUyeId" => $siparis['uyeId']
		]);

		if($siparis["siparisOdemeBilgileri"] == "Havale/Eft"){
			$odemeYontemi = "Havale/Eft";
		}
		else {
			$odemeYontemi = "Kredi Kartı";
		}

		$siparisKodu = $siparis['siparisKodu'];
		$siparisDurum = $siparis['siparisDurumAdi'];
		$siparisTarihi = $fonk->sqlToDateTime($siparis['siparisTarihi']);
		$message = $fonk->getDil('siparisinizi-profilinizden-takip-edebilirsiniz');

		$siparisIcerikleri = $db->select("SiparisIcerikleri", [
			"[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
			"[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
			"[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
		], "*", [
			"urunDilBilgiDilId" => 1,
			"urunDurum" => 1,
			"urunDilBilgiDurum" => 1,
			"siparisIcerikSiparisId" => $siparis['siparisId']
		]);

		$siparisIcerik = "";
		foreach ($siparisIcerikler as $value) {
			$siparisIcerik = $siparisIcerik . $value['siparisIcerikUrunVaryantDilBilgiAdi'] . "  -  " . $value['siparisIcerikAdet'] . " " . $fonk->getDil('adet') . "<br>";
		}

		$bankalar = $db->select("BankaBilgileri", "*", [
			"bankaBilgiDurum" => 1
		]);

		$urunArtis = $db->select("Siparisler", [
			"[<]SiparisIcerikleri" => ["Siparisler.siparisId" => "siparisIcerikSiparisId"],
			"[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
		], "*", [
			"siparisKodu" => $_POST['conversationId']
		]);

		foreach ($urunArtis as $key => $value) {
			$arttir = $db->update('Urunler', [
				'urunSatisMiktar' => $value["urunSatisMiktar"] + $value["siparisIcerikAdet"],
				'urunStok' => $value["urunStok"] - $value["siparisIcerikAdet"]
			], [
				"urunId" => $value["urunId"]
			]);
		}

		$baslik = "Siparis Bilgisi (".$siparisKodu.")";
		include("Mailtemplate/odemeEmailTemplate.php");
		$fonk->mailGonder($siparis["uyeMail"], $baslik, $body);
		$fonk->mailGonder($sabitB["sabitBilgiBildirimMail"], $baslik, $body); 
		header("HTTP/1.1 303 See Other");
		header("Location: " . $sabitB["sabitBilgiSiteUrl"] . "thanks?s=" . $_POST['conversationId']);
	}
}
