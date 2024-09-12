<?php
$conn = mysqli_connect('localhost', 'root', '', 'pasar');

if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="dist/output.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/4c39239a64.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <title>Pasarku | Bandingkan Harga Sekarang!</title>
</head>
<body>

<nav class="bg-white border-gray-200 dark:bg-gray-900 dark:border-gray-700">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
        <!-- <img src="img\LogoPasar.png" class="h-8" alt="Pasarku Logo" /> -->
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Pasarku</span>
    </a>
    <button data-collapse-toggle="navbar-dropdown" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-dropdown" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
    </button>
    <div class="hidden w-full md:block md:w-auto" id="navbar-dropdown">
      <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
        <li>
          <a href="index.php" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500 dark:bg-blue-600 md:dark:bg-transparent" aria-current="page">Beranda</a>
        </li>
        <li>
          <a href="#harga" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Harga</a>
        </li>
        <li>
          <a href="#Komentar" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Forum</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Search Box -->
<div class="flex justify-center mt-8 mb-8">
  <input type="text" id="searchQuery" class="w-10/12 md:w-6/12 border-b border-gray-300 focus:outline-none focus:border-indigo-500" placeholder="Cari nama bahan pokok">
</div>

<!-- Data Pasar A -->
<div class="flex justify-center my-2 px-3" id="harga">
  <div class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Harga Rata Rata Pasar Klewer</h5>
    <form action="" method="post">
      <div class="mb-3">
        <p class="text-base text-gray-500 sm:text-lg dark:text-gray-400">Tanggal : <b><?php echo date('d-M-Y'); ?></b></p>
        <p class="mb-3 text-base text-gray-500 sm:text-lg dark:text-gray-400">Tanggal Dipilih: <b><?php if (isset($_POST['selectedDate'])) { echo date('d-M-Y', strtotime($_POST['selectedDate'])); } ?></b></p>
        <input type="date" name="selectedDate" class="border border-gray-300 rounded-md px-2 py-1 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" onchange="this.form.submit()">
      </div>
    </form>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4" id="dataContainer">
    <?php
  $selectedDate = isset($_POST['selectedDate']) ? $_POST['selectedDate'] : date('Y-m-d'); // Default date if not submitted

  $id_pasar = 1; // Assuming you have the current market ID stored in $id_pasar

  $sql = "SELECT * FROM hargabahanpokok WHERE id_pasar = $id_pasar AND tanggal = '$selectedDate' ORDER BY nama_bahan_pokok ASC";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    echo "<p class='text-red-500'>Error: " . mysqli_error($conn) . "</p>";
    exit();
  }

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  mysqli_free_result($result);

  foreach ($data as $row) {
    echo "<div class='dataItem w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700'>";
    echo "<h5 class='mb-2 text-3xl font-bold text-gray-900 dark:text-white'>" . $row['nama_bahan_pokok'] . "</h5>";
    echo "<p class='mb-2 text-base text-gray-500 sm:text-lg dark:text-gray-400'>Rp. " . $row['harga'] . ",- per KG</p>";

    $sql2 = "SELECT
    ROUND(((hb2.harga - hb1.harga) / hb1.harga) * 100, 2) AS Persentase_Kenaikan
    FROM HargaBahanPokok hb1
    INNER JOIN HargaBahanPokok hb2 ON hb1.id_pasar = hb2.id_pasar
      AND hb1.nama_bahan_pokok = hb2.nama_bahan_pokok
      AND hb1.id_pasar = $id_pasar
    WHERE hb1.tanggal = '$selectedDate'
      AND hb2.tanggal = CURRENT_DATE
      AND hb1.nama_bahan_pokok = '" . $row['nama_bahan_pokok'] . "'";
    
    $result2 = mysqli_query($conn, $sql2);

    if (!$result2) {
      echo "<p class='text-red-500'>Error: " . mysqli_error($conn) . "</p>";
      exit();
    }

    if (mysqli_num_rows($result2) > 0) {
      $row2 = mysqli_fetch_assoc($result2);
      $persentaseKenaikan = $row2['Persentase_Kenaikan'];

      if ($persentaseKenaikan > 0) {
        echo "<p class='text-base text-red-500 sm:text-lg dark:text-red-400'>Kenaikan Harga: <b>" . $persentaseKenaikan . "%</b> <i class='fas fa-arrow-up'></i></p>";
      } else if ($persentaseKenaikan < 0) {
        echo "<p class='text-base text-green-500 sm:text-lg dark:text-green-400'>Penurunan Harga: <b>" . abs($persentaseKenaikan) . "%</b> <i class='fas fa-arrow-down'></i></p>";
      } else {
        echo "<p class='text-base text-gray-500 sm:text-lg dark:text-gray-400'>Harga Tidak Berubah</p>";
      }
    } else {
      echo "<p class='text-base text-gray-500 sm:text-lg dark:text-gray-400'>Data tidak ditemukan.</p>";
    }

    mysqli_free_result($result2);
    echo "</div>";
  }
  ?>
    </div>
  </div>
</div>

<!-- Data Pasar B -->
<div class="flex justify-center my-2 px-3" id="harga">
  <div class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Harga Rata Rata Pasar Legi</h5>
    <form action="" method="post">
      <div class="mb-3">
        <p class="text-base text-gray-500 sm:text-lg dark:text-gray-400">Tanggal : <b><?php echo date('d-M-Y'); ?></b></p>
        <p class="mb-3 text-base text-gray-500 sm:text-lg dark:text-gray-400">Tanggal Dipilih: <b><?php if (isset($_POST['selectedDate'])) { echo date('d-M-Y', strtotime($_POST['selectedDate'])); } ?></b></p>
        <input type="date" name="selectedDate" class="border border-gray-300 rounded-md px-2 py-1 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" onchange="this.form.submit()">
      </div>
    </form>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4" id="dataContainer">
    <?php
  $selectedDate = isset($_POST['selectedDate']) ? $_POST['selectedDate'] : date('Y-m-d'); // Default date if not submitted

  $id_pasar = 2; // Assuming you have the current market ID stored in $id_pasar

  $sql = "SELECT * FROM hargabahanpokok WHERE id_pasar = $id_pasar AND tanggal = '$selectedDate' ORDER BY nama_bahan_pokok ASC";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    echo "<p class='text-red-500'>Error: " . mysqli_error($conn) . "</p>";
    exit();
  }

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  mysqli_free_result($result);

  foreach ($data as $row) {
    echo "<div class='dataItem w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700'>";
    echo "<h5 class='mb-2 text-3xl font-bold text-gray-900 dark:text-white'>" . $row['nama_bahan_pokok'] . "</h5>";
    echo "<p class='mb-2 text-base text-gray-500 sm:text-lg dark:text-gray-400'>Rp. " . $row['harga'] . ",- per KG</p>";

    $sql2 = "SELECT
    ROUND(((hb2.harga - hb1.harga) / hb1.harga) * 100, 2) AS Persentase_Kenaikan
    FROM HargaBahanPokok hb1
    INNER JOIN HargaBahanPokok hb2 ON hb1.id_pasar = hb2.id_pasar
      AND hb1.nama_bahan_pokok = hb2.nama_bahan_pokok
      AND hb1.id_pasar = $id_pasar
    WHERE hb1.tanggal = '$selectedDate'
      AND hb2.tanggal = CURRENT_DATE
      AND hb1.nama_bahan_pokok = '" . $row['nama_bahan_pokok'] . "'";
    
    $result2 = mysqli_query($conn, $sql2);

    if (!$result2) {
      echo "<p class='text-red-500'>Error: " . mysqli_error($conn) . "</p>";
      exit();
    }

    if (mysqli_num_rows($result2) > 0) {
      $row2 = mysqli_fetch_assoc($result2);
      $persentaseKenaikan = $row2['Persentase_Kenaikan'];

      if ($persentaseKenaikan > 0) {
        echo "<p class='text-base text-red-500 sm:text-lg dark:text-red-400'>Kenaikan Harga: <b>" . $persentaseKenaikan . "%</b> <i class='fas fa-arrow-up'></i></p>";
      } else if ($persentaseKenaikan < 0) {
        echo "<p class='text-base text-green-500 sm:text-lg dark:text-green-400'>Penurunan Harga: <b>" . abs($persentaseKenaikan) . "%</b> <i class='fas fa-arrow-down'></i></p>";
      } else {
        echo "<p class='text-base text-gray-500 sm:text-lg dark:text-gray-400'>Harga Tidak Berubah</p>";
      }
    } else {
      echo "<p class='text-base text-gray-500 sm:text-lg dark:text-gray-400'>Data tidak ditemukan.</p>";
    }

    mysqli_free_result($result2);
    echo "</div>";
  }
  ?>
    </div>
  </div>
</div>

<!-- Data Pasar C -->
<div class="flex justify-center my-2 px-3" id="harga">
  <div class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Harga Rata Rata Pasar Gading</h5>
    <form action="" method="post">
      <div class="mb-3">
        <p class="text-base text-gray-500 sm:text-lg dark:text-gray-400">Tanggal : <b><?php echo date('d-M-Y'); ?></b></p>
        <p class="mb-3 text-base text-gray-500 sm:text-lg dark:text-gray-400">Tanggal Dipilih: <b><?php if (isset($_POST['selectedDate'])) { echo date('d-M-Y', strtotime($_POST['selectedDate'])); } ?></b></p>
        <input type="date" name="selectedDate" class="border border-gray-300 rounded-md px-2 py-1 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" onchange="this.form.submit()">
      </div>
    </form>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4" id="dataContainer">
    <?php
  $selectedDate = isset($_POST['selectedDate']) ? $_POST['selectedDate'] : date('Y-m-d'); // Default date if not submitted

  $id_pasar = 3; // Assuming you have the current market ID stored in $id_pasar

  $sql = "SELECT * FROM hargabahanpokok WHERE id_pasar = $id_pasar AND tanggal = '$selectedDate' ORDER BY nama_bahan_pokok ASC";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    echo "<p class='text-red-500'>Error: " . mysqli_error($conn) . "</p>";
    exit();
  }

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  mysqli_free_result($result);

  foreach ($data as $row) {
    echo "<div class='dataItem w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700'>";
    echo "<h5 class='mb-2 text-3xl font-bold text-gray-900 dark:text-white'>" . $row['nama_bahan_pokok'] . "</h5>";
    echo "<p class='mb-2 text-base text-gray-500 sm:text-lg dark:text-gray-400'>Rp. " . $row['harga'] . ",- per KG</p>";

    $sql2 = "SELECT
    ROUND(((hb2.harga - hb1.harga) / hb1.harga) * 100, 2) AS Persentase_Kenaikan
    FROM HargaBahanPokok hb1
    INNER JOIN HargaBahanPokok hb2 ON hb1.id_pasar = hb2.id_pasar
      AND hb1.nama_bahan_pokok = hb2.nama_bahan_pokok
      AND hb1.id_pasar = $id_pasar
    WHERE hb1.tanggal = '$selectedDate'
      AND hb2.tanggal = CURRENT_DATE
      AND hb1.nama_bahan_pokok = '" . $row['nama_bahan_pokok'] . "'";
    
    $result2 = mysqli_query($conn, $sql2);

    if (!$result2) {
      echo "<p class='text-red-500'>Error: " . mysqli_error($conn) . "</p>";
      exit();
    }

    if (mysqli_num_rows($result2) > 0) {
      $row2 = mysqli_fetch_assoc($result2);
      $persentaseKenaikan = $row2['Persentase_Kenaikan'];

      if ($persentaseKenaikan > 0) {
        echo "<p class='text-base text-red-500 sm:text-lg dark:text-red-400'>Kenaikan Harga: <b>" . $persentaseKenaikan . "%</b> <i class='fas fa-arrow-up'></i></p>";
      } else if ($persentaseKenaikan < 0) {
        echo "<p class='text-base text-green-500 sm:text-lg dark:text-green-400'>Penurunan Harga: <b>" . abs($persentaseKenaikan) . "%</b> <i class='fas fa-arrow-down'></i></p>";
      } else {
        echo "<p class='text-base text-gray-500 sm:text-lg dark:text-gray-400'>Harga Tidak Berubah</p>";
      }
    } else {
      echo "<p class='text-base text-gray-500 sm:text-lg dark:text-gray-400'>Data tidak ditemukan.</p>";
    }

    mysqli_free_result($result2);
    echo "</div>";
  }
  ?>
    </div>
  </div>
</div>

<!-- Data Pasar Nasional -->
<div class="flex justify-center my-2 px-3" id="harga">
  <div class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Harga Rata Rata Pasar Nasional</h5>
    <form action="" method="post">
      <div class="mb-3">
        <p class="text-base text-gray-500 sm:text-lg dark:text-gray-400">Tanggal : <b><?php echo date('d-M-Y'); ?></b></p>
        <p class="mb-3 text-base text-gray-500 sm:text-lg dark:text-gray-400">Tanggal Dipilih: <b><?php if (isset($_POST['selectedDate'])) { echo date('d-M-Y', strtotime($_POST['selectedDate'])); } ?></b></p>
        <input type="date" name="selectedDate" class="border border-gray-300 rounded-md px-2 py-1 shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" onchange="this.form.submit()">
      </div>
    </form>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4" id="dataContainer">
    <?php
  $selectedDate = isset($_POST['selectedDate']) ? $_POST['selectedDate'] : date('Y-m-d'); // Default date if not submitted

  $id_pasar = 4; // Assuming you have the current market ID stored in $id_pasar

  $sql = "SELECT * FROM hargabahanpokok WHERE id_pasar = $id_pasar AND tanggal = '$selectedDate' ORDER BY nama_bahan_pokok ASC";
  $result = mysqli_query($conn, $sql);

  if (!$result) {
    echo "<p class='text-red-500'>Error: " . mysqli_error($conn) . "</p>";
    exit();
  }

  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }

  mysqli_free_result($result);

  foreach ($data as $row) {
    echo "<div class='dataItem w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700'>";
    echo "<h5 class='mb-2 text-3xl font-bold text-gray-900 dark:text-white'>" . $row['nama_bahan_pokok'] . "</h5>";
    echo "<p class='mb-2 text-base text-gray-500 sm:text-lg dark:text-gray-400'>Rp. " . $row['harga'] . ",- per KG</p>";

    $sql2 = "SELECT
    ROUND(((hb2.harga - hb1.harga) / hb1.harga) * 100, 2) AS Persentase_Kenaikan
    FROM HargaBahanPokok hb1
    INNER JOIN HargaBahanPokok hb2 ON hb1.id_pasar = hb2.id_pasar
      AND hb1.nama_bahan_pokok = hb2.nama_bahan_pokok
      AND hb1.id_pasar = $id_pasar
    WHERE hb1.tanggal = '$selectedDate'
      AND hb2.tanggal = CURRENT_DATE
      AND hb1.nama_bahan_pokok = '" . $row['nama_bahan_pokok'] . "'";
    
    $result2 = mysqli_query($conn, $sql2);

    if (!$result2) {
      echo "<p class='text-red-500'>Error: " . mysqli_error($conn) . "</p>";
      exit();
    }

    if (mysqli_num_rows($result2) > 0) {
      $row2 = mysqli_fetch_assoc($result2);
      $persentaseKenaikan = $row2['Persentase_Kenaikan'];

      if ($persentaseKenaikan > 0) {
        echo "<p class='text-base text-red-500 sm:text-lg dark:text-red-400'>Kenaikan Harga: <b>" . $persentaseKenaikan . "%</b> <i class='fas fa-arrow-up'></i></p>";
      } else if ($persentaseKenaikan < 0) {
        echo "<p class='text-base text-green-500 sm:text-lg dark:text-green-400'>Penurunan Harga: <b>" . abs($persentaseKenaikan) . "%</b> <i class='fas fa-arrow-down'></i></p>";
      } else {
        echo "<p class='text-base text-gray-500 sm:text-lg dark:text-gray-400'>Harga Tidak Berubah</p>";
      }
    } else {
      echo "<p class='text-base text-gray-500 sm:text-lg dark:text-gray-400'>Data tidak ditemukan.</p>";
    }

    mysqli_free_result($result2);
    echo "</div>";
  }
  ?>
    </div>
  </div>
</div>

<!-- Komentar -->

<div class="my-2 px-3" id="Komentar">
  <div class="w-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Komentar</h5>
    <button id="openModal" class="bg-gray-900 text-white px-4 py-2 rounded-md shadow-md hover:bg-gray-700 focus:outline-none mb-5 w-full sm:w-1/4">Berikan Komentar</button>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <?php
      // Query untuk mengambil data komentar
      $sql = "SELECT nama, isi, waktu_dibuat FROM komentar ORDER BY waktu_dibuat DESC LIMIT 10";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
          // Output data dari setiap baris
          while ($row = mysqli_fetch_assoc($result)) {
              echo '<div class="mb-4 border p-4 rounded-lg shadow-md">';
              echo '<h6 class="text-lg font-bold text-gray-700 dark:text-white"> <i class="fa-solid fa-user-tie"></i> | ' . htmlspecialchars($row['nama']) . '</h6>';
              echo '<p class="text-sm text-gray-900 dark:text-gray-200">' . htmlspecialchars($row['isi']) . '</p>';
              echo '<span class="text-xs text-gray-500 dark:text-gray-400">' . htmlspecialchars($row['waktu_dibuat']) . '</span>';
              echo '</div>';
          }
      } else {
          echo '<p class="text-gray-700 dark:text-white">Belum ada komentar.</p>';
      }

      // Tutup koneksi
      mysqli_close($conn);
      ?>
    </div>

  </div>
</div>


<div id="modal" class="fixed inset-0 z-10 hidden overflow-y-auto bg-gray-900 bg-opacity-50 flex justify-center items-center">
  <div class="relative bg-white w-full max-w-sm p-8 rounded-md shadow-lg">
    <button id="closeModal" class="absolute top-0 right-0 p-2 m-2 rounded-md hover:bg-gray-200 focus:outline-none">&times;</button>

    <!-- Form komentar bisa dimasukkan di sini -->
    <h5 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Komentar</h5>
    <form action="proses_komentar.php" method="POST" class="mt-4 mb-5">
  <div class="mb-4">
    <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-white">Nama</label>
    <input type="text" id="nama" name="nama" required placeholder="Masukkan nama Anda" class="w-full px-3 py-2 mt-1 text-sm text-gray-900 dark:text-gray-200 placeholder-gray-500 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:focus:ring-gray-700 dark:focus:border-gray-700">
  </div>
  
  <div class="mb-4">
    <label for="komentar" class="block text-sm font-medium text-gray-700 dark:text-white">Komentar</label>
    <textarea id="komentar" name="komentar" required rows="3" placeholder="Tulis komentar Anda..." class="w-full px-3 py-2 mt-1 text-sm text-gray-900 dark:text-gray-200 placeholder-gray-500 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-700 dark:focus:ring-gray-700 dark:focus:border-gray-700"></textarea>
  </div>
  
  <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-800 dark:focus:ring-gray-700">
    Kirim Komentar
  </button>
</form>
  </div>
</div>

<script>
  // Ambil elemen-elemen yang dibutuhkan
  const openModalButton = document.getElementById('openModal');
  const closeModalButton = document.getElementById('closeModal');
  const modal = document.getElementById('modal');

  // Tambahkan event listener untuk tombol buka modal
  openModalButton.addEventListener('click', function() {
    modal.classList.remove('hidden');
  });

  // Tambahkan event listener untuk tombol tutup modal
  closeModalButton.addEventListener('click', function() {
    modal.classList.add('hidden');
  });
</script>

<script>
  const searchQuery = document.getElementById('searchQuery');
  const dataContainer = document.getElementById('dataContainer');
  const dataItems = document.querySelectorAll('.dataItem');

  searchQuery.addEventListener('input', function() {
    const filter = searchQuery.value.toLowerCase();
    dataItems.forEach(function(item) {
      const itemName = item.querySelector('h5').textContent.toLowerCase();
      if (itemName.includes(filter)) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  });
</script>

<script>
  const button = document.querySelector('[data-collapse-toggle="navbar-dropdown"]');
  const menu = document.getElementById('navbar-dropdown');

  button.addEventListener('click', () => {
    menu.classList.toggle('hidden');
  });
</script>
<script src="https://kit.fontawesome.com/4c39239a64.js" crossorigin="anonymous"></script>

</body>
</html>