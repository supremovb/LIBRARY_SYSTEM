    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard - Library System</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <!-- SweetAlert CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
        <style>
            .card-deck .card {
                margin-bottom: 20px;
            }

            .card {
        height: 100%; /* Ensures all cards are of equal height */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .modal-body img {
        display: block;
        margin-left: auto;
        margin-right: auto;
        max-width: 100%; /* Ensure the image scales properly */
        height: auto;    /* Maintain the aspect ratio */
    }


    .book-image {
        height: 200px; /* Adjust image height for better fit */
        object-fit: cover;
        border-bottom: 1px solid #ddd; /* Optional: Adds a separator for better visuals */
    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Aligns content properly */
        padding: 15px;
    }

            .search-bar {
                width: 300px;
            }

            .d-flex {
                align-items: center;
                justify-content: space-between;
            }

            .card-title {
        font-size: 1rem; /* Standardize title size */
        font-weight: bold;
        margin-bottom: 5px;
    }

    .card-text {
        font-size: 0.85rem; /* Slightly smaller text for compactness */
        margin-bottom: 5px;
    }

            .pagination-container {
                text-align: center;
            }

            .btn-sm {
                font-size: 0.8rem;
            }

            /* Adjust button container to ensure it is properly aligned */
            .card-body .d-flex {
        display: flex;
        justify-content: space-between; /* Space out buttons evenly */
        margin-top: auto;
    }

            /* Ensure buttons fit within the card */
            .card-body .btn {
        flex: 1; /* Makes buttons equally sized */
        margin: 0 5px; /* Adds spacing between buttons */
    }
    @media (max-width: 576px) {
        .card {
            height: auto; /* Allows flexibility for smaller screens */
        }

        .book-image {
            height: 150px; /* Adjust image height for smaller devices */
        }

        .card-text {
            font-size: 0.8rem; /* Further reduce text size on small screens */
        }

        .card-body .btn {
            font-size: 0.75rem; /* Adjust button size for smaller screens */
        }
    }

        </style>
    </head>

    <body>
        <div class="container mt-5">

        <!-- Include Navbar -->
        <?= $this->include('layout/navbar'); ?>
            <h2>Admin Dashboard</h2>
            <div class="d-flex justify-content-between mb-3">
                <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search by title, author, or ISBN" aria-label="Search Books">
            </div>

            

            <div class="row">
                <!-- Displaying Books in Card Style -->
                <?php foreach ($books as $book) : ?>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <!-- Book Photo with Modal Trigger -->
                            <img src="<?= base_url('uploads/books/' . $book['photo']) ?>" class="card-img-top book-image" alt="<?= $book['title'] ?>" data-toggle="modal" data-target="#bookModal<?= $book['book_id'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($book['title']) ?></h5>
                                <p class="card-text">Author: <?= esc($book['author']) ?></p>
                                <p class="card-text">ISBN: <?= esc($book['isbn']) ?></p>
                                <p class="card-text">Published: <?= esc($book['published_date']) ?></p>
                                <p class="card-text">Status: <?= esc($book['status']) ?></p>
                                <div class="d-flex">
                                    <a href="edit-book/<?= esc($book['book_id']) ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= esc($book['book_id']) ?>">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for Book Details -->
                    <!-- Modal for Book Details with Borrower History -->
<div class="modal fade" id="bookModal<?= $book['book_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="bookModalLabel<?= $book['book_id'] ?>" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookModalLabel<?= $book['book_id'] ?>"><?= esc($book['title']) ?> Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Book Details -->
                <img src="<?= base_url('uploads/books/' . $book['photo']) ?>" class="img-fluid mb-3" alt="<?= $book['title'] ?>">
                <p><strong>Author:</strong> <?= esc($book['author']) ?></p>
                <p><strong>ISBN:</strong> <?= esc($book['isbn']) ?></p>
                <p><strong>Published Date:</strong> <?= esc($book['published_date']) ?></p>
                <p><strong>Status:</strong> <?= esc($book['status']) ?></p>
                <hr>
                <!-- Borrower History -->
                <h6>Borrower History</h6>
                <ul id="borrowerHistory<?= $book['book_id'] ?>" class="list-unstyled">
                    <li>Loading history...</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

                <?php endforeach; ?>
            </div>

            <div class="pagination-container mt-3">
                <!-- Pagination links would go here -->
            </div>
        </div>

        <!-- jQuery, Bootstrap JS, SweetAlert JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <script>
    // Fetch borrower history when a modal is shown
    $('.modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var modal = $(this); // Current modal
        var bookId = modal.attr('id').replace('bookModal', ''); // Extract book ID

        // Clear previous history and show loading message
        var historyList = modal.find(`#borrowerHistory${bookId}`);
        historyList.html('<li>Loading history...</li>');

        // Fetch borrower history via AJAX
        $.ajax({
            url: '<?= base_url("student/get_book_details") ?>',
            type: 'GET',
            data: { book_id: bookId },
            success: function (response) {
                if (response.history && response.history.length > 0) {
                    // Populate history
                    historyList.empty();
                    response.history.forEach(function (entry) {
                        historyList.append(`<li>${entry.user} borrowed on ${entry.date}</li>`);
                    });
                } else {
                    // No history found
                    historyList.html('<li>No borrow history found.</li>');
                }
            },
            error: function () {
                historyList.html('<li>Unable to fetch history.</li>');
            }
        });
    });
</script>


        <script>
            // Delete book confirmation
            $('.delete-btn').click(function () {
                var book_id = $(this).data('id');
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
                        $.ajax({
                            url: 'delete-book/' + book_id,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                book_id: book_id
                            },
                            success: function (response) {
                                if (response.status == 'success') {
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    );
                                }
                            }
                        });
                    }
                });
            });

            // Search functionality
            $('#searchInput').on('input', function () {
                var query = $(this).val().toLowerCase();
                $('.card').each(function () {
                    var title = $(this).find('.card-title').text().toLowerCase();
                    var author = $(this).find('.card-text').eq(0).text().toLowerCase();
                    var isbn = $(this).find('.card-text').eq(1).text().toLowerCase();

                    if (title.includes(query) || author.includes(query) || isbn.includes(query)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        </script>
    </body>

    </html>
