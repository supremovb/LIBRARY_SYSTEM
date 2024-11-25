<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Library System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">
    <style>
        
        .container {
            margin-top: 50px;
        }

        .card {
            width: 100%;
            
            margin: 0;
            
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            
            border-radius: 8px;
            
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            
        }

        .card:hover {
            transform: scale(1.05);
            
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            
        }

        .card img {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .card-deck {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            
            gap: 1.5rem;
            
        }

        .card-body {
            padding: 1rem;
            
            text-align: center;
            
        }

        .card-title {
            font-size: 1.25rem;
            
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .card-text {
            font-size: 0.9rem;
            
            color: #555;
            
        }

        .modal-content {
            background-color: rgba(255, 255, 255, 0.9);
            
        }

        .modal-body img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .borrow-history {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .borrow-history ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .borrow-history .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 8px 0;
            font-size: 0.9rem;
            gap: 1rem;
        }

        .borrow-history .list-group-item span {
            white-space: nowrap;
        }

        .dropdown-menu {
            max-height: 400px;
            overflow-y: auto;
            width: 300px;
        }

        .dropdown-menu ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .list-group-item {
            padding: 10px;
            font-size: 0.9rem;
        }

        .stars i {
            font-size: 24px;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s;
        }

        .stars i.hover {
            color: gold;
        }

        .stars i.selected {
            color: gold;
        }

        
        @media (max-width: 768px) {
            .card-deck {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .card-deck {
                grid-template-columns: 1fr;
            }
        }

        
        #recommendedBooksList {
            margin-top: 20px;
            
            position: relative;
            top: 20px;
            
        }

        #relatedBooksList {
            margin-top: 20px;
            
            position: relative;
            top: 20px;
            
        }

        
        #backToTopBtn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 15px;
            font-size: 24px;
            display: none;
            
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #backToTopBtn:hover {
            background-color: #0056b3;
        }

        #backToTop {
            transition: transform 0.3s ease;
        }

        #backToTop:hover {
            transform: scale(1.1);
            
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            
        }

        
        #scrollToRecommendedBtn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 15px;
            font-size: 24px;
            display: none;
            
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #scrollToRecommendedBtn:hover {
            background-color: #0056b3;
        }

        #scrollToRecommended {
            transition: transform 0.3s ease;
        }

        #scrollToRecommended:hover {
            transform: scale(1.1);
            
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
            
        }

        #feedbackButton {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #feedbackButton:hover {
            background-color: #0056b3;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <i class="bx bx-book-reader"></i> Library System
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('dashboard') ?>"><i class="bx bx-home"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('student/book-reviews') ?>"><i class="bx bx-book"></i> Book Reviews</a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-bell"></i> Notifications
                        <span class="badge badge-danger" id="notificationCount" style="display: none;"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="notificationDropdown" style="max-height: 400px; overflow-y: auto; width: 300px;">
                        <ul id="notificationList" class="list-group list-group-flush">
                            
                        </ul>
                    </div>

                </li>


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-user"></i> <?= session()->get('firstname') ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url('student/my-borrowed-books') ?>"><i class="bx bx-book"></i> My Borrowed Books</a>
                        <a class="dropdown-item" href="<?= base_url('student/view-profile') ?>"><i class="bx bx-user"></i> View Profile</a>
                        <a class="dropdown-item" href="<?= base_url('student/view-history') ?>"><i class="bx bx-history"></i> History</a>
                        <a class="dropdown-item" href="<?= base_url('user/logout') ?>"><i class="bx bx-log-out"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2><i class="bx bx-user-circle"></i> Student Dashboard</h2>


        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                </div>
                <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search by name or ISBN" aria-label="Search Categories">
            </div>
        </div>

        <div class="form-group">
            <label for="categoryFilter"><strong>Filter by Category:</strong></label>
            <select id="categoryFilter" class="form-control">
                <option value="">All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= esc($category['category_id']) ?>">
                        <?= esc($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


        <h4><i class="bx bx-book"></i> Available Books</h4>
        <div class="card-deck" id="booksList">
            <?php foreach ($books as $book): ?>
                <div class="card category-row" data-category-id="<?= esc($book['category_id']) ?>" data-title="<?= esc(strtolower($book['title'])) ?>" data-author="<?= esc(strtolower($book['author'])) ?>" data-isbn="<?= esc(strtolower($book['isbn'])) ?>">
                    <img src="<?= base_url('uploads/books/' . esc($book['photo'])) ?>"
                        class="card-img-top book-image"
                        alt="<?= esc($book['title']) ?>"
                        data-id="<?= esc($book['book_id']) ?>">
                    <div class="card-body">
                        <h5 class="card-title category-name"><?= esc($book['title']) ?></h5>
                        <p class="card-text category-description">
                            <strong>Author:</strong> <?= esc($book['author']) ?><br>
                            <strong>ISBN:</strong> <?= esc($book['isbn']) ?><br>
                            <strong>Published:</strong> <?= esc($book['published_date']) ?>
                        </p>
                        <div class="stars">
                            <i class="bx bxs-star" data-rating="1"></i>
                            <i class="bx bxs-star" data-rating="2"></i>
                            <i class="bx bxs-star" data-rating="3"></i>
                            <i class="bx bxs-star" data-rating="4"></i>
                            <i class="bx bxs-star" data-rating="5"></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        
        <div id="recommendedBooksList">
            <h4><i class="bx bx-bookmark"></i> Recommended Books Based on Rating</h4>
            <div class="card-deck">
                <?php foreach ($recommendedBooks as $book): ?>
                    
                    <?php if (isset($book->avg_rating) && $book->avg_rating >= 3): ?>
                        <div class="card">
                            <img src="<?= base_url('uploads/books/' . esc($book->photo)) ?>" class="card-img-top" alt="<?= esc($book->title) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= esc($book->title) ?></h5>
                                <p class="card-text"><?= esc($book->author) ?></p>
                                <p class="card-text"><strong>Rating:</strong> <?= esc($book->avg_rating ?? 'No rating yet') ?> Stars</p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="relatedBooksList">
            <h4><i class="bx bx-bookmark"></i> Related Books Based on Borrower History</h4>
            <div class="card-deck">
                <?php foreach ($relatedBooks as $book): ?>
                    <div class="card">
                        <img src="<?= base_url('uploads/books/' . esc($book->photo)) ?>" class="card-img-top" alt="<?= esc($book->title) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($book->title) ?></h5>
                            <p class="card-text"><?= esc($book->author) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <div class="modal fade" id="bookDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bookTitle"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="bookImage" alt="Book Photo">
                        <p id="bookDetails"></p>
                        <button class="btn btn-success borrow-btn"><i class="bx bx-bookmark"></i> Borrow</button>
                        <hr>
                        <h6>Borrow History</h6>
                        <div class="borrow-history">
                            <ul id="borrowHistory" class="list-group"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewModalLabel">Submit Your Review</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Rating: <span id="selectedRating"></span></p>

                        <textarea id="reviewText" class="form-control" placeholder="Write your review here..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="submitReviewBtn">Submit Review</button>
                    </div>
                </div>
            </div>
        </div>

        
        <a href="#" id="backToTop" class="btn" style="display: none; position: fixed; bottom: 30px; right: 30px; z-index: 1000; width: 50px; height: 50px; background: linear-gradient(135deg, #4e73df, #1a5ab1); color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); font-size: 24px; transition: all 0.3s ease-in-out; border: none;">
            <i class="bx bx-up-arrow-alt"></i>
        </a>

        
        <button id="scrollToRecommended" class="btn" style="display: none; position: fixed; bottom: 30px; right: 30px; z-index: 1000; width: 50px; height: 50px; background: linear-gradient(135deg, #4e73df, #1a5ab1); color: white; border-radius: 50%; display: flex; justify-content: center; align-items: center; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); font-size: 24px; transition: all 0.3s ease-in-out; border: none;">
            <i class="bx bx-down-arrow-alt"></i>
        </button>

        
        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=supremopkv@gmail.com"
            id="feedbackButton"
            class="btn btn-primary"
            target="_blank"
            rel="noopener">
            <i class="fas fa-envelope"></i> Report Issues
        </a>


        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <script>
            
            window.onscroll = function() {
                
                var scrollPosition = document.documentElement.scrollTop || document.body.scrollTop;
                var scrollToRecommended = document.getElementById("scrollToRecommended");

                
                if (scrollPosition === 0) {
                    scrollToRecommended.style.display = "block";
                } else {
                    scrollToRecommended.style.display = "none";
                }
            };

            
            document.getElementById("scrollToRecommended").addEventListener("click", function() {
                
                var recommendedBooksSection = document.getElementById("recommendedBooksList");

                
                recommendedBooksSection.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        </script>


        <script>
            
            window.addEventListener("scroll", function() {
                let button = document.getElementById("backToTop");
                if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                    button.style.display = "flex"; 
                } else {
                    button.style.display = "none"; 
                }
            });

            
            document.getElementById("backToTop").addEventListener("click", function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        </script>


        <script>
            $(document).ready(function() {
                function updateNotifications() {
                    $.ajax({
                        url: '<?= base_url('NotificationController/updateNotifications') ?>',
                        method: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            
                            const notificationList = $("#notificationList");
                            notificationList.empty();

                            response.notifications.forEach(notification => {
                                const listItem = `
                        <li class="list-group-item">
                            ${notification.message}
                            <small class="text-muted float-right">${new Date(notification.created_at).toLocaleString()}</small>
                        </li>`;
                                notificationList.append(listItem);
                            });

                            
                            const notificationCount = $("#notificationCount");
                            if (response.unread_count > 0) {
                                notificationCount.text(response.unread_count).show();
                            } else {
                                notificationCount.hide();
                            }
                        },
                        error: function() {
                            console.error("Failed to update notifications.");
                        }
                    });
                }

                
                $("#notificationDropdown").on('click', function() {
                    updateNotifications();
                });

                
                updateNotifications();
            });

            $(document).ready(function() {
                $('#categoryFilter').on('change', function() {
                    var selectedCategory = $(this).val();
                    $('.category-row').each(function() {
                        var categoryId = $(this).data('category-id');
                        if (!selectedCategory || categoryId == selectedCategory) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                
                function fetchNotificationCount() {
                    $.ajax({
                        url: '<?= base_url("NotificationController/unreadCount") ?>',
                        method: 'GET',
                        success: function(response) {
                            const count = response.unread_count || 0;
                            const notificationBadge = $('#notificationCount');
                            if (count > 0) {
                                notificationBadge.text(count).show();
                            } else {
                                notificationBadge.hide();
                            }
                        },
                        error: function() {
                            console.error("Failed to fetch notification count.");
                        }
                    });
                }

                
                function fetchNotifications() {
                    $.ajax({
                        url: '<?= base_url("NotificationController/fetchNotifications") ?>',
                        method: 'GET',
                        success: function(notifications) {
                            const notificationList = $('#notificationList');
                            notificationList.empty();

                            if (notifications.length > 0) {
                                notifications.forEach(notification => {
                                    const listItem = `
                            <li class="list-group-item">
                                <strong>${notification.type}</strong>: ${notification.message}
                                <small class="text-muted d-block">${new Date(notification.created_at).toLocaleString()}</small>
                            </li>`;
                                    notificationList.append(listItem);
                                });
                            } else {
                                notificationList.append('<li class="list-group-item text-center">No notifications found</li>');
                            }
                        },
                        error: function() {
                            console.error("Failed to fetch notifications.");
                        }
                    });
                }

                
                $('#notificationDropdown').on('click', function() {
                    fetchNotifications();
                    $.ajax({
                        url: '<?= base_url("NotificationController/markAsRead") ?>',
                        method: 'POST',
                        success: function() {
                            fetchNotificationCount(); 
                        },
                        error: function() {
                            console.error("Failed to mark notifications as read.");
                        }
                    });
                });

                
                fetchNotificationCount();

                
                setInterval(fetchNotificationCount, 30000); 
            });
        </script>


        <script>
            $('#searchInput').on('input', function() {
                var query = $(this).val().toLowerCase();
                $('.category-row').each(function() {
                    var name = $(this).find('.category-name').text().toLowerCase();
                    var description = $(this).find('.category-description').text().toLowerCase();

                    if (name.includes(query) || description.includes(query)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });


            $(document).on('click', '.book-image', function() {
                const bookId = $(this).data('id');
                $.ajax({
                    url: '<?= base_url("student/get_book_details") ?>',
                    type: 'GET',
                    data: {
                        book_id: bookId
                    },
                    success: function(response) {
                        const book = response.book;
                        const history = response.history || [];


                        $('#bookTitle').text(book.title);
                        $('#bookImage').attr('src', '<?= base_url('uploads/books/') ?>' + book.photo)
                            .attr('data-id', book.book_id);
                        $('#bookDetails').html(
                            `<strong>Author:</strong> ${book.author}<br>
                 <strong>ISBN:</strong> ${book.isbn}<br>
                 <strong>Published:</strong> ${book.published_date}<br>
                 <strong>Description:</strong> ${book.description}`
                        );


                        $('#borrowHistory').empty();
                        if (history.length > 0) {
                            history.forEach(item => {
                                const user = item.user || 'Unknown User';
                                const date = item.date || 'Unknown Date';
                                $('#borrowHistory').append(
                                    `<li class="list-group-item">
                            <span class="user-name">${user}</span>
                            <span class="borrow-date">${date}</span>
                        </li>`
                                );
                            });
                        } else {
                            $('#borrowHistory').append('<li class="list-group-item">No borrow history available.</li>');
                        }


                        $.ajax({
                            url: '<?= base_url("student/get_recommendations/") ?>' + book.book_id,
                            type: 'GET',
                            success: function(response) {
                                if (response.status === 'success') {
                                    const recommendedBooks = response.books;
                                    let recommendationsHtml = '<h6>Recommended Books</h6><div class="card-deck">';

                                    recommendedBooks.forEach(function(recommendedBook) {
                                        recommendationsHtml += `
                                <div class="card category-row">
                                    <img src="<?= base_url('uploads/books/') ?>${recommendedBook.photo}" 
                                         class="card-img-top book-image" 
                                         alt="${recommendedBook.title}">
                                    <div class="card-body">
                                        <h5 class="card-title">${recommendedBook.title}</h5>
                                        <p class="card-text">
                                            <strong>Author:</strong> ${recommendedBook.author}<br>
                                            <strong>ISBN:</strong> ${recommendedBook.isbn}
                                        </p>
                                    </div>
                                </div>
                            `;
                                    });
                                    recommendationsHtml += '</div>';
                                    $('#recommendations').html(recommendationsHtml); 
                                } else {
                                    $('#recommendations').html('<p>No recommendations available at the moment.</p>');
                                }
                            },
                            error: function() {
                                $('#recommendations').html('<p>Failed to fetch recommendations.</p>');
                            }
                        });

                        $('#bookDetailsModal').modal('show');
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to fetch book details.', 'error');
                    }
                });
            });


            $(document).on('click', '.borrow-btn', function() {
                const bookId = $('#bookDetailsModal').find('#bookImage').data('id');
                Swal.fire({
                    title: 'Confirm Borrow',
                    text: "Do you want to borrow this book?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Borrow',
                    cancelButtonText: 'No, Cancel'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= base_url("student/borrow_book") ?>',
                            type: 'POST',
                            data: {
                                book_id: bookId
                            },
                            success: function(response) {
                                if (response.status === 'success') { 
                                    Swal.fire('Success', response.message, 'success').then(() => {
                                        location.reload(); 
                                    });
                                    $('#bookDetailsModal').modal('hide');
                                } else {
                                    Swal.fire('Error', response.message, 'error'); 
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'An error occurred while borrowing the book.', 'error');
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.stars i', function() {
                var rating = $(this).data('rating');
                var bookId = $(this).closest('.card').find('.book-image').data('id');

                
                var starIcons = '';
                for (var i = 1; i <= rating; i++) {
                    starIcons += '<i class="fa fa-star text-warning"></i>'; 
                }

                
                $('#selectedRating').html(starIcons);

                
                $('#reviewModal').modal('show');

                
                $('#reviewModal').data('rating', rating);
                $('#reviewModal').data('bookId', bookId);
            });




            $(document).ready(function() {
                
                $(document).on('mouseover', '.stars i', function() {
                    var rating = $(this).data('rating');
                    
                    $(this).siblings().each(function(index) {
                        if (index < rating) {
                            $(this).addClass('hover');
                        } else {
                            $(this).removeClass('hover');
                        }
                    });
                    
                    $(this).addClass('hover');
                });

                
                $(document).on('mouseout', '.stars', function() {
                    $(this).find('i').removeClass('hover');
                });

                
                $(document).on('click', '.stars i', function() {
                    var rating = $(this).data('rating');
                    var bookId = $(this).closest('.card').find('.book-image').data('id');

                    
                    $('#reviewModal').modal('show');

                    
                    $('#reviewModal').data('rating', rating);
                    $('#reviewModal').data('bookId', bookId);
                });
            });


            $(document).on('click', '#submitReviewBtn', function() {
                var rating = $('#reviewModal').data('rating');
                var bookId = $('#reviewModal').data('bookId');
                var reviewText = $('#reviewText').val();

                $.ajax({
                    url: '<?= base_url('user/submit-review') ?>',
                    type: 'POST',
                    data: {
                        book_id: bookId,
                        rating: rating,
                        review_text: reviewText
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Success', response.message, 'success').then(() => {
                                $('#reviewModal').modal('hide');
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to submit the review.', 'error');
                    }
                });
            });
        </script>
</body>

</html>