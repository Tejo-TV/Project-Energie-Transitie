<?php
// Start de sessie zodat we deze kunnen beëindigen voor uitloggen
session_start();
// Verwijder alle sessie-variabelen (gebruiker wordt uitgelogd)
session_unset();
// Vernietig de sessie helemaal
session_destroy();

// Stuur de gebruiker terug naar de loginpagina met een melding
header("Location: ../../login.php?error=uitgelogd");
exit();
