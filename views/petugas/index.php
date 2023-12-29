<?php include_once '../../config.php';
include_once '../../helpers/core.php';
include '../layouts/header.php'; ?>

<?php

// cek setiap proses
if (@$_GET['tag'] == '' or @$_GET['tag'] == 'proses') {
    $pengaduans = get('pengaduan');
} else {
    if (@$_GET['tag'] == 'berhasil') {
        $pengaduans = find('pengaduan', ['status', 'berhasil']);
    } elseif (@$_GET['tag'] == 'ditolak') {
        $pengaduans = find('pengaduan', ['status', 'ditolak']);
    } else {
        $pengaduans = get('pengaduan');
    }
}

// pagination


?>

<div class="container">
    <form action="" method="get">
        <div class="row justify-content-center mt-5 mb-3">
            <div class="col-md-6">
                <select name="tag" id="tag" class="form-select">
                    <option hidden class="text-black-50">Pilih Proses Pengaduan</option>
                    <option value="proses">Pengaduan Masuk</option>
                    <option value="berhasil">Pengaduan Di Terima</option>
                    <option value="ditolak">Pengaduan Di Tolak</option>
                </select>
            </div>
            <div class="col-md">
                <button type="submit" class="btn btn-warning">Submit</button>
            </div>
        </div>
    </form>
    <div class="row justify-content-center">
        <div class="table-responsive">
            <table class="table align-middle mb-0 bg-white">
                <thead class="bg-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Pengguna</th>
                        <th>Tanggal</th>
                        <th>Laporan Pengaduan</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pengaduans as $key => $pengaduan) : ?>
                        <tr>
                            <td>
                                <?= $key + 1 ?>
                            </td>
                            <td>
                                <p class="text-muted mb-0">
                                    <?= findFirst('users', ['id', $pengaduan['user_id']])['nama'] ?>
                                </p>
                            </td>
                            <td>
                                <p class="text-muted mb-0"><?= date_format(date_create($pengaduan['tgl']), 'd M Y') ?></p>
                            </td>
                            <td>
                                <p class="text-muted mb-0"><?= substr_replace($pengaduan['isi_laporan'], '...', 40) ?></p>
                            </td>
                            <td>
                                <span class="badge badge-primary rounded-pill d-inline">
                                    <a href="<?= ASSETS ?>/img/<?= $pengaduan['foto'] ?>" target="_blank">Lihat</a>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm btn-rounded" data-mdb-toggle="modal" data-mdb-target="#updatePengaduan<?= $pengaduan['id'] ?>">
                                    Cek
                                </button>
                                <form action="" method="post" class="d-inline-block" onsubmit="return confirm('yakin ingin hapus?')">
                                    <input type="hidden" name="id" value="<?= $pengaduan['id'] ?>">
                                    <button type="submit" name="delete" class="btn btn-danger btn-sm btn-rounded">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>
