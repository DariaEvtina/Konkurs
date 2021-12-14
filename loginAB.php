<?php
//LOGIN VORM KODIS ADMEBAASIS SALVESTATUD KIRJUTAJANIMEGA JA PAROOLIGA
session_start();
if(isset($_SESSION['tuvastamine'])){
    header('Location: puu_leht.php');
    exit();
}
//kontroll kas login vorm on  täitetud
if(!empty($_POST['login']) && !empty($_POST['pass'])){
    $login=$_POST['login'];
    $pass=$_POST['pass'];
    $sool='vagavagatekst';
    $krypt=crypt($pass, $sool);
    //kontrollime kas admebaasis on selline kasutaja
    require('conf.php');
    global $yhendus;
    $kask=$yhendus->prepare("SELECT nimi, onAdmin, koduleht FROM kasutajad WHERE nimi=? AND password=?");
    $kask->bind_param("ss",$login,$krypt);
    $kask->bind_result($nimi, $onAdmin, $koduleht);
    $kask->execute();
    if($kask->fetch()){
        $_SESSION['kasutaja']=$nimi;
        $_SESSION['onAdmin']=$onAdmin;
        if(isset($koduleht)){
            $_SESSION['tuvastamine']='niilithne';
            header("Location: $koduleht");
            exit();
        }
        else{
            header('Location: kontakt.php');
        }

    }
    else{
        echo "kasutaja $login või parool $krypt on vale";
    }

}



?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1>Login vorm</h1>
    <form action="" method="post">
        Login:
        <input type="text" name="login" placeholder="kasutaja nimi">
        <br>
        Prool:
        <input type="password" name="pass">
        <div>
        <input type="submit" value="Logi sisse">
        </div>
    </form>
</body>
</html>