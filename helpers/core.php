<?php
// buat session
session_start();
// membuat variabel yag berisi koneksi
$conn = mysqli_connect('localhost', 'root', 'password', 'ukk2023') or die('Error connection: ' . mysqli_connect_error());

// query builder
function get($table)
{
    global $conn;

    $sql = "select * from $table";
    $result = mysqli_query($conn, $sql) or die("Query Get Gagal: " . mysqli_error($conn));

    $datas = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $datas;
}

function find($table, array $params, $compare = '=')
{
    global $conn;

    $sql = "select * from $table where $params[0]$compare'$params[1]'";
    $result = mysqli_query($conn, $sql) or die("Query Gagal: " . mysqli_error($conn));

    $datas = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $datas;
}

function findFirst($table, array $params, $compare = '=')
{
    global $conn;

    $sql = "select * from $table where $params[0]$compare'$params[1]'";
    $result = mysqli_query($conn, $sql) or die("Query Gagal: " . mysqli_error($conn));

    $datas = mysqli_fetch_assoc($result);
    return $datas;
}

function create($table, $data)
{
    global $conn;

    $fields = [];
    $datas = [];
    foreach ($data as $key => $value) {
        if ($key != 'submit') {
            $fields[] = $key;
            $datas[] = "'" . htmlspecialchars($value) . "'";
        }
    }

    $fieldsInsert = implode(',', $fields);
    $datasInsert = implode(',', $datas);
    $sql = "insert into $table($fieldsInsert) values($datasInsert)";

    mysqli_query($conn, $sql) or die("Query Gagal: " . mysqli_error($conn));

    return mysqli_affected_rows($conn);
}

function update($table, $data)
{
    global $conn;

    $datas = [];
    $id = $data['id'];
    foreach ($data as $key => $value) {
        if ($key != 'submit') {
            $datas[] = $key . "='" . htmlspecialchars($value) . "'";
        }
    }

    $datasInsert = implode(',', $datas);
    $sql = "update $table set $datasInsert where id=$id";

    mysqli_query($conn, $sql) or die("Query Update Gagal: " . mysqli_error($conn));

    return mysqli_affected_rows($conn);
}

function delete($table, $id)
{
    global $conn;

    $sql = "delete from $table where id='$id'";
    mysqli_query($conn, $sql) or die("Query Gagal: " . mysqli_error($conn));

    return mysqli_affected_rows($conn);
}

function upload($field, $extensi, $max_size = 1)
{
    $nama = $_FILES[$field]['name'];
    $type = $_FILES[$field]['type'];
    $tmp_name = $_FILES[$field]['tmp_name'];
    $error = $_FILES[$field]['error'];
    $size = $_FILES[$field]['size'];

    // cek gambar !diupload
    if ($error == 4) {
        alert("Pilih gambar terlebih dahulu!");
        return false;
    }

    // validasi extensi
    $extensiValid = $extensi;
    $extensiFile = explode('/', $type);
    $extensiFile = strtolower(end($extensiFile));
    if (!in_array($extensiFile, $extensiValid)) {
        alert("Extensi file tidak didukung!");
        return false;
    }

    // validasi ukuran
    if ($size > $max_size * 1000000) {
        alert("Ukuran file terlalu besar!");
        return false;
    }

    // valid file
    $nama = uniqid() . '.' . $extensiFile;
    if (move_uploaded_file($tmp_name, '../../assets/img/' . $nama)) {
        return $nama;
    } else {
        alert('File gagal di uploads!');
        return false;
    }
}

// custom function

function alert($message)
{
    echo "<script>alert('$message');</script>";
}

function register($data)
{
    // make variable
    $data['username'] = strtolower(stripslashes($data['username']));
    $data['password'] = htmlspecialchars($data['password']);
    $data['password2'] = htmlspecialchars($data['password2']);

    // cek konfirmasi password
    if ($data['password'] != $data['password2']) {
        alert('Password tidak match!');
        return false;
    }

    // cek unique username
    $user = findFirst('users', ['username', $data['username']]);
    if ($user) {
        alert('Username sudah ada!');
        return false;
    }

    // cek unique nik
    $user = findFirst('users', ['nik', $data['nik']]);
    if ($user) {
        alert('Nik sudah ada!');
        return false;
    }

    // cek unique no hp
    $user = findFirst('users', ['telp', $data['telp']]);
    if ($user) {
        alert('No Handphone sudah ada!');
        return false;
    }

    // jika data valid
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    $data['level'] = 'masyarakat';
    unset($data['password2']);

    return create('users', $data);
}

function login($data)
{
    // cek username
    $user = findFirst('users', ['username', $data['username']]);
    if ($user) {
        if (password_verify($data['password'], $user['password'])) {
            // jika role masyarakat
            if ($user['level'] == 'masyarakat') {
                $_SESSION['user'] = $user;
                $_SESSION['auth'] = true;
                header("location: masyarakat");
            } else {
                $_SESSION['user'] = $user;
                $_SESSION['auth'] = true;
                header("location: petugas");
            }
        }
    }

    return false;
}

function logout()
{
    session_destroy();
    header('location: login');
}

function auth()
{
    if ($_SESSION['auth'] == true) {
        return true;
    }

    return false;
}
