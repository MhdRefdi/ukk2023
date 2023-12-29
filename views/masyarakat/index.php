<?php include_once '../../config.php';
include_once '../../helpers/core.php';
include '../layouts/header.php'; ?>

<?php
if (isset($_POST['create'])) {
    $foto = upload('foto', ['png', 'jpg', 'jpeg']);
    if (!$foto) {
        alert('Gagal mengupload pengaduan!');
    } else {
        $_POST['user_id'] = $_SESSION['user']['id'];
        $_POST['tgl'] = date('Y-m-d');
        $_POST['status'] = 'proses';
        $_POST['foto'] = $foto;
        unset($_POST['create']);
        create('pengaduan', $_POST);
        alert('Data berhasil ditambahkan!');
    }
}
if (isset($_POST['update'])) {
    unset($_POST['update']);
    if ($_FILES['foto']['error'] == 4) {
        if (update('pengaduan', $_POST)) {
            alert('Data berhasil diubah!');
        }
    } else {
        $foto = upload('foto', ['png', 'jpg', 'jpeg']);
        if ($foto) {
            $namaFoto = findFirst('pengaduan', ['id', $_POST['id']]);
            $namaFoto = $namaFoto['foto'];
            $_POST['foto'] = $foto;
            unlink('../../assets/img/' . $namaFoto);
            update('pengaduan', $_POST);
            alert('Data berhasil diubah!');
        }
    }
}
if (isset($_POST['delete'])) {
    if (delete('pengaduan', $_POST['id'])) {
        alert('Data berhasil dihapus!');
    }
}

$pengaduans = find('pengaduan', ['user_id', $_SESSION['user']['id']]);
?>

<div class="container">
    <!-- Button trigger modal -->

    <div class="card my-5">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <span class="fw-bold">Listing Pengaduan</span>
                <button type="button" class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#createPengaduan">
                    Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0 bg-white">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Laporan Pengaduan</th>
                            <th>Foto</th>
                            <th>Status</th>
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
                                    <span class="badge badge-<?= $pengaduan['status'] == 'proses' ? 'warning' : ($pengaduan['status'] == 'berhasil' ? 'success' : 'danger') ?> rounded-pill d-inline text-uppercase"><?= $pengaduan['status'] ?></span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm btn-rounded" data-mdb-toggle="modal" data-mdb-target="#updatePengaduan<?= $pengaduan['id'] ?>">
                                        Edit
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
</div>

<!-- Modal Create -->
<div class="modal fade" id="createPengaduan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Pengaduan</h5>
                <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" for="isi_laporan">Pengaduan</label>
                        <textarea required name="isi_laporan" class="form-control" id="isi_laporan" name="isi_laporan" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="foto">Foto / Bukti Pengaduan</label>
                        <input type="file" name="foto" id="foto" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                    <button type="submit" name="create" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Update -->
<?php foreach ($pengaduans as $key => $pengaduanModal) : ?>
    <div class="modal fade" id="updatePengaduan<?= $pengaduanModal['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Pengaduan</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $pengaduanModal['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label" for="isi_laporan">Pengaduan</label>
                            <textarea required name="isi_laporan" class="form-control" id="isi_laporan" name="isi_laporan" rows="3"><?= $pengaduanModal['isi_laporan'] ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="foto">Foto / Bukti Pengaduan (Tidak Wajib)</label>
                            <input type="file" name="foto" id="foto" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php include '../layouts/footer.php'; ?>
