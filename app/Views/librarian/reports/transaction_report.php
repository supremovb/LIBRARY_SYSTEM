<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Transaction Report</h1>
    <table>
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Book Title</th>
                <th>User Name</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Due Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= $transaction['transaction_id'] ?></td>
                    <td><?= $transaction['book_title'] ?></td>
                    <td><?= $transaction['user_name'] ?></td>
                    <td><?= $transaction['borrow_date'] ?></td>
                    <td><?= $transaction['return_date'] ?></td>
                    <td><?= $transaction['due_date'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>