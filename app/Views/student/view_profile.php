    <?php 
    // Extract photo file name from the full URL
    $photoFileName = !empty($user['photo']) ? basename($user['photo']) : null;

    // Build the photo path
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
        
        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            .profile-container {
            margin-top: 50px;
            text-align: center;
        }
        .profile-photo {
            width: 200px; /* Increased width */
            height: 200px; /* Increased height */
            object-fit: cover;
            border-radius: 50%; /* Keeps the photo circular */
            cursor: pointer;
            border: 2px solid #ddd; /* Optional: Add a border for aesthetics */
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
            <h2>User Profile</h2>

            <!-- User Photo -->
            <form action="<?= base_url('student/update-profile') ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <!-- Hidden file input -->
                    <input type="file" class="d-none" name="photo" id="photo" onchange="previewPhoto()">
                    <!-- Clickable profile photo -->
                    <img id="photo-preview" src="<?= $photoPath ?>" alt="User Photo" class="profile-photo" onclick="triggerFileInput()">
                </div>

                <!-- User Information -->
                <div class="profile-details">
                    <div class="row">
                        <div class="col-md-4"><strong>Username:</strong></div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="username" value="<?= esc($user['username']) ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><strong>First Name:</strong></div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="firstname" value="<?= esc($user['firstname']) ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><strong>Last Name:</strong></div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="lastname" value="<?= esc($user['lastname']) ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><strong>Course:</strong></div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="course" value="<?= esc($user['course']) ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><strong>Year:</strong></div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="year" value="<?= esc($user['year']) ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><strong>Role:</strong></div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="role" value="<?= esc($user['role']) ?>" readonly>
                        </div>
                    </div>

                    <!-- Password Change Fields -->
                    <div class="row mt-3">
                        <div class="col-md-4"><strong>New Password:</strong></div>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="new_password" placeholder="Enter new password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><strong>Confirm Password:</strong></div>
                        <div class="col-md-8">
                            <input type="password" class="form-control" name="confirm_password" placeholder="Confirm new password">
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>

        
        </div>

        <!-- jQuery, Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>

        <!-- Script for Image Preview and File Input Trigger -->
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
                
                reader.onloadend = function () {
                    document.getElementById('photo-preview').src = reader.result;
                };
                
                if (file) {
                    reader.readAsDataURL(file);
                }
            }

            // SweetAlert for success and error messages
            <?php if (session()->getFlashdata('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: 'Your profile has been successfully updated.',
                });
            <?php elseif (session()->getFlashdata('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'There was an issue updating your profile.',
                });
            <?php endif; ?>
        </script>
    </body>
    </html>
