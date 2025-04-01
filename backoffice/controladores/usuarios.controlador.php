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
                echo '<script>
                    swal({
                        type:"success",
                        title: "¡SU CUENTA HA SIDO CREADA CORRECTAMENTE!",
                        text: "¡Por favor revise su correo electrónico para verificar la cuenta!",
                        showConfirmButton: true,
                        confirmButtonText: "Cerrar"
                    }).then(function(result){
                        if(result.value){
                            window.location = "'.$ruta.'ingreso";
                        }
                    });    
                </script>';
            }

        } else {
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




