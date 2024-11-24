<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <i class="bx bx-book-reader"></i> Library System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a href="<?= base_url('admin/dashboard') ?>" class="nav-link"><i class="bx bx-home"></i> Dashboard</a>
                </li>

                <?php if (session()->get('role') === 'admin'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-book"></i> Books
                        </a>
                        <div class="dropdown-menu" aria-labelledby="booksDropdown">
                            <a class="dropdown-item" href="<?= base_url('admin/categories') ?>"><i class="bx bx-list-ul"></i> Categories</a>
                            <a class="dropdown-item" href="<?= base_url('admin/add-category') ?>"><i class="bx bx-plus"></i> Add Category</a>
                            <a class="dropdown-item" href="<?= base_url('admin/create-book') ?>"><i class="bx bx-plus"></i> Add Book</a>
                            <a class="dropdown-item" href="<?= base_url('admin/view-books') ?>"><i class="bx bx-book-open"></i> View Books</a>
                            <a class="dropdown-item" href="<?= base_url('admin/approve_reject_transactions') ?>"><i class="bx bx-book"></i> View Pending Books</a>
                            <a class="dropdown-item" href="<?= base_url('admin/borrowed-books') ?>"><i class="bx bx-bookmark"></i> View Borrowed Books</a>
                        </div>
                    </li>
                    <!-- Reports Dropdown Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-bar-chart-alt-2"></i> Reports
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('admin/generate-book-report') ?>"><i class="bx bx-book"></i> Generate Book's Report</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/generate-user-report') ?>"><i class="bx bx-group"></i> Generate User's Report</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/generate-transaction-report') ?>"><i class="bx bx-receipt"></i> Generate Transaction's Report</a></li>
                        </ul>
                    </li>

                <?php endif; ?>


                <?php if (session()->get('logged_in')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-user"></i> <?= session()->get('firstname') ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="<?= session()->get('role') === 'admin' ? base_url('admin/view-profile') : base_url('student/view-profile') ?>"><i class="bx bx-user"></i> View Profile</a>
                            <a class="dropdown-item" href="<?= base_url('admin/view-users') ?>"><i class="bx bx-group"></i> View Users</a>
                            <a class="dropdown-item" href="<?= base_url('user/logout') ?>"><i class="bx bx-log-out"></i> Logout</a>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Borrowed Books</h2>
        <div class="text-center mb-3">
            <!-- Button to Send Notifications -->
            <button id="sendNotificationButton" class="btn btn-danger">Send Notifications to Overdue Students</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <!-- Table content here -->
            </table>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Book Title</th>
                        <th>Borrower</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($borrowedBooks)): ?>
                        <?php foreach ($borrowedBooks as $index => $book): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($book->title) ?></td>
                                <td><?= htmlspecialchars($book->firstname . ' ' . $book->lastname) ?></td>
                                <td><?= date('M d, Y', strtotime($book->borrow_date)) ?></td>
                                <td><?= date('M d, Y', strtotime($book->due_date)) ?></td>
                                <td><?= $book->return_date ? date('M d, Y', strtotime($book->return_date)) : 'Not Returned' ?></td>
                                <td>
                                    <button
                                        class="btn btn-primary btn-sm view-details"
                                        data-title="<?= htmlspecialchars($book->title) ?>"
                                        data-borrower="<?= htmlspecialchars($book->firstname . ' ' . $book->lastname) ?>"
                                        data-borrowed="<?= $book->borrow_date ?>"
                                        data-due="<?= $book->due_date ?>"
                                        data-return="<?= $book->return_date ? $book->return_date : 'Not Returned' ?>">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No borrowed books found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script>
        // Handle "View Details" button click
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                const title = this.dataset.title;
                const borrower = this.dataset.borrower;
                const borrowedDate = this.dataset.borrowed;
                const dueDate = this.dataset.due;
                const returnDate = this.dataset.return;

                Swal.fire({
                    title: 'Book Details',
                    html: `
                        <p><strong>Title:</strong> ${title}</p>
                        <p><strong>Borrower:</strong> ${borrower}</p>
                        <p><strong>Borrow Date:</strong> ${new Date(borrowedDate).toLocaleDateString()}</p>
                        <p><strong>Due Date:</strong> ${new Date(dueDate).toLocaleDateString()}</p>
                        <p><strong>Return Date:</strong> ${returnDate !== 'Not Returned' ? new Date(returnDate).toLocaleDateString() : 'Not Returned'}</p>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Close'
                });
            });
        });

        document.getElementById('sendNotificationButton').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to send notifications to all overdue students!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, send it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make an AJAX request to send notifications
                    fetch('<?= base_url('admin/send-notification-to-overdue') ?>', {
                            method: 'POST',
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Success!', 'Notifications sent to overdue students.', 'success');
                            } else {
                                // Handle the case when there are no overdue books
                                Swal.fire('No Overdue Books', data.message, 'info');
                            }
                        })
                        .catch(error => {
                            // Handle network or other errors
                            Swal.fire('Error!', 'There was an issue sending notifications.', 'error');
                        });
                }
            });
        });
    </script>
</body>

</html>