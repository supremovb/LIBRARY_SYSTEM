<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style for card container to adapt to content height */
        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;  /* Removed fixed height constraint */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Hover effect for card */
        .card:hover {
            transform: translateY(-10px);  /* Slight lift on hover */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);  /* Adding shadow for emphasis */
        }

        /* Image size adjustment */
        .card-img-top {
            height: 200px; /* Reduce image height to free up space */
            object-fit: cover;
            width: 100%;
            cursor: pointer;
        }

        /* Styling for card body */
        .card-body {
            flex-grow: 1;
            padding: 10px;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: bold;
            text-overflow: ellipsis; /* Ensures long titles don't overflow */
            white-space: nowrap;     /* Keeps title on one line */
            overflow: hidden;
        }

        .card-text {
            font-size: 0.9rem;
            margin: 5px 0;
        }

        /* Modal styling */
        .modal-content {
            background: rgba(255, 255, 255, 0.9);
        }

        .modal-body {
            text-align: center;
        }

        .modal-body img {
            max-width: 80%;
            margin: 0 auto;
        }

        .modal-title {
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- Include the Navbar -->
    <?= $this->include('layout/navbar'); ?>

    <div class="container mt-5">
    <h2 class="text-center mb-4"><i class="bx bx-book"></i> All Books</h2>
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
                                <p class="card-text">Category: <?= $book['category_name'] ?></p>
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
                                    <p><strong>Category:</strong> <?= $book['category_name'] ?></p>
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
