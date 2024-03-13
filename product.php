<?php include('layouts/header.php') ?>

<link rel="stylesheet" href="assets/css/single-masonry-theme.css">
<link rel="stylesheet" href="assets/css/single-product.css">
<?php 

$kategoriler="";
$kategoriList = $db->select("UrunKategoriler",[
    "[>]Kategoriler" => ["UrunKategoriler.urunKategoriKategoriId" => "kategoriId"],
    "[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
],"*",[
    "kategoriDilBilgiDilId" => $_SESSION["dilId"],
    "urunKategoriUrunId" => $urun["urunId"],
    "ORDER" => [
        "kategoriId" => "ASC"
    ]
]);
foreach ($kategoriList as $key => $value) {
    $kategoriler.="<a href='products/".$value['kategoriKodu']."-".$value['kategoriDilBilgiSlug']."'>".$value["kategoriDilBilgiBaslik"]."</a><i class='facl facl-angle-right'></i>";
}

$favoriDurum = $db->get("UrunFavoriler",[
    "[<]UrunVaryantlari" => ["UrunFavoriler.urunFavoriUrunVaryantId" => "urunVaryantId"],
    "[<]Uyeler" => ["UrunFavoriler.urunFavoriUyeId" => "uyeId"]
],"*",[
    "uyeId" => $uye["uyeId"],
    "urunFavoriUrunVaryantId" => $urun["urunVaryantId"],
    "ORDER" => [
        "urunFavoriId" => "DESC",
    ]
]);


?>
<div id="brandList">
    <div id="nt_content">
        <div class="sp-single sp-single-1 des_pr_layout_1 mb__60">

            <!-- breadcrumb -->
            <div class="bgbl pt__20 pb__20 lh__1">
                <div class="container">
                    <div class="row al_center">
                        <div class="col">
                            <nav class="sp-breadcrumb">
                                <a href="index"><?= $fonk->getDil("Anasayfa"); ?></a>
                                <i class="facl facl-angle-right"></i>
                                <?=$kategoriler?>
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
                                        <div class="p-thumb p-thumb_ppr images sp-pr-gallery equal_nt nt_contain ratio_imgtrue position_8 nt_slider pr_carousel" data-flickity='{"initialIndex": ".media_id_001","fade":true,"draggable":">1","cellAlign": "center","wrapAround": true,"autoPlay": 5000,"prevNextButtons":true,"adaptiveHeight": true,"imagesLoaded": false, "lazyLoad": 0,"dragThreshold" : 6,"pageDots": false,"rightToLeft": false }'>
                                            <?php
                                            $urunGorselleri = $db->select("UrunGorselleri", [
                                                "[>]Urunler" => ["UrunGorselleri.urunGorselUrunId" => "urunId"],
                                            ], "*", [
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
                                                <i class="las la-expand-arrows-alt"></i><span class="tt_txt"><?= $fonk->getDil("Büyütmek için tıklayın"); ?></span>
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

                                        <?= $urun["urunModel"]; ?>

                                        <div class="pr_short_des">
                                            <p class="mb__40 cb">
                                                <?= $urun["urunDilBilgiAciklama"]; ?>
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
                                                                <?php if($uye['uyeIndirimOrani'] > 0 ): ?>
                                                                    <?php if($urun['urunVaryantKampanyasizFiyat'] != 0 ): ?>
                                                                    <span class="button-liste mr-3">
                                                                        <ins style="color:white;"> 
                                                                        <?php $hesapla=$fonk->Hesapla($urun["urunVaryantId"],"");?>
                                                                            <?= $urun["paraBirimSembol"] ?><?=number_format($hesapla["birimFiyat"],2,',','.');?>
                                                                        </ins>
                                                                    </span>
                                                                    <?php else: ?>
                                                                    <span class="button-liste mr-3">
                                                                        <?= $fonk->getDil("Ürün Satış Fiyat"); ?>:
                                                                        <del style="color:white;"> 
                                                                            <?= $urun["paraBirimSembol"] ?><?=number_format($urun["urunVaryantFiyat"],2,',','.');?>
                                                                        </del>
                                                                    </span>
                                                                    <br>
                                                                    <span class="button-bayi">
                                                                        <?= $fonk->getDil("Bayi Fiyatı"); ?>:
                                                                        <ins style="color:white;"> 
                                                                            <?php $hesapla2=$fonk->Hesapla($urun["urunVaryantId"],"",$uye['uyeIndirimOrani']);?>
                                                                            <?= $urun["paraBirimSembol"] ?><?=number_format($hesapla2["birimFiyat"],2,',','.');?>
                                                                        </ins>
                                                                    </span>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <span class="button-liste mr-3">
                                                                        <ins style="color:white;"> 
                                                                        <?php $hesapla=$fonk->Hesapla($urun["urunVaryantId"],"");?>
                                                                            <?= $urun["paraBirimSembol"] ?><?=number_format($hesapla["birimFiyat"],2,',','.');?>
                                                                        </ins>
                                                                    </span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <?php } ?>
                                                        </div>

                                                        <div class="flex wrap">
                                                            <?php if($urun["urunStok"] > 0){ ?>
                                                                <button type="submit" onclick="SepeteEkle(<?= $urun['urunVaryantId']; ?>);" class="single_add_to_cart_button button truncate w__100 mt__20 order-4 d-inline-block animated mr-4">
                                                                    <span class="txt_add "> <?= $fonk->getDil("Sepete Ekle"); ?></span>
                                                                </button>
                                                            <?php } else { ?>
                                                                <button href="contact" onClick="javascript:window.location.href = 'contact';" class="single_add_to_cart_button button truncate w__100 mt__20 order-4 d-inline-block animated mr-4">
                                                                    <span class="txt_add"><?= $fonk->getDil("Talep Et"); ?></span>
                                                                </button>
                                                            <?php } ?>
                                                            
                                                            <button onClick="javascript:window.open('<?= $urun['urunDataSheetBaseUrl'] ?><?= $urun['urunDataSheet'] ?>', '_blank');" class="single_add_to_cart_button button truncate w__100 mt__20 order-4 d-inline-block animated mr-4">
                                                                <span class="txt_add "> <?= $fonk->getDil("DATASHEET"); ?></span>
                                                            </button>

                                                            <div class="nt_add_w ts__03 pa order-3 <?=($favoriDurum) ? 'favori_added' : ''; ?>">
                                                                <a onclick="FavoriEkle(<?= $urun['urunVaryantId']; ?>,<?= $uye['uyeId']; ?>);" id="favoriButton_<?= $urun["urunVaryantId"]; ?>" class="wishlistadd cb chp ttip_nt tooltip_top_left">
                                                                    <span class="tt_txt"><?= $fonk->getDil("Favori Ekle"); ?></span>
                                                                    <i class="facl facl-heart-o"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <?= $fonk->getDil("Stok Durumu"); ?>: <?=($urun['urunStok'] > 0) ? 'Stokta var' : 'Stokta yok'; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>        

                                        <div class="row">
                                            <?php
                                            
                                            $markalar = $db->query(
                                                'SELECT "varyantDilBilgiBaslik",
                                                "urunVaryantDilBilgiId",
                                                "urunVaryantKodu",
                                                "urunVaryantDilBilgiSlug",
                                                "urunVaryantUrunId",
                                                "varyanDurum",
                                                "urunVaryantId",
                                                "urunVaryantDilBilgiVaryantId",
                                                "urunVaryantVaryantId",
                                                "varyantId",
                                                "varyantDilBilgiVaryatId",
                                                "urunVaryantDilBilgiDilId"
                                                FROM "UrunVaryantlari" 
                                                LEFT JOIN "UrunVaryantDilBilgiler" ON "UrunVaryantlari"."urunVaryantId" = "UrunVaryantDilBilgiler"."urunVaryantDilBilgiVaryantId" 
                                                LEFT JOIN "Varyantlar" ON "UrunVaryantlari"."urunVaryantVaryantId" = "Varyantlar"."varyantId" 
                                                LEFT JOIN "VaryantDilBilgiler" ON "UrunVaryantlari"."urunVaryantVaryantId" = "VaryantDilBilgiler"."varyantDilBilgiVaryatId" 
                                                WHERE "urunVaryantDilBilgiDilId" = '.$_SESSION["dilId"].' AND "urunVaryantUrunId" = '.$urun["urunId"].' AND "varyantDilBilgiDilId" = 1 AND "varyanDurum" = true 
                                                ORDER BY "varyantDilBilgiBaslik" ASC'
                                            )->fetchAll();
                                         
                                            foreach ($markalar as $value) {
                                                if($value['urunVaryantKodu'] == $urun["urunVaryantKodu"])
                                                {
                                                    $css = 'style="background: #f1734c; border-color: #f1734c; color: #ffffff;"';
                                                }
                                                else {
                                                    $css = 'style="background: #fff; border-color: #eee; color: #19191a;"';
                                                }
                                            ?>
                                                <div class="col-lg-3 col-4">
                                                    <a href="/product/<?= $value['urunVaryantKodu'].'-'.$value['urunVaryantDilBilgiSlug'] ?>" class="button truncate w__100 mt__20 order-4 d-inline-block animated mr-4" <?=$css?>>
                                                        <span class="txt_add ">
                                                            <?= $value['varyantDilBilgiBaslik'] ?>
                                                        </span>
                                                    </a>
                                                </div>
                                            <?php }  ?>
                                        </div>

                                        <p class="mb__40 cb">
                                        <?= $fonk->getDil("Etiketler"); ?>: <?= $urun["urunVaryantDilBilgiEtiketler"]; ?>
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

</div>
<?php if($seo){ //sadece bu alanda çalışacak ?>
<script src="assets/js/drift.min.js"></script>
<script src="assets/js/photoswipe.min.js"></script>
<script src="assets/js/photoswipe-ui-default.min.js"></script>
<script src="assets/js/drift.min.js"></script>
<script src="assets/js/isotope.pkgd.min.js"></script>
<script src="assets/js/resize-sensor.min.js"></script>
<?php } ?>

<?php include('layouts/footer.php') ?>

<?php if($seo){ //sadece bu alanda çalışacak ?>
<script src="assets/js/theia-sticky-sidebar.min.js"></script>
<?php } ?>

<script type="text/javascript">
    function SepeteEkle(urunId) 
    {
        <?php if($uyeVar == 1){ ?>
        var adet = document.getElementById("adet_" + urunId).value;
        SepetKayit(urunId, "0", adet);
        <?php } else {?> 
            swal("Uyarı", "Lütfen üye girişi yapınız.", "warning")
            .then((value) => {
                window.location.href = "<?= $sabitBilgiler['sabitBilgiSiteUrl']; ?>account";
            });
        <?php } ?>
    }

    function brandCall(urunVaryantKodu) 
    {
        $('#brandList').html('<img src="Panel/Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
        $.ajax({
            type: "POST",
            url: "brand-list.php",
            data: {
                "urunVaryantKodu": urunVaryantKodu
            },
            success: function(gelenSayfa) {
                $('#brandList').html(gelenSayfa);
            }
        });
    }

    function FavoriEkle(urunId,uyeId) 
    {
        if (uyeId != "" && uyeId != undefined) {
            FavoriKayit(urunId,uyeId);
        }
        else {
            swal("Uyarı", "Lütfen üye girişi yapınız.", "warning")
            .then((value) => {
                window.location.href = "<?= $sabitBilgiler['sabitBilgiSiteUrl']; ?>account";
            });
        }
    }
</script>