<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Borrowed Books - Library System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .card {
            width: 18rem;
            margin: 1rem;
            height: 400px; /* Increased height for more space */
            display: flex;
            flex-direction: column;
        }
        .card-body {
            overflow: hidden;
            flex-grow: 1;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .grid-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .card-img-top {
            height: 150px;
            object-fit: cover;
        }
        .card-body p {
            font-size: 0.9rem;
        }
        .return-btn {
            margin-top: auto; /* Push the button to the bottom of the card */
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
                <!-- Dropdown for User's First Name -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= session()->get('firstname') ?> <!-- Display first name -->
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

    <!-- Borrowed Books in Card Style -->
    <div class="grid-container">
        <?php foreach ($borrowed as $transaction): ?>
            <div class="card">
                <!-- Display book photo -->
                <img src="<?= base_url('uploads/books/' . esc($transaction['photo'])) ?>" class="card-img-top" alt="<?= esc($transaction['title']) ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= esc($transaction['title']) ?></h5>
                    <p class="card-text">
                        <strong>Author:</strong> <?= esc($transaction['author']) ?><br>
                        <strong>ISBN:</strong> <?= esc($transaction['isbn']) ?><br>
                        <strong>Published Date:</strong> <?= esc($transaction['published_date']) ?><br>
                        <strong>Borrowed On:</strong> <?= esc($transaction['borrow_date']) ?><br>
                        <strong>Due Date:</strong> <?= esc($transaction['due_date']) ?> <!-- Display the due date -->
                    </p>
                    <button class="btn btn-warning btn-sm return-btn" data-id="<?= esc($transaction['transaction_id']) ?>">Return</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).on('click', '.return-btn', function(){
            var transaction_id = $(this).data('id');
            Swal.fire({
                title: 'Confirm Return',
                text: "Do you want to return this book?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, return it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/library_system/index.php/transaction/returnBook',
                        type: 'POST',
                        data: {transaction_id: transaction_id},
                        beforeSend: function() {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Please wait.',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });
                        },
                        success: function(response){
                            if(response.status == 'success'){
                                Swal.fire(
                                    'Success!',
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
    </script>
</body>
</html>
