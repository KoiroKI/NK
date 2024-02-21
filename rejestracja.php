<?php
    mysql_connect("localhost","admin","haslo");
    mysql_select_db("baza");
    
    function filtruj($zmienna)
    {
        if(get_magic_quotes_gpc())
            $zmienna = stripslashes($zmienna); // usuwamy slashe
        
        // usuwamy spacje, tagi html oraz niebezpieczne znaki
        return mysql_real_escape_string(htmlspecialchars(trim($zmienna)));
    }
    
    if (isset($_POST['rejestruj']))
    {
        $login = filtruj($_POST['login']);
        $haslo1 = filtruj($_POST['haslo1']);
        $haslo2 = filtruj($_POST['haslo2']);
        $email = filtruj($_POST['email']);
        $ip = filtruj($_SERVER['REMOTE_ADDR']);
        
        // sprawdzamy czy login nie jest już w bazie
        if (mysql_num_rows(mysql_query("SELECT login FROM uzytkownicy WHERE login = '".$login."';")) == 0)
        {
            if ($haslo1 == $haslo2) // sprawdzamy czy hasła takie same
            {
                mysql_query("INSERT INTO `uzytkownicy` (`login`, `haslo`, `email`, `rejestracja`, `logowanie`, `ip`)
                    VALUES ('".$login."', '".md5($haslo1)."', '".$email."', '".time()."', '".time()."', '".$ip."');");
                
                echo "Konto zostało utworzone!";
            }
            else echo "Hasła nie są takie same";
        }
        else echo "Podany login jest już zajęty.";
    }
?>

<form method="POST" action="rejestracja.php">
    <b>Login:</b> <input type="text" name="login"><br><br>
    <b>Hasło:</b> <input type="password" name="haslo1"><br>
    <b>Powtórz hasło:</b> <input type="password" name="haslo2"><br><br>
    <b>Email:</b> <input type="text" name="email"><br>
    <input type="submit" value="Utwórz konto" name="rejestruj">
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