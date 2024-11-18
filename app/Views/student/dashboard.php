<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Library System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <style>
        /* Custom Styles */
        .container {
            margin-top: 50px;
        }
        .card {
            width: 18rem;
            margin: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card img {
            height: 200px;
            object-fit: cover;
            cursor: pointer;
        }
        .card-deck {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .modal-content {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent */
        }
        .modal-body img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .borrow-history {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .borrow-history ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .borrow-history .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 8px 0;
            font-size: 0.9rem;
            gap: 1rem;
        }
        .borrow-history .list-group-item span {
            white-space: nowrap;
        }
        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 1rem);
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
                    <a class="nav-link" href="<?= base_url('dashboard') ?>">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= session()->get('firstname') ?> <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url('student/my-borrowed-books') ?>">My Borrowed Books</a>
                        <a class="dropdown-item" href="<?= base_url('student/view-profile') ?>">View Profile</a>
                        <a class="dropdown-item" href="<?= base_url('user/logout') ?>">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Student Dashboard</h2>

        <!-- Available Books Section -->
        <h4>Available Books</h4>
        <div class="card-deck">
            <?php foreach($books as $book): ?>
                <div class="card">
                    <img src="<?= base_url('uploads/books/' . esc($book['photo'])) ?>" 
                         class="card-img-top book-image" 
                         alt="<?= esc($book['title']) ?>" 
                         data-id="<?= esc($book['book_id']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($book['title']) ?></h5>
                        <p class="card-text">
                            <strong>Author:</strong> <?= esc($book['author']) ?><br>
                            <strong>ISBN:</strong> <?= esc($book['isbn']) ?><br>
                            <strong>Published:</strong> <?= esc($book['published_date']) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Book Details Modal -->
    <div class="modal fade" id="bookDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="bookImage" alt="Book Photo">
                    <p id="bookDetails"></p>
                    <button class="btn btn-success borrow-btn">Borrow</button>
                    <hr>
                    <h6>Borrow History</h6>
                    <div class="borrow-history">
                        <ul id="borrowHistory" class="list-group"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery, Bootstrap JS, SweetAlert JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).on('click', '.book-image', function() {
            const bookId = $(this).data('id');
            // Fetch book details and history
            $.ajax({
                url: '<?= base_url("student/get_book_details") ?>',
                type: 'GET',
                data: { book_id: bookId },
                success: function(response) {
                    const book = response.book;
                    const history = response.history || [];

                    $('#bookTitle').text(book.title);
                    $('#bookImage').attr('src', '<?= base_url('uploads/books/') ?>' + book.photo)
                                   .attr('data-id', book.book_id);
                    $('#bookDetails').html(
                        `<strong>Author:</strong> ${book.author}<br>
                         <strong>ISBN:</strong> ${book.isbn}<br>
                         <strong>Published:</strong> ${book.published_date}<br>
                         <strong>Description:</strong> ${book.description}`
                    );

                    $('#borrowHistory').empty();
                    if (history.length > 0) {
                        history.forEach(item => {
                            const user = item.user || 'Unknown User';
                            const date = item.date || 'Unknown Date';
                            $('#borrowHistory').append(
                                `<li class="list-group-item">
                                    <span class="user-name">${user}</span>
                                    <span class="borrow-date">${date}</span>
                                </li>`
                            );
                        });
                    } else {
                        $('#borrowHistory').append('<li class="list-group-item">No borrow history available.</li>');
                    }

                    $('#bookDetailsModal').modal('show');
                },
                error: function() {
                    Swal.fire('Error', 'Failed to fetch book details.', 'error');
                }
            });
        });

        $(document).on('click', '.borrow-btn', function() {
            const bookId = $('#bookDetailsModal').find('#bookImage').data('id');
            Swal.fire({
                title: 'Confirm Borrow',
                text: "Do you want to borrow this book?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, borrow it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url("transaction/borrow") ?>',
                        type: 'POST',
                        data: { book_id: bookId, status: 'pending' },
                        beforeSend: function() {
                            Swal.fire({ title: 'Processing...', text: 'Please wait.', showConfirmButton: false, allowOutsideClick: false });
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire('Success!', response.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Error!', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'An error occurred while processing the request.', 'error');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
