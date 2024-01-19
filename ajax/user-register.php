<?php
include("../Panel/System/Config.php");
extract($_POST);

if ($_POST['formdan'] == "1") {

    $uyeTcVergiNoKontrol = $db->get('Uyeler', "*", [
        'uyeTcVergiNo' => $uyeTcVergiNo
    ]);

    $uyeMailKontrol = $db->get('Uyeler', "*", [
        'uyeMail' => $uyeMail
    ]);


    if ($_FILES['uyeTicaretSicilGazetesi']['name'] == "") {
        echo '8';
    }
    if ($_FILES['uyeMukerrerImza']['name'] == "") {
        echo '9';
    }
    if (count($_FILES['uyeVergiLevhasiDosya']['name']) == 0) {
        echo '10';
    }
    if ($uyeSifre != $uyeSifreTekrar) {
        echo '4';
    } else if ($uyeMailKontrol) {
        echo '5';
    } else if ($gizlilikonay != "1") {
        echo '6';
    } else if ($kvkk != "1") {
        echo '7';
    } else if (!$uyeTcVergiNoKontrol) {
        $uyeKodu = time();
        $parametreler = array(
            'uyeTcVergiNo' => $uyeTcVergiNo,
            'uyeAdi' => $uyeAdi,
            'uyeSoyadi' => $uyeSoyadi,
            'uyeMail' => $uyeMail,
            'uyeTel' => $uyeTel,
            'uyeSifre' => hash("sha256", md5($uyeSifre)),
            'uyeKodu' => $uyeKodu,
            'uyeFirmaAdi' => $uyeFirmaAdi,
            'uyeDurum' => 0, //pasif
            'uyeIndirimOrani' => 0, //başta 0 olacak
            'uyeTicaretSicilGazetesiBaseUrl' => $sabitB["sabitBilgiSiteUrl"] . "Images/TicaretSicilGazetesi/",
            'uyeMukerrerImzaBaseUrl' => $sabitB["sabitBilgiSiteUrl"] . "Images/ImzaSirkuleri/",
            'uyeGizlilikOnay' => $gizlilikonay,
            'uyeKvkkOnay' => $kvkk,
            'uyeKayitTarihi' => date("Y-m-d H:i:s")
        );


        $files = array_filter($_FILES['uyeTicaretSicilGazetesi']['name']);
        $fileName = mt_rand();
        $tmpFilePath = $_FILES['uyeTicaretSicilGazetesi']['tmp_name'];
        if ($tmpFilePath != "") {
            $ext = pathinfo($_FILES['uyeTicaretSicilGazetesi']['name'], PATHINFO_EXTENSION);
            $newFilePath = "../Images/TicaretSicilGazetesi/" . $fileName . "." . $ext;
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $parametreler = array_merge($parametreler, array('uyeTicaretSicilGazetesi' => $fileName . "." . $ext));
            }
        }

        $files3 = array_filter($_FILES['uyeMukerrerImza']['name']);
        $fileName3 = mt_rand();
        $tmpFilePath3 = $_FILES['uyeMukerrerImza']['tmp_name'];
        if ($tmpFilePath3 != "") {
            $ext = pathinfo($_FILES['uyeMukerrerImza']['name'], PATHINFO_EXTENSION);
            $newFilePath3 = "../Images/ImzaSirkuleri/" . $fileName3 . "." . $ext;
            if (move_uploaded_file($tmpFilePath3, $newFilePath3)) {
                $parametreler = array_merge($parametreler, array('uyeMukerrerImza' => $fileName3 . "." . $ext));
            }
        }

        $query = $db->insert('Uyeler', $parametreler);
        $uyeId = $db->id();



        if ($query) {
            $files2 = array_filter($_FILES['uyeVergiLevhasiDosya']['name']);
            $total_count2 = count($_FILES['uyeVergiLevhasiDosya']['name']);
            for ($i = 0; $i < $total_count2; $i++) {
                $faturaAdi2 = mt_rand();
                $tmpFilePath2 = $_FILES['uyeVergiLevhasiDosya']['tmp_name'][$i];
                if ($tmpFilePath2 != "") {
                    $ext = pathinfo($_FILES['uyeVergiLevhasiDosya']['name'][$i], PATHINFO_EXTENSION);
                    $newFilePath2 = "../Images/VergiLevhasi/" . $faturaAdi2 . "." . $ext;
                    if (move_uploaded_file($tmpFilePath2, $newFilePath2)) {
                        $datas = array(
                            'uyeVergiLevhasiUyeId' => $uyeId,
                            'uyeVergiLevhasiBaseUrl' => $sabitB["sabitBilgiSiteUrl"] . "Images/VergiLevhasi/",
                            'uyeVergiLevhasiDosya' => $faturaAdi2 . "." . $ext
                        );
                        $uyeVergiLevhasi = $db->insert('UyeVergiLevhasi', $datas);
                    }
                }
            }

            $uyeVergiLevhasi = $db->select("Uyeler", [
                "[>]UyeVergiLevhasi" => ["Uyeler.uyeId" => "uyeVergiLevhasiUyeId"],
            ], "*", [
                "uyeId" => $uyeId,
                "ORDER" => [
                    "uyeId" => "ASC"
                ]
            ]);

            $uye = $db->get("Uyeler", "*", [
                "uyeId" => $uyeId,
                "ORDER" => [
                    "uyeId" => "ASC"
                ]
            ]);

            echo '3';

            include("../userInfoMailTemplate.php");
            $baslik = "Yeni Kullanici (" . $uyeId . " )";
            $baslik2 = "SFPTURKEY-Yeni Uye Kaydi";
            $fonk->mailGonder($uyeMail, $baslik2, $body);
            $fonk->mailGonder($gondericiMail[3], $baslik, $body);
        } else {
            echo '2';
        }
    } else {
        echo '1';
    }
}
