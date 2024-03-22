<?php

// Kredensial database
$dbname = 'example'; // Ganti dengan nama database Anda
$dbuser = 'root';    // User default XAMPP
$dbpass = '';        // Password default XAMPP (biasanya kosong)
$dbhost = 'localhost'; // Host default XAMPP

// Membuat koneksi ke database
$connect = @mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Periksa koneksi
if(!$connect) {
    echo "Error: " . mysqli_connect_error();
    exit();
}

echo "Connection Success!<br><br>";

// Mengambil data suhu dari query URL
$temperature = $_GET["temperature"];

// Memastikan bahwa kita menerima data suhu
if(isset($temperature)) {
    // Query untuk memasukkan data ke dalam tabel
    // Pastikan Anda sudah memiliki tabel `iot_project` dengan kolom `temperature`
    $query = "INSERT INTO iot_project (temperature) VALUES ('$temperature')";
    
    // Melakukan query ke database
    $result = mysqli_query($connect, $query);

    if($result) {
        echo "Insertion Success!<br>";
    } else {
        echo "Insertion Failed: " . mysqli_error($connect) . "<br>";
    }
} else {
    echo "Temperature data is missing";
}

// Menutup koneksi
mysqli_close($connect);

?>
