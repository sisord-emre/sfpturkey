<?php
include 'layouts/header.php';
unset($_SESSION["SiparisKodu"]);
unset($_SESSION["Sepet"]);

$bankalar = $db->select("BankaBilgileri", "*", [
	"bankaBilgiDurum" => 1,
    "ORDER" => "bankaBilgiSirasi",
]);

$siparis = $db->get("Siparisler", "*", [
	"siparisKodu" => $_GET["s"],
	"siparisUyeId" => $uye['uyeId']
]);

$siparisIcerikleri = $db->select("SiparisIcerikleri", [
	"[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
	"[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
], "*", [
	"urunDilBilgiDilId" => $_SESSION["dilId"],
	"urunDurum" => 1,
	"urunDilBilgiDurum" => 1,
	"siparisIcerikSiparisId" => $siparis['siparisId']
]);

$toplamTutar=0;
foreach($siparisIcerikleri as $siparisIcerik){
    $toplamTutar+=$siparisIcerik['siparisIcerikAdet']*$siparisIcerik['siparisIcerikFiyat'];
}
if($siparis['siparisIndirimKodu']!="" && $siparis['siparisIndirimYuzdesi']!=0){
    $toplamTutar-=($toplamTutar/100*$siparis['siparisIndirimYuzdesi']);
}
if($siparis['siparisKargoUcreti']!=0){
    $toplamTutar+=$siparis['siparisKargoUcreti'];
}
if($siparis["siparisOdenenIskontoUcreti"] > 0){
    $toplamTutar-=$siparis["siparisOdenenIskontoUcreti"];
}

$odemeLoglari = $db->insert('OdemeLoglari', [
    'odemeLogSiparisKod' => $_GET["s"],
    'odemeLogHata' => "havale sayfasına yönlendirme başarılı",
    'odemeLogStatus' => "success",
    'odemeLogTarih' => date("Y-m-d H:i:s")
]);
?>
<div id="nt_content">
    <!--hero banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="tu mb__5 cw">
                    <?= $fonk->getDil("Sipariş/Ödeme Bilgileri"); ?>
                </h1>
            </div>
        </div>
    </div>
    <!--end hero banner-->

    <!--page content-->
    <div class="kalles-section container mt__20 mb__60">
        <div class="row fl_center cb">
            <div class="col-12 mt__35">
                <h2><?= $fonk->getDil("Hesap Bilgilerimiz") ?></h2>
                <?php foreach ($bankalar as $key => $value) {?>
                    <p><b><?= $fonk->getDil("Banka") ?></b> : <?=$value['bankaBilgiBankaAdi'] ?></p>
                    <p><b><?= $fonk->getDil("IBAN") ?></b> : <?=$value['bankaBilgiIban'] ?></p>
                    <p><b><?= $fonk->getDil("Hesap Sahibi") ?></b> : <?=$value['bankaBilgiHesapSahibi'] ?></p>
                    <hr>
                <?php } ?>
                <p><b><?= $fonk->getDil("Ödenecek Tutar") ?></b> : <?=number_format($siparis["siparisToplam"],2,',','.');?> <?= $_SESSION["paraBirimSembol"]?></p>
                <p>
                    <?= $fonk->getDil("Lütfen hesaba havale/eft yaparken ilgili sipariş kodunu ")?> <b> <?=$_GET["s"] ?> </b> <?= $fonk->getDil("açıklamaya yazmayı unutmayınız")?>
                </p>
                <p>
                    <a href="myorders"><?= $fonk->getDil("Siparişinizin durumunu Profil>Siparişlerim Bölümünden takip edebilirsiniz..") ?></a>
                </p>
                <p>
                    <a href="index"><?= $fonk->getDil("Alışverişe Devam Et/Ana Sayfa") ?></a>
                </p>
            </div>
        </div>
    </div>
    <!--end page content-->
</div>
<?php include 'layouts/footer.php'; ?>