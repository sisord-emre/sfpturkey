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
            <!--main content-->
            <div class="col-lg-12 col-12">
                <div class="kalles-section tp_se_cdt">
                    <label><span id="toplamKayit"></span> <?= $fonk->getDil("sonuçlar"); ?></label>
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
        document.getElementById("loadingItem").style.display = "block";
        urunlerList(sayac, "<?= $_GET['ara'] ?>",<?=$uyeVar?>);
        const target = document.getElementById('altBolum');
        const callback = (entries) => {
            if (entries[0].isIntersecting) {
                if (loadingDurum) {
                    urunlerList(sayac, "<?= $_GET['ara'] ?>",<?=$uyeVar?>);
                }
            }
        };
        const observer = new IntersectionObserver(callback);
        observer.observe(target);
    };

    function urunlerList(page, ara) {
        loadingDurum = false;
       
        $.ajax({
            type: "GET",
            url: "ajax/searchList.php",
            data: {
                'page': page,
                'ara': ara
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
</script>