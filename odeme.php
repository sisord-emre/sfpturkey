<?php
extract($_POST);

if($uyeTeslimatAdresId == ""){
	echo '<script> alert("Teslimat adresi veya Fatura Adresi boş olamaz") </script>';
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . 'checkout"; </script>';
	exit;
}
//havale eft ödeme 
if ($odemeTipi == "cash") {
	include 'Panel/System/Config.php';
}
else {
	include 'layouts/header.php';
}


if ($_SESSION['uyeSessionKey'] == "") {
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . 'account"; </script>';
	exit;
}
$uye = $db->get("Uyeler", "*", [
	"uyeDurum" => 1,
	"uyeSessionKey" => $_SESSION['uyeSessionKey'],
]);
if (!$uye) {
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . 'account"; </script>';
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

$adres = $db->get("UyeAdresler", [
	"[>]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
	"[>]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
	"[>]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
], "*", [
	"uyeAdresId" => $uyeTeslimatAdresId
]);

if ($odemeTipi == "iyzico") {
	$siparisOdemeTipiId = 1;
} 
else if ($odemeTipi == "cash") {
	$siparisOdemeTipiId = 2;
}

$siparisIskontoUcreti = 0;
$siparisIndirimYuzdesi = 0;
$toplamTutar = 0;

if ($_SESSION['SiparisKodu'] == "") //eğer sipariş kaydı yok ise
{
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . 'account"; </script>';
	exit;
} 
else //eğer sipariş kaydı var ise
{
	$siparisKontrol = $db->get("Siparisler", "*", [
		"siparisKodu" => $_SESSION['SiparisKodu']
	]);
	$siparisIskontoUcreti = $siparisKontrol["siparisIskontoUcreti"]; //iskonto ücreti atamasını yaptık
	$siparisOdenenIskontoUcreti = $fonk->paraCevir($siparisIskontoUcreti, "USD", "TRY");
	if ($siparisKontrol) 
	{
		$siparis = $db->update('Siparisler', [
			'siparisUyeId' => $uye['uyeId'],
			'siparisNot' => $note,
			'siparisTeslimatUyeAdresId' => intval($uyeTeslimatAdresId),
			'siparisFaturaUyeAdresId' => intval($uyeFaturaAdresId),
			'siparisIndirimKodu' => "",
			'siparisIndirimYuzdesi' => floatval($siparisIndirimYuzdesi),
			'siparisKargoUcreti' => floatval($siparisKargoUcreti),
			'siparisOdenenIskontoUcreti' => floatval($siparisOdenenIskontoUcreti),
			'siparisToplam' => floatval($siparisToplam),
			'siparisDolarKur' => floatval($sabitB["sabitBilgiDolar"]),
			'siparisDilId' => $_SESSION["dilId"],
			'siparisTeslimatTarihi' => null,
			'siparisKayitTarihi' => date("Y-m-d H:i:s")
		], [
			"siparisKodu" => $siparisKontrol["siparisKodu"]
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
				"urunDilBilgiDilId" => $_SESSION["dilId"],
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
				'siparisIcerikSiparisId' => $siparisKontrol["siparisId"],
				'siparisIcerikUrunId' => $urun["urunId"],
				'siparisIcerikVaryantId' => intval($sepet[$i]["varyantId"]),
				'siparisIcerikUrunVaryantDilBilgiId' => intval($sepet[$i]["urunId"]),
				'siparisIcerikAdet' => $sepet[$i]["adet"],
				'siparisIcerikNot' => $note,
				'siparisIcerikUrunAdi' => $urun["urunDilBilgiAdi"],
				'siparisIcerikUrunVaryantDilBilgiAdi' => $urun["urunVaryantDilBilgiAdi"],
				'siparisIcerikTeslimatDurumu' => 1,
				'siparisIcerikFiyat' => floatval($fonk->paraCevir($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]), $urun["paraBirimKodu"], "TRY")),
				'siparisIcerikKdvsizFiyat' => floatval($fonk->paraCevir($hesapla["birimFiyat"],$urun["paraBirimKodu"],"TRY")),
				'siparisIcerikIndirimliFiyat' => floatval($fonk->paraCevir($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]), $urun["paraBirimKodu"], "TRY")),
				'siparisIcerikPanelFiyat' => floatval($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"])),
				'siparisIcerikPanelFiyatKdvsiz' => floatval($hesapla["birimFiyat"]),
				'siparisIcerikPanelIndirimliFiyat' => floatval($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"])),
				'siparisIcerikKdv' => floatval($fonk->paraCevir($hesapla["birimFiyat"] / 100 * $urun["urunKdv"],$urun["paraBirimKodu"],"TRY")),
				'siparisIcerikGorsel' => $urun["urunGorsel"]
			]);
		}

		if($siparisIskontoUcreti > 0){
			$toplamTutar-=$siparisIskontoUcreti;
		}
	} 
	else {
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
	"[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
], "*", [
	"urunDilBilgiDilId" => $_SESSION["dilId"],
	"urunDurum" => 1,
	"urunDilBilgiDurum" => 1,
	"siparisIcerikSiparisId" => $siparis['siparisId']
]);

$bankalar = $db->select("BankaBilgileri", "*", [
	"bankaBilgiDurum" => 1
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
		'siparisSiparisDurumSiparisDurumId' => 2, //ödeme yapıldı
		"siparisDurumDilBilgiDilId" => $_SESSION["dilId"],
	]);
	if ($siparisDurumKontrol) {
		$hata = $fonk->getPDil("Siparişinizin Ödemesi Daha Önce Onaylandı. Kabul edilme tarihi:") . $fonk->sqlToDateTime($siparisDurumKontrol["siparisSiparisDurumKayitTarihi"]);
	} 
	else {
		$siparisGuncelle = $db->update('Siparisler', [
			'siparisOdemeBilgileri' => "Havale/Eft",
			'siparisOdemeTipiId' => $siparisOdemeTipiId
		], [
			"siparisId" => $siparis["siparisId"]
		]);

		$siparisDurum = $db->insert('SiparisSiparisDurumlari', [
			'siparisSiparisDurumSiparisId' => $siparis["siparisId"],
			'siparisSiparisDurumSiparisDurumId' => 1, //ödeme bekleniyor
			'siparisSiparisDurumKargoFirmaId' => 0,
			'siparisSiparisDurumKargoTakipKodu' => "",
			'siparisSiparisDurumKargoTakipLink' => "",
			'siparisSiparisDurumKayitTarihi' => date("Y-m-d H:i:s")
		]);

		$siparis = $db->get("Siparisler", [
			"[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
		], "*", [
			"siparisKodu" => $_SESSION['SiparisKodu'],
			"siparisUyeId" => $uye['uyeId']
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
			"urunDilBilgiDilId" => $_SESSION["dilId"],
			"urunDurum" => 1,
			"urunDilBilgiDurum" => 1,
			"siparisIcerikSiparisId" => $siparis['siparisId']
		]);

		$bankalar = $db->select("BankaBilgileri", "*", [
			"bankaBilgiDurum" => 1
		]);

		$urunArtis = $db->select("Siparisler", [
			"[<]SiparisIcerikleri" => ["Siparisler.siparisId" => "siparisIcerikSiparisId"],
			"[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
		], "*", [
			"siparisKodu" => $siparis['siparisKodu']
		]);

		foreach ($urunArtis as $key => $value) {
			$arttir = $db->update('Urunler', [
				'urunSatisMiktar' => $value["urunSatisMiktar"] + $value["siparisIcerikAdet"],
				'urunStok' => $value["urunStok"] - $value["siparisIcerikAdet"]
			], [
				"urunId" => $value["urunId"]
			]);
		}
		
		$baslik = "Siparis No: ".$siparisKodu."";
		$baslik2 = "SFPTURKEY-Siparis No: ".$siparisKodu."";
		include("Mailtemplate/odemeEmailTemplate.php");
		$fonk->mailGonder($siparis["uyeMail"], $baslik2, $body);
		// $fonk->mailGonder($gondericiMail[3], $baslik, $body); 
		header("HTTP/1.1 303 See Other");
		header("Location: " . $sabitB["sabitBilgiSiteUrl"] . "havale?s=" . $_SESSION["SiparisKodu"]);
	}
}
?>
<?php if ($siparisOdemeTipiId == 1) { // iyzico 
$siparis = $db->get("Siparisler", "*", [
	"siparisKodu" => $_SESSION['SiparisKodu'],
	"siparisUyeId" => $uye['uyeId']
]); 
?> 
<div id="nt_content">

	<div class="kalles-section page_section_heading">
		<div class="page-head pr oh cat_bg_img page_head_">
			<div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
			<div class="container pr z_100">
				<h1 class="mb__5 cw">Ödeme İşlemi</h1>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="billing-info-wrap">
			<div class="row">
				<div class="col-lg-12"><!-- Card Görseli -->
					<div class='card-wrapper'></div>
				</div>
				<div class="col-lg-12">
					<div class="billing-info mb-20">
						<?php if ($sabitB["sabitBilgiEposFirma"] == 2) { //iysico 
						?>
							<form action="" id="formpost" method="POST" class="card-form">
								<fieldset class="mb-1">
									<h5><?=$fonk->getDil('Kart Numarasi') ?></h5>
									<div class="form-group">
										<input type="text" class="form-control card-number" name="number" id="card-number" maxlength="19" placeholder="<?=$fonk->getDil('kart-numarasi') ?>" autocomplete="off" onkeyup="cardAktarim();" required>
									</div>
								</fieldset>
								<fieldset class="mb-1">
									<h5><?=$fonk->getDil('Kart Üzerindeki İsim') ?></h5>
									<div class="form-group">
										<input type="text" class="form-control card-name" name="name" id="card-name" placeholder="<?=$fonk->getDil('kart-uzerindeki-isim') ?>"  autocomplete="off" onkeyup="cardAktarim();" required>
									</div>
								</fieldset>
								<div class="row">
									<div class="col-md-6">
										<fieldset class="mb-1">
											<h5><?=$fonk->getDil('Son Kullanma Tarihi') ?></h5>
											<div class="form-group">
												<input type="text" class="form-control card-expiry" name="expiry" id="card-expiry" onkeyup="formatKontrol()" placeholder="03/24"  autocomplete="off" required>
											</div>
										</fieldset>
									</div>
									<div class="col-md-6">
										<fieldset class="mb-1">
											<h5>CVC</h5>
											<div class="form-group">
												<input type="text" class="form-control card-cvc" name="cvc" id="card-cvc" maxlength="16" placeholder="CVC"  autocomplete="off" onkeyup="cardAktarim();" required>
											</div>
										</fieldset>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<fieldset class="mb-1">
											<div class="form-group" style="text-align:center">
												<!-- kart bilgileri script ile aktarıalcak -->
												<input type="hidden" name="cc_owner" id="kartSahibi">
												<input type="hidden" name="card_number" id="kartNumara">
												<input type="hidden" name="expiry_month" id="kartAy">
												<input type="hidden" name="expiry_year" id="kartYil">
												<input type="hidden" name="cvv" id="kartCvv">
												<!-- !kart bilgileri script ile aktarıalcak -->
												<input type="hidden" name="tutar" value="<?=$fonk->paraCevir($toplamTutar,$urun["paraBirimKodu"],"TRY") + $siparis["siparisKargoUcreti"];?>" />
												<input type="hidden" name="siparisNo" value="<?= $_SESSION['SiparisKodu'] ?>" />
												<input type="hidden" name="musteriId" value="<?= $uye["uyeId"] ?>" />
												<input type="hidden" name="musteriAdi" value="<?= $uyeAdi ?>" />
												<input type="hidden" name="musteriSoyadi" value="<?= $uyeSoyadi ?>" />
												<input type="hidden" name="musteriEmail" value="<?= $uyeMail ?>" />
												<input type="hidden" name="musteriTcNo" value="<?= $uye["uyeTcVergiNo"] ?>" />
												<input type="hidden" name="musteriUlke" value="<?= $adres["ulkeAdi"] ?>" />
												<input type="hidden" name="musteriIl" value="<?= $adres["ilAdi"] ?>" />
												<input type="hidden" name="musteriAdres" value="<?= $adres["uyeAdresBilgi"] . " " . $adres["ilAdi"] . "/" . $adres["ilceAdi"] ?>" />
						
												<input type="hidden" name="urunId" value="<?=$urun["urunId"]?>"/>
												<input type="hidden" name="urunAdi" value="<?=$urun["urunDilBilgiAdi"]?>"/>
												<input type="hidden" name="urunKategori" value="<?=$urun["kategoriDilBilgiBaslik"]?>"/>

												<button type="submit" id="odemeYap" class="btn_checkout button button_primary tu mt__10 mb__10 js_add_ld w__100" style="width: auto;"><?=$fonk->getDil('Odemeyi Tamamla') ?></button>

											</div>
										</fieldset>
									</div>
								</div>
							</form>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<?php include 'layouts/footer.php'; ?>

<div class="modal fade text-left" id="pos3d" role="dialog" aria-hidden="true"><!-- id leri unutma -->
	<!-- ekleme modalı -->
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Ödeme Bilgileri</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="card-body" id="response">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Kapat</button>
			</div>
		</div>
	</div>
</div>

<script>
	<?php if ($siparisOdemeTipiId == 1) { //iysico ?>
	$('#formpost').submit(function(e) {
		e.preventDefault(); //submit postu kesyoruz
		$("#pos3d").modal("show");
		var data = new FormData(this);
		$.ajax({
			type: "POST",
			url: "iyzipay/samples/initialize_threeds.php",
			data: data,
			contentType: false,
			processData: false,
			success: function(gelenSayfa) {
				//swal(gelenSayfa);
				$('#response').html(gelenSayfa);
				if (gelenSayfa.search("<noscript>")) {
					gelenSayfa = gelenSayfa.replace("<noscript>", "");
					gelenSayfa = gelenSayfa.replace("</noscript>", "");
					$('#response').append(gelenSayfa);
				}
			}
		});
	});
	<?php } ?>

	function formatKontrol() {
		var cardExpiry = document.getElementById("card-expiry").value;
		cardExpiry = cardExpiry.replace(" ", "");
		if (cardExpiry.length == 2) {
			document.getElementById("card-expiry").value = String(cardExpiry) + "/";
		}
		if (cardExpiry.length > 6) {
			document.getElementById("card-expiry").value = cardExpiry.substr(0, 5);
		}
		cardAktarim();
	}

	function cardAktarim() {
		var adSoyad = document.getElementById("card-name").value;
		var kartNo = document.getElementById("card-number").value;
		var ayYil = document.getElementById("card-expiry").value;
		var cvv = document.getElementById("card-cvc").value;
		kartNo = kartNo.replace(/ /g, "");
		ayYil = ayYil.split("/");
		document.getElementById("kartSahibi").value = adSoyad;
		document.getElementById("kartNumara").value = kartNo;
		document.getElementById("kartAy").value = ayYil[0];
		document.getElementById("kartYil").value = ayYil[1];
		document.getElementById("kartCvv").value = cvv;
	}
</script>