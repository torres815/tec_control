<?php 
include 'db.php'; 

if(isset($_POST['registrar'])) {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $fecha_nac = $_POST['fecha_nac'];
    $genero = $_POST['genero'];
    $distancia = $_POST['distancia'];
    $dorsal = $_POST['dorsal'];
    
    $nacimiento = new DateTime($fecha_nac);
    $hoy = new DateTime();
    $edad = $hoy->diff($nacimiento)->y;

    $sql = "INSERT INTO corredores (nombre, fecha_nac, edad, genero, distancia, dorsal) 
            VALUES ('$nombre', '$fecha_nac', '$edad', '$genero', '$distancia', '$dorsal')";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>alert('Corredor #$dorsal registrado con $edad años');</script>";
    } else {
        echo "<script>alert('Error: El dorsal ya existe');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - TEC</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="table-container" style="max-width: 500px; margin: auto;">
            <h2 style="text-align: center;">NUEVO CORREDOR</h2>
            <form method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                <input type="text" name="nombre" placeholder="Nombre Completo" required class="btn-modern" style="text-align: left; border: 1px solid #ddd;">
                <label>Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nac" required class="btn-modern">
                <select name="genero" class="btn-modern">
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
                <select name="distancia" class="btn-modern">
                    <option value="8k">8K</option>
                    <option value="16k">16K</option>
                </select>
                <input type="number" name="dorsal" placeholder="Número de Dorsal" required class="btn-modern" style="text-align: left; border: 1px solid #ddd;">
                <button type="submit" name="registrar" class="btn-modern" style="background: var(--primary-blue); color: white;">GUARDAR EN BASE DE DATOS</button>
            </form>
        </div>
    </div>
    <div style="margin-top: 50px; border-top: 1px solid #333; padding-top: 20px; text-align: center;">
    <p style="color: #666; font-size: 0.8rem;">ZONA DE PELIGRO</p>
    <a href="#" onclick="confirmarBorrado()" class="btn-modern btn-danger" style="font-size: 0.7rem;">
        BORRAR TODOS LOS CORREDORES
    </a>
</div>

<script>
function confirmarBorrado() {
    if (confirm("¿ESTÁS COMPLETAMENTE SEGURO? Esta acción borrará a todos los corredores y no se puede deshacer.")) {
        if (confirm("POR FAVOR CONFIRMA UNA VEZ MÁS: ¿Vaciar toda la base de datos?")) {
            window.location.href = "borrar_datos.php";
            // También limpiamos el cronómetro por si acaso
            localStorage.removeItem('inicioCarrera');
        }
    }
}
</script>
</body>
</html>