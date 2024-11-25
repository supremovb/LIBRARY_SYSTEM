<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 100px;
            
        }

        .form-container {
            max-width: 500px;
            margin: 0 auto;
            
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
        }
    </style>
</head>

<body>


    <?php if (session()->get('role') === 'librarian'): ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= base_url('dashboard') ?>"><i class="bx bx-library"></i> Library System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="<?= base_url('librarian_dashboard') ?>" class="nav-link"><i class="bx bx-home-alt"></i> Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-book"></i> Books
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="booksDropdown">
                                <li><a class="dropdown-item" href="<?= base_url('librarian/categories') ?>"><i class="bx bx-category"></i> Categories</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('librarian/add-category') ?>"><i class="bx bx-plus-circle"></i> Add Category</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('librarian/create-book') ?>"><i class="bx bx-book-add"></i> Add Book</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('librarian/view-books') ?>"><i class="bx bx-list-ul"></i> View Books</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('librarian/approve_reject_transactions') ?>"><i class="bx bx-book"></i> View Pending Books</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('librarian/borrowed-books') ?>"><i class="bx bx-bookmark"></i> View Borrowed Books</a></li>
                            </ul>
                        </li>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-bar-chart-alt-2"></i> Reports
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                                <li><a class="dropdown-item" href="<?= base_url('librarian/generate-book-report') ?>"><i class="bx bx-book"></i> Generate Book's Report</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('librarian/generate-user-report') ?>"><i class="bx bx-group"></i> Generate User's Report</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('librarian/generate-transaction-report') ?>"><i class="bx bx-receipt"></i> Generate Transaction's Report</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-user"></i> <?= session()->get('firstname') ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?= base_url('librarian/view-profile') ?>"><i class="bx bx-id-card"></i> View Profile</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('librarian/view-users') ?>"><i class="bx bx-group"></i> View Users</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('user/logout') ?>"><i class="bx bx-log-out"></i> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <?php endif; ?>


    <div class="container">
        <div class="form-container">
            <h2><i class="bx bx-plus-circle"></i> Add New Category</h2>

            <form method="POST" action="<?= base_url('librarian/add-category') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="name" class="form-label"><i class="bx bx-category"></i> Category Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">
                        <i class="bx bx-pencil"></i> Description
                    </label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>


                <button type="submit" class="btn btn-primary w-100"><i class="bx bx-save"></i> Add Category</button>
            </form>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        <?php if (session()->get('success')): ?>
            Swal.fire({
                title: 'Success!',
                text: '<?= session()->get('success'); ?>', 
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php elseif (session()->get('error')): ?>
            Swal.fire({
                title: 'Error!',
                text: '<?= session()->get('error'); ?>', 
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        <?php endif; ?>
    </script>

</body>

</html>