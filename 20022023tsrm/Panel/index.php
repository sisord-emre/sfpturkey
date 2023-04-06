<?php
include("System/Config.php");
if (isset($_GET["islemDilKodu"])) {
    if (strlen($_GET["islemDilKodu"]) > 5) {
        exit;
    }
    $seciliDil = $db->get("Diller", "*", [
        "dilKodu" => $_GET["islemDilKodu"]
    ]);
    $_SESSION["islemDilId"] = $seciliDil["dilId"];
    $_SESSION["islemDilKodu"] = $seciliDil["dilKodu"];
    $_SESSION["islemDilGorsel"] = $seciliDil["dilGorsel"];
    $_SESSION["islemDilAdi"] = $seciliDil["dilAdi"];
}
?>
<!DOCTYPE html>
<html class="loading" lang="tr" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="<?= $sabitB['sabitBilgiDescription'] ?>">
    <meta name="keywords" content="<?= $sabitB['sabitBilgiKeywords'] ?>">
    <meta name="author" content="<?= $sabitB['sabitBilgiLisansFirmaAdi'] ?>">
    <title><?= $fonk->getPDil("Panel") ?> - <?= $sabitB['sabitBilgiTitle'] ?></title>
    <link rel="apple-touch-icon" href="Images/Ayarlar/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="Images/Ayarlar/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/tables/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/ui/prism.min.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/forms/icheck/custom.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/forms/selects/select2.min.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/forms/selects/selectivity-full.min.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/forms/selects/jquery.selectBoxIt.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/pickers/daterange/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/charts/c3.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/components.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/plugins/forms/extended/form-extended.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/plugins/forms/selectivity/selectivity.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/plugins/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/plugins/forms/selectBoxIt/selectBoxIt.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/plugins/forms/wizard.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/plugins/pickers/daterange/daterange.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/plugins/forms/checkboxes-radios.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/plugins/charts/c3-chart.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/extensions/toastr.css">
    <!-- END: Page CSS-->

    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/forms/toggle/bootstrap-switch.min.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/forms/toggle/switchery.min.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/plugins/forms/switch.css">

    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/forms/selects/selectize.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/forms/selects/selectize.default.css">
    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/plugins/forms/selectize/selectize.css">

    <link rel="stylesheet" type="text/css" href="Assets/app-assets/vendors/css/extensions/dragula.min.css">

    <link rel="stylesheet" type="text/css" href="Assets/app-assets/css/plugins/images/cropper/cropper.css">

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="Assets/assets/css/style.css">
    <!-- END: Custom CSS-->

    <link rel="stylesheet" type="text/css" href="Assets/myStyle.css">

    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light bg-info navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#" id="mobilmenu"><i class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item"><a class="navbar-brand" href="./" onclick='sessionStorage.setItem("menuId",""); sessionStorage.setItem("sayfa",""); sessionStorage.setItem("duzenleId",""); sessionStorage.setItem("dPage",""); sessionStorage.setItem("dSearch",""); sessionStorage.setItem("dLink",""); sessionStorage.setItem("editId",""); sessionStorage.setItem("orderDt","");'><img class="brand-logo" alt="logo" src="Images/Ayarlar/logo.png">
                            <h3 class="brand-text"> <?= $sabitB['sabitBilgiSiteAdi'] ?></h3>
                        </a></li>
                    <li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-search" href="<?= $sabitB['sabitBilgiSiteUrl'] ?>" target="_blank" title="Siteye Git"><i class="ficon ft-layout"></i></a></li>
                    </ul>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-language nav-item">
                            <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 1.5rem 1rem 0.5rem 1rem;">
                                <?= "$: " . $sabitB['sabitBilgiDolar'] . " , " . "€: " . $sabitB['sabitBilgiEuro'] ?>
                                <br />
                                <?= $fonk->sqlToDateTimeSaniyesiz($sabitB["sabitBilgiOtoKurGuncelleTarihi"]) ?>
                            </a>
                        </li>
                        <li class="dropdown dropdown-language nav-item">
                            <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?= $fonk->getPDil("İşlem Yapılan Dil") ?>:
                            </a>
                        </li>
                        <li class="dropdown dropdown-language nav-item">
                            <?php
                            if ($_SESSION["islemDilKodu"] != "") {
                            ?>
                                <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="Images/Ayarlar/<?= $_SESSION["islemDilGorsel"] ?>" style="width:24px"> <span class="selected-language"><?= $_SESSION["islemDilAdi"] ?></span>
                                </a>
                            <?php } else { ?>
                                <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="selected-language"><?= $fonk->getPDil("Tümü") ?></span>
                                </a>
                            <?php } ?>
                            <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                                <?php
                                if ($_SESSION["islemDilKodu"] != "") {
                                ?>
                                    <a class="dropdown-item" href="javascript:window.location.href = '?islemDilKodu=';"><?= $fonk->getPDil("Tümü") ?></a>
                                <?php
                                }
                                $islemDiller = $db->select("Diller", "*", [
                                    "dilKodu[!]" => $_SESSION["islemDilKodu"]
                                ]);
                                foreach ($islemDiller as $key => $value) {
                                ?>
                                    <a class="dropdown-item" href="javascript:window.location.href = '?islemDilKodu=<?= $value["dilKodu"] ?>';"><img src="Images/Ayarlar/<?= $value["dilGorsel"] ?>" style="width:24px"> <?= $value["dilAdi"] ?></a>
                                <?php } ?>
                            </div>
                        </li>
                        <?php
                        if ($sabitB["sabitBilgiPanelDilGosterim"] == 1) {
                        ?>
                            <li class="dropdown dropdown-language nav-item">
                                <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="./Images/Ayarlar/<?= $_SESSION["panelDilGorsel"] ?>" style="width: 23px;"> <span class="selected-language"><?= $_SESSION["panelDilAdi"] ?></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                                    <?php
                                    $diller = $db->select("Diller", "*", [
                                        "dilId[!]" => $_SESSION["panelDilId"],
                                        "dilPanelDurumu" =>    1
                                    ]);
                                    foreach ($diller as $dil) {
                                    ?>
                                        <a class="dropdown-item pointer" onclick="PanelDilSecim('<?= $dil["dilKodu"] ?>');"><img src="./Images/Ayarlar/<?= $dil["dilGorsel"] ?>" style="width: 23px;"> <?= $dil["dilAdi"] ?></a>
                                    <?php } ?>
                                </div>
                            </li>
                        <?php } ?>
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"><span class="mr-1 user-name text-bold-700"><?= $kulBilgi['kullaniciAdSoyad'] ?></span><span class="avatar avatar-online"><img src="Images/Ayarlar/profil.png" alt="avatar"><i></i></span></a>
                            <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" onclick="SayfaGetir('','Kullanicilar/profilDuzenle.php')"><i class="ft-user"></i> <?= $fonk->getPDil("Profili Düzenle") ?></a><a class="dropdown-item">
                                    <div class="dropdown-divider"></div><a class="dropdown-item" href="login.php?exit=ok"><i class="ft-power"></i> <?= $fonk->getPDil("Çıkış Yap") ?></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END: Header--

  <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion  menu-shadow " data-scroll-to-active="true">
        <div class="main-menu-content">
            <ul class="navigation" style="margin: 1rem;margin-bottom: 0.5rem;">
                <li class=" nav-item">
                    <input type="text" id="menuAra" class="form-control border-primary" placeholder="<?= $fonk->getPDil("Menü Ara") ?>..." style="width: 100%;" autocomplete="off">
                </li>
            </ul>
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" style="margin-bottom: 3rem;">
                <?php
                ///Ana Menüler
                $menuler = $db->select("Menuler", "*", [
                    "menuUstMenuId" => 0,
                    "menuGorunurluk" =>    1,
                    "menuOzelGorunuruk" =>    1,
                    "ORDER" => [
                        "menuSirasi" => "ASC"
                    ]
                ]);
                foreach ($menuler as $menu) {
                    $menuYetki = false;
                    $listelemeYetki = false;
                    $eklemeYetki = false;
                    for ($i = 0; $i < Count($kullaniciYetkiler); $i++) { //kullanıcının yetkilerini sorguluyoruz
                        $kullaniciYetki = json_decode($kullaniciYetkiler[$i], true);
                        if ($kullaniciYetki['menuYetkiID'] == $menu['menuId']) {
                            $menuYetki = true;
                            if ($kullaniciYetki['listeleme'] == "on" || $kullaniciYetki['silme'] == "on" || $kullaniciYetki['duzenleme'] == "on") {
                                $listelemeYetki = true;
                            } //listeleme, duzenleme yada silmek için menu gözükme
                            if ($kullaniciYetki['ekleme'] == "on") {
                                $eklemeYetki = true;
                            } //ekleme için menu gözükme
                        }
                    }
                    if ($eklemeYetki == false && $listelemeYetki == false) {
                        $menuYetki = false;
                    } //eğer iki yetkide yoksa başlıkı hiç gösterme
                    if ($menuYetki) {
                ?>
                        <li class=" nav-item <?php if ($menu['menuId'] == $_GET["sayfaBilgi"]) {
                                                    echo "open";
                                                } ?>"><a href="<?= $menu['menuSayfa'] ?>"><i class="la <?= $menu['menuIcon'] ?>"></i><span class="menu-title" data-i18n="nav.dash.main"><?= $fonk->getPDil($menu['menuAdi']) ?></span></a>
                            <ul class="menu-content">
                                <?php
                                ///Alt Menüler
                                $altMenuler = $db->select("Menuler", "*", [
                                    "menuUstMenuId" => $menu['menuId'],
                                    "menuGorunurluk" =>    1,
                                    "menuOzelGorunuruk" =>    1,
                                    "ORDER" => [
                                        "menuSirasi" => "ASC"
                                    ]
                                ]);
                                foreach ($altMenuler as $altMenu) {
                                    if (($altMenu['menuTipi'] == 1 && $eklemeYetki) || ($altMenu['menuTipi'] == 2 && $listelemeYetki) || ($altMenu['menuTipi'] == 3)) {
                                ?>
                                        <li>
                                            <a class="menu-item" oncontextmenu="return false;" onclick="SayfaGetir('<?= $altMenu['menuUstMenuId'] ?>','<?= $altMenu['menuSayfa'] ?>')"><i></i><span data-i18n="nav.dash.ecommerce"><?= $fonk->getPDil($altMenu['menuAdi']) ?></span></a>
                                        </li>
                                <?php }
                                } ?>
                            </ul>
                        </li>
                <?php }
                }  ?>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row mb-1" style="margin-bottom: 0rem !important;">
                <div class="content-header-left col-md-6 col-8 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./" onclick='sessionStorage.setItem("menuId",""); sessionStorage.setItem("sayfa",""); sessionStorage.setItem("duzenleId",""); sessionStorage.setItem("dPage",""); sessionStorage.setItem("dSearch",""); sessionStorage.setItem("dLink",""); sessionStorage.setItem("editId",""); sessionStorage.setItem("orderDt","");'><?= $fonk->getPDil("Anasayfa") ?></a></li>
                                <li><span id="ustYazi"></span></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="content-header-right col-md-6 col-4 mb-2" style="margin-bottom: 1.0rem !important;">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb" style="float: right;">
                                <li class="breadcrumb-item">
                                    <button type="button" onclick="geriback()" class="btn btn-outline-primary mr-1" style="padding: 0.50rem 0.70rem;"><i class="la la-undo"></i></button>
                                    <button type="button" class="btn btn-outline-primary" style="padding: 0.50rem 0.70rem;" id="yenileButton" onclick="SayfaYenile()"><i class="la la-refresh"></i></button>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body" id="Sayfalar">
                <!-- Sayfa İçeriği -->

                <!-- /Sayfa İçeriği -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer fixed-bottom footer-dark navbar-border navbar-shadow">
        <span class="float-md-center d-md-inline-block d-none d-md-block d-lg-block d-xl-block" style="position: absolute;text-align: center;width: 100%;z-index: -5;" id="time"></span>
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
            <span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2022 <a href="<?= $sabitB['sabitBilgiLisansFirmaLink'] ?>" target="_blank"><?= $sabitB['sabitBilgiLisansFirmaAdi'] ?></a></span>
            <span class="float-md-right d-none d-lg-block"><?= $sabitB['sabitBilgiLisansYapilanFirmaAdi'] ?> <i class="ft-heart pink"></i><span id="scroll-top"></span></span>
        </p>
    </footer>
    <!-- END: Footer-->

    <script src="Assets/app-assets/vendors/js/vendors.min.js"></script>
    <script src="Assets/app-assets/js/core/app-menu.js"></script>
    <script src="Assets/app-assets/js/core/app.js"></script>
    <script src="Assets/app-assets/vendors/js/extensions/toastr.min.js"></script>
    <script src="Assets/app-assets/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="Assets/app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js"></script>
    <script src="Assets/app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js"></script>
    <script src="Assets/app-assets/vendors/js/tables/jszip.min.js"></script>
    <script src="Assets/app-assets/vendors/js/tables/pdfmake.min.js"></script>
    <script src="Assets/app-assets/vendors/js/tables/vfs_fonts.js"></script>
    <script src="Assets/app-assets/vendors/js/tables/buttons.html5.min.js"></script>
    <script src="Assets/app-assets/vendors/js/tables/buttons.print.min.js"></script>
    <script src="Assets/app-assets/vendors/js/tables/buttons.colVis.min.js"></script>
    <script src="Assets/app-assets/js/scripts/tables/datatables-extensions/datatable-button/datatable-html5.js"></script>
    <script src="Assets/index.js"></script>
</body>
<!-- END: Body-->
<script type="text/javascript">
    $(document).ready(function() {
        var table;
        <?php if ($panelUrl == "/") {
            echo "history.replaceState('', '', '/');";
        } else {
            echo "history.replaceState('', '', '/" . $panelUrl . "/');";
        } ?>
        panelDil = JSON.parse('<?= str_replace(array(";", "'", "\""), array("\;", "\'", "\""), json_encode($_SESSION["panelDil"], JSON_UNESCAPED_UNICODE)) ?>');
    });
</script>

</html>