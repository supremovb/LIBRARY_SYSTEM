<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book - Librarian</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">

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


    <?= $this->include('layout/navbar_librarian'); ?>



    <div class="container mt-5">
        <h2><i class="bx bx-book"></i> Add New Book <i class="bx bx-plus"></i></h2>


        <form id="addBookForm" action="librarian/create-book" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="title"><i class="bx bx-book"></i> Title</label>
                <input type="text" name="title" class="form-control" required placeholder="Enter book title" aria-describedby="titleHelp">
                <small id="titleHelp" class="form-text text-muted">Please enter the book's title.</small>
            </div>

            <div class="form-group">
                <label for="description"><i class="bx bx-comment"></i> Description</label>
                <textarea name="description" class="form-control" rows="5" placeholder="Enter book description (optional)" aria-describedby="descriptionHelp"></textarea>
                <small id="descriptionHelp" class="form-text text-muted">You can add a brief description of the book.</small>
            </div>

            <div class="form-group">
                <label for="author"><i class="bx bx-user"></i> Author</label>
                <input type="text" name="author" class="form-control" required placeholder="Enter author name" aria-describedby="authorHelp">
                <small id="authorHelp" class="form-text text-muted">Please enter the author's name.</small>
            </div>

            <div class="form-group">
                <label for="isbn"><i class="bx bx-barcode"></i> ISBN</label>
                <input type="text" name="isbn" class="form-control" required placeholder="Enter ISBN number" pattern="\d{13}" aria-describedby="isbnHelp">
                <small id="isbnHelp" class="form-text text-muted">ISBN should be a 13-digit number.</small>
            </div>

            <div class="form-group">
                <label for="published_date"><i class="bx bx-calendar"></i> Published Date</label>
                <input type="date" name="published_date" class="form-control" required aria-describedby="publishedDateHelp">
                <small id="publishedDateHelp" class="form-text text-muted">Please select the book's publication date.</small>
            </div>

            <div class="form-group">
                <label for="category"><i class="bx bx-category"></i> Category</label>
                <select name="category" class="form-control" required>
                    <option value="" disabled selected>Select category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['category_id']; ?>"><?= $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <small id="categoryHelp" class="form-text text-muted">Please select the category of the book.</small>
            </div>

            <div class="form-group">
                <label for="quantity"><i class="bx bx-cart-alt"></i> Quantity</label>
                <input type="number" name="quantity" class="form-control" required placeholder="Enter book quantity" aria-describedby="quantityHelp" min="1">
                <small id="quantityHelp" class="form-text text-muted">Enter the number of copies available.</small>
            </div>

            <div class="form-group">
                <label for="photo"><i class="bx bx-image"></i> Book Photo</label>
                <input type="file" name="photo" class="form-control" accept="image/*" required aria-describedby="photoHelp" id="photoInput">
                <small id="photoHelp" class="form-text text-muted">Upload a cover photo for the book (required).</small>


                <div id="imagePreviewContainer" class="mt-3" style="display: none;">
                    <p><strong>Selected Photo:</strong></p>
                    <img id="imagePreview" src="" alt="Book Photo Preview" class="img-fluid">
                    <p id="imageFilename"></p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Add Book</button>


            <div class="loading mt-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div> Processing...
            </div>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $('#photoInput').change(function(e) {
            var file = e.target.files[0];
            var reader = new FileReader();

            if (file) {
                reader.onload = function(event) {

                    $('#imagePreview').attr('src', event.target.result);
                    $('#imageFilename').text('Filename: ' + file.name);
                    $('#imagePreviewContainer').show(); 
                };
                reader.readAsDataURL(file);
            }
        });

        $('#addBookForm').submit(function(e) {
            e.preventDefault();


            $('.loading').addClass('show');


            var formData = new FormData(this);

            $.ajax({
                url: 'create-book',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('.loading').removeClass('show'); 

                    if (response.status === 'success') {
                        Swal.fire(
                            'Success!',
                            response.message,
                            'success'
                        ).then(() => {
                            window.location.href = 'http://localhost/library_system/librarian_dashboard';
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function() {
                    $('.loading').removeClass('show'); 
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