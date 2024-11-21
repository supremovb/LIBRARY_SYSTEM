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
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons/css/boxicons.min.css" rel="stylesheet">
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
            <h2><i class="bx bx-lock-alt"></i> Reset Password</h2>
            <form action="<?= base_url('/update-password') ?>" method="POST" id="resetPasswordForm">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= esc($token) ?>">

                <!-- New Password Field -->
                <div class="form-group">
                    <label for="password"><i class="bx bx-key"></i> New Password</label>
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Enter new password">
                    <?php if(isset(session()->getFlashdata('errors')['password'])): ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors')['password']; ?></small>
                    <?php endif; ?>
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="pass_confirm"><i class="bx bx-check-circle"></i> Confirm New Password</label>
                    <input type="password" name="pass_confirm" id="pass_confirm" class="form-control" required placeholder="Confirm new password">
                    <?php if(isset(session()->getFlashdata('errors')['pass_confirm'])): ?>
                        <small class="text-danger"><?= session()->getFlashdata('errors')['pass_confirm']; ?></small>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">
    <i class="bx bx-refresh"></i> Reset Password
</button>

            </form>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JavaScript -->
    <script>
        document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('pass_confirm').value;

            if (password !== confirmPassword) {
                event.preventDefault(); // Prevent the form submission
                Swal.fire({
                    icon: 'error',
                    title: 'Passwords do not match',
                    text: 'The new password and confirm password fields must match.',
                    confirmButtonText: 'Try Again'
                });
            }
        });
    </script>

    <!-- Success Message -->
    <?php if(session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Password Reset Successful',
            text: 'Your password has been successfully updated.',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to login page after SweetAlert is closed
                window.location.href = "<?= base_url('/login') ?>";
            }
        });
    </script>
<?php endif; ?>


<!-- Error Message -->
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

<!-- Other Flash Messages -->
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

</body>
</html>
