<?php
include("../Panel/System/Config.php");
extract($_POST);

if ($_POST['formdan'] == "4") {

    if ($uyeSifre == $uyeSifreTekrar) {
        $parametreler = array(
            'uyeSifre' => hash("sha256", md5($uyeSifre))
        );

        $query = $db->update("Uyeler", $parametreler, [
            "uyeMail" => $sifreUnuttumEmail
        ]);

        if ($query) {
            echo '3';
        } else {
            echo '2';
        }
    } else {
        echo '4';
    }
}
