<head>
    <style>
        body {
            background-color: #f4f7fc;
            min-height: 100vh;

            display: flex;
            flex-direction: column;
            justify-content: center;

            align-items: center;

            margin: 0;

        }

        .content {
            flex: 1;

            display: flex;
            justify-content: center;

            align-items: center;

        }

        .footer {
            position: absolute;

            bottom: 0;
            width: 100%;

            padding: 10px 0;

            background-color: #f8f9fa;

            text-align: center;

        }


        .container {
            display: flex;
            justify-content: space-between;

            align-items: center;
            width: 100%;

        }

        .text-muted {
            color: #6c757d;
            font-size: 1rem;

        }

        .footer-nav {
            display: flex;
            justify-content: center;

            align-items: center;
        }

        .footer-nav a {
            color: #007bff;
            text-decoration: none;
            font-size: 1rem;

            margin: 0 15px;

        }

        .footer-nav a:hover {
            text-decoration: underline;
        }

        .footer-right {
            text-align: right;

        }
    </style>
</head>


<div class="content">

</div>


<footer class="footer bg-light">
    <div class="container">
        <span class="text-muted footer-right">Library System &copy; <?php echo date("Y"); ?>. All rights reserved.</span>
        <div class="footer-nav">

            <a href="<?php echo base_url('about-us'); ?>">Developers</a>
        </div>
    </div>
</footer>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>