<?php 
include('layouts/header.php');

if(!$uyeVar)
{
    echo '<script> window.location.href="'.$sabitB['sabitBilgiSiteUrl'].'account";</script>';
    exit;
}
?>

<div id="nt_content">
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw"><?= $fonk->getDil("Sepetİm"); ?></h1>
            </div>
        </div>
    </div>

    <div class="kalles-section cart_page_section container mt__60">

        <form action="checkout" method="post" class="frm_cart_ajax_true frm_cart_page nt_js_cart pr oh ">
            <div class="row">
                <div class="col-12 col-md-9">
                    <div class="cart_header">
                        <div class="row al_center">
                            <div class="col-5"><?=$fonk->getDil("Ürün");?></div>
                            <div class="col-2 tc"><?=$fonk->getDil("Fiyat");?></div>
                            <div class="col-2 tc"><?=$fonk->getDil("Adet");?></div>
                            <div class="col-2 tc"><?=$fonk->getDil("Tutar");?></div>
                            <div class="col-1 tc"></div>
                        </div>
                    </div>

                    <div class="cart_items js_cat_items">
                        <?php
                        if($_SESSION['Sepet']=="" || $_SESSION['Sepet']=="null" || $_SESSION['Sepet']==null || !isset($_SESSION['Sepet'])) {
                            $_SESSION['Sepet']="[]";
                        }
                        $sepet=json_decode($_SESSION['Sepet'],true);
                     
                        $toplamTutar=0;
                        $araTutar=0;
                        $kdvTutar=0;
                        $indirimTutar=0;
                        $siparisKargoUcreti=0;
                        $siparisKargoKdvUcreti=0;
                        for ($i=0; $i <count($sepet) ; $i++){
                        $urun = $db->get("Urunler", [
                            "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
                            "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
                            "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
                            "[>]UrunKategoriler" => ["Urunler.urunId" => "urunKategoriUrunId"],
                            "[>]KategoriDilBilgiler" => ["UrunKategoriler.urunKategoriKategoriId" => "kategoriDilBilgiKategoriId"],
                            "[>]ParaBirimleri" => ["Urunler.urunParaBirimId" => "paraBirimId"],
                        ],"*",[
                            "urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
                            "urunVaryantDilBilgiVaryantId" => $sepet[$i]["urunId"],
                            "urunVaryantDilBilgiDurum" => 1,
                            "ORDER" => [
                                "urunId" => "ASC"
                            ]
                        ]);
                        $hesapla=$fonk->Hesapla($sepet[$i]["urunId"],$sepet[$i]["varyantId"],$uye['uyeIndirimOrani']);
                        $toplamTutar+=($hesapla["birimFiyat"]+($hesapla["birimFiyat"]/100*$urun["urunKdv"]))*$sepet[$i]["adet"];
                        $araTutar+=($hesapla["birimFiyat"])*$sepet[$i]["adet"];
                        $kdvTutar+=($hesapla["birimFiyat"]/100*$urun["urunKdv"])*$sepet[$i]["adet"];
                       
                        if($urun["urunGorsel"]==""){
                            $urun["urunGorsel"]="img-not-found.png";
                        }
                        ?>
                        <div class="cart_item js_cart_item">
                            <div class="ld_cart_bar"></div>
                            <div class="row al_center">
                                <div class="col-12 col-md-12 col-lg-5">
                                    <div class="page_cart_info flex al_center">
                                        <a href="product/<?=$urun['urunVaryantKodu']."-".$urun["urunVaryantDilBilgiSlug"]?>">
                                            <img class="lazyload w__100 lz_op_ef" src="<?=$urun["urunBaseUrl"]."".$urun["urunGorsel"];?>" data-src="<?=$urun["urunBaseUrl"]."".$urun["urunGorsel"];?>" alt="">
                                        </a>
                                        <div class="mini_cart_body ml__15">
                                            <h5 class="mini_cart_title mg__0 mb__5">
                                                <a href="product/<?=$urun['urunVaryantKodu']."-".$urun["urunVaryantDilBilgiSlug"]?>">
                                                    <?=$urun["urunVaryantDilBilgiAdi"]?>
                                                </a>
                                            </h5>
                                            <div class="mini_cart_meta">
                                                <p class="cart_selling_plan"></p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-2 tc__ tc_lg">
                                    <div class="cart_meta_prices price">
                                        <div class="cart_price fs__22">
                                            <?= $urun["paraBirimSembol"] ?><?=number_format($hesapla["birimFiyat"],2,",",".");?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-2 tc mini_cart_actions">
                                    <div class="quantity pr mr__10 qty__true">
                                        <input type="number" class="input-text qty text tc qty_cart_js" onchange="SepetAdet(<?=$i?>,<?=$hesapla['birimFiyat']?>,'<?=$_SESSION['paraBirimSembol']?>',<?=$uye['uyeIndirimOrani']?>)" id="adet-<?=$i?>" name="adet-<?=$i?>" value="<?=$sepet[$i]["adet"]?>" min="1">                               
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 col-lg-2 tc__ tc_lg">
                                    <span class="cart-item-price fwm cd js_tt_price_it fs__22" id="araToplam-<?=$i?>">
                                        <?= $_SESSION["paraBirimSembol"] ?>
                                        <?=$fonk->paraCevir($hesapla["birimFiyat"]*$sepet[$i]["adet"],$urun["paraBirimKodu"],"TRY");?>
                                    </span>
                                </div>
                                <div class="col-12 col-md-4 col-lg-1 tc__ tr_lg">
                                    <div class="mini_cart_tool mt__10">
                                        <a href="#" class="cart_ac_remove js_cart_rem ttip_nt tooltip_top_right">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none" onclick="SepetSil(<?=$i?>,1);" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-12 col-md-12 cart_actions tl_md tc order-md-2 order-2 mb__50">
                        <label for="CartSpecialInstructions_2" class="cart-note__label dib cd mb__10">
                            <span class="txt_add_note "><?= $fonk->getDil("Sipariş Notu Ekle "); ?></span>
                        </label>
                        <textarea name="note" id="note" class="cart-note__input" placeholder="Nasıl yardım edebiliriz?"></textarea>
                    </div>
                    
                    <?php 
                        $siparisKargoUcreti = $fonk->KargoUcreti($fonk->paraCevir($toplamTutar,$urun["paraBirimKodu"],"TRY")); 
                        $siparisKargoKdvUcreti = $fonk->KargoKdvUcreti($fonk->paraCevir($toplamTutar,$urun["paraBirimKodu"],"TRY")); 
                    ?>
                    <input type="hidden" name="siparisKargoUcreti" value="<?=$siparisKargoUcreti?>">
                    <input type="hidden" name="siparisKargoKdvUcreti" value="<?=$siparisKargoKdvUcreti?>">
                </div>
                <div class="col-12 col-md-3">
                    <div class="cart__footer mt__60">
                        <div class="row">
                            <div class="col-12 tr_md tc order-md-4 order-4 col-md-12">
                                <div class="total row in_flex fl_between al_center cd fs__18 tu">
                                    <div class="col-auto"><strong><?= $fonk->getDil("Aratutar"); ?>:</strong></div>
                                    <div class="col-auto tr js_cat_ttprice fs__20 fwm">
                                        <div class="cart_tot_price" id="araTutar">
                                            <?= $_SESSION["paraBirimSembol"] ?>
                                            <?=$fonk->paraCevir($araTutar,$urun["paraBirimKodu"],"TRY");?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="total row in_flex fl_between al_center cd fs__18 tu">
                                    <div class="col-auto"><strong> <?= $fonk->getDil("KDV"); ?>:</strong></div>
                                    <div class="col-auto tr js_cat_ttprice fs__20 fwm">
                                        <div class="cart_tot_price" id="kdvTutar">
                                            <?= $_SESSION["paraBirimSembol"] ?>
                                            <?=$fonk->paraCevir($kdvTutar,$urun["paraBirimKodu"],"TRY");?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="total row in_flex fl_between al_center cd fs__18 tu">
                                    <div class="col-auto"><strong> <?= $fonk->getDil("Toplam-1"); ?>:</strong></div>
                                    <div class="col-auto tr js_cat_ttprice fs__20 fwm">
                                        <div class="cart_tot_price" id="toplamTutar">
                                            <?= $_SESSION["paraBirimSembol"] ?>
                                            <?=$fonk->paraCevir($toplamTutar,$urun["paraBirimKodu"],"TRY");?>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <!--<p class="db txt_tax_ship mb__5"> <?= $fonk->getDil("Ödeme sırasında hesaplanan vergiler, nakliye ve indirim kodları"); ?></p>-->
                                
                                <div class="clearfix"></div>
                                <button type="submit" name="checkout" id="submitButton" onclick="ButtonDisabled('submitButton')" class="btn_checkout button button_primary tu mt__10 mb__10 js_add_ld w__100">Ödemeye Geç</button>
                                <a href="index.php" type="button" data-confirm="ck_lumise" name="checkout" style="background-color: black; border-color: black;" class="btn_checkout button button_primary tu mt__10 mb__10 js_add_ld text-center">Alışverİşe Devam Et</a>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include('layouts/footer.php') ?>