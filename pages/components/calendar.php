<?php
//---------------------------------------------------------------------------------------------------//
// Naam script		  : calendar.php
// Omschrijving		  : Op deze pagina staat de calender
// Naam ontwikkelaar  : dominik
// Project		      : Energie Transitie
// Datum		      : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//
?>

<!-- Dynamic calendar with month/year picker, highlights days with data -->
<div class="calendar-widget">
    <div class="calendar-tabs">
        <button class="active">Day</button>
        <button disabled>Week</button>
        <button disabled>Month</button>
    </div>
    <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 8px;">
        <select id="calendar-month"></select>
        <select id="calendar-year"></select>
    </div>
    <div class="calendar-table">
        <table id="calendar-table">
            <thead>
                <tr>
                    <th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th>
                </tr>
            </thead>
            <tbody id="calendar-tbody">
                <!-- JS will generate days here -->
            </tbody>
        </table>
    </div>
</div>
<script>
// Parse CSV and extract all available dates (unique days)
async function getAvailableDates() {
    const res = await fetch('assets/data/energie.csv');
    const csv = await res.text();
    const lines = csv.trim().split('\n');
    const tijdIdx = lines[0].split(';').indexOf('Tijdstip');
    const dates = new Set();
    for (let i = 1; i < lines.length; i++) {
        const tijd = lines[i].split(';')[tijdIdx];
        if (tijd) {
            const day = tijd.split(' ')[0]; // e.g. 14-6-2025
            dates.add(day);
        }
    }
    return Array.from(dates);
}

// Render calendar for selected month/year, highlight days with data
function renderCalendar(month, year, availableDays, selectedDay = null) {
    const daysInMonth = new Date(year, month, 0).getDate();
    const firstDay = new Date(year, month - 1, 1).getDay(); // 0=Su
    const tbody = document.getElementById('calendar-tbody');
    tbody.innerHTML = '';
    let day = 1;
    for (let row = 0; row < 6; row++) {
        const tr = document.createElement('tr');
        for (let col = 0; col < 7; col++) {
            const td = document.createElement('td');
            if ((row === 0 && col < firstDay) || day > daysInMonth) {
                td.textContent = '';
            } else {
                td.textContent = day;
                const dayStr = `${day}-${month}-${year}`;
                if (availableDays.has(dayStr)) {
                    td.className = 'calendar-day';
                    td.setAttribute('data-day', day);
                    if (selectedDay === day) td.classList.add('selected');
                    td.onclick = function() {
                        document.querySelectorAll('.calendar-day').forEach(cell => cell.classList.remove('selected'));
                        td.classList.add('selected');
                        const date = dayStr;
                        window.selectedChartDate = date;
                        window.dispatchEvent(new CustomEvent('calendar-day-selected', { detail: { date } }));
                    };
                } else {
                    td.style.opacity = 0.3;
                }
                day++;
            }
            tr.appendChild(td);
        }
        tbody.appendChild(tr);
        if (day > daysInMonth) break;
    }
}

// Populate month/year pickers
function populateMonthYearPickers(months, years, selectedMonth, selectedYear) {
    const monthNames = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
    const monthSel = document.getElementById('calendar-month');
    const yearSel = document.getElementById('calendar-year');
    monthSel.innerHTML = '';
    yearSel.innerHTML = '';
    months.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m;
        opt.textContent = monthNames[m-1] + ' - ' + new Date(0, m-1).toLocaleString('nl-NL', { month: 'long' });
        if (m === selectedMonth) opt.selected = true;
        monthSel.appendChild(opt);
    });
    years.forEach(y => {
        const opt = document.createElement('option');
        opt.value = y;
        opt.textContent = y;
        if (y === selectedYear) opt.selected = true;
        yearSel.appendChild(opt);
    });
}

// Main: load available dates, setup pickers, render calendar
window.addEventListener('DOMContentLoaded', async function() {
    const allDates = await getAvailableDates();
    // Parse unique months/years
    const availableDays = new Set(allDates);
    // Get all months and years present in the CSV
    const months = Array.from(new Set(allDates.map(d => parseInt(d.split('-')[1])))).sort((a,b) => a-b);
    const years = Array.from(new Set(allDates.map(d => parseInt(d.split('-')[2])))).sort((a,b) => a-b);
    let selectedMonth = months[0];
    let selectedYear = years[0];
    let selectedDay = parseInt(allDates[0].split('-')[0]);
    populateMonthYearPickers(months, years, selectedMonth, selectedYear);
    function rerenderCalendar() {
        renderCalendar(selectedMonth, selectedYear, availableDays, selectedDay);
    }
    rerenderCalendar();
    // Picker change events
    document.getElementById('calendar-month').onchange = function() {
        selectedMonth = parseInt(this.value);
        // Pick first available day in this month/year
        const dayInMonth = allDates.find(d => parseInt(d.split('-')[1]) === selectedMonth && parseInt(d.split('-')[2]) === selectedYear);
        selectedDay = dayInMonth ? parseInt(dayInMonth.split('-')[0]) : 1;
        rerenderCalendar();
    };
    document.getElementById('calendar-year').onchange = function() {
        selectedYear = parseInt(this.value);
        // Pick first available day in this month/year
        const dayInMonth = allDates.find(d => parseInt(d.split('-')[1]) === selectedMonth && parseInt(d.split('-')[2]) === selectedYear);
        selectedDay = dayInMonth ? parseInt(dayInMonth.split('-')[0]) : 1;
        rerenderCalendar();
    };
});
</script> 