<?php
session_start();

/**tutaj obsługujemy to kiedy ktoś już jest zalogowany */
if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
{
    header('Location: gra.php');
    /**ta linia pozwala na to aby reszta html na stronie nie została niepotrzebnie wykonana */
    exit();
}

?>


<!DOCTYPE HTML>
<html lang = pl>
    <head>
        <meta charset = "utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE"="edge,chrome=1" /> 
        <title> Osadnicy - Gra nieżyciowa</title>
        

    </head>
    <body>
        Tylko Chady ujrzą soczystość młodości - Jakub <br/><br/>

        <a href = "rejestracja.php"> Rejestracja - załóż darmowe konto!</a>
        <br/><br/>

        <form action = "zaloguj.php" method = "post">
            LOGIN: <br/> <input type="text" name = "login" /> <br/>
        <!-- Typ pola input = [password] pozwoli na to aby zamiast widocznych znaków pojawiały się gwiazdki -->
            HASŁO: <br/> <input type="password" name = "haslo" /> <br/>
            <input type = "submit" value = "Zaloguj" />

<?php
    /**isset sprawdza czy zmienna jest zdefiniowana, zabezpieczamy aby ktoś wchodząc na strone po raz pierwszy nie miał komunikatu z błędem o nieistniejącej zmiennej */
    if(isset($_SESSION['blad'])) echo $_SESSION['blad'];
?>


        </form>


    </body>

<!-- MD5 message - digest algorithm 5 algorytm szyfrujący zamienia ciąg znaków na jego zakodowaną reprezentację-->

</html>