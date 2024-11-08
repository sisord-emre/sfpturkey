<?php
include('Panel/System/Config.php');

$assetVersion = "1.2.5";
$siteTitle = $sabitB["sabitBilgiTitle"];
$siteDescription = $sabitB["sabitBilgiDescription"];
$siteTel = explode(';', $sabitB['sabitBilgiTel']);
$siteAdres = $sabitB["sabitBilgiAdres"];

$dilList = $db->get("Diller", "*", [
    "dilDurumu" => 1,
    "dilId" => $_SESSION["dilId"]
]);

$_SESSION["dilKodu"] = $dilList['dilKodu'];

if (strstr($_SERVER['PHP_SELF'], "product.php")) {
    $seo = intval(explode('-', $_GET['seo'])[0]);
    $urun = $db->get("Urunler", [
        "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
        "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
        "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
        "[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
        "[>]KategoriDilBilgiler" => ["UrunKategoriler.urunKategoriKategoriId" => "kategoriDilBilgiKategoriId"],
        "[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
    ], "*", [
        "urunVaryantKodu" => $seo,
        "urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
        "urunDilBilgiDilId" => $_SESSION["dilId"],
        "urunDilBilgiDurum" => 1,
        "ORDER" => [
            "urunId" => "ASC"
        ]
    ]);

    $siteTitle = $urun["urunVaryantDilBilgiAdi"];
    $siteDescription = $urun["urunDilBilgiDescription"];
}

if ($_SESSION['uyeSessionKey'] != "") {
    $uyeVar = 1;
    $uye = $db->get("Uyeler", "*", [
        "uyeSessionKey" => $_SESSION['uyeSessionKey']
    ]);

	$enSonSiparis = $db->select("Siparisler",[
		"[>]SiparisIcerikleri" => ["Siparisler.siparisId" => "siparisIcerikSiparisId"],
	],"*",[
		"siparisOdemeTipiId" => 0,//yani herhangi bir ödeme tipi olmayan
        "siparisUyeId" =>$uye["uyeId"]
	]);
}
?>
<!DOCTYPE html>
<html lang="<?= $dilList['dilKodu']; ?>">

<head>
    <base href="<?= $sabitB["sabitBilgiSiteUrl"] ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png" />
    <title><?= $siteTitle ?></title>
    <meta name="keywords" content="<?= $sabitB["sabitBilgiKeywords"] ?>">
    <meta name="author" content="<?= $sabitB["sabitBilgiLisansFirmaAdi"] ?>">
    <meta name="description" content="<?= $siteDescription ?>">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Libre+Baskerville:300,300i,400,400i,500,500i&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/font-icon.min.css?v=<?= $assetVersion ?>">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css?v=<?= $assetVersion ?>">
    <link rel="stylesheet" href="assets/css/reset.css?v=<?= $assetVersion ?>">
    <link rel="stylesheet" href="assets/css/defined.css?v=<?= $assetVersion ?>">
    <link rel="stylesheet" href="assets/css/base.css?v=<?= $assetVersion ?>">
    <link rel="stylesheet" href="assets/css/style.css?v=<?= $assetVersion ?>">
    <link rel="stylesheet" href="assets/css/custom.css?v=<?= $assetVersion ?>">
    <link rel="stylesheet" href="assets/css/home-header-01.css?v=<?= $assetVersion ?>">
    <link rel="stylesheet" href="assets/css/home-electronic-vertical.css?v=<?= $assetVersion ?>">
    <link rel="stylesheet" href="assets/css/shopping-cart.css?v=<?= $assetVersion ?>">

    <script src='https://www.google.com/recaptcha/api.js?hl=eng'></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Q6QEP18KQ5"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-Q6QEP18KQ5');
    </script>
	<!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-961001604"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'AW-961001604');
    </script>
</head>

<body class="lazy_icons btnt4_style_2 zoom_tp_2 css_scrollbar template-index js_search_true cart_pos_side kalles_toolbar_true hover_img2 swatch_style_rounded swatch_list_size_small label_style_rounded wrapper_full_width header_sticky_true hide_scrolld_true des_header_1 h_banner_true top_bar_true prs_bordered_grid_1 search_pos_full lazyload js_search_type template-cart">
  
    <div id="nt_wrapper">

        <!-- header -->
        <header id="ntheader" class="ntheader header_1 h_icon_iccl">
            <div class="ntheader_wrapper pr z_200">
                <div id="kalles-section-header_top" class="kalles-section">
                    <div class="h__top bgbl pt__10 pb__10 fs__12 flex fl_center al_center">
                        <div class="container">
                            <div class="row al_center">
                                <div class="col-lg-4 col-12 mb--3 tc tl_lg col-md-12 dn_false_1024 align-self-center">
                                    <div class="header-text">
                                        <i class="pegk pe-7s-call"></i><?= $siteTel[0] ?>
                                        <i class="pegk pe-7s-mail ml__15"></i>
                                        <a class="cg" href="mailto:<?= $siteMail[1] ?>"><?= $siteMail[1] ?></a>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-12 mb--3 tc col-md-12 dn_false_1024 align-self-center">
                                    <div class="header-text">
                                        <div class="nt-social">
                                            <a href="<?=$sabitB["sabitBilgiFacebook"]?>" class="facebook cb">
                                                <i class="facl facl-facebook"></i>
                                            </a>
                                            <a href="<?=$sabitB["sabitBilgiTwitter"]?>" class="twitter cb">
                                                <i class="facl facl-twitter"></i>
                                            </a>
                                            <a href="<?=$sabitB["sabitBilgiInstagram"]?>" class="instagram cb">
                                                <i class="facl facl-instagram"></i>
                                            </a>
                                            <a href="<?=$sabitB["sabitBilgiLinkedin"]?>" class="linkedin cb">
                                                <i class="facl facl-linkedin"></i>
                                            </a>
                                            <a href="<?=$sabitB["sabitBilgiYoutube"]?>" class="youtube cb">
                                                <i class="facl facl-youtube-play {"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-12 tc col-md-12 tr_lg dn_false_1024">
                                        <div class="nt_action in_flex al_center cart_des_1">
                                        <div class="my-account ts__05">
                                            <button>USD : <?= $sabitB["sabitBilgiDolar"]; ?> TL</button>
                                        </div>
                                    </div>
                                 </div>
                                <?php if (!$uyeVar) { ?>
                                    <div class="col-lg-2 col-12 tc col-md-12 tr_lg dn_false_1024 mt-3 mt-md-0">
                                        <div class="nt_action in_flex al_center cart_des_1">
                                            <div class="my-account ts__05">
                                                <button onclick="location.href='account.php';" class="btn btn-sm h_search_btn text-white">
                                                    <?= $fonk->getDil("Bayi Girişi"); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else {?>
                                    <div class="col-lg-2 col-12 tc col-md-12 tr_lg dn_false_1024 mt-3 mt-md-0">
                                        <div class="nt_action in_flex al_center cart_des_1">
                                            <div class="my-account ts__05">
                                                <?= $fonk->getDil("Hoşgeldiniz"); ?> <?= $uye['uyeAdi'] ?> <?= $uye['uyeSoyadi'] ?> <a href="account.php?exit=ok" style="color:#f1734c;">Çıkış</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="kalles-section-header_1" class="kalles-section sp_header_mid">
                    <div class="header__mid">
                        <div class="container">
                            <div class="row al_center min-height__64px">

                                <div class="col-lg-1 col-md-1 col-1 lh__1">
                                    <a href="#" data-id="#nt_menu_canvas" class="push_side push-menu-btn lh__1 flex al_center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="16" viewBox="0 0 30 16">
                                            <rect width="30" height="1.5"></rect>
                                            <rect y="7" width="20" height="1.5"></rect>
                                            <rect y="14" width="30" height="1.5"></rect>
                                        </svg>
                                    </a>
                                </div>

                                <div class="col-lg-4 col-md-6 col-6">
                                    <div class="branding ts__05 lh__1">
                                        <a class="dib" href="index.php">
                                            <img class="max-width__300px logo_normal dn db_lg" src="assets/img/sfplogo.png" alt="">
                                            <img class="max-width__180px logo_sticky dn" src="assets/img/sfplogo.png" alt="">
                                            <img class="max-width__180px logo_mobile dn_lg" src="assets/img/sfplogo.png" alt="">
                                        </a>
                                    </div>
                                </div>

                                <div class="col-lg-5 dn db_lg cl_h_search atc_opended_rs">
                                    <form action="search.php" method="get" class="h_search_frm js_frm_search pr" role="search">
                                        <div class="row no-gutters al_center">
                                            <div class="col-auto h_space_search"></div>
                                            <div class="frm_search_input pr oh col">
                                                <input id="input-ajax-search" class="h_search_ip js_iput_search" autocomplete="off" type="text" name="ara" value="<?= $_GET['ara']; ?>" placeholder="<?= $fonk->getDil("ürün ara"); ?> ...." value="" />
                                            </div>
                                            <div class="frm_search_cat col-auto">
                                                <button class="h_search_btn js_btn_search" type="submit">
                                                    <?= $fonk->getDil("Ara"); ?>
                                                </button>
                                            </div>
                                            <div class="ld_bar_search"></div>
                                        </div>
                                    </form>

                                    <div class="pr">
                                        <div class="mini_cart_content fixcl-scroll widget">
                                            <div class="fixcl-scroll-content product_list_widget" id="isNullSearch">
                                                <div class="ld_bar_search"></div>
                                                <div class="skeleton_wrap skeleton_js dn">
                                                    <div class="row mb__10 pb__10">
                                                        <div class="col-auto widget_img_pr">
                                                            <div class="skeleton_img"></div>
                                                        </div>
                                                        <div class="col widget_if_pr">
                                                            <div class="skeleton_txt1"></div>
                                                            <div class="skeleton_txt2"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb__10 pb__10">
                                                        <div class="col-auto widget_img_pr">
                                                            <div class="skeleton_img"></div>
                                                        </div>
                                                        <div class="col widget_if_pr">
                                                            <div class="skeleton_txt1"></div>
                                                            <div class="skeleton_txt2"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb__10 pb__10">
                                                        <div class="col-auto widget_img_pr">
                                                            <div class="skeleton_img"></div>
                                                        </div>
                                                        <div class="col widget_if_pr">
                                                            <div class="skeleton_txt1"></div>
                                                            <div class="skeleton_txt2"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb__10 pb__10">
                                                        <div class="col-auto widget_img_pr">
                                                            <div class="skeleton_img"></div>
                                                        </div>
                                                        <div class="col widget_if_pr">
                                                            <div class="skeleton_txt1"></div>
                                                            <div class="skeleton_txt2"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="js_prs_search">
                                                    <div id="result-ajax-search" class="hidden-sm hidden-xs">
                                                        <ul class="list-unstyled search-results"></ul>
                                                    </div>
                                                    <div class="search_h_break pa w__100"></div>
                                                    <div class="search_header__prs fwsb cd pa dn js_prs_search product_list_widget"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-2 col-md-5 col-5 tc tr_lg dn_false_1024">
                                    <div class="nt_action in_flex al_center cart_des_1">
                                        <?php if ($uyeVar) { ?>
                                            <div class="my-account ts__05 pr">
                                                <a class="cb chp db push_side" href="user-profile.php" data-id="#nt_login_canvas"><i class="iccl iccl-user"></i></a>
                                            </div>
                                            <a class="icon_like cb chp pr db_md js_link_wis" href="favori">
                                                <i class="iccl iccl-heart pr"> </i>
                                            </a>
                                        <?php } ?>
                                        <div class="icon_cart pr">
                                            <a class="push_side pr cb chp db" href="cart.php" data-id="#nt_cart_canvas">
                                                <i class="iccl iccl-cart pr"><span class="op__0 ts_op pa tcount bgb br__50 cw tc" id="sepet_adet">0</span></i>
                                            </a>
                                        </div>

                                        <div class="nt_action dbnone_lg in_flex al_center cart_des_1">
                                            <a class="icon_search push_side cb chp" data-id="#nt_search_canvas" href="#"><i class="iccl iccl-search"></i></a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>