<?php
include ("../../System/Config.php");
$primaryId=$_POST['Id'];
?>
<div class="card-body">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-content collapse show">
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th><?=$fonk->getPDil("Görsel")?></th>
                  <th><?=$fonk->getPDil("Link")?></th>
                  <th><?=$fonk->getPDil("Kayit Tarihi")?></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $dosyalar = glob("../../Images/Temp/*");
                $satir=0;
                foreach($dosyalar as $item){
                  $item=end(explode("/",$item));
                  $satir++;
                  ?>
                  <tr id="satirAltBirim_<?=$satir?>">
                    <th scope="row"><?=$satir?></th>
                    <td><a href="<?=$sabitB["sabitBilgiSiteUrl"]."Panel/Images/Temp/".$item?>" target="_blank"><img src="<?=$sabitB["sabitBilgiSiteUrl"]."Panel/Images/Temp/".$item?>" height="50px"></a></td>
                    <td id="td-<?=$satir?>" onclick="kopyala('td-<?=$satir?>',2)"><?=$sabitB["sabitBilgiSiteUrl"]."Panel/Images/Temp/".$item?></td>
                    <td>
                      <button type="button" onclick="altBirimSil('<?=$satir?>','<?=$item?>');" class="btn btn-danger btn-sm"><i class="la la-trash-o"></i> <?=$fonk->getPDil("Sil")?></button>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
