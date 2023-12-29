<?php
include_once 'helpers/core.php';
$pengaduans = get('pengaduan');

if (!auth()) {
    header("location: login");
} else {
    if ($_SESSION['user']['level'] != 'admin') {
        if ($_SESSION['user']['level'] == 'masyarakat') {
            header("location: masyarakat");
        } else {
            header("location: petugas");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
</head>

<body onload="window.print();">
    <center>
        <table border="1" cellspacing="0">
            <tr>
                <th>
                    #
                </th>
                <th>
                    Nama
                </th>
                <th>
                    Tanggal
                </th>
            </tr>
            <?php foreach ($pengaduans as $key => $pengaduan) : ?>
                <tr>
                    <td>
                        <?= $key + 1 ?>
                    </td>
                    <td>
                        <?php foreach ($users = find('users', ['id', $pengaduan['user_id']]) as $user) : ?>
                            <?= $user['nama'] ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?= $pengaduan['tgl'] ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </center>
</body>

</html>
