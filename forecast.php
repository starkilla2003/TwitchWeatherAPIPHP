<?php
$message = "";
$city = $_GET['city'];
if (empty($city)) {
    $city='Tokyo';
}
$apiKey = "";
$googleApiUrl = "http://api.openweathermap.org/data/2.5/forecast?q=" . $city . "&lang=en&units=metric&APPID=" . $apiKey;
$googleApiUrlImperial = "http://api.openweathermap.org/data/2.5/forecast?q=" . $city . "&lang=en&units=imperial&APPID=" . $apiKey;

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


$x = 0;
$forecast = "";

while($x < $data->cnt) {
	$dt = new DateTime($data->list[$x]->dt_txt);

	$date = $dt->format('Y-m-d');
	$time = $dt->format('H:i:s');

	$day=date('l', strtotime($data->list[$x]->dt_txt));
	if ($time == "15:00:00") $forecast = $forecast."| ".$day.": ".$data->list[$x]->weather[0]->description." ".$data->list[$x]->main->temp." C (".$dataimperial->list[$x]->main->temp." F) ";
    $x=$x+1;
};
$message = "Forecast for ".$data->city->name.", ".$data->city->country." ".$forecast;
if ($data->cod == "404") $message = strtoupper($data->message);
echo $message;
?>