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

                        $mail = new PHPMailer;

                        $mail->Charset = "UTF-8";

                        $mail->isMail();

                        $mail->setFrom("admin@compraganando.com", "Compra Ganando");

                        $mail->addReplyTo("admin@compraganando.com", "Compra Ganando");

                        $mail->Subject = mb_encode_mimeheader("🔹 Verifique su dirección de correo electrónico 🔹", "UTF-8");

                        $mail->addAddress($_POST["registroEmail"]);

                        $mail->msgHTML('<div style="width:100%; background:#eee; position:relative; font-family:sans-serif; padding-bottom:40px">
    
                                <center>
                                        
                                    <img style="padding:20px; width:10%" src="https://tutorialesatualcance.com/tienda/logo.png">

                                </center>

                                <div style="position:relative; margin:auto; width:600px; background:white; padding:20px">
                                    
                                    <center>

                                        <img style="padding:20px; width:15%" src="https://tutorialesatualcance.com/tienda/icon-email.png">

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

<<<<<<< HEAD
            if($respuesta == "ok"){





                
=======
>>>>>>> aac5a0a71fb9ca8bd3d1a78c895ee30c0d551b08
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


}




