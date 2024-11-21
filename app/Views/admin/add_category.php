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
            margin-top: 100px; /* Increased margin-top for more space at the top */
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto; /* Center the form container */
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

    
    <?php if (session()->get('role') === 'admin'): ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('dashboard') ?>"><i class="bx bx-library"></i> Library System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="<?= base_url('admin/dashboard') ?>" class="nav-link"><i class="bx bx-home-alt"></i> Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-book"></i> Books
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="booksDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('admin/categories') ?>"><i class="bx bx-category"></i> Categories</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/add-category') ?>"><i class="bx bx-plus-circle"></i> Add Category</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/create-book') ?>"><i class="bx bx-book-add"></i> Add Book</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/view-books') ?>"><i class="bx bx-list-ul"></i> View Books</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/approve_reject_transactions') ?>"><i class="bx bx-check-circle"></i> View Borrowed Books</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-user"></i> <?= session()->get('firstname') ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('admin/view-profile') ?>"><i class="bx bx-id-card"></i> View Profile</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/view-users') ?>"><i class="bx bx-group"></i> View Users</a></li>
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

            <form method="POST" action="<?= base_url('admin/add-category') ?>">
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

    <?php if(session()->get('success')): ?>
        Swal.fire({
            title: 'Success!',
            text: '<?= session()->get('success'); ?>',  // Dynamically use session success message
            icon: 'success',
            confirmButtonText: 'OK'
        });
    <?php elseif(session()->get('error')): ?>
        Swal.fire({
            title: 'Error!',
            text: '<?= session()->get('error'); ?>',  // Dynamically use session error message
            icon: 'error',
            confirmButtonText: 'Try Again'
        });
    <?php endif; ?>
</script>

</body>
</html>
