<?php
$data = [ ['img 1','title 1','desc1','', ''],['img2','title2','desc2','', ''] ] ;
$datafields = ['imp', 'title', 'desc', 'created_at', 'updated_at']; 

function makeQuerVals($values): string {
    $one_quer_str = "";
    foreach($values as $one_row){
        $one_quer_str .= "(";
        foreach($one_row as $item){
            $one_quer_str .= "'".$item."',";
        }
        $one_quer_str = rtrim($one_quer_str, ",");
        $one_quer_str .= "),";
    }
    return rtrim($one_quer_str, ",");
}

$sql = "INSERT INTO table (".implode(", ", $datafields ).") VALUES ".makeQuerVals($data)." ";
$db->qurey($sql);
