<?php 
    session_start();

    $mensaje = "";

    if(isset($_POST['btnAction'])){
        switch($_POST['btnAction']){
            case 'Agregar':
                if(is_numeric(openssl_decrypt($_POST['id'], COD, KEY))){
                    $ID = openssl_decrypt($_POST['id'], COD, KEY);
                    $mensaje.= "Ok ID correcto ".$ID."<br>";
                }else{
                    $mensaje = "Ups, algo salio mal";
                }
                if(is_string(openssl_decrypt($_POST['nombre'], COD, KEY))){
                    $NOMBRE = openssl_decrypt($_POST['nombre'], COD, KEY);
                    $mensaje.= "Ok Nombre correcto ".$NOMBRE."<br>";
                } else{
                    $mensaje = "Ups, algo salio mal";
                }
                if(is_numeric(openssl_decrypt($_POST['precio'], COD, KEY))){
                    $PRECIO = openssl_decrypt($_POST['precio'], COD, KEY);
                    $mensaje.= "Ok Precio correcto ".$PRECIO."<br>";
                }else{
                    $mensaje = "Ups, algo salio mal";
                }
                if(is_numeric(openssl_decrypt($_POST['cantidad'], COD, KEY))){
                    $CANTIDAD = openssl_decrypt($_POST['cantidad'], COD, KEY);
                    $mensaje.= "Ok Cantidad correcto ".$CANTIDAD."<br>";
                }
                else{
                    $mensaje = "Ups, algo salio mal";
                }

                if(!isset($_SESSION['CARRITO'])){
                    $producto = array(
                        'ID' => $ID,
                        'NOMBRE' => $NOMBRE,
                        'PRECIO' => $PRECIO,
                        'CANTIDAD' => $CANTIDAD
                    );
                    $_SESSION['CARRITO'][0] = $producto;
                    $mensaje = "Producto agregado al carrito";
                }
                else{
                    $idProductos = array_column($_SESSION['CARRITO'], "ID");
                    if(in_array($ID, $idProductos)){
                        $mensaje = "El producto ya ha sido seleccionado...";
                    }
                    else{
                        $NumeroProductos = count($_SESSION['CARRITO']);
                        $producto = array(
                            'ID' => $ID,
                            'NOMBRE' => $NOMBRE,
                            'PRECIO' => $PRECIO,
                            'CANTIDAD' => $CANTIDAD
                        );
                        $_SESSION['CARRITO'][$NumeroProductos] = $producto;
                        $mensaje = "Producto agregado al carrito";
                    }
                }

            break;

            case 'Eliminar':
                if(is_numeric(openssl_decrypt($_POST['id'], COD, KEY))){
                    $ID = openssl_decrypt($_POST['id'], COD, KEY);
                    foreach($_SESSION['CARRITO'] as $indice => $producto){
                        if($producto['ID'] == $ID){
                            unset($_SESSION['CARRITO'][$indice]);
                            $mensaje = "Producto eliminado";
                        }
                    }
                }else{
                    $mensaje = "Ups, algo salio mal";
                }
            break;
        }
    }

    // destruir sesion
    if(isset($_POST['btnVaciar'])){
        unset($_SESSION['CARRITO']);
        $mensaje = "Carrito vaciado";
    }

?>