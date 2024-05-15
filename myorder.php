<?php
include 'layouts/header.php';

$seo = $_GET['seo'];

$siparislerim = $db->get("Siparisler", [
    "[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
    "[>]UyeAdresler" => ["Siparisler.siparisTeslimatUyeAdresId" => "uyeAdresId"],
    "[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
    "[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
    "[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
    "[>]OdemeTipleri" => ["Siparisler.siparisOdemeTipiId" => "odemeTipId"],
    "[>]Diller" => ["Siparisler.siparisDilId" => "dilId"],
    "[>]ParaBirimleri" => ["Siparisler.siparisParaBirimId" => "paraBirimId"]
], "*", [
    "siparisKodu" => $seo,
]);

$siparisIcerikleri = $db->select("SiparisIcerikleri", [
    "[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
    "[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
    "[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
], "*", [
    "urunDilBilgiDilId" => $siparislerim["siparisDilId"],
    "urunDurum" => 1,
    "urunDilBilgiDurum" => 1,
    "siparisIcerikSiparisId" => $siparislerim["siparisId"]
]);

$siparisOdenenIskontoUcreti=$siparislerim["siparisOdenenIskontoUcreti"]; //iskonto ücreti atamasını yaptık
$siparisIskontoUcreti = $siparislerim["siparisIskontoUcreti"]; //iskonto ücreti atamasını yaptık
?>

<div id="nt_content">

    <!--shop banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw"><?= $fonk->getDil("Sipariş Detayı"); ?></h1>
            </div>
        </div>
    </div>
    <!--end shop banner-->


    <!--page content-->
    <div class="container mt__40 mb__40 cb">
        <div class="row">
            <div class="col-12 col-md-3">
                <?php include 'layouts/left-menu.php'; ?>
            </div>

            <div class="col-12 col-md-9">
                <div class="order-review__wrapper">
                    <div class="checkout-order-review">
                        <table class="checkout-review-order-table">
                            <thead>
                                <tr class="text-uppercase">
                                    <th class="product-total"><b><?= $fonk->getDil("Sipariş Kodu"); ?></b></th>
                                    <th class="product-name"><b><?= $fonk->getDil("Ürün"); ?></b></th>
                                    <th class="product-total text-center"><b><?= $fonk->getDil("Adet"); ?></b></th>
                                    <th class="product-name text-center"><b><?= $fonk->getDil("KDV Dahil Birim Fİyatı"); ?></b></th>
                                    <th class="product-total text-center"><b><?= $fonk->getDil("KDV Dahil Sipariş Toplamı"); ?></b></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $toplamTutar = 0;
                                $araTutar = 0;
                                $kdvTutar = 0;
                                $siparisKargoUcreti = 0;
                                foreach ($siparisIcerikleri as $val) {
                                    $toplamTutar += ($val["siparisIcerikPanelFiyatKdvsiz"] + ($val["siparisIcerikPanelFiyatKdvsiz"] / 100 * $val["urunKdv"])) * $val['siparisIcerikAdet'];
                                    $araTutar += ($val["siparisIcerikPanelFiyatKdvsiz"]) * $val['siparisIcerikAdet'];
                                    $kdvTutar += ($val["siparisIcerikPanelFiyatKdvsiz"] / 100 * $val["urunKdv"]) * $val['siparisIcerikAdet'];
                                ?>
                                    <tr class="cart_item">
                                        <td class="product-name" style="width: 15%;"><?= $seo ?></td>
                                        <td class="product-name"><?= $val["siparisIcerikUrunVaryantDilBilgiAdi"] ?></td>
                                        <td class="product-total text-center" style="width: 10%;"><?= $val["siparisIcerikAdet"] ?></td>
                                        <td class="product-total text-center" style="width: 20%;"><span class="cart_price"><?= $siparislerim["paraBirimSembol"] . number_format($fonk->paraCevir(($val["siparisIcerikPanelFiyatKdvsiz"] + ($val["siparisIcerikPanelFiyatKdvsiz"] / 100 * $val["urunKdv"])),$val["paraBirimKodu"],"TRY"),2,',','.'); ?></span></td>
                                        <td class="product-total text-center" style="width: 25%;"><span class="cart_price"><?= $siparislerim["paraBirimSembol"] . number_format($fonk->paraCevir(($val["siparisIcerikPanelFiyatKdvsiz"] + ($val["siparisIcerikPanelFiyatKdvsiz"] / 100 * $val["urunKdv"])) * $val['siparisIcerikAdet'],$val["paraBirimKodu"],"TRY"),2,',','.'); ?></span></td>
                                    </tr>
                                <?php } ?>
                                <?php
                                if ($siparislerim['siparisKargoUcreti'] != 0) {
                                    $siparisKargoUcreti = $siparislerim['siparisKargoUcreti'];
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="cart-subtotal cart_item">
                                    <th></th>
                                    <th></th>
                                    <th colspan="2" style="text-align:right;"><?= $fonk->getDil("KDV Hariç Ara Toplam"); ?></th>
                                    <td class="text-center"><span class="cart_price"><?= $siparislerim["paraBirimSembol"] . number_format($fonk->paraCevir($araTutar,$val["paraBirimKodu"],"TRY"),2,',','.'); ?></span></td>
                                </tr>
                                <tr class="cart-subtotal cart_item">
                                    <th></th>
                                    <th></th>
                                    <th colspan="2" style="text-align:right;"><?= $fonk->getDil("Toplam KDV"); ?></th>
                                    <td class="text-center"><span class="cart_price"><?= $siparislerim["paraBirimSembol"] . number_format($fonk->paraCevir($kdvTutar,$val["paraBirimKodu"],"TRY"),2,',','.'); ?></span></td>
                                </tr>
                                <tr class="cart-subtotal cart_item">
                                    <th></th>
                                    <th></th>
                                    <th colspan="2" style="text-align:right;"><?= $fonk->getDil("Kdv Dahil Toplam"); ?></th>
                                    <td class="text-center"><span class="cart_price"><?= $siparislerim["paraBirimSembol"] . number_format($fonk->paraCevir($toplamTutar,$val["paraBirimKodu"],"TRY"),2,',','.'); ?></span></td>
                                </tr>
                                <?php if ($siparisIskontoUcreti > 0) { ?>
                                    <tr class="cart_item">
                                        <th></th>
                                        <th></th>
                                        <th colspan="2" style="text-align:right;"><?= $fonk->getDil("Proje İskonto"); ?></th>
                                        <td class="text-center"> <span class="cart_price"><?= $siparislerim["paraBirimSembol"] ?><?=number_format($siparisOdenenIskontoUcreti,2,',','.'); ?></span></td>
                                    </tr>
                                    <tr class="cart_item">
                                        <th></th>
                                        <th></th>
                                        <th colspan="2" style="text-align:right;"><?= $fonk->getDil("Kdv Dahil Proje Tutarı"); ?></th>
                                        <td class="text-center"> <span class="cart_price"><?= $siparislerim["paraBirimSembol"] ?><?= number_format($fonk->paraCevir($toplamTutar - $siparisIskontoUcreti,$val["paraBirimKodu"],"TRY"),2,',','.'); ?></span></td>
                                    </tr>
                                <?php } ?>
                                <tr class="cart-subtotal cart_item">
                                    <th></th>
                                    <th></th>
                                    <th colspan="2" style="text-align:right;"><?= $fonk->getDil("Vergiler Dahil Kargo Ucreti"); ?></th>
                                    <td class="text-center"><span class="cart_price"><?= $siparislerim["paraBirimSembol"] .number_format($siparisKargoUcreti,2,',','.'); ?></span></td>
                                </tr>
                                <tr class="order-total cart_item">
                                    <th></th>
                                    <th></th>
                                    <th colspan="2" style="text-align:right;"><?= $fonk->getDil("TOPLAM"); ?></th>
                                    <td class="text-center"><strong><span class="cart_price amount"><?= $siparislerim["paraBirimSembol"] ?><?=number_format($siparislerim["siparisToplam"],2,',','.'); ?></span></strong></td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--end cart section-->
</div>

<?php include 'layouts/footer.php'; ?>