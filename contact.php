<?php
include('layouts/header.php');
?>
<div id="nt_content">
    <!--shop banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw">
                    <?= $fonk->getDil("İletişim") ?>
                </h1>
            </div>
        </div>
    </div>
    <!--end shop banner-->

    <!--page content-->
    <div class="kalles-section container mb__50 cb">
        <div class="row fl_center">
            <div class="contact-form col-12 col-md-6 order-1 order-md-0">
                <form id="contactpost" class="contact-form" method="post" action="" enctype="multipart/form-data">
                    <h3 class="mb__20 mt__40"><?= $fonk->getDil("İLETİŞİM"); ?></h3>
                    <p>
                        <label for="iletisimFormAdSoyad"><?= $fonk->getDil("İsim (zorunlu)"); ?></label>
                        <input required="required" type="text" id="iletisimFormAdSoyad" name="iletisimFormAdSoyad">
                    </p>
                    <p>
                        <label for="iletisimFormEmail"><?= $fonk->getDil("Email (zorunlu)"); ?></label>
                        <input required="required" type="email" id="iletisimFormEmail" name="iletisimFormEmail">
                    </p>
                    <p>
                        <label for="iletisimFormTel"><?= $fonk->getDil("Telefon"); ?></label>
                        <input type="tel" id="iletisimFormTel" name="iletisimFormTel" pattern="[0-9\-]*">
                    </p>
                    <p>
                        <label for="iletisimFormMesaj"><?= $fonk->getDil("Mesaj (zorunlu)"); ?></label>
                        <textarea rows="10" id="iletisimFormMesaj" name="iletisimFormMesaj" required="required"></textarea>
                    </p>
                    <input type="hidden" name="gelen" value="1">
                    <button type="submit" id="submitBtn" class="button button_primary w__100"><?= $fonk->getDil("Gönder"); ?></button>
                </form>
            </div>

            <div class="contact-content col-12 col-md-6 order-0 order-md-1">
                <h3 class="mb__20 mt__40"><?= $fonk->getDil("İLETİŞİM BİLGİLERİ"); ?></h3>
                <p>
                    <?= $fonk->getDil("Müşteri hizmetlerimiz, ürünlerimiz, web sitemiz veya bizimle paylaşmak istediğiniz herhangi bir konuda sizden haber almayı çok seviyoruz. Yorumlarınız ve önerileriniz takdir edilecektir. Lütfen aşağıdaki formu doldurunuz."); ?>
                </p>
                <p class="mb__5 d-flex"><i class="las la-home fs__20 mr__10 text-primary"></i> <?= $sabitB["sabitBilgiAdres"]; ?></p>
                <p class="mb__5 d-flex"><i class="las la-phone fs__20 mr__10 text-primary"></i><?= $siteTel[0]; ?> (<?= $fonk->getDil("SFP Satış"); ?>)</p>
                <p class="mb__5 d-flex"><i class="las la-phone fs__20 mr__10 text-primary"></i><?= $siteTel[1]; ?> (<?= $fonk->getDil("SFP Teknik Destek"); ?>)</p>
                <p class="mb__5 d-flex"><i class="las la-phone fs__20 mr__10 text-primary"></i><?= $siteTel[2]; ?> (<?= $fonk->getDil("SFP Finans Operasyon"); ?>)</p>
                <p class="mb__5 d-flex"><i class="las la-envelope fs__20 mr__10 text-primary"></i>
                    <a href="mailto:<?= $gondericiMail[1]; ?>">
                        <?= $fonk->getDil("Satış için tıklayınız."); ?>
                    </a>
                </p>
                <p class="mb__5 d-flex"><i class="las la-envelope fs__20 mr__10 text-primary"></i>
                    <a href="mailto:<?= $gondericiMail[2]; ?>">
                        <?= $fonk->getDil("Teknik destek için tıklayınız."); ?>
                    </a>
                </p>
                <p class="mb__5 d-flex"><i class="las la-envelope fs__20 mr__10 text-primary"></i>
                    <a href="mailto:<?= $gondericiMail[3]; ?>">
                        <?= $fonk->getDil("Finans Operasyonu için tıklayınız."); ?>
                    </a>
                </p>

                <p class="mb__5 d-flex"><i class="las la-clock fs__20 mr__10 text-primary"></i> <?= $fonk->getDil("Hafta içi 09:00-18:00"); ?> </p>
            </div>
        </div>
    </div>
    <!--end page content-->
</div>

<?php include('layouts/footer.php') ?>

<script>
    $("#contactpost").submit(function(e) {
        const submitButton = document.getElementById("submitBtn");
        submitButton.disabled = true;
        e.preventDefault();
        var error = 0;
        var data = new FormData(this);
        $.ajax({
            type: "POST",
            url: "ajax/contactPost.php",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(gelenSayfa) {
                if (gelenSayfa == "1") {
                    document.getElementById("contactpost").reset();
                    swal("<?=$fonk->getDil('Başarılı')?>", "<?=$fonk->getDil('Başarılı')?>", "success")
                } 
                else if (gelenSayfa == "2") {
                    swal("<?=$fonk->getDil('Hata')?>", "<?=$fonk->getDil('Hata Oluştu! Lütfen tekrar deneyiniz')?>", "success")
                }
                setTimeout(function () {
                    // Re-enable the submit button after some time (e.g., after AJAX request completes)
                    submitButton.disabled = false;
                }, 2000); // 2 seconds (you can adjust this as needed)
            },
        });

    });
</script>