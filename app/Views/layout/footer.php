<head>
    <style>
        body {
            background-color: #f4f7fc;
            min-height: 100vh;
            /* Ensures body takes up at least the full height of the viewport */
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* Center content vertically */
            align-items: center;
            /* Center content horizontally */
            margin: 0;
            /* Ensure no default margin */
        }

        .content {
            flex: 1;
            /* Allow the main content area to take up the remaining space */
            display: flex;
            justify-content: center;
            /* Center content horizontally */
            align-items: center;
            /* Center content vertically */
        }

        .footer {
            position: absolute;
            /* Position footer at the bottom of the page */
            bottom: 0;
            width: 100%;
            /* Ensure footer takes full width */
            padding: 10px 0;
            /* Adjust padding for footer */
            background-color: #f8f9fa;
            /* A subtle background for the footer */
            text-align: center;
            /* Center the text in the footer */
        }


        .container {
            display: flex;
            justify-content: space-between;
            /* Align items to the left and right */
            align-items: center;
            width: 100%;
            /* Full width for proper alignment */
        }

        .text-muted {
            color: #6c757d;
            font-size: 1rem;
            /* Standard font size */
        }

        .footer-nav {
            display: flex;
            justify-content: center;
            /* Center the footer nav horizontally */
            align-items: center;
        }

        .footer-nav a {
            color: #007bff;
            text-decoration: none;
            font-size: 1rem;
            /* Standard font size */
            margin: 0 15px;
            /* Add space between the links */
        }

        .footer-nav a:hover {
            text-decoration: underline;
        }

        .footer-right {
            text-align: right;
            /* Align the text to the right */
        }
    </style>
</head>


<div class="content">

</div>


<footer class="footer bg-light">
    <div class="container">
        <span class="text-muted footer-right">Library System &copy; <?php echo date("Y"); ?>. All rights reserved.</span>
        <div class="footer-nav">

            <a href="<?php echo base_url('about-us'); ?>">About Us</a>
        </div>
    </div>
</footer>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>