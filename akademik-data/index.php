<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "akademik";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}

$nis        = "";
$nama       = "";
$alamat     = "";
$jurusan    = "";
$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "delete from siswa where id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "select * from siswa where id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $nis = $r1['nis'];
    $nama = $r1['nama'];
    $alamat = $r1['alamat'];
    $jurusan = $r1['jurusan'];

    if ($nis == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) {
    $id         = $_POST['id']; 
    $nis        = $_POST['nis'];
    $nama       = $_POST['nama'];
    $alamat     = $_POST['alamat'];
    $jurusan    = $_POST['jurusan'];

    if ($nis && $nama && $alamat && $jurusan) {
        if ($op == 'edit') {
            $sql1 = "update siswa set nis = '$nis', nama='$nama', alamat = '$alamat', jurusan='$jurusan' where id = '$id'";
            $q1   = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        } else {
            $sql_check = "SELECT * FROM siswa WHERE nis = '$nis'";
            $q_check = mysqli_query($koneksi, $sql_check);
            if (mysqli_num_rows($q_check) > 0) {
                $error = "NIS sudah terdaftar";
            } else {
                $sql1 = "insert into siswa (nis, nama, alamat, jurusan) values ('$nis', '$nama', '$alamat', '$jurusan')";
                $q1 = mysqli_query($koneksi, $sql1);
                if ($q1) {
                    $sukses = "Berhasil memasukkan data baru";
                } else {
                    $error = "Gagal memasukkan data";
                }
            }
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}

function cari($keyword) {
    global $koneksi; 
    $query = "SELECT * FROM siswa WHERE nama LIKE '%$keyword%' OR nis LIKE '%$keyword%' order by nama ASC";
    return mysqli_query($koneksi, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto { width: 800px }
        .card { margin-top: 10px; }
    </style>
</head>
<body>

<!--buat memasukkam data-->
<div class="mx-auto">
    <div class="card">
        <div class="card-header">Create / Edit Data</div>
        <div class="card-body">
            <?php if ($error) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error ?>
                </div>
                <?php header("refresh:5;url=index.php"); }
            ?>
            <?php if ($sukses) { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $sukses ?>
                </div>
                <?php header("refresh:5;url=index.php"); }
            ?>

            <!--form data-->
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="mb-3 row">
                    <label for="nis" class="col-sm-2 col-form-label">NIS</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nis" name="nis" value="<?php echo $nis ?>" autocomplete="off">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>" autocomplete="off">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>" autocomplete="off">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="jurusan" class="col-sm-2 col-form-label">Jurusan</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="jurusan" id="jurusan">
                            <option value="">- Pilih Jurusan -</option>
                            <option value="rpl" <?php if ($jurusan == "rpl") echo "selected" ?>>rpl</option>
                            <option value="dkv" <?php if ($jurusan == "dkv") echo "selected" ?>>dkv</option>
                            <option value="tkj" <?php if ($jurusan == "tkj") echo "selected" ?>>tkj</option>
                        </select>
                    </div>
                </div>

           
                <div class="col-12">
                    <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                </div>
            </form>
        </div>
    </div>
 <!--buat memunculkan data-->
    <div class="card">
        <div class="card-header text-white bg-secondary">Data Siswa</div>
        <div class="card-body">
            <table class="table">
                <div class="card-body">
                    <!-- search bar-->
                    Cari siswa :
                    <form method="post" action="">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="keyword" placeholder="Cari berdasarkan NIS atau Nama" autocomplete="off">
                            <button class="btn btn-primary" type="submit" name="cari">Cari</button>
                        </div>
                    </form>
                </div>
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Jurusan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_POST["cari"])) {
                        $q2 = cari($_POST["keyword"]);
                    } else {
                        $siswa = "SELECT * from siswa order by nama ASC";
                        $q2 = mysqli_query($koneksi, $siswa);
                    }

                    $urut = 1;
                    while ($r2 = mysqli_fetch_array($q2)) {
                        $id = $r2['id'];
                        $nis = $r2['nis'];
                        $nama = $r2['nama'];
                        $alamat = $r2['alamat'];
                        $jurusan = $r2['jurusan'];
                    ?>
                        <tr>
                            <th scope="row"><?php echo $urut++ ?></th>
                            <td scope="row"><?php echo $nis ?></td>
                            <td scope="row"><?php echo $nama ?></td>
                            <td scope="row"><?php echo $alamat ?></td>
                            <td scope="row"><?php echo $jurusan ?></td>
                            <td scope="row">
                                <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau delete data ?')"><button type="button" class="btn btn-danger">Delete</button></a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
