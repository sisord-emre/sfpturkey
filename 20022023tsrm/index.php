<?php
include('layouts/header.php');
?>

<div id="nt_content">
    <div class="nt_section type_slideshow type_carousel ">
        <div class="slideshow-wrapper nt_full se_height_cus_h nt_first">
            <div class="fade_flick_1 slideshow row no-gutters equal_nt nt_slider js_carousel prev_next_0 btn_owl_1 dot_owl_2 dot_color_1 btn_vi_2" data-flickity='{ "fade":0,"cellAlign": "center","imagesLoaded": 0,"lazyLoad": 0,"freeScroll": 0,"wrapAround": true,"autoPlay" : 0,"pauseAutoPlayOnHover" : true, "rightToLeft": false, "prevNextButtons": false,"pageDots": true, "contain" : 1,"adaptiveHeight" : 1,"dragThreshold" : 5,"percentPosition": 1 }'>
                <?php
                $slider = $db->select("Slider", "*", [
                    "sliderDilId" => $_SESSION["dilId"],
                    "sliderDurum" => 1,
                    "ORDER" => [
                        "sliderSirasi" => "ASC"
                    ]
                ]);
                foreach ($slider as $key => $value) {
                ?>
                    <div class="col-12 slideshow__slide">
                        <div class="oh pr nt_img_txt bg-black--transparent">
                            <div class="js_full_ht4 img_slider_block kalles-slide-element__pdb-600">
                                <a href="">
                                    <div class="bg_rp_norepeat bg_sz_cover lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0" data-bgset="<?= $value['sliderBaseUrl'] . $value['sliderGorsel'] ?>"></div>
                                </a>
                            </div>
                            <div class="caption-wrap caption-w-1 pe_none z_100 tl_md tl">
                                <div class="pa_txts caption kalles-caption-layout-01 kalles-caption--midle-left">
                                    <div class="left_right">
                                        <h3 class="kalles-caption-layout-01__title mg__0 lh__1" style="color:white;">
                                            <?= $value['sliderBaslik'] ?>
                                        </h3>
                                        <a class="kalles-caption-layout-01__button kalles-button--square slt4_btn button pe_auto round_false btn_icon_false" href="<?= $value['sliderButtonLink'] ?>">
                                            <?= $value['sliderButtonYazi'] ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <a href="" class="pa t__0 l__0 b__0 r__0 pe_none"></a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- featured collection -->
    <div class="nt_section type_featured_collection tp_se_cdt">
        <div class="kalles-otp-01__feature container">
            <div class="wrap_title des_title_2">
                <h3 class="section-title tc pr flex fl_center al_center fs__24 title_2">
                    <span class="mr__10 ml__10"><?= $fonk->getDil("Kampanyalı Ürünler") ?></span>
                </h3>
            </div>
            <div class="products nt_products_holder row fl_center row_pr_1 cdt_des_5 round_cd_true nt_cover ratio_nt position_8 space_30">
                <?php 
                 $sartlar = [];
                 //toplam veri
                 $sutunlar=[
                     "urunId",
                     "urunVaryantDilBilgiUrunId",
                     "urunVaryantId",
                     "urunBaseUrl",
                     "urunGorsel",
                     "urunVaryantKodu",
                     "urunVaryantDilBilgiAdi",
                     "urunVaryantDilBilgiSlug",
                     "urunVaryantFiyat",
                     "urunVaryantDilBilgiDilId",
                     "urunVaryantVaryantId",
                     "urunVaryantDefaultSecim",
                     "urunVaryantDilBilgiDurum",
                     "urunDurum",
                 ];
             
                 $sartlar = array_merge($sartlar, [
                     'GROUP' => $sutunlar,
                     "urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
                     "urunKampanya" => 1, //kampanyaları ürünler
                     "urunVaryantDefaultSecim" => 1, //default seçili olanlar listelenecek
                     "urunVaryantDilBilgiDurum" => 1,
                     "urunDurum" => 1,
                     "ORDER" => [
                         "urunId" => "ASC"
                     ]
                 ]);
                 //normal sorgumuz
                 $urunler = $db->select("Urunler", [
                     "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
                     "[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
                     "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
                     "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
                 ], $sutunlar, $sartlar);

                foreach ($urunler as $value) {
                    if ($value["urunGorsel"] == "") {
                        $value["urunGorsel"] = "img-not-found.png";
                    }
                ?>
                <div class="col-lg-3 col-md-3 col-6 pr_animated done mt__30 pr_grid_item product nt_pr desgin__1">
                    <div class="product-inner pr">
                        <div class="product-image pr oh lazyload">
                            <a class="d-block" href="product-detail-layout-01.html">
                                <div class="pr_lazy_img main-img nt_img_ratio nt_bg_lz lazyload padding-top__127_571 " data-bgset="<?= $value["urunBaseUrl"] . "" . $value["urunGorsel"]; ?>"></div>
                            </a>
                            <div class="hover_img pa pe_none t__0 l__0 r__0 b__0 op__0">
                                <div class="pr_lazy_img back-img pa nt_bg_lz lazyload padding-top__127_571 " data-bgset="<?= $value["urunBaseUrl"] . "" . $value["urunGorsel"]; ?>"></div>
                            </div>
                        </div>
                        <div class="product-info mt__15">
                            <h3 class="product-title pr fs__14 mg__0 fwm">
                                <a class="cd chp" href="product/<?= $value["urunVaryantKodu"] . "-" . $value["urunVaryantDilBilgiSlug"]; ?>">
                                    <?= $value["urunVaryantDilBilgiAdi"]; ?>
                                </a>
                            </h3>
                            <?php if($uyeVar == 1){ ?>
                            <span class="price dib mb__5">
                                <?php $hesapla=$fonk->Hesapla($value["urunVaryantId"],"",$uye['uyeIndirimOrani']);?>
                                <?= $_SESSION["paraBirimSembol"] ?><?=$hesapla["birimFiyat"];?>
                            </span>
                            <?php } ?> 
                            <button type="submit" onclick="SepeteEkle(<?= $value['urunVaryantId']; ?>);" id="sepetButton_<?= $value["urunVaryantId"]; ?>" data-time="6000" data-ani="shake" class="single_add_to_cart_button button truncate w__100 mt__10 order-4 d-inline-block animated">
                                <span class="txt_add"><?= $fonk->getDil("Sepete Ekle"); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- end featured collection -->


     <!-- featured collection -->
     <div class="nt_section type_featured_collection tp_se_cdt">
        <div class="kalles-otp-01__feature container">
            <div class="wrap_title des_title_2">
                <h3 class="section-title tc pr flex fl_center al_center fs__24 title_2">
                    <span class="mr__10 ml__10"><?= $fonk->getDil("En Çok Satan Ürünler") ?></span>
                </h3>
            </div>
            <div class="products nt_products_holder row fl_center row_pr_1 cdt_des_5 round_cd_true nt_cover ratio_nt position_8 space_30">
                <?php 
                 $sartlar = [];
                 //toplam veri
                 $sutunlar=[
                     "urunId",
                     "urunVaryantDilBilgiUrunId",
                     "urunVaryantId",
                     "urunBaseUrl",
                     "urunGorsel",
                     "urunVaryantKodu",
                     "urunVaryantDilBilgiAdi",
                     "urunVaryantDilBilgiSlug",
                     "urunVaryantFiyat",
                     "urunVaryantDilBilgiDilId",
                     "urunVaryantVaryantId",
                     "urunVaryantDefaultSecim",
                     "urunVaryantDilBilgiDurum",
                     "urunDurum",
                 ];
             
                 $sartlar = array_merge($sartlar, [
                     'GROUP' => $sutunlar,
                     "urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
                     "urunEnCokSatan" => 1, //en cok satan ürünler
                     "urunVaryantDefaultSecim" => 1, //default seçili olanlar listelenecek
                     "urunVaryantDilBilgiDurum" => 1,
                     "urunDurum" => 1,
                     "ORDER" => [
                         "urunId" => "ASC"
                     ]
                 ]);
                 //normal sorgumuz
                 $urunler = $db->select("Urunler", [
                     "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
                     "[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
                     "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
                     "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
                 ], $sutunlar, $sartlar);

                foreach ($urunler as $value) {
                    if ($value["urunGorsel"] == "") {
                        $value["urunGorsel"] = "img-not-found.png";
                    }
                ?>
                <div class="col-lg-3 col-md-3 col-6 pr_animated done mt__30 pr_grid_item product nt_pr desgin__1">
                    <div class="product-inner pr">
                        <div class="product-image pr oh lazyload">
                            <a class="d-block" href="product-detail-layout-01.html">
                                <div class="pr_lazy_img main-img nt_img_ratio nt_bg_lz lazyload padding-top__127_571 " data-bgset="<?= $value["urunBaseUrl"] . "" . $value["urunGorsel"]; ?>"></div>
                            </a>
                            <div class="hover_img pa pe_none t__0 l__0 r__0 b__0 op__0">
                                <div class="pr_lazy_img back-img pa nt_bg_lz lazyload padding-top__127_571 " data-bgset="<?= $value["urunBaseUrl"] . "" . $value["urunGorsel"]; ?>"></div>
                            </div>
                        </div>
                        <div class="product-info mt__15">
                            <h3 class="product-title pr fs__14 mg__0 fwm">
                                <a class="cd chp" href="product/<?= $value["urunVaryantKodu"] . "-" . $value["urunVaryantDilBilgiSlug"]; ?>">
                                    <?= $value["urunVaryantDilBilgiAdi"]; ?>
                                </a>
                            </h3>
                            <?php if($uyeVar == 1){ ?>
                            <span class="price dib mb__5">
                                <?php $hesapla=$fonk->Hesapla($value["urunVaryantId"],"",$uye['uyeIndirimOrani']);?>
                                <?= $_SESSION["paraBirimSembol"] ?><?=$hesapla["birimFiyat"];?>
                            </span>
                            <?php } ?> 
                            <button type="submit" onclick="SepeteEkle(<?= $value['urunVaryantId']; ?>);" id="sepetButton_<?= $value["urunVaryantId"]; ?>" data-time="6000" data-ani="shake" class="single_add_to_cart_button button truncate w__100 mt__10 order-4 d-inline-block animated">
                                <span class="txt_add"><?= $fonk->getDil("Sepete Ekle"); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- end featured collection -->


    <?php
    $bloglar = $db->select("Bloglar", [
        "[>]BlogDilBilgiler" => ["Bloglar.blogId" => "blogDilBilgiBlogId"],
    ], "*", [
        "blogDilBilgiDilId" => $_SESSION["dilId"],
        "blogDurum" => 1,
        "blogDilBilgiDurum" => 1,
        "ORDER" => [
            "blogSirasi" => "ASC"
        ]
    ]);
    if ($bloglar) {
    ?>
        <!--blog post-->
        <div class="kalles-section kalles-section_type_featured_blog nt_section type_featured_blog type_carousel mb__50" style="background: #f6f6f8;">
            <div class="container">
                <div class="wrap_title mb__30 pt__30 des_title_2">
                    <h3 class="section-title tc pr flex fl_center al_center fs__24 title_2">
                        <span class="mr__10 ml__10"><?= $fonk->getDil("BLOG") ?> </span>
                    </h3>
                </div>
                <div class="articles art_des1 nt_products_holder row nt_cover ratio4_3 position_8 equal_nt js_carousel nt_slider prev_next_1 btn_owl_1 dot_owl_1 dot_color_1 btn_vi_1" data-flickity='{"imagesLoaded": 0,"adaptiveHeight": 1, "contain": 1, "groupCells": "100%", "dragThreshold" : 5, "cellAlign": "left","wrapAround": false,"prevNextButtons": true,"percentPosition": 1,"pageDots": false, "autoPlay" : 0, "pauseAutoPlayOnHover" : true, "rightToLeft": false }'>
                    <?php foreach ($bloglar as $key => $value) { ?>
                        <article class="post_nt_loop post_1 col-lg-4 col-md-4 col-12 pr_animated done mb__40">
                            <a class="mb__15 db pr oh" href="">
                                <div class="lazyload nt_bg_lz pr_lazy_img" data-bgset="<?= $value['blogBaseUrl'] . $value['blogGorsel'] ?>"></div>
                            </a>
                            <div class="post-info mb__10">
                                <h4 class="mg__0 fs__16 mb__5 ls__0">
                                    <a class="cd chp open" href=""><?= $value['blogDilBilgiBaslik'] ?></a>
                                </h4>
                            </div>
                            <div class="post-content">
                                <?= $value['blogDilBilgiDescription'] ?>
                            </div>
                        </article>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!--end blog post-->
    <?php } ?>

    <!-- shipping info -->
    <div class="kalles-section nt_section type_shipping kalles-section-shipping">
        <div class="container">
            <div class="row use_border_false">
                <div class="col-12 col-md-6 col-lg-3 mb__25 bl_1581530479619-0">
                    <div class="nt_shipping nt_icon_deafult tl row no-gutters al_center_">
                        <div class="col-auto icon large csi">
                            <i class="pegk pe-7s-plane"></i>
                        </div>
                        <div class="col content">
                            <h3 class="title cd fs__14 mg__0 mb__5">
                                <?= $fonk->getDil("ÜCRETSİZ KARGO") ?>
                            </h3>
                            <p class="mg__0">
                                <?= $fonk->getDil("Tüm Yurtiçi siparişleri için ücretsiz gönderim") ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb__25 bl_1581530479619-1">
                    <div class="nt_shipping nt_icon_deafult tl row no-gutters al_center_">
                        <div class="col-auto icon large csi">
                            <i class="pegk pe-7s-help2"></i>
                        </div>
                        <div class="col content">
                            <h3 class="title cd fs__14 mg__0 mb__5">
                                <?= $fonk->getDil("7/24 DESTEK") ?>
                            </h3>
                            <p class="mg__0">
                                <?= $fonk->getDil("24 saat destek veriyoruz") ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb__25 bl_1581530479619-2">
                    <div class="nt_shipping nt_icon_deafult tl row no-gutters al_center_">
                        <div class="col-auto icon large csi">
                            <i class="pegk pe-7s-refresh"></i>
                        </div>
                        <div class="col content">
                            <h3 class="title cd fs__14 mg__0 mb__5">
                                <?= $fonk->getDil("30 GÜN İADE") ?>
                            </h3>
                            <p class="mg__0">
                                <?= $fonk->getDil("Geri dönmek için 30 gününüz var") ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 mb__25 bl_1581530479619-3">
                    <div class="nt_shipping nt_icon_deafult tl row no-gutters al_center_">
                        <div class="col-auto icon large csi">
                            <i class="pegk pe-7s-door-lock"></i>
                        </div>
                        <div class="col content">
                            <h3 class="title cd fs__14 mg__0 mb__5">
                                <?= $fonk->getDil(" %100 ÖDEME GÜVENLİ") ?>
                            </h3>
                            <p class="mg__0">
                                <?= $fonk->getDil(" %100 güvenli ödeme") ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end shipping info -->
</div>

<?php include('layouts/footer.php') ?>
<script type="text/javascript">
    function SepeteEkle(urunId) 
    {
        <?php if($uyeVar == 1){ ?>
        
        SepetKayit(urunId, "0", 1);
        <?php } else {?> 
            swal("Uyarı", "Lütfen üye girişi yapınız.", "warning")
            .then((value) => {
                window.location.href = "<?= $sabitBilgiler['sabitBilgiSiteUrl']; ?>account";
            });
        <?php } ?>
    }
</script>