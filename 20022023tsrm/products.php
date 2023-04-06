<?php include('layouts/header.php') ?>

<div id="nt_content">

    <!--shop banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw">
                    <?= $fonk->getDil("Ürünler") ?>
                </h1>
            </div>
        </div>
    </div>
    <!--end shop banner-->

    <div class="container container_cat pop_default cat_default mb__20">

        <!--grid control-->
        <div class="cat_toolbar row fl_center al_center mt__30">

            <div class="cat_sortby cat_sortby_js col tr kalles_dropdown kalles_dropdown_container">
                <a class="in_flex fl_between al_center sortby_pick kalles_dropDown_label" href="#">
                    <span class="lbl-title sr_txt dn"><?= $fonk->getDil("Filtre") ?></span>
                    <span class="lbl-title sr_txt_mb"><?= $fonk->getDil("Filtre") ?></span>
                    <i class="ml__5 mr__5 facl facl-angle-down"></i>
                </a>
                <div class="nt_sortby dn">
                    <svg class="ic_triangle_svg" viewBox="0 0 20 9" role="presentation">
                        <path d="M.47108938 9c.2694725-.26871321.57077721-.56867841.90388257-.89986354C3.12384116 6.36134886 5.74788116 3.76338565 9.2467995.30653888c.4145057-.4095171 1.0844277-.40860098 1.4977971.00205122L19.4935156 9H.47108938z" fill="#ffffff"></path>
                    </svg>
                    <div class="h3 mg__0 tc cd tu ls__2 dn_lg db"><?= $fonk->getDil("Filtre") ?><i class="pegk pe-7s-close fs__50 ml__5"></i>
                    </div>
                    <div class="nt_ajaxsortby wrap_sortby kalles_dropdown_options">
                        <a data-label="<?= $fonk->getDil("Fiyat, düşükten yükseğe") ?>" class="kalles_dropdown_option truncate selected" href="#">
                            <?= $fonk->getDil("Fiyat, düşükten yükseğe") ?>
                        </a>
                        <a data-label="<?= $fonk->getDil("Fiyat, yüksekten alçağa") ?>" class="kalles_dropdown_option truncate" href="#">
                            <?= $fonk->getDil("Fiyat, yüksekten alçağa") ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!--end grid control-->


        <!--product container-->
        <div class="row">
            <!--left sidebar-->
            <div class="js_sidebar sidebar sidebar_nt col-lg-2 col-12 space_30 hidden_false lazyload">
                <div id="kalles-section-sidebar_shop" class="kalles-section nt_ajaxFilter section_sidebar_shop type_instagram">
                    <div class="h3 mg__0 tu bgb cw visible-sm fs__16 pr"><?= $fonk->getDil("Kategoriler") ?> <i class="close_pp pegk pe-7s-close fs__40 ml__5"></i>
                    </div>
                    <div class="cat_shop_wrap">
                        <div class="cat_fixcl-scroll">
                            <div class="cat_fixcl-scroll-content css_ntbar">
                                <div class="row no-gutters wrap_filter">

                                    <div class="col-12 col-md-12 widget widget_product_categories cat_count_false">
                                        <h5 class="widget-title"><?= $fonk->getDil("Kategoriler"); ?></h5>
                                        <ul class="product-categories">
                                            <?php
                                            $kategoriler = $db->select("Kategoriler", [
                                                "[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
                                            ], "*", [
                                                "kategoriDilBilgiDilId" => $_SESSION["dilId"],
                                                "kategoriUstMenuId" => 0,
                                                "kategoriDurum" => 1,
                                                "ORDER" => [
                                                    "kategoriSirasi" => "ASC"
                                                ]
                                            ]);
                                            foreach ($kategoriler as $key => $value) {
                                            ?>
                                                <li class="cat-item">
                                                    <a href="products/<?= $value["kategoriDilBilgiSlug"]; ?>" class="text-uppercase">
                                                        <?= $value["kategoriDilBilgiBaslik"]; ?>
                                                    </a>
                                                    <?php
                                                    $altkategoriler = $db->select("Kategoriler", [
                                                        "[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
                                                    ], "*", [
                                                        "kategoriDilBilgiDilId" => $_SESSION["dilId"],
                                                        "kategoriUstMenuId" => $value["kategoriId"],
                                                        "kategoriDurum" => 1,
                                                        "ORDER" => [
                                                            "kategoriSirasi" => "ASC"
                                                        ]
                                                    ]);
                                                    if (count($altkategoriler) > 0) {
                                                    ?>
                                                        <ul class="product-categories">
                                                            <?php foreach ($altkategoriler as $key => $valueAlt) { ?>
                                                                <li class="cat-item altKategori">
                                                                    <a href="products/<?= $valueAlt["kategoriDilBilgiSlug"]; ?>" class="text-uppercase">
                                                                        <?= $valueAlt["kategoriDilBilgiBaslik"]; ?>
                                                                    </a>
                                                                    <?php
                                                                    $altkategoriler2 = $db->select("Kategoriler", [
                                                                        "[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
                                                                    ], "*", [
                                                                        "kategoriDilBilgiDilId" => $_SESSION["dilId"],
                                                                        "kategoriUstMenuId" => $valueAlt["kategoriId"],
                                                                        "kategoriDurum" => 1,
                                                                        "ORDER" => [
                                                                            "kategoriSirasi" => "ASC"
                                                                        ]
                                                                    ]);
                                                                    if (count($altkategoriler2) > 0) {
                                                                    ?>
                                                                        <ul class="product-categories">
                                                                            <?php foreach ($altkategoriler2 as $key => $valueAlt2) { ?>
                                                                                <li class="cat-item altKategori2">
                                                                                    <a href="products/<?= $valueAlt2["kategoriDilBilgiSlug"]; ?>" class="text-uppercase">
                                                                                        <?= $valueAlt2["kategoriDilBilgiBaslik"]; ?>
                                                                                    </a>
                                                                                </li>
                                                                            <?php } ?>
                                                                        </ul>
                                                                    <?php } ?>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    <?php } ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end left sidebar-->

            <!--main content-->
            <div class="col-lg-10 col-12">
                <div class="kalles-section tp_se_cdt">
                    <label>Showing 1–8 of <span id="toplamKayit"></span> <?= $fonk->getDil("sonuçlar"); ?></label>
                    <!--products list-->
                    <div class="on_list_view_false products nt_products_holder row fl_center row_pr_1 cdt_des_1 round_cd_false nt_cover ratio_nt position_8 space_30 nt_default" id="urunlerList">
                        <!-- ürünlerin listelemesi -->
                    </div>
                    <!--end products list-->
                    <img src="Panel/Images/loading.gif" id="loadingItem" style="position:relative;left:50%;margin-top:10%;width:64px">
                    <div id="altBolum"></div><!-- margin -->
                </div>
            </div>
            <!--end main content-->

        </div>
        <!--end product container-->
    </div>
</div>


<script>
    var sayac = 1;
    var loadingDurum = true;
    window.onload = (event) => {
        urunlerList(sayac, "<?= $_GET['seo'] ?>");
        const target = document.getElementById('altBolum');
        const callback = (entries) => {
            if (entries[0].isIntersecting) {
                if (loadingDurum) {
                    urunlerList(sayac, "<?= $_GET['seo'] ?>, <?=$uyeVar?>");
                }
            }
        };
        const observer = new IntersectionObserver(callback);
        observer.observe(target);
    };

    function urunlerList(page, seo) {
        loadingDurum = false;
        document.getElementById("loadingItem").style.display = "block";
        $.ajax({
            type: "GET",
            url: "ajax/urunlerList.php",
            data: {
                'page': page,
                'seo': seo
            },
            success: function(gelenSayfa) {
                document.getElementById("loadingItem").style.display = "none";
                $('#urunlerList').append(gelenSayfa);
                sayac++;
                loadingDurum = true;
            }
        });
    }
</script>
<?php include('layouts/footer.php') ?>
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
</script>