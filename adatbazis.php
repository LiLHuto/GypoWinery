<?php
    // Adatbázis csatlakozás
    $db = mysqli_connect('localhost', 'majben686', '12345', 'gypowinery');
    
    // Ellenőrizzük a csatlakozást
    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
<html>
    <body>
          <?php
                // Lekérdezés a 'felhasznalo' táblából
                $query = "SELECT * FROM `login`;";
                $result = mysqli_query($db, $query);
                
                // Eredmények kiíratása
                while($row = mysqli_fetch_assoc($result)) {
                    // Az oszlopok megjelenítése
                    echo "ID: " . $row['id'] . "<br>";
                    echo "Vezetéknév: " . $row['vezeteknev'] . "<br>";
                    echo "Keresztnév: " . $row['keresztnev'] . "<br>";
                    echo "Email: " . $row['email'] . "<br>";
                    echo "Telefonszám: " . $row['telefonszam'] . "<br>";
                    echo "Jelszó: " . $row['jelszo'] . "<br><br>";
                }
          ?>
    </body>
</html>
