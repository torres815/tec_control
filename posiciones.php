<?php 
include 'db.php'; 

$distancia = $_GET['distancia'] ?? '8k';
$genero = $_GET['genero'] ?? 'Masculino';
$edad_min = $_GET['edad_min'] ?? 15;
$edad_max = $_GET['edad_max'] ?? 19;

$query = "SELECT * FROM corredores 
          WHERE distancia = '$distancia' 
          AND genero = '$genero' 
          AND edad BETWEEN $edad_min AND $edad_max 
          AND estado = 'Finalizado'
          ORDER BY tiempo_final ASC";
$res = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Posiciones por Categoría - TEC</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <div class="table-container">
            <h2>Resultados por Categoría</h2>
            <form method="GET" class="filter-grid">
                <select name="distancia" class="btn-modern">
                    <option value="8k">8K</option>
                    <option value="16k">16K</option>
                </select>
                <select name="genero" class="btn-modern">
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
                <select name="rango" class="btn-modern" onchange="actualizarEdades(this.value)">
                    <option value="15-19">15 a 19 años</option>
                    <option value="20-29">20 a 29 años</option>
                    <option value="30-39">30 a 39 años</option>
                    <option value="40-49">40 a 49 años</option>
                </select>
                <input type="hidden" name="edad_min" id="emin" value="15">
                <input type="hidden" name="edad_max" id="emax" value="19">
                
                <button type="submit" class="btn-modern" style="background: var(--primary-blue); color:white;">VER TABLA</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Pos</th>
                        <th>Dorsal</th>
                        <th>Nombre</th>
                        <th>Edad</th>
                        <th>Tiempo Final</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $p = 1; while($r = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><strong><?php echo $p++; ?>°</strong></td>
                        <td>#<?php echo $r['dorsal']; ?></td>
                        <td><?php echo $r['nombre']; ?></td>
                        <td><?php echo $r['edad']; ?></td>
                        <td><?php echo $r['tiempo_final']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    function actualizarEdades(valor) {
        const [min, max] = valor.split('-');
        document.getElementById('emin').value = min;
        document.getElementById('emax').value = max;
    }
    </script>
</body>
</html>