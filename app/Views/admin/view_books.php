<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style for card container to maintain same height for all cards */
        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;  /* Ensure all cards have the same height */
            height: 400px;
        }

        /* Ensure the image inside the card has the same size */
        .card-img-top {
            height: 250px;
            object-fit: cover;
            width: 100%;
            cursor: pointer; /* Add cursor pointer on image */
        }

        /* Optional: Add some styling for the card text */
        .card-body {
            flex-grow: 1;
            padding: 10px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card-text {
            font-size: 1rem;
            margin: 5px 0;
        }

        /* Modal styling */
        .modal-content {
            background: rgba(255, 255, 255, 0.9); /* Add slight transparency */
        }

        /* Centering the image and title in the modal */
        .modal-body {
            text-align: center;  /* Centers the content inside the modal */
        }

        .modal-body img {
            max-width: 80%;  /* Adjusts the image size inside the modal */
            margin: 0 auto;  /* Centers the image */
        }

        .modal-title {
            text-align: center;  /* Center the title */
        }
    </style>
</head>

<body>

    <!-- Include the Navbar -->
    <?= $this->include('layout/navbar'); ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4">All Books</h2>
        <div class="row">
            <!-- Loop through books and display each book as a card -->
            <?php if (!empty($books) && is_array($books)): ?>
                <?php foreach ($books as $book): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <!-- Book image with data-toggle to trigger modal -->
                            <img src="<?= base_url('uploads/books/' . $book['photo']) ?>" class="card-img-top" alt="<?= $book['title'] ?>" data-toggle="modal" data-target="#bookModal<?= $book['book_id'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $book['title'] ?></h5>
                                <p class="card-text">Author: <?= $book['author'] ?></p>
                                <p class="card-text">ISBN: <?= $book['isbn'] ?></p>
                                <p class="card-text">Status: <?= ucfirst($book['status']) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for Book Details -->
                    <div class="modal fade" id="bookModal<?= $book['book_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="bookModalLabel<?= $book['book_id'] ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="bookModalLabel<?= $book['book_id'] ?>"><?= $book['title'] ?></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Book Details -->
                                    <img src="<?= base_url('uploads/books/' . $book['photo']) ?>" class="img-fluid mb-3" alt="<?= $book['title'] ?>">
                                    <p><strong>Author:</strong> <?= $book['author'] ?></p>
                                    <p><strong>ISBN:</strong> <?= $book['isbn'] ?></p>
                                    <p><strong>Status:</strong> <?= ucfirst($book['status']) ?></p>
                                    <p><strong>Description:</strong> <?= nl2br($book['description']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <p>No books available.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
