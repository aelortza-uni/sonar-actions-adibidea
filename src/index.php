
<?php
/**
 * Adibide sinplea SonarQube bidez analizatzeko.
 * Kode honek nahita egindako "akats" txikiak ditu.
 */

$izena = "Ikaslea";

// 1. Arazoa: Baldintza hau beti da egia (Redundant code)
if ($izena == "Ikaslea") {
    echo "Kaixo, " . $izena . "!";
}

// 2. Arazoa: Aldagai hau definitu da baina ez da inoiz erabiltzen (Unused variable)
$zenbakiSekretua = 12345;

function gehiketa($a, $b) {
    return $a + $b;
}

// Funtzioa deitzen dugu, baina ez dugu emaitza erabiltzen
gehiketa(5, 10);

?>

