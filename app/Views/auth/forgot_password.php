<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
            margin: 0;
            padding: 0;
        }

        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding-top: 20px;
        }

        .container {
            max-width: 500px;
            width: 100%;
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

<!-- Include header -->
<?= $this->include('layout/header'); ?>

<!-- Main container for the forgot password form -->
<div class="main-container">
    <div class="container">
        <div class="card">
            <h2><i class="bx bx-lock"></i> Forgot Password</h2>
            <form action="<?= base_url('/send-reset-link') ?>" method="POST">
                <?= csrf_field() ?>

                <!-- SweetAlert Notifications -->
                <?php if(session()->getFlashdata('success')): ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: '<?= session()->getFlashdata('success'); ?>',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = "<?= base_url('/login') ?>"; // Redirect to login
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

                <?php if(session()->getFlashdata('errors')): ?>
                    <ul class="text-danger">
                        <?php foreach(session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <!-- Email Input Field with Boxicon -->
                <div class="form-group position-relative">
    <!-- Email Icon Inside the Input -->
    <i class="bx bx-envelope position-absolute" style="top: 50%; left: 15px; transform: translateY(-50%); font-size: 20px;"></i>
    
    <!-- Email Input Field with Padding to Accommodate Icon -->
    <input type="email" name="email" class="form-control pl-5" required placeholder="Enter your registered email" autofocus>
</div>



                <!-- Submit Button with Boxicon -->
<button type="submit" class="btn btn-primary">
    <i class="bx bx-lock-alt"></i> Send Reset Link
</button>



            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert Notifications -->
<?php if(session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?= session()->getFlashdata('success'); ?>',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = "<?= base_url('/login') ?>"; // Redirect to login page after showing the alert
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


<script>
    // Prevent back navigation to this page
    window.onload = function() {
        history.pushState(null, null, window.location.href);
        window.onpopstate = function() {
            history.go(1); // Push forward to prevent going back
        };
    };
</script>

<!-- Include footer -->
<?= $this->include('layout/footer'); ?>
</body>
</html>
