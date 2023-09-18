<?php
include ("../../System/Config.php");

$menuId=$_POST['menuId'];//tabloadı istenirse burdan değiştirilebilir

///menu bilgileri alınıyor
$hangiMenu = $db->get("Menuler", "*", [
	"menuUstMenuId" => $menuId,
	"menuOzelGorunuruk" =>	1,
	"menuTipi" =>	2 //kayıt için 1 listeleme için 2 diğer sayfalar içim 3 yazılmalı****
]);

for($i=0;$i<Count($kullaniciYetkiler);$i++){//kullanıcının yetkilerini sorguluyoruz
	$kullaniciYetki= json_decode($kullaniciYetkiler[$i], true);

	if($kullaniciYetki['menuYetkiID']==$menuId){//menu id

		if($kullaniciYetki['listeleme']=="on")
		{$listelemeYetki=true;}//listeleme

		if($kullaniciYetki['ekleme']=="on")
		{$eklemeYetki=true;}//ekleme

		if($kullaniciYetki['silme']=="on")
		{$silmeYetki=true;}//silme

		if($kullaniciYetki['duzenleme']=="on")
		{$duzenlemeYetki=true;}//duzenleme

		if($kullaniciYetki['excel']=="on")
		{$tamExcelYetki=true;}//tam excel
	}
}
if(!$listelemeYetki)
{
	//yetki yoksa gözükecek yazi
	echo '<div class="alert alert-icon-right alert-warning alert-dismissible mb-2" role="alert">
	<span class="alert-icon"><i class="la la-warning"></i></span>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">×</span>
	</button>
	<strong>'.$fonk->getPDil("Yetki!").' </strong> '.$fonk->getPDil("Bu Menüye Erişim Yetkiniz Bulunmamaktadır.").'
	</div>';
	exit;
}

$_POST["bitTarihFiltre"]=date("Y-m-d", strtotime($_POST["bitTarihFiltre"]."+1 day"));
?>

<div id="line-chart-siparis"></div>

<script src="Assets/app-assets/vendors/js/charts/d3.min.js"></script>
<script src="Assets/app-assets/vendors/js/charts/c3.min.js"></script>
<?php
$gunler="";
$sayimList="";
$begin = new DateTime($_POST["basTarihFiltre"]);
$end = new DateTime($_POST["bitTarihFiltre"]);
$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);
foreach ($period as $dt) {
	$sartlar=["siparisSiparisDurumSiparisDurumId" => 2 , "siparisKayitTarihi[<>]" => [$dt->format("Y-m-d"),date("Y-m-d", strtotime($dt->format("Y-m-d")."+1 day"))]];
	
	if ($_POST["siparisDurumIdFiltre"]!="" & count($_POST["siparisDurumIdFiltre"])>0) {
		$sartlar=array_merge($sartlar,["siparisSiparisDurumId" => $_POST["siparisDurumIdFiltre"]]);
	}

	$sayim = $db->count("SiparisSiparisDurumlari",[
		"[>]Siparisler" => ["SiparisSiparisDurumlari.siparisSiparisDurumSiparisId" => "siparisId"]
	],"*",$sartlar);

	$sayimList.=",".$sayim;
	$gunler.=",'".$dt->format("d-m-Y")."'";
}
$sayimList=ltrim($sayimList,",");
$gunler=ltrim($gunler,",");
?>
<script type="text/javascript">
$(document).ready(function(){
	var lineChartSiparis = c3.generate({
		bindto: '#line-chart-siparis',
		size: { height: 400 },
		point: {
			r: 4
		},
		color: {
			pattern: ['#28d094']
		},
		data: {
			columns: [
				['<?=$fonk->getPDil("Satış Miktarları")?>', <?=$sayimList?>]
			]
		},
		legend: {
			//position: 'inset',//tablo üzerinde gözükmesi için
			inset: {
				anchor: 'top-left',
				x: 0,
				y: 0,
				step: 1
			},
		},
		grid: {
			y: {
				show: true,
				stroke: '#ff0'
			}
		},axis: {
			x: {
				type: 'category',
				categories: [<?=$gunler?>]
			}
		}
	});
	// Resize chart on sidebar width change
	$(".menu-toggle").on('click', function() {
		lineChartSiparis.resize();
	});
});
</script>
<!-- !istatistikler-->
