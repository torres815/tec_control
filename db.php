<?php
$conn = mysqli_connect("localhost", "root", "", "tec_control");
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
function obtenerNombreCategoria($edad, $genero) {
    $rango = "";
    if ($edad >= 15 && $edad <= 19) $rango = "15-19";
    elseif ($edad >= 20 && $edad <= 29) $rango = "20-29";
    elseif ($edad >= 30 && $edad <= 39) $rango = "30-39";
    elseif ($edad >= 40 && $edad <= 49) $rango = "40-49";
    elseif ($edad >= 50 && $edad <= 59) $rango = "50-59";
    else $rango = "60+";
    
    // Retorna algo como "20-29 FEM" o "30-39 MASC"
    $sufijo = ($genero == 'Femenino') ? 'FEM' : 'MASC';
    return $rango . " " . $sufijo;
}
?>