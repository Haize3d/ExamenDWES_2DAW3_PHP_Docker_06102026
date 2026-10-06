<html>
    <head>
        <title>Examen de Desarrollo web en entorno servidor</title>
        <link rel="icon" type="image/png" sizes="32x32" href="../imagenes/favicon.jpeg">
        <link rel="stylesheet" type="text/css" href="../estilos/estilos.css">

    </head>
    <body>
        <h1>Centro de Ayuda al Empleo</h1>
        <h2>    
            <?php
                if(isset($_REQUEST['tipo']))
                {
                    $tipo = "Soldadura";
                    if ($_REQUEST['tipo'] == 'informatica'){
                        $tipo = 'Informática';
                    } elseif ($_REQUEST['tipo'] == 'socio'){
                        $tipo = "Asistencia Sociosanitaria";
                    }
                    echo "Solicitudes de $tipo";
                }
            ?>
        </h2>

        
        <?php        // Aquí tenéis que crear la tabla de solicitantes de ese tipo
        $host = "localhost";
        $username = "root";
        $password = "";
        $dbname = "CAE";

        $conn = mysqli_connect($host, $username, $password, $dbname, 3307);


        if(isset($_POST["inscribir"]))
        {
            $tipo = null; //para que no muestre ninguna tabla al guardar datos nuevos

            $nombre = $_POST["nombre"];
            $apellidos = $_POST["apellidos"];
            $dni = $_POST["dni"];
            $f_nac = $_POST["f_na"];
            $tlf = $_POST["tlf"];
            $email = $_POST["email"];
            $profesion = null;
            if(isset($_POST["soldadura"]))
            {
                $profesion = "Soldadura";
            }
            if(isset($_POST["informatica"]))
            {
                if(empty($profesion)) //estos ifs son para que se añada correctamente al "array" de profesiones, así luego
                {
                    $profesion = "Informática"; //se puede extraer con el explode y saber si pertenece a alguna tabla
                }else
                {
                    $profesion = $profesion . ",Informática"; //estos nombres tienen que ser exactamente los mismos que se comprueban arriba
                }
            }
            if(isset($_POST["asistencia"]))
            {
                if(empty($profesion))
                {
                    $profesion = "Asistencia Sociosanitaria";
                }else
                {
                    $profesion = $profesion . ",Asistencia Sociosanitaria";
                }
            }
            if(isset($_POST["0"]))
            {
                $jornadaParcial = "0"; //si jornadaParcial es 0, la jornada NO es parcial, así que es completa
            }else
            {
                $jornadaParcial = "1"; //si jornadaParcial es 1, la jornada ES parcial
            }
            $idiomas = null;
            if(isset($_POST["euskera"])) //hago lo mismo que con las profesiones para guardarlo como un array
            {
                $idiomas = "euskera";
            }
            if(isset($_POST["ingles"]))
            {
                if(empty($idiomas))
                {
                    $idiomas = "ingles";
                }else
                {
                    $idiomas = $idiomas . ",ingles";
                }
            }

            $sql = "INSERT INTO solicitud(nombre, apellidos, dni, f_nac, tlf, email, profesion, jornadaParcial, idiomas)
                VALUES('$nombre', '$apellidos', '$dni', '$f_nac', '$tlf', '$email', '$profesion', '$jornadaParcial', '$idiomas')";
            $rs = mysqli_query($conn, $sql);

            if(!$rs)
            {
                //echo '<script>alert("Ha habido un error. No se han añadido los datos.")</script>';
                echo "Ha habido un error. No se han añadido los datos. <br/>";
            }else
            {
                //echo '<script>alert("Se han añadido los datos.")</script>';
                echo "Se han añadido los datos. <br/>";
            }
        }else
        {
            //echo '<script>alert("Solicitudes de ' . $tipo . '")</script>';
            $contenido_tabla = mysqli_query($conn, "SELECT * FROM solicitud");

            echo '<table border=1 cellspacing=1 cellpadding=1>';
            /*Los nombres de las columnas*/
            echo '<tr>
                <td>ID</td>
                <td>Nombre</td>
                <td>Apellidos</td>
                <td>DNI</td>
                <td>Fecha de nacimiento</td>
                <td>Teléfono</td>
                <td>E-mail</td>
                <td>Profesión</td>
                <td>¿Jornada parcial?</td>
                <td>Idiomas</td>
            </tr>';

            if($contenido_tabla->num_rows > 0) 
            {
                while($row = $contenido_tabla->fetch_assoc())
                {
                    $profesion = $row["profesion"]; //todas las profesiones se guardan en un array
                    if(!empty($profesion)) //si no hay profesiones, entonces no se muestra en absoluto
                    {
                        $profesionesArray = explode(",", $profesion); //si HAY profesiones, se separan por las comas
                        foreach($profesionesArray as $profesiones) //en principio no se rompe aunque no haya comas
                        {
                            if($profesiones == $tipo) //si alguna de las profesiones es del tipo que se está mirando, se muestra
                            {
                                /*echo "ID: " . $row["id"] . "<br/>";
                                echo "Nombre: ". $row["nombre"] . "<br/>";
                                echo "Apellidos: ". $row["apellidos"] . "<br/>";
                                echo "DNI: ". $row["dni"] . "<br/>";
                                echo "Fecha de nacimiento: ". $row["f_nac"] . "<br/>";
                                echo "Teléfono: ". $row["tlf"] . "<br/>";
                                echo "E-mail: ". $row["email"] . "<br/>";
                                echo "Profesión: ". $row["profesion"] . "<br/>";
                                echo "¿Jornada parcial?: ". $row["jornadaParcial"] . "<br/>";
                                echo "Idiomas: ". $row["idiomas"] . "<br/>";*/
                                /*El contenido de las columnas*/
                                echo '<tr>
                                    <td>'.$row["id"].'</td>
                                    <td>'.$row["nombre"].'</td>
                                    <td>'.$row["apellidos"].'</td>
                                    <td>'.$row["dni"].'</td>
                                    <td>'.$row["f_nac"].'</td>
                                    <td>'.$row["tlf"].'</td>
                                    <td>'.$row["email"].'</td>
                                    <td>'.$row["profesion"].'</td>
                                    <td>'.$row["jornadaParcial"].'</td>
                                    <td>'.$row["idiomas"].'</td>
                                </tr>';
                            }
                        }
                    }
                }
            }
            echo '</table>';
        }
        
        ?>
        <button onclick="location.href='../html/index.html'">Volver al formulario</button>
    </body>
</html>