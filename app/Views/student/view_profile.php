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

    <link href="https://unpkg.com/boxicons/css/boxicons.min.css" rel="stylesheet">


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

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .form-control {
            padding-left: 30px;
            /* Adds space for the icon */
        }
    </style>
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <i class="bx bx-book-reader"></i> Library System
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('dashboard') ?>"><i class="bx bx-home"></i> Dashboard</a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('student/book-reviews') ?>" class="nav-link"><i class="bx bx-comment"></i> Book Reviews</a>
                </li>

                <!-- Navbar -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-bell"></i> Notifications
                        <span class="badge badge-danger" id="notificationCount" style="display: none;"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="notificationDropdown" style="max-height: 400px; overflow-y: auto; width: 300px;">
                        <ul id="notificationList" class="list-group list-group-flush">
                            <!-- Notifications will be dynamically loaded here -->
                        </ul>
                    </div>

                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-user"></i> <?= session()->get('firstname') ?> <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url('student/my-borrowed-books') ?>"><i class="bx bx-book"></i> My Borrowed Books</a>
                        <a class="dropdown-item" href="<?= base_url('student/view-profile') ?>"><i class="bx bx-user-circle"></i> View Profile</a>
                        <a class="dropdown-item" href="<?= base_url('student/view-history') ?>"><i class="bx bx-history"></i> History</a>
                        <a class="dropdown-item" href="<?= base_url('user/logout') ?>"><i class="bx bx-log-out"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container profile-container">
        <h2><i class="bx bx-user"></i> User Profile</h2>


        <form action="<?= base_url('student/update-profile') ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">

                <input type="file" class="d-none" name="photo" id="photo" onchange="previewPhoto()">

                <img id="photo-preview" src="<?= $photoPath ?>" alt="User Photo" class="profile-photo" onclick="triggerFileInput()">
            </div>


            <div class="profile-details">
                <div class="row">
                    <div class="col-md-4"><strong>Student ID:</strong></div>
                    <div class="col-md-8">
                        <div class="input-icon">
                            <input type="text" class="form-control" name="student_id" value="<?= esc($user['student_id']) ?>" readonly>
                            <i class="bx bx-id-card"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>First Name:</strong></div>
                    <div class="col-md-8">
                        <div class="input-icon">
                            <input type="text" class="form-control" name="firstname" value="<?= esc($user['firstname']) ?>" required>
                            <i class="bx bx-user"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>Last Name:</strong></div>
                    <div class="col-md-8">
                        <div class="input-icon">
                            <input type="text" class="form-control" name="lastname" value="<?= esc($user['lastname']) ?>" required>
                            <i class="bx bx-user"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>Course:</strong></div>
                    <div class="col-md-8">
                        <div class="input-icon">
                            <input type="text" class="form-control" name="course" value="<?= esc($user['course']) ?>" required>
                            <i class="bx bx-book"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>Year:</strong></div>
                    <div class="col-md-8">
                        <div class="input-icon">
                            <input type="text" class="form-control" name="year" value="<?= esc($user['year']) ?>" required>
                            <i class="bx bx-calendar"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>Role:</strong></div>
                    <div class="col-md-8">
                        <div class="input-icon">
                            <input type="text" class="form-control" name="role" value="<?= esc($user['role']) ?>" readonly>
                            <i class="bx bx-briefcase"></i>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4"><strong>Email:</strong></div>
                    <div class="col-md-8">
                        <div class="position-relative">

                            <input type="email" class="form-control pl-5 pr-5" name="email" value="<?= esc($user['email']) ?>" required>

                            <i class="bx bx-envelope position-absolute" style="top: 50%; left: 15px; transform: translateY(-50%);"></i>

                            <span class="position-absolute" style="top: 50%; right: 15px; transform: translateY(-50%);">
                                <?php if (isset($emailVerified) && $emailVerified): ?>
                                    <i class="bx bx-check-circle text-success" title="Email Verified"></i>
                                <?php else: ?>
                                    <i class="bx bx-x-circle text-danger" title="Email Not Verified"></i>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-4"><strong>Username:</strong></div>
                    <div class="col-md-8">
                        <div class="input-icon">
                            <input type="text" class="form-control" name="username" value="<?= esc($user['username']) ?>" required>
                            <i class="bx bx-user-circle"></i>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mt-3">
                <div class="col-md-4"><strong>New Password:</strong></div>
                <div class="col-md-8">
                    <div class="position-relative">

                        <input type="password" class="form-control pl-5" name="new_password" placeholder="Enter new password">

                        <i class="bx bx-lock position-absolute" style="top: 50%; left: 15px; transform: translateY(-50%);"></i>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4"><strong>Confirm Password:</strong></div>
                <div class="col-md-8">
                    <div class="position-relative">

                        <input type="password" class="form-control pl-5" name="confirm_password" placeholder="Confirm new password">

                        <i class="bx bx-lock position-absolute" style="top: 50%; left: 15px; transform: translateY(-50%);"></i>
                    </div>
                </div>
            </div>


            <div class="form-group mt-3 text-center">
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Save Changes</button>
            </div>

        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>


    <script>
        $(document).ready(function() {
            function updateNotifications() {
                $.ajax({
                    url: '<?= base_url('NotificationController/updateNotifications') ?>',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Update notification list
                        const notificationList = $("#notificationList");
                        notificationList.empty();

                        response.notifications.forEach(notification => {
                            const listItem = `
                        <li class="list-group-item">
                            ${notification.message}
                            <small class="text-muted float-right">${new Date(notification.created_at).toLocaleString()}</small>
                        </li>`;
                            notificationList.append(listItem);
                        });

                        // Update unread count
                        const notificationCount = $("#notificationCount");
                        if (response.unread_count > 0) {
                            notificationCount.text(response.unread_count).show();
                        } else {
                            notificationCount.hide();
                        }
                    },
                    error: function() {
                        console.error("Failed to update notifications.");
                    }
                });
            }

            // Trigger notification update when dropdown is clicked
            $("#notificationDropdown").on('click', function() {
                updateNotifications();
            });

            // Initial update on page load
            updateNotifications();
        });
    </script>

    <script>
        $(document).ready(function() {
            // Fetch unread notifications count
            function fetchNotificationCount() {
                $.get("<?= base_url('notification/unread-count') ?>", function(data) {
                    $('#notificationCount').text(data.unread_count || '');
                });
            }

            // Load notifications into the dropdown
            $('#notificationDropdown').on('click', function() {
                const notificationList = $('#notificationList');
                $.get("<?= base_url('notification/fetch-notifications') ?>", function(notifications) {
                    notificationList.empty();
                    if (notifications.length > 0) {
                        notifications.forEach(notification => {
                            const listItem = `
                        <li class="list-group-item">
                            <strong>${notification.type}</strong>: ${notification.message}
                            <small class="text-muted d-block">${new Date(notification.created_at).toLocaleString()}</small>
                        </li>`;
                            notificationList.append(listItem);
                        });
                    } else {
                        notificationList.append('<li class="list-group-item text-center">No notifications found</li>');
                    }
                });
            });

            // Fetch notification count periodically
            fetchNotificationCount();
            setInterval(fetchNotificationCount, 30000); // Update every 30 seconds
        });
    </script>

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
            });
        <?php elseif (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: 'There was an issue updating your profile.',
            });
        <?php endif; ?>

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
                text: '<?= session()->getFlashdata('error') ?>',
            });
        <?php endif; ?>
    </script>
</body>

</html>