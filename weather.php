<?php
// Initialize variables
$message = "";

// Get parameter city from URL
$city = $_GET['city'];
if (empty($city)) {
    $city='Tokyo';
}

// Register an account at https://openweathermap.org/ for access to a free api key

$apiKey = "";

// For specification of the API go to https://openweathermap.org/current 
// Getting both metric and imperial 
$googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&lang=en&units=metric&APPID=" . $apiKey;
$googleApiUrlImperial = "http://api.openweathermap.org/data/2.5/weather?q=" . $city . "&lang=en&units=imperial&APPID=" . $apiKey;

// Using CURL to get metric data
$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response);

// Using CURL to get imperial data
$chimperial = curl_init();

curl_setopt($chimperial, CURLOPT_HEADER, 0);
curl_setopt($chimperial, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($chimperial, CURLOPT_URL, $googleApiUrlImperial);
curl_setopt($chimperial, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($chimperial, CURLOPT_VERBOSE, 0);
curl_setopt($chimperial, CURLOPT_SSL_VERIFYPEER, false);
$responseimperial = curl_exec($chimperial);
curl_close($chimperial);
$dataimperial = json_decode($responseimperial);

// Converting degree to wind directions
$winddirection = 'North';
$degree = $data->wind->deg;
if ($degree>337.5) $winddirection='North';
if ($degree>292.5) $winddirection='North West';
if ($degree>247.5) $winddirection='West';
if ($degree>202.5) $winddirection='South West';
if ($degree>157.5) $winddirection='South';
if ($degree>122.5) $winddirection='South East';
if ($degree>67.5) $winddirection='East';
if ($degree>22.5) $winddirection='North East';

// constructing return message
$message = "Weather for ".$data->name.", ".$data->sys->country.": ".$data->weather[0]->description." with a temperature of ".$data->main->temp." C (".$dataimperial->main->temp."  F). Wind is blowing from the ".$winddirection." at ".$data->wind->speed." kph (".$dataimperial->wind->speed." mph) and the humidity is ".$data->main->humidity." %";

// If city was not found give out error message
if ($data->cod == "404") $message = strtoupper($data->message);

// printing out message
echo $message;
?>