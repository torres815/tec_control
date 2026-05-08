<?php 
include 'db.php'; 

function obtenerTabla($dist, $gen, $conn) {
    return mysqli_query($conn, "SELECT nombre, dorsal, tiempo_final FROM corredores 
           WHERE distancia = '$dist' AND genero = '$gen' AND estado = 'Finalizado' 
           ORDER BY tiempo_final ASC LIMIT 10");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TEC - Clasificación General</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h1>CLASIFICACIONES GENERALES</h1>
        
        <?php foreach(['8k', '16k'] as $dist): ?>
            <h2 style="background: var(--primary-blue); color: white; padding: 10px; border-radius: 8px; margin-top: 40px;">DISTANCIA <?php echo $dist; ?></h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <?php foreach(['Masculino', 'Femenino'] as $gen): ?>
                    <div class="table-container">
                        <h3>TOP 10 <?php echo strtoupper($gen); ?></h3>
                        <table>
                            <thead><tr><th>Pos</th><th>Dorsal</th><th>Nombre</th><th>Tiempo</th></tr></thead>
                            <tbody>
                                <?php 
                                $res = obtenerTabla($dist, $gen, $conn);
                                $p = 1;
                                while($r = mysqli_fetch_assoc($res)): ?>
                                    <tr>
                                        <td><?php echo $p++; ?>°</td>
                                        <td>#<?php echo $r['dorsal']; ?></td>
                                        <td><?php echo $r['nombre']; ?></td>
                                        <td><?php echo $r['tiempo_final']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>