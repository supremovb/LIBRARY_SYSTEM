<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book - Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <style>
        .loading {
            display: none;
        }

        .loading.show {
            display: block;
        }

        #imagePreview {
            max-width: 200px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Include the Navbar -->
    <?= $this->include('layout/navbar'); ?>

    <div class="container mt-5">
        <h2>Add New Book</h2>

        <form id="addBookForm" action="admin/create-book" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" required placeholder="Enter book title" aria-describedby="titleHelp">
                <small id="titleHelp" class="form-text text-muted">Please enter the book's title.</small>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Enter book description (optional)" aria-describedby="descriptionHelp"></textarea>
                <small id="descriptionHelp" class="form-text text-muted">You can add a brief description of the book.</small>
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" name="author" class="form-control" required placeholder="Enter author name" aria-describedby="authorHelp">
                <small id="authorHelp" class="form-text text-muted">Please enter the author's name.</small>
            </div>

            <div class="form-group">
                <label for="isbn">ISBN</label>
                <input type="text" name="isbn" class="form-control" required placeholder="Enter ISBN number" pattern="\d{13}" aria-describedby="isbnHelp">
                <small id="isbnHelp" class="form-text text-muted">ISBN should be a 13-digit number.</small>
            </div>

            <div class="form-group">
                <label for="published_date">Published Date</label>
                <input type="date" name="published_date" class="form-control" required aria-describedby="publishedDateHelp">
                <small id="publishedDateHelp" class="form-text text-muted">Please select the book's publication date.</small>
            </div>

            <div class="form-group">
                <label for="photo">Book Photo</label>
                <input type="file" name="photo" class="form-control" accept="image/*" required aria-describedby="photoHelp" id="photoInput">
                <small id="photoHelp" class="form-text text-muted">Upload a cover photo for the book (required).</small>

                <!-- Image Preview -->
                <div id="imagePreviewContainer" class="mt-3" style="display: none;">
                    <p><strong>Selected Photo:</strong></p>
                    <img id="imagePreview" src="" alt="Book Photo Preview" class="img-fluid">
                    <p id="imageFilename"></p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add Book</button>

            <!-- Loading spinner while submitting -->
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
        // Handle file input change and preview image
        $('#photoInput').change(function (e) {
            var file = e.target.files[0];
            var reader = new FileReader();

            if (file) {
                reader.onload = function (event) {
                    // Show the image preview
                    $('#imagePreview').attr('src', event.target.result);
                    $('#imageFilename').text('Filename: ' + file.name);
                    $('#imagePreviewContainer').show(); // Display the preview container
                };
                reader.readAsDataURL(file);
            }
        });

        $('#addBookForm').submit(function (e) {
            e.preventDefault();

            // Show loading spinner
            $('.loading').addClass('show');

            // FormData object for file upload
            var formData = new FormData(this);

            $.ajax({
                url: 'create-book',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('.loading').removeClass('show'); // Hide loading spinner

                    if (response.status === 'success') {
                        Swal.fire(
                            'Success!',
                            response.message,
                            'success'
                        ).then(() => {
                            window.location.href = 'dashboard';
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function () {
                    $('.loading').removeClass('show'); // Hide loading spinner
                    Swal.fire(
                        'Oops!',
                        'Something went wrong, please try again.',
                        'error'
                    );
                }
            });
        });
    </script>
</body>

</html>