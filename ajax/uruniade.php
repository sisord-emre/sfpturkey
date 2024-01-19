<?php 
include ("../Panel/System/Config.php");
extract($_POST);

if($_POST['formdan']=="1")
{
    $secret = $sabitB['sabitBilgiPrivateRecaptcha'];
	$response=$_POST["g-recaptcha-response"];
	$verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
	$captcha_success=json_decode($verify);

	if($captcha_success->success==false)
	{
		echo '0';
		exit;
	}
    else if($captcha_success->success==true)
    {
        $parametreler=array(
            'iadeTalepAdi' => $iadeTalepAdi,
            'iadeTalepSoyadi' => $iadeTalepSoyadi,
            'iadeTalepEmail' => $iadeTalepEmail,
            'iadeTalepTelefon' => $iadeTalepTelefon,
            'iadeTalepSiparisNo' => $iadeTalepSiparisNo,
            'iadeTalepSiparisTarihi' => $iadeTalepSiparisTarihi,
            'iadeTalepUrunAdi' => $iadeTalepUrunAdi,
            'iadeTalepUrunKodu' => $iadeTalepUrunKodu, 
            'iadeTalepUrunAdet' => $iadeTalepUrunAdet,
            'iadeTalepIadeNeden' => $iadeTalepIadeNeden,
            'iadeTalepUrunAcildimi' => $iadeTalepUrunAcildimi,
            'iadeTalepDetay' => $iadeTalepDetay,
            'iadeTalepDurumu' => 0,
            'iadeTalepKayitTarihi' => date("Y-m-d H:i:s")
        );
        
        $query = $db->insert('IadeTalepleri', $parametreler);

        if($query)
        {
            echo '3';
        }
        else
        {
            echo '2';
        }
    }
}
