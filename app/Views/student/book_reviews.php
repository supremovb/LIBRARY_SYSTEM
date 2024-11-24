<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Reviews</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.min.css">
    <!-- Boxicons CSS -->
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
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('dashboard') ?>"><i class="bx bx-home"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('student/book-reviews') ?>"><i class="bx bx-book"></i> Book Reviews</a>
                </li>
                <!-- Notifications Dropdown -->
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
                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

    <div class="container mt-5">
        <h1>Book Reviews</h1>
        <div class="list-group">
            <?php foreach ($reviews as $review): ?>
                <div class="list-group-item">
                    <h5 class="mb-1"><?= esc($review['title']); ?> - Rating: <?= esc($review['rating']); ?> <i class="bx bx-star text-warning"></i></h5>
                    <p class="mb-1"><?= esc($review['review']); ?></p>
                    <small>Reviewed by: <?= esc($review['firstname']) . ' ' . esc($review['lastname']); ?> on <?= esc($review['created_at']); ?></small>

                    <!-- Check if the logged-in user is the one who wrote this review -->
                    <?php if (session()->get('user_id') == $review['user_id']): ?>
                        <!-- Edit Button to Trigger Modal -->
                        <button class="btn btn-warning btn-sm ml-2" data-toggle="modal" data-target="#editReviewModal"
                            data-review-id="<?= $review['review_id']; ?>"
                            data-rating="<?= $review['rating']; ?>"
                            data-review-text="<?= esc($review['review']); ?>">
                            <i class="bx bx-edit"></i> Edit
                        </button>

                        <button class="btn btn-danger btn-sm ml-2" onclick="deleteReview(<?= $review['review_id']; ?>)">
                            <i class="bx bx-trash"></i> Delete
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal for Editing Review -->
    <div class="modal fade" id="editReviewModal" tabindex="-1" role="dialog" aria-labelledby="editReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReviewModalLabel">Edit Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editReviewForm" method="POST" action="<?= site_url('book_review/update'); ?>">
                        <input type="hidden" name="review_id" id="reviewId">
                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <input type="number" id="rating" name="rating" class="form-control" min="1" max="5">
                        </div>
                        <div class="form-group">
                            <label for="review">Review (Comments):</label>
                            <textarea id="review" name="review" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.all.min.js"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

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
        // Trigger Modal with Data from Edit Button
        $('#editReviewModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var reviewId = button.data('review-id');
            var rating = button.data('rating');
            var reviewText = button.data('review-text');

            // Populate the modal fields with review data
            var modal = $(this);
            modal.find('#reviewId').val(reviewId);
            modal.find('#rating').val(rating);
            modal.find('#review').val(reviewText); // Corrected to match the field ID
        });

        $(document).ready(function() {
            // Handle form submission for editing the review
            $('#editReviewForm').submit(function(e) {
                e.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Show success SweetAlert message
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Reload the page to reflect the changes
                                location.reload();
                            });
                        } else {
                            // Show error SweetAlert message
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'Try Again'
                            });
                        }
                    },
                    error: function() {
                        // Show generic error SweetAlert message in case of failure
                        Swal.fire({
                            title: 'Oops!',
                            text: 'Something went wrong. Please try again later.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });


        // Function to handle deleting a review
        function deleteReview(reviewId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this review!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= site_url("book_review/delete/"); ?>' + reviewId,
                        method: 'POST',
                        success: function(response) {
                            if (response === 'success') {
                                Swal.fire(
                                    'Deleted!',
                                    'Your review has been deleted.',
                                    'success'
                                );
                                location.reload(); // Reload page to update the reviews list
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting your review.',
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>