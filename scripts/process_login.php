<?php
// Inicializa la sesión
session_start();
// Verifique si el usuario ya ha iniciado sesión, si es así, rediríjalo a la página inicio sesion iniciada
if(isset($_SESSION["logueado"]) && $_SESSION["logueado"] === true)
{
    header("location: ../Gym - Goodbody.html");
    exit;
}
// Incluir el archivo de configuración
require_once "bd.php";
// Definir variables e inicializar con valores vacíos
$usuario = $contraseña = "";
//se usa para conocer el método de solicitud ( por ejemplo GET, POST, PUT, etc ) que se usa para acceder a la página
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $usuario = trim($_POST["username"]);
    $contraseña = trim($_POST["password"]);
    // Preparar la consulta select
    $sql = "select Id, Nombre, Contraseña from users where Nombre = ?";
    if($declPrep = mysqli_prepare($conex, $sql))
    {
        /* Vincular variables a la declaración preparada como parámetros, s es por la
		variable de tipo string*/
        mysqli_stmt_bind_param($declPrep, "s", $param_usuario);
        // Asignar parámetros
        $param_usuario = $usuario;
        // Intentar ejecutar la declaración preparada
        if(mysqli_stmt_execute($declPrep))
        {
            // almacenar el resultado de la consulta
            mysqli_stmt_store_result($declPrep);

            /*Verificar si existe el nombre de usuario, si es así,
            verificar la contraseña*/
            if(mysqli_stmt_num_rows($declPrep) == 1)
            {                    
                // Vincular las variables del resultado
                mysqli_stmt_bind_result($declPrep, $id, $usuario, $hashed_contraseña);
					
                //obtener los valores de la consulta
                if(mysqli_stmt_fetch($declPrep))
                {
            		/*comprueba que la contraseña ingresada sea igual a la 	
    		    	almacenada con hash*/
                        
                    if(password_verify($contraseña, $hashed_contraseña))
                    {
                        // se almacenan los datos en las variables de la sesión
                        $_SESSION["logueado"] = true;
                        $_SESSION["Id"] = $id;
                        $_SESSION["username"] = $usuario;                        
       
                        // Redirigir al usuario a la página de inicio
                        header("location: ../Gym - Goodbody.html");
                    } 
                    else
                    {
                        // Mostrar un mensaje de error si la contraseña no es válida
                        echo "<script>alert('La contraseña es incorrecta, vuelve a interntar.');window.location.href=''</script>";
                    }
                }
            }
            else
            {
                // Mostrar un mensaje de error si el nombre de usuario no existe
                echo "<script>alert('No existe una cuenta con ese usuario, vuelve a interntar.');window.location.href=''</script>";
            }
            mysqli_stmt_close($declPrep);   
        }
        else
        {
            echo "<script>alert('Algo salió mal, vuelve a interntar.');window.location.href=''</script>";
        }
    }
    // Cerrar la sentencia de consulta
}
// Cerrar laconexión
mysqli_close($conex);

?>