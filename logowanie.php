<?php
    session_start();
    mysql_connect("localhost","admin","haslo");
    mysql_select_db("baza");
?>

<form method="POST" action="logowanie.php">
    <b>Login:</b> <input type="text" name="login"><br>
    <b>Hasło:</b> <input type="password" name="haslo"><br>
    <input type="submit" value="Zaloguj" name="loguj">
</form>
<?php
    function filtruj($zmienna)
    {
        if(get_magic_quotes_gpc())
            $zmienna = stripslashes($zmienna); // usuwamy slashe
        
        // usuwamy spacje, tagi html oraz niebezpieczne znaki
        return mysql_real_escape_string(htmlspecialchars(trim($zmienna)));
    }
    
    if (isset($_POST['loguj']))
    {
        $login = filtruj($_POST['login']);
        $haslo = filtruj($_POST['haslo']);
        $ip = filtruj($_SERVER['REMOTE_ADDR']);
        
        // sprawdzamy czy login i hasło są dobre
        if (mysql_num_rows(mysql_query("SELECT login, haslo FROM uzytkownicy WHERE login = '".$login."' AND haslo = '".md5($haslo)."';")) > 0)
        {
            // uaktualniamy date logowania oraz ip
            mysql_query("UPDATE `uzytkownicy` SET (`logowanie` = '".time().", `ip` = '".$ip."'') WHERE login = '".$login."';");
            
            $_SESSION['zalogowany'] = true;
            $_SESSION['login'] = $login;
            
            // zalogowany
        }
        else echo "Wpisano złe dane.";
    }
?>