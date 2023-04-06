<?php
include ("../../System/Config.php");

$silID=$_POST['silID'];
$dosya=$_POST['dosya'];

if($silID!="" && $dosya!=""){
  unlink('../../Images/Temp/'.$dosya);
}
else{
  $dosyalar = glob("../../Images/Temp/*");
  foreach($dosyalar as $item){
    $item=end(explode("/",$item));
    unlink('../../Images/Temp/'.$item);
  }
}
echo '1';
?>
