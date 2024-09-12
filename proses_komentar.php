<?php
$conn = mysqli_connect('localhost', 'root', '', 'pasar');

if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $komentar = $_POST['komentar'];
    
    // Lakukan validasi data jika diperlukan
    
    // Lakukan sanitasi data sebelum memasukkan ke database
    $nama = htmlspecialchars($nama);
    $komentar = htmlspecialchars($komentar);
    
    // Buat koneksi ke database (contoh menggunakan PDO)
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=pasar', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Query untuk menyimpan komentar ke dalam tabel 'komentar'
        $query = "INSERT INTO komentar (nama, isi) VALUES (:nama, :isi)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nama', $nama, PDO::PARAM_STR);
        $stmt->bindParam(':isi', $komentar, PDO::PARAM_STR);
        $stmt->execute();
        
        // Redirect kembali ke halaman awal atau tampilkan pesan sukses
        header('Location: index.php?status=success');
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
