<?php
include("../../System/Config.php");

$urunListeleme = $db->select("Urunler","*",[
    "urunEnCokSatan" => 1,
    "ORDER" => [
        "urunId" => "ASC"
    ]
]);

if($urunListeleme){
?>

<section id="html5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?= $fonk->getPDil("En Çok Satan Urunlerin Listesi") ?></h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
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
                                                        <th style="width:150px;text-align:center"><?=$fonk->getPDil("İşlemler")?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sayac=1;
                                                    foreach ($urunListeleme as $list) {
                                                    ?>
                                                        <tr id="trSatir-<?=$list["urunId"]; ?>">
                                                            <td><?= $sayac; ?></td>
                                                            <td><?= $list['urunKodu']; ?></td>
                                                            <td style="text-align:center">
																<div class="btn-group btn-group-sm" role="group">
                                                                    <button type="button" onclick="veriSil('<?=$list['urunId'];?>');"  class="btn btn-danger"><i class="la la-trash-o"></i> <?=$fonk->getPDil("Sil")?></button>
                                                                </div>
															</td>
                                                        </tr>
                                                    <?php $sayac++; } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>