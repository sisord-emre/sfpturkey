<?php
include('layouts/header.php');

if ($_POST["gelen"] == 1) {
    extract($_POST);

    $parametreler = array(
        'iletisimFormAdSoyad' => $iletisimFormAdSoyad,
        'iletisimFormEmail' => $iletisimFormEmail,
        'iletisimFormTel' => $iletisimFormTel,
        'iletisimFormMesaj' => $iletisimFormMesaj,
        'iletisimFormDurum' => "0",
        'iletisimFormKayitTarihi' => date("Y-m-d H:i:s")
    );
    $query = $db->insert('IletisimFormlari', $parametreler);


    if ($query) {
        $baslik = "İletisim";
        include("Mailtemplate/contactMailTemplate.php");
        $sonuc = $fonk->mailGonder($gondericiMail[1], $baslik, $body, $sabitB);

        echo '<script> swal("' . $fonk->getDil('Başarılı') . '", "' . $fonk->getDil('Başarılı') . '", "success");</script>';
    } else {
        echo '<script> swal("' . $fonk->getDil('Hata') . '", "' . $fonk->getDil('Hata Oluştu! Lütfen tekrar deneyiniz') . '", "error");  </script>';
    }
}
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
                <form method="post" action="" class="contact-form">
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
                    <input type="submit" class="button button_primary w__100" value="<?= $fonk->getDil("Gönder"); ?>">
                </form>
            </div>

            <div class="contact-content col-12 col-md-6 order-0 order-md-1">
                <h3 class="mb__20 mt__40"><?= $fonk->getDil("İLETİŞİM BİLGİLERİ"); ?></h3>
                <p>
                    <?= $fonk->getDil("Müşteri hizmetlerimiz, ürünlerimiz, web sitemiz veya bizimle paylaşmak istediğiniz herhangi bir konuda sizden haber almayı çok seviyoruz. Yorumlarınız ve önerileriniz takdir edilecektir. Lütfen aşağıdaki formu doldurunuz."); ?>
                </p>
                <p class="mb__5 d-flex"><i class="las la-home fs__20 mr__10 text-primary"></i> <?= $sabitB["sabitBilgiAdres"]; ?></p>
                <p class="mb__5 d-flex"><i class="las la-phone fs__20 mr__10 text-primary"></i><?= $siteTel[0]; ?></p>
                <p class="mb__5 d-flex"><i class="las la-envelope fs__20 mr__10 text-primary"></i><?= $gondericiMail[1]; ?></p>
                <p class="mb__5 d-flex"><i class="las la-clock fs__20 mr__10 text-primary"></i> <?= $fonk->getDil("Hafta içi 09:00-18:00"); ?> </p>
            </div>
        </div>
    </div>
    <!--end page content-->
</div>

<?php include('layouts/footer.php') ?>