<?php
session_start();

/**tutaj obsługujemy sytuację w której ktoś z palca by wpisał do przeglądarki /witamy.php jak ta zmienna nie jest ustawiona czyli jak nie trafisz tu z formularza rejestracji to wracasz na stronę główną */
if((!isset($_SESSION['udanarejestracja'])))
{
    header('Location: index.php');
    /**ta linia pozwala na to aby reszta html na stronie nie została niepotrzebnie wykonana */
    exit();
}
else
{
    /**wywalamy zmienną po tym jak udało Ci się tu znaleźć prawidłowo by po odświeżeniu jej nie było */
    unset($_SESSION['udanarejestracja']);
}
//usuwamy zmienne które służyły do zapamiętania wartości w razie nieudanej walidacji
if (isset($_SESSION['fr_nick'])) unset($_SESSION['fr_nick']);
if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
if (isset($_SESSION['fr_haslo1'])) unset($_SESSION['fr_haslo1']);
if (isset($_SESSION['fr_haslo2'])) unset($_SESSION['fr_haslo2']);
if (isset($_SESSION['fr_regulamin'])) unset($_SESSION['fr_regulamin']);

//usuwanie błędów rejestracji
if (isset($_SESSION['e_nick'])) unset($_SESSION['e_nick']);
if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
if (isset($_SESSION['e_haslo1'])) unset($_SESSION['e_haslo1']);
if (isset($_SESSION['e_haslo2'])) unset($_SESSION['e_haslo2']);
if (isset($_SESSION['e_regulamin'])) unset($_SESSION['e_regulamin']);
if (isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);

?>


<!DOCTYPE HTML>
<html lang = pl>
    <head>
        <meta charset = "utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE"="edge,chrome=1" /> 
        <title> Osadnicy - Gra nieżyciowa</title>
        

    </head>
    <body>
        Dziękujemy za rejestrację w serwisie. Możesz już zalogować się na swoje konto<br/><br/>
        <a href ="index.php">Zaluguj się na swoje konto </a>
        <br/><br/>
    </body>

<!-- MD5 message - digest algorithm 5 algorytm szyfrujący zamienia ciąg znaków na jego zakodowaną reprezentację-->

</html>