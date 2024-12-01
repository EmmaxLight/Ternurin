<?php
// Conexión a la base de datos
include('./../includes/config.php');

// Consulta para obtener los nombres de los productos y las existencias
$query = "SELECT nombre, existencias FROM productos";
$result = mysqli_query($conexion, $query);

// Variables para almacenar los nombres y existencias
$product_names = [];
$product_stocks = [];

// Llenar las variables con los datos de la base de datos
while ($row = mysqli_fetch_assoc($result)) {
    $product_names[] = $row['nombre'];
    $product_stocks[] = $row['existencias'];
}

// Convertir los arrays a formato JSON para pasarlos a JavaScript
$product_names_json = json_encode($product_names);
$product_stocks_json = json_encode($product_stocks);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfica de Existencias de Productos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-image: url('https://www.zipang-hobby.com/media/product/085/epoch-sylvanian-families-halloween-night-parade-set-epc48414-by-epoch-ae0.jpg');
            background-size: cover;
            background-position: center;
            animation: fadeIn 2s ease-in-out;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .regresar-button {
            background-color: transparent; /* Botón transparente */
            color: white; /* Letras blancas */
            border: 2px solid white; /* Borde blanco */
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            margin: 20px auto;
            display: block;
            text-align: center;
            font-size: 18px; /* Tamaño de letra aumentado */
            transition: background-color 0.3s ease; /* Efecto suave al pasar el ratón */
        }

        .regresar-button:hover {
            background-color: rgba(255, 255, 255, 0.2); /* Cambia el fondo al pasar el ratón */
        }
    </style>
</head>
<body>
    <div style="width: 80%; margin: 50px auto;">
        <canvas id="productChart"></canvas>
    </div>

    <!-- Botón regresar -->
    <a href="producven.php">
        <button class="regresar-button">Regresar</button>
    </a>

    <script>
        // Obtener los datos desde PHP (nombres y existencias de productos)
        var productNames = <?php echo $product_names_json; ?>;
        var productStocks = <?php echo $product_stocks_json; ?>;

        // Configuración de la gráfica
        var ctx = document.getElementById('productChart').getContext('2d');

        // Generar colores alternados para las barras
        var backgroundColors = [];
        var borderColors = [];
        for (let i = 0; i < productStocks.length; i++) {
            if (i % 2 === 0) { // Morado
                backgroundColors.push('rgba(128, 0, 128, 0.5)');
                borderColors.push('rgba(128, 0, 128, 1)');
            } else { // Azul
                backgroundColors.push('rgba(0, 0, 255, 0.5)');
                borderColors.push('rgba(0, 0, 255, 1)');
            }
        }

        var productChart = new Chart(ctx, {
            type: 'bar', // Tipo de gráfica: barras
            data: {
                labels: productNames, // Nombres de los productos en el eje X
                datasets: [{
                    label: 'Existencias',
                    data: productStocks, // Existencias de los productos en el eje Y
                    backgroundColor: backgroundColors, // Colores de fondo alternados
                    borderColor: borderColors, // Colores de borde alternados
                    borderWidth: 0.5, // Ancho del borde más delgado
                    barThickness: 30, // Grosor de las barras ajustado a un tamaño medio
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            color: 'white', // Nombres de los productos en color blanco
                            font: {
                                size: 16 // Aumentar tamaño de letra de los productos
                            }
                        },
                        grid: {
                            display: false // Ocultar líneas de cuadrícula del eje X
                        },
                        // Juntar más las barras
                        stacked: true // Apilar las barras para juntarlas más
                    },
                    y: {
                        beginAtZero: true, // Empezar desde cero en el eje Y
                        ticks: {
                            color: 'white', // Números del eje Y en color blanco
                        },
                        grid: {
                            color: 'white' // Líneas de la cuadrícula en color blanco
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: 'white' // Color de las etiquetas de la leyenda en blanco
                        }
                    },
                },
                // Ajustar el espaciado entre las barras
                layout: {
                    padding: {
                        left: 10,
                        right: 10,
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });
    </script>
</body>
</html>
