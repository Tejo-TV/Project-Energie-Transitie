<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Energie Transitie</title>
    <link rel="stylesheet" href="assets/CSS/dashboard.css">
</head>
<body>
<?php include __DIR__ . '/components/header.php'; ?>
<div class="dashboard-container">
    <div class="dashboard-header">
        <div id="greeting-app">
            <h2>{{ greeting }}</h2>
        </div>
    </div>
    <div class="dashboard-main">
        <div class="chart-section">
            <?php include __DIR__ . '/components/chart.php'; ?>
        </div>
        <div class="calendar-section">
            <?php include __DIR__ . '/components/calendar.php'; ?>
        </div>
        <div id="vue-sections" style="display: contents;">
            <div class="usage-section">
                <button @click="showUsage = !showUsage">{{ showUsage ? 'Hide' : 'Show' }} Usage by device</button>
                <div v-show="showUsage">
                    <?php include __DIR__ . '/components/usage.php'; ?>
                </div>
            </div>
            <div class="cost-section">
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
<script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
<script>
// Alleen begroeting
const { createApp } = Vue;
createApp({
  data() {
    return {
      greeting: (function() {
        const hour = new Date().getHours();
        if (hour < 12) return 'Goedemorgen! Stroomverbruik woning';
        if (hour < 18) return 'Goedemiddag! Stroomverbruik woning';
        return 'Goedenavond! Stroomverbruik woning';
      })()
    }
  }
}).mount('#greeting-app');
// Alleen usage/cost-secties
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
    costValue() {
      return this.costPerMonth[this.month] || '0000,0';
    }
  }
}).mount('#vue-sections');
</script>
</body>
</html> 