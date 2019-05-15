<?php

/**Zmienne sesyjne są przechowywane na serwerze a klient posiada tylko PHPSESSID to ID jest zapisane w cookie lub przekazywane poprzez URL metodą GET. Podejrzyj wartość swojego PHPSESSID metodą: echo session_id(); */

session_start();

/**sprawdzamy czy zmienna nie jest ustawiona aby nie pozwolić z palca w przeglądarce dostać się do strony bez podanych danych */
if(!isset($_SESSION['zalogowany']))
{
    header('Location: index.php');
    exit();
}


?>

<!DOCTYPE HTML>
<html lang = pl>
    <head>
        <meta charset = "utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE"="edge,chrome=1" /> 
        <title> Stulejarze - Gra życiowa</title>

    </head>
    <body>


    <!-- SESJA W PHP aby móc niejawnie korzystać ze zmiennych w php należy utworzyć sesję w obrębie której będzie tablica zmiennych dostępnych w obrębie wielu dokumentów.
    
    Umożliwia ona przekazywanie zmiennyh pomiędzy podstronami w łatwy sposób, z użyciem globalnej tablicy asocjacyjnej o nazwie $_SESSION

    Zmienne są przechowywane po stronie serwera a klient na swoim komputerze posiada tylko tzw. identywikator sesji PHPSESSID
     -->

    <?php   
        /**akapit w html to <p>, kropka skleja łańcuch */
        /**użyto zmiennej globalnej dzięki tablicy asocjacyjnej sesji */
        echo "<p>Witaj ".$_SESSION['user'].' !1! [<a href="logout.php">Wyloguj</a>] </p>' ;
        echo "<p><b>Drewno</b>".$_SESSION['drewno'];
        echo "|";
        echo " | <strong>Kamień</strong> ".$_SESSION['kamien'];
        echo " | <strong>Zboże</strong> ".$_SESSION['zboze']."</p>";
        echo "<p><b>E-mail</b> ".$_SESSION['email'];
        echo "<br/><b>Data wygaśnięcia premium:</b> ".$_SESSION['dnipremium']."</p>";
        
        /**w phpMyadmin należy zmienić strukturę tablicy aby czas premium był w datetime */
        /**data odczytana z serwera - obiekt $dataczas
         * data wygaśnięcią premium $_SESSION['dnipremium']
         * musimy obliczyć różnicę by wiedzieć za ile wygaśnie premum
         */
        $dataczas = new DateTime('2017-02-06 22:10:59');
        echo "Data i czas serwera: " .$dataczas->format('Y-m-d H:i:s')."<br/>";
/**klasa datetime zawiera metodę diff() która ułatwia duuuuuuużo, szybko liczymy różnicę pomidzy dwoma obiektami datetime
 * createFromFormat() - metoda przenosi datę wyjętą z bazy do nowego drugiego obiektu datetime
 */
/** :: operator zasięgu, czyli Z KLASY datetime wywołaj METODE o nazwie createFromFormat
 * czyli utwórz nowy obiekt na podstawie tego co wyjęliśmy z bazy
*/
 $koniec = DateTime::createFromFormat('Y-m-d H:i:s',$_SESSION['dnipremium']);
/** "->" operator strzałki pozwala dostać się do właściwości albo metody obiektu 
 * innymi słowy użyj metody
*/
/**kolejność jest dowolna, różnica czasu między dwoma momentami zawsze jest dodatnia */
 $roznica = $dataczas->diff($koniec);
        
 if($dataczas<$koniec)
 {
     echo "Pozostało premium: ".$roznica->format("%Y lat, %m mies, %d dni, %h godz, %i min, %s sek");
 }
else
{
    echo "Premium nieaktywne od: ".$roznica->format("%Y lat, %m mies, %d dni, %h godz, %i min, %s sek");
}

    ?>






    </body>



</html>