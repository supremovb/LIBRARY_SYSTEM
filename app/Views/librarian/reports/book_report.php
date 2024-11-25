<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .report-container {
            width: 100%;
            margin: 20px auto;
            text-align: center;
        }

        h1 {
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        td {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="report-container">
        <h1>Book Report</h1>
        <table>
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Published Date</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= $book['book_id'] ?></td>
                        <td><?= $book['title'] ?></td>
                        <td><?= $book['description'] ?></td>
                        <td><?= $book['author'] ?></td>
                        <td><?= $book['isbn'] ?></td>
                        <td><?= date('m/d/Y', strtotime($book['published_date'])) ?></td>
                        <td><?= $book['quantity'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>