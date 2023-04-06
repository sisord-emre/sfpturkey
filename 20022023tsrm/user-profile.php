<?php include('layouts/header.php'); ?>
<div id="nt_content">

    <!--shop banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw">Kullanıcı Bilgileri</h1>
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
                                    <label for="f-name">İsim</label>
                                    <input type="text" name="uyeFirmaAdi" id="uyeFirmaAdi" placeholder="İsim" value="">
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="f-name">Soyisim</label>
                                    <input type="text" name="uyeAdi" id="uyeAdi" placeholder="Soyisim" value="">
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="l-name">Email</label>
                                    <input type="email" name="mail" id="mail" placeholder="Email" value="">
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="f-name">Telefon</label>
                                    <input type="tel" name="uyeTel" id="uyeTel" placeholder="Telefon" value="">
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12">
                                    <label for="uyeTeslimatAdresId">Teslimat adresi
                                        <span style="float: right">
                                            <a href="javascript:AdresModal('','<?= $sabitB['sabitBilgiSiteUrl'] ?>user-profile');" style="color: #e45050;">Yeni Ekle</a>
                                        </span>
                                    </label>
                                    <select id="uyeTeslimatAdresId" name="uyeTeslimatAdresId" onchange="AdresBilgi('uyeTeslimatAdresId','teslimat_adres');" required>
                                        <option value="">Seçiniz</option>
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
                                    <label for="uyeFaturaAdresId">Fatura Adresi
                                        <span style="float: right">
                                            <a href="javascript:AdresModal('','<?= $sabitB['sabitBilgiSiteUrl'] ?>user-profile');" style="color: #e45050;">Yeni Ekle</a>
                                        </span>
                                    </label>
                                    <select id="uyeFaturaAdresId" name="uyeFaturaAdresId" onchange="AdresBilgi('uyeFaturaAdresId','fatura_adres');" required>
                                        <option value="">Seçiniz</option>
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
                                        <span>Şifre Değiştir</span>
                                    </label>
                                </p>

                                <p class="checkout-section__field col-lg-6 col-12" id="gizli" style="display:none;">
                                    <label for="address_01">Eski Şifre</label>
                                    <input type="password" id="uyeSifreOld" name="uyeSifreOld" placeholder="Eski Şifre" class="mb__20">
                                </p>
                                <p class="checkout-section__field col-lg-6 col-12" id="gizli2" style="display:none;">
                                    <label for="address_03">Yeni Şifre</label>
                                    <input type="password" id="uyeSifre" name="uyeSifre" placeholder="Yeni Şifre">
                                </p>

                                <p class="checkout-section__field col-12">
                                    <input type="hidden" name="formdan" value="1" />
                                    <button type="submit" class="button button_primary">Kaydet</button>
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
</script>