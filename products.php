<?php
include('layouts/header.php');
$seo = intval(explode('-', $_GET['seo'])[0]);

$kategoriBul = $db->get("Kategoriler", [
    "[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
], "*", [
    "kategoriDilBilgiDilId" => $_SESSION["dilId"],
    "kategoriKodu" => $seo,
    "ORDER" => [
        "kategoriId" => "ASC"
    ]
]);
$kategoriList = $db->select("Kategoriler", [
    "[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
], "*", [
    "kategoriDilBilgiDilId" => $_SESSION["dilId"],
    "ORDER" => [
        "kategoriId" => "ASC"
    ]
]);

$result = [];
$fonk->findCategoryById($kategoriList, $kategoriBul["kategoriUstMenuId"], $result);
?>
<link rel="stylesheet" href="assets/css/product_sidebar.css?v=<?= $assetVersion ?>">
<div id="nt_content">

    <!--shop banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="<?=$sabitB['sabitBilgiProductGorselBaseUrl']?><?=$sabitB['sabitBilgiProductGorsel']?>"></div>
            <div class="container pr z_100">
                <h4 class="mb__5 cw">
                    <?php
                    // Sonuçları yazdırma
                    foreach (array_reverse($result) as $category) {
                        echo "<a href='products/".$category['kategoriKodu'] ."-". $category['kategoriDilBilgiSlug'] . "'>" . $category["kategoriDilBilgiBaslik"] . "</a><i class='facl facl-angle-right' style='padding:10px;'></i>";
                    }
                    ?>
                    <span class="text-uppercase"><?= $kategoriBul["kategoriDilBilgiBaslik"]; ?></span>
                </h4>
            </div>
        </div>
    </div>
    <!--end shop banner-->

    <div class="container container_cat pop_default cat_default mb__20">

        <!--grid control-->
        <form id="formpost" action="" method="get">
            <div class="cat_toolbar row fl_center al_center mt__30">
                <div class="form-check" style="padding: 20px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urunSFPPort" id="urunSFPPort" <?= ($_GET['urunSFPPort'] == '1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urunSFPPort">
                        100Mbit SFP Port
                    </label>
                </div>

                <div class="form-check" style="padding: 20px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urun1GSFPPort" id="urun1GSFPPort" <?= ($_GET['urun1GSFPPort'] == '1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urun1GSFPPort">
                        1Gigabit SFP Port
                    </label>
                </div>

                <div class="form-check" style="padding: 20px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urunSFPPortBirlikte" id="urunSFPPortBirlikte" <?= ($_GET['urunSFPPortBirlikte'] == '1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urunSFPPortBirlikte">
                        SFP+ Port
                    </label>
                </div>

                <div class="form-check" style="padding: 20px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urunSFP28Port" id="urunSFP28Port" <?= ($_GET['urunSFP28Port'] == '1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urunSFP28Port">
                        SFP28 Port
                    </label>
                </div>

                <div class="form-check" style="padding: 20px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urunQSFPPort" id="urunQSFPPort" <?= ($_GET['urunQSFPPort'] == '1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urunQSFPPort">
                        QSFP+ Port
                    </label>
                </div>

                <div class="form-check" style="padding: 20px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urunQSFP28Port" id="urunQSFP28Port" <?= ($_GET['urunQSFP28Port'] == '1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urunQSFP28Port">
                        QSFP28 Port
                    </label>
                </div>

                <div class="form-check" style="padding: 20px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urunEndustriyelTip" id="urunEndustriyelTip" <?= ($_GET['urunEndustriyelTip'] == '1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urunEndustriyelTip">
                        Endüstriyel Tip
                    </label>
                </div>

                <div class="form-check" style="padding: 20px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urun100MegabitRJ45Port" id="urun100MegabitRJ45Port" <?= ($_GET['urun100MegabitRJ45Port'] == '1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urun100MegabitRJ45Port">
                        100Mbit RJ45 Port
                    </label>
                </div>

                <div class="form-check" style="padding: 20px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urun1GigabitRJ45Port" id="urun1GigabitRJ45Port" <?= ($_GET['urun1GigabitRJ45Port'] == '1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urun1GigabitRJ45Port">
                        1 Gigabit RJ45 Port
                    </label>
                </div>

                <div class="form-check" style="padding: 20px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urun10GigabitRJ45Port" id="urun10GigabitRJ45Port" <?= ($_GET['urun10GigabitRJ45Port'] == '1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urun10GigabitRJ45Port">
                        10 Gigabit RJ45 Port
                    </label>
                </div>

                <?php
                if ($kategoriBul['kategoriOzelFiltre'] == 1) { ?>
                    <div class="form-check" style="padding: 15px !important;">
                        <input class="form-check-input" type="checkbox" value="1" name="urun1Metre" id="urun1Metre" <?= ($_GET['urun1Metre'] == '1') ? 'checked="checked"' : '0' ?>>
                        <label class="form-check-label" for="urun1Metre">
                            1Mt ve altı
                        </label>
                    </div>

                    <div class="form-check" style="padding: 15px !important;">
                        <input class="form-check-input" type="checkbox" value="1" name="urun2Metre" id="urun2Metre" <?= ($_GET['urun2Metre'] == '1') ? 'checked="checked"' : '0' ?>>
                        <label class="form-check-label" for="urun2Metre">
                            2Mt
                        </label>
                    </div>

                    <div class="form-check" style="padding: 15px !important;">
                        <input class="form-check-input" type="checkbox" value="1" name="urun3Metre" id="urun3Metre" <?= ($_GET['urun3Metre'] == '1') ? 'checked="checked"' : '0' ?>>
                        <label class="form-check-label" for="urun3Metre">
                            3Mt
                        </label>
                    </div>

                    <div class="form-check" style="padding: 15px !important;">
                        <input class="form-check-input" type="checkbox" value="1" name="urun510Metre" id="urun510Metre" <?= ($_GET['urun510Metre'] == '1') ? 'checked="checked"' : '0' ?>>
                        <label class="form-check-label" for="urun510Metre">
                            5-10Mt
                        </label>
                    </div>

                    <div class="form-check" style="padding: 15px !important;">
                        <input class="form-check-input" type="checkbox" value="1" name="urun1020Metre" id="urun1020Metre" <?= ($_GET['urun1020Metre'] == '1') ? 'checked="checked"' : '0' ?>>
                        <label class="form-check-label" for="urun1020Metre">
                            10-20Mt
                        </label>
                    </div>

                    <div class="form-check" style="padding: 15px !important;">
                        <input class="form-check-input" type="checkbox" value="1" name="urun2030Metre" id="urun2030Metre" <?= ($_GET['urun2030Metre'] == '1') ? 'checked="checked"' : '0' ?>>
                        <label class="form-check-label" for="urun2030Metre">
                            20Mt ve üzeri
                        </label>
                    </div>
                <?php } ?>

                <div class="form-check" style="padding: 15px !important;">
                    <input type="hidden" name="filtre" value="1">
                    <button type="submit" class="btn btn-primary btn-sm"><?= $fonk->getDil("Filtrele") ?></button>
                </div>
            </div>
        </form>

        <!--end grid control-->


        <!--product container-->
        <div class="row">
            <!--left sidebar-->
            <div class="js_sidebar sidebar sidebar_nt col-xl-2 col-lg-3 col-md-4 col-12 space_30 hidden_false lazyload">
                <div id="kalles-section-sidebar_shop" class="kalles-section nt_ajaxFilter section_sidebar_shop type_instagram">
                    
                    <div class="cat_shop_wrap">
                        <div class="cat_fixcl-scroll">
                            <div class="cat_fixcl-scroll-content css_ntbar">
                                <div class="row no-gutters wrap_filter">
                                    <div class="col-12 col-md-12 widget widget_product_categories cat_count_false">
                                        <h5 class="widget-title"><?= $fonk->getDil("Kategoriler"); ?></h5>
                                        <div class="sidenav-82">
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
                                            ?>
                                                <?php if (count($altkategoriler) <= 0) { ?>
                                                    <button class="dropdown-btn-82">
                                                        <span onclick="javascript:location.href='<?=$sabitB['sabitBilgiSiteUrl']?>products/<?= $value['kategoriKodu']; ?>-<?= $value['kategoriDilBilgiSlug']; ?>'">
                                                            <?= $value["kategoriDilBilgiBaslik"]; ?>
                                                        </span>
                                                    </button>
                                                <?php } else { ?>
                                                    <button class="dropdown-btn-82">
                                                        <span onclick="javascript:location.href='<?=$sabitB['sabitBilgiSiteUrl']?>products/<?= $value['kategoriKodu']; ?>-<?= $value['kategoriDilBilgiSlug']; ?>'">
                                                            <?= $value["kategoriDilBilgiBaslik"]; ?>
                                                        </span>
                                                        <i class="fa fa-caret-down" onclick="call(<?= $value['kategoriKodu']; ?>)"></i>
                                                    </button>
                                                    <?php foreach ($altkategoriler as $key => $valueAlt) {
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
                                                    ?>
                                                        <div class="dropdown-container-82" name="show-<?= $value["kategoriKodu"]; ?>" id="s-<?= uniqid() ?>" style="display:none; background:#F2815D;">
                                                            <?php if (count($altkategoriler2) <= 0) { ?>
                                                                <button class="dropdown-btn-82">
                                                                    <span onclick="javascript:location.href='<?=$sabitB['sabitBilgiSiteUrl']?>products/<?= $valueAlt['kategoriKodu']; ?>-<?= $value['kategoriDilBilgiSlug']; ?>-<?= $valueAlt['kategoriDilBilgiSlug']; ?>'">
                                                                        <?= $valueAlt["kategoriDilBilgiBaslik"]; ?>
                                                                    </span>
                                                                </button>
                                                            <?php } else { ?>
                                                                <button class="dropdown-btn-82">
                                                                    <span onclick="javascript:location.href='<?=$sabitB['sabitBilgiSiteUrl']?>products/<?= $valueAlt['kategoriKodu']; ?>-<?= $value['kategoriDilBilgiSlug']; ?>-<?= $valueAlt['kategoriDilBilgiSlug']; ?>'">
                                                                        <?= $valueAlt["kategoriDilBilgiBaslik"]; ?>
                                                                    </span>
                                                                    <i class="fa fa-caret-down" onclick="call(<?= $valueAlt['kategoriKodu']; ?>)"></i>
                                                                </button>
                                                                <div class="dropdown-container-82" name="show-<?= $valueAlt["kategoriKodu"]; ?>" id="s-<?= uniqid() ?>" style="display:none; background:#F38F6F;">
                                                                    <?php foreach ($altkategoriler2 as $key => $valueAlt2) { ?>
                                                                        <a href="products/<?= $valueAlt2["kategoriKodu"]; ?>-<?= $value['kategoriDilBilgiSlug']; ?>-<?= $valueAlt['kategoriDilBilgiSlug']; ?>-<?= $valueAlt2["kategoriDilBilgiSlug"]; ?>">
                                                                            <?= $valueAlt2["kategoriDilBilgiBaslik"]; ?>
                                                                        </a>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end left sidebar-->

            <!--main content-->
            <div class="col-xl-10 col-lg-9 col-md-8 col-12">
                <div class="kalles-section tp_se_cdt">
                    <label><span id="toplamKayit"></span> <?= $fonk->getDil("sonuçlar"); ?></label>
                    <!--products list-->
                    <div class="on_list_view_false products nt_products_holder row fl_center row_pr_1 cdt_des_1 round_cd_false nt_cover ratio_nt position_8 space_30 nt_default" id="urunlerList">
                        <!-- ürünlerin listelemesi -->
                    </div>
                    <!--end products list-->
                    <img src="Panel/Images/loading.gif" id="loadingItem" style="position:relative;left:50%;margin-top:10%;width:64px">
                    <div id="altBolum"></div><!-- margin -->

                    <div class="mt-5">
                        <?=$kategoriBul["kategoriDilBilgiAciklama"]?>
                    </div>
                </div>
            </div>
            <!--end main content-->

            <div>
               
            </div>

        </div>
        <!--end product container-->
    </div>
</div>


<script>
    var sayac = 1;
    var loadingDurum = true;
    window.onload = (event) => {
        document.getElementById("loadingItem").style.display = "block";
        urunlerList(sayac, "<?= $_GET['seo'] ?>", <?= $uyeVar ?>);
        const target = document.getElementById('altBolum');
        const callback = (entries) => {
            if (entries[0].isIntersecting) {
                if (loadingDurum) {
                    urunlerList(sayac, "<?= $_GET['seo'] ?>", <?= $uyeVar ?>);
                }
            }
        };
        const observer = new IntersectionObserver(callback);
        observer.observe(target);
    };

    function urunlerList(page, seo) {
        loadingDurum = false;
        $.ajax({
            type: "GET",
            url: "ajax/urunlerList.php",
            data: {
                'page': page,
                'seo': seo,
                'filtre': "<?= ($_GET['filtre'] == '1') ? '1' : '0' ?>",
                'urunSFPPort': "<?= ($_GET['urunSFPPort'] == '1') ? '1' : '0' ?>",
                'urun1GSFPPort': "<?= ($_GET['urun1GSFPPort'] == '1') ? '1' : '0' ?>",
                'urunSFPPortBirlikte': "<?= ($_GET['urunSFPPortBirlikte'] == '1') ? '1' : '0' ?>",
                'urunSFP28Port': "<?= ($_GET['urunSFP28Port'] == '1') ? '1' : '0' ?>",
                'urunQSFPPort': "<?= ($_GET['urunQSFPPort'] == '1') ? '1' : '0' ?>",
                'urunQSFP28Port': "<?= ($_GET['urunQSFP28Port'] == '1') ? '1' : '0' ?>",
                'urunEndustriyelTip': "<?= ($_GET['urunEndustriyelTip'] == '1') ? '1' : '0' ?>",
                'urun100MegabitRJ45Port': "<?= ($_GET['urun100MegabitRJ45Port'] == '1') ? '1' : '0' ?>",
                'urun1GigabitRJ45Port': "<?= ($_GET['urun1GigabitRJ45Port'] == '1') ? '1' : '0' ?>",
                'urun10GigabitRJ45Port': "<?= ($_GET['urun10GigabitRJ45Port'] == '1') ? '1' : '0' ?>",
                'urun1Metre': "<?= ($_GET['urun1Metre'] == '1') ? '1' : '0' ?>",
                'urun2Metre': "<?= ($_GET['urun2Metre'] == '1') ? '1' : '0' ?>",
                'urun3Metre': "<?= ($_GET['urun3Metre'] == '1') ? '1' : '0' ?>",
                'urun510Metre': "<?= ($_GET['urun510Metre'] == '1') ? '1' : '0' ?>",
                'urun1020Metre': "<?= ($_GET['urun1020Metre'] == '1') ? '1' : '0' ?>",
                'urun2030Metre': "<?= ($_GET['urun2030Metre'] == '1') ? '1' : '0' ?>"
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
    function SepeteEkle(urunId) {
        <?php if ($uyeVar == 1) { ?>
            var adet = document.getElementById("adet_" + urunId).value;
            SepetKayit(urunId, "0", adet);
        <?php } else { ?>
            swal("Uyarı", "Lütfen üye girişi yapınız.", "warning")
                .then((value) => {
                    window.location.href = "<?= $sabitBilgiler['sabitBilgiSiteUrl']; ?>account";
                });
        <?php } ?>
    }

    function FavoriEkle(urunId, uyeId) {
        if (uyeId != "" && uyeId != undefined) {
            FavoriKayit(urunId, uyeId);
        } else {
            swal("Uyarı", "Lütfen üye girişi yapınız.", "warning")
                .then((value) => {
                    window.location.href = "<?= $sabitBilgiler['sabitBilgiSiteUrl']; ?>account";
                });
        }
    }
</script>