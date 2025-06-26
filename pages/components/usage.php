<?php
//---------------------------------------------------------------------------------------------------//
// Naam script		  : cost.php
// Omschrijving		  : Op deze pagina staat de usage
// Naam ontwikkelaar  : dominik
// Project		      : Energie Transitie
// Datum		      : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//
?>
<div id="usage-app" class="usage-device">
    <h4>Usage by device</h4>
    <div v-for="device in devices" :key="device.name" class="usage-bar" style="position: relative;">
        <span>{{ device.icon }}</span>
        <div class="bar" :style="{ width: device.animated + '%' }" @mouseenter="showTooltip(device)" @mouseleave="hideTooltip">
            <transition name="fade">
                <span v-if="tooltipDevice === device.name" class="usage-tooltip">{{ device.animated.toFixed(1) }}%</span>
            </transition>
        </div>
        <span style="margin-left: 8px; font-size: 0.95em;">{{ device.label }}</span>
    </div>
</div> 