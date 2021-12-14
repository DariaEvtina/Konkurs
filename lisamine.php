<?php
require('conf.php');
global $yhendus;
//avalik petmine
if(!empty($_REQUEST['nimi'])){
    $kask=$yhendus->prepare("INSERT INTO konkurss(nimi,pilt,lisamisaeg) VALUES(?,?,NOW())");
    $kask->bind_param("ss",$_REQUEST['nimi'],$_REQUEST['pilt']);
    $kask->execute();
    header("Location: haldus.php");
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Fotokonkurssi - halduse leht</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h2>Uue pilti lissamine</h2>
<form action="?">
    <input type="text" name="nimi" placeholder="uus nimi">
    <br>
    <textarea name="pilt"> pildi linki aadress</textarea>
    <br>
    <input type="submit" value="Lisa..">
</form>
<a href="haldus.php" id="Lsubmit">Fotokonkurssi - halduse leht</a>
</body>
</html>