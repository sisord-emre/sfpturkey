<?php 
include('layouts/header.php'); 

if ($_SESSION['uyeSessionKey'] == "") {
    echo '<script> window.location.href="'.$sabitB['sabitBilgiSiteUrl'].'account";</script>';
    exit;
}
?>
<div id="nt_content">

    <!--shop banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw"><?= $fonk->getDil("Kullanıcı Bilgileri"); ?></h1>
            </div>
        </div>
    </div>
    <!--end shop banner-->

    <!--page content-->
    <div class="container mt__40 mb__40 cb">
        <div class="row">
            <div class="col-12 col-md-3">
                <?php include 'layouts/left-menu.php'; ?>
            </div>

            <div class="col-12 col-md-9">
                <div class="kalles-term-exp mb__30">
                    <div class="checkout-section">
                        <form method="post" action="" id="formpost" name="profileFormu" class="comment-form">
                            <div class="row">
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="f-name"><?= $fonk->getDil("İsim"); ?></label>
                                    <?= $uye['uyeAdi'] ?>
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="f-name"><?= $fonk->getDil("Soyisim"); ?></label>
                                    <?= $uye['uyeSoyadi'] ?>
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="l-name"><?= $fonk->getDil("Email"); ?></label>
                                    <?= $uye['uyeMail'] ?>
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="f-name"><?= $fonk->getDil("Telefon"); ?></label>
                                    <?= $uye['uyeTel'] ?>
                                </p>
                             
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="f-name"><?= $fonk->getDil("Ticaret Sicil Gazetesi"); ?></label>
                                   
									<a href="<?=$uye['uyeTicaretSicilGazetesiBaseUrl']?><?=$uye['uyeTicaretSicilGazetesi']?>" class="btn btn-info btn-sm" style="padding:0.1rem 0.3rem;" target="_blank"><i class="la la-search"></i></a>
									
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="f-name"><?= $fonk->getDil("Mükerrer İmza"); ?></label>
                                    
									<a href="<?=$uye['uyeMukerrerImzaBaseUrl']?><?=$uye['uyeMukerrerImza']?>" class="btn btn-info btn-sm" style="padding:0.1rem 0.3rem;" target="_blank"><i class="la la-search"></i></a>
									
                                </p>
                                
                                <p class="checkout-section__field col-lg-12 col-12">
                                    <label for="f-name"><?= $fonk->getDil("Vergi Levhası"); ?></label>
                                    <?php
                                    $vergiLevhasi = $db->select("UyeVergiLevhasi","*",[
                                        "uyeVergiLevhasiUyeId" => $uye['uyeId']
                                    ]);
                                    foreach ($vergiLevhasi as $value) {?>
                                        
									    <a href="<?=$value['uyeVergiLevhasiBaseUrl']?><?=$value['uyeVergiLevhasiDosya']?>" class="btn btn-info btn-sm" style="padding:0.1rem 0.3rem;" target="_blank"><i class="la la-search"></i></a>
									
                                    <?php } ?>
                                </p>


                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeTeslimatAdresId"><?= $fonk->getDil("Teslimat adresi"); ?> </label>
                                    <select id="uyeTeslimatAdresId" name="uyeTeslimatAdresId" onchange="AdresBilgi('uyeTeslimatAdresId','teslimat_adres');">
                                        <option value=""><?= $fonk->getDil("Seçiniz"); ?></option>
                                        <?php
                                        $uyeAdresler = $db->select("UyeAdresler", [
                                            "[<]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
                                            "[<]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
                                            "[<]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"]
                                        ], "*", [
                                            "uyeAdresUyeId" => $uye["uyeId"],
                                        ]);
                                        foreach ($uyeAdresler as $key => $value) {
                                        ?>
                                            <option value="<?= $value["uyeAdresId"] ?>"><?= $value["uyeAdresAdi"] ?></option>
                                        <?php } ?>
                                    </select>
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeFaturaAdresId"><?= $fonk->getDil("Fatura Adresi"); ?></label>
                                    <select id="uyeFaturaAdresId" name="uyeFaturaAdresId" onchange="AdresBilgi('uyeFaturaAdresId','fatura_adres');">
                                        <option value=""><?= $fonk->getDil("Seçiniz"); ?></option>
                                        <?php
                                        $uyeAdresler = $db->select("UyeAdresler", [
                                            "[<]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
                                            "[<]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
                                            "[<]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"]
                                        ], "*", [
                                            "uyeAdresUyeId" => $uye["uyeId"],
                                        ]);
                                        foreach ($uyeAdresler as $key => $value) {
                                        ?>
                                            <option value="<?= $value["uyeAdresId"] ?>"><?= $value["uyeAdresAdi"] ?></option>
                                        <?php } ?>
                                    </select>
                                </p>

                                <div class="checkout-section__field col-lg-6 col-12 order-review__wrapper" id="teslimat_adres">
                                    <?php 
                                    $uyeAdres = $db->get("UyeAdresler", [
                                        "[<]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
                                        "[<]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
                                        "[<]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"]
                                    ], "*", [
                                        "uyeAdresUyeId" => $uye["uyeId"]
                                    ]);
                                    ?>
                                    <table class="checkout-review-order-table" style="margin: unset;">
                                        <thead>
                                            <tr>
                                                <th class="product-name" colspan="2"><?= $uyeAdres["uyeAdresAdi"] ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="cart_item">
                                                <td class="product-name"><strong><?= $uyeAdres["ulkeAdi"] . "/" . $uyeAdres["ilAdi"] . "/" . $uyeAdres["ilceAdi"] ?></strong></td>
                                            </tr>
                                            <tr class="cart_item">
                                                <td class="product-name"><?= $uyeAdres["uyeAdresBilgi"] ?></td>
                                            </tr>
                                            <tr class="cart_item">
                                                <td class="product-name"><strong><?= $fonk->sqlToDateTime($uyeAdres["uyeAdresKayitTarihi"]) ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="checkout-section__field col-lg-6 col-12 order-review__wrapper" id="fatura_adres">
                                    <?php 
                                    $uyeAdres = $db->get("UyeAdresler", [
                                        "[<]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
                                        "[<]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
                                        "[<]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"]
                                    ], "*", [
                                        "uyeAdresUyeId" => $uye["uyeId"]
                                    ]);
                                    ?>
                                    <table class="checkout-review-order-table" style="margin: unset;">
                                        <thead>
                                            <tr>
                                                <th class="product-name" colspan="2"><?= $uyeAdres["uyeAdresAdi"] ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="cart_item">
                                                <td class="product-name"><strong><?= $uyeAdres["ulkeAdi"] . "/" . $uyeAdres["ilAdi"] . "/" . $uyeAdres["ilceAdi"] ?></strong></td>
                                            </tr>
                                            <tr class="cart_item">
                                                <td class="product-name"><?= $uyeAdres["uyeAdresBilgi"] ?></td>
                                            </tr>
                                            <tr class="cart_item">
                                                <td class="product-name"><strong><?= $fonk->sqlToDateTime($uyeAdres["uyeAdresKayitTarihi"]) ?></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--end page content-->
</div>

<?php include('layouts/footer.php') ?>
<script>
    <?php if($_SESSION['isProduct'] == ""){?>
        <?php
        foreach ($enSonSiparis as $key => $value) { 
            if(count($enSonSiparis) > 0){
                $_SESSION['SiparisKodu'] = $value["siparisKodu"];
        ?>
            SepetTekrarKayit(<?=$value["siparisIcerikUrunVaryantDilBilgiId"]?>, "0", <?=$value["siparisIcerikAdet"]?>);
        <?php } } ?>
    <?php } $_SESSION['isProduct'] = "1";?>
</script>