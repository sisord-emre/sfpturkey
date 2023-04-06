<?php
include "../System/Config.php";

$il = intval($_POST["il"]);

$list = $db->select("Ilceler","*",[
	"ilceIlId" => $il,
	"ORDER" => [
		"ilceAdi" => "ASC"
	]
]);
echo '<option value="">'.$fonk->getPDil("Seçiniz").'</option>';
foreach ($list as $item) {
	?>
	<option value="<?=$item['ilceId'];?>"><?=$item['ilceAdi'];?></option>
	<?php
}
?>
