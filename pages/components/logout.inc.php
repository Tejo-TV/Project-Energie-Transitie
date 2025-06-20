<?php
// Start de sessie zodat we deze kunnen beëindigen voor uitloggen
session_start();
// Verwijder alle sessie-variabelen (gebruiker wordt uitgelogd)
session_unset();     // Unset all session variables
// Vernietig de sessie helemaal
session_destroy();   // Destroy the session

// Stuur de gebruiker terug naar de loginpagina met een melding
header("Location: ../login.php?error=uitgelogd"); // Of een andere pagina indien gewenst
exit();
