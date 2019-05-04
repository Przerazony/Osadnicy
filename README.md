Formularz rejestracji oraz logowania do strony gry przeglądarkowej pokazującej aktualnie posiadane surowce oraz dane użytkownika wyciągane z bazy danych.

Pliki php łączą się do bazy danych poprzez plik "connect.php" więc tam należy wprowadzić informację na temat swojego serwera i bazy do której rejestracja ma wprowadzać dane oraz z której dane logowania mają być pobierane.

Najlepiej w XAMPP właczyć Apache oraz MySQL wtedy jedynie w pliku "connect.php" należy zmienić hasło tj "$db_password" jeżeli nasz lokalny serwer jest zabezpieczony hasłem oraz nazwę bazy "$db_name" jeżeli nazwa naszej bazy w której stworzymy potrzebną tablicę do obłsugi strony będzie zawarta w bazie o innej nazwie.

Plik "uzytkownicy.sql" to plik zakładający tabelę potrzebną do działania strony wraz z 1 przykładowym użytkownikiem.

Gdy zakończymy konfigurację przenosimy cały folder z plikami na serwer, w przypadku XAMPP jest to folder "htdocs" w lokalizacji instalacji XAMPP. Najlepiej stworzyć osobny podfolder np "gra". Docelowo pliki znajdują się w (...)\xampp\htdocs\gra dzięki temu w przeglądarce przy włączonym serwerze wpisując http://localhost/gra/ uruchomimy stronę logowania domyślnie "index.php"

logowanie zostało zabezpieczone przed podstawowym atakiem injection a rejestracja walidacją oraz sanityzacją wprowadzonych danych. Dostępna jest również walidacja captcha jednak możliwe iż na własne potrzeby będzie trzeba stworzyć w google własne klucze.