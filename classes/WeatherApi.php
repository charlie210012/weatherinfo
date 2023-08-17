<?php

class WeatherApi
{
    private static $instance;
    private $apiKey = '499d05e259b84874b9d122328231608';

    private function __construct() {}

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new WeatherApi();
        }
        return self::$instance;
    }

    public function getCurrentWeather($ip)
    {
        $url = "http://api.weatherapi.com/v1/current.json?key={$this->apiKey}&q={$ip}";
        $response = @file_get_contents($url);
        if ($response !== false) {
            $data = json_decode($response, true);
            return $data;
        }
        return null;
    }
}
