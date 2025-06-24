<?php
//---------------------------------------------------------------------------------------------------//
// Naam script		  : chart.php
// Omschrijving		  : Op deze pagina staat de chart
// Naam ontwikkelaar  : dominik
// Project		      : Energie Transitie
// Datum		      : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//

// Laad het CSV-bestand met energie-data in
$csvFile = __DIR__ . '../../../assets/data/energie.csv';
$data = [];
// Open het CSV-bestand en lees de data uit
if (($handle = fopen($csvFile, 'r')) !== false) {
    // Lees de eerste regel (header) om kolomnamen te krijgen
    $header = fgetcsv($handle, 0, ';');
    // Zoek de indexen van de kolommen die we willen gebruiken
    $tijdIndex = array_search('Tijdstip', $header);
    $verbruikIndex = array_search('Stroomverbruik woning (kW)', $header);
    // Loop door alle rijen van het CSV-bestand
    while (($row = fgetcsv($handle, 0, ';')) !== false) {
        $tijd = $row[$tijdIndex];
        $verbruikRaw = $row[$verbruikIndex] ?? '';
        // Zet komma's om naar punten voor getallen
        $verbruik = str_replace(',', '.', $verbruikRaw);
        // Voeg alleen geldige data toe aan de array
        if ($tijd && is_numeric($verbruik)) {
            $data[] = [
                'tijd' => $tijd,
                'verbruik' => (float)$verbruik
            ];
        }
    }
    fclose($handle);
}
// Zet de tijdstippen en verbruiken in aparte arrays voor de grafiek
$tijden = array_column($data, 'tijd');
$verbruiken = array_column($data, 'verbruik');
// Optioneel: alleen de eerste 24 punten tonen (voor overzicht)
$tijden = array_slice($tijden, 0, 24);
$verbruiken = array_slice($verbruiken, 0, 24);
?>
<!-- Canvas voor de Chart.js grafiek -->
<canvas id="verbruikChart" width="600" height="250"></canvas>
<!-- Laad Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Zet de PHP-arrays om naar JavaScript-arrays voor de grafiek
const labels = <?php echo json_encode($tijden); ?>;
const data = <?php echo json_encode($verbruiken); ?>;
// Haal de canvas-context op en maak de grafiek aan
const ctx = document.getElementById('verbruikChart').getContext('2d');
new Chart(ctx, {
    type: 'line', // Lijngrafiek
    data: {
        labels: labels,
        datasets: [{
            label: 'Stroomverbruik woning (kW)', // Titel van de dataset
            data: data, // De waardes van het verbruik
            borderColor: '#4bc0c0', // Lijnkleur
            backgroundColor: 'rgba(75,192,192,0.2)', // Opvulkleur
            fill: true,
            tension: 0.3 // Maakt de lijn wat vloeiender
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true } // Toon de legenda
        },
        scales: {
            x: { display: false }, // Verberg de x-as labels
            y: { beginAtZero: true } // Laat y-as bij 0 beginnen
        }
    }
});
</script> 