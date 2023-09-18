<?php
include('layouts/header.php');
?>

<div id="nt_content">

    <div class="nt_section type_featured_collection tp_se_cdt" id="kampanyaliurunler">
        <div class="kalles-otp-01__feature container">
            <div class="wrap_title des_title_2">
                <h3 class="section-title tc pr flex fl_center al_center fs__24 title_2">
                    <span class="mr__10 ml__10"><?= $fonk->getDil("Favori Ürünler") ?></span>
                </h3>
            </div>
            <div class="products nt_products_holder row fl_center row_pr_1 cdt_des_5 round_cd_true nt_cover ratio_nt position_8 space_30">
                <?php 
                 $sartlar = [];
                 //toplam veri
                 $sutunlar=[
                     "urunId",
                     "urunVaryantDilBilgiUrunId",
                     "urunVaryantId",
                     "urunBaseUrl",
                     "urunGorsel",
                     "urunVaryantKodu",
                     "urunVaryantDilBilgiAdi",
                     "urunVaryantDilBilgiSlug",
                     "urunVaryantFiyat",
                     "urunVaryantDilBilgiDilId",
                     "urunVaryantVaryantId",
                     "urunVaryantDefaultSecim",
                     "urunVaryantDilBilgiDurum",
                     "urunDurum",
                     "paraBirimKodu",
                     "paraBirimSembol",
                 ];
             
                 $sartlar = array_merge($sartlar, [
                     'GROUP' => $sutunlar,
                     "urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
                     "uyeId" => $uye["uyeId"],
                     "urunVaryantDilBilgiDurum" => 1,
                     "urunDurum" => 1,
                     "ORDER" => [
                         "urunId" => "ASC"
                     ]
                 ]);
                 //normal sorgumuz
                 $urunler = $db->select("Urunler", [
                     "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
                     "[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
                     "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
                     "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
                     "[>]UrunFavoriler" => ["UrunVaryantlari.urunVaryantId" => "urunFavoriUrunVaryantId"],
                     "[>]Uyeler" => ["UrunFavoriler.urunFavoriUyeId" => "uyeId"],
                     "[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
                 ], $sutunlar, $sartlar);

                foreach ($urunler as $value) {
                    if ($value["urunGorsel"] == "") {
                        $value["urunGorsel"] = "img-not-found.png";
                    }
                ?>
                <div class="col-lg-2 col-md-2 col-6 pr_animated done mt__30 pr_grid_item product nt_pr desgin__1">
                    <div class="product-inner pr">
                        <div class="product-image pr oh lazyload">
                            <a class="d-block" href="product/<?= $value["urunVaryantKodu"] . "-" . $value["urunVaryantDilBilgiSlug"]; ?>">
                                <div class="pr_lazy_img main-img nt_img_ratio nt_bg_lz lazyload padding-top__127_571 " data-bgset="<?= $value["urunBaseUrl"] . "" . $value["urunGorsel"]; ?>"></div>
                            </a>
                            <div class="hover_img pa pe_none t__0 l__0 r__0 b__0 op__0">
                                <div class="pr_lazy_img back-img pa nt_bg_lz lazyload padding-top__127_571 " data-bgset="<?= $value["urunBaseUrl"] . "" . $value["urunGorsel"]; ?>"></div>
                            </div>
                          
                        </div>
                        <div class="product-info mt__15">
                            <h3 class="product-title pr fs__14 mg__0 fwm">
                                <a class="cd chp" href="product/<?= $value["urunVaryantKodu"] . "-" . $value["urunVaryantDilBilgiSlug"]; ?>">
                                    <?= $value["urunVaryantDilBilgiAdi"]; ?>
                                </a>
                            </h3>
                            <?php if($uyeVar == 1){ ?>
                            <span class="price dib mb__5">
                                <?php if($uye['uyeIndirimOrani'] > 0 ): ?>
                                    Bayi Özel Fiyat:
                                    <del> 
                                        <?php $hesapla=$fonk->Hesapla($value["urunVaryantId"],"");?>
                                        <?= $value["paraBirimSembol"] ?><?=$hesapla["birimFiyat"];?>
                                    </del>
                                    <br>
                                    Liste Özel Fiyat:
                                    <ins> 
                                        <?php $hesapla2=$fonk->Hesapla($value["urunVaryantId"],"",$uye['uyeIndirimOrani']);?>
                                        <?= $value["paraBirimSembol"] ?><?=$hesapla2["birimFiyat"];?>
                                    </ins>
                                <?php else: ?>
                                    <?php $hesapla=$fonk->Hesapla($value["urunVaryantId"],"");?>
                                    <ins> <?= $value["paraBirimSembol"] ?><?=$hesapla["birimFiyat"];?></ins>
                                <?php endif; ?>
                            </span>
                            <?php } ?> 
                            <button type="button" onclick="location.href='product/<?= $value['urunVaryantKodu'] . '-' . $value['urunVaryantDilBilgiSlug']; ?>'" data-time="6000" data-ani="shake" class="single_add_to_cart_button button truncate w__100 mt__10 order-4 d-inline-block animated mb-3">
                                <span class="txt_add"><?= $fonk->getDil("Ürüne Git"); ?></span>
                            </button>
                            <button type="button" onclick="FavoriEkle(<?= $value['urunVaryantId']; ?>,<?= $uye['uyeId']; ?>);" data-time="6000" data-ani="shake" class="single_add_to_cart_button button truncate w__100 mt__10 order-4 d-inline-block animated">
                                <span class="txt_add"><?= $fonk->getDil("Favoriden Çıkar"); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

</div>

<?php include('layouts/footer.php') ?>
<script type="text/javascript">
    function SepeteEkle(urunId) 
    {
        <?php if($uyeVar == 1){ ?>
        
        SepetKayit(urunId, "0", 1);
        <?php } else {?> 
            swal("Uyarı", "Lütfen üye girişi yapınız.", "warning")
            .then((value) => {
                window.location.href = "<?= $sabitBilgiler['sabitBilgiSiteUrl']; ?>account";
            });
        <?php } ?>
    }

    function FavoriEkle(urunId,uyeId) 
    {
        if (uyeId != "" && uyeId != undefined) {
            FavoriKayit(urunId,uyeId);
        }
        else {
            swal("Uyarı", "Lütfen üye girişi yapınız.", "warning")
            .then((value) => {
                window.location.href = "<?= $sabitBilgiler['sabitBilgiSiteUrl']; ?>account";
            });
        }
    }
</script>