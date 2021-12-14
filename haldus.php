<?php
session_start();
if(!isset($_SESSION['tuvastamine'])){
    header('Location: loginAB.php');
    exit();
}
//admin - admin
//opilane-opilane
$a=true;
global $a;
require('conf.php');
global $yhendus;
if(isset($_REQUEST['kustuta'])){
    if($a===true){
        $kask=$yhendus->prepare("DELETE FROM konkurss WHERE id=?");
        $kask->bind_param("i",$_REQUEST['kustuta']);
        $kask->execute();
    }
}
//punktid nulliksUPDATE
if(isset($_REQUEST['punkt'])){
    $kask=$yhendus->prepare("UPDATE konkurss SET punktid=0 WHERE id=?");
    $kask->bind_param("i",$_REQUEST['punkt']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
if(isset($_REQUEST['komment'])){
    $kask=$yhendus->prepare("UPDATE konkurss SET kommentaar=' ' WHERE id=?");
    $kask->bind_param("i",$_REQUEST['komment']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//nimi naitamine avalik=1 UPDATE
if(isset($_REQUEST['avamine'])){
    $kask=$yhendus->prepare("UPDATE konkurss SET avalik=1  WHERE id=?");
    $kask->bind_param("i",$_REQUEST['avamine']);
    $kask->execute();
}
if(isset($_REQUEST['peitmine'])){
    $kask=$yhendus->prepare("UPDATE konkurss SET avalik=0  WHERE id=?");
    $kask->bind_param("i",$_REQUEST['peitmine']);
    $kask->execute();
}
//avalik petmine
if(!empty($_REQUEST['nimi'])){
    $kask=$yhendus->prepare("INSERT INTO konkurss(nimi,pilt,lisamisaeg) VALUES(?,?,NOW())");
    $kask->bind_param("ss",$_REQUEST['nimi'],$_REQUEST['pilt']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Fotokonkurssi - halduse leht</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<script>
    function myFunction() {
        if(confirm("Ta tatahab kustuta selle andmed?")==true){<?php $a=true; ?>}
        else if(confirm("Ta tatahab kustuta selle andmed?")==false){<?php $a=false; ?>}
    }
</script>
<body>
<nav>
    <ul>
        <li><a href="haldus.php">Administreerimise leht</a></li>
        <li><a href="konkurss.php">Kasutaja leht</a></li>
        <li><a href="https://github.com/DariaEvtina/Konkurs">GitHub</a></li>
    </ul>
</nav>
<div>
    <form action="logout.php" method="post">
        <p><?=$_SESSION['kasutaja']?> on sisse loginud</p>
        <input type="submit" value="Logi valja" name="logout">
    </form>
</div>
<h1>Fotokonkurssi - halduse leht</h1>
<?php
//tabeli sisu näitamine
$kask=$yhendus->prepare("SELECT id,nimi,pilt,lisamisaeg,punktid, avalik, kommentaar FROM konkurss");
$kask->bind_result($id,$nimi,$pilt,$aeg,$punktid, $avalik, $kommentaar);
$kask->execute();
echo"<table><tr><td></td><td></td><td></td><td>Nimi</td><td>Pilt</td><td>Lisamisaeg</td><td>punktid</td></tr>";
while($kask->fetch()){
    echo"<tr><td><a href='?kustuta=$id' onclick='myFunction()'>kustuta</a></td>";
    $avatext="Ava";
    $param="avamine";
    $seisund="Peidetud";
    if($avalik==1){
        $avatext="Peida";
        $param="peitmine";
        $seisund="Avatud";
    }
    echo"<td>$seisund</td>";
    echo"<td><a href='?$param=$id'>$avatext</a></td>";
    echo"<td>$nimi</td>";
    echo"<td><img src='$pilt' alt='pilt'</td>";
    echo"<td>$aeg</td>";
    echo"<td>$punktid</td>";
    echo"<td>$kommentaar</td>";
    echo"<td><a href='?punkt=$id'>punktid nulliks</a><br>";
    echo"<a href='?komment=$id'>komment nulliks</a></td>";
    echo"</tr>";
}
echo"</table>";
?>
<a href="lisamine.php" id="Hsubmit">Lissa tabelisse</a>
</body>
</html>