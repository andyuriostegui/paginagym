<?php
// Definir variables e inicializar con valores vacíos
$correo = $contraseña = $conf_contraseña = $nombre="";


//se usa para conocer el método de solicitud ( por ejemplo GET, POST, PUT, etc ) que se usa para acceder a la página
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // se incluye el archivo de configuración
    require_once "bd.php";

  // Validar nombre de la persona, si fue enviado mediante el método post, eliminando 
  //espacio en blanco al iniico y final de la cadena de texto
  $correo = trim($_POST["email"]);     
    //validar si el correo ya existe o no
    //? funciona como un marcador de posicion, donde separamos los datos proporcionados
    //por el usuario de la cosulta sql
    $sql = "select Id from users  where Nombre = ?";
    if($declPrep = mysqli_prepare($conex, $sql))
    {
        // Vincular variables param_correo a la declaración preparada declPrep como un parametro
        //en donde el tipo de dato esperado es una cadena "s"
        mysqli_stmt_bind_param($declPrep, "s", $param_nombre);
        //asignar parámetros
        $param_nombre = trim($_POST["username"]);
        // Intentar ejecutar la declaración preparada
        if(mysqli_stmt_execute($declPrep))
        {
            /* almacenar resultado de la consulta preparada en la memoria
            de procesamiento*/
            mysqli_stmt_store_result($declPrep);

            if(mysqli_stmt_num_rows($declPrep) == 1)
            {
                echo "<script>alert('El nombre de usuario ya se encuentra registrado. Intente con otro');window.location.href=''</script>";
            } 
            else
            {
                $nombre = trim($_POST["username"]);

                // contraseña
                $contraseña = trim($_POST["password"]);

                // Validar que se confirma la contraseña
                if(strlen(trim($_POST["password"])) <5)
                {
                    echo "<script>alert('La contraseña debe tener al menos 5 caracteres. Intente de nuevo');window.location.href=''</script>";
                }
                else
                {
                    $conf_contraseña = trim($_POST["password"]);

                    if(($contraseña != $conf_contraseña))
                    {
                        echo "<script>alert('La contraseña es diferente. Intente otra vez');window.location.href=''</script>";
                    }
                    else
                    {
                        // consulta de insercion con marcadores de posicion
                        $sql = "insert into users (Nombre, Contraseña, Correo) VALUES (?, ?, ?)";
                        if($declPrep = mysqli_prepare($conex, $sql)) //se prepara la consulta, si es exitosa, la declaracion preparada se guarda en la variable
                        {
                            // Vincular variables a la declaración preparada como parámetros
                            mysqli_stmt_bind_param($declPrep, "sss", $param_nombre, $param_contraseña, $param_correo );
                            // asigno los valores a los parametros
                            $param_nombre = $nombre;
			                $param_contraseña = password_hash($contraseña, PASSWORD_DEFAULT); // Crear una contraseña hash
                            $param_correo = $correo;
                            // Intentar ejecutar la declaración preparada
                            if(mysqli_stmt_execute($declPrep))
                            {
                                mysqli_stmt_close($declPrep);

                                // Cerrar la conexión
                                mysqli_close($conex);
                                // Redirigir a la página de inicio de sesión (login.php)
                                header("location: ../paginas_inicio/sesion.html");
                            } else
                            {
                                echo "Algo salió mal, por favor inténtalo de nuevo.";
                            }
                        }
                        // claración de cierre
                    }
                }
            }
        }
    }
    // Declaración de cierre
}

?>

 