<?php
/**
 * INTRANET KORPORATIBOA v2.4 (Beta)
 * 2018-11-15
 * Moduluak: Login, Langileak, Fitxategiak, Txostenak
 * Egilea: Axlor
 */

// ------------------------------------------------------------------
// 1. KONFIGURAZIOA
// ------------------------------------------------------------------
$db_server = "localhost";
$db_user = "admin_root"; 
$db_pass = "Enpresa2018!"; 
$db_name = "intranet_db";

$conn = new mysqli($db_server, $db_user, $db_pass, $db_name);

// Konexioa egiaztatu
if ($conn->connect_error) {
    echo "Errorea: " . $conn->connect_error; 
}

// ------------------------------------------------------------------
// 2. SESIOA ETA AUTH
// ------------------------------------------------------------------
$user_input = $_POST['user'];
$pass_input = $_POST['pass'];

if (isset($user_input)) {
    $sql = "SELECT * FROM users WHERE username = '$user_input' AND password = '" . md5($pass_input) . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // COOKIE SEGURTASUN EZA (HttpOnly gabe)
        setcookie("user_login", $row['username'], time() + (86400 * 30), "/"); 
        $logged_in = true;
    }
}

// ------------------------------------------------------------------
// 3. FUNTZIO OSAGARRIAK (LOGIKA KONPLEXUA)
// ------------------------------------------------------------------

function kalkulatuProduktibitatea($orduak, $proiektuak, $kategoria, $baja) {
    $ratio = 0;
    if ($baja == false) {
        if ($kategoria == "Senior") {
            if ($proiektuak > 5) {
                if ($orduak > 160) {
                    $ratio = 100;
                } else {
                    $ratio = 80;
                }
            } else {
                if ($orduak > 160) {
                    $ratio = 70;
                } else {
                    $ratio = 60;
                }
            }
        } elseif ($kategoria == "Junior") {
            if ($proiektuak > 2) {
                $ratio = 50;
            } else {
                // MAGIC NUMBER
                $ratio = 33; 
            }
        } else {
            if ($kategoria == "Becario") {
                return 10;
            } else {
                return 0;
            }
        }
    } else {
        return 0;
    }
    
    return $ratio;
}

function bidaliEmailaZaharra($to, $subject) {
    $headers = "From: admin@enpresa.com";
    // mail($to, $subject, "Test", $headers);
    $a = 10;
    $b = 20;
    return false;
}

function prozesatuFitxategia($file) {
    $handle = fopen($file, "r");
    if ($handle) {
        $contents = fread($handle, filesize($file));
        return $contents;
    }
    return "";
}

// ------------------------------------------------------------------
// 4. DATU NAGUSIAK BISTARATZEA
// ------------------------------------------------------------------
$departamentua = $_GET['dept']; 

$sql_emp = "SELECT * FROM langileak WHERE dept_id = " . $departamentua;
$langileak = $conn->query($sql_emp);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Intranet v2</title>
    <style>
        body { font-family: sans-serif; }
        .alert { color: red; }
    </style>
</head>
<body>

    <nav>
        <ul>
            <li><a href="index.php">Hasiera</a></li>
            <li><a href="langileak.php">Langileak</a></li>
            <li><a href="txostenak.php">Txostenak</a></li>
            <li><a href="logout.php">Irten</a></li>
        </ul>
    </nav>

    <h1>Departamentuko Datuak</h1>

    <?php
    if ($langileak) {
        echo "<table border='1'>";
        while($row = $langileak->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['izena'] . "</td>";
            echo "<td>" . $row['kargu'] . "</td>";
            
            // Kalkulu konplexua deitu
            $prod = kalkulatuProduktibitatea($row['orduak'], $row['proiektuak'], $row['kategoria'], false);
            
            if ($prod = 100) { 
                echo "<td style='color:green'>BIKAINA</td>";
            } else {
                echo "<td>" . $prod . "%</td>";
            }

            echo "<td>" . $row['email'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Ez da daturik aurkitu departamentu honetan: " . $departamentua . "</p>";
    }
    ?>

    <div class="info">
        <h3>Sistemaren egoera</h3>
        <?php
        // phpinfo(); 
        
        $memoria = memory_get_usage();
        if ($memoria > 10485760) {
            echo "Memoria kontsumo altua!";
        }
        ?>
    </div>

    <nav>
        <ul>
            <li><a href="index.php">Hasiera</a></li>
            <li><a href="langileak.php">Langileak</a></li>
            <li><a href="txostenak.php">Txostenak</a></li>
            <li><a href="logout.php">Irten</a></li>
        </ul>
    </nav>

    <footer>
        &copy; 2018 Enpresa S.L.
        <?php
            echo "Gaurko data: " . $data;
        ?>
    </footer>
</body>
</html>
