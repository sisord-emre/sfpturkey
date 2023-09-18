<style>
    .floatW {
        position: fixed;
        width: 55px;
        height: 55px;
        bottom: 115px;
        right: 40px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        box-shadow: 2px 2px 3px #999;
        z-index: 100;
    }

    .my-float {
        color: #FFF;
        padding: 10px;
    }
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<!--<a href="https://api.whatsapp.com/send?phone=+000000&text=Yardıma ihtiyacınız mı var?" class="floatW" target="_blank">
    <i class="fa fa-whatsapp my-float"></i>
</a>-->
<div id="modalDiv"></div>
<!-- footer -->
<footer id="nt_footer" class="bgbl footer-1">
    <div id="kalles-section-footer_top" class="kalles-section footer__top type_instagram">
        <div class="footer__top_wrap footer_sticky_false footer_collapse_true nt_bg_overlay pr oh pb__30 pt__80">
            <div class="container pr z_100">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-12 mb__50 order-lg-1 order-1">
                        <div class="widget widget_text widget_logo">
                            <h3 class="widget-title fwsb flex al_center fl_between fs__16 mg__0 mb__30 dn_md">
                                <span class="txt_title"> <?= $fonk->getDil("KURUMSAL"); ?></span>
                                <span class="nav_link_icon ml__5"></span>
                            </h3>
                            <div class="widget_footer">
                                <div class="footer-contact">
                                    <p>
                                        <a class="d-block" href="index">
                                            <img class="w__100 mb__15 lazyload max-width__300px" src="assets/img/sfplogo.png" alt="" data-src="assets/img/sfplogo.png">
                                        </a>
                                    </p>
                                    <p>
                                        <i class="pegk pe-7s-map-marker"> </i>
                                           <?=$siteAdres?>
                                        </span>
                                    </p>
                                    <p><i class="pegk pe-7s-mail"></i>
                                        <span>
                                            <a href="mailto:<?=$siteMail[1]?>">
                                            <?=$siteMail[1]?>
                                            </a>
                                        </span>
                                    </p>
                                    <p>
                                        <i class="pegk pe-7s-call"></i>
                                        <span><?=$siteTel[0]?> </span>
                                    </p>
                                    <div class="nt-social">
                                        <a href="<?=$sabitB["sabitBilgiFacebook"]?>" class="facebook cb ttip_nt tooltip_top">
                                            <i class="facl facl-facebook"></i>
                                        </a>
                                        <a href="<?=$sabitB["sabitBilgiTwitter"]?>" class="twitter cb ttip_nt tooltip_top">
                                            <i class="facl facl-twitter"></i>
                                        </a>
                                        <a href="<?=$sabitB["sabitBilgiInstagram"]?>" class="instagram cb ttip_nt tooltip_top">
                                            <i class="facl facl-instagram"></i>
                                        </a>
                                        <a href="<?=$sabitB["sabitBilgiLinkedin"]?>" class="linkedin cb ttip_nt tooltip_top">
                                            <i class="facl facl-linkedin"></i>
                                        </a>
                                        <a href="<?=$sabitB["sabitBilgiYoutube"]?>" class="youtube cb ttip_nt tooltip_top">
                                            <i class="facl facl-youtube-play {"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $footerMenuler = $db->select("FooterMenuler", "*", [
                        "footerMenuDilId" => $_SESSION["dilId"],
                        "footerMenuUstMenuId" => 0, //anamenü
                        "ORDER" => [
                            "footerMenuSirasi" => "ASC"
                        ]
                    ]);

                    foreach ($footerMenuler as $key => $value) {
                    ?>
                    <div class="col-lg-2 col-md-6 col-12 mb__50 order-lg-2 order-1">
                        <div class="widget widget_nav_menu">
                            <h3 class="widget-title fwsb flex al_center fl_between fs__16 mg__0 mb__30">
                                <span class="txt_title"><?= $value['footerMenuBaslik'] ?></span>
                                <span class="nav_link_icon ml__5"></span>
                            </h3>
                            <div class="menu_footer widget_footer">
                                <?php
                                $footerAltMenuler = $db->select("FooterMenuler", "*", [
                                    "footerMenuDilId" => $_SESSION["dilId"],
                                    "footerMenuUstMenuId" => $value['footerMenuId'],
                                    "ORDER" => [
                                        "footerMenuSirasi" => "ASC"
                                    ]
                                ]);
                                if ($footerAltMenuler > 0) {
                                ?>
                                <ul class="menu">
                                    <?php foreach ($footerAltMenuler as $value) { ?>
                                    <li class="menu-item">
                                        <a href="<?= $value['footerMenuLink'] ?>">
                                            <?= $value['footerMenuBaslik'] ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="col-lg-2 col-md-6 col-12 mb__50 order-lg-2 order-1">
                        <div class="widget widget_nav_menu">
                            <h3 class="widget-title fwsb flex al_center fl_between fs__16 mg__0 mb__30">
                                <span class="txt_title"><?= $fonk->getDil("E-TİCARET BİLGİ PLATFORMU"); ?></span>
                                <span class="nav_link_icon ml__5"></span>
                            </h3>
                            <div class="menu_footer widget_footer">
                                <p>
                                    <a class="d-block" href="https://www.eticaret.gov.tr/siteprofil/4333801107747868/wwwsfpturkeycomtr" target="_blank">
                                        <img class="w__100 mb__15 lazyload max-width__135px" src="assets/img/indir.jpg" alt="" data-src="assets/img/indir.jpg">
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="kalles-section-footer_bot" class="kalles-section footer__bot">
        <div class="footer__bot_wrap pt__20 pb__20">
            <div class="container pr tc">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12 col_1 text-center"><?= $fonk->getDil("Telif hakkı"); ?> © <?= date("Y"); ?>
                        <span class="cp"><?=$sabitB["sabitBilgiSiteAdi"]?></span>  <?= $fonk->getDil("tüm hakları saklıdır"); ?>.
                    </div>

                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end footer -->

</div>

<!-- mobile menu -->
<div id="nt_menu_canvas" class="nt_fk_canvas nt_sleft dn lazyload">
    <i class="close_pp pegk pe-7s-close ts__03 cd"></i>
    <div class="mb_nav_tabs flex al_center mb_cat_true">
        <div class="mb_nav_title pr mb_nav_ul flex al_center fl_center active act_opened" data-id="#kalles-section-mb_nav_js">
            <span class="d-block truncate act_opened"> <?= $fonk->getDil("Kategorİler"); ?></span>
        </div>
        <div class="mb_nav_title pr flex al_center fl_center act_opened" data-id="#kalles-section-mb_cat_js">
            <span class="d-block truncate act_opened"> <?= $fonk->getDil("Marka"); ?></span>
        </div>
    </div>
    <div id="kalles-section-mb_nav_js" class="mb_nav_tab active act_opened">
        <div id="kalles-section-mb_nav" class="kalles-section">
            <ul id="menu_mb_ul" class="nt_mb_menu">
                <?php 
                $kategoriler = $db->select("Kategoriler", [
                    "[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"],
                ], "*", [
                    "kategoriDilBilgiDilId" => $_SESSION["dilId"],
                    "kategoriUstMenuId" => 0, //anamenü
                    "kategoriDilBilgiDurum" => 1,
                    "ORDER" => [
                        "kategoriSirasi" => "ASC"
                    ]
                ]);
                foreach ($kategoriler as $key => $value) { ?>
                <li class="menu-item text-uppercase">
                    <a href="products/<?=$value['kategoriKodu']?>-<?=$value['kategoriDilBilgiSlug']?>" class="act_opened">
                        <?=$value['kategoriDilBilgiBaslik']?>
                    </a>
                </li>
                <?php } ?>
                
                <li class="menu-item menu-item-infos act_opened">
                    <p class="menu_infos_title act_opened"> <?= $fonk->getDil("Yardıma mı ihtiyacınız var?"); ?></p>
                    <div class="menu_infos_text act_opened">
                        <i class="pegk pe-7s-call fwb mr__10 act_opened"></i><?=$siteTel[0]?> <br>
                        <i class="pegk pe-7s-mail fwb mr__10 act_opened"></i>
                        <a class="cg act_opened" href="mailto:<?=$siteMail[1]?>"><?=$siteMail[1]?></a>
                    </div>
                </li>

            </ul>
        </div>
    </div>
    <div id="kalles-section-mb_cat_js" class="mb_nav_tab act_opened">
        <div id="kalles-section-mb_cat" class="kalles-section">
            <ul id="menu_mb_cat" class="nt_mb_menu act_opened">
                <?php 
                $varyantlar = $db->select("Varyantlar", [
                    "[>]VaryantDilBilgiler" => ["Varyantlar.varyantId" => "varyantDilBilgiVaryatId"],
                ], "*", [
                    "varyantDilBilgiDilId" => $_SESSION["dilId"],
                    "ORDER" => [
                        "varyantDilBilgiBaslik" => "ASC"
                    ]
                ]);
              
                foreach ($varyantlar as $key => $value) { ?>
                <li class="menu-item">
                    <a href="brands/<?=$value['varyantDilBilgiSlug']?>" class="act_opened">
                        <?=$value['varyantDilBilgiBaslik']?>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<!-- end mobile menu -->


<!-- search box -->
<div id="nt_search_canvas" class="nt_fk_canvas dn">
    <div class="nt_mini_cart flex column h__100">
        <div class="mini_cart_header flex fl_between al_center">
            <h3 class="widget-title tu fs__16 mg__0 font-poppins">  <?= $fonk->getDil("SİTEMİZDE ARAYIN"); ?></h3>
            <i class="close_pp pegk pe-7s-close ts__03 cd"></i>
        </div>
        <div class="mini_cart_wrap">
            <form action="search.php" method="get" role="search">
                <div class="search_header mini_search_frm pr js_frm_search" role="search">
                    <div class="frm_search_input pr oh">
                        <input class="search_header__input js_iput_search placeholder-black" autocomplete="off" type="text" name="ara" value="<?= $_GET['ara']; ?>" placeholder="<?= $fonk->getDil("ürün ara"); ?> ....">
                        <button class="search_header__submit js_btn_search" type="submit">
                            <i class="iccl iccl-search"></i>
                        </button>
                    </div>
                    <div class="ld_bar_search"></div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end search box -->

<!-- back to top button-->
<a id="nt_backtop" class="pf br__50 z__100 des_bt1" href="#"><span class="tc br__50 d-block cw"><i class="pr pegk pe-7s-angle-up"></i></span></a>
<script src="assets/js/jquery-3.5.1.min.js?v=<?=$assetVersion?>"></script>
<script src="assets/js/jarallax.min.js?v=<?=$assetVersion?>"></script>
<script src="assets/js/packery.pkgd.min.js?v=<?=$assetVersion?>"></script>
<script src="assets/js/jquery.hoverIntent.min.js?v=<?=$assetVersion?>"></script>
<script src="assets/js/magnific-popup.min.js?v=<?=$assetVersion?>"></script>
<script src="assets/js/flickity.pkgd.min.js?v=<?=$assetVersion?>"></script>
<script src="assets/js/lazysizes.min.js?v=<?=$assetVersion?>"></script>
<script src="assets/js/js-cookie.min.js?v=<?=$assetVersion?>"></script>
<script src="assets/js/jquery.countdown.min.js?v=<?=$assetVersion?>"></script>
<script src="assets/js/interface.js?v=<?=$assetVersion?>"></script>
<script src="assets/scripts.js?v=<?=$assetVersion?>"></script>
<script src="assets/js/bootstrap.min.js?v=<?=$assetVersion?>"></script>
<script src="assets/gl.ajax-search.js?v=<?=$assetVersion?>"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

</body>

</html>