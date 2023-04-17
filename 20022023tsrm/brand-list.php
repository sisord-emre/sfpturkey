<?php
include('Panel/System/Config.php');

$urunVaryantKodu = $_POST['urunVaryantKodu'];
$urun = $db->get("Urunler", [
    "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
    "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
    "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
    "[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
    "[>]KategoriDilBilgiler" => ["UrunKategoriler.urunKategoriKategoriId" => "kategoriDilBilgiKategoriId"],
    "[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
], "*", [
    "urunVaryantKodu" => $urunVaryantKodu,
    "urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
    "urunVaryantDilBilgiDurum" => 1,
    "ORDER" => [
        "urunId" => "ASC"
    ]
]);

if ($_SESSION['uyeKodu'] != "") 
{
    $uyeVar = 1;
    $uye = $db->get("Uyeler", "*", [
        "uyeKodu" => $_SESSION['uyeKodu']
    ]);
}
?>

<div id="nt_content">
    <div class="sp-single sp-single-1 des_pr_layout_1 mb__60">

        <!-- breadcrumb -->
        <div class="bgbl pt__20 pb__20 lh__1">
            <div class="container">
                <div class="row al_center">
                    <div class="col">
                        <nav class="sp-breadcrumb">
                            <a href="index">Home</a>
                            <i class="facl facl-angle-right"></i>
                            <a href="products/<?= $urun["kategoriDilBilgiSlug"]; ?>">
                                <?= $urun["kategoriDilBilgiBaslik"]; ?>
                            </a>
                            <i class="facl facl-angle-right"></i>
                            <?= $urun["urunVaryantDilBilgiAdi"]; ?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- end breadcrumb -->

        <div class="container container_cat cat_default">
            <div class="row product mt__40">
                <div class="col-md-12 col-12 thumb_left">
                    <div class="row mb__50 pr_sticky_content">

                        <!-- product thumbnails -->
                        <div class="col-md-5 col-12 pr product-images img_action_zoom pr_sticky_img kalles_product_thumnb_slide">
                            <div class="row theiaStickySidebar">
                                <div class="col-12 col-lg col_thumb">
                                    <div class="p-thumb p-thumb_ppr images sp-pr-gallery equal_nt nt_contain ratio_imgtrue position_8 nt_slider pr_carousel" data-flickity='{"initialIndex": ".media_id_001","fade":true,"draggable":">1","cellAlign": "center","wrapAround": true,"autoPlay": false,"prevNextButtons":true,"adaptiveHeight": true,"imagesLoaded": false, "lazyLoad": 0,"dragThreshold" : 6,"pageDots": false,"rightToLeft": false }'>
                                        <?php
                                        $urunGorselleri = $db->select("UrunGorselleri", [
                                            "[>]Urunler" => ["UrunGorselleri.urunGorselUrunId" => "urunId"],
                                        ], [
                                            "urunGorselBaseUrl",
                                            "urunGorselLink"
                                        ], [
                                            "urunDurum" => 1,
                                            "urunGorselUrunId" => $urun['urunId'],
                                            "ORDER" => [
                                                "urunGorselSirasi" => "ASC"
                                            ]
                                        ]);
                                        foreach ($urunGorselleri as $value) {
                                        ?>
                                            <div class="img_ptw p_ptw p-item sp-pr-gallery__img w__100 nt_bg_lz lazyload padding-top__127_66 media_id_001" data-mdid="001" data-height="1440" data-width="1128" data-ratio="0.7833333333333333" data-mdtype="image" data-src="<?= $value["urunGorselBaseUrl"] . "" . $value["urunGorselLink"]; ?>" data-bgset="<?= $value["urunGorselBaseUrl"] . "" . $value["urunGorselLink"]; ?>"></div>
                                        <?php } ?>
                                    </div>
                                    <div class="p_group_btns pa flex">
                                        <button class="br__40 tc flex al_center fl_center show_btn_pr_gallery ttip_nt tooltip_top_left">
                                            <i class="las la-expand-arrows-alt"></i><span class="tt_txt">Büyütmek için tıklayın</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-auto col_nav nav_medium t4_show">
                                    <div class="p-nav ratio_imgtrue row equal_nt nt_cover position_8 nt_slider pr_carousel" data-flickityjs='{"initialIndex": ".media_id_001","cellSelector": ".n-item:not(.is_varhide)","cellAlign": "left","asNavFor": ".p-thumb","wrapAround": true,"draggable": ">1","autoPlay": 0,"prevNextButtons": 0,"percentPosition": 1,"imagesLoaded": 0,"pageDots": 0,"groupCells": 3,"rightToLeft": false,"contain":  1,"freeScroll": 0}'></div>
                                    <button type="button" aria-label="Previous" class="btn_pnav_prev pe_none">
                                        <i class="las la-angle-up"></i>
                                    </button>
                                    <button type="button" aria-label="Next" class="btn_pnav_next pe_none">
                                        <i class="las la-angle-down"></i>
                                    </button>
                                </div>
                                <div class="dt_img_zoom pa t__0 r__0 dib"></div>
                            </div>
                        </div>
                        <!-- end product thumbnails -->

                        <!-- product detail -->
                        <div class="col-md-7 col-12 product-infors pr_sticky_su">
                            <div class="theiaStickySidebar">
                                <div class="kalles-section-pr_summary kalles-section summary entry-summary mt__30">
                                    <h1 class="product_title entry-title fs__16">
                                        <?= $urun["urunVaryantDilBilgiAdi"]; ?>
                                    </h1>

                                    <div class="pr_short_des">
                                        <p class="mb__40 cb">
                                            <?= $urun["urunVaryantDilBilgiAciklama"]; ?>
                                        </p>
                                    </div>

                                    <div class="btn-atc atc-slide btn_des_1 btn_txt_3">
                                        <div id="callBackVariant_ppr">

                                            <div class="nt_cart_form variations_form variations_form_ppr">
                                                <div class="variations_button in_flex column w__100 buy_qv_false">
                                                    <div class="flex wrap mb-3">
                                                        <?php if($uyeVar == 1){ ?>
                                                        <div class="quantity pr mr__10 qty__true d-inline-block" id="sp_qty_ppr">
                                                            <input type="number" class="input-text qty text tc qty_pr_js qty_cart_js" id="adet_<?= $urun["urunVaryantId"] ?>" name="quantity" value="1">
                                                            <div class="qty tc fs__14">
                                                                <button type="button" class="plus db cb pa pd__0 pr__15 tr r__0">
                                                                    <i class="facl facl-plus"></i></button>
                                                                <button type="button" class="minus db cb pa pd__0 pl__15 tl l__0">
                                                                    <i class="facl facl-minus"></i></button>
                                                            </div>
                                                        </div>

                                                        <div class="flex wrap fl_between al_center price-review">
                                                            <p class="price_range" id="price_ppr">
                                                                <?php $hesapla=$fonk->Hesapla($urun["urunVaryantId"],"",$uye['uyeIndirimOrani']);?>
                                                                <?= $_SESSION["paraBirimSembol"] ?><?=$fonk->paraCevir($hesapla["birimFiyat"],$urun["paraBirimKodu"],"TRY");?>
                                                            </p>
                                                        </div>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="flex wrap">
                                                        <button type="submit" onclick="SepeteEkle(<?= $urun['urunVaryantId']; ?>);" class="single_add_to_cart_button button truncate w__100 mt__20 order-4 d-inline-block animated mr-4">
                                                            <span class="txt_add ">Sepete Ekle</span>
                                                        </button>

                                                        <a href="#" class="single_add_to_cart_button button truncate w__100 mt__20 order-4 d-inline-block animated mr-4" style="background: #2c2ce7; border-color: #2c2ce7; color: white;">
                                                            <span class="txt_add ">DATASHEET</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <?php
                                        $markalar = $db->select("UrunVaryantlari", [
                                            "[>]Varyantlar" => ["UrunVaryantlari.urunVaryantUrunId" => "varyantId"],
                                            "[>]VaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantVaryantId" => "varyantDilBilgiVaryatId"],
                                        ], "*", [
                                            "varyantDilBilgiDilId" => $_SESSION["dilId"],
                                            "urunVaryantUrunId" => $urun["urunId"],
                                            "varyanDurum" => 1,
                                            "urunVaryantVaryantId[!]" => $urun["urunVaryantVaryantId"],
                                            "ORDER" => [
                                                "urunVaryantId" => "ASC"
                                            ]
                                        ]);
                                        foreach ($markalar as $value) {
                                        ?>
                                            <div class="col-lg-3 col-sm-12">
                                                <a type="button" onclick="brandCall(<?= $value['urunVaryantKodu'] ?>)" class="single_add_to_cart_button button truncate w__100 mt__20 order-4 d-inline-block animated mr-4" style="background: #fff; border-color: #eee; color: #19191a;">
                                                    <span class="txt_add ">
                                                        <?= $value['varyantDilBilgiBaslik'] ?>
                                                    </span>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <p class="mb__40 cb">
                                        Etiketler: <?= $urun["urunVaryantDilBilgiEtiketler"]; ?>
                                    </p>

                                </div>
                            </div>
                        </div>
                        <!-- end product detail -->

                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

    </div>
</div>