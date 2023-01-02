<?php
include 'global\config.php';
include_once 'global\conexion.php';
include_once 'carrito.php';
include 'template\cabecera.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://www.paypal.com/sdk/js?client-id=sb&enable-funding=venmo&currency=EUR" data-sdk-integration-source="button-factory"></script>

    <title></title>
</head>

<body>
    <?php
    if($_POST){
        $total = 0;
        $SID = session_id();
        $correo = $_POST['email'];
        foreach($_SESSION['CARRITO'] as $indice => $producto){
            $total = $total + ($producto['PRECIO'] * $producto['CANTIDAD']);
        }
        $sentencia = $pdo->prepare("INSERT INTO `ventas` (`ID`, `ClaveTransaccion`, `PaypalDatos`, `Fecha`, `Correo`, `Total`, `status`) VALUES (NULL, :claveTransaccion, '', NOW(), :correo, :total, 'pendiente');");
        $sentencia->bindParam(":claveTransaccion", $SID);
        $sentencia->bindParam(":correo", $correo);
        $sentencia->bindParam(":total", $total);
        $sentencia->execute();
        $idVenta = $pdo->lastInsertId();

        foreach($_SESSION['CARRITO'] as $indice => $producto){
            $sentencia = $pdo->prepare("INSERT INTO `detalleVenta` (`ID`, `IDVENTA`, `IDPRODUCTO`, `PRECIOUNITARIO`, `CANTIDAD`, `DESCARGADO`) VALUES (NULL, :idVenta, :idProducto, :precioUnitario, :cantidad, '0');");
            $sentencia->bindParam(":idVenta", $idVenta);
            $sentencia->bindParam(":idProducto", $producto['ID']);
            $sentencia->bindParam(":precioUnitario", $producto['PRECIO']);
            $sentencia->bindParam(":cantidad", $producto['CANTIDAD']);
            $sentencia->execute();
        }

    }
    ?>

    <div class="jumbotron text-center">
        <h1 class="display-4">¡Paso Final!</h1>
        <hr class="my-4">
        <p class="lead">Estas a punto de pagar con paypal la cantidad de:
            <h4>$<?php echo number_format($total, 2); ?>€</h4>
            <div id="paypal-button-container"></div>
        </p>
        <p>Los productos podrán ser descargados una vez que se procese el pago</p><br>
        <strong>(Para aclaraciones: <a href="mailto:javi.epi10@gmail.com">Escriba aquí</a> )</strong>
    </div>

    <div id="smart-button-container">
      <div style="text-align: center;">
        <div id="paypal-button-container"></div>
      </div>
    </div>
  <script>
    function initPayPalButton() {
      paypal.Buttons({
        style: {
          shape: 'rect',
          color: 'gold',
          layout: 'vertical',
          label: 'paypal',
          
        },

        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{"amount":{"currency_code":"EUR","value":1}}]
          });
        },

        onApprove: function(data, actions) {
          return actions.order.capture().then(function(orderData) {
            
            // Full available details
            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));

            // Show a success message within this page, e.g.
            const element = document.getElementById('paypal-button-container');
            element.innerHTML = '';
            element.innerHTML = '<h3>Thank you for your payment!</h3>';

            // Or go to another URL:  actions.redirect('thank_you.html');
            
          });
        },

        onError: function(err) {
          console.log(err);
        }
      }).render('#paypal-button-container');
    }
    initPayPalButton();
  </script>

    <?php
    include 'template\footer.php';
    ?>
</body>

</html>