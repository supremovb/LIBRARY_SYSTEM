<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve or Reject Transactions</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons CDN for Icons -->
    <link href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            word-wrap: break-word;
        }

        .table th {
            background-color: #007BFF;
            color: white;
        }

        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table td:nth-child(3) {
            max-width: 200px;
        }

        .btn {
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn:hover {
            opacity: 0.8;
        }

        form input[type="date"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        form button {
            margin-left: 10px;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .actions form {
            margin-right: 10px;
        }

        /* Added search input style */
        #searchInput {
            margin-bottom: 20px;
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 20px;
        }

        /* Icon styles */
        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        #searchInputWrapper {
            position: relative;
        }
    </style>
</head>

<body>
    <!-- Include the Navbar -->
    <?= $this->include('layout/navbar'); ?>

    <div class="container mt-5">
    <h2><i class="bx bx-bookmark"></i> Pending Borrowed Books</h2>


        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert"><?= session()->getFlashdata('message') ?></div>
        <?php endif; ?>

        <!-- Search Box -->
        <div id="searchInputWrapper" style="position: relative;">
            <i class="uil uil-search search-icon" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);"></i>
            <input type="text" id="searchInput" class="form-control" placeholder="Search by Book Title or Borrower" style="padding-left: 30px;">
        </div>


        <div class="d-flex justify-content-between mb-3">
            <!-- Approve All Button -->
            <form id="approveAllForm" action="<?= site_url('admin/approveAllTransactions') ?>" method="POST">
                <input type="date" name="due_date" id="approveAllDate" required>
                <button type="button" class="btn btn-success" id="approveAllBtn">
                    <i class="uil uil-check-circle"></i> Approve All
                </button>
            </form>
            <!-- Reject All Button -->
            <form id="rejectAllForm" action="<?= site_url('admin/rejectAllTransactions') ?>" method="POST">
                <input type="hidden" name="action" value="reject_all">
                <button type="button" class="btn btn-danger" id="rejectAllBtn">
                    <i class="uil uil-times-circle"></i> Reject All
                </button>
            </form>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Borrower</th>
                    <th>Book Title</th>
                    <th>Borrowed Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="transactionTable">
                <?php if (!empty($pendingTransactions)): ?>
                    <?php foreach ($pendingTransactions as $transaction): ?>
                        <tr>
                            <td><?= esc($transaction->transaction_id) ?></td>
                            <td><?= esc($transaction->firstname) . ' ' . esc($transaction->lastname) ?></td>
                            <td><?= esc($transaction->title) ?></td>
                            <td><?= esc($transaction->borrow_date) ?></td>
                            <td><?= esc($transaction->status) ?></td>
                            <td class="actions">
                                <form action="<?= site_url('admin/approveTransaction/' . $transaction->transaction_id) ?>" method="POST">
                                    <input type="date" name="due_date" value="<?= esc($transaction->due_date) ?>" required>
                                    <button type="submit" class="btn btn-success"><i class="uil uil-check-circle"></i> Approve</button>
                                </form>
                                <a href="<?= site_url('admin/rejectTransaction/' . $transaction->transaction_id) ?>" class="btn btn-danger"><i class="uil uil-times-circle"></i> Reject</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No pending transactions.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- jQuery, Bootstrap JS, SweetAlert JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
    // Approve All Transactions with SweetAlert
    document.getElementById('approveAllBtn').addEventListener('click', function () {
        const dueDate = document.getElementById('approveAllDate').value;
        if (!dueDate) {
            Swal.fire('Error', 'Please select a due date.', 'error');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to approve all pending transactions.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve all!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approveAllForm').submit();
            }
        });
    });

    // Reject All Transactions with SweetAlert
    document.getElementById('rejectAllBtn').addEventListener('click', function () {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to reject all pending transactions.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, reject all!',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the reject all form
            document.getElementById('rejectAllForm').submit();
        }
    });
});

// Search functionality for the table
$('#searchInput').on('input', function () {
            var value = $(this).val().toLowerCase();
            $("#transactionTable tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

</script>

    <script>
        // Add SweetAlert for better user experience on approve/reject actions
        $('.btn-danger').on('click', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to reject this transaction?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    </script>
</body>

</html>
