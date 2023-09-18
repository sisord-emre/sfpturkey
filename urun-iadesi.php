<?php include('layouts/header.php'); ?>
<div id="nt_content">

    <!--shop banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw"><?= $fonk->getDil("Ürün İadesi"); ?></h1>
            </div>
        </div>
    </div>
    <!--end shop banner-->

    <!--page content-->
    <div class="container cb">
        <div class="row">
            <div class="col-12 col-md-12 mt__60 mb__60">
                <div id="CustomerRegisterForm">
                    <h2><?= $fonk->getDil("İade Taleplerim"); ?></h2>
                    <p><?= $fonk->getDil("İade numarası istemek için lütfen aşağıdaki formu doldurunuz."); ?></p>
                    <form id="formpost" method="post" action="" enctype="multipart/form-data">
                        <p class="form">
                            <label for="iadeTalepAdi"><?= $fonk->getDil("Adınız"); ?>
                                <span class="required">*</span>
                                <span id="iadeTalepAdiHata" class="span-color-red"></span>
                            </label>
                            <input type="text" class="form-control" name="iadeTalepAdi" id="iadeTalepAdi" aria-required="true">
                        </p>

                        <p class="form">
                            <label for="iadeTalepSoyadi"><?= $fonk->getDil("Soyadınız"); ?>
                                <span class="required">*</span>
                                <span id="iadeTalepSoyadiHata" class="span-color-red"></span>
                            </label>
                            <input type="text" class="form-control" name="iadeTalepSoyadi" id="iadeTalepSoyadi" aria-required="true">
                        </p>

                        <p class="form">
                            <label for="iadeTalepEmail"><?= $fonk->getDil("E-Posta"); ?>
                                <span class="required">*</span>
                                <span id="iadeTalepEmailHata" class="span-color-red"></span>
                            </label>
                            <input type="email" class="form-control" name="iadeTalepEmail" id="iadeTalepEmail" aria-required="true">
                        </p>

                        <p class="form">
                            <label for="iadeTalepTelefon"><?= $fonk->getDil("Telefon"); ?>
                                <span class="required">*</span>
                                <span id="iadeTalepTelefonHata" class="span-color-red"></span>
                            </label>
                            <input type="tel" class="form-control" name="iadeTalepTelefon" id="iadeTalepTelefon" aria-required="true">
                        </p>

                        <p class="form">
                            <label for="iadeTalepSiparisNo"><?= $fonk->getDil("Sipariş No"); ?>
                                <span class="required">*</span>
                                <span id="iadeTalepSiparisNoHata" class="span-color-red"></span>
                            </label>
                            <input type="text" class="form-control" name="iadeTalepSiparisNo" id="iadeTalepSiparisNo" aria-required="true">
                        </p>

                        <p class="form">
                            <label for="iadeTalepSiparisTarihi"><?= $fonk->getDil("Sipariş Tarihi"); ?>
                                <span class="required">*</span>
                                <span id="iadeTalepSiparisTarihiHata" class="span-color-red"></span>
                            </label>
                            <input type="date" class="form-control" name="iadeTalepSiparisTarihi" id="iadeTalepSiparisTarihi" aria-required="true">
                        </p>

                        <p class="form">
                            <label for="iadeTalepUrunAdi"><?= $fonk->getDil("Ürün Adı"); ?>
                                <span class="required">*</span>
                                <span id="iadeTalepUrunAdiHata" class="span-color-red"></span>
                            </label>
                            <input type="text" class="form-control" name="iadeTalepUrunAdi" id="iadeTalepUrunAdi" aria-required="true">
                        </p>

                        <p class="form">
                            <label for="iadeTalepUrunKodu"><?= $fonk->getDil("Ürün Kodu"); ?>
                                <span class="required">*</span>
                                <span id="iadeTalepUrunKoduHata" class="span-color-red"></span>
                            </label>
                            <input type="text" class="form-control" name="iadeTalepUrunKodu" id="iadeTalepUrunKodu" aria-required="true">
                        </p>

                        <p class="form">
                            <label for="iadeTalepUrunAdet"><?= $fonk->getDil("Adet"); ?>
                                <span class="required">*</span>
                                <span id="iadeTalepUrunAdetHata" class="span-color-red"></span>
                            </label>
                            <input type="text" class="form-control" name="iadeTalepUrunAdet" id="iadeTalepUrunAdet" aria-required="true">
                        </p>

                        <p class="form">
                            <label for="iadeTalepIadeNeden"><?= $fonk->getDil("İade Nedeni"); ?>
                                <span class="required">*</span>
                                <span id="iadeTalepIadeNedenHata" class="span-color-red"></span>
                            </label><br>
                            <input type="checkbox" id="iadeTalepIadeNeden" name="iadeTalepIadeNeden" value="1">
                            <label for="iadeTalepIadeNeden"> Diğer (lütfen detay belirtin)</label><br>
                            <input type="checkbox" id="iadeTalepIadeNeden2" name="iadeTalepIadeNeden" value="2">
                            <label for="iadeTalepIadeNeden2"> Kargoda hasar görmüş</label><br>
                            <input type="checkbox" id="iadeTalepIadeNeden3" name="iadeTalepIadeNeden" value="3">
                            <label for="iadeTalepIadeNeden3"> Yanlış ürün gönderildi</label><br>
                        </p>

                        <p class="form">
                            <label for="iadeTalepUrunAcildimi"><?= $fonk->getDil("Ürün Açıldı mı?"); ?>
                                <span class="required">*</span>
                                <span id="iadeTalepUrunAcildimi" class="span-color-red"></span>
                            </label><br>
                            <input type="checkbox" id="iadeTalepUrunAcildimi" name="iadeTalepUrunAcildimi" value="1">
                            <label for="iadeTalepUrunAcildimi"> Evet</label><br>
                            <input type="checkbox" id="iadeTalepUrunAcildimi2" name="iadeTalepUrunAcildimi" value="2">
                            <label for="iadeTalepUrunAcildimi2"> Hayır</label><br>
                        </p>

                        <p class="form">
                            <label for="iadeTalepDetay"><?= $fonk->getDil("Arıza ya da diğer detaylar"); ?>
                                <span class="required">*</span>
                                <span id="iadeTalepDetay" class="span-color-red"></span>
                            </label>
                            <textarea id="iadeTalepDetay" name="iadeTalepDetay" rows="4" cols="50"></textarea>
                        </p>

                        <p class="form-row">
                            <?php if ($sabitB['sabitBilgiPublicRecaptcha'] != "" && $sabitB['sabitBilgiPrivateRecaptcha'] != "") { ?>
                        <div class="g-recaptcha" data-sitekey="<?= $sabitB['sabitBilgiPublicRecaptcha'] ?>"></div>
                    <?php } ?>
                    </p>

                    <input type="hidden" name="formdan" value="1" />
                    <input type="submit" value="<?= $fonk->getDil("Gönder"); ?>" class="btn btn-sm">
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<?php include('layouts/footer.php'); ?>

<script>
$("#formpost").submit(function(e) 
{
    e.preventDefault();
    var data = new FormData(this);

    $.ajax({
        type: "POST",
        url: "ajax/uruniade.php",
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function(gelenSayfa) {
            if (gelenSayfa == "0") {
                swal("Hata!", "Lütfen robot olmadığınızı doğrulayın", "error");
            } 
            else if (gelenSayfa == "2") {
                swal("Hata!", "Bir hata oluştu tekrar deneyin.", "error");
            } 
            else if (gelenSayfa == "3") {
                swal("Başarılı", "İşlem başarılı", "success");
                document.getElementById("formpost").reset();
            } 
        },
    });
    
});
</script>