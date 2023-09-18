<?php include('layouts/header.php') ?>
<div id="nt_content">

    <!--shop banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw">
                    <?= $fonk->getDil("Markalar") ?>
                </h1>
            </div>
        </div>
    </div>
    <!--end shop banner-->

    <div class="container container_cat pop_default cat_default mb__20">

        <!--grid control-->
        <form id="formpost" action="" method="get"> 
            <div class="cat_toolbar row fl_center al_center mt__30">
                <div class="form-check" style="padding: 15px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urunSFPPort" id="urunSFPPort" <?=($_GET['urunSFPPort']=='1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urunSFPPort">
                        100Mbit SFP Port
                    </label>
                </div>

                <div class="form-check" style="padding: 15px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urun1GSFPPort" id="urun1GSFPPort" <?=($_GET['urun1GSFPPort']=='1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urun1GSFPPort">
                        1Gigabit SFP Port
                    </label>
                </div>

                <div class="form-check" style="padding: 15px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urunSFPPortBirlikte" id="urunSFPPortBirlikte" <?=($_GET['urunSFPPortBirlikte']=='1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urunSFPPortBirlikte">
                        SFP+ Port
                    </label>
                </div>

                <div class="form-check" style="padding: 15px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urunSFP28Port" id="urunSFP28Port" <?=($_GET['urunSFP28Port']=='1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urunSFP28Port">
                        SFP28 Port
                    </label>
                </div>

                <div class="form-check" style="padding: 15px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urunQSFPPort" id="urunQSFPPort" <?=($_GET['urunQSFPPort']=='1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urunQSFPPort">
                        QSFP+ Port
                    </label>
                </div>

                <div class="form-check" style="padding: 15px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urunQSFP28Port" id="urunQSFP28Port" <?=($_GET['urunQSFP28Port']=='1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urunQSFP28Port">
                        QSFP28 Port
                    </label>
                </div>

                <div class="form-check" style="padding: 15px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urunEndustriyelTip" id="urunEndustriyelTip" <?=($_GET['urunEndustriyelTip']=='1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urunEndustriyelTip">
                        Endüstriyel Tip
                    </label>
                </div>

                <div class="form-check" style="padding: 15px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urun100MegabitRJ45Port" id="urun100MegabitRJ45Port" <?=($_GET['urun100MegabitRJ45Port']=='1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urun100MegabitRJ45Port">
                        100Mbit RJ45 Port
                    </label>
                </div>

                <div class="form-check" style="padding: 15px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urun1GigabitRJ45Port" id="urun1GigabitRJ45Port" <?=($_GET['urun1GigabitRJ45Port']=='1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urun1GigabitRJ45Port">
                        1 Gigabit RJ45 Port
                    </label>
                </div>

                <div class="form-check" style="padding: 15px !important;">
                    <input class="form-check-input" type="checkbox" value="1" name="urun10GigabitRJ45Port" id="urun10GigabitRJ45Port" <?=($_GET['urun10GigabitRJ45Port']=='1') ? 'checked="checked"' : '0' ?>>
                    <label class="form-check-label" for="urun10GigabitRJ45Port">
                        10 Gigabit RJ45 Port
                    </label>
                </div>

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
            <div class="js_sidebar sidebar sidebar_nt col-lg-2 col-12 space_30 hidden_false lazyload">
                <div id="kalles-section-sidebar_shop" class="kalles-section nt_ajaxFilter section_sidebar_shop type_instagram">
                    <div class="h3 mg__0 tu bgb cw visible-sm fs__16 pr"><?= $fonk->getDil("Markalar") ?> <i class="close_pp pegk pe-7s-close fs__40 ml__5"></i>
                    </div>
                    <div class="cat_shop_wrap">
                        <div class="cat_fixcl-scroll">
                            <div class="cat_fixcl-scroll-content css_ntbar">
                                <div class="row no-gutters wrap_filter">

                                    <div class="col-12 col-md-12 widget widget_product_categories cat_count_false">
                                        <h5 class="widget-title"><?= $fonk->getDil("Markalar"); ?></h5>
                                        <div class="wrapper">
                                            <div class="sidebar">
                                                <div class="sb-item-list">
                                                <?php
                                                $markaListesi = $db->select("Varyantlar", [
                                                    "[>]VaryantDilBilgiler" => ["Varyantlar.varyantId" => "varyantDilBilgiVaryatId"]
                                                ], "*", [
                                                    "varyantDilBilgiDilId" => $_SESSION["dilId"],
                                                    "varyanDurum" => 1,
                                                    "ORDER" => [
                                                        "varyantId" => "ASC"
                                                    ]
                                                ]);
                                                foreach ($markaListesi as $key => $value) {
                                                ?>
                                                    <div class="sb-item"><i class="sb-icon fa fa-address-card"></i>
                                                        <span class="sb-text">
                                                            <a href="brands/<?= $value["varyantDilBilgiSlug"]; ?>" class="text-uppercase">
                                                                <?= $value["varyantDilBilgiBaslik"]; ?>
                                                            </a>
                                                        </span>
                                                    </div>  
                                                <?php } ?>  
                                                </div>
                                            </div>
                                            <div class="main"></div>
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
            <div class="col-lg-10 col-12">
                <div class="kalles-section tp_se_cdt">
                    <label>Showing 1–8 of <span id="toplamKayit"></span> <?= $fonk->getDil("sonuçlar"); ?></label>
                    <!--products list-->
                    <div class="on_list_view_false products nt_products_holder row fl_center row_pr_1 cdt_des_1 round_cd_false nt_cover ratio_nt position_8 space_30 nt_default" id="markalarList">
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
    function myFunction(id) {
        var count = document.getElementById('key').value
        for (let index = 0; index <= count-1; index++) {
            document.getElementById("id-"+id+"-"+index).style.display = "block";
        }
    }

    var sayac = 1;
    var loadingDurum = true;
    window.onload = (event) => {
        document.getElementById("loadingItem").style.display = "block";
        markalarList(sayac, "<?= $_GET['seo'] ?>",<?=$uyeVar?>);
        const target = document.getElementById('altBolum');
        const callback = (entries) => {
            if (entries[0].isIntersecting) {
                if (loadingDurum) {
                    markalarList(sayac, "<?= $_GET['seo'] ?>",<?=$uyeVar?>);
                }
            }
        };
        const observer = new IntersectionObserver(callback);
        observer.observe(target);
    };

    function markalarList(page, seo) {
        loadingDurum = false;
        //document.getElementById("loadingItem").style.display = "none";
        $.ajax({
            type: "GET",
            url: "ajax/markalarList.php",
            data: {
                'page': page,
                'seo': seo,
                'filtre': "<?=($_GET['filtre']=='1') ? '1' : '0' ?>",
                'urunSFPPort': "<?=($_GET['urunSFPPort']=='1') ? '1' : '0' ?>",
                'urun1GSFPPort': "<?=($_GET['urun1GSFPPort']=='1') ? '1' : '0' ?>",
                'urunSFPPortBirlikte': "<?=($_GET['urunSFPPortBirlikte']=='1') ? '1' : '0' ?>",
                'urunSFP28Port': "<?=($_GET['urunSFP28Port']=='1') ? '1' : '0' ?>",
                'urunQSFPPort': "<?=($_GET['urunQSFPPort']=='1') ? '1' : '0' ?>",
                'urunQSFP28Port': "<?=($_GET['urunQSFP28Port']=='1') ? '1' : '0' ?>",
                'urunEndustriyelTip': "<?=($_GET['urunEndustriyelTip']=='1') ? '1' : '0' ?>",
                'urun100MegabitRJ45Port': "<?=($_GET['urun100MegabitRJ45Port']=='1') ? '1' : '0' ?>",
                'urun1GigabitRJ45Port': "<?=($_GET['urun1GigabitRJ45Port']=='1') ? '1' : '0' ?>",
                'urun10GigabitRJ45Port': "<?=($_GET['urun10GigabitRJ45Port']=='1') ? '1' : '0' ?>"
            },
            success: function(gelenSayfa) {
                document.getElementById("loadingItem").style.display = "none";
                $('#markalarList').append(gelenSayfa);
                sayac++;
                loadingDurum = true;
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
</script>