<?php
//---------------------------------------------------------------------------------------------------//
// Naam script		    : dashboard.php
// Omschrijving		    : Op deze pagina staat het hele dashboard
// Naam ontwikkelaar  : dominik
// Project		        : Energie Transitie
// Datum		          : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Energie Transitie</title>
    <!-- Koppel de dashboard-specifieke CSS -->
    <link rel="stylesheet" href="assets/CSS/style.css">
</head>
<body>
<?php 
// Voeg de header toe (logo, titel, evt. navigatie)
include_once 'components/header.php'; 
?>
<div class="dashboard-container">
    <!-- Hoofdgrid van het dashboard: grafiek, kalender, usage, kosten -->
    <div id="dashboard-draggable" class="dashboard-main">
        <!-- Linksboven: grafiek met stroomverbruik (Chart.js) -->
        <div class="draggable-box chart-section">
            <?php include_once 'components/chart.php'; ?>
        </div>
        <!-- Rechtsboven: kalender met energie-data -->
        <div class="draggable-box calendar-section">
            <?php include_once 'components/calendar.php'; ?>
        </div>
        <!-- Vue.js secties voor usage en kosten (linksonder en rechtsonder) -->
        <div id="vue-sections" style="display: contents;">
            <!-- Linksonder: usage by device, met show/hide knop -->
            <div class="draggable-box usage-section">
                <button @click="showUsage = !showUsage">{{ showUsage ? 'Hide' : 'Show' }} Usage by device</button>
                <div v-show="showUsage">
                    <?php include_once 'components/usage.php'; ?>
                </div>
            </div>
            <!-- Rechtsonder: energiekosten, met maand-selector -->
            <div class="draggable-box cost-section">
                <div class="energy-cost">
                    <h4>Energi kost<br>
                        <select v-model="month">
                            <option v-for="m in months" :value="m">{{ m }}</option>
                        </select>
                    </h4>
                    <div class="cost-value">€ {{ costValue }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Laad Vue.js voor dynamische onderdelen -->
<script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
<script>
// Vue-app voor de begroeting bovenaan (verandert op basis van tijd)
const { createApp } = Vue;
createApp({
  data() {

    
  }
}).mount('#greeting-app');
// Vue-app voor usage/cost-secties (show/hide usage, maand-selectie voor kosten)
createApp({
  data() {
    return {
      showUsage: true,
      months: ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'],
      month: 'januari',
      costPerMonth: {
        'januari': '0000,1',
        'februari': '0000,2',
        'maart': '0000,3',
        'april': '0000,4',
        'mei': '0000,5',
        'juni': '0000,6',
        'juli': '0000,7',
        'augustus': '0000,8',
        'september': '0000,9',
        'oktober': '0001,0',
        'november': '0001,1',
        'december': '0001,2',
      }
    }
  },
  computed: {
    // Berekent de kostenwaarde op basis van de geselecteerde maand
    costValue() {
      return this.costPerMonth[this.month] || '0000,0';
    }
  }
}).mount('#vue-sections');
</script>
<!-- Add SortableJS for drag-and-drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
  new Sortable(document.getElementById('dashboard-draggable'), {
    animation: 200,
    handle: '.draggable-box',
    ghostClass: 'sortable-ghost'
  });
</script>
</body>
</html> 