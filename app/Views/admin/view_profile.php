<?php

$photoFileName = !empty($user['photo']) ? basename($user['photo']) : null;


$photoPath = (!empty($photoFileName) && file_exists(ROOTPATH . 'uploads/user_photos/' . $photoFileName))
    ? base_url('uploads/user_photos/' . esc($photoFileName))
    : base_url('uploads/user_photos/default_photo.png');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


    <link href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" rel="stylesheet">


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .profile-container {
            margin-top: 50px;
            text-align: center;
        }

        .profile-photo {
            width: 200px;
            /* Increased width */
            height: 200px;
            /* Increased height */
            object-fit: cover;
            border-radius: 50%;
            /* Keeps the photo circular */
            cursor: pointer;
            border: 2px solid #ddd;
            /* Optional: Add a border for aesthetics */
        }

        .profile-details {
            margin-top: 30px;
        }

        .profile-details .row {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <?= $this->include('layout/navbar'); ?>
    <div class="container profile-container">
        <h2><i class="bx bx-id-card"></i> User Profile</h2>



        <?php if (session()->getFlashdata('validationErrors')): ?>
            <div class="alert alert-danger alert-dismissible fade show validation-alert">
                <ul>
                    <?php foreach (session()->getFlashdata('validationErrors') as $field => $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>


        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show general-alert">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show general-alert">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>


        <form action="<?= base_url('admin/update-profile') ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">

                <input type="file" class="d-none" name="photo" id="photo" onchange="previewPhoto()">

                <img id="photo-preview" src="<?= $photoPath ?>" alt="User Photo" class="profile-photo" onclick="triggerFileInput()">
            </div>


            <div class="profile-details">
                <div class="row">
                    <div class="col-md-4"><strong>Student ID:</strong></div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="student_id" value="<?= esc($user['student_id']) ?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>First Name:</strong></div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="uil uil-user"></i></span>
                            </div>
                            <input type="text" class="form-control" name="firstname" value="<?= esc($user['firstname']) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>Last Name:</strong></div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="uil uil-user"></i></span>
                            </div>
                            <input type="text" class="form-control" name="lastname" value="<?= esc($user['lastname']) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>Role:</strong></div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="uil uil-shield-check"></i></span>
                            </div>
                            <input type="text" class="form-control" name="role" value="<?= esc($user['role']) ?>" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>Email:</strong></div>
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="uil uil-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control" name="email" value="<?= esc($user['email']) ?>" required>
                            </div>
                            <span class="ml-2">
                                <?php if ($emailVerified): ?>
                                    <i class="fas fa-check-circle text-success" title="Email Verified"></i>
                                <?php else: ?>
                                    <i class="fas fa-times-circle text-danger" title="Email Not Verified"></i>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>Username:</strong></div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="uil uil-user"></i></span>
                            </div>
                            <input type="text" class="form-control" name="username" value="<?= esc($user['username']) ?>" required>
                        </div>
                    </div>
                </div>


                <div class="row mt-3">
                    <div class="col-md-4"><strong>New Password:</strong></div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="uil uil-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" name="new_password" placeholder="Enter new password">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>Confirm Password:</strong></div>
                    <div class="col-md-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="uil uil-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm new password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary"><i class="uil uil-save"></i> Save Changes</button>
            </div>
        </form>

    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>


    <script>
        function triggerFileInput() {
            Swal.fire({
                icon: 'info',
                title: 'Change Profile Photo',
                text: 'Click "OK" to upload a new profile photo.',
                confirmButtonText: 'OK',
                preConfirm: () => {
                    document.getElementById('photo').click();
                }
            });
        }

        function previewPhoto() {
            const file = document.getElementById('photo').files[0];
            const reader = new FileReader();

            reader.onloadend = function() {
                document.getElementById('photo-preview').src = reader.result;
            };

            if (file) {
                reader.readAsDataURL(file);
            }
        }


        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Profile Updated',
                text: 'Your profile has been successfully updated.',
                timer: 3000, // Auto-close after 3 seconds
                timerProgressBar: true,
            });
        <?php elseif (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: '<?= esc(session()->getFlashdata('error')) ?>',
                timer: 3000, // Auto-close after 3 seconds
                timerProgressBar: true,
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('validationErrors')): ?>
            const validationErrors = <?= json_encode(session()->getFlashdata('validationErrors')) ?>;
            let errorList = '';
            for (const [field, error] of Object.entries(validationErrors)) {
                errorList += `<li>${error}</li>`;
            }
            Swal.fire({
                icon: 'error',
                title: 'Validation Errors',
                html: `<ul style="text-align: left;">${errorList}</ul>`,
                confirmButtonText: 'OK',
            });
        <?php endif; ?>



        setTimeout(() => {
            const validationAlert = document.querySelector('.validation-alert');
            if (validationAlert) {
                validationAlert.classList.remove('show'); // Bootstrap's fade-out effect
                setTimeout(() => validationAlert.remove(), 500); // Remove from DOM after animation
            }

            const generalAlert = document.querySelector('.general-alert');
            if (generalAlert) {
                generalAlert.classList.remove('show'); // Bootstrap's fade-out effect
                setTimeout(() => generalAlert.remove(), 500); // Remove from DOM after animation
            }
        }, 1000); // Delay in milliseconds
    </script>
</body>

</html>