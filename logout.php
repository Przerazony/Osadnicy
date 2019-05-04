<?php
session_start();

/**tutaj niszczymy sesję czyli zmienne zapisane a tym samym wylogowujemy */
session_unset();
header('Location: index.php')

?>
