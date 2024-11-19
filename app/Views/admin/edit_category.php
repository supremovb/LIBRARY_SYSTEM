<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
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

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('dashboard') ?>">Library System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Dashboard Link -->
                    <li class="nav-item">
                        <a href="<?= base_url('admin/dashboard') ?>" class="nav-link">Dashboard</a>
                    </li>
                    <!-- Admin-Specific Books Dropdown -->
                    <?php if (session()->get('role') === 'admin'): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Books
                            </a>
                            <div class="dropdown-menu" aria-labelledby="booksDropdown">
                                <a class="dropdown-item" href="<?= base_url('admin/categories') ?>">Categories</a>
                                <a class="dropdown-item" href="<?= base_url('admin/add-category') ?>">Add Category</a>
                                <a class="dropdown-item" href="<?= base_url('admin/create-book') ?>">Add Book</a>
                                <a class="dropdown-item" href="<?= base_url('admin/view-books') ?>">View Books</a>
                                <a class="dropdown-item" href="<?= base_url('admin/approve_reject_transactions') ?>">View Borrowed Books</a>
                            </div>
                        </li>
                    <?php endif; ?>

                    <!-- Profile Dropdown (Admin/Student) -->
                    <?php if (session()->get('logged_in')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?= session()->get('firstname') ?> <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="userDropdown">
                                <!-- Conditional Profile Link Based on Role -->
                                <a class="dropdown-item" href="<?= session()->get('role') === 'admin' ? base_url('admin/view-profile') : base_url('student/view-profile') ?>">View Profile</a>
                                <a class="dropdown-item" href="<?= base_url('user/logout') ?>">Logout</a>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Edit Category Form -->
    <div class="container">
        <div class="form-container">
            <h2>Edit Category</h2>

            <form method="POST" action="<?= base_url('admin/update-category') ?>">
                <?= csrf_field() ?>  <!-- CSRF token field -->

                <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $category['name'] ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= $category['description'] ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Category</button>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js"></script>

    <script>
        // Show SweetAlert on form submission success or error
        <?php if(session()->get('success')): ?>
            Swal.fire({
                title: 'Success!',
                text: '<?= session()->get('success'); ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php elseif(session()->get('error')): ?>
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
