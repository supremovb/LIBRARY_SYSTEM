<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Box Icons -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <i class="bx bx-book-reader"></i> Library System
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Use ml-auto to push navbar items to the right -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('dashboard') ?>"><i class="bx bx-home"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('student/book-reviews') ?>"><i class="bx bx-book"></i> Book Reviews</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-bell"></i> Notifications
                        <span class="badge badge-danger" id="notificationCount" style="display: none;"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="notificationDropdown" style="max-height: 400px; overflow-y: auto; width: 300px;">
                        <ul id="notificationList" class="list-group list-group-flush">
                            <!-- Notifications will be dynamically loaded here -->
                        </ul>
                    </div>

                </li>
                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= session()->get('firstname') ?> <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url('student/my-borrowed-books') ?>"><i class="bx bx-book"></i> My Borrowed Books</a>
                        <a class="dropdown-item" href="<?= base_url('student/view-profile') ?>"><i class="bx bx-user"></i> View Profile</a>
                        <a class="dropdown-item" href="<?= base_url('student/view-history') ?>"><i class="bx bx-history"></i> History</a>
                        <a class="dropdown-item" href="<?= base_url('user/logout') ?>"><i class="bx bx-log-out"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h3 class="text-center mb-4"><i class="bx bx-history"></i> Transaction History</h3>

        <!-- Display alert if there are no transactions -->
        <?php if (empty($transactions)): ?>
            <div class="alert alert-warning" role="alert">
                No transaction history found.
            </div>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Book Title</th>
                        <th>Borrow Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $index => $transaction): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($transaction['title']) ?></td>
                            <td><?= esc($transaction['borrow_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

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

        $(document).ready(function() {
            $('#categoryFilter').on('change', function() {
                var selectedCategory = $(this).val();
                $('.category-row').each(function() {
                    var categoryId = $(this).data('category-id');
                    if (!selectedCategory || categoryId == selectedCategory) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
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


    <?php if (empty($transactions)): ?>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'No Transaction History',
                text: 'You have no transaction history at the moment.',
            });
        </script>
    <?php endif; ?>

</body>

</html>