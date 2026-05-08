<?php 
include 'db.php'; 

$datos = null; // Inicializamos la variable para evitar el Warning

if(isset($_GET['dorsal']) && !empty($_GET['dorsal'])) {
    $dorsal = mysqli_real_escape_string($conn, $_GET['dorsal']);
    
    // Consulta los datos del corredor
    $query = "SELECT * FROM corredores WHERE dorsal = '$dorsal' LIMIT 1";
    $res = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($res) > 0) {
        $datos = mysqli_fetch_assoc($res);
        $dist = $datos['distancia'];
        $gen = $datos['genero'];
        $edad = $datos['edad'];
        $t_final = $datos['tiempo_final'];

        // Lógica de Rangos de Edad para posición por categoría
        $min = 15; $max = 19; // Default
        if ($edad >= 20 && $edad <= 29) { $min = 20; $max = 29; }
        elseif ($edad >= 30 && $edad <= 39) { $min = 30; $max = 39; }
        elseif ($edad >= 40 && $edad <= 49) { $min = 40; $max = 49; }
        elseif ($edad >= 50 && $edad <= 59) { $min = 50; $max = 59; }
        elseif ($edad >= 60) { $min = 60; $max = 200; }

        // Calcular posición en categoría (edad + genero + distancia)
        $q_cat = "SELECT COUNT(*) + 1 as pos FROM corredores 
                  WHERE distancia = '$dist' AND genero = '$gen' 
                  AND edad BETWEEN $min AND $max 
                  AND tiempo_final < '$t_final' AND estado = 'Finalizado'";
        $res_cat = mysqli_query($conn, $q_cat);
        $pos_cat = mysqli_fetch_assoc($res_cat)['pos'];

        // Calcular posición General (mismo genero + misma distancia)
        $q_gral = "SELECT COUNT(*) + 1 as pos FROM corredores 
                   WHERE distancia = '$dist' AND genero = '$gen'
                   AND tiempo_final < '$t_final' AND estado = 'Finalizado'";
        $res_gral = mysqli_query($conn, $q_gral);
        $pos_gral = mysqli_fetch_assoc($res_gral)['pos'];
    } else {
        echo "<script>alert('Dorsal no encontrado');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket - TEC CONTROL</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .ticket-card {
            background: white;
            width: 300px;
            margin: 20px auto;
            padding: 20px;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 10px;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .header-logos {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .logo-placeholder {
            width: 60px;
            height: 60px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            border: 1px solid #ccc;
        }
        .pos-categoria { font-size: 4rem; font-weight: 900; color: #0097e6; margin: 10px 0; }
        .tiempo-grande { font-size: 2rem; font-weight: bold; }
        .pos-general { font-size: 1.2rem; color: #666; }
        
        @media print {
            .nav-admin, .busqueda-box, .btn-print { display: none; }
            body { background: white; }
            .ticket-card { border: none; margin: 0; width: 100%; }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container busqueda-box">
        <div class="table-container" style="max-width: 400px; margin: auto;">
            <form method="GET">
                <input type="number" name="dorsal" placeholder="Ingresar Dorsal" class="btn-modern" style="width:88%; border:1px solid #ccc;" required>
                <button type="submit" class="btn-modern" style="background:var(--primary-blue); color:white; width:100%; margin-top:10px;">BUSCAR Y GENERAR</button>
            </form>
        </div>
    </div>

    <?php if($datos): ?>
    <div class="ticket-card">
        <div class="header-logos">
            <img src="logo_tec.png" alt="TEC" style="width:80px;" onerror="this.style.display='none'"> 
        </div>

        <hr>
        <h2 style="margin:10px 0;"><?php echo strtoupper($datos['nombre']); ?></h2>
        <p>Dorsal: <strong><?php echo $datos['dorsal']; ?></strong> | Distancia: <?php echo $datos['distancia']; ?></p>
        
        <p style="margin-top:20px; font-size: 0.9rem; letter-spacing: 2px;">POSICIÓN CATEGORÍA</p>
        <div class="pos-categoria"><?php echo $pos_cat; ?>º</div>
        
        <p>TIEMPO OFICIAL</p>
        <div class="tiempo-grande"><?php echo $datos['tiempo_final']; ?></div>
        
        <p class="pos-general">Puesto Gral: <?php echo $pos_gral; ?>º</p>
        
        <button onclick="window.print()" class="btn-modern btn-print" style="margin-top:20px; background:#44bd32; color:white;">IMPRIMIR TICKET</button>
    </div>
    <?php endif; ?>
</body>
</html>