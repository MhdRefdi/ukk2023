<?php include_once '../config.php';
include_once '../helpers/core.php';
include 'layouts/header.php'; ?>

<?php
if (auth()) {
    if ($_SESSION['user']['level'] == 'masyarakat') {
        header('location: masyarakat');
    }
}

if (isset($_POST['submit'])) {
    if (register($_POST)) {
        alert("Berhasil membuat user!");
    }
}
?>

<div class="container">
    <div class="row justify-content-center my-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header fw-bold">Register</div>
                <form action="" method="post">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="nama">Nama</label>
                            <input type="text" id="nama" name="nama" maxlength="10" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nik">Nik</label>
                            <input type="text" id="nik" name="nik" class="form-control" maxlength="16" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="telp">No Handphone</label>
                            <input type="text" id="telp" name="telp" class="form-control" maxlength="12" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" id="username" name="username" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" id="password" name="password" maxlength="12" minlength="8" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password2">Confirmation Password</label>
                            <input type="password" id="password2" name="password2" maxlength="12" minlength="8" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <button type="submit" name="submit" class="btn btn-primary">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>
