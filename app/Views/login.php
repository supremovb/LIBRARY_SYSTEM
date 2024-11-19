<?= $this->include('layout/header'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Login</title>
    <!-- Bootstrap 4.5.0 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <!-- Font Awesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
    background-color: #f4f7fc;
    height: 100vh;
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: flex-start; /* Align items to the top, allowing room for margin */
    margin: 0;
    padding-top: 50px; /* Push the content down slightly */
}

.login-container {
    max-width: 350px;
    width: 100%;
    background-color: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 140px; /* Add margin-top to push the container down */
}


        .form-group {
            width: 100%;
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
        }

        .btn-block {
            width: 100%;
            padding: 12px;
            border: 2px solid rgba(0, 0, 0, 0.2);
            background-color: transparent;
            color: rgba(0, 0, 0, 0.7);
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-block:hover {
            border-color: rgba(0, 0, 0, 0.5);
            color: black;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .btn-block:focus {
            border-color: black;
            color: white;
            background-color: black;
            outline: none;
        }

        .alert {
            font-size: 14px;
            padding: 10px 15px;
            margin-bottom: 15px;
            max-width: 80%;
            word-wrap: break-word;
            margin-left: auto;
            margin-right: auto;
        }

        .input-group-text {
            cursor: pointer;
        }

        h2 {
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: black;
        }

        .btn-block i {
            margin-right: 8px;
        }

        .swal2-title {
            background-color: transparent !important;
            color: #333 !important;
            padding: 0 !important;
            border: none !important;
            font-size: 1.5em;
        }

    </style>
</head>
<body>

    <div class="container login-container">
        <h2 class="text-center mb-4">Login</h2>

        <!-- Flash message for login failure -->
        <?php if(session()->getFlashdata('msg')):?>
            <div class="alert alert-danger" id="flashError">
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif;?>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <form action="<?= base_url('user/authenticate') ?>" method="post" id="loginForm">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required placeholder="Enter username" aria-describedby="usernameHelp">
                        
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" required placeholder="Enter password" aria-describedby="passwordHelp">
                            <div class="input-group-append">
                                <span class="input-group-text" id="togglePassword">
                                    <i class="fas fa-eye"></i> <!-- Eye icon -->
                                </span>
                            </div>
                        </div>
                
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>

                    <div class="text-center mt-3">
                        <a href="<?= base_url('/forgot-password') ?>">Forgot Password?</a>
                    </div>

                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="<?= base_url('user/register') ?>">Register here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery, Bootstrap JS, SweetAlert JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Custom JS -->
    <script>
        // Toggle password visibility
        $('#togglePassword').on('click', function() {
            var passwordField = $('#password');
            var icon = $(this).find('i');

            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash'); // Change to "eye-slash" when visible
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye'); // Change to "eye" when hidden
            }
        });

        // Form validation for better UX
        $('#loginForm').on('submit', function(event) {
            event.preventDefault();
            
            var username = $('#username').val().trim();
            var password = $('#password').val().trim();

            if (username === "" || password === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Both fields are required!',
                });
            } else {
                // If validation passes, submit the form
                this.submit();
            }
        });

                <?php if(session()->getFlashdata('registration_success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful',
                text: '<?= session()->getFlashdata('registration_success') ?>',
                timer: 3000,
                timerProgressBar: true,
            });
        <?php endif; ?>

        <?php if(session()->getFlashdata('password_reset_success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Password Reset Successful',
        text: '<?= session()->getFlashdata('password_reset_success') ?>',
        timer: 3000,
        timerProgressBar: true,
    });
<?php endif; ?>

            <?php if(session()->getFlashdata('email_verification_success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Email Verified',
                    text: '<?= session()->getFlashdata('email_verification_success') ?>',
                    timer: 3000,
                    timerProgressBar: true,
                });
            <?php endif; ?>


            // Fade in the flash message
            $('#flashError').fadeIn('slow').delay(3000).fadeOut('slow');

        <?php if(session()->getFlashdata('msg')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '<?= session()->getFlashdata('msg') ?>',
                timer: 3000,
                timerProgressBar: true,
            });
            setTimeout(function() {
                $('#flashError').fadeOut('slow');
            }, 1000);
        <?php endif; ?>

        <?php if (isset($_GET['session_expired']) && $_GET['session_expired'] == 1): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Session Expired',
                text: 'Your session has expired. Please log in again.',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>

<?= $this->include('layout/footer'); ?>

</body>
</html>
