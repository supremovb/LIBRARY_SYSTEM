<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .loading {
            display: none;
        }

        .loading.show {
            display: block;
        }

        /* Adjust input fields */
        .form-control {
            max-width: 100%;
        }

        .container {
            max-width: 800px;
        }

        /* Ensure image is not too large */
        .img-thumbnail {
            max-width: 150px;
        }

        /* Adjust text area */
        textarea.form-control {
            resize: vertical;
        }
    </style>
</head>

<body>

<?= $this->include('layout/navbar'); ?>
    <div class="container mt-5">
        <h2>Edit Book</h2>

        <form id="editBookForm" action="<?= site_url('admin/update-book') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="book_id" value="<?= esc($book['book_id']) ?>">

            <div class="form-group">
                <label for="title"><i class="fas fa-book"></i> Title</label>
                <input type="text" name="title" class="form-control" required value="<?= esc($book['title']) ?>">
            </div>

            <div class="form-group">
                <label for="author"><i class="fas fa-user"></i> Author</label>
                <input type="text" name="author" class="form-control" required value="<?= esc($book['author']) ?>">
            </div>

            <div class="form-group">
                <label for="isbn"><i class="fas fa-barcode"></i> ISBN</label>
                <input type="text" name="isbn" class="form-control" required value="<?= esc($book['isbn']) ?>">
            </div>

            <div class="form-group">
                <label for="published_date"><i class="fas fa-calendar-alt"></i> Published Date</label>
                <input type="date" name="published_date" class="form-control" required value="<?= esc($book['published_date']) ?>">
            </div>

            <div class="form-group">
                <label for="status"><i class="fas fa-check-circle"></i> Status</label>
                <select name="status" class="form-control">
                    <option value="available" <?= $book['status'] == 'available' ? 'selected' : '' ?>>Available</option>
                    <option value="borrowed" <?= $book['status'] == 'borrowed' ? 'selected' : '' ?>>Borrowed</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" class="form-control" required><?= esc($book['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="category"><i class="fas fa-tags"></i> Category</label>
                <select name="category" class="form-control" required>
                    <option value="" disabled selected>Select category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['category_id']; ?>" <?= $book['category_id'] == $category['category_id'] ? 'selected' : '' ?>>
                            <?= $category['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small id="categoryHelp" class="form-text text-muted">Please select the category of the book.</small>
            </div>

            <div class="form-group">
                <label for="quantity"><i class="fas fa-cogs"></i> Quantity</label>
                <input type="number" name="quantity" class="form-control" required min="0" value="<?= esc($book['quantity']) ?>">
            </div>


            <div class="form-group">
                <label for="photo"><i class="fas fa-camera"></i> Photo</label>
                <input type="file" name="photo" class="form-control">
                <?php if (!empty($book['photo'])): ?>
                    <img src="<?= base_url('uploads/books/' . esc($book['photo'])) ?>" alt="Book Photo" class="img-thumbnail mt-2">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Book</button>
            <div class="loading mt-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div> Processing...
            </div>
        </form>
    </div>

    <!-- jQuery, Bootstrap JS, SweetAlert JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $('#editBookForm').submit(function (e) {
            e.preventDefault();
            $('.loading').addClass('show'); // Show the loading spinner

            const formData = new FormData(this);

            $.ajax({
                url: '<?= site_url("admin/update-book") ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('.loading').removeClass('show'); // Hide the loading spinner

                    if (response.status === 'success') {
                        Swal.fire(
                            'Success!',
                            response.message,
                            'success'
                        ).then(() => {
                            window.location.href = '<?= site_url("admin/dashboard") ?>'; // Redirect to the dashboard
                        });
                    } else if (response.status === 'error') {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function () {
                    $('.loading').removeClass('show'); // Hide the loading spinner
                    Swal.fire(
                        'Oops!',
                        'Something went wrong. Please try again later.',
                        'error'
                    );
                }
            });
        });
    </script>
</body>

</html>
