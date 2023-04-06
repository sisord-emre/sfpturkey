<?php
include("../Panel/System/Config.php");

extract($_POST);
$fonk->csrfKontrol();

$uye = $db->get("Uyeler", "*", [
	"uyeKodu" => $_SESSION['uyeKodu']
]);
if (!$uye) {
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . '"; </script>';
	exit;
}

$parametreler = array(
	"uyeAdresUyeId" => $uye["uyeId"],
	"uyeAdresAdi" => $uyeAdresAdi,
	"uyeAdresUlkeId" => $uyeAdresUlkeId,
	"uyeAdresIlId" => $uyeAdresIlId,
	"uyeAdresIlceId" => $uyeAdresIlceId,
	"uyeAdresBilgi" => $uyeAdresBilgi,
	"uyeAdresKayitTarihi" => date("Y-m-d H:i:s")
);

if ($uyeAdresId == "") {
	$query = $db->insert("UyeAdresler", $parametreler);
} else {
	$query = $db->update("UyeAdresler", $parametreler, [
		"uyeAdresId" => $uyeAdresId
	]);
}

if ($query) {
	echo 1;
}
