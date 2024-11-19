<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css" rel="stylesheet">
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Library System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('admin/categories') ?>">Categories</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Add Category Form -->
    <div class="container">
        <div class="form-container">
            <h2>Add New Category</h2>

            <form method="POST" action="<?= base_url('admin/add-category') ?>">
            <?= csrf_field() ?>  <!-- CSRF token field -->


                <div class="mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Add Category</button>
            </form>
        </div>
    </div>

    <!-- jQuery, Bootstrap JS, SweetAlert JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <script>
        // Show SweetAlert on form submission success or error
        <?php if(session()->get('success')): ?>
            Swal.fire({
                title: 'Success!',
                text: 'Category added successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php elseif(session()->get('error')): ?>
            Swal.fire({
                title: 'Error!',
                text: 'Failed to add category. Please try again.',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        <?php endif; ?>
    </script>
</body>
</html>
