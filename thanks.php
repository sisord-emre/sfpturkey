<?php
include 'layouts/header.php';
unset($_SESSION["SiparisKodu"]);
unset($_SESSION["Sepet"]);
?>
<div id="nt_content">
    <!--hero banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="tu mb__5 cw">
                    <?= $fonk->getDil("Sipariş/Ödeme Bilgileri"); ?>
                </h1>
            </div>
        </div>
    </div>
    <!--end hero banner-->

    <!--page content-->
    <div class="kalles-section container mt__20 mb__60">
        <div class="row fl_center cb">
            <div class="col-12 mt__35 text-center">
                <?php if ($_GET["e"] != "") { ?>
                    <?php
                        //eğer hata olursa tekrar sepete ürünleri ekle
                        $siparis = $db->get("Siparisler", [
                            "[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
                        ], "*", [
                            "siparisKodu" => $_GET["sk"]
                        ]);    

                        $sonSiparisIcerikleri = $db->select("SiparisIcerikleri", [
                            "[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
                            "[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
                        ], "*", [
                            "urunDilBilgiDilId" => $_SESSION["dilId"],
                            "urunDurum" => 1,
                            "urunDilBilgiDurum" => 1,
                            "siparisIcerikSiparisId" => $siparis['siparisId']
                        ]);

                        if (count($sonSiparisIcerikleri) > 0) {
                            foreach ($sonSiparisIcerikleri as $key => $value) {
                                echo '<script> SepetKayit('.$value["urunVaryantId"].', "0", 1); </script>';
                            }
                        }
                    ?>
                    <h2>
                        <?= $fonk->getDil("Hata Oluştu") ?>
                    </h2>

                    <p>
                        <?= $fonk->getDil("Hata Mesajı: ") ?> <?= $_GET["e"] ?>
                    </p>

                    <p>
                        <a href="cart"><?= $fonk->getDil("Alışverişe Devam Et/Tekrar Deneyiniz") ?></a>
                    </p>
                <?php } ?>
                <?php if ($_GET["s"] != "") { ?>
                    <h2>
                        <?= $fonk->getDil("İşlem Başarılı") ?>
                    </h2>

                    <p>
                        <?= $fonk->getDil("Sipariş Kodu: ") ?> <?= $_GET["s"] ?>
                    </p>

                    <p>
                        ÖDEMENİZ BAŞARIYLA GERÇEKLEŞMİŞTİR,SİPARİŞİNİZ İŞLEME ALINMIŞTIR.
                    </p>

                    <p>
                        <a href="myorders"><?= $fonk->getDil("Siparişinizin durumunu Profil>Siparişlerim Bölümünden takip edebilirsiniz..") ?></a>
                    </p>

                    <p>
                        <a href="/"><?= $fonk->getDil("Alışverişe Devam Et/Ana Sayfa") ?></a>
                    </p>
                <?php } ?>
            </div>
        </div>
    </div>
    <!--end page content-->
</div>
<?php include 'layouts/footer.php'; ?>