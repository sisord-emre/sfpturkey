<?php
include("../../System/Config.php");

$tabloPrimarySutun = "siparisId"; //primarykey

$tableName = "Siparisler";

$detayId = $_GET['detayId'];


$detay = $db->get($tableName,[
	"[>]Uyeler" => ["Siparisler.siparisUyeId" => "uyeId"],
	"[>]UyeAdresler" => ["Siparisler.siparisTeslimatUyeAdresId" => "uyeAdresId"],
	"[><]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
	"[><]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
	"[><]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"],
	"[>]OdemeTipleri" => ["Siparisler.siparisOdemeTipiId" => "odemeTipId"],
	"[>]Diller" => ["Siparisler.siparisDilId" => "dilId"],
	"[>]ParaBirimleri" => ["Siparisler.siparisParaBirimId" => "paraBirimId"]
],"*",[
	$tabloPrimarySutun => $detayId
]);

$siparisIcerikleri = $db->select("SiparisIcerikleri",[
	"[<]Urunler" => ["SiparisIcerikleri.siparisIcerikUrunId" => "urunId"],
	"[<]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
],"*",[
	"urunDilBilgiDilId" => $detay["siparisDilId"],
	"urunDurum" => 1,
	"urunDilBilgiDurum" => 1,
	"siparisIcerikSiparisId" => $detay["siparisId"]
]);

$icerik="";
$icerikSayac=0;
foreach($siparisIcerikleri as $siparisIcerik)
{
    $icerikSayac++;
    if ($icerikSayac<=2) {
        $icerik.="<span style='display: flex;width: max-content;'>
        ".$siparisIcerik["siparisIcerikUrunVaryantDilBilgiAdi"]."
        </span>";
    }
    else{
        $icerik.="<span style='display: flex;width: max-content;'>...</span>";
    }
}

$siparisDurum = $db->get("SiparisSiparisDurumlari",[
	"[<]SiparisDurumlari" => ["SiparisSiparisDurumlari.siparisSiparisDurumSiparisDurumId" => "siparisDurumId"],
	"[<]SiparisDurumDilBilgiler" => ["SiparisDurumlari.siparisDurumId" => "siparisDurumDilBilgiSiparisDurumId"],
	"[>]KargoFirmalari" => ["SiparisSiparisDurumlari.siparisSiparisDurumKargoFirmaId" => "kargoFirmaId"]
],"*",[
	"siparisSiparisDurumSiparisId" => $detay["siparisId"],
	"siparisDurumDilBilgiDilId" => $detay["siparisDilId"],
    "siparisSiparisDurumSiparisDurumId" => 5, //kargoya verildi
	"ORDER" => [
		"siparisSiparisDurumId" => "ASC",
	]
]);
?>
<!DOCTYPE html>
<html class="loading" lang="<?= $_SESSION["panelDilKodu"] ?>" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="<?= $sabitB['sabitBilgiDescription'] ?>">
    <meta name="keywords" content="<?= $sabitB['sabitBilgiKeywords'] ?>">
    <meta name="author" content="<?= $sabitB['sabitBilgiLisansFirmaAdi'] ?>">
    <title><?= $fonk->getPDil("Panel") ?> - <?= $sabitB['sabitBilgiTitle'] ?></title>
    <link rel="apple-touch-icon" href="Images/Ayarlar/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="Images/Ayarlar/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>

    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
</head>
<!-- END: Head-->


<div class="modal fade text-left" id="fadeIn" role="dialog" aria-hidden="true">
    <!-- detay modalı -->
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" id="icerikModal">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="width:50%">
                        <tbody>
                            <tr>
                                <th colspan="2" style="text-align:left;">
                                    <b>Alıcı Bilgileri</b>
                                </th>
                            </tr>
                            <tr>
                                <td>İsim:</td>
                                <td><?=$detay['uyeAdi']." ".$detay['uyeSoyadi']?></td>
                            </tr>
                            <tr>
                                <td>Adres:</td>
                                <td><?=$detay['uyeAdresBilgi']?> - <?=$detay['ilceAdi']?> - <?=$detay['ilAdi']?> - <?=$detay['ulkeAdi']?></td>
                            </tr>
                            <tr>
                                <th colspan="2" style="text-align:left;">
                                    <b>Gönderen Bilgileri</b>
                                </th>
                            </tr>
                            <tr>
                                <td>İsim:</td>
                                <td><?=$sabitB["sabitBilgiSiteAdi"]?></td>
                            </tr>
                            <tr>
                                <td>Telefon:</td>
                                <td><?=$sabitB["sabitBilgiTel"]?></td>
                            </tr>
                            <tr>
                                <td>Adres:</td>
                                <td><?=$sabitB["sabitBilgiAdres"]?></td>
                            </tr>
                            <tr>
                                <th colspan="2" style="text-align:left;">
                                    <b>Kargo Bilgileri</b>
                                </th>
                            </tr>
                            <tr>
                                <td>Kargo Firması:</td>
                                <td><?=$siparisDurum['kargoFirmaAdi']?></td>
                            </tr>
                            <tr>
                                <th colspan="2" style="text-align:left;">
                                    <b>Alınan Ürünler</b>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="2" style="text-align:left;">
                                   <?=$icerik?>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>