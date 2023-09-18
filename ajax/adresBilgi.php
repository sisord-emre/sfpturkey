<?php
include("../Panel/System/Config.php");

extract($_POST);

$uye = $db->get("Uyeler", "*", [
	"uyeSessionKey" => $_SESSION['uyeSessionKey']
]);
if (!$uye) {
	echo '<script> window.location.href="' . $sabitB['sabitBilgiSiteUrl'] . '"; </script>';
	exit;
}

$uyeAdres = $db->get("UyeAdresler", [
	"[<]Ulkeler" => ["UyeAdresler.uyeAdresUlkeId" => "ulkeId"],
	"[<]Iller" => ["UyeAdresler.uyeAdresIlId" => "ilId"],
	"[<]Ilceler" => ["UyeAdresler.uyeAdresIlceId" => "ilceId"]
], "*", [
	"uyeAdresUyeId" => $uye["uyeId"],
	"uyeAdresId" => $uyeAdresId
]);
?>
<table class="checkout-review-order-table" style="margin: unset;">
	<thead>
		<tr>
			<th class="product-name" colspan="2"><?= $uyeAdres["uyeAdresAdi"] ?></th>
		</tr>
	</thead>
	<tbody>
		<tr class="cart_item">
			<td class="product-name"><strong><?= $uyeAdres["ulkeAdi"] . "/" . $uyeAdres["ilAdi"] . "/" . $uyeAdres["ilceAdi"] ?></strong></td>
		</tr>
		<tr class="cart_item">
			<td class="product-name"><?= $uyeAdres["uyeAdresBilgi"] ?></td>
		</tr>
		<tr class="cart_item">
			<td class="product-name"><strong><?= $fonk->sqlToDateTime($uyeAdres["uyeAdresKayitTarihi"]) ?></strong></td>
		</tr>
	</tbody>
</table>