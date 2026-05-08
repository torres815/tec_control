<?php include 'db.php'; 

if(isset($_POST['aplicar_penalizacion'])) {
    $dorsal = $_POST['dorsal_penalizar'];
    $tiempo_penal = $_POST['tiempo_penal']; // Formato HH:MM:SS

    // Sumar el tiempo de llegada + la penalización
    $sql = "UPDATE corredores SET 
            penalizacion = '$tiempo_penal',
            tiempo_final = ADDTIME(tiempo_llegada, '$tiempo_penal') 
            WHERE dorsal = '$dorsal'";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Penalización aplicada correctamente');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Penalizaciones - TEC</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <?php include 'nav.php'; ?>


    <div class="container">
        <div class="table-container" style="max-width: 500px; margin: auto;">
            <h2>Registrar Penalización</h2>
            <form method="POST">
                <div style="margin-bottom: 15px;">
                    <label>Dorsal del Corredor:</label><br>
                    <input type="number" name="dorsal_penalizar" class="input-modern" style="width: 100%;" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Tiempo a sumar (HH:MM:SS):</label><br>
                    <input type="text" name="tiempo_penal" placeholder="00:05:00" class="input-modern" style="width: 100%;" required>
                </div>
                <button type="submit" name="aplicar_penalizacion" class="btn-modern" style="background: #f1c40f; color: #000; width: 100%;">APLICAR PENALIZACIÓN</button>
            </form>
        </div>
    </div>
</body>
</html>