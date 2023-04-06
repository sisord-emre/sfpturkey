<?php
include ("../../System/Config.php");

extract($_POST);//POST parametrelerini değişken olarak çevirir

if($tip==1){
  $gorsel = $db->get("Urunler","*",[
    "urunId" => $Id,
  ]);
  //$gorsel['urunBaseUrl']="https://localhost/smartcaterings/Images/Urunler/";
  $gorselUrl=$gorsel['urunBaseUrl'].$gorsel['urunGorsel'];
  if($kayit==1){
    $kontrol=$fonk->imageCropSave("../../../Images/Urunler/".$gorsel['urunGorsel'],'../../../Images/Urunler/',explode(".",$gorsel['urunGorsel'])[0],$left,$top,$width,$height,jpg);
    if($kontrol==1){
      echo 1;
    }else{
      echo $kontrol;
    }
    exit;
  }
}
else if($tip==2){
  $gorsel = $db->get("UrunGorselleri",[
    "[>]Urunler" => ["UrunGorselleri.urunGorselUrunId" => "urunId"],
  ],"*",[
    "urunGorselId" => $Id,
  ]);
  //$gorsel['urunBaseUrl']="https://localhost/smartcaterings/Images/Urunler/";
  $gorselUrl=$gorsel['urunBaseUrl'].$gorsel['urunGorselLink'];
  if($kayit==1){
    $kontrol=$fonk->imageCropSave("../../../Images/Urunler/".$gorsel['urunGorselLink'],'../../../Images/Urunler/',explode(".",$gorsel['urunGorselLink'])[0],$left,$top,$width,$height,jpg);
    if($kontrol==1){
      echo 1;
    }else{
      echo $kontrol;
    }
    exit;
  }
}
?>
<div class="modal fade text-left" id="fadeIn" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <!-- detay modalı -->
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="baslikModal"><?=$fonk->getPDil("Görsel Kesim İşlemleri")?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="icerikModal">
        <!-- Güncellenecek Kısımlar -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-content">
                <div class="card-body">
                  <div class="row mb-1">
                    <div class="col-md-9">
                      <div class="img-container overflow-hidden">
                        <img class="img-crop img-fluid" src="<?=$gorselUrl?>?v=<?=uniqid();?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <div class="docs-preview clearfix">
                          <div class="img-preview preview-lg img-fluid"></div>
                        </div>
                      </div>
                      <div class="form-group text-center">
                        <button class="btn btn-outline-blue" type="button" id="kesimKayitButton" onclick="KesVeKaydet()"><?=$fonk->getPDil("Kesim ve Kayıt")?></button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /Güncellenecek Kısımlar -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal"><?=$fonk->getPDil("Kapat")?></button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
setTimeout(function(){ ImageCrop('img-crop',"img-preview",<?=$width?>,<?=$height?>); }, 700);
function KesVeKaydet(){
  document.getElementById("kesimKayitButton").disabled = true;
  var result=JSON.parse(CropImageInfo());
  $.ajax({
    type: "POST",
    url: "Pages/Urunler/gorselSecimModal.php",
    data:{'Id':<?=$Id?>,'tip':<?=$tip?>,'left':parseInt(result.x),'top':parseInt(result.y),'width':<?=$width?>,'height':<?=$height?>,'kayit':1},
    success: function(res){
      if(res==1){
        $("#fadeIn").modal("hide");
        setTimeout(function(){ SayfaYenile(); }, 700);
      }else{
        alert(res);
      }
      document.getElementById("kesimKayitButton").disabled = false;
    },
    error: function (jqXHR, status, errorThrown) {
      alert("Result: "+status+" Status: "+jqXHR.status);
    }
  });
}
</script>
