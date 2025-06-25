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
$columns = [];
if (($handle = fopen($csvFile, 'r')) !== false) {
    $header = fgetcsv($handle, 0, ';');
    $tijdIndex = array_search('Tijdstip', $header);
    // Verzamel alle kolomnamen behalve Tijdstip
    foreach ($header as $i => $col) {
        if ($i !== $tijdIndex) {
            $columns[$col] = $i;
        }
    }
    // Initialiseer arrays voor elke kolom
    $series = [];
    foreach ($columns as $col => $i) {
        $series[$col] = [];
    }
    $tijden = [];
    $rowCount = 0;
    while (($row = fgetcsv($handle, 0, ';')) !== false && $rowCount < 24) {
        $tijd = $row[$tijdIndex];
        if ($tijd) {
            $tijden[] = $tijd;
            foreach ($columns as $col => $i) {
                $raw = $row[$i] ?? '';
                $val = str_replace(',', '.', $raw);
                $series[$col][] = is_numeric($val) ? (float)$val : null;
            }
            $rowCount++;
        }
    }
    fclose($handle);
}
?>
<!-- Chart select dropdown -->
<div class="chart-select-container">
    <label for="chart-select">Kies dataset:</label>
    <select id="chart-select"></select>
    <span id="chart-title"></span>
</div>
<canvas id="verbruikChart" width="600" height="250"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?php echo json_encode($tijden); ?>;
const datasets = <?php echo json_encode($series); ?>;
const datasetNames = Object.keys(datasets);
let currentIndex = 0;
const colors = [
    '#4bc0c0', '#ff6384', '#36a2eb', '#ffcd56', '#9966ff', '#ff9f40', '#c9cbcf'
];
const ctx = document.getElementById('verbruikChart').getContext('2d');
let chart;

// Populate the select list
const select = document.getElementById('chart-select');
datasetNames.forEach((name, idx) => {
    const option = document.createElement('option');
    option.value = idx;
    option.textContent = name;
    select.appendChild(option);
});

function renderChart(idx) {
    const name = datasetNames[idx];
    const data = datasets[name];
    document.getElementById('chart-title').textContent = name;
    if (chart) chart.destroy();
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: name,
                data: data,
                borderColor: colors[idx % colors.length],
                backgroundColor: 'rgba(75,192,192,0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true } },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Datum/tijd',
                        color: '#222',
                        font: { weight: 'bold', size: 14 }
                    },
                    ticks: {
                        color: '#222',
                        font: { size: 12 }
                    }
                },
                y: { beginAtZero: true }
            }
        }
    });
}

select.onchange = function() {
    currentIndex = parseInt(this.value);
    renderChart(currentIndex);
};
// Initial render
renderChart(currentIndex);
</script> 