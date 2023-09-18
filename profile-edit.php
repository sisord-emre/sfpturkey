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
                <h1 class="mb__5 cw"><?= $fonk->getDil("Bilgilerimi Güncelle"); ?></h1>
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
                                    <label for="uyeAdi"><?= $fonk->getDil("İsim"); ?></label>
                                    <input type="text" name="uyeAdi" id="uyeAdi" value="<?= $uye['uyeAdi'] ?>">
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeSoyadi"><?= $fonk->getDil("Soyisim"); ?></label>
                                    <input type="text" name="uyeSoyadi" id="uyeSoyadi" value="<?= $uye['uyeSoyadi'] ?>">
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeMail"><?= $fonk->getDil("Email"); ?></label>
                                    <input type="text" name="uyeMail" id="uyeMail" value="<?= $uye['uyeMail'] ?>">
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeTel"><?= $fonk->getDil("Telefon"); ?></label>
                                    <input type="tel" name="uyeTel" id="uyeTel" value="<?= $uye['uyeTel'] ?>">
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeFirmaAdi"><?= $fonk->getDil("Şirket Adı"); ?>
                                        <span id="uyeFirmaAdiHata" class="span-color-red"></span>
                                    </label>
                                    <input type="text" name="uyeFirmaAdi" id="uyeFirmaAdi" value="<?= $uye['uyeFirmaAdi'] ?>">
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeTcVergiNo"><?= $fonk->getDil("Vergi Numarası"); ?> 
                                        <span id="uyeTcVergiNoHata" class="span-color-red"></span>
                                    </label>
                                    <input type="text" name="uyeTcVergiNo" id="uyeTcVergiNo" value="<?= $uye['uyeTcVergiNo'] ?>">
                                </p>
                             
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeVergiLevhasiDosya"><?= $fonk->getDil("Vergi Levhası"); ?> 
                                    ( <?= $fonk->getDil("Sadece jpeg, png, pdf uzantılı dosyalar yüklenebilir"); ?> )
                                    </label>
                                    <input type="file" id="uyeVergiLevhasiDosya" name="uyeVergiLevhasiDosya[]" accept=".png, .jpg, .jpeg, .pdf" multiple>
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeTicaretSicilGazetesi"><?= $fonk->getDil("Sicil gazetesi"); ?> 
                                    ( <?= $fonk->getDil("Sadece jpeg, png, pdf uzantılı dosyalar yüklenebilir"); ?> )
                                    </label>
                                    <input type="file" id="uyeTicaretSicilGazetesi" name="uyeTicaretSicilGazetesi" accept=".png, .jpg, .jpeg, .pdf">
                                </p>

                                <p class="checkout-section__field col-lg-12 col-12">
                                    <label for="uyeMukerrerImza"><?= $fonk->getDil("Mükerrer İmza"); ?> 
                                    ( <?= $fonk->getDil("Sadece jpeg, png, pdf uzantılı dosyalar yüklenebilir"); ?> )
                                    </label>
                                    <input type="file" id="uyeMukerrerImza" name="uyeMukerrerImza" accept=".png, .jpg, .jpeg, .pdf">
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeTeslimatAdresId"><?= $fonk->getDil("Teslimat adresi"); ?>
                                        <span style="float: right">
                                            <a href="javascript:AdresModal('','<?= $sabitB['sabitBilgiSiteUrl'] ?>profile-edit');" style="color: #e45050;">
                                                <?= $fonk->getDil("Yeni Ekle"); ?>
                                            </a>
                                        </span>
                                    </label>
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
                                    <label for="uyeFaturaAdresId"><?= $fonk->getDil("Fatura Adresi"); ?>
                                        <span style="float: right">
                                            <a href="javascript:AdresModal('','<?= $sabitB['sabitBilgiSiteUrl'] ?>profile-edit');" style="color: #e45050;">
                                                <?= $fonk->getDil("Yeni Ekle"); ?>
                                            </a>
                                        </span>
                                    </label>
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
                                </div>

                                <div class="checkout-section__field col-lg-6 col-12 order-review__wrapper" id="fatura_adres">
                                </div>

                                <p class="checkout-section__field col-lg-12 col-12">
                                    <label class="checkout-payment__confirm-terms-and-conditions">
                                        <input type="checkbox" name="change" id="change" onclick="myChange()">
                                        <span><?= $fonk->getDil("Şifre Değiştir"); ?></span>
                                    </label>
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12" id="gizli" style="display:none;">
                                    <label for="address_01"><?= $fonk->getDil("Eski Şifre"); ?></label>
                                    <input type="password" id="uyeSifreOld" name="uyeSifreOld" placeholder="Eski Şifre" class="mb__20">
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12" id="gizli2" style="display:none;">
                                    <label for="address_03"><?= $fonk->getDil("Yeni Şifre"); ?></label>
                                    <input type="password" id="uyeSifre" name="uyeSifre" placeholder="Yeni Şifre">
                                </p>

                                <p class="checkout-section__field col-12">
                                    <input type="hidden" name="formdan" value="1" />
                                    <button type="submit" class="button button_primary"><?= $fonk->getDil("Güncelle"); ?></button>
                                </p>
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
    function myChange() {
        var checkBox = document.getElementById("change");
        var text = document.getElementById("gizli");
        var text2 = document.getElementById("gizli2");

        if (checkBox.checked == true) {
            text.style.display = "block";
            text2.style.display = "block";
        } else {
            text.style.display = "none";
            text2.style.display = "none";
        }
    }


    $("#formpost").submit(function(e) {
        e.preventDefault(); //submit postu kesyoruz
        var data = new FormData(this);

        $.ajax({
            type: "POST",
            url: "ajax/profileEdit.php",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(gelenSayfa) {
                if (gelenSayfa == "1") {
                    swal("<?= $fonk->getDil('Hata'); ?>", "<?= $fonk->getDil('Geçerli şifre yanlış'); ?>", "error");
                } 
                else if (gelenSayfa == "2") {
                    swal("<?= $fonk->getDil('Başarılı'); ?>", "<?= $fonk->getDil('Başarılı'); ?>", "success");
                } 
                else if (gelenSayfa == "3") {
                    swal("<?= $fonk->getDil('Hata'); ?>", "<?= $fonk->getDil('Bir hata oluştu tekrar deneyin'); ?>", "error");
                }
            },
        });
    });
</script>