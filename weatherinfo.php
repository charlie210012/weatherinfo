<?php
    if (!defined('_PS_VERSION_')) {
        exit;
    }

    require_once(_PS_MODULE_DIR_ . 'weatherinfo/classes/WeatherApi.php');

    class WeatherInfo extends Module
    {
        public function __construct()
        {
            $this->name = 'weatherinfo';
            $this->tab = 'front_office_features';
            $this->version = '1.0.0';
            $this->author = 'Carlos Andrés Arévalo Cortés';
            $this->need_instance = 0;

            parent::__construct();

            $this->displayName = $this->l('Información del clima');
            $this->description = $this->l('Muestra información del clima en el encabezado.');
        }

        public function install()
        {
            return parent::install() &&
            $this->registerHook('displayNav') &&
            $this->registerHook('header');
        }

        public function hookDisplayNav($params)
        {
            $weatherApi = WeatherApi::getInstance();
            $ip = $this->getUserIP();

            $apiResponse = $weatherApi->getCurrentWeather($ip);

            $weatherData = $this->getWeatherData($apiResponse);


            if ($weatherData) {
                $this->context->smarty->assign('weatherData', $weatherData);
                return $this->display(__FILE__, 'views/templates/hook/weatherinfo.tpl');
            }

            return '';
        }

        public function hookHeader($params)
        {
            if ($this->context->controller->php_self == 'index') {
                // Verificar si el hook ya ha sido registrado previamente
                $isHookRegistered = Configuration::get('MYMODULE_DISPLAYNAV_HOOK_REGISTERED');

                if (!$isHookRegistered) {
                    $hookCode = '{hook h="displayNav"}';

                    // Leer el contenido actual del header.tpl
                    $headerTplPath = _PS_THEME_DIR_ . 'templates/_partials/header.tpl';
                    $currentContent = file_get_contents($headerTplPath);

                    // Definir la etiqueta target que cierra la etiqueta <a>
                    $targetTag = '</a>';

                    // Verificar si la etiqueta target existe en el archivo
                    $targetPosition = strpos($currentContent, $targetTag);
                    if ($targetPosition !== false) {
                        // Insertar el gancho después de la etiqueta target
                        $newContent = substr_replace($currentContent, $targetTag . "\n" . $hookCode, $targetPosition, strlen($targetTag));

                        // Escribir el contenido actualizado en el archivo
                        file_put_contents($headerTplPath, $newContent);

                        // Marcar el hook como registrado en la base de datos
                        Configuration::updateValue('MYMODULE_DISPLAYNAV_HOOK_REGISTERED', true);
                    }
                }
            }
        }



        private function getUserIP()
        {
            $ip = '';
            $ipServices = array(
                'https://api64.ipify.org?format=json',
                'https://api6.ipify.org?format=json',
                'https://api.ipify.org?format=json'
            );

            foreach ($ipServices as $ipService) {
                $response = @file_get_contents($ipService);
                if ($response !== false) {
                    $data = json_decode($response, true);
                    if (isset($data['ip']) && filter_var($data['ip'], FILTER_VALIDATE_IP)) {
                        $ip = $data['ip'];
                        break;
                    }
                }
            }

            return $ip;
        }

        private function getWeatherData($request)
        {
            // Use your preferred weather API here to fetch weather data based on user's location or IP
            // Replace with actual API request and parsing logic
            $weatherData = array(
                'location' => $request["location"]["name"],
                'country' => $request["location"]["country"],
                'temperatureCelsius' => $request["current"]["temp_c"],
                'temperatureF' => $request["current"]["temp_f"],
                'humidity' => $request["current"]["humidity"],
            );

            return $weatherData;
        }

    }
