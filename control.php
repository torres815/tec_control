<?php 
include 'db.php'; 
$res = mysqli_query($conn, "SELECT COUNT(*) as t FROM corredores");
$total = mysqli_fetch_assoc($res)['t'];
$res2 = mysqli_query($conn, "SELECT COUNT(*) as l FROM corredores WHERE estado='Finalizado'");
$llegaron = mysqli_fetch_assoc($res2)['l'];
$faltan = $total - $llegaron;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control TEC</title>
    <link rel="stylesheet" href="style.css">
   <style>
    .cronometro {
        font-size: 7rem;
        text-align: center;
        color: var(--primary-cyan);
        background: #000;
        padding: 40px;
        border-radius: 20px;
        margin: 20px 0;
        font-family: 'Courier New', Courier, monospace;
        text-shadow: 0 0 20px rgba(0, 242, 255, 0.7);
        border: 2px solid #161b22;
        box-shadow: inset 0 0 30px rgba(0, 242, 255, 0.1);
    }
    #contadorFaltan {
        color: #ff4757;
        text-shadow: 0 0 10px rgba(255, 71, 87, 0.5);
    }
</style>
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container" style="text-align: center;">
        <div class="cronometro" id="reloj">00:00:00.00</div>
        
        <div class="table-container">
            <p>CORREDORES FALTANTES</p>
            <div class="faltantes-box" id="contadorFaltan"><?php echo $faltan; ?></div>
            
            <button id="btnIniciar" class="btn-modern" style="background: #44bd32; color: white;">INICIAR CARRERA</button>
            
            <div id="panelDorsal" style="display:none; margin-top: 20px;">
                <input type="number" id="inputDorsal" placeholder="DORSAL" style="font-size: 2rem; width: 150px; padding: 10px; border-radius: 10px; border: 2px solid #00a8ff;">
<button id="btnFinalizar" class="btn-modern" style="background: #c23616; color: white;">FINALIZAR CARRERA</button>            </div>
        </div>
    </div>

    <script>
    let inicio;
    let intervalo;

    if(localStorage.getItem('inicioCarrera')) {
        inicio = parseInt(localStorage.getItem('inicioCarrera'));
        arrancar();
    }

    document.getElementById('btnIniciar').onclick = function() {
        inicio = Date.now();
        localStorage.setItem('inicioCarrera', inicio);
        arrancar();
    };

    function arrancar() {
        document.getElementById('btnIniciar').style.display = 'none';
        document.getElementById('panelDorsal').style.display = 'block';
        intervalo = setInterval(actualizar, 10);
    }

    function actualizar() {
        let diff = Date.now() - inicio;
        let ms = Math.floor((diff % 1000) / 10);
        let s = Math.floor((diff / 1000) % 60);
        let m = Math.floor((diff / 60000) % 60);
        let h = Math.floor(diff / 3600000);
        document.getElementById('reloj').textContent = 
            (h<10?'0'+h:h)+":"+(m<10?'0'+m:m)+":"+(s<10?'0'+s:s)+"."+(ms<10?'0'+ms:ms);
    }

    document.getElementById('inputDorsal').onkeypress = function(e) {
        if(e.key === 'Enter') {
            let d = this.value;
            let t = document.getElementById('reloj').textContent.split('.')[0];
            fetch('registrar_llegada.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `dorsal=${d}&tiempo=${t}`
            })
            .then(r => r.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('contadorFaltan').textContent = data.faltantes;
                    if(data.faltantes == 0) document.getElementById('btnFinalizar').disabled = false;
                    this.value = '';
                } else { alert(data.message); }
            });
        }
    };

 // ... dentro de tu <script> ...

document.getElementById('btnFinalizar').onclick = function() {
    // 1. Preguntar confirmación
    if(confirm('¿Deseas finalizar la carrera? El tiempo se detendrá y se limpiará el reloj.')) {
        
        // 2. Detener el intervalo (detiene el movimiento del reloj)
        clearInterval(intervalo);
        
        // 3. Borrar el registro de la hora de inicio del navegador
        localStorage.removeItem('inicioCarrera');
        
        // 4. Opcional: Podrías enviar un aviso a la BD de que la carrera terminó
        alert('Carrera finalizada con éxito.');
        
        // 5. Recargar la página para limpiar la interfaz
        location.reload();
    }
};
    </script>
</body>
</html>