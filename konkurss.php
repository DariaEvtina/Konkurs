<?php
require('conf.php');
global $yhendus;
//punktide lisamine UPDATE
if(isset($_REQUEST['punkt'])){
    $kask=$yhendus->prepare("UPDATE konkurss SET punktid=punktid+1 WHERE id=?");
    $kask->bind_param("i",$_REQUEST['punkt']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Fotokonkurss</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1>Fotokonkurss "cats"</h1>
<?php
//tabeli sisu näitamine
$kask=$yhendus->prepare("SELECT id,nimi,pilt,lisamisaeg,punktid, avalik FROM konkurss");
$kask->bind_result($id,$nimi,$pilt,$aeg,$punktid, $avalik);
$kask->execute();
echo"<table><tr><td>Nimi</td><td>Pilt</td><td>Lisamisaeg</td><td>punktid</td></tr>";
while($kask->fetch()){
    if($avalik==1){
        echo"<tr><td>$nimi</td>";
        echo"<td><img src='$pilt' alt='pilt'</td>";
        echo"<td>$aeg</td>";
        echo"<td>$punktid</td>";
        echo"<td><a href='?punkt=$id'>+1 punkt</a></td>";
        echo"</tr>";
    }

}
echo"</table>";
?>
</body>
</html>