<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helado 3D</title>
    <style>
        body { margin: 0; overflow: hidden; }
        canvas { display: block; }
        #instructions {
            position: absolute;
            bottom: 10px;
            left: 10px;
            color: white;
            font-family: Arial, sans-serif;
            font-size: 16px;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div id="instructions">Mueve con el mouse el helado</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
    // Configuración de la escena, cámara y renderizador
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.body.appendChild(renderer.domElement);

    // Crear la geometría del helado
    const coneGeometry = new THREE.ConeGeometry(1, 2, 4);
    const coneMaterial = new THREE.MeshBasicMaterial({ color: 0xff8000 });
    const cone = new THREE.Mesh(coneGeometry, coneMaterial);

    cone.rotation.x = Math.PI;
    cone.position.y = -1;

    const sphereGeometry = new THREE.SphereGeometry(1.2, 32, 32);
    const sphereMaterial = new THREE.MeshBasicMaterial({ color: 0xff0000 });
    const sphere = new THREE.Mesh(sphereGeometry, sphereMaterial);

    sphere.position.y = 0.7;

    const iceCream = new THREE.Group();
    iceCream.add(cone);
    iceCream.add(sphere);
    scene.add(iceCream);

    camera.position.z = 5;

    function animate() {
        requestAnimationFrame(animate);
        renderer.render(scene, camera);
    }

    animate();

    let isDragging = false;
    let previousMousePosition = { x: 0, y: 0 };

    document.addEventListener('mousedown', () => { isDragging = true; });
    document.addEventListener('mouseup', () => { isDragging = false; });

    document.addEventListener('mousemove', (e) => {
        if (isDragging) {
            const deltaMove = {
                x: e.offsetX - previousMousePosition.x,
                y: e.offsetY - previousMousePosition.y
            };

            iceCream.rotation.y += deltaMove.x * 0.01;
            iceCream.rotation.x += deltaMove.y * 0.01;
        }

        previousMousePosition = { x: e.offsetX, y: e.offsetY };
    });

    // Función para guardar datos en la base de datos
    function guardarEnBaseDeDatos(info) {
        fetch('guardar_helado.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ info: info })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error);
        });
    }

    // Ejemplo: guardar datos al hacer clic en el helado
    document.addEventListener('click', () => {
        const informacion = 'Helado interactivo guardado';
        guardarEnBaseDeDatos(informacion);
    });

    // Ajustar tamaño del canvas
    window.addEventListener('resize', () => {
        renderer.setSize(window.innerWidth, window.innerHeight);
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
    });
</script>


</body>
</html>
