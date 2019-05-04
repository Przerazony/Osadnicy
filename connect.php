<?php
/**ogólny plik zawierający namiary na bazę, robi się to po to iż połączeń do bazy może być bardzo dużo o aby nie pisać tego za każdym razem (podobnie jak CSS sie robi dla html)
  - są 4 opcje dołączania bazy w plikach:
 * include - jeśli jest błąd i pliku nie uda się dołączyć to pojawi się ostrzeżenie lecz reszta kodu pliku zostanie wykonana.
 
 * include_once - nie zmienia ogólnej zasady działania funkcji, lecz sprawia iż PHP przy włączeniu pliku do kodu, dodatkowo sprawdzi czy ten plik nie został już dołączony wcześniej w dokumencie. Jeśli tak, to dołączane linie nie zostanąponownie wklejone w plik.
 
 * require - wymagaj pliku, jeśli nie uda się go otworzyć wygeneruje się błąd krytyczny co zatrzymuje działanie reszty skryptu.
 
 * require_once - to samo co przy incl.
*/


    $host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "osadnicy";



?>