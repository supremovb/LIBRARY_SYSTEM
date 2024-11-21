<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Library System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <!-- Box Icons -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">
    <style>
        /* Updated Custom Styles */
        .container {
            margin-top: 50px;
        }
        .card {
            width: 100%; /* Ensure consistent sizing */
            margin: 0; /* Remove margin to avoid extra spacing */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            border-radius: 8px; /* Rounded corners */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition for hover effect */
        }

        .card:hover {
            transform: scale(1.05); /* Slightly increase the card size */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* More prominent shadow */
        }

        .card img {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .card-deck {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 cards per row */
            gap: 1.5rem; /* Space between cards */
        }
        .card-body {
            padding: 1rem; /* Adds padding inside card content */
            text-align: center; /* Centers text for better readability */
        }
        .card-title {
            font-size: 1.25rem; /* Slightly larger font for titles */
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .card-text {
            font-size: 0.9rem; /* Makes the text smaller to fit details */
            color: #555; /* Subtle color for better contrast */
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
    <a class="navbar-brand" href="#">
        <i class="bx bx-book-reader"></i> Library System
    </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('dashboard') ?>"><i class="bx bx-home"></i> Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= session()->get('firstname') ?> <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url('student/my-borrowed-books') ?>"><i class="bx bx-book"></i> My Borrowed Books</a>
                        <a class="dropdown-item" href="<?= base_url('student/view-profile') ?>"><i class="bx bx-user"></i> View Profile</a>
                        <a class="dropdown-item" href="<?= base_url('user/logout') ?>"><i class="bx bx-log-out"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
    <h2><i class="bx bx-user-circle"></i> Student Dashboard</h2>

        <!-- Search Bar Section -->
        <div class="form-group">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="bx bx-search"></i></span>
        </div>
        <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search by name or ISBN" aria-label="Search Categories">
    </div>
</div>


        <!-- Available Books Section -->
        <h4><i class="bx bx-book"></i> Available Books</h4>
        <div class="card-deck" id="booksList">
            <?php foreach($books as $book): ?>
                <div class="card category-row" data-title="<?= esc(strtolower($book['title'])) ?>" data-author="<?= esc(strtolower($book['author'])) ?>" data-isbn="<?= esc(strtolower($book['isbn'])) ?>">
                    <img src="<?= base_url('uploads/books/' . esc($book['photo'])) ?>" 
                         class="card-img-top book-image" 
                         alt="<?= esc($book['title']) ?>" 
                         data-id="<?= esc($book['book_id']) ?>">
                    <div class="card-body">
                        <h5 class="card-title category-name"><?= esc($book['title']) ?></h5>
                        <p class="card-text category-description">
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
                    <button class="btn btn-success borrow-btn"><i class="bx bx-bookmark"></i> Borrow</button>
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
        // Search functionality for the books
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

        // When a book is clicked to view its details
$(document).on('click', '.book-image', function() {
    const bookId = $(this).data('id');
    $.ajax({
        url: '<?= base_url("student/get_book_details") ?>',
        type: 'GET',
        data: { book_id: bookId },
        success: function(response) {
            const book = response.book;
            const history = response.history || [];

            // Display book details in modal
            $('#bookTitle').text(book.title);
            $('#bookImage').attr('src', '<?= base_url('uploads/books/') ?>' + book.photo)
                           .attr('data-id', book.book_id);
            $('#bookDetails').html(
                `<strong>Author:</strong> ${book.author}<br>
                 <strong>ISBN:</strong> ${book.isbn}<br>
                 <strong>Published:</strong> ${book.published_date}<br>
                 <strong>Description:</strong> ${book.description}`
            );

            // Display borrow history
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

            // Fetch and display book recommendations based on the same category
            $.ajax({
                url: '<?= base_url("student/get_recommendations/") ?>' + book.book_id,
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        const recommendedBooks = response.books;
                        let recommendationsHtml = '<h6>Recommended Books</h6><div class="card-deck">';

                        recommendedBooks.forEach(function(recommendedBook) {
                            recommendationsHtml += `
                                <div class="card category-row">
                                    <img src="<?= base_url('uploads/books/') ?>${recommendedBook.photo}" 
                                         class="card-img-top book-image" 
                                         alt="${recommendedBook.title}">
                                    <div class="card-body">
                                        <h5 class="card-title">${recommendedBook.title}</h5>
                                        <p class="card-text">
                                            <strong>Author:</strong> ${recommendedBook.author}<br>
                                            <strong>ISBN:</strong> ${recommendedBook.isbn}
                                        </p>
                                    </div>
                                </div>
                            `;
                        });
                        recommendationsHtml += '</div>';
                        $('#recommendations').html(recommendationsHtml); // Update the recommendations section
                    } else {
                        $('#recommendations').html('<p>No recommendations available at the moment.</p>');
                    }
                },
                error: function() {
                    $('#recommendations').html('<p>Failed to fetch recommendations.</p>');
                }
            });

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
        confirmButtonText: 'Yes, Borrow',
        cancelButtonText: 'No, Cancel'
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("student/borrow_book") ?>',
                type: 'POST',
                data: { book_id: bookId },
                success: function(response) {
                    if (response.status === 'success') { // Check for 'status' instead of 'success'
                        Swal.fire('Success', response.message, 'success').then(() => {
                            location.reload(); // Automatically refresh the page
                        });
                        $('#bookDetailsModal').modal('hide');
                    } else {
                        Swal.fire('Error', response.message, 'error'); // Display the error message from response
                    }
                },
                error: function() {
                    Swal.fire('Error', 'An error occurred while borrowing the book.', 'error');
                }
            });
        }
    });
});


    </script>
</body>
</html>
