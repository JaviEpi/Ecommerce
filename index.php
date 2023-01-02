<?php
include_once 'global\config.php';
include_once 'global\conexion.php';
include_once 'carrito.php';
include_once 'template\cabecera.php';

$sentencia = $pdo->prepare("SELECT * FROM `tblproductos`;");
$sentencia->execute();
$listaProductos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- cdn bootstrap lastest version -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- cdn bootstrap 5.2.3 version js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <script src="main.js"></script>
    <title>Empresa Epi's</title>
</head>
<body>
    <main>
        <?php
        if ($mensaje != "") { ?>
                    <section class="container">
            <div class="alert alert-success">
                <?php echo $mensaje; ?>
                <a href="mostrarCarrito.php" class="badge rounded-pill bg-success">Ver carrito</a>
            </div>
        </section>
        <?php }
        ?>
        <section class="container">
            <div class="row">
                <?php
                foreach ($listaProductos as $producto) { ?>
                    <div class="col-3">
                        <div class="card" style="min-height: 600px;">
                            <img title="<?php echo $producto['nombre'] ?>" alt="<?php echo $producto['nombre'] ?>" src="<?php echo $producto['imagen'] ?>" class="card-img-top" alt="..." width="auto" height="200px">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $producto['nombre'] ?></h5>
                                <p class="card-text"><?php echo $producto['descripcion'] ?></p>
                                <p class="card-text"><small class="text-muted"><?php echo $producto['precio'] ?>€</small></p>

                                <form action="" method="post">
                                    <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['id'], COD, KEY); ?>">
                                    <input type="hidden" name="nombre" id="nombre" value="<?php echo openssl_encrypt($producto['nombre'], COD, KEY); ?>">
                                    <input type="hidden" name="precio" id="precio" value="<?php echo openssl_encrypt($producto['precio'], COD, KEY); ?>">
                                    <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt(1, COD, KEY); ?>">
                                    <button type="submit" class="btn btn-warning" name="btnAction" value="Agregar">Agregar al carrito</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    </main>
    <?php
    include_once 'template\footer.php';
    ?>
</body>
</html>