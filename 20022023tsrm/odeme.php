<?php
include 'Panel/System/Config.php';

extract($_POST);
if ($_SESSION['uyeKodu'] == "") {
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . 'login"; </script>';
	exit;
}
$uye = $db->get("Uyeler", "*", [
	"uyeDurum" => 1,
	"uyeKodu" => $_SESSION['uyeKodu'],
]);
if (!$uye) {
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . 'login"; </script>';
	exit;
}
if ($_SESSION['Sepet'] == "" || $_SESSION['Sepet'] == "null" || $_SESSION['Sepet'] == null || !isset($_SESSION['Sepet'])) {
	$_SESSION['Sepet'] = "[]";
}
$sepet = json_decode($_SESSION['Sepet'], true);
if (count($sepet) <= 0) {
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . '"; </script>';
	exit;
}
if ($uyeAdi == "" || $uyeSoyadi == "" || $uyeMail == "" || $uyeTel == "") {
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . '"; </script>';
	exit;
}

$uyeGuncelle = $db->update('Uyeler', [
	'uyeAdi' => $uyeAdi,
	'uyeSoyadi' => $uyeSoyadi,
	'uyeMail' => $uyeMail,
	'uyeTel' => $uyeTel
], [
	"uyeId" => $uye['uyeId']
]);

if ($odemeTipi == "stripe") {
	$siparisOdemeTipiId = 1;
} else if ($odemeTipi == "cash") {
	$siparisOdemeTipiId = 2;
}
$siparisIskontoUcreti = 0;
$siparisKargoUcreti = 0;
$siparisIndirimYuzdesi = 0;
$toplamTutar = 0;

if ($_SESSION['SiparisKodu'] == "") //eğer sipariş kaydı yok ise
{
	$siparisKodu = strval(mt_rand(100000000, 999999999));
	$_SESSION['SiparisKodu'] = $siparisKodu;
	$siparis = $db->insert('Siparisler', [
		'siparisKodu' => $siparisKodu,
		'siparisUyeId' => $uye['uyeId'],
		'siparisNot' => $note,
		'siparisTeslimatUyeAdresId' => $uyeTeslimatAdresId,
		'siparisFaturaUyeAdresId' => $uyeFaturaAdresId,
		'siparisOdemeTipiId' => $siparisOdemeTipiId,
		'siparisIndirimKodu' => "",
		'siparisIndirimYuzdesi' => $siparisIndirimYuzdesi,
		'siparisKargoUcreti' => $siparisKargoUcreti,
		'siparisDilId' => $_SESSION["dilId"],
		'siparisParaBirimId' => $_SESSION["paraBirimId"],
		'siparisOdemeBilgileri' => "",
		'siparisTeslimatTarihi' => $siparisTeslimatTarihi,
		'siparisKayitTarihi' => date("Y-m-d H:i:s")
	]);
	$siparisId = $db->id();

	$siparisDurum = $db->insert('SiparisSiparisDurumlari', [
		'siparisSiparisDurumSiparisId' => $siparisId,
		'siparisSiparisDurumSiparisDurumId' => 1,
		'siparisSiparisDurumKargoFirmaId' => 0,
		'siparisSiparisDurumKargoTakipKodu' => "",
		'siparisSiparisDurumKargoTakipLink' => "",
		'siparisSiparisDurumKayitTarihi' => date("Y-m-d H:i:s")
	]);

	for ($i = 0; $i < count($sepet); $i++) {
		$urun = $db->get("Urunler", [
			"[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
			"[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
			"[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
			"[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
			"[>]KategoriDilBilgiler" => ["UrunKategoriler.urunKategoriKategoriId" => "kategoriDilBilgiKategoriId"],
			"[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
		], "*", [
			"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
			"urunVaryantDilBilgiVaryantId" => $sepet[$i]["urunId"],
			"urunVaryantDilBilgiDurum" => 1,
			"ORDER" => [
				"urunId" => "ASC"
			]
		]);

		$hesapla = $fonk->Hesapla($sepet[$i]["urunId"], $sepet[$i]["varyantId"], $uye['uyeIndirimOrani']);
		$toplamTutar += ($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"])) * $sepet[$i]["adet"];
		$araTutar += ($hesapla["birimFiyat"]) * $sepet[$i]["adet"];
		$kdvTutar += ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]) * $sepet[$i]["adet"];

		$siparisIcerik = $db->insert('SiparisIcerikleri', [
			'siparisIcerikSiparisId' => $siparisId,
			'siparisIcerikUrunId' => $urun["urunId"],
			'siparisIcerikVaryantId' => intval($sepet[$i]["varyantId"]),
			'siparisIcerikUrunVaryantDilBilgiId' => intval($sepet[$i]["urunId"]),
			'siparisIcerikAdet' => $sepet[$i]["adet"],
			'siparisIcerikNot' => $sepet[$i]["siparisIcerikNot"],
			'siparisIcerikUrunAdi' => $urun["urunDilBilgiAdi"],
			'siparisIcerikUrunVaryantDilBilgiAdi' => $urun["urunVaryantDilBilgiAdi"],
			'siparisIcerikTeslimatDurumu' => 1,
			'siparisIcerikFiyat' => $fonk->paraCevir($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]),$urun["paraBirimKodu"],"TRY"),
			'siparisIcerikIndirimliFiyat' => $fonk->paraCevir($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]),$urun["paraBirimKodu"],"TRY"),
			'siparisIcerikGorsel' => $urun["urunGorsel"]
		]);
	}
} 
else //eğer sipariş kaydı var ise
{
	$siparisKontrol = $db->get("Siparisler", "*", [
		"siparisKodu" => $_SESSION['SiparisKodu']
	]);
	$siparisIskontoUcreti = $siparisKontrol["siparisIskontoUcreti"]; //iskonto ücreti atamasını yaptık
	if ($siparisKontrol) {
		$siparis = $db->update('Siparisler', [
			'siparisUyeId' => $uye['uyeId'],
			'siparisNot' => $note,
			'siparisTeslimatUyeAdresId' => $uyeTeslimatAdresId,
			'siparisFaturaUyeAdresId' => $uyeFaturaAdresId,
			'siparisOdemeTipiId' => $siparisOdemeTipiId,
			'siparisIndirimKodu' => "",
			'siparisIndirimYuzdesi' => $siparisIndirimYuzdesi,
			'siparisKargoUcreti' => $siparisKargoUcreti,
			'siparisDilId' => $_SESSION["dilId"],
			'siparisTeslimatTarihi' => $siparisTeslimatTarihi,
			'siparisKayitTarihi' => date("Y-m-d H:i:s")
		], [
			"siparisId" => $siparisKontrol["siparisId"]
		]);

		$silIcerik = $db->delete("SiparisIcerikleri", [
			"siparisIcerikSiparisId" => $siparisKontrol["siparisId"]
		]);

		for ($i = 0; $i < count($sepet); $i++) {
			$urun = $db->get("Urunler", [
				"[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
				"[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
				"[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
				"[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
				"[>]KategoriDilBilgiler" => ["UrunKategoriler.urunKategoriKategoriId" => "kategoriDilBilgiKategoriId"],
				"[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
			], "*", [
				"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
				"urunVaryantDilBilgiVaryantId" => $sepet[$i]["urunId"],
				"urunVaryantDilBilgiDurum" => 1,
				"ORDER" => [
					"urunId" => "ASC"
				]
			]);

			$hesapla = $fonk->Hesapla($sepet[$i]["urunId"], $sepet[$i]["varyantId"], $uye['uyeIndirimOrani']);
			$toplamTutar += ($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"])) * $sepet[$i]["adet"];
			$araTutar += ($hesapla["birimFiyat"]) * $sepet[$i]["adet"];
			$kdvTutar += ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]) * $sepet[$i]["adet"];

			$siparisIcerik = $db->insert('SiparisIcerikleri', [
				'siparisIcerikSiparisId' => $siparisId,
				'siparisIcerikUrunId' => $urun["urunId"],
				'siparisIcerikVaryantId' => intval($sepet[$i]["varyantId"]),
				'siparisIcerikUrunVaryantDilBilgiId' => intval($sepet[$i]["urunId"]),
				'siparisIcerikAdet' => $sepet[$i]["adet"],
				'siparisIcerikNot' => $sepet[$i]["siparisIcerikNot"],
				'siparisIcerikUrunAdi' => $urun["urunDilBilgiAdi"],
				'siparisIcerikUrunVaryantDilBilgiAdi' => $urun["urunVaryantDilBilgiAdi"],
				'siparisIcerikTeslimatDurumu' => 1,
				'siparisIcerikFiyat' => $fonk->paraCevir($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]),$urun["paraBirimKodu"],"TRY"),
				'siparisIcerikIndirimliFiyat' => $fonk->paraCevir($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]),$urun["paraBirimKodu"],"TRY"),
				'siparisIcerikGorsel' => $urun["urunGorsel"]
			]);
		}
	} else {
		echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . 'cart"; </script>';
		exit;
	}
}

$siparis = $db->get("Siparisler", "*", [
	"siparisKodu" => $_SESSION['SiparisKodu'],
	"siparisUyeId" => $uye['uyeId']
]);
if (!$siparis) {
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . 'cart"; </script>';
	exit;
}
$siparisIcerikleri = $db->select("SiparisIcerikleri", [
	"[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
	"[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
], "*", [
	"urunDilBilgiDilId" => $_SESSION["dilId"],
	"urunDurum" => 1,
	"urunDilBilgiDurum" => 1,
	"siparisIcerikSiparisId" => $siparis['siparisId']
]);

if (count($siparisIcerikleri) <= 0) {
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . 'cart"; </script>';
	exit;
}

if ($siparisOdemeTipiId == 2) //havale eft ödeme 
{
	$siparis = $db->get("Siparisler", [
		"[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
	], "*", [
		"siparisKodu" => $_SESSION['SiparisKodu'],
		"siparisUyeId" => $uye['uyeId']
	]);

	$siparisDurumKontrol = $db->get("SiparisSiparisDurumlari", [
		"[<]SiparisDurumDilBilgiler" => ["SiparisDurumlari.siparisDurumId" => "siparisDurumDilBilgiSiparisDurumId"]
	], "*", [
		'siparisSiparisDurumSiparisId' => $siparis["siparisId"],
		'siparisSiparisDurumSiparisDurumId' => 9, //havale/eft
		"siparisDurumDilBilgiDilId" => $_SESSION["dilId"],
	]);
	if ($siparisDurumKontrol) {
		$hata = $fonk->getPDil("Siparişinizin Ödemesi Daha Önce Onaylandı. Kabul edilme tarihi:") . $fonk->sqlToDateTime($siparisDurumKontrol["siparisSiparisDurumKayitTarihi"]);
	} 
	else {
		$siparisGuncelle = $db->update('Siparisler', [
			'siparisOdemeBilgileri' => "Havale/Eft"
		], [
			"siparisId" => $siparis["siparisId"]
		]);

		$siparisDurum = $db->insert('SiparisSiparisDurumlari', [
			'siparisSiparisDurumSiparisId' => $siparis["siparisId"],
			'siparisSiparisDurumSiparisDurumId' => 9, //havale/eft Ödeme
			'siparisSiparisDurumKargoFirmaId' => 0,
			'siparisSiparisDurumKargoTakipKodu' => "",
			'siparisSiparisDurumKargoTakipLink' => "",
			'siparisSiparisDurumKayitTarihi' => date("Y-m-d H:i:s")
		]);

		// $baslik = $fonk->getDil("Sipariş Bilgisi");
		// $mesaj = $fonk->getDil("Sipariş kodu") . ":" . $siparis["siparisKodu"] . "<br />" . $fonk->getDil("Sipariş durumu") . ":" . $fonk->getDil("Havale/Eft");
		// include("Panel/Pages/bildirimMailTemplate.php");
		// $fonk->mailGonder($siparis["uyeMail"], $baslik, $body);
		// $fonk->mailGonder($sabitB["sabitBilgiBildirimMail"], $baslik, $body);
		header("HTTP/1.1 303 See Other");
		header("Location: " . $sabitB["sabitBilgiSiteUrl"] . "havale?s=" . $_SESSION["SiparisKodu"]);
		unset($_SESSION["SiparisKodu"]);
		unset($_SESSION["Sepet"]);
	}
}
