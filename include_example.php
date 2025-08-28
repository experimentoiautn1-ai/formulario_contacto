<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=b, initial-scale=1.0">
    <title>Ejemplo formulario</title>
    
    <!-- CSS del formulario -->
    <link rel="stylesheet" href="formulario_contacto/formulario.css">
</head>
<body>
    <!-- Header feito con inline style -->
    <header style="
        background-color: #252525;
        width: 100%;
        height: 15%;
        display: flex;
        justify-content: center;
        aling-items: center;
        ">
        Header feito
    </header>
    <!-- Include del formulario -->
    <main style="background-color: #181818;">
        <?php include __DIR__ . '/src/formulario.html'; ?>
    </main>
</body>
</html>