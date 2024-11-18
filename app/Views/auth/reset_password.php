<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 500px;
        }
        .card {
            padding: 30px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
            color: #007bff;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            font-size: 16px;
        }
        .form-group label {
            font-weight: bold;
        }
        small.text-danger {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <h2>Reset Password</h2>
            <form action="<?= base_url('/update-password') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= esc($token) ?>">

                <!-- SweetAlert Notifications -->
                <?php if(session()->getFlashdata('success')): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: '<?= session()->getFlashdata('success'); ?>',
                            confirmButtonText: 'OK'
                        });
                    </script>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '<?= session()->getFlashdata('error'); ?>',
                            confirmButtonText: 'OK'
                        });
                    </script>
                <?php endif; ?>

                <?php if(session()->getFlashdata('msg')): ?>
                    <script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: '<?= session()->getFlashdata('msg'); ?>',
                            confirmButtonText: 'OK'
                        });
                    </script>
                <?php endif; ?>

                <?php if(session()->getFlashdata('errors')): ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please fix the errors below.',
                            confirmButtonText: 'OK'
                        });
                    </script>
                <?php endif; ?>

                <!-- New Password Field -->
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Enter new password">
                    <?php if(isset(session()->getFlashdata('errors')['password'])): ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors')['password']; ?></small>
                    <?php endif; ?>
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="pass_confirm">Confirm New Password</label>
                    <input type="password" name="pass_confirm" class="form-control" required placeholder="Confirm new password">
                    <?php if(isset(session()->getFlashdata('errors')['pass_confirm'])): ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors')['pass_confirm']; ?></small>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
