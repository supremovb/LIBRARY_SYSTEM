<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System - Login</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="https://unpkg.com/boxicons/css/boxicons.min.css">

    <style>
        body {
            background: url('http://localhost/library_system/assets/login_bg.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            /* Centers content horizontally */
            align-items: center;
            /* Centers content vertically */
        }

        .login-container {
            max-width: 250px;
            /* Reduced maximum width */
            width: 85%;
            /* Reduced responsive width for smaller screens */
            padding: 15px;
            /* Slightly reduced padding */
            border-radius: 15px;
            /* Smoother, rounded corners */
            background-color: rgba(255, 255, 255, 0.8);
            /* Added light transparency */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            /* Softer shadow for better depth */
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: auto;
            /* Ensures dynamic adjustment */
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

        /* Add blur effect for IP address field */
        .blurred {
            filter: blur(5px);
        }
    </style>
</head>

<body>

    <?= $this->include('layout/header'); ?>

    <div class="container login-container">
        <h2 class="text-center mb-4">LOGIN</h2>


        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-danger" id="flashError">
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif; ?>


        <div class="row justify-content-center">
            <div class="col-md-12">
                <form action="<?= base_url('user/authenticate') ?>" method="post" id="loginForm">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="username" id="usernameLabel">Username/Student ID</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                            </div>
                            <input type="text" id="username" name="username" class="form-control" required placeholder="Enter username" aria-describedby="usernameHelp">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-lock"></i></span>
                            </div>
                            <input type="password" id="password" name="password" class="form-control" required placeholder="Enter password" aria-describedby="passwordHelp">
                            <div class="input-group-append">
                                <span class="input-group-text" id="togglePassword">
                                    <i class="bx bx-show"></i>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="ipAddress">IP Address</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-laptop"></i></span>
                            </div>
                            <input type="text" id="ipAddress" class="form-control blurred" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text" id="toggleIP">
                                    <i class="bx bx-show"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="bx bx-log-in"></i> Login
                    </button>

                    <div class="text-center mt-3">
                        <a href="<?= base_url('/forgot-password') ?>">Forgot Password?</a>
                    </div>

                    <div class="form-group">
                        <label for="role">Login As:</label>
                        <select id="role" name="role" class="form-control">
                            <option value="student">Student</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="text-center mt-3">
                        <p>Don't have an account? <a href="<?= base_url('user/register') ?>">Register here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    <script>
        $.get("https://api.ipify.org?format=json", function(data) {
            $('#ipAddress').val(data.ip);
        });


        $('#togglePassword').on('click', function() {
            var passwordField = $('#password');
            var icon = $(this).find('i');

            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('bx-show').addClass('bx-hide');
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('bx-hide').addClass('bx-show');
            }
        });


        $('#toggleIP').on('click', function() {
            var ipField = $('#ipAddress');
            var icon = $(this).find('i');

            if (ipField.hasClass('blurred')) {
                ipField.removeClass('blurred');
                icon.removeClass('bx-show').addClass('bx-hide');
            } else {
                ipField.addClass('blurred');
                icon.removeClass('bx-hide').addClass('bx-show');
            }
        });

        $('#role').on('change', function() {
            var role = $(this).val(); // Get the selected role

            // Change the label based on the role
            if (role === 'admin') {
                $('#usernameLabel').text('Username/Admin ID');
            } else {
                $('#usernameLabel').text('Username/Student ID');
            }
        });




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

                Swal.fire({
                    title: 'Logging in...',
                    text: 'Please wait while we process your login.',
                    imageUrl: 'http://localhost/library_system/assets/loading.gif',
                    imageWidth: 100,
                    imageHeight: 100,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        setTimeout(() => {
                            $('#loginForm')[0].submit();
                        }, 2000);
                    }
                });
            }
        });


        <?php if (session()->getFlashdata('msg')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '<?= session()->getFlashdata('msg') ?>',
                timer: 3000,
                timerProgressBar: true,
            });
        <?php endif; ?>
    </script>

    <?= $this->include('layout/footer'); ?>

</body>

</html>