<?php
$conn = mysqli_connect('localhost', 'root', '', 'pasar');

if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id_pasar = $_POST['id_pasar'];
  $nama_bahan_pokok = $_POST['nama_bahan_pokok'];
  $tanggal = $_POST['tanggal'];
  $harga = $_POST['harga'];

  // Cek apakah data dengan nama bahan pokok dan tanggal yang sama sudah ada
  $check_sql = "SELECT * FROM hargabahanpokok WHERE id_pasar='$id_pasar' AND nama_bahan_pokok='$nama_bahan_pokok' AND tanggal='$tanggal'";
  $result = $conn->query($check_sql);

  if ($result->num_rows > 0) {
    $message = "Data untuk nama bahan pokok ini sudah ada pada tanggal yang sama.";
    $message_type = "error";
  } else {
    $sql = "INSERT INTO hargabahanpokok (id_pasar, nama_bahan_pokok, tanggal, harga) VALUES ('$id_pasar', '$nama_bahan_pokok', '$tanggal', '$harga')";
    if ($conn->query($sql) === TRUE) {
      $message = "Data berhasil ditambahkan";
      $message_type = "success";
    } else {
      $message = "Error: " . $sql . "<br>" . $conn->error;
      $message_type = "error";
    }
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Input Data Harga Bahan Pokok</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/4c39239a64.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6">Input Data Harga Bahan Pokok</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <div class="mb-4">
        <label for="id_pasar" class="block text-gray-700">ID Pasar:</label>
        <select id="id_pasar" name="id_pasar" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
          <option value="1">1 - Pasar Klewer</option>
          <option value="2">2 - Pasar Legi</option>
          <option value="3">3 - Pasar Gading</option>
          <option value="4">4 - Pasar Nasional</option>
        </select>
      </div>
      <div class="mb-4">
        <label for="nama_bahan_pokok" class="block text-gray-700">Nama Bahan Pokok:</label>
        <select id="nama_bahan_pokok" name="nama_bahan_pokok" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
          <?php
            $bahan_pokok = [
              "Ayam Broiler",
              "Bawang Merah",
              "Bawang Putih",
              "Beras",
              "Cabai Merah Keriting",
              "Cabai Rawit",
              "Daging Sapi",
              "Kentang",
              "Minyak Goreng",
              "Telur",
              "Tomat",
              "Wortel"
            ];

            sort($bahan_pokok);

            foreach ($bahan_pokok as $bahan) {
              echo "<option value=\"$bahan\">$bahan</option>";
            }
          ?>
        </select>
      </div>
      <div class="mb-4">
        <label for="tanggal" class="block text-gray-700">Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
      </div>
      <div class="mb-4">
        <label for="harga" class="block text-gray-700">Harga:</label>
        <input type="number" id="harga" name="harga" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
      </div>
      <div class="text-right">
        <a href="index.php" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">Cancel</a>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Submit</button>
      </div>
    </form>
  </div>

  <!-- Modal Box -->
  <?php if (!empty($message)) : ?>
    <div class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
      <div class="bg-white p-8 rounded-lg shadow-lg max-w-md mx-auto">
        <?php if ($message_type == 'success') : ?>
          <i class="fas fa-solid fa-circle-check text-4xl text-green-500 mb-4"></i>
          <h2 class="text-2xl font-bold mb-4">Success</h2>
        <?php elseif ($message_type == 'error') : ?>
          <i class="fas fa-solid fa-circle-exclamation text-4xl text-red-500 mb-4"></i>
          <h2 class="text-2xl font-bold mb-4">Error</h2>
        <?php endif; ?>
        <p><?php echo $message; ?></p>
        <button onclick="closeModal()" class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4">Close</button>
      </div>
    </div>
  <?php endif; ?>

  <script>
    function closeModal() {
      document.querySelector('.fixed.inset-0').style.display = 'none';
    }
  </script>
</body>
</html>
