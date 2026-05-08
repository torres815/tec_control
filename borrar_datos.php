<?php
include 'db.php';

// TRUNCATE es mejor que DELETE porque resetea los contadores de ID a 1
$sql = "TRUNCATE TABLE corredores";

if (mysqli_query($conn, $sql)) {
    header("Location: registro.php?reset=success");
} else {
    echo "Error al limpiar la base de datos: " . mysqli_error($conn);
}
?>