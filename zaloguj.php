<?php
    /**funckja która pozwala dokumentowi korzystać z sesji
     * działa inaczej niż połączenie z bazą więc nie trzeba zamykać
     * każdy dokument który chce korzystać z zsesji musi to mieć na samej górze dokumentu
     */
    session_start();

        /**zabezpieczenie przed tym by z palca w przeglądarce dostać się do strony bez wprowwadzonych danych do zmiennych logowania */
    if((!isset($_POST['login'])) || (!isset($_POST['haslo'])) )
    {
        header('Location: index.php');
        exit();
    }

    /**tutaj wpisujemy instrukcję którą ten plik php będzie się odnosił do innego pliku php w którym są zawarte instrukcje do łączenia się z bazą danych. Dzięki temu iż pliki są w tej samej lokalizacji nie musimy podawać ścieżki tylko samą nazwę pliku. */
    require_once "connect.php";

/**funkcje mysql_connect oraz mysql_query lub PDO_MySQL (php data objects) zostają wygaszane gdyż mają luki i zostaną zastąpione przez np mysqli_query 
    czyli wszystkie funkcje rozpoczynające się od "mysql_" przechodzą na "mysqli_"*/



    /**ustanowienie połączenia z bazą przy pomocy obiektu klasy mysqli (instancji tej klasy)*/
    /**znak "at" = "małpa" - operator kontroli błędów - wyrażenie, przed którym postawiono ten znak nie spowoduje wyświetlania się jakiegokolwiek błędu lub ostrzeżenia ze strony php */

    $polaczenie = @new mysqli($host,$db_user,$db_password,$db_name);

    /**specjalny atrybut obiektu połączenie 
     * connect_errno równy zero oznacza iż ostantio podjęta próba połączenia się z bazą zakończyła się sukcesem
     czyli jeśli połączenie uda nam się ustanowić to if się NIE SPEŁNI !!
     */

     /**Opis: ".$polaczenie->connect_error; można dodać jako dodatkową informację przy braku połączenia ale raczej dla admina jak szuka OCB, ibok?*/
    if($polaczenie->connect_errno!=0)
        {

            echo "Error:".$polaczenie->connect_errno;
            
            
             
        }
    else
        {
            /**przechwytywanie wpisanych wartości z pól login i hasło pliku index.php */
            $login = $_POST['login'];
            $haslo = $_POST['haslo'];

            /**Zabezpieczamy się przed SQL injection:
* luka w zabezpieczeniach aplikacji internetowych polegająca na nieodpowiednim filtrowaniu znaków z danych wejściowych, co pozwala na przekazanie dodatkowych parametrów d o oryginalnego zapytania SQL
* Nigdy nie ufajmy ciągom znaktów, które otrzymaliśmy od użytkownika!
* walidacja = sprawdzenie poprawnosci danych
* sanityzacja = wyczyszczenie danych z potencjalnie niebezpiecznych zapisów. 
*/

            /**encje html'a - jakbyśmy zapisali <div> to na ekranie nie pojawi się "<div>" tylko html będzie chciał to interpretować, encja to taki ciąg znaków który pokaże nam na ekranie dokładnie wybrany znak bez jego interpretacji czasami potrzebne
             * ta funckja np zmienia z wprowadzonego <b> na &lt;b&gt, wyświetla to samo ale nie pozwoli zinterpretować tego później w kodzie
             * np <script>document.write("pwnd by zero cool");</script> wywaliłby cala strone i to napisal
             * ENT_QUOTES mówi o tym by zamieniać na encje także cudzysłowia i apostrofy
             * UTF-8 nasz system znaków
             */
            $login = htmlentities($login,ENT_QUOTES,"UTF-8");
            

            /**tutaj kiedyś było napisane przez nas zapytanie podatne na ataki teraz obłożone niżej funckją sprintf() */
            /*$sql = */

                /**zapytanie dajemy do if bo jeśli w składni sql będzie błąd to wartość rezultat z automatu będzie false, */

                /**funckja sprintf() działa podobnie jak w C print, tam gdzie wstawiamy %s tam będzie wstawiona zmienna typu string, na końcu po przecinku podajemy w argumentach te zmienne pokolei*/
            if ($rezultat = @$polaczenie->query(
                sprintf("SELECT*FROM UZYTKOWNICY WHERE USER = '%s'",
                mysqli_real_escape_string($polaczenie,$login))))
 /**mysqli_real_escape_string specjalnie napisana funkcja zaawansowana by chronić programistów przed wstrzykiwaniem sql */
                {
                    /**tutaj musimy złapać zwrócone przez wykonane zapytanie informacje z bazy sql i zapisać ją w pamięci ram w jakiś zmiennych */

                    /**tworzymy zmienną ilu userów, i korzystamy z obiektu resultat i jego właściwości num_rows */
                    $ilu_userow = $rezultat->num_rows;
                    if($ilu_userow>0)
                        {
                            /**tworzymy tablice która przechowa nam wszystkie pobrane z bazy kolumny 
                             * metoda fetch_association, ta funkcja tworzy nam tablicę asocjacyjną czyli skojarzeniową do której powkładane zostaną zmienne o takich samych nazwach jak nazwy kolumn w bazie.
                             * fetch - pobież, przynieś, aportuj
                             * tablica to zestaw kilku zmiennych, wygodne szufladki ponumerowane co w każdą wkładamy (numer to index)
                             * asocjacyjna - skojarzenie, - szufladka ma nazwę zamiast numeru*/
                            $wiersz = $rezultat->fetch_assoc();
                            /**wiersz - nazwa tablicy asocjacyjnej, ['user] - nazwa kolumny w bazie i zarazem tekstowy indeks tablicy asocjacyjnej */

          /** dodatkowa weryfikacja hasza sprawdza czy zmienna zgadza się z tym co siedzi w bazie w kolumnie ["pass"]*/
          if (password_verify($haslo,$wiersz['pass']))
          {

          

                        /**dodajemy zmienną aby wiedzieć kiedy ktoś jest zalogowany i nie kierować go od razu na stronę logowania */
                        $_SESSION['zalogowany']=true;

                                                

                                                /** SESJA W PHP aby móc niejawnie korzystać ze zmiennych w php należy utworzyć sesję w obrębie której będzie tablica zmiennych dostępnych w obrębie wielu dokumentów.
                                                 * 
                                                 * Umożliwia ona przekazywanie zmiennyh pomiędzy podstronami w łatwy sposób, z użyciem globalnej tablicy asocjacyjnej o nazwie $_SESSION 
                                                 * 
                                                 * Zmienne są przechowywane po stronie serwera a klient na swoim komputerze posiada tylko tzw. identywikator sesji PHPSESSID */
                                                $_SESSION['id'] = $wiersz['id'];    
                                                $_SESSION['user'] = $wiersz['user'];
                                                $_SESSION['drewno'] = $wiersz['drewno'];
                                                $_SESSION['kamien'] = $wiersz['kamien'];
                                                $_SESSION['zboze'] = $wiersz['zboze'];
                                                $_SESSION['email'] = $wiersz['email'];
                                                $_SESSION['dnipremium'] = $wiersz['dnipremium'];

                                                /**po wszystkich operacjach na danych pasuje wyczyścić te dane
                                                 * close()
                                                 * free() - zwolnij pamięc
                                                 * free_result()
                                                 */
                                                
                                                /**jeśli udało nam się zalogować wywalamy zmienną która informuje o błędzie logowania. z sesji dla porządku */
                                                unset($_SESSION['blad']);
                                                $rezultat->free_result();
                                                
                    /**przekierowania przeglądarki do innego pliku używamy header */
                                    header('Location: gra.php');
            }
            /**else do ifa sprawdzającego hasz */
            else
            {

                $_SESSION['blad'] = '<span style = "color:red">Nieprawidłowy login lub hasło!</span>';
                header('Location: index.php');
              
            }


                /**else do ifa sprawdzającego czy istnieje login w bazie */
                        }
                    
                    else
                        {

                            $_SESSION['blad'] = '<span style = "color:red">Nieprawidłowy login lub hasło!</span>';
                            header('Location: index.php');
                          
                        }    
                    }


            /**ta metoda zamyka połączenie NIE WOLNO O TYM ZAPOMNIEĆ */
            $polaczenie->close();
        }



?>