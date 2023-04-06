<?php
include 'layouts/header.php';
if (!$_SESSION['uyeKodu']) {
    echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . '?link=checkout"; </script>';
    exit;
} 
else {
    $uye = $db->get("Uyeler", "*", [
        "uyeDurum" => 1,
        "uyeKodu" => $_SESSION['uyeKodu']
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
$_SESSION['SiparisKodu'] = "";
?>

<div id="nt_content">

    <!--shop banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw">Ödeme İşlemi</h1>
            </div>
        </div>
    </div>
    <!--end shop banner-->

    <!--cart section-->
    <form action="odeme" method="post">
        <div class="kalles-section cart_page_section container mt__60">
            <div class="frm_cart_page check-out_calculator">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-7">
                        <div class="checkout-section">
                            <h3 class="checkout-section__title">Fatura Detayları</h3>
                            <div class="row">
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeAdi">İsim</label>
                                    <input type="text" id="uyeAdi" name="uyeAdi" value="<?= $uye["uyeAdi"] ?>" placeholder="İsim" required>
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeSoyadi">Soyisim</label>
                                    <input type="text" id="uyeSoyadi" name="uyeSoyadi" value="<?= $uye["uyeSoyadi"] ?>" placeholder="Soyisim" required>
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeTel">Telefon</label>
                                    <input type="tel" id="uyeTel" name="uyeTel" value="<?= $uye["uyeTel"] ?>" placeholder="Telefon" required />
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeMail">Email</label>
                                    <input type="email" id="uyeMail" name="uyeMail" value="<?= $uye["uyeMail"] ?>" placeholder="Email" required />
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="siparisTeslimatTarihi">Teslim tarihi</label>
                                    <input type="date" min="<?= date('Y-m-d', strtotime(date("Y-m-d") . ' + 1 days')); ?>" value="<?= date('Y-m-d', strtotime(date("Y-m-d") . ' + 1 days')); ?>" id="siparisTeslimatTarihi" name="siparisTeslimatTarihi" required />
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">

                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeTeslimatAdresId">Teslimat adresi
                                        <span style="float: right">
                                            <a href="javascript:AdresModal('','<?= $sabitB['sabitBilgiSiteUrl'] ?>checkout');" style="color: #e45050;">Yeni ekle</a>
                                        </span>
                                    </label>
                                    <select id="uyeTeslimatAdresId" name="uyeTeslimatAdresId" onchange="AdresBilgi('uyeTeslimatAdresId','teslimat_adres');" required>
                                        <option value="">Seçiniz</option>
                                        <?php
                                        $uyeAdresler = $db->select("UyeAdresler", [
                                            "[<]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
                                            "[<]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
                                            "[<]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"]
                                        ], "*", [
                                            "uyeAdresUyeId" => $uye["uyeId"],
                                        ]);
                                        foreach ($uyeAdresler as $key => $value) {
                                        ?>
                                            <option value="<?= $value["uyeAdresId"] ?>"><?= $value["uyeAdresAdi"] ?></option>
                                        <?php } ?>
                                    </select>
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeFaturaAdresId">Fatura Adresi
                                        <span style="float: right">
                                            <a href="javascript:AdresModal('','<?= $sabitB['sabitBilgiSiteUrl'] ?>checkout');" style="color: #e45050;">Yeni ekle</a>
                                        </span>
                                    </label>
                                    <select id="uyeFaturaAdresId" name="uyeFaturaAdresId" onchange="AdresBilgi('uyeFaturaAdresId','fatura_adres');" required>
                                        <option value="">Seçiniz</option>
                                        <?php
                                        $uyeAdresler = $db->select("UyeAdresler", [
                                            "[<]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
                                            "[<]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
                                            "[<]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"]
                                        ], "*", [
                                            "uyeAdresUyeId" => $uye["uyeId"],
                                        ]);
                                        foreach ($uyeAdresler as $key => $value) {
                                        ?>
                                            <option value="<?= $value["uyeAdresId"] ?>"><?= $value["uyeAdresAdi"] ?></option>
                                        <?php } ?>
                                    </select>
                                </p>

                                <div class="checkout-section__field col-lg-6 col-12 order-review__wrapper" id="teslimat_adres">
                                </div>

                                <div class="checkout-section__field col-lg-6 col-12 order-review__wrapper" id="fatura_adres">
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-5 mt__50 mb__80">
                        <div class="order-review__wrapper">
                            <h3 class="order-review__title">Siparişiniz</h3>
                            <div class="checkout-order-review">
                                <table class="checkout-review-order-table">
                                    <thead>
                                        <tr>
                                            <th class="product-name">Ürün</th>
                                            <th class="product-total">Fiyat</th>
                                            <th class="product-total">KDV</th>
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
                                            ], "*", [
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
                                                <td class="product-name"><?= $urun["urunDilBilgiAdi"]; ?>
                                                    <strong class="product-quantity">× <?= $sepet[$i]["adet"] ?></strong>
                                                </td>
                                                <td class="product-total">
                                                    <span class="cart_price">
                                                        <?= $_SESSION["paraBirimSembol"] . number_format($hesapla["birimFiyat"], 2, ",", "."); ?>
                                                    </span>
                                                </td>
                                                <td class="product-total">
                                                    <span class="cart_price">
                                                        <?= $_SESSION["paraBirimSembol"] . number_format($hesapla["birimFiyat"] / 100 * $urun["urunKdv"], 2, ",", "."); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="order-total cart_item">
                                            
                                            <td class="product-total">
                                            </td>
                                            <th>Ara toplam</th>
                                            <td colspan="2">
                                                <strong>
                                                    <span class="cart_price amount">
                                                        <?= $_SESSION["paraBirimSembol"] . number_format($araTutar, 2, ",", "."); ?>
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr class="order-total cart_item">
                                        
                                            <td class="product-total">
                                            </td>
                                            <th>Toplam KDV</th>
                                            <td colspan="2">
                                                <strong>
                                                    <span class="cart_price amount">
                                                        <?= $_SESSION["paraBirimSembol"] . number_format($kdvTutar, 2, ",", "."); ?>
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr class="order-total cart_item">
                                        
                                            <td class="product-total">
                                            </td>
                                            <th>Toplam</th>
                                            <td>
                                                <strong>
                                                    <span class="cart_price amount">
                                                        <?= $_SESSION["paraBirimSembol"] . number_format($toplamTutar, 2, ",", "."); ?>
                                                    </span>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr class="order-total cart_item">
                                            <th>Not:</th>
                                            <td>
                                                <?= $note; ?>
                                                <input type="hidden" name="note" value="<?= $note; ?>" />
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="checkout-payment">
                                    <ul class="payment_methods">
                                        <li class="payment_method">
                                            <input id="payment_method_stripe" type="radio" class="input-radio" name="odemeTipi" value="stripe" checked="checked">
                                            <label for="payment_method_stripe">
                                                Kredi kartı
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
                                                Havale/Eft
                                            </label>
                                        </li>
                                        </li>
                                    </ul>
                                    <p class="checkout-payment__policy-text">
                                        Kişisel verileriniz, siparişinizi işlemek, bu web sitesindeki deneyiminizi desteklemek ve <a href="#">gizlilik politikamızda </a>açıklanan diğer amaçlar için kullanılacaktır.
                                    </p>
                                    <label class="checkout-payment__confirm-terms-and-conditions">
                                        <input type="checkbox" name="terms" id="terms" required>
                                        <span> Web sitesi <a href="#" class="terms-and-conditions-link">hüküm ve koşullarını</a></span> okudum ve kabul ediyorum &nbsp;<span class="required">*</span>
                                    </label>
                                    <button type="submit" class="btn_checkout button button_primary tu mt__10 mb__10 js_add_ld w__100" id="submitButton" onclick="ButtonDisabled('submitButton')">Siparişi Tamamla</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <!--end cart section-->

</div>

<?php include('layouts/footer.php') ?>
<script type="text/javascript">
    const picker = document.getElementById('siparisTeslimatTarihi');
    picker.addEventListener('input', function(e) {
        var day = new Date(this.value).getUTCDay();
        if ([6, 0].includes(day)) {
            e.preventDefault();
            this.value = '';
            alert(getDil("Hafta sonları işlem yapılamaz."));
        }
    });
</script>