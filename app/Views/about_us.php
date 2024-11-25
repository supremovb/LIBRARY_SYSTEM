<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About the Creators</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            text-align: center;
        }

        .card-deck {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            
        }

        .creator-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            transition: transform 0.3s ease;
        }

        .creator-card:hover {
            transform: scale(1.05);
        }

        .creator-card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .creator-card h3 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 10px;
        }

        .creator-card p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 20px;
        }

        .creator-card .social-icons a {
            margin: 0 10px;
            font-size: 24px;
            color: #007bff;
            transition: color 0.3s ease;
        }

        .creator-card .social-icons a:hover {
            color: #0056b3;
        }

        
        .modal-content {
            border-radius: 10px;
        }

        .modal-body img {
            width: 100%;
            height: auto;
            max-width: 300px;
            
            border-radius: 50%;
            
            object-fit: cover;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card-deck">

            <div class="card creator-card" data-toggle="modal" data-target="#modalCreator1">
                <img src="/library_system/assets/velasquez.jpg" alt="Creator 1">
                <h3>Anabelle Velasquez</h3>
                <p>Year: 2024 | Motto: "Never stop learning."</p>
                <div class="social-icons">
                    <a href="https://www.facebook.com/chastinitaprims/" target="_blank"><i class="fab fa-facebook"></i></a>
                    <a href="https://github.com/supremovb" target="_blank"><i class="fab fa-github"></i></a>
                </div>
            </div>


            <div class="card creator-card" data-toggle="modal" data-target="#modalCreator2">
                <img src="/library_system/assets/alabado.jpg" alt="Creator 2">
                <h3>Arriane Alabado</h3>
                <p>Year: 2023 | Motto: "Strive for excellence."</p>
                <div class="social-icons">
                    <a href="https://www.facebook.com/profile.php?id=61564256037057" target="_blank"><i class="fab fa-facebook"></i></a>
                    <a href="https://github.com/johndoe" target="_blank"><i class="fab fa-github"></i></a>
                </div>
            </div>


            <div class="card creator-card" data-toggle="modal" data-target="#modalCreator3">
                <img src="/library_system/assets/monton.jpg" alt="Creator 3">
                <h3>Jirielle Monton</h3>
                <p>Year: 2025 | Motto: "Code with passion."</p>
                <div class="social-icons">
                    <a href="https://www.facebook.com/mynameisjirielle" target="_blank"><i class="fab fa-facebook"></i></a>
                    <a href="https://github.com/janesmith" target="_blank"><i class="fab fa-github"></i></a>
                </div>
            </div>


            <div class="card creator-card" data-toggle="modal" data-target="#modalCreator4">
                <img src="/library_system/assets/paul.jpg" alt="Creator 4">
                <h3>Paul Andrei Roncesvalles</h3>
                <p>Year: 2026 | Motto: "Innovate and inspire."</p>
                <div class="social-icons">
                    <a href="https://www.facebook.com/paulandrei17" target="_blank"><i class="fab fa-facebook"></i></a>
                    <a href="https://github.com/markjohnson" target="_blank"><i class="fab fa-github"></i></a>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="modalCreator1" tabindex="-1" role="dialog" aria-labelledby="modalCreator1Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreator1Label">Anabelle Velasquez</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="/library_system/assets/velasquez.jpg" alt="Creator 1" class="img-fluid rounded-circle">
                    <p>Year: 2024 | Motto: "Never stop learning."</p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/chastinitaprims/" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="https://github.com/supremovb" target="_blank"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalCreator2" tabindex="-1" role="dialog" aria-labelledby="modalCreator2Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreator2Label">Arriane Alabado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="/library_system/assets/alabado.jpg" alt="Creator 2" class="img-fluid rounded-circle">
                    <p>Year: 2023 | Motto: "Strive for excellence."</p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/profile.php?id=61564256037057" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="https://github.com/johndoe" target="_blank"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalCreator3" tabindex="-1" role="dialog" aria-labelledby="modalCreator3Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreator3Label">Jirielle Monton</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="/library_system/assets/monton.jpg" alt="Creator 3" class="img-fluid rounded-circle">
                    <p>Year: 2025 | Motto: "Code with passion."</p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/mynameisjirielle" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="https://github.com/janesmith" target="_blank"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalCreator4" tabindex="-1" role="dialog" aria-labelledby="modalCreator4Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreator4Label">Paul Andrei Roncesvalles</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="/library_system/assets/paul.jpg" alt="Creator 4" class="img-fluid rounded-circle">
                    <p>Year: 2026 | Motto: "Innovate and inspire."</p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/paulandrei17" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="https://github.com/markjohnson" target="_blank"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>

</html>