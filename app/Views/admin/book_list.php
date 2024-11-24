<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Library System</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

    <link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">

    <style>
        .card-deck .card {
            margin-bottom: 20px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .modal-body img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            max-width: 100%;
            height: auto;
        }

        .book-image {
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
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
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .card-text {
            font-size: 0.85rem;
            margin-bottom: 5px;
        }

        .pagination-container {
            text-align: center;
        }

        .btn-sm {
            font-size: 0.8rem;
        }

        .card-body .d-flex {
            display: flex;
            justify-content: space-between;
            margin-top: auto;
        }

        .card-body .btn {
            flex: 1;
            margin: 0 5px;
        }

        @media (max-width: 576px) {
            .card {
                height: auto;
            }

            .book-image {
                height: 150px;
            }

            .card-text {
                font-size: 0.8rem;
            }

            .card-body .btn {
                font-size: 0.75rem;
            }
        }

        /* Back to top button styles */
        #backToTopBtn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 15px;
            font-size: 24px;
            display: none;
            /* Hidden by default */
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #backToTopBtn:hover {
            background-color: #0056b3;
        }

        #backToTop {
            transition: transform 0.3s ease;
        }

        #backToTop:hover {
            transform: scale(1.1);
            /* Slightly enlarge on hover */
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            /* Bigger shadow on hover */
        }
    </style>
</head>

<body>
    <div class="container mt-5">

        <?= $this->include('layout/navbar'); ?>
        <h2><i class="bx bx-home"></i> Admin Dashboard</h2>
        <div class="d-flex justify-content-between mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                </div>
                <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search by title, author, or ISBN" aria-label="Search Books">
            </div>
        </div>

        <div class="row">

            <?php foreach ($books as $book) : ?>
                <div class="col-md-3 mb-4">
                    <div class="card">

                        <img src="<?= base_url('uploads/books/' . $book['photo']) ?>" class="card-img-top book-image" alt="<?= $book['title'] ?>" data-toggle="modal" data-target="#bookModal<?= $book['book_id'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($book['title']) ?></h5>
                            <p class="card-text">Author: <?= esc($book['author']) ?></p>
                            <p class="card-text">ISBN: <?= esc($book['isbn']) ?></p>
                            <p class="card-text">Published: <?= esc($book['published_date']) ?></p>
                            <p class="card-text">Status: <?= esc($book['status']) ?></p>
                            <div class="d-flex">
                                <a href="edit-book/<?= esc($book['book_id']) ?>" class="btn btn-primary btn-sm">
                                    <i class="bx bx-edit"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= esc($book['book_id']) ?>">
                                    <i class="bx bx-trash"></i> Delete
                                </button>
                            </div>

                        </div>
                    </div>
                </div>


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
                                <img src="<?= base_url('uploads/books/' . $book['photo']) ?>" class="img-fluid mb-3" alt="<?= $book['title'] ?>">
                                <p><strong>Author:</strong> <?= esc($book['author']) ?></p>
                                <p><strong>ISBN:</strong> <?= esc($book['isbn']) ?></p>
                                <p><strong>Published Date:</strong> <?= esc($book['published_date']) ?></p>
                                <p><strong>Status:</strong> <?= esc($book['status']) ?></p>
                                <hr>
                                <h6>Borrower History</h6>
                                <ul id="borrowerHistory<?= $book['book_id'] ?>" class="list-unstyled">
                                    <li>Loading history...</li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="bx bx-x"></i> Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

        <!-- Back to Top Button -->
        <a href="#" id="backToTop" class="btn" style="display: none; position: fixed; bottom: 30px; right: 30px; z-index: 1000; width: 50px; height: 50px; background: linear-gradient(135deg, #4e73df, #1a5ab1); color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); font-size: 24px; transition: all 0.3s ease-in-out; border: none;">
            <i class="bx bx-up-arrow-alt"></i>
        </a>

    </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        // Show the back to top button when scrolling down
        window.addEventListener("scroll", function() {
            let button = document.getElementById("backToTop");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                button.style.display = "flex"; // Show button
            } else {
                button.style.display = "none"; // Hide button
            }
        });

        // Smooth scroll to the top when the button is clicked
        document.getElementById("backToTop").addEventListener("click", function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

    <script>
        $('.modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var modal = $(this); // Current modal
            var bookId = modal.attr('id').replace('bookModal', ''); // Extract book ID


            var historyList = modal.find(`#borrowerHistory${bookId}`);
            historyList.html('<li>Loading history...</li>');


            $.ajax({
                url: '<?= base_url("student/get_book_details") ?>',
                type: 'GET',
                data: {
                    book_id: bookId
                },
                success: function(response) {
                    if (response.history && response.history.length > 0) {
                        historyList.empty();
                        response.history.forEach(function(entry) {
                            historyList.append(`<li>${entry.user} borrowed on ${entry.date}</li>`);
                        });
                    } else {
                        historyList.html('<li>No borrow history found.</li>');
                    }
                },
                error: function() {
                    historyList.html('<li>Unable to fetch history.</li>');
                }
            });
        });
    </script>

    <script>
        $('.delete-btn').click(function() {
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
                        success: function(response) {
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


        $('#searchInput').on('input', function() {
            var query = $(this).val().toLowerCase();
            $('.card').each(function() {
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