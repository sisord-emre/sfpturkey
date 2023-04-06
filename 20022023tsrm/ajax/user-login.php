<?php 
include ("../Panel/System/Config.php");
extract($_POST);

if($_POST['formdan']=="2")
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
        $mailKontrol = $db->get('Uyeler', "*", [
            'uyeMail'=> $uyeMail
        ]);

		if($mailKontrol)
		{
			$uye = $db->get("Uyeler","*",[
                "uyeMail" => $uyeMail,
                "uyeSifre" => hash("sha256", md5($uyeSifre))
            ]);
				
			if($uye)
			{	
				if($uye["uyeDurum"] == 1)
				{
					$_SESSION['uyeKodu'] = $uye['uyeKodu'];
					$_SESSION['uyeFirmaAdi'] = $uye['uyeFirmaAdi'];
					$_SESSION['uyeAdi'] = $uye['uyeAdi'];
					$_SESSION['uyeSoyadi'] = $uye['uyeSoyadi'];
					echo '3';
				}
				else {
					echo '4';
				}
			}
			else
			{
				echo '2';
			}
		}
		else
		{
			echo '1';
		}
	}
}
?>