<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="<?= csrf_hash() ?>">

    <title>My Borrowed Books - Library System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css">
    <style>
        .container {
            margin-top: 50px;
        }

        .card {
            width: 18rem;
            margin: 1rem;
            height: 400px;
            display: flex;
            flex-direction: column;
        }

        .card-body {
            overflow: hidden;
            flex-grow: 1;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .grid-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card-img-top {
            height: 150px;
            object-fit: cover;
        }

        .card-body p {
            font-size: 0.9rem;
        }

        .return-btn {
            margin-top: auto;
        }

        .no-books-message {
            text-align: center;
            font-size: 1.2rem;
            color: #6c757d;
            margin-top: 20px;
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
                    <a class="nav-link" href="<?= base_url('dashboard') ?>">
                        <i class="bx bx-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('student/book-reviews') ?>" class="nav-link"><i class="bx bx-comment"></i> Book Reviews</a>
                </li>

                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-bell"></i> Notifications
                        <span class="badge badge-danger" id="notificationCount" style="display: none;"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="notificationDropdown" style="max-height: 400px; overflow-y: auto; width: 300px;">
                        <ul id="notificationList" class="list-group list-group-flush">
                            
                        </ul>
                    </div>

                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-user-circle"></i> <?= session()->get('firstname') ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url('student/my-borrowed-books') ?>">
                            <i class="bx bx-bookmark"></i> My Borrowed Books
                        </a>
                        <a class="dropdown-item" href="<?= base_url('student/view-profile') ?>">
                            <i class="bx bx-id-card"></i> View Profile
                        </a>
                        <a class="dropdown-item" href="<?= base_url('student/view-history') ?>"><i class="bx bx-history"></i> History</a>
                        <a class="dropdown-item" href="<?= base_url('user/logout') ?>">
                            <i class="bx bx-log-out"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        
        <?php if (empty($borrowed)): ?>
            <div class="no-books-message">
                <p>You have no borrowed books at the moment.</p>
            </div>
        <?php else: ?>
            <div class="text-center mt-4" id="returnAllBooksContainer">
                <button class="btn btn-danger" id="returnAllBooksBtn">
                    <i class="bx bx-rotate-left"></i> Return All Books
                </button>
            </div>

            <div class="grid-container">
                <?php foreach ($borrowed as $transaction): ?>
                    <div class="card">
                        <img src="<?= base_url('uploads/books/' . esc($transaction['photo'])) ?>" class="card-img-top" alt="<?= esc($transaction['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($transaction['title']) ?></h5>
                            <p class="card-text">
                                <strong>Author:</strong> <?= esc($transaction['author']) ?><br>
                                <strong>ISBN:</strong> <?= esc($transaction['isbn']) ?><br>
                                <strong>Published Date:</strong> <?= esc($transaction['published_date']) ?><br>
                                <strong>Borrowed On:</strong> <?= esc($transaction['borrow_date']) ?><br>
                                <strong>Due Date:</strong> <?= esc($transaction['due_date']) ?>
                            </p>
                            <button class="btn btn-warning btn-sm return-btn" data-id="<?= esc($transaction['transaction_id']) ?>">
                                <i class="bx bx-check-circle"></i> Return
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    <script>
        $(document).ready(function() {
            function updateNotifications() {
                $.ajax({
                    url: '<?= base_url('NotificationController/updateNotifications') ?>',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        
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

            
            $("#notificationDropdown").on('click', function() {
                updateNotifications();
            });

            
            updateNotifications();
        });
    </script>

    <script>
        $(document).ready(function() {
            
            function fetchNotificationCount() {
                $.ajax({
                    url: '<?= base_url("NotificationController/unreadCount") ?>',
                    method: 'GET',
                    success: function(response) {
                        const count = response.unread_count || 0;
                        const notificationBadge = $('#notificationCount');
                        if (count > 0) {
                            notificationBadge.text(count).show();
                        } else {
                            notificationBadge.hide();
                        }
                    },
                    error: function() {
                        console.error("Failed to fetch notification count.");
                    }
                });
            }

            
            function fetchNotifications() {
                $.ajax({
                    url: '<?= base_url("NotificationController/fetchNotifications") ?>',
                    method: 'GET',
                    success: function(notifications) {
                        const notificationList = $('#notificationList');
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
                    },
                    error: function() {
                        console.error("Failed to fetch notifications.");
                    }
                });
            }

            
            $('#notificationDropdown').on('click', function() {
                fetchNotifications();
                $.ajax({
                    url: '<?= base_url("NotificationController/markAsRead") ?>',
                    method: 'POST',
                    success: function() {
                        fetchNotificationCount(); 
                    },
                    error: function() {
                        console.error("Failed to mark notifications as read.");
                    }
                });
            });

            
            fetchNotificationCount();

            
            setInterval(fetchNotificationCount, 30000); 
        });
    </script>

    <script>
        $(document).on('click', '.return-btn', function() {
            var transaction_id = $(this).data('id');
            Swal.fire({
                title: 'Confirm Return',
                text: "Do you want to return this book?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, return it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/library_system/index.php/transaction/returnBook',
                        type: 'POST',
                        data: {
                            transaction_id: transaction_id
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait.',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire(
                                    'Success!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        });

        $(document).on('click', '#returnAllBooksBtn', function() {
            Swal.fire({
                title: 'Confirm Return All Books',
                text: "Do you want to return all your borrowed books at once?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, return all!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/library_system/index.php/transaction/returnAllBooks',
                        type: 'POST',
                        data: {
                            user_id: <?= session()->get('user_id') ?>
                        },
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait while we return all your books.',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire(
                                    'Success!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>