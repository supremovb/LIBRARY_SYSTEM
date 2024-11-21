<?= $this->include('layout/header'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <!-- Bootstrap 4.5.0 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.4/dist/sweetalert2.min.css">
    <!-- Boxicons Icons -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons/css/boxicons.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f7fc;
        }
        .registration-container {
            margin-top: 5%;
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
        <h2 class="text-center mb-4">Student Registration</h2>

        <!-- Flash message for form validation or errors -->
        <?php if(session()->getFlashdata('msg')): ?>
            <div class="alert alert-danger flash-message">
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <form action="<?= site_url('user/createStudent') ?>" method="POST">
                    <?= csrf_field(); ?>

                    <!-- Flash message for validation errors -->
                    <?php if (isset($validation) && $validation->getErrors()): ?>
                        <div class="alert alert-danger flash-message">
                            <?= $validation->listErrors() ?>
                        </div>
                    <?php endif; ?>

                    <!-- Username -->
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-user"></i></span> <!-- Boxicon user -->
                            </div>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-lock"></i></span> <!-- Boxicon lock -->
                            </div>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-lock"></i></span> <!-- Boxicon lock -->
                            </div>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-envelope"></i></span> <!-- Boxicon envelope -->
                            </div>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>

                    <!-- First Name -->
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-user"></i></span> <!-- Boxicon user -->
                            </div>
                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                        </div>
                    </div>

                    <!-- Last Name -->
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-user"></i></span> <!-- Boxicon user -->
                            </div>
                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                        </div>
                    </div>

                    <!-- Course -->
                    <div class="form-group">
                        <label for="course">Course</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-book"></i></span> <!-- Boxicon book -->
                            </div>
                            <input type="text" class="form-control" id="course" name="course" required>
                        </div>
                    </div>

                    <!-- Year -->
                    <div class="form-group">
                        <label for="year">Year</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bx bx-calendar"></i></span> <!-- Boxicon calendar -->
                            </div>
                            <input type="text" class="form-control" id="year" name="year" required>
                        </div>
                    </div>

                    <!-- Register button with Boxicon icon -->
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="bx bx-user-plus"></i> Register <!-- Boxicon user-plus -->
                    </button>

                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="<?= base_url('user/login') ?>">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery, Bootstrap JS, and SweetAlert2 JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.4/dist/sweetalert2.all.min.js"></script>

    <!-- Custom Script for SweetAlert and Auto-Close Flash Messages -->
    <script>
        // SweetAlert for session messages
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

        // Auto-close flash messages
        $(document).ready(function () {
            setTimeout(function () {
                $(".flash-message").fadeOut("slow", function () {
                    $(this).remove();
                });
            }, 3000); // 5 seconds
        });
    </script>

</body>
</html>
