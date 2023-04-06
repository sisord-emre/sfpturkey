<?php
include "../System/Config.php";

$ulke = intval($_POST["ulke"]);

$list = $db->select("Iller","*",[
	"ilUlkeId" => $ulke,
	"ORDER" => [
		"ilAdi" => "ASC"
	]
]);
echo '<option value="">'.$fonk->getPDil("Seçiniz").'</option>';
foreach ($list as $item) {
	?>
	<option value="<?=$item['ilId'];?>"><?=$item['ilAdi'];?></option>
	<?php
}
?>
