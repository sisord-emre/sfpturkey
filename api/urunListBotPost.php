<?php
include('../Panel/System/Config.php');
if ($_GET["ApiKey"] == "8bYuhtCv5997aGgCxzsLpXgJuCRMFqEp") {
	//$siparisid = $_POST['siparisid'];
	$siparisid = 11;
	$siparisStokPostList = $db->get("SiparisStokPost", "*", [
		"siparisStokPostSiparisId" => $siparisid
	]);

	$userLink = $fonk->akinSoftConnection('wlogin', 'MUHASEBE', '6512bd43d9caa6e02c990b0a82652dca', '202217518', '535AD', '60');
	$userLink = base64_encode($userLink);
	$data = "DATA=" . $userLink;
	$response = $fonk->akinSoftPostApi('http://195.174.216.24:3056/', $data);
	$decodeLink = base64_decode($response);
	$decodeLinkFindOne = explode("&", $decodeLink);
	$xmlValue = $siparisStokPostList["siparisStokPostDataXml"];
	$editHTTPLink = $fonk->akinSoftPostParametreApi($decodeLinkFindOne[1], "postxml_stokhrk", "01", "2022", $xmlValue);

	$encodedString = urlencode(base64_encode($editHTTPLink));
	$encryptedHTTPLink = 'DATA=' . $encodedString;

	$response2 = $fonk->akinSoftPostApi('http://195.174.216.24:3056/', $encryptedHTTPLink);
	$decodeLink2 = base64_decode($response2);

	if (strstr($decodeLink2, "XML_POST_OK^")) {
		echo "1";
	} else {
		echo $decodeLink2;
	}
}
