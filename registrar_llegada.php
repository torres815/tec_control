<?php
include 'db.php';

if(isset($_POST['dorsal'])) {
    $dorsal = $_POST['dorsal'];
    $tiempo = $_POST['tiempo'];

    // Actualizamos el corredor
    $sql = "UPDATE corredores SET tiempo_llegada = '$tiempo', tiempo_final = '$tiempo', estado = 'Finalizado' 
            WHERE dorsal = '$dorsal' AND estado = 'Pendiente'";
    
    if(mysqli_query($conn, $sql) && mysqli_affected_rows($conn) > 0) {
        // Contar cuántos faltan
        $res = mysqli_query($conn, "SELECT COUNT(*) as faltan FROM corredores WHERE estado = 'Pendiente'");
        $faltan = mysqli_fetch_assoc($res)['faltan'];
        
        echo json_encode(['success' => true, 'faltantes' => $faltan]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Dorsal no encontrado o ya registrado']);
    }
}
?>