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
<!-- Dynamic Greeting, Clock, Theme Switcher, and Weather Widget -->
<div id="dashboard-header-dynamic" style="display: flex; align-items: center; justify-content: space-between; padding: 16px 32px;">
  <div id="greeting-app" style="display: flex; align-items: center; gap: 24px; width: 100%; justify-content: space-between;">
    <div>
      <span style="font-size: 1.3em; font-weight: bold;">{{ greeting }}</span>
      <span style="margin-left: 18px; font-size: 1.1em;">🕒 {{ time }}</span>
    </div>
    <div id="weather-widget" style="margin-right: 24px;"></div>
    <button @click="toggleTheme" style="padding: 8px 18px; border-radius: 8px; border: none; background: #eaf6f7; cursor: pointer; font-weight: bold; min-width: 120px;">{{ isDarkMode ? 'Light Mode' : 'Dark Mode' }}</button>
  </div>
</div>
<!-- Random Tip -->
<div id="tip-app" style="text-align: center; margin-bottom: 10px; font-style: italic; color: #007BFF; font-size: 1.1em; min-height: 1.5em;">
  <transition name="tip-fade">
    <span v-if="show" key="tip">💡 {{ tip }}</span>
  </transition>
</div>
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
                        <select v-model="year" style="margin-left: 6px;">
                            <option v-for="y in years" :value="y">{{ y }}</option>
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
const { createApp } = Vue;
createApp({
  data() {
    return {
      time: '',
      greeting: '',
      isDarkMode: false // Always start in light mode
    }
  },
  mounted() {
    this.updateTime();
    setInterval(this.updateTime, 1000);
    // Only enable dark mode if user previously selected it
    if (localStorage.getItem('dashboard-theme') === 'dark') {
      this.isDarkMode = true;
      document.body.classList.add('dark-mode');
    } else {
      this.isDarkMode = false;
      document.body.classList.remove('dark-mode');
    }
  },
  watch: {
    isDarkMode(newVal) {
      document.body.classList.toggle('dark-mode', newVal);
      localStorage.setItem('dashboard-theme', newVal ? 'dark' : 'light');
    }
  },
  methods: {
    updateTime() {
      const now = new Date();
      this.time = now.toLocaleTimeString();
      const hour = now.getHours();
      if (hour < 12) this.greeting = 'Goedemorgen!';
      else if (hour < 18) this.greeting = 'Goedemiddag!';
      else this.greeting = 'Goedenavond!';
    },
    toggleTheme() {
      this.isDarkMode = !this.isDarkMode;
    }
  }
}).mount('#greeting-app');

// Vue.js for random tip (now rotates every 8 seconds with fade animation)
createApp({
  data() {
    return {
      tips: [
        'Zet apparaten volledig uit in plaats van op stand-by.',
        'Gebruik LED-lampen voor energiebesparing.',
        'Laat geen opladers onnodig in het stopcontact zitten.',
        'Was op lagere temperaturen om energie te besparen.',
        'Isoleer je huis voor minder warmteverlies.',
        'Gebruik een slimme thermostaat voor efficiënter verwarmen.',
        'Douche korter om water en energie te besparen.',
        'Plaats tochtstrips om warmte binnen te houden.'
      ],
      tip: '',
      tipIndex: 0,
      intervalId: null,
      show: true
    }
  },
  mounted() {
    this.tip = this.tips[0];
    this.intervalId = setInterval(this.nextTip, 8000);
  },
  beforeUnmount() {
    if (this.intervalId) clearInterval(this.intervalId);
  },
  methods: {
    nextTip() {
      this.show = false;
      setTimeout(() => {
        this.tipIndex = (this.tipIndex + 1) % this.tips.length;
        this.tip = this.tips[this.tipIndex];
        this.show = true;
      }, 400); // fade out, then update, then fade in
    }
  }
}).mount('#tip-app');

// Vue.js for animated usage/cost counters
createApp({
  data() {
    return {
      showUsage: true,
      months: ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'],
      years: [],
      month: 'januari',
      year: '',
      costPerMonthYear: {},
      animatedCost: 0
    }
  },
  watch: {
    month() { this.animateCost(); },
    year() { this.animateCost(); }
  },
  mounted() {
    this.fetchCosts();
  },
  methods: {
    async fetchCosts() {
      try {
        const res = await fetch('assets/data/energie.csv');
        const csv = await res.text();
        const lines = csv.trim().split('\n');
        const headers = lines[0].split(';');
        const tijdIdx = headers.indexOf('Tijdstip');
        const stroomIdx = headers.indexOf('Stroomverbruik woning (kW)');
        const monthYearMap = {};
        const yearSet = new Set();
        for (let i = 1; i < lines.length; i++) {
          const row = lines[i].split(';');
          if (!row[tijdIdx] || !row[stroomIdx]) continue;
          const [day, month, year] = row[tijdIdx].split(' ')[0].split('-');
          const monthNum = parseInt(month);
          const monthName = this.months[monthNum-1];
          yearSet.add(year);
          const key = `${monthName}-${year}`;
          const stroom = parseFloat((row[stroomIdx] || '0').replace(',', '.'));
          if (!monthYearMap[key]) monthYearMap[key] = 0;
          monthYearMap[key] += stroom * 0.25;
        }
        // Assume price per kWh
        const price = 0.35;
        for (const y of Array.from(yearSet).sort()) {
          for (let m = 0; m < this.months.length; m++) {
            const key = `${this.months[m]}-${y}`;
            if (monthYearMap[key]) {
              this.costPerMonthYear[key] = (monthYearMap[key] * price).toFixed(1).replace('.', ',');
            } else {
              this.costPerMonthYear[key] = '0,0';
            }
          }
        }
        this.years = Array.from(yearSet).sort();
        this.year = this.years[0];
      } catch (e) {
        // Fallback: just use current year and static values
        const now = new Date();
        this.years = [now.getFullYear().toString()];
        this.year = this.years[0];
        for (let m = 0; m < this.months.length; m++) {
          const key = `${this.months[m]}-${this.year}`;
          this.costPerMonthYear[key] = '0,0';
        }
      }
      this.animateCost();
    },
    animateCost() {
      const key = `${this.month}-${this.year}`;
      const target = parseFloat((this.costPerMonthYear[key] || '0').replace(',', '.'));
      let current = 0;
      const step = target / 40;
      if (this._interval) clearInterval(this._interval);
      this.animatedCost = 0;
      this._interval = setInterval(() => {
        current += step;
        if (current >= target) {
          this.animatedCost = target;
          clearInterval(this._interval);
        } else {
          this.animatedCost = current;
        }
      }, 20);
    }
  },
  computed: {
    costValue() {
      return this.animatedCost.toLocaleString('nl-NL', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
    }
  }
}).mount('#vue-sections');

// Vue.js for Usage by Device (animated, CSV-driven, tooltip on hover)
createApp({
  data() {
    return {
      devices: [
        { name: 'home', label: 'Home', icon: '🏠', col: 'Stroomverbruik woning (kW)', animated: 0, value: 0 },
        { name: 'auto', label: 'Auto', icon: '🚗', col: 'Waterstofverbruik auto (L/u)', animated: 0, value: 0 },
        { name: 'lighting', label: 'Lighting', icon: '💡', col: 'Waterstofproductie (L/u)', animated: 0, value: 0 }
      ],
      tooltipDevice: null
    }
  },
  mounted() {
    this.fetchUsage();
  },
  methods: {
    async fetchUsage() {
      const res = await fetch('assets/data/energie.csv');
      const csv = await res.text();
      const lines = csv.trim().split('\n');
      const headers = lines[0].split(';');
      const rows = lines.slice(1).map(line => line.split(';'));
      // Calculate average for each device
      this.devices.forEach(device => {
        const idx = headers.indexOf(device.col);
        if (idx === -1) return;
        let sum = 0, count = 0;
        rows.forEach(row => {
          let val = row[idx].replace(',', '.');
          val = parseFloat(val);
          if (!isNaN(val)) {
            sum += val;
            count++;
          }
        });
        device.value = count ? sum / count : 0;
      });
      // Normalize to percent (relative to max device)
      const max = Math.max(...this.devices.map(d => d.value)) || 1;
      this.devices.forEach(device => {
        device.percent = (device.value / max) * 100;
        device.animated = 0;
      });
      // Animate bars
      this.animateBars();
    },
    animateBars() {
      this.devices.forEach(device => {
        let current = 0;
        const target = device.percent;
        const step = target / 40;
        if (device._interval) clearInterval(device._interval);
        device.animated = 0;
        device._interval = setInterval(() => {
          current += step;
          if (current >= target) {
            device.animated = target;
            clearInterval(device._interval);
          } else {
            device.animated = current;
          }
        }, 20);
      });
    },
    showTooltip(device) {
      this.tooltipDevice = device.name;
    },
    hideTooltip() {
      this.tooltipDevice = null;
    }
  }
}).mount('#usage-app');
</script>
<!-- Weather widget (plain JS, fixed location) -->
<script>
// Mini weather widget for Amsterdam (Open-Meteo API, no key needed)
async function updateWeather() {
  const res = await fetch('https://api.open-meteo.com/v1/forecast?latitude=52.37&longitude=4.89&current_weather=true');
  const data = await res.json();
  if (data.current_weather) {
    const w = data.current_weather;
    document.getElementById('weather-widget').innerHTML =
      `<span style="font-size:1.1em;">🌤️ ${w.temperature}&deg;C, wind ${w.windspeed} km/u</span>`;
  }
}
updateWeather();
</script>
<!-- Koppel custom dashboard JS voor calendar interactiviteit en andere functies -->
<script src="assets/JS/script.js"></script>
<style>
.tip-fade-enter-active, .tip-fade-leave-active {
  transition: opacity 0.4s;
}
.tip-fade-enter-from, .tip-fade-leave-to {
  opacity: 0;
}
.tip-fade-enter-to, .tip-fade-leave-from {
  opacity: 1;
}
.usage-tooltip {
  position: absolute;
  left: 50%;
  top: -28px;
  transform: translateX(-50%);
  background: #222;
  color: #fff;
  padding: 3px 10px;
  border-radius: 6px;
  font-size: 0.95em;
  white-space: nowrap;
  pointer-events: none;
  z-index: 10;
  box-shadow: 0 2px 8px rgba(0,0,0,0.18);
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.fade-enter-to, .fade-leave-from {
  opacity: 1;
}
/* Simple calendar styles (scoped for dashboard) */
.calendar-widget {
  background: #eaf6f7;
  border-radius: 12px;
  padding: 16px 20px 18px 20px;
  max-width: 340px;
  margin: -17px auto 12px auto;
}
.calendar-table th, .calendar-table td {
  padding: 5px 0;
  text-align: center;
  font-size: 1em;
}
.calendar-day {
  cursor: pointer;
  border-radius: 50%;
  background: #eaf6f7;
  transition: background 0.18s, color 0.18s;
}
.calendar-day:hover {
  background: #b3e5fc;
  color: #222;
}
.calendar-day.selected {
  background: #4bc0c0;
  color: #fff;
  font-weight: bold;
}
</style>
</body>
</html> 