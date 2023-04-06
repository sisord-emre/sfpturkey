<?php
if ($_GET["token"]="panel16400") {
  include ("System/Config.php");
  $tablolar = $db->query("select * from information_schema.tables where table_schema = 'public' and table_type = 'BASE TABLE' order by table_name");
  foreach($tablolar as $tablo){
    $key = $db->query("select column_name,column_default from information_schema.COLUMNS where table_name = '".$tablo["table_name"]."'")->fetch();
    $seqKey=str_replace("\"","",$key["column_default"]);
    $seqKey=str_replace("nextval('","",$seqKey);
    $seqKey=str_replace("'::regclass)","",$seqKey);
    $last = $db->query('SELECT (MAX("'.$key["column_name"].'") + 1) as lastid FROM "'.$tablo["table_name"].'"')->fetch();
    if ($last["lastid"]!="") {
      echo $tablo["table_name"]." - ".$key["column_name"]." - ".$seqKey." - ".$last["lastid"]."<br>";
      $db->query('ALTER SEQUENCE "'.$seqKey.'" RESTART WITH '.$last["lastid"])->fetch();
    }
  }
}
?>
