<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css">
    <style>
        /* Ensure descriptions break properly */
        .description-cell {
            word-wrap: break-word;
            max-width: 300px; /* Adjust width for better layout */
            overflow-wrap: break-word;
        }

        /* Add margin below the categories title */
        h2 {
            margin-bottom: 30px;
        }

        /* Add padding to the search bar for spacing */
        .search-bar {
            margin-bottom: 20px;
        }

        /* Reduce width of Actions column */
        .table th:nth-child(4), .table td:nth-child(4) {
            width: 150px; /* Adjust width to fit the buttons */
            text-align: center;
        }

        /* Reduce space between buttons */
        .btn-group .btn {
            margin-right: 5px;
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
                    <li class="nav-item">
                        <a href="<?= base_url('admin/dashboard') ?>" class="nav-link">Dashboard</a>
                    </li>
                    <?php if (session()->get('role') === 'admin'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Books
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="booksDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('admin/categories') ?>">Categories</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/add-category') ?>">Add Category</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/create-book') ?>">Add Book</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/view-books') ?>">View Books</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/approve_reject_transactions') ?>">View Borrowed Books</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if (session()->get('logged_in')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= session()->get('firstname') ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= session()->get('role') === 'admin' ? base_url('admin/view-profile') : base_url('student/view-profile') ?>">View Profile</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('user/logout') ?>">Logout</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Categories Content -->
    <div class="container mt-5">
        <h2>Categories</h2>
    
        <!-- Search Bar -->
        <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search by name or description" aria-label="Search Categories">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <tr class="category-row">
                            <td><?= $category['category_id'] ?></td>
                            <td class="category-name"><?= $category['name'] ?></td>
                            <td class="category-description description-cell"><?= $category['description'] ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= base_url('admin/edit-category/' . $category['category_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <button class="btn btn-danger btn-sm delete-category" data-category-id="<?= $category['category_id'] ?>">Delete</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No categories found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Include SweetAlert2 and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js"></script>

    <script>
    $(document).ready(function() {
        // Display success or error message if set
        <?php if (session()->getFlashdata('message')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= session()->getFlashdata('message') ?>',
                confirmButtonText: 'Ok'
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonText: 'Ok'
            });
        <?php endif; ?>

        // Handle category deletion
        $('.delete-category').on('click', function() {
            var categoryId = $(this).data('category-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete URL after confirmation
                    window.location.href = '<?= base_url('admin/delete-category/') ?>' + categoryId;
                }
            });
        });

        // Search functionality
        $('#searchInput').on('input', function () {
            var query = $(this).val().toLowerCase();
            $('.category-row').each(function () {
                var name = $(this).find('.category-name').text().toLowerCase();
                var description = $(this).find('.category-description').text().toLowerCase();

                if (name.includes(query) || description.includes(query)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
    </script>

</body>
</html>
