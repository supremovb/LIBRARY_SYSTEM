<?= $this->include('layout/header'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.4/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="https://unpkg.com/boxicons/css/boxicons.min.css">

    <style>
        body {
            background: url('http://localhost/library_system/assets/login_bg.png') no-repeat center center fixed;
            
            background-size: cover;
            
            background-color: #f4f7fc;
            
        }

        .registration-container {
            margin-top: 5%;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-block {
            padding: 12px;
        }

        .flash-message {
            transition: opacity 0.5s ease-in-out;
        }
    </style>

</head>

<body>
    <div class="container registration-container">
        <h2 class="text-center mb-4">STUDENT REGISTRATION</h2>


        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-danger flash-message">
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <form action="<?= site_url('user/createStudent') ?>" method="POST">
                    <?= csrf_field(); ?>


                    <?php if (isset($validation) && $validation->getErrors()): ?>
                        <div class="alert alert-danger flash-message">
                            <?= $validation->listErrors() ?>
                        </div>
                    <?php endif; ?>


                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                            </div>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                            </div>
                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-user"></i></span>
                            </div>
                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="course">Course</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-book"></i></span>
                            </div>
                            <input type="text" class="form-control" id="course" name="course" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="year">Year</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control" id="year" name="year" required>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="bx bx-user-plus"></i> Register
                    </button>

                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="<?= base_url('user/login') ?>">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.4/dist/sweetalert2.all.min.js"></script>


    <script>
        <?php if (session()->getFlashdata('msg')): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: '<?= session()->getFlashdata('msg'); ?>',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33'
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful',
                text: '<?= session()->getFlashdata('success') ?>',
            });
        <?php endif; ?>


        $(document).ready(function() {
            setTimeout(function() {
                $(".flash-message").fadeOut("slow", function() {
                    $(this).remove();
                });
            }, 3000); 
        });
    </script>

</body>

</html>