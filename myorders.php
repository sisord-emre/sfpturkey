<?php
include 'layouts/header.php';

$siparislerim = $db->select("Siparisler", [
    "[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
    "[>]UyeAdresler" => ["Siparisler.siparisTeslimatUyeAdresId" => "uyeAdresId"],
    "[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
    "[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
    "[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
    "[>]OdemeTipleri" => ["Siparisler.siparisOdemeTipiId" => "odemeTipId"],
    "[>]Diller" => ["Siparisler.siparisDilId" => "dilId"],
    "[>]ParaBirimleri" => ["Siparisler.siparisParaBirimId" => "paraBirimId"]
], "*", [
    "siparisUyeId" => $uye['uyeId'],
    "siparisOdemeBilgileri[!]" => "",
    "ORDER" => [
		"siparisKayitTarihi" => "DESC"
	]
]);

if ($_SESSION['uyeSessionKey'] == "") {
    echo '<script> window.location.href="'.$sabitB['sabitBilgiSiteUrl'].'account";</script>';
    exit;
}
?>

<div id="nt_content">

    <!--shop banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw">  <?= $fonk->getDil("Siparişlerim"); ?></h1>
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
                                    <th class="product-name"><b><?= $fonk->getDil("Sİparİş Kodu"); ?></b></th>
                                    <th class="product-total"><b><?= $fonk->getDil("Toplam Tutar"); ?></b></th>
                                    <th class="product-name"><b><?= $fonk->getDil("Sİparİş Tarİhİ"); ?></b></th>
                                    <th class="product-total"><b><?= $fonk->getDil("Durum"); ?></b></th>
                                    <th class="product-name" style="width:100px;"><b><?= $fonk->getDil("Detay"); ?></b></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($siparislerim as $list) {
                                    $siparisDurum = $db->get("SiparisSiparisDurumlari", [
                                        "[<]SiparisDurumlari" => ["SiparisSiparisDurumlari.siparisSiparisDurumSiparisDurumId" => "siparisDurumId"],
                                        "[<]SiparisDurumDilBilgiler" => ["SiparisDurumlari.siparisDurumId" => "siparisDurumDilBilgiSiparisDurumId"],
                                        "[>]KargoFirmalari" => ["SiparisSiparisDurumlari.siparisSiparisDurumKargoFirmaId" => "kargoFirmaId"]
                                    ], "*", [
                                        "siparisSiparisDurumSiparisId" => $list["siparisId"],
                                        "siparisDurumDilBilgiDilId" => $list["siparisDilId"],
                                        "ORDER" => [
                                            "siparisSiparisDurumId" => "DESC",
                                        ]
                                    ]);

                                    $siparisIcerikleri = $db->select("SiparisIcerikleri", [
                                        "[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
                                        "[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
                                    ], "*", [
                                        "urunDilBilgiDilId" => $list["siparisDilId"],
                                        "urunDurum" => 1,
                                        "urunDilBilgiDurum" => 1,
                                        "siparisIcerikSiparisId" => $list["siparisId"]
                                    ]);
                                    $toplamTutar = 0;
                                    foreach ($siparisIcerikleri as $siparisIcerik) {
                                        $toplamTutar += $siparisIcerik['siparisIcerikAdet'] * $siparisIcerik['siparisIcerikFiyat'];
                                    }
                                    if ($list['siparisIndirimKodu'] != "" && $list['siparisIndirimYuzdesi'] != 0) {
                                        $toplamTutar -= ($toplamTutar / 100 * $list['siparisIndirimYuzdesi']);
                                    }
                                    if ($list['siparisKargoUcreti'] != 0) {
                                        $toplamTutar += $list['siparisKargoUcreti'] + $list['siparisKargoKdvUcreti'];
                                    }
                                    if($list["siparisOdenenIskontoUcreti"] > 0){
                                        $toplamTutar-=$list["siparisOdenenIskontoUcreti"];
                                    }
                                ?>
                                    <tr class="cart_item">
                                        <td class="product-name"><?= $list['siparisKodu'] ?></td>
                                        <td class="product-total"><span class="cart_price"><?= $list["paraBirimSembol"] . number_format($toplamTutar,2,',','.'); ?></span></td>
                                        <td class="product-name"><?= $fonk->sqlToDateTime($list['siparisKayitTarihi']); ?></td>
                                        <td class="product-total"><?= $siparisDurum['siparisDurumDilBilgiBaslik']; ?></td>
                                        <td class="product-total" style="display:flex;">
                                            <a type="button" href="myorder/<?= $list['siparisKodu'] ?>" class="button button_primary btn-sm w-100 mr-2 d-flex align-items-center"><?= $fonk->getDil("Detay"); ?></a>
                                            <a type="button" href="<?= $list['siparisFaturaBaseUrl'] ?><?= $list['siparisFatura'] ?>" target="_blank" class="button btn-success btn-sm w-100 d-flex align-items-center"><?= $fonk->getDil("Fatura Görüntüle"); ?></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--end cart section-->
</div>

<?php include 'layouts/footer.php'; ?>