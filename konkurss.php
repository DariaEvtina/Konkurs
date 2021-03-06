<?php
session_start();
if(!isset($_SESSION['tuvastamine'])){
    header('Location: loginAB.php');
    exit();
}
require('conf.php');
global $yhendus;
//punktide lisamine UPDATE
if(isset($_REQUEST['punkt'])){
    $kask=$yhendus->prepare("UPDATE konkurss SET punktid=punktid+1 WHERE id=?");
    $kask->bind_param("i",$_REQUEST['punkt']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//uue kommentaari lisamine
if(isset($_REQUEST['uus_komment'])){
    $kask=$yhendus->prepare("UPDATE konkurss SET kommentaar=CONCAT(kommentaar, ?) WHERE id=?");
    $kommentlisa= $_REQUEST['komment']."\n";
    $kask->bind_param("si",$kommentlisa, $_REQUEST['uus_komment']);
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
<?php
if($_SESSION['onAdmin']==1){
    echo"<nav><ul><li><a href='haldus.php'>Administreerimise leht</a></li><li><a href='konkurss.php'>Kasutaja leht</a></li><li><a href='https://github.com/DariaEvtina/Konkurs'>GitHub</a></li></ul></nav>";
}
else{
    echo"<nav><ul><li><a href='konkurss.php'>Kasutaja leht</a></li><li><a href='https://github.com/DariaEvtina/Konkurs'>GitHub</a></li></ul></nav>";
}
?>
<div>
    <form action="logout.php" method="post">
        <p><?=$_SESSION['kasutaja']?> on sisse loginud</p>
        <input type="submit" value="Logi valja" name="logout">
    </form>
</div>
<h1>Fotokonkurss "cats"</h1>
<?php
//tabeli sisu näitamine
$kask=$yhendus->prepare("SELECT id,nimi,pilt,kommentaar,punktid, avalik FROM konkurss");
$kask->bind_result($id,$nimi,$pilt,$kommentaar,$punktid, $avalik);
$kask->execute();
if($_SESSION['onAdmin']==1) {
    echo "<table><tr><td>Nimi</td><td>Pilt</td><td>Kommentaar</td><td>punktid</td></tr>";
}
else{
    echo "<table><tr><td>Nimi</td><td>Pilt</td><td>Kommentaar</td><td>Lisa Kommentaar</td><td>punktid</td></tr>";
}
while($kask->fetch()){
    if($avalik==1){
        echo"<tr><td>$nimi</td>";
        echo"<td><img src='$pilt' alt='pilt'</td>";
        echo"<td>".nl2br($kommentaar)."</td>";
        if($_SESSION['onAdmin']==0) {
            echo "<td> 
        <form action='?'>
        <input type='hidden' name='uss_komment' value=$id>
        <input type='text' name='komment'>
        <input type='submit' value='OK'>
        </form>
        </td>";
        }
        echo"<td>$punktid</td>";
        if($_SESSION['onAdmin']==0) {
            echo "<td><a href='?punkt=$id'>+1 punkt</a></td>";
        }
        echo"</tr>";
    }

}
echo"</table>";
?>
</body>
</html>