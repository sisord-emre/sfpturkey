<?php 
include 'layouts/header.php'; 

$seo = $_GET['seo'];

$siparislerim = $db->get("Siparisler",[
  "[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
  "[>]UyeAdresler" => ["Siparisler.siparisTeslimatUyeAdresId" => "uyeAdresId"],
  "[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
  "[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
  "[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
  "[>]OdemeTipleri" => ["Siparisler.siparisOdemeTipiId" => "odemeTipId"],
  "[>]Diller" => ["Siparisler.siparisDilId" => "dilId"],
  "[>]ParaBirimleri" => ["Siparisler.siparisParaBirimId" => "paraBirimId"]
],"*",[
  "siparisKodu" => $seo,
]);

$siparisIcerikleri = $db->select("SiparisIcerikleri",[
  "[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
  "[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
],"*",[
  "urunDilBilgiDilId" => $siparislerim["siparisDilId"],
  "urunDurum" => 1,
  "urunDilBilgiDurum" => 1,
  "siparisIcerikSiparisId" => $siparislerim["siparisId"]
]);
?>

<div id="nt_content">

    <!--shop banner-->
    <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw">Sipariş Detayı</h1>
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
                <div class="order-review__wrapper">
                    <div class="checkout-order-review">
                        <table class="checkout-review-order-table">
                            <thead>
                                <tr class="text-uppercase">
                                    <th class="product-total"><b>Sipariş Kodu</b></th>
                                    <th class="product-name"><b>Ürün</b></th>
                                    <th class="product-total text-center"><b>Adet Mİktar</b></th>
                                    <th class="product-name text-center"><b>Sİparİş Fİyatı</b></th>
                                    <th class="product-total text-center"><b>Sİparİş Toplamı</b></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $toplamTutar=0;
                            $araTutar=0;
                            foreach ($siparisIcerikleri as $val){
                                $toplamTutar+=$val['siparisIcerikAdet']*$val['siparisIcerikFiyat'];
                                $araTutar+=$val['siparisIcerikAdet']*$val['siparisIcerikFiyat'];

                                if($val['siparisIndirimKodu']!="" && $val['siparisIndirimYuzdesi']!=0)
                                {
                                $indirimMiktar=$toplamTutar/100*$val['siparisIndirimYuzdesi'];
                                $toplamTutar-=$indirimMiktar;
                                $indirimMiktar=$val["paraBirimSembol"].round($indirimMiktar,2);

                                }
                                if($val['siparisKargoUcreti']!=0)
                                {
                                $toplamTutar+=$val['siparisKargoUcreti'];
                                $siparisKargoUcreti=$val["paraBirimSembol"].round($val['siparisKargoUcreti'],2);
                            }
                            ?>
                                <tr class="cart_item">
                                    <td class="product-name"><?=$seo?></td>
                                    <td class="product-name"><?=$val["siparisIcerikUrunVaryantDilBilgiAdi"]?></td>
                                    <td class="product-total text-center"><?=$val["siparisIcerikAdet"]?></td>
                                    <td class="product-total text-center"><span class="cart_price"><?=$val["paraBirimSembol"].round(($val['siparisIcerikFiyat']),2)?></span></td>
                                    <td class="product-total text-center"><span class="cart_price"><?=$val["paraBirimSembol"].round(($val['siparisIcerikAdet']*$val['siparisIcerikFiyat']),2)?></span></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr class="cart-subtotal cart_item">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center">Ara toplam</th>
                                    <td class="text-center"><span class="cart_price"><?=$val["paraBirimSembol"].round(($araTutar),2)?></span></td>
                                </tr>
                                <tr class="cart_item">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center">İndirim</th>
                                    <td class="text-center"><span class="cart_price"><?=$val["paraBirimSembol"].round(($indirimMiktar),2)?></span></td>
                                </tr>
                                <tr class="cart-subtotal cart_item">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center">Gönderim Ücreti</th>
                                    <td class="text-center"><span class="cart_price"><?=$val["paraBirimSembol"].round(($siparisKargoUcreti),2)?></span></td>
                                </tr>
                                <tr class="order-total cart_item">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-center">Toplam</th>
                                    <td class="text-center"><strong><span class="cart_price amount"><?=$val["paraBirimSembol"].round(($toplamTutar),2)?></span></strong></td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--end cart section-->
</div>

<?php include 'layouts/footer.php'; ?>