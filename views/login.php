<?php include_once '../config.php';
include_once '../helpers/core.php';
include 'layouts/header.php'; ?>

<?php
if (auth()) {
    if ($_SESSION['user']['level'] == 'masyarakat') {
        header('location: masyarakat');
    } else {
        header('location: petugas');
    }
}

if (isset($_POST['submit'])) {
    if (login($_POST) != true) {
        alert("Login Gagal!");
    }
}
?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header fw-bold">Login</div>
                <form action="" method="post">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" id="username" maxlength="10" name="username" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" id="password" minlength="8" maxlength="12" name="password" class="form-control" required />
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
