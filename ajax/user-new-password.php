<?php 
include ("../Panel/System/Config.php");
extract($_POST);

if($_POST['formdan']=="4")
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

        if($uyeSifre == $uyeSifreTekrar)
        {
            $parametreler=array(
				'uyeSifre' => hash("sha256", md5($uyeSifre))
			);

            $query = $db->update("Uyeler", $parametreler, [
                "uyeMail" => $sifreUnuttumEmail
            ]);

            if($query)
            {
                echo '3';
            }
            else 
            {
                echo '2';
            }
        }
		else
		{
			echo '4';
		}
	}
}
