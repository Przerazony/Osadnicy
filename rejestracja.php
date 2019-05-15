<?php
session_start();

    /**formularz przetwarzamy dopiero gdy zostanie tutaj wciśnięty przycisk submit */
    /**jeśli ta zmienna jest ustawiona to oznacza że nastąpił submit formularza i trzeba dokonać walidacji wprowadzonych danych (w ramach klamer tego ifa)
     * else oznacza że formularza nie przesłano, nie trzeba nic robić nawet nie zapiszemy więc klauzuli else w kodzie*/
    if (isset($_POST['email']))
    {
        //Udana walidacja? Załóżmy że tak!
        $wszystko_OK = true;

        //Sprawdź nickname 3-20 znaków
        // za każdym razem pobieramy zmienną która przychodzi w POST
        $nick = $_POST['nick'];
        //sprawdzenie długości nicka
        if((strlen($nick)<3) || (strlen($nick)>20))
        {
            $wszystko_OK = false;
            $_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków.";
        }
        /**ctype_alnum() check for alphanumeric characters sprawdź czy wszystkie znaki w łańcuchu są alfanumeryczne(polskie ogonki też tej walidacji nie przejdą bo są znakami specjalnymi, narodowymi) */
        /**preg_match() ta funkcja korzysta z tzw. wyrażeń regularnych - to również potężny mechanizm walidacyjny do poczytania jeśli ochota*/
        if (ctype_alnum($nick)==false)
        {
            /**jeżeli oba ify zostaną spełnione co do nicku, to pojawi się informacja z ifa który jest ostatni */
            $wszystko_OK=false;
            $_SESSION['e_nick'] = "Nick może składać się tylko z liter i cyfr (bez polskich znaków).";
        }

        //sprawdź poprawność adresu e-mail
        $email = $_POST['email'];
        // filter_var(zmienna,filtr) przefiltruj zmienną w sposób określony przez rodaj filtru (drugi parametr funkcji) użyteczne do sanityzacji kodu, *stałe są pisane całe dużymi literami; FILTER_SANITIZE_EMAIL specjalny filtr do adresów mailowych, sanityzuje lewą i prawą czesć emaila tj usuwa znaki które są niedozwolone (opis które jakie co na necie) to nie oznacza jednak jeszcze że email jest poprawny
        $emailB = filter_var($email,FILTER_SANITIZE_EMAIL);
        /**tutaj sprawdzamy czy po sanityzacji email się zmienił wtedy coś śmierdzi i email wadliwy, LUB sprawdzamy poprawność schematu email */
        if ((filter_var($emailB,FILTER_VALIDATE_EMAIL) == false) || ($emailB!=$email))
        {
            $wszystko_OK=false;
            $_SESSION['e_email'] = "Podaj poprawny adres e-mail.";
        }

        //sprawdź poprawność hasła
        $haslo1 = $_POST['haslo1'];
        $haslo2 = $_POST['haslo2'];
        //sprawdzamy czy długość jest ok 8-20 znaków
        if ( (strlen($haslo1)<8) || (strlen($haslo1)>20) )
        {
            $wszystko_OK=false;
            $_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków.";
        }
        if ($haslo1!=$haslo2)
        {
            $wszystko_OK=false;
            $_SESSION['e_haslo'] = "Podane hasła nie są identyczne";
        }
        /**musimy zahaszować hasło, ta operacja działa w jedną stronę nie da się jej odwrócić, kiedyś korzystano z MD5 jednak ta  funckja została złamana przez 3 kitajców to są goście dopiero, teraz korzysta się z funkcji password_hash() aktualnie korzysta z algorytmu bcrypt. Ta funkja używa soli (salt) tj dokleja na początko już zamienionego na hasz hasła wybraną liczbę losowowygenerowanych znaków co potęguje ilość kombinacji */
        /**to jaki dokładnie algorytm zostanie użyty do haszowania określa się po przecinku stałą
         * PASSWORD_DEFAULT to stała: użyj najsilniejszego algorytmu hashującego jaki jest dostępny. aktualnie jest to algorytm bcrypt, oczywiście to się zmieni jeśli uaktualnimy wersję php na serwerze
         * PASSWORD_BCRYPT - niby teraz nie ma różnicy między tym co powyżej ale w przyszłości może zostać zastąpiony czymś silniejszym.
         * zalecana długoość komórki w bazie która przechowuje haszowane hasło to 255 znaków*/
        $haslo_hash = password_hash($haslo1,PASSWORD_DEFAULT);
        // czy zaakceptowano regulamin
        if (!isset($_POST['regulamin']))
        {
            $wszystko_OK=false;
            $_SESSION['e_regulamin'] = "Potwierdź akceptację regulaminu.";   
        }
        
        //BOT OR NOT
        /**tutaj weryfikujemy captcha i wprowadzamy sekretny kod wygenerowany dla nas */
        $sekret = "6LcowKEUAAAAAHe7tyNunRbDb-5T1swU5d_Fh5bs";
        //pobierz zawartość pliku do zmiennej ("adres interesującego nas pliku")
        /**to google ma sprawdzać czy capcha jest poprawna a nie nasz serwer więc odnosimy się do google, api,
         * wprowadzamy link metodą GET a dwie zmienne w metodzie GET posłane w adreseie:
         * adres.com?zmienna1=wartosc1&zmienna2=wartosc2
         * $_POST['g-recaptcha-response'] taka zmienna zostaje zawsze wygenerowana w formularzu i ją tez musimy wysłać do googla jako response
         */
        $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);

        /**odpowiedź googla jest zapisana w formacie JSON dlatego musimy zapisać jeszcze: */
        $odpowiedz = json_decode($sprawdz);
        /**JSON - JavaScript Object Notation - lekki format wymiany danych komputerowych, bazujący na podzbiorze języka JavaScript. Pomimo nazwy kojarzącej sięz JSem wiele języków programowania obsługuje ten format przesyłu */

         /** -> jest to notacja obiektowa, uzyskana po zdekodowaniu odpowiedzi funckją json_decode(). Składnia to klasyczny zapis obiektowy w postaci: obiekt->atrybut_obiektu */ 
        if ($odpowiedz->success==false)
        {
            $wszystko_OK=false;
            $_SESSION['e_bot'] = "Potwierdź, że nie jesteś ro-BOTEM";   
        }

/**sprawienie aby formularz pamiętał wprowadzone dane aby nie terzeba było ich od nowa wprowadzać */
//zapamiętaj wprowadzone dane:
$_SESSION['fr_nick'] = $nick;
$_SESSION['fr_email'] = $email;
$_SESSION['fr_haslo1'] = $haslo1;
$_SESSION['fr_haslo2'] = $haslo2;
//zapamiętanie akceptacji regulaminu
if (isset($_POST['regulamin'])) $_SESSION['fr_regulamin'] = true;




        /**sprawdzamy czy już czasem nie ma takiego loginu w bazie więc łączymy się z bazą danych */
        require_once "connect.php";

        /**aby przy błędzie połączenie nie pokazał się WARNING na początku to musimy użyć takiej funkcji a taka stała informuje PHP że zamiast warningów chcemy rzucać exceptions */
        mysqli_report(MYSQLI_REPORT_STRICT);

        /**próba połączenia się z bazą wykonana w nowym aktualnym bloku try - catch */
        try
        {
            $polaczenie = new mysqli($host,$db_user,$db_password,$db_name);
            if($polaczenie->connect_errno!=0)
        {
            /**rzuć nowym wyjątkiem, jeśli nastąpi rzut to złapany zostanie przez catch i catch się wykona */
            throw new Exception(mysqli_connect_errno());
        }   
            /**else do connect error number = 0 czyli kiedy uda nam się połączyć*/
            else
            {
                // Czy email już istnieje?
                $rezultat = $polaczenie->query("SELECT ID FROM UZYTKOWNICY WHERE email = '$email' ");
                /**rzuć gdy z jakiegoś powodu selecy się nie uda */
                if(!$rezultat) throw new Exception($polaczenie->error);
                /**sprawdzenie czy już jest taki mail w bazie */
                $ile_takich_maili = $rezultat->num_rows;
                if($ile_takich_maili>0)
                {
                    $wszystko_OK=false;
                    $_SESSION['e_email'] = "Istnieje już konto przypsane do tego adresu email";   

                }

                // Czy nick jest już zarezerwowany?
                $rezultat = $polaczenie->query("SELECT ID FROM UZYTKOWNICY WHERE user = '$nick'");
                /**rzuć gdy z jakiegoś powodu select się nie uda */
                if(!$rezultat) throw new Exception($polaczenie->error);
                /**sprawdzenie czy już jest taki mail w bazie */
                $ile_takich_nickow = $rezultat->num_rows;
                if($ile_takich_nickow>0)
                {
                    $wszystko_OK=false;
                    $_SESSION['e_nick'] = "Istnieje już gracz o takim nicku wybież inny.";   

                }

                 if($wszystko_OK==true)
                {
                    
                    //testy zaliczone dodajemy gracza do bazy
                    /**sprawdzamy czy się uda i dopiero działamy */
                    if($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL,'$nick','$haslo_hash','$email',100,100,100,now() + INTERVAL 14 DAY)"))
                    /**funckja myslq NOW zwraca nam bieżącą datę i czas jeśli chcemy dodać coś to dodajemy interwał czasowy*/
                    {
                        $_SESSION['udanarejestracja'] = true;
                        header('Location: witamy.php');
                    }
                    /**jeśli nie to rzućmy wyjątek, teraz bez "if(!$rezultat)" ponieważ zmienna ta istnieje tylko dla zapytań SELECT */
                    else
                    {
                        throw new Exception($polaczenie->error);
                    }

                }

                $polaczenie->close();
            }
        }
        /**złap błąd, wyjątek klasy exception a error to nowa zmienna $... */
        catch(Exception $e)
        {
            echo '<span style = "color:red;"> Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestracjęw innym terminie</span>';
            //echo '<br/>Informacja deweloperska:'.$e;
        }

    }

?>


<!DOCTYPE HTML>
<html lang = pl>
    <head>
        <meta charset = "utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE"="edge,chrome=1" /> 
        <title> Osadnicy - załóż darmowe konto</title>

        <!-- pokazanie modułu reCAPTCHA w witrynie -->
        <!-- umiezczenie tagu script w sekcji head oraz umieszczenie w formularzu  diva zawierającego wygenerowany parametr: "data-sitekey" -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <!--roboczo tutaj okodujemy css normalnie to byłby osobny plik-->
        <style>
        .error
        {
            color: red;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        
        </style>
    </head>
<body >
        <!-- tym razem bez atrybutu action gdyż tym razem nie chcemy przekierowania do nowego dokumentu lecz ten sam plik przetworzy wysłany submitem formularz, jeśli nie wpisujemy action to z automatu ten sam plik otrzymuje POSTEM wysłane dane  -->
        <!-- w polu value obsługujemy to aby zapamiętać wprowadzone dane przy błędzie-->
     <form method = "post">
        Nickname: <br/> <input type = "text" 
        value = 
                "<?php
                if(isset($_SESSION['fr_nick']))
                    {
                        echo $_SESSION['fr_nick'];
                        unset($_SESSION['fr_nick']);
                    }
                ?>" 
        name =  "nick"/> <br/>

        <?php
        /**wyświetlamy informację jeżeli zmienna z błędem zostanie stworzona */
            if(isset($_SESSION['e_nick']))
            {
                echo '<div class = "error">'.$_SESSION['e_nick'].'</div>';
                unset($_SESSION['e_nick']);
            }
        ?>

        E-mail: <br/> <input type = "text"
        value = 
                "<?php
                if(isset($_SESSION['fr_email']))
                    {
                        echo $_SESSION['fr_email'];
                        unset($_SESSION['fr_email']);
                    }
                ?>" 
         name =  "email"/> <br/>

        <?php
        /**wyświetlamy informację jeżeli zmienna z błędem zostanie stworzona */
            if(isset($_SESSION['e_email']))
            {
                echo '<div class = "error">'.$_SESSION['e_email'].'</div>';
                unset($_SESSION['e_email']);
            }
        ?>

        Twoje hasło: <br/> <input type = "password"
        value = 
                "<?php
                if(isset($_SESSION['fr_haslo1']))
                    {
                        echo $_SESSION['fr_haslo1'];
                        unset($_SESSION['fr_haslo1']);
                    }
                ?>" 
         name =  "haslo1"/> <br/>

        <?php
        /**wyświetlamy informację jeżeli zmienna z błędem zostanie stworzona */
            if(isset($_SESSION['e_haslo']))
            {
                echo '<div class = "error">'.$_SESSION['e_haslo'].'</div>';
                unset($_SESSION['e_haslo']);
            }
        ?>

        Powtórz hasło: <br/> <input type = "password"
        value = 
                "<?php
                if(isset($_SESSION['fr_haslo2']))
                    {
                        echo $_SESSION['fr_haslo2'];
                        unset($_SESSION['fr_haslo2']);
                    }
                ?>" 
         name =  "haslo2"/> <br/>

        <!-- type checkbox tworzy nam element ptaszka na stronie -->
        <!-- label zamyka nam w jednej etykiecie wszystko więc zaznaczenie ptaszka będzie możliwe również po kliknięciu na napis a nie tylko na okienko  -->
        <label>
        <input type = "checkbox" name = "regulamin" 
                <?php
                    if (isset($_SESSION['fr_regulamin']))
                    {
                        /**takie wywołanie zatwierdza checkbox */
                        echo "checked";
                        unset($_SESSION['fr_regulamin']);
                    }
                ?>
        /> Akceptuję regulamin
        </label>
        <?php
        /**wyświetlamy informację jeżeli zmienna z błędem zostanie stworzona */
            if(isset($_SESSION['e_regulamin']))
            {
                echo '<div class = "error">'.$_SESSION['e_regulamin'].'</div>';
                unset($_SESSION['e_regulamin']);
            }
        ?>

        <!-- umiezczenie tagu script w sekcji head oraz umieszczenie w formularzu  diva zawierającego wygenerowany parametr: "data-sitekey" tutaj wprowadzamy klucz witryny  -->
        <div class="g-recaptcha" data-sitekey="6LcowKEUAAAAAMzhlmK4m_tpLIe9XF-52j4zUE6b">
        </div>
        <?php
        /**wyświetlamy informację jeżeli zmienna z błędem zostanie stworzona */
            if(isset($_SESSION['e_bot']))
            {
                echo '<div class = "error">'.$_SESSION['e_bot'].'</div>';
                unset($_SESSION['e_bot']);
            }
        ?>
      <br/>
      <input type="submit" value="Zarejestruj się">
    
     </form>
</body>

</html>