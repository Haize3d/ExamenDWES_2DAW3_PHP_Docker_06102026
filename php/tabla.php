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
                $tipo = "Soldadura";
                if ($_REQUEST['tipo'] == 'informatica'){
                    $tipo = 'Informática';
                } elseif ($_REQUEST['tipo'] == 'socio'){
                    $tipo = "Asistencia Sociosanitaria";
                }
                echo "Solicitudes de $tipo";
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
            $tipo = null;

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
                if(empty($profesion))
                {
                    $profesion = "Informática";
                }else
                {
                    $profesion = $profesion . ",Informática";
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
                $jornadaParcial = $_POST["0"];
            }else
            {
                $jornadaParcial = $_POST["1"];
            }
            $idiomas = null;
            if(isset($_POST["euskera"]))
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
                echo '<script>alert("Ha habido un error. No se han añadido los datos.")</script>';
            }else
            {
                echo '<script>alert("Se han añadido los datos.")</script>';
            }
        }else
        {
            echo '<script>alert("Solicitudes de ' . $tipo . '")</script>';
            $contenido_tabla = mysqli_query($conn, "SELECT * FROM solicitud");

            if($contenido_tabla->num_rows > 0) 
            {
                while($row = $contenido_tabla->fetch_assoc())
                {
                    $profesion = $row["profesion"];
                    if(!empty($profesion))
                    {
                        $profesionesArray = explode(",", $profesion);
                        foreach($profesionesArray as $profesiones)
                        {
                            if($profesiones == $tipo)
                            {
                                echo "ID: " . $row["id"] . "<br/>";
                                echo "Nombre: ". $row["nombre"] . "<br/>";
                                echo "Apellidos: ". $row["apellidos"] . "<br/>";
                                echo "DNI: ". $row["dni"] . "<br/>";
                                echo "Fencha de nacimiento: ". $row["f_nac"] . "<br/>";
                                echo "Teléfono: ". $row["tlf"] . "<br/>";
                                echo "E-mail: ". $row["email"] . "<br/>";
                                echo "Profesión: ". $row["profesion"] . "<br/>";
                                echo "¿Jornada parcial?: ". $row["jornadaParcial"] . "<br/>";
                                echo "Idiomas: ". $row["idiomas"] . "<br/>";
                            }
                        }
                    }
                }
            }
        }
        
        ?>
        <button onclick="location.href='../html/index.html'">Volver al formulario</button>
    </body>
</html>