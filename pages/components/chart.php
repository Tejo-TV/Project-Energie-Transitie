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
<!-- Chart component: all data loading is now done in JS, PHP logic removed for interactivity -->
<!-- Chart select dropdown -->
<div class="chart-select-container">
    <label for="chart-select">Kies dataset:</label>
    <select id="chart-select"></select>
    <span id="chart-title"></span>
</div>
<!-- Show selected date above the chart -->
<div id="selected-date-label" style="text-align:center; font-weight:bold; margin-bottom:8px;"></div>
<canvas id="verbruikChart" width="600" height="250"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Helper to parse CSV string to array of objects
function parseCSV(csv, delimiter = ';') {
    const lines = csv.trim().split('\n');
    const headers = lines[0].split(delimiter);
    return lines.slice(1).map(line => {
        const values = line.split(delimiter);
        const obj = {};
        headers.forEach((h, i) => obj[h] = values[i]);
        return obj;
    });
}

// Fetch and filter CSV data for a given date (e.g., '14-6-2025')
async function loadChartData(selectedDate) {
    const response = await fetch('assets/data/energie.csv');
    const csv = await response.text();
    const data = parseCSV(csv);
    // Filter rows for the selected date
    const filtered = data.filter(row => row['Tijdstip'].startsWith(selectedDate + ' '));
    return { filtered, headers: Object.keys(data[0]) };
}

// Render the chart for a given dataset and date
let chart;
async function renderChart(selectedDate, datasetName = null) {
    const { filtered, headers } = await loadChartData(selectedDate);
    if (!filtered.length) return;
    // Prepare labels and datasets
    const tijdIndex = headers.indexOf('Tijdstip');
    // Show both date and time on x-axis
    const labels = filtered.map(row => row['Tijdstip']);
    const datasetNames = headers.filter(h => h !== 'Tijdstip');
    // Populate select if not already
    const select = document.getElementById('chart-select');
    if (select.options.length === 0) {
        datasetNames.forEach((name, idx) => {
            const option = document.createElement('option');
            option.value = name;
            option.textContent = name;
            select.appendChild(option);
        });
    }
    // Use selected dataset or default
    const selectedDataset = datasetName || select.value || datasetNames[0];
    // Prepare data for chart
    const data = filtered.map(row => parseFloat((row[selectedDataset] || '0').replace(',', '.')));
    // Chart colors
    const colors = [
        '#4bc0c0', '#ff6384', '#36a2eb', '#ffcd56', '#9966ff', '#ff9f40', '#c9cbcf'
    ];
    // Destroy previous chart if exists
    if (chart) chart.destroy();
    chart = new Chart(document.getElementById('verbruikChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: selectedDataset,
                data: data,
                borderColor: colors[datasetNames.indexOf(selectedDataset) % colors.length],
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
                        font: { size: 12 },
                        // Optionally, show fewer ticks for readability
                        autoSkip: true,
                        maxTicksLimit: 12
                    }
                },
                y: { beginAtZero: true }
            }
        }
    });
    // Update chart title
    document.getElementById('chart-title').textContent = selectedDataset;
    // Show selected date above the chart
    document.getElementById('selected-date-label').textContent = 'Geselecteerde datum: ' + selectedDate;
}

// Listen for dataset select changes
const select = document.getElementById('chart-select');
select.onchange = function() {
    // Re-render chart with new dataset
    const selectedDate = window.selectedChartDate || '14-6-2025';
    renderChart(selectedDate, this.value);
};

// Listen for custom event from calendar
window.selectedChartDate = '14-6-2025'; // Default
window.addEventListener('calendar-day-selected', function(e) {
    window.selectedChartDate = e.detail.date;
    // Clear select so it repopulates for new date
    select.options.length = 0;
    renderChart(window.selectedChartDate);
});

// Initial render
renderChart(window.selectedChartDate);
</script> 