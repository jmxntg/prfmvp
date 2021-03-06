<?php

//	Server file of the web application 'PRF Alerta' for the 2nd Brazilian National Contest MJ/W3C over open PRF data.
//	Copyright (C) 2013  Authors: João Marcelo Teixeira, Mozart Almeida, Edvar Neto, Artur Santos

//	This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as
//    published by the Free Software Foundation, either version 3 of the
//    License, or (at your option) any later version.

//   This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.

//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.


function distance($lat1, $lng1, $lat2, $lng2) { // distance in km
  $earthRadius = 3958.75;
  $dLat = deg2rad($lat2-$lat1);
  $dLng = deg2rad($lng2-$lng1);
  $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) * sin($dLng/2);
  $c = 2 * atan2(sqrt($a), sqrt(1-$a));
  $dist = $earthRadius * $c;
  $meterConversion = 1609;
  return (($dist * $meterConversion)/1000);
}

$risk_counter = -1;

if(isset($_GET["lat"]) && isset($_GET["lat"])) {

$lat = $_GET["lat"];
$lng = $_GET["lng"];

$fh = fopen("prf.txt", "rb");
$data = fread($fh, filesize("prf.txt"));
fclose($fh);

$risk_radius = 500.0; // km
$risk_points = 200; // number of points in that risk area

$floatArray = explode("\n", $data);
//$min_distance = 999999999.9;
//$min_index = -1;

$risk_counter = 0;

for($i = 0; $i < count($floatArray)/2; $i++) {
  $floatArray[$i*2] = floatval($floatArray[$i*2]);
  $floatArray[$i*2+1] = floatval($floatArray[$i*2+1]);

  /*$distance_square = ($lat - $floatArray[$i*2])*($lat - $floatArray[$i*2]) + ($lng - $floatArray[$i*2+1])*($lng - $floatArray[$i*2+1]);
  if($distance_square < $min_distance) {
    $min_distance = $distance_square;
    $min_index = $i;
  }*/

  if(distance($lat, $lng, $floatArray[$i * 2], $floatArray[$i * 2+1]) < $risk_radius) {
    $risk_counter++;
  }
}
  if($risk_counter >= $risk_points) {
    echo "RISKY";
  } else {
    echo "OK";
  }
} else {
  echo "ERROR";
}
?>