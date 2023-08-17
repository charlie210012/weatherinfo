{if isset($weatherData)}
    <link rel="stylesheet" type="text/css" href="{$module_dir}/views/css/weatherinfo.css">
    <div class="weather-info">
        <p class="weather-data">Ubicación: <span>{$weatherData.location}</span></p>
        <p class="weather-data">Temperatura en Celsius: <span>{$weatherData.temperatureCelsius} °C</span></p>
        <p class="weather-data">Temperatura en Fahrenheit: <span>{$weatherData.temperatureF} °F</span></p>
        <p class="weather-data">Humedad: <span>{$weatherData.humidity} %</span></p>
    </div>
{/if}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{$module_dir}/views/js/weatherinfo.js"></script>
