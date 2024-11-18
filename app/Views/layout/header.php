<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System</title>
    <!-- Bootstrap 4.5.0 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <!-- Font Awesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Header styling */
        .header {
            display: flex;
            justify-content: space-between; /* Align the logo to the left and right */
            padding: 10px 20px; /* Reduced padding for a smaller look */
            background-color: #C1D3FE; /* Caramel color background for header */
            position: fixed; /* Fix the header at the top */
            top: 0;
            width: 100%; /* Make the header span across the full width of the page */
            z-index: 1000; /* Ensure the header stays above other content */
        }

        .header .logo img {
            max-width: 120px; /* Reduced logo size */
        }

        /* Add padding to the top of the page content to make space for the fixed header */
        body {
            padding-top: 70px; /* Adjusted to match the smaller header */
            margin: 0; /* Remove default body margin */
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <div class="logo">
            <!-- Library Logo from local assets wrapped in a clickable link -->
            <a href="https://stdominiccollege.edu.ph" target="_blank">
                <img src="/library_system/assets/logo.png" alt="Library Logo" class="img-fluid">
            </a>
        </div>
    </div>

</body>
</html>
