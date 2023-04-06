<?php
include("../../System/Config.php");

$menuId = $_POST['menuId']; //menu id alınıyor

///menu bilgileri alınıyor
$hangiMenu = $db->get("Menuler", "*", [
	"menuUstMenuId" => $menuId,
	"menuOzelGorunuruk" =>	1,
	"menuTipi" =>	1 //kayıt için 1 listeleme için 2 diğer sayfalar içim 3 yazılmalı****
]);

for ($i = 0; $i < Count($kullaniciYetkiler); $i++) { //kullanıcının yetkilerini sorguluyoruz
	$kullaniciYetki = json_decode($kullaniciYetkiler[$i], true);

	if ($kullaniciYetki['menuYetkiID'] == $menuId) { //menu id

		if ($kullaniciYetki['listeleme'] == "on") {
			$listelemeYetki = true;
		} //listeleme

		if ($kullaniciYetki['ekleme'] == "on") {
			$eklemeYetki = true;
		} //ekleme

		if ($kullaniciYetki['silme'] == "on") {
			$silmeYetki = true;
		} //silme

		if ($kullaniciYetki['duzenleme'] == "on") {
			$duzenlemeYetki = true;
		} //duzenleme

	}
}
if (!$eklemeYetki && !$duzenlemeYetki) {
	//yetki yoksa gözükecek yazi
	echo '<div class="alert alert-icon-right alert-warning alert-dismissible mb-2" role="alert">
	<span class="alert-icon"><i class="la la-warning"></i></span>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">×</span>
	</button>
	<strong>' . $fonk->getPDil("Yetki!") . ' </strong> ' . $fonk->getPDil("Bu Menüye Erişim Yetkiniz Bulunmamaktadır.") . '
	</div>';
	exit;
}
//Listeleme Yetkisi Var
$tableName = "Urunler"; //tabloadı istenirse burdan değiştirilebilir

$tabloPrimarySutun = "urunId"; //primarykey sutunu

$baslik = "En Çok Satan Urunler Sayfası"; //başlıkta gözükecek yazı menu adi

$duzenlemeSayfasi = $tableName . '/' . strtolower($tableName) . 'Kayit.php';
$listelemeSayfasi = $tableName . "/" . strtolower($tableName) . "Listeleme.php";
$kartGonderimSayfasi='EnCokSatan/encoksatanKayit.php';

$primaryId = $_POST['update']; //düzenle isteği ile gelen

if ($_POST['formdan'] != "1") {
	//sayfayı görüntülenme logları
	$fonk->logKayit(6, $_SERVER['REQUEST_URI'] . "?primaryId=" . $primaryId); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
}

////güncllenecek parametreler***
//Forumdan gelenler
extract($_POST); //POST parametrelerini değişken olarak çevirir
////güncllenecek parametreler***

if ($_POST['formdan'] == "1") 
{
	$fonk->csrfKontrol();

	if ($primaryId != "") 
	{
		//günclelemedeki parametreler
		$parametreler = array(
			'urunEnCokSatan' => $urunEnCokSatan
		);
	}

	if ($primaryId != "") 
	{
		$fonk->logKayit(2, $tableName . ' ; ' . $primaryId . ' ; ' . json_encode($parametreler)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
		///güncelleme
		$query = $db->update($tableName, $parametreler, [
			$tabloPrimarySutun => $primaryId
		]);
	}

	if ($query) {
		echo '
		<div class="alert alert-success alert-dismissible mb-2" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
		</button>
		<strong>' . $fonk->getPDil("Başarılı!") . '</strong> ' . $fonk->getPDil("Kayıt İşlemi Başarıyla Gerçekleşmiştir.") . '
		</div>';
	} 
	else {
		echo '
		<div class="alert alert-danger alert-dismissible mb-2" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
		</button>
		<strong>' . $fonk->getPDil("Hata!") . '</strong> ' . $fonk->getPDil("Kayıt Esnasında Bir Hata Oluştu. Lütfen Tekrar Deneyiniz.") . '(' . $db->error . ')
		</div>';
	}
}
//update ise bilgiler getiriliyor

	$Listeleme = $db->get($tableName, "*");


    if ($_POST["page"]=="" || $_POST["page"]==0) {
        $_POST["page"]=1;
    }
    extract($_POST);//POST parametrelerini değişken olarak çevirir
    ///----- Saylafama Sorgu
    $sartlar = [];
    $orSartlar = [];
    $andSartlar = [];
    
    if ($Ara!="") {
        $andSartlar=array_merge($andSartlar,["urunKodu" => $Ara]);
    }
    if (count($orSartlar) > 0) {
        $sartlar = [
            "AND" => [
                "OR" => $orSartlar,
            ]
        ];
        $sartlar["AND"] = array_merge($sartlar["AND"], $andSartlar);
    } else {
        $sartlar = $andSartlar;
    }
    //toplam veri
    $totalRecord = $db->count("Urunler", $sartlar);
    
    $pageLimit = 10;
    // sayfa parametresi? Örn: index.php?page=2 [page = $pageParam]
    $pageParam = 'page';
    // limit için start ve limit değerleri hesaplanıyor
    $pagination = $fonk->paginationAjax($totalRecord, $pageLimit, $pageParam);
    
    if($_POST["sirala"]!=""){
        if($_SESSION["sirala"][0]==$_POST["sirala"] && $_SESSION["sirala"][1]=="ASC"){
            $_SESSION["sirala"][0]=$_POST["sirala"];
            $_SESSION["sirala"][1]="DESC";
        }else{
            $_SESSION["sirala"][0]=$_POST["sirala"];
            $_SESSION["sirala"][1]="ASC";
        }
    }else if($_SESSION["sirala"][2]!=$_SERVER['REQUEST_URI']){
        $_SESSION["sirala"][0]="urunId";
        $_SESSION["sirala"][1]="DESC";
    }
    $_SESSION["sirala"][2]=$_SERVER['REQUEST_URI'];
    
    $sartlar=array_merge($sartlar,[
        "ORDER" => [
            $_SESSION["sirala"][0] => $_SESSION["sirala"][1]
        ],
        'LIMIT' => [$pagination['start'], $pagination['limit']]
    ]);
    //normal sorgumuz
    $listelemeKart = $db->select("Urunler","*",$sartlar);
    ///----- Saylafama Sorgu
    if($Ara!=""){echo '<script type="text/javascript">document.getElementById("Ara").value="'.$Ara.'"; $("#Ara").focus();</script>';}

?>
<!-- Basic form layout section start -->
<section id="basic-form-layouts">
	<div class="row match-height">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title" id="basic-layout-colored-form-control"><?= $fonk->getPDil($baslik) ?></h4>
				</div>

                <div class="col-12">
					<div class="heading-elements">
						<input type="search" onkeyup="AramaYap(event)" name="Ara" id="Ara" value="<?=$Ara?>" class="form-control form-control-sm" style="display: initial;width: auto;margin-right: 0.75rem; float:right;" placeholder="<?=$fonk->getPDil("Arama")?>">
					</div>

				</div>
                                    
				<div class="card-content collapse show">
					<div class="card-body card-dashboard">
						<!-- Hoverable rows start -->
						<div class="table-responsive">
							<div class="col-12">
								<div class="card">
									<div class="card-content collapse show">
										<div class="table-responsive">
											<table class="table table-bordered table-striped table-hover mb-0">
												<thead>
													<tr>
														<th><?=$fonk->getPDil("ID")?></th>
														<th><?=$fonk->getPDil("Urun Kodu")?></th>
														<th style="width:250px;text-align:center"><?=$fonk->getPDil("İşlemler")?></th>
													</tr>
												</thead>
												<tbody>
                                                    <form id="formpost" class="form" action="" method="post">
													<?php
													foreach($listelemeKart as $list){
                                                        $kartVarmi = $db->get("Urunler", "*", [
                                                            "urunId" => $list["urunId"],
															"urunEnCokSatan" => 1
                                                        ]);
                                                        if(!$kartVarmi){
														?>
														<tr id="trSatir-<?=$list["urunId"];?>">
															<td><?=$list["urunId"];?></td>

															<!-- Güncellenecek Kısımlar -->
															<td>
                                                                <?=$list['urunKodu'];?>
                                                                <input type="hidden" class="form-control" id="urunKampanya_<?=$list['urunId'];?>" name="urunEnCokSatan" value="1">
                                                            </td>
															<!-- /Güncellenecek Kısımlar -->

															<td style="text-align:center">
																<div class="btn-group btn-group-sm" role="group">
                                                                    <?php if($duzenlemeYetki){?><button type="button" onclick="kartEkle('<?=$list['urunId'];?>')" class="btn btn-success"><i class="la la-add"></i> <?=$fonk->getPDil("Ekle")?></button><?php } ?>
																</div>
															</td>
														</tr>
													<?php } }?>
                                                    </form>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="text-center mb-2">
									<nav aria-label="Page navigation">
										<ul class="pagination justify-content-center pagination-separate pagination-round">
											<?php echo $fonk->showPaginationAjax('javascript:sayfalama('.$menuId.',[page]);');?>
										</ul>
									</nav>
								</div>
								<small style="float:left"><?=$fonk->getPDil("Toplam Kayıt")?>: <?=$totalRecord?></small> <small style="float:right"><?=$fonk->getPDil("Toplam Sayfa")?>: <?=ceil($totalRecord/$pageLimit)?></small>
							</div>
						</div>
						<!-- Hoverable rows end -->
					</div>
				</div>

				<div id="kartList">

				</div>


			</div>
		</div>
	</div>
</section>
<!-- // Basic form layout section end -->

<?php include("../../Scripts/kayitJs.php"); ?>
<script type="text/javascript">

    function sayfalama(menuId,page){
        var Ara=document.getElementById("Ara").value;
        $('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
        sessionStorage.setItem("dPage",page);
        sessionStorage.setItem("dSearch",Ara);
        sessionStorage.setItem("dLink",_sayfa);
        var data=new FormData();
        data.append("menuId",menuId);
        data.append("page",page);
        data.append("Ara",Ara);
        data.append("update",<?=$Listeleme[$tabloPrimarySutun]?>);
        $.ajax({
            type: "POST",
            url: "<?=$_SERVER['REQUEST_URI']?>",
            data:data,
            contentType:false,
            processData:false,
            success: function(res){
                $('#Sayfalar').html(res);
            },
            error: function (jqXHR, status, errorThrown) {
                alert("Result: "+status+" Status: "+jqXHR.status);
            }
        });
    }


    function AramaYap(e){
        var Ara=document.getElementById("Ara").value;
        if(e.keyCode == 13){
            $('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
            sessionStorage.setItem("dPage",1);
            sessionStorage.setItem("dSearch",Ara);
            sessionStorage.setItem("dLink",_sayfa);
            $.ajax({
                type: "POST",
                url: "<?=$_SERVER['REQUEST_URI']?>",
                data:{'menuId':'<?=$menuId?>','Ara':Ara,'page':1,'update':'<?=$Listeleme[$tabloPrimarySutun]?>'},
                success: function(gelenSayfa){
                    $('#Sayfalar').html(gelenSayfa);
                },
                error: function (jqXHR, status, errorThrown) {
                    alert("Result: "+status+" Status: "+jqXHR.status);
                }
            });
        }
    }

    $(document).ready(function() {
        document.getElementById("Ara").value=sessionStorage.getItem("dSearch");
        var Ara=document.getElementById("Ara").value;
        if ("<?=$_SERVER['REQUEST_URI']?>".indexOf(sessionStorage.getItem("dLink")) && sessionStorage.getItem("dPage")!="" && sessionStorage.getItem("dPage")!=0 && sessionStorage.getItem("dPage")!="<?=$_POST["page"]?>") {
            if(sessionStorage.getItem("dPage")!="null" && sessionStorage.getItem("dPage")!=null && sessionStorage.getItem("dPage")!="NaN"){
                sayfalama(<?=$menuId?>,parseInt(sessionStorage.getItem("dPage")));
            }
            return false;
        }
       

        kartListele();
    });


    function kartEkle(urunId){
        urunEnCokSatan=document.getElementById("urunKampanya_"+urunId).value;

        $.ajax({
            type: "POST",
            url: "Pages/EnCokSatan/encoksatanUrunEkle.php",
            data:{'urunId':urunId,'urunEnCokSatan':urunEnCokSatan},
            success: function(gelenSayfa){
                if(gelenSayfa==1){
                    document.getElementById("urunKampanya_"+urunId).value="";
                    SayfaGetir('<?=$menuId?>','<?=$kartGonderimSayfasi?>','<?=$Listeleme[$tabloPrimarySutun]?>');
                }
                else{
                    alert("Bu Urun Kodu Daha Önce Girilmiş.");
                }
            }
        });
    }

    function kartListele(){
        $.ajax({
            type: "POST",
            url: "Pages/EnCokSatan/encoksatanUrunListele.php",
            data:{'urunId':1},
            success: function(gelenSayfa){
                $('#kartList').html(gelenSayfa);
            }
        });
    }

    function veriSil(sil){
        if(confirm('<?=$fonk->getPDil("Silmek İstediğinize Emin misiniz ?")?>')) {
            $.ajax({
                type: "POST",
                url: "Pages/EnCokSatan/encoksatanUrunSil.php",
                data:{'sil':sil},
                success: function(gelenSayfa){
                    if (gelenSayfa==1) {
                        document.getElementById('trSatir-'+sil).style.display="none";
                        SayfaGetir('<?=$menuId?>','<?=$kartGonderimSayfasi?>','<?=$Listeleme[$tabloPrimarySutun]?>');
                    }
                    else {
                        alert("Silme Esnasında Bir Hata Oluştu. Lütfen Tekrar Deneyiniz.");
                    }
                },
                error: function (jqXHR, status, errorThrown) {
                    alert("Result: "+status+" Status: "+jqXHR.status);
                }
            });
        }
    }

</script>