<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css">

    <link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">
    <style>
        /* Ensure descriptions break properly */
        .description-cell {
            word-wrap: break-word;
            max-width: 300px;
            /* Adjust width for better layout */
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
        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 150px;
            /* Adjust width to fit the buttons */
            text-align: center;
        }

        /* Reduce space between buttons */
        .btn-group .btn {
            margin-right: 5px;
        }
    </style>
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
                        <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-book"></i> Books
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="booksDropdown">
                            <li><a class="dropdown-item" href="<?= base_url('admin/categories') ?>"><i class="bx bx-category"></i> Categories</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/add-category') ?>"><i class="bx bx-plus-circle"></i> Add Category</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/create-book') ?>"><i class="bx bx-book-add"></i> Add Book</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/view-books') ?>"><i class="bx bx-list-ul"></i> View Books</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/approve_reject_transactions') ?>"><i class="bx bx-book"></i> View Pending Books</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (session()->get('logged_in')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-user"></i> <?= session()->get('firstname') ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= session()->get('role') === 'admin' ? base_url('admin/view-profile') : base_url('student/view-profile') ?>"><i class="bx bx-id-card"></i> View Profile</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/view-users') ?>"><i class="bx bx-group"></i> View Users</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('user/logout') ?>"><i class="bx bx-log-out"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        </div>
    </nav>


    <div class="container mt-5">
        <h2>Categories</h2>


        <div class="input-group search-bar">
            <span class="input-group-text"><i class="bx bx-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Search by name or description" aria-label="Search Categories">
        </div>

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
                                    <button class="btn btn-warning btn-sm edit-category" data-category-id="<?= $category['category_id'] ?>" data-category-name="<?= $category['name'] ?>" data-category-description="<?= $category['description'] ?>">
                                        <i class="bx bx-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-category" data-category-id="<?= $category['category_id'] ?>">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
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


        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editCategoryForm" method="POST" action="<?= base_url('admin/update-category') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="category_id" value="<?= $category['category_id'] ?>">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="categoryName">Category Name</label>
                                <input type="text" id="categoryName" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="categoryDescription">Description</label>
                                <textarea id="categoryDescription" name="description" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Update Category</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x-circle"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addCategoryForm" method="POST" action="<?= base_url('admin/add-category') ?>">
                        <?= csrf_field() ?>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="categoryName">Category Name</label>
                                <input type="text" id="categoryName" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="categoryDescription">Description</label>
                                <textarea id="categoryDescription" name="description" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Save Category</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x-circle"></i> Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {

            $('.edit-category').on('click', function() {
                var categoryId = $(this).data('category-id');
                var categoryName = $(this).data('category-name');
                var categoryDescription = $(this).data('category-description');


                $('#categoryName').val(categoryName);
                $('#categoryDescription').val(categoryDescription);


                $('input[name="category_id"]').val(categoryId);


                $('#editCategoryModal').modal('show');
            });


            $('#editCategoryForm').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    dataType: 'json', // Expect JSON response
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Category Updated!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {

                                location.reload(); // Reload to reflect changes
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred while processing the request.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });




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

                        window.location.href = '<?= base_url('admin/delete-category/') ?>' + categoryId;
                    }
                });
            });


            <?php if (session()->get('message')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: '<?= session()->get('message') ?>',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>

            <?php if (session()->get('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '<?= session()->get('error') ?>',
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>


            $('#searchInput').on('input', function() {
                var query = $(this).val().toLowerCase();
                $('.category-row').each(function() {
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