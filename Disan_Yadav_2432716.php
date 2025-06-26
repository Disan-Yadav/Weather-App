<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers:*");

// date_default_timezone_set('Asia/Kathmandu');
$serverName = "sql201.infinityfree.com";
$userName= "if0_36513362";
$password = "e3OLhzlkwhu";
$conn = mysqli_connect($serverName, $userName, $password);
if($conn){
    // echo "Connection Successful <br>";
}
else{
    echo "Failed to connect".mysqli_connect_error();
}
$createDatabase = "CREATE DATABASE IF NOT EXISTS if0_36513362_prototype2";

if (mysqli_query($conn, $createDatabase)) {

    // echo "Database Created or already Exists <br>";

} else {

    echo "Failed to create database <br>" .mysqli_error($conn);
}
mysqli_select_db($conn,'if0_36513362_prototype2');

$createTable = "CREATE TABLE IF NOT EXISTS weather2 (


    city VARCHAR(255) NOT NULL,
    temp FLOAT NOT NULL,
    humidity FLOAT NOT NULL,
    wind FLOAT NOT NULL,
    wind_direction FLOAT NOT NULL,
    pressure FLOAT NOT NULL,
    weather_condition VARCHAR(255) NOT NULL,
    weather_icon VARCHAR(255) NOT NULL,
    created_at datetime
);";

if(mysqli_query($conn, $createTable)) {
    // echo "Table Created or already Exists <br>";
}else{
    echo "Failed to create table <br>";
}
if(isset($_GET['q'])){
    $cityName = $_GET['q'];
    // echo $cityName;
}else{
    $cityName = "Gateshead";
}


$selectAllData = "SELECT * FROM weather2 where LOWER(city) = LOWER('{$cityName}') order by created_at desc";
$result = mysqli_query($conn, $selectAllData);
$row = mysqli_fetch_assoc($result);
$lastUpdate = strtotime($row['created_at']);
$current_time=time();
if (mysqli_num_rows($result) == 0 || $current_time - $lastUpdate >= 7200) {
    $url = "https://api.openweathermap.org/data/2.5/weather?units=metric&q=" . $cityName . "&appid=c44d2b9613b2b14da8ed19b3590daec3";
    $response = file_get_contents($url);
   
    $data = json_decode($response, true);
    $city = $data['name'];
    $temp = $data['main']['temp']; 
    $humidity = $data['main']['humidity'];
    $wind = $data['wind']['speed'];
    $wind_direction = $data['wind']['deg'];
    $pressure = $data['main']['pressure'];
    $weather_condition = $data ['weather'][0]['description'];
    $weather_icon = $data ['weather'][0]['icon'];
    $date = date('Y-m-d H : i : s', $data['dt']);

    $insertData = "INSERT INTO weather2 (city, temp, humidity, wind, wind_direction, pressure, weather_condition, weather_icon, created_at)
                   VALUES ('$city', '$temp', '$humidity', '$wind', '$wind_direction', '$pressure', '$weather_condition', '$weather_icon','$date')";

    if (mysqli_query($conn, $insertData)) {
        // echo "Data inserted Successfully";
    } else {
        echo "Failed to insert data" . mysqli_error($conn);
    }
} 


// Fetching data from weather table based on city name again after insertion
$result = mysqli_query($conn, $selectAllData);

while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}
// // Encoding fetched data to JSON and sending as response
$json_data = json_encode($rows);
header('Content-Type: application/json');

echo $json_data;

?>   