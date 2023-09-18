<?php
include ("../../System/Config.php");

$Id=$_POST['Id'];

$detay = $db->get("Yorumlar", "*", [
  "yorumId" => $Id
]);

$adSoyad=$detay["yorumAdSoyad"];
$email=$detay["yorumEmail"];
if($detay['yorumUyeId']=="0"){
  $adSoyad=$sabitB['sabitBilgiSiteAdi'];
}
else if ($detay["yorumUyeId"]!="") {
  $uye = $db->get("Uyeler", "*", [
    "uyeId" =>$detay["yorumUyeId"]
  ]);
  $adSoyad=$uye["uyeAdi"]." ".$uye["uyeSoyadi"]." <span style='color:green'>*</span>";
  $email=$uye["uyeMail"];
}
?>
<div class="modal fade text-left" id="fadeIn" role="dialog" aria-hidden="true">
  <!-- detay modalı -->
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="baslikModal"><?=$fonk->getPDil("Yorum Cevapla")?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="icerikModal">

        <!-- Güncellenecek Kısımlar -->
        <div class="table-responsive">
          <form id="formpost" class="form" action="" method="post">
            <table class="table table-bordered table-striped">
              <tbody>
                <tr>
                  <td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("Kodu")?></b></td>
                  <td><?=$detay['yorumKodu']?></td>
                </tr>
                <tr>
                  <td style="vertical-align: middle;"><b><?=$fonk->getPDil("Adı Soyadı")?></b></td>
                  <td><?=$adSoyad?></td>
                </tr>
                <tr>
                  <td style="vertical-align: middle;"><b><?=$fonk->getPDil("Kaynak")?></b></td>
                  <td><?=$detay['urunKodu']?></td>
                </tr>
                <tr>
                  <td style="width: 27%;vertical-align: middle;"><b><?=$fonk->getPDil("İçerik")?></b><small style="color:red;margin-left:1rem">*</small></td>
                  <td>
                    <textarea class="form-control" id="yorumIcerik" name="yorumIcerik" rows="3" placeholder="..." required></textarea>
                  </td>
                </tr>
                <tr>
                  <input type="hidden" name="yorumUstYorumId" value="<?=$detay['yorumId']?>" />
                  <input type="hidden" name="token" value="<?=$_SESSION['token']?>" />
                  <td colspan="2" style="text-align:center"><button type="submit" class="btn btn-success"><i class="la la-floppy-o"></i> <?=$fonk->getPDil("Cevabı Kaydet")?></button></td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
        <!-- /Güncellenecek Kısımlar -->

      </div>
      <div class="modal-footer">
        <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal"><?=$fonk->getPDil("Kapat")?></button>
      </div>
    </div>
  </div>
</div>
<script>
$('#formpost').submit(function (e) {
  e.preventDefault(); //submit postu kesyoruz
  var data=new FormData(this);
  $.ajax({
    type: "POST",
    url: "Pages/Yorumlar/cevaplaKayıt.php",
    data:data,
    contentType:false,
    processData:false,
    success: function(res){
      if (res=='1') {
        $("#fadeIn").modal("hide");
        toastr.success('<?=$fonk->getPDil("Güncelleme Sağlandı.")?>');
      }else {
        alert(res);
      }
    },
    error: function (jqXHR, status, errorThrown) {
      alert("Result: "+status+" Status: "+jqXHR.status);
    }
  });
});
</script>
