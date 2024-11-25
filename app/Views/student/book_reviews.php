<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Reviews</title>
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">
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
                    <a class="nav-link" href="<?= base_url('student/book-reviews') ?>"><i class="bx bx-book"></i> Book Reviews</a>
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

                    
                    <?php if (session()->get('user_id') == $review['user_id']): ?>
                        
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

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.all.min.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

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
        
        $('#editReviewModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); 
            var reviewId = button.data('review-id');
            var rating = button.data('rating');
            var reviewText = button.data('review-text');

            
            var modal = $(this);
            modal.find('#reviewId').val(reviewId);
            modal.find('#rating').val(rating);
            modal.find('#review').val(reviewText); 
        });

        $(document).ready(function() {
            
            $('#editReviewForm').submit(function(e) {
                e.preventDefault(); 

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                
                                location.reload();
                            });
                        } else {
                            
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonText: 'Try Again'
                            });
                        }
                    },
                    error: function() {
                        
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
                                location.reload(); 
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