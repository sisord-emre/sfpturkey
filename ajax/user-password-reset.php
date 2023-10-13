<?php
include("../Panel/System/Config.php");
extract($_POST);

if ($_POST['formdan'] == "3") {

    $mailKontrol = $db->get('Uyeler', "*", [
        'uyeMail' => $uyeMail
    ]);

    if ($mailKontrol) {
        $tkn = hash("sha256", md5(time()));
        $parametreler = array(
            'sifreUnuttumEmail' => $uyeMail,
            'sifreUnuttumToken' => $tkn,
            'sifreUnuttumTarihi' => date("Y-m-d H:i:s")
        );
        $query = $db->insert('SifreUnuttum', $parametreler);

        if ($query) {
            $baslik = "SFPTURKEY-Sifre Sifirlama";
            include("reset-password-mail-template.php");
            $sonuc = $fonk->mailGonder($uyeMail, $baslik, $body);
            if ($sonuc == '1') {
                echo "3";
            } else {
                echo "2";
            }
        } else {
            echo '4';
        }
    } else {
        echo '1';
    }
}
