<?php
include 'layouts/header.php';
extract($_POST);
if (!$_SESSION['uyeSessionKey']) {
    echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . '?link=checkout"; </script>';
    exit;
} 
else {
    $uye = $db->get("Uyeler", "*", [
        "uyeDurum" => 1,
        "uyeSessionKey" => $_SESSION['uyeSessionKey']
    ]);
}
if ($_SESSION['Sepet'] == "" || $_SESSION['Sepet'] == "null" || $_SESSION['Sepet'] == null || !isset($_SESSION['Sepet'])) {
    $_SESSION['Sepet'] = "[]";
}
$sepet = json_decode($_SESSION['Sepet'], true);
if (count($sepet) <= 0) {
    echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . '"; </script>';
    exit;
}

// eğer herhangi bir ödenmiş sipariş kodu sessiondan gelirse anasayfaya gönder
$isSiparisDurumKontrol = $db->get("Siparisler", [
    "[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
    "[<]SiparisSiparisDurumlari" => ["Siparisler.siparisId" => "siparisSiparisDurumSiparisId"]
], "*", [
    "siparisKodu" => $_SESSION['SiparisKodu'],
    "siparisUyeId" => $uye['uyeId']
]);

if($isSiparisDurumKontrol)
{
    unset($_SESSION['SiparisKodu']);
    $_SESSION['SiparisKodu'] == "";
    echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . '"; </script>';
    exit;
}

$siparisIskontoUcreti = 0;
$siparisKargoUcreti =$siparisKargoUcreti;
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
		'siparisTeslimatUyeAdresId' => 0,
		'siparisFaturaUyeAdresId' => 0,
		'siparisOdemeTipiId' => 0,
		'siparisIndirimKodu' => "",
		//'siparisIndirimYuzdesi' => $siparisIndirimYuzdesi,
		'siparisKargoUcreti' => floatval($siparisKargoUcreti),
		'siparisDilId' => $_SESSION["dilId"],
		'siparisParaBirimId' => $_SESSION["paraBirimId"],
		'siparisOdemeBilgileri' => "",
		//'siparisTeslimatTarihi' => "",
        "siparisIp" => $_SERVER['REMOTE_ADDR'],
		'siparisKayitTarihi' => date("Y-m-d H:i:s")
	]);
	$siparisId = $db->id();

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
			'siparisIcerikNot' => $note,
			'siparisIcerikUrunAdi' => $urun["urunDilBilgiAdi"],
			'siparisIcerikUrunVaryantDilBilgiAdi' => $urun["urunVaryantDilBilgiAdi"],
			'siparisIcerikTeslimatDurumu' => 1,
			'siparisIcerikFiyat' => floatval($fonk->paraCevir($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]),$urun["paraBirimKodu"],"TRY")),
			'siparisIcerikKdvsizFiyat' => floatval($fonk->paraCevir($hesapla["birimFiyat"],$urun["paraBirimKodu"],"TRY")),
            'siparisIcerikIndirimliFiyat' => floatval($fonk->paraCevir($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]),$urun["paraBirimKodu"],"TRY")),
            'siparisIcerikPanelFiyat' => floatval($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"])),
            'siparisIcerikPanelFiyatKdvsiz' => floatval($hesapla["birimFiyat"]),
			'siparisIcerikPanelIndirimliFiyat' => floatval($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"])),
            'siparisIcerikKdv' => floatval($fonk->paraCevir($hesapla["birimFiyat"] / 100 * $urun["urunKdv"],$urun["paraBirimKodu"],"TRY")),
			'siparisIcerikGorsel' => $urun["urunGorsel"]
		]);
	}

	$siparisKontrol = $db->get("Siparisler", "*", [
		"siparisKodu" => $siparisKodu
	]);
} 
else //eğer sipariş kaydı var ise
{
	$siparisKontrol = $db->get("Siparisler", "*", [
		"siparisKodu" => $_SESSION['SiparisKodu']
	]);
    $siparisIskontoUcreti = $siparisKontrol["siparisIskontoUcreti"]; //iskonto ücreti atamasını yaptık
    $siparisOdenenIskontoUcreti = $fonk->paraCevir($siparisIskontoUcreti,"USD","TRY");

	if ($siparisKontrol) {
		$siparis = $db->update('Siparisler', [
			'siparisUyeId' => $uye['uyeId'],
			'siparisNot' => $note,
			'siparisTeslimatUyeAdresId' => 0,
		    'siparisFaturaUyeAdresId' => 0,
			'siparisOdemeTipiId' => 0,
			'siparisIndirimKodu' => "",
			//'siparisIndirimYuzdesi' => $siparisIndirimYuzdesi,
			'siparisKargoUcreti' => floatval($siparisKargoUcreti),
			'siparisDilId' => $_SESSION["dilId"],
            'siparisOdenenIskontoUcreti' => $siparisOdenenIskontoUcreti,
			//'siparisTeslimatTarihi' => "",
            "siparisIp" => $_SERVER['REMOTE_ADDR'],
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
				'siparisIcerikSiparisId' => $siparisKontrol["siparisId"],
				'siparisIcerikUrunId' => $urun["urunId"],
				'siparisIcerikVaryantId' => intval($sepet[$i]["varyantId"]),
                'siparisIcerikUrunVaryantDilBilgiId' => intval($sepet[$i]["urunId"]),
				'siparisIcerikAdet' => $sepet[$i]["adet"],
				'siparisIcerikNot' => $note,
				'siparisIcerikUrunAdi' => $urun["urunDilBilgiAdi"],
				'siparisIcerikUrunVaryantDilBilgiAdi' => $urun["urunVaryantDilBilgiAdi"],
				'siparisIcerikTeslimatDurumu' => 1,
				'siparisIcerikFiyat' => floatval($fonk->paraCevir($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]),$urun["paraBirimKodu"],"TRY")),
                'siparisIcerikKdvsizFiyat' => floatval($fonk->paraCevir($hesapla["birimFiyat"],$urun["paraBirimKodu"],"TRY")),
                'siparisIcerikIndirimliFiyat' => floatval($fonk->paraCevir($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]),$urun["paraBirimKodu"],"TRY")),
                'siparisIcerikPanelFiyat' => floatval($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"])),
                'siparisIcerikPanelFiyatKdvsiz' => floatval($hesapla["birimFiyat"]),
                'siparisIcerikPanelIndirimliFiyat' => floatval($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"])),
                'siparisIcerikKdv' => floatval($fonk->paraCevir($hesapla["birimFiyat"] / 100 * $urun["urunKdv"],$urun["paraBirimKodu"],"TRY")),
				'siparisIcerikGorsel' => $urun["urunGorsel"]
			]);
		}
	} 
    else {
		echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . 'cart"; </script>';
		exit;
	}
}
$siparisKargoUcreti = $fonk->KargoUcreti(number_format($fonk->paraCevir($toplamTutar - $siparisIskontoUcreti,$urun["paraBirimKodu"],"TRY"),2,',','.')); 
//$siparisKargoUcreti=$siparisKontrol["siparisKargoUcreti"];
?>

<div id="nt_content">

    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw"> <?= $fonk->getDil("Ödeme İşlemi"); ?></h1>
            </div>
        </div>
    </div>

    <form action="odeme" method="post">
        <div class="kalles-section cart_page_section container mt__60">
            <div class="frm_cart_page check-out_calculator">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="checkout-section">
                            <h3 class="checkout-section__title"><?= $fonk->getDil("Fatura Detayları"); ?> </h3>
                            <div class="row">
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeAdi"><?= $fonk->getDil("İsim"); ?> </label>
                                    <input type="text" id="uyeAdi" name="uyeAdi" value="<?= $uye["uyeAdi"] ?>" placeholder="İsim" required>
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeSoyadi"><?= $fonk->getDil("Soyisim"); ?> </label>
                                    <input type="text" id="uyeSoyadi" name="uyeSoyadi" value="<?= $uye["uyeSoyadi"] ?>" placeholder="Soyisim" required>
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeTel"><?= $fonk->getDil("Telefon"); ?> </label>
                                    <input type="tel" id="uyeTel" name="uyeTel" value="<?= $uye["uyeTel"] ?>" placeholder="Telefon" required />
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeMail"><?= $fonk->getDil("Email"); ?> </label>
                                    <input type="email" id="uyeMail" name="uyeMail" value="<?= $uye["uyeMail"] ?>" placeholder="Email" required />
                                </p>
                                <p class="checkout-section__field col-lg-12 col-12">
                                    <label for="siparisTeslimatTarihi"><?= $fonk->getDil("Teslim tarihi"); ?> </label>
                                    <?= $fonk->getDil("Siparişiniz 3 iş gününde kargoya verilir."); ?>
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeTeslimatAdresId"><?= $fonk->getDil("Teslimat adresi"); ?> 
                                        <span style="float: right">
                                            <a href="javascript:AdresModal('','<?= $sabitB['sabitBilgiSiteUrl'] ?>checkout');" style="color: #e45050;"><?= $fonk->getDil("Yeni ekle"); ?> </a>
                                        </span>
                                    </label>
                                    <select id="uyeTeslimatAdresId" name="uyeTeslimatAdresId" onchange="AdresBilgi('uyeTeslimatAdresId','teslimat_adres');" required>
                                        <option value=""><?= $fonk->getDil("Seçiniz"); ?> </option>
                                        <?php
                                        $uyeAdresler3 = $db->select("UyeAdresler", [
                                            "[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
                                            "[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
                                            "[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"]
                                        ], "*", [
                                            "uyeAdresUyeId" => $uye["uyeId"]
                                        ]);
                                        foreach ($uyeAdresler3 as $key => $value) {
                                        ?>
                                            <option value="<?= $value["uyeAdresId"] ?>"><?= $value["uyeAdresAdi"] ?></option>
                                        <?php } ?>
                                    </select>
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeFaturaAdresId"><?= $fonk->getDil("Fatura Adresi"); ?> 
                                        <span style="float: right">
                                            <a href="javascript:AdresModal('','<?= $sabitB['sabitBilgiSiteUrl'] ?>checkout');" style="color: #e45050;"><?= $fonk->getDil("Yeni ekle"); ?> </a>
                                        </span>
                                    </label>
                                    <select id="uyeFaturaAdresId" name="uyeFaturaAdresId" onchange="AdresBilgi('uyeFaturaAdresId','fatura_adres');" required>
                                        <option value=""><?= $fonk->getDil("Seçiniz"); ?> </option>
                                        <?php
                                        $uyeAdresler2= $db->select("UyeAdresler", [
                                            "[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
                                            "[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
                                            "[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"]
                                        ], "*", [
                                            "uyeAdresUyeId" => $uye["uyeId"]
                                        ]);
                                        foreach ($uyeAdresler2 as $key => $value) {
                                        ?>
                                            <option value="<?= $value["uyeAdresId"] ?>"><?= $value["uyeAdresAdi"] ?></option>
                                        <?php } ?>
                                    </select>
                                </p>

                                <div class="checkout-section__field col-lg-6 col-12 order-review__wrapper" id="teslimat_adres">
                                </div>

                                <div class="checkout-section__field col-lg-6 col-12 order-review__wrapper" id="fatura_adres">
                                </div>

                                <input type="hidden" name="siparisKargoUcreti" id="siparisKargoUcreti" value="<?=$siparisKargoUcreti?>">
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6 mt__50 mb__80">
                        <div class="order-review__wrapper">
                            <h3 class="order-review__title"><?= $fonk->getDil("Siparişiniz"); ?> </h3>
                            <div class="checkout-order-review">
                                <table class="checkout-review-order-table">
                                    <thead>
                                        <tr>
                                            <th class="product-name"><?= $fonk->getDil("Ürün"); ?> </th>
                                            <th class="product-total"><?= $fonk->getDil("Fiyat"); ?> </th>
                                            <th class="product-total" style="width: 15%;"><?= $fonk->getDil("KDV-1"); ?> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $toplamTutar = 0;
                                        $araTutar = 0;
                                        $kdvTutar = 0;
                                        for ($i = 0; $i < count($sepet); $i++) {
                                            $urun = $db->get("Urunler", [
                                                "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
                                                "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
                                                "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
                                                "[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
                                                "[>]KategoriDilBilgiler" => ["UrunKategoriler.urunKategoriKategoriId" => "kategoriDilBilgiKategoriId"],
                                                "[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
                                            ],"*",[
                                                "urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
                                                "urunVaryantDilBilgiVaryantId" => $sepet[$i]["urunId"],
                                                "urunVaryantDilBilgiDurum" => 1,
                                                "ORDER" => [
                                                    "urunId" => "ASC"
                                                ]
                                            ]);

                                            $hesapla = $fonk->Hesapla($sepet[$i]["urunId"], $sepet[$i]["varyantId"],$uye['uyeIndirimOrani']);
                                            $toplamTutar += ($hesapla["birimFiyat"] + ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"])) * $sepet[$i]["adet"];
                                            $araTutar += ($hesapla["birimFiyat"]) * $sepet[$i]["adet"];
                                            $kdvTutar += ($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]) * $sepet[$i]["adet"];
                                        ?>
                                            <tr class="cart_item" style="border-bottom: 1px solid #ddd;">
                                                <td class="product-name"><?= $urun["urunVaryantDilBilgiAdi"]; ?>
                                                    <strong class="product-quantity">× <?= $sepet[$i]["adet"] ?></strong>
                                                </td>
                                                <td class="product-total">
                                                    <span class="cart_price">
                                                        <?= $_SESSION["paraBirimSembol"] ?><?=number_format($fonk->paraCevir($hesapla["birimFiyat"]*$sepet[$i]["adet"],$urun["paraBirimKodu"],"TRY"),2,',','.');?>
                                                        
                                                    </span>
                                                </td>
                                                <td class="product-total">
                                                    <span class="cart_price">
                                                        <?= $_SESSION["paraBirimSembol"] ?><?=number_format($fonk->paraCevir(($hesapla["birimFiyat"] / 100 * $urun["urunKdv"]) * $sepet[$i]["adet"],$urun["paraBirimKodu"],"TRY"),2,',','.');?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="order-total cart_item">
                                            <th colspan="2" style="text-align: right;"><?= $fonk->getDil("Ara toplam"); ?> </th>
                                            <td colspan="2">
                                                <strong>
                                                    <span class="cart_price amount">
                                                        <?= $_SESSION["paraBirimSembol"] ?><?=number_format($fonk->paraCevir($araTutar,$urun["paraBirimKodu"],"TRY"),2,',','.');?>
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr class="order-total cart_item">
                                            <th colspan="2" style="text-align: right;"><?= $fonk->getDil("Toplam KDV"); ?> </th>
                                            <td colspan="2">
                                                <strong>
                                                    <span class="cart_price amount">
                                                        <?= $_SESSION["paraBirimSembol"] ?><?=number_format($fonk->paraCevir($kdvTutar,$urun["paraBirimKodu"],"TRY"),2,',','.'); ?> 
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr class="order-total cart_item">
                                            <th colspan="2" style="text-align: right;"><?= $fonk->getDil("Kdv Dahil Toplam"); ?> </th>
                                            <td colspan="2">
                                                <strong>
                                                    <span class="cart_price amount">
                                                        <?= $_SESSION["paraBirimSembol"] ?><?=number_format($fonk->paraCevir($toplamTutar,$urun["paraBirimKodu"],"TRY"),2,',','.'); ?>
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>
                                        <?php if($siparisIskontoUcreti > 0) {?>
                                        <tr class="order-total cart_item">
                                            <th colspan="2" style="text-align: right;"><?= $fonk->getDil("Proje İskonto"); ?> </th>
                                            <td colspan="2">
                                                <strong>
                                                    <span class="cart_price amount">
                                                        <?= $_SESSION["paraBirimSembol"] ?><?=number_format($siparisOdenenIskontoUcreti,2,',','.');?>
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr class="order-total cart_item">
                                            <th colspan="2" style="text-align: right;"><?= $fonk->getDil("Kdv Dahil Proje Tutarı"); ?> </th>
                                            <td colspan="2">
                                                <strong>
                                                    <span class="cart_price amount">
                                                        <?= $_SESSION["paraBirimSembol"] ?><?=number_format($fonk->paraCevir($toplamTutar - $siparisIskontoUcreti,$urun["paraBirimKodu"],"TRY"),2,',','.');?>
                                                        
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr class="order-total cart_item">
                                            <th colspan="2" style="text-align: right;"><?= $fonk->getDil("Vergiler Dahil Kargo Ucreti"); ?> </th>
                                            <td>
                                                <strong>
                                                    <span class="cart_price amount">
                                                        <?= $_SESSION["paraBirimSembol"] ?><?=number_format($siparisKargoUcreti,2,',','.');?>
                                                        
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>
                                        
                                        
                                        <tr class="order-total cart_item">
                                            <th colspan="2" style="text-align: right;"><?= $fonk->getDil("TOPLAM"); ?> </th>
                                            <td>
                                                <strong>
                                                    <span class="cart_price amount">
                                                        <?= $_SESSION["paraBirimSembol"] ?><?=number_format($fonk->paraCevir($toplamTutar - $siparisIskontoUcreti,$urun["paraBirimKodu"],"TRY")+ $siparisKargoUcreti,2,',','.');?>
                                                        <input type="hidden" name="siparisToplam" id="siparisToplam" value="<?=$fonk->paraCevir($toplamTutar - $siparisIskontoUcreti,$urun["paraBirimKodu"],"TRY")+ $siparisKargoUcreti;?>">
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr class="order-total cart_item">
                                            <th><?= $fonk->getDil("Not"); ?> :</th>
                                            <td><?= $_POST['note']; ?><input type="hidden" name="note" value="<?= $_POST['note']; ?>" /></td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="checkout-payment">
                                    <ul class="payment_methods">
                                        <li class="payment_method">
                                            <input id="payment_method_stripe" type="radio" class="input-radio" name="odemeTipi" value="iyzico" checked="checked">
                                            <label for="payment_method_stripe">
                                                <?= $fonk->getDil("Kredi kartı"); ?> 
                                                <img src="assets/images/shopping-cart/visa.svg" class="stripe-visa-icon stripe-icon" alt="Visa">
                                                <img src="assets/images/shopping-cart/mastercard.svg" class="stripe-mastercard-icon stripe-icon" alt="Mastercard">
                                                <img src="assets/images/shopping-cart/amex.svg" class="stripe-amex-icon stripe-icon" alt="American Express">
                                                <img src="assets/images/shopping-cart/discover.svg" class="stripe-discover-icon stripe-icon" alt="Discover">
                                                <img src="assets/images/shopping-cart/diners.svg" class="stripe-diners-icon stripe-icon" alt="Diners">
                                                <img src="assets/images/shopping-cart/jcb.svg" class="stripe-jcb-icon stripe-icon" alt="JCB">
                                            </label>

                                        <li class="payment_method">
                                            <input id="payment_method_cash" type="radio" class="input-radio" name="odemeTipi" value="cash">
                                            <label for="payment_method_cash">
                                            <?= $fonk->getDil("Havale/Eft"); ?> 
                                            </label>
                                        </li>
                                        </li>
                                    </ul>
                                   <!--<p class="checkout-payment__policy-text">
                                        Kişisel verileriniz, siparişinizi işlemek, bu web sitesindeki deneyiminizi desteklemek ve <a href="#">gizlilik politikamızda </a>açıklanan diğer amaçlar için kullanılacaktır.
                                    </p>-->
                                    <label class="checkout-payment__confirm-terms-and-conditions">
                                        <input type="checkbox" name="terms" id="terms" required>
                                        <span>
                                            <a href="#" target="_blank" class="terms-and-conditions-link"> 
                                            <label for="sart" style="display: inline;">
                                            <a href="page/mesafeli-satis-sozlesmesi" target="_blank">
                                                <b><?= $fonk->getDil("Mesafeli Satış Sözleşmesini"); ?></b>
                                            </a>
                                            <?= $fonk->getDil("ve"); ?>
                                            <a href="page/garanti-degisim-iptal-ve-iade-politikasi" target="_blank">
                                                <b><?= $fonk->getDil("iptal, iade ve değişim koşullarını"); ?></b>
                                            </a>
                                            <?= $fonk->getDil("okudum kabul ediyorum"); ?>
                                        </label>
                                    <button type="submit" class="btn_checkout button button_primary tu mt__10 mb__10 js_add_ld w__100" id="submitButton" onclick="ButtonDisabled('submitButton')"><?= $fonk->getDil("Siparişi Tamamla"); ?> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

<?php include('layouts/footer.php') ?>