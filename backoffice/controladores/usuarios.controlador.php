<?php

// https://github.com/PHPMailer/PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

Class ControladorUsuarios{

	/*=============================================
	Registro de usuarios
	=============================================*/

	public function ctrRegistroUsuario(){

        // Verificar si el formulario ha sido enviado y si las variables POST están definidas
        if(isset($_POST["registroNombre"]) && isset($_POST["registroEmail"]) && isset($_POST["registroPassword"])){

            $ruta = ControladorRuta::ctrRuta();

            if(preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["registroNombre"]) &&
               filter_var($_POST["registroEmail"], FILTER_VALIDATE_EMAIL) &&
               preg_match('/^[a-zA-Z0-9!@#$%^&*()_+=-]+$/', $_POST["registroPassword"])){

                $encriptar = crypt($_POST["registroPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

                $encriptarEmail = md5($_POST["registroEmail"]);

                $tabla = "usuarios";
                $datos = array(
                    "perfil" => "usuario",
                    "nombre" => $_POST["registroNombre"],
                    "email" => $_POST["registroEmail"],
                    "password" => $encriptar,
                    "suscripcion" => 0,
                    "verificacion" => 0,
                    "email_encriptado" => $encriptarEmail,
                    "patrocinador" => $_POST["patrocinador"]
                );

                $respuesta = ModeloUsuarios::mdlRegistroUsuario($tabla, $datos);

                if($respuesta == "ok"){

                       /*=============================================
                        Verificación Correo Electrónico
                        =============================================*/

                        date_default_timezone_set("America/Bogota");

                        /*$mail = new PHPMailer;

                        $mail->Charset = "UTF-8";

                        $mail->isMail();

                        $mail->setFrom("admin@compraganando.com", "Compra Ganando");

                        $mail->addReplyTo("admin@compraganando.com", "Compra Ganando");*/

                        $mail = new PHPMailer(true);
                        $mail->Charset = "UTF-8";
                        // Configuración SMTP
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'enmitiendavirtual1@gmail.com';          // Tu correo
                        $mail->Password = 'gsxx zmga yjfn geyh'; // Contraseña o app password
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;
                        // Remitente y respuesta
                        $mail->setFrom('enmitiendavirtual1@gmail.com', 'Compra Ganando');
                        $mail->addReplyTo('enmitiendavirtual1@gmail.com', 'Compra Ganando');


                        $mail->Subject = mb_encode_mimeheader("🔹 Verifique su dirección de correo electrónico 🔹", "UTF-8");

                        $mail->addAddress($_POST["registroEmail"]);

                        $mail->msgHTML('<div style="width:100%; background:#eee; position:relative; font-family:sans-serif; padding-bottom:40px">
    
                                <center>
                                        
                                    <img style="padding:20px; width:10%" src="https://ventas.compraganando.com/ventas-suscripcion/imagenes/logo1.png">

                                </center>

                                <div style="position:relative; margin:auto; width:600px; background:white; padding:20px">
                                    
                                    <center>

                                        <img style="padding:20px; width:15%" src="https://ventas.compraganando.com/ventas-suscripcion/imagenes/iconoemail.png">

                                        <h3 style="font-weight:100; color:#999">VERIFIQUE SU DIRECCIÓN DE CORREO ELECTRÓNICO</h3>

                                        <hr style="border:1px solid #ccc; width:80%">

                                        <h4 style="font-weight:100; color:#999; padding:0 20px">Para comenzar a usar su cuenta, debe confirmar su dirección de correo electrónico</h4>

                                        <a href="'.$ruta.$encriptarEmail.'" target="_blank" style="text-decoration:none">
                                            
                                            <div style="line-height:60px; background:#0aa; width:60%; color:white">Verifique su dirección de correo electrónico</div>

                                        </a>

                                        <br>

                                        <hr style="border:1px solid #ccc; width:80%">

                                        <h5 style="font-weight:100; color:#999">Si no se inscribió en esta cuenta, puede ignorar este correo electrónico y eliminarlo.</h5>

                                    </center>   

                                </div>

                            </div>');
                                
                        $envio = $mail->Send();

                        if(!$envio){

                    echo '<script>

                            swal({

                                type:"error",
                                title: "¡ERROR!",
                                text: "¡¡Ha ocurrido un problema enviando verificación de correo electrónico a '.$_POST["registroEmail"].' '.$mail->ErrorInfo.', por favor inténtelo nuevamente",
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar"

                            }).then(function(result){

                                if(result.value){

                                    history.back();

                                }

                            }); 

                        </script>';

                    }else{


                        echo '<script>

                            swal({

                                type:"success",
                                title: "¡SU CUENTA HA SIDO CREADA CORRECTAMENTE!",
                                text: "¡Por favor revise la bandeja de entrada o la carpeta SPAM de su correo electrónico para verificar la cuenta!",
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar"

                            }).then(function(result){

                                if(result.value){

                                    window.location = "'.$ruta.'ingreso";

                                }

                            }); 

                        </script>';

                    }
                    
                }

            }else{

          echo '<script>

                    swal({

                        type:"error",
                        title: "¡CORREGIR!",
                        text: "¡No se permiten caracteres especiales en ninguno de los campos!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"

                    }).then(function(result){

                        if(result.value){

                            history.back();

                        }

                    }); 

                </script>';


            }

        }

    }

    /*=============================================
	Mostrar Usuarios
	=============================================*/

	static public function ctrMostrarUsuarios($item, $valor){
	
		$tabla = "usuarios";

		$respuesta = ModeloUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);

		return $respuesta;

	}


    /*=============================================
    Actualizar Usuario
    =============================================*/

    static public function ctrActualizarUsuario($id, $item, $valor){

        $tabla = "usuarios";

        $respuesta = ModeloUsuarios::mdlActualizarUsuario($tabla, $id, $item, $valor);

        return $respuesta;

    }


    /*=============================================
    Ingreso Usuario
    =============================================*/

    public function ctrIngresoUsuario(){

        if(isset($_POST["ingresoEmail"])){

             if(preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["ingresoEmail"]) && preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingresoPassword"])){

                $encriptar = crypt($_POST["ingresoPassword"], '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

                $tabla = "usuarios";
                $item = "email";
                $valor = $_POST["ingresoEmail"];

                $respuesta = ModeloUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);

                if($respuesta["email"] == $_POST["ingresoEmail"] && $respuesta["password"] == $encriptar){

                    if($respuesta["verificacion"] == 0){

                        echo'<script>

                            swal({
                                    type:"error",
                                    title: "¡ERROR!",
                                    text: "¡El correo electrónico aún no ha sido verificado, por favor revise la bandeja de entrada o la carpeta SPAM de su correo electrónico para verificar la cuenta, o contáctese con nuestro soporte a admin@compraganando.com!",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar"
                                  
                            }).then(function(result){

                                    if(result.value){   
                                        history.back();
                                      } 
                            });

                        </script>';

                        return;

                    }else{

                        $_SESSION["validarSesion"] = "ok";
                        $_SESSION["id"] = $respuesta["id_usuario"];

                        $ruta = ControladorRuta::ctrRuta();

                        echo '<script>
                    
                            window.location = "'.$ruta.'backoffice";              

                        </script>';

                    }

                }else{

                    echo'<script>

                        swal({
                                type:"error",
                                title: "¡ERROR!",
                                text: "¡El email o contraseña no coinciden!",
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar"
                              
                        }).then(function(result){

                                if(result.value){   
                                    history.back();
                                  } 
                        });

                    </script>';

                }


             }else{

                echo '<script>

                    swal({

                        type:"error",
                        title: "¡CORREGIR!",
                        text: "¡No se permiten caracteres especiales en ninguno de los campos!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"

                    }).then(function(result){

                        if(result.value){

                            history.back();

                        }

                    }); 

                </script>';

             }

        }

    }
   




}




