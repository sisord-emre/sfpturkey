<?php 
include('layouts/header.php');

if($_GET['exit']=="ok"){//oturumu kapatma
	unset($_SESSION['uyeSessionKey']);
	session_regenerate_id(true); //sessionId sıfırlamak için
	session_destroy();
	session_start();
	$fonk->yonlendir(index);
	exit;
}
?>

<div id="nt_content">

    <!--shop banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw"><?= $fonk->getDil("Hesabım"); ?></h1>
            </div>
        </div>
    </div>
    <!--end shop banner-->

    <!--page content-->
    <div class="container cb">
        <div class="row">
            <div class="col-12 col-md-5 login-form mt__60 mb-0 mb-md-5">
                <div id="CustomerLoginForm" class="kalles-wrap-form">
                    <h2><?= $fonk->getDil("Oturum Aç"); ?></h2>
                    <form id="loginpost" method="post" action="">
                        <p class="form">
                            <label for="uyeMail"><?= $fonk->getDil("E-Posta "); ?>
                                <span class="required">*</span>
                                <span id="uyeMailHata" class="span-color-red"></span>
                            </label>
                            <input type="email" class="form-control" name="uyeMail" id="uyeMail" required>
                        </p>

                        <p class="form">
                            <label for="uyeSifre"><?= $fonk->getDil("Parola"); ?> 
                                <span class="required">*</span>
                                <span id="uyeSifreHata" class="span-color-red"></span>
                            </label>
                            <input type="password" class="form-control" name="uyeSifre" id="uyeSifre" required>
                        </p>

                        <p class="form-row">
                            <?php if ($sabitB['sabitBilgiPublicRecaptcha'] != "" && $sabitB['sabitBilgiPrivateRecaptcha'] != "") { ?>
                                <div class="g-recaptcha" data-sitekey="<?= $sabitB['sabitBilgiPublicRecaptcha'] ?>"></div>
                            <?php } ?>
                        </p>

                        <p>
                            <a href="#RecoverPasswordForm" class="btn-change-login-form"><?= $fonk->getDil("Parolamı Unutum"); ?>?</a>
                        </p>

                        <input type="hidden" name="formdan" value="2" />
                        <input type="submit" value="Oturum Aç" class="btn btn-sm">
                    </form>
                </div>

                <div id="RecoverPasswordForm" class="kalles-wrap-form dn">
                    <h2></h2>
                    <p><?= $fonk->getDil("Sıfırlama bağlantısı için kayıtlı e-posta adresinizi yazınız"); ?>.</p>
                    <form id="resedpost" method="post" action="">
                        <p class="form">
                            <label for="uyeMail"><?= $fonk->getDil("E-Posta"); ?> 
                                <span class="required">*</span>
                                <span id="uyeMailHata" class="span-color-red"></span>
                            </label>
                            <input type="email" class="form-control" name="uyeMail" id="uyeMail" required>
                        </p>
                        <input type="hidden" name="formdan" value="3" />
                        <input type="submit" value="Şifre Yinele" class="btn btn-sm">
                        <a href="#CustomerLoginForm" class="button ml__15 btn-change-login-form"><?= $fonk->getDil("Vazgeç"); ?></a>
                    </form>
                </div>
            </div>

            <div class="col-12 col-md-7 login-form mt__60 mb__60">
                <div id="CustomerRegisterForm">
                    <h2><?= $fonk->getDil("Hesap Oluştur"); ?></h2>
                    <form id="formpost" method="post" action="" enctype="multipart/form-data">
                        <p class="form">
                            <label for="uyeAdi"><?= $fonk->getDil("Adınız"); ?> 
                                <span class="required">*</span>
                                <span id="uyeAdiHata" class="span-color-red"></span>
                            </label>
                            <input type="text" class="form-control" name="uyeAdi" id="uyeAdi" required>
                        </p>

                        <p class="form">
                            <label for="uyeSoyadi"><?= $fonk->getDil("Soyadınız"); ?> 
                                <span class="required">*</span>
                                <span id="uyeSoyadiHata" class="span-color-red"></span>
                            </label>
                            <input type="text" class="form-control" name="uyeSoyadi" id="uyeSoyadi" required>
                        </p>

                        <p class="form">
                            <label for="uyeMail"><?= $fonk->getDil("E-Posta"); ?> 
                                <span class="required">*</span>
                                <span id="uyeMailHata" class="span-color-red"></span>
                            </label>
                            <input type="email" class="form-control" name="uyeMail" id="uyeMailKayit" required>
                        </p>

                        <p class="form">
                            <label for="uyeTel"><?= $fonk->getDil("Telefon"); ?>
                                <span class="required">*</span>
                                <span id="uyeTelHata" class="span-color-red"></span>
                            </label>
                            <input type="tel" class="form-control" name="uyeTel" id="uyeTel" required>
                        </p>

                        <p class="form">
                            <label for="uyeFirmaAdi"><?= $fonk->getDil("Şirket Adı"); ?>
                                <span class="required">*</span>
                                <span id="uyeFirmaAdiHata" class="span-color-red"></span>
                            </label>
                            <input type="text" class="form-control" name="uyeFirmaAdi" id="uyeFirmaAdi" required>
                        </p>

                        <p class="form">
                            <label for="uyeTcVergiNo"><?= $fonk->getDil("Vergi Numarası"); ?> 
                                <span class="required">*</span>
                                <span id="uyeTcVergiNoHata" class="span-color-red"></span>
                            </label>
                            <input type="text" class="form-control" name="uyeTcVergiNo" id="uyeTcVergiNo" required>
                        </p>

                        <p class="form">
                            <label for="uyeVergiLevhasiDosya"><?= $fonk->getDil("Vergi Levhası"); ?> 
                            <span class="required">*</span>
                            ( <?= $fonk->getDil("Sadece jpeg, png, pdf uzantılı dosyalar yüklenebilir"); ?> )
                        </label><br>
                            <input type="file" class="form-control" id="uyeVergiLevhasiDosya" name="uyeVergiLevhasiDosya[]" accept=".png, .jpg, .jpeg, .pdf" required multiple>
                        </p>

                        <p class="form">
                            <label for="uyeTicaretSicilGazetesi"><?= $fonk->getDil("Ticaret sicil gazetesi"); ?> 
                            <span class="required">*</span>
                            ( <?= $fonk->getDil("Sadece jpeg, png, pdf uzantılı dosyalar yüklenebilir"); ?> )
                            </label><br>
                            <input type="file" class="form-control" id="uyeTicaretSicilGazetesi" name="uyeTicaretSicilGazetesi" accept=".png, .jpg, .jpeg, .pdf" required>
                        </p>

                        <p class="form">
                            <label for="uyeMukerrerImza"><?= $fonk->getDil("Mükerrer İmza"); ?> 
                            <span class="required">*</span>
                            ( <?= $fonk->getDil("Sadece jpeg, png, pdf uzantılı dosyalar yüklenebilir"); ?> )
                            </label><br>
                            <input type="file" class="form-control" id="uyeMukerrerImza" name="uyeMukerrerImza" accept=".png, .jpg, .jpeg, .pdf" required>
                        </p>

                        <p class="form">
                            <label for="uyeSifre"><?= $fonk->getDil("Parola"); ?> 
                                <span class="required">*</span>
                                <span id="uyeSifreHata" class="span-color-red"></span>
                            </label>
                            <input type="password" class="form-control" name="uyeSifre" id="uyeSifre" required>
                        </p>

                        <p class="form">
                            <label for="uyeSifreTekrar"><?= $fonk->getDil("Parola tekrar "); ?>
                                <span class="required">*</span>
                                <span id="uyeSifreTekrarHata" class="span-color-red"></span>
                            </label>
                            <input type="password" class="form-control" name="uyeSifreTekrar" id="uyeSifreTekrar" required>
                        </p>

                        <p class="form">
                            <input type="checkbox" id="gizlilikonay" name="gizlilikonay" value="1">
                            <label for="gizlilikonay">  <a href="page/uyelik-sozlesmesi" target="_blank">ÜYELİK SÖZLEŞMESİNİ OKUDUM, KABUL EDİYORUM</a></label>
                        </p>

                        <p class="form">
                            <input type="checkbox" id="kvkk" name="kvkk" value="1">
                            <label for="kvkk">  <a href="page/kvkk-metni" target="_blank">KVKK-AÇIK RIZA VE AYDINLATMA METNİNİ OKUDUM, KABUL EDİYORUM</a></label>
                        </p>


                        <p class="form-row">
                            <?php if ($sabitB['sabitBilgiPublicRecaptcha'] != "" && $sabitB['sabitBilgiPrivateRecaptcha'] != "") { ?>
                                <div class="g-recaptcha" data-sitekey="<?= $sabitB['sabitBilgiPublicRecaptcha'] ?>"></div>
                            <?php } ?>
                        </p>

                       
                        <input type="hidden" name="formdan" value="1" />
                        <input type="submit" value="Kayıt Ol" class="btn btn-sm">
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!--end page content-->

<?php include('layouts/footer.php') ?>

<script>
$("#formpost").submit(function(e) 
{
    e.preventDefault();
    var error = 0;
    var data = new FormData(this);
    var uyeAdi = data.get("uyeAdi");
    if (uyeAdi == "") {
        document.getElementById("uyeAdiHata").innerHTML = "Bu alan boş bırakılmamalıdır";
        error++;
    }
    var uyeSoyadi = data.get("uyeSoyadi");
    if (uyeSoyadi == "") {
        document.getElementById("uyeSoyadiHata").innerHTML = "Bu alan boş bırakılmamalıdır";
        error++;
    }
    var uyeMail = data.get("uyeMailKayit");
    if (uyeMail == "") {
        document.getElementById("uyeMailHata").innerHTML = "Bu alan boş bırakılmamalıdır";
        error++;
    }
    var uyeTel = data.get("uyeTel");
    if (uyeTel == "") {
        document.getElementById("uyeTelHata").innerHTML = "Bu alan boş bırakılmamalıdır";;
        error++;
    }
    var uyeFirmaAdi = data.get("uyeFirmaAdi");
    if (uyeFirmaAdi == "") {
        document.getElementById("uyeFirmaAdiHata").innerHTML = "Bu alan boş bırakılmamalıdır";;
        error++;
    }
    var uyeTcVergiNo = data.get("uyeTcVergiNo");
    if (uyeTcVergiNo == "") {
        document.getElementById("uyeTcVergiNoHata").innerHTML = "Bu alan boş bırakılmamalıdır";;
        error++;
    }
    var uyeSifre = data.get("uyeSifre");
    if (uyeSifre == "") {
        document.getElementById("uyeSifreHata").innerHTML = "Bu alan boş bırakılmamalıdır";;
        error++;
    }
    var uyeSifreTekrar = data.get("uyeSifreTekrar");
    if (uyeSifreTekrar == "") {
        document.getElementById("uyeSifreTekrarHata").innerHTML = "Bu alan boş bırakılmamalıdır";;
        error++;
    }
    if (error <= 0) 
    {
        $.ajax({
            type: "POST",
            url: "ajax/user-register.php",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(gelenSayfa) {
                if (gelenSayfa == "0") {
                    swal("Hata!", "Lütfen robot olmadığınızı doğrulayın", "error");
                } 
                else if (gelenSayfa == "1") {
                    swal("Hata!", "Bu vergi numarası zaten kayıtlı", "error");
                } 
                else if (gelenSayfa == "2") {
                    swal("Hata!", "Bir hata oluştu tekrar deneyin.", "error");
                } 
                else if (gelenSayfa == "3") {
                    swal("Başarılı", "Kayıt başarılı", "success");
                    document.getElementById("formpost").reset();
                } 
                else if (gelenSayfa == "4") {
                    swal("Hata!", "Parolalar uyuşmuyor", "error");
                }
                else if (gelenSayfa == "5") {
                    swal("Hata!", "Bu email zaten kayıtlı", "error");
                } 
                else if (gelenSayfa == "6") {
                    swal("Hata!", "Satış Sözleşmesini, Üyelik Sözleşmesini, Sipariş İptali, İade ve Değişim Prosedürünü onay veriniz.", "error");
                } 
                else if (gelenSayfa == "7") {
                    swal("Hata!", "KVKK aydınlatma metnine onay veriniz.", "error");
                } 
                else if (gelenSayfa == "8") {
                    swal("Hata!", "Ticaret Sicil Gazetesini lütfen yükleyiniz.", "error");
                } 
                else if (gelenSayfa == "9") {
                    swal("Hata!", "İmza Sirküsünü lütfen yükleyiniz.", "error");
                } 
                else if (gelenSayfa == "10") {
                    swal("Hata!", "Vergi Levhasını lütfen yükleyiniz.", "error");
                } 
            },
        });
    }
});

$("#loginpost").submit(function(e) 
{
    e.preventDefault();
    var error = 0;
    var data = new FormData(this);
    
    var uyeMail = data.get("uyeMail");
    if (uyeMail == "") {
        document.getElementById("uyeMailHata").innerHTML = "Bu alan boş bırakılmamalıdır";
        error++;
    }
    
    var uyeSifre = data.get("uyeSifre");
    if (uyeSifre == "") {
        document.getElementById("uyeSifreHata").innerHTML = "Bu alan boş bırakılmamalıdır";;
        error++;
    }
    if (error <= 0) 
    {
        $.ajax({
            type: "POST",
            url: "ajax/user-login.php",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(gelenSayfa) {
                if (gelenSayfa == "0") {
                    swal("Hata!", "Lütfen robot olmadığınızı doğrulayın", "error");
                } 
                else if (gelenSayfa == "1") {
                    swal("Hata!", "Sistemde kayıtlı böyle email bulunamadı.", "error");
                } 
                else if (gelenSayfa == "2") {
                    swal("Hata!", "Parola yanlış.", "error");
                } 
                else if (gelenSayfa == "3") {
                    //swal("Başarılı", "Giriş başarılı", "success")
                    //.then((value) => {
                        window.location.href = "<?= $sabitBilgiler['sabitBilgiSiteUrl']; ?>user-profile";
                    //});
                } 
                else if (gelenSayfa == "4") {
                    swal("Hata!", "Üyeliğiniz henüz onaylanmadı.", "error");
                } 
            },
        });
    }
});

$("#resedpost").submit(function(e) 
{
    e.preventDefault();
    var error = 0;
    var data = new FormData(this);
    
    var uyeMail = data.get("uyeMail");
    if (uyeMail == "") {
        document.getElementById("uyeMailHata").innerHTML = "Bu alan boş bırakılmamalıdır";
        error++;
    }
    
    if (error <= 0) 
    {
        $.ajax({
            type: "POST",
            url: "ajax/user-password-reset.php",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(gelenSayfa) {
                if (gelenSayfa == "0") {
                    swal("Hata!", "Lütfen robot olmadığınızı doğrulayın", "error");
                } 
                else if (gelenSayfa == "1") {
                    swal("Hata!", "Sistemde kayıtlı böyle email bulunamadı.", "error");
                } 
                else if (gelenSayfa == "2") {
                    swal("Hata!", "Mail gönderilemedi.", "error");
                } 
                else if (gelenSayfa == "3") {
                    swal("Başarılı", "Email adresine yeni şifre gönderildi.", "success")
                    .then((value) => {
                        window.location.href = "<?= $sabitBilgiler['sabitBilgiSiteUrl']; ?>account";
                    });
                } 
                else if (gelenSayfa == "4") {
                    swal("Hata!", "Hata oluştu tekrar deneyiniz.", "error");
                } 
            },
        });
    }
});
</script>