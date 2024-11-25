<link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
    <ul class="navbar-nav ml-auto ml-n3">

        <li class="nav-item">
            <a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bx bx-tachometer"></i> Dashboard</a>
        </li>

        <?php if (session()->get('role') === 'admin'): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-book"></i> Books
                </a>
                <div class="dropdown-menu" aria-labelledby="booksDropdown">
                    <a class="dropdown-item" href="<?= base_url('admin/categories') ?>"><i class="bx bx-category"></i> Categories</a>
                    <a class="dropdown-item" href="<?= base_url('admin/add-category') ?>"><i class="bx bx-plus-circle"></i> Add Category</a>
                    <a class="dropdown-item" href="<?= base_url('admin/create-book') ?>"><i class="bx bx-book-add"></i> Add Book</a>
                    <a class="dropdown-item" href="<?= base_url('admin/view-books') ?>"><i class="bx bx-book-open"></i> View Books</a>
                    <a class="dropdown-item" href="<?= base_url('admin/approve_reject_transactions') ?>"><i class="bx bx-book"></i> View Pending Books</a>
                    
                    <a class="dropdown-item" href="<?= base_url('admin/borrowed-books') ?>"><i class="bx bx-bookmark"></i> View Borrowed Books</a>
                </div>
            </li>

            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="reportsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-bar-chart-alt-2"></i> Reports
                </a>
                <div class="dropdown-menu" aria-labelledby="reportsDropdown">
                    <a class="dropdown-item" href="<?= base_url('admin/generate-book-report') ?>"><i class="bx bx-book"></i> Generate Book's Report</a>
                    <a class="dropdown-item" href="<?= base_url('admin/generate-user-report') ?>"><i class="bx bx-group"></i> Generate User's Report</a>
                    <a class="dropdown-item" href="<?= base_url('admin/generate-transaction-report') ?>"><i class="bx bx-receipt"></i> Generate Transaction's Report</a>
                </div>
            </li>
        <?php endif; ?>

        <?php if (session()->get('role') === 'admin'): ?>


        <?php endif; ?>

        <?php if (isset($userRole) && $userRole === 'Student'): ?>
            <li class="nav-item">
                <a href="<?= base_url('student/my-borrowed-books') ?>" class="nav-link"><i class="bx bx-bookmark"></i> My Borrowed Books</a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('student/book-reviews') ?>" class="nav-link"><i class="bx bx-comment"></i> Book Reviews</a>
            </li>
        <?php endif; ?>

        <?php if (session()->get('logged_in')): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-user"></i> <?= session()->get('firstname') ?> <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="userDropdown">
                    <a class="dropdown-item"
                        href="<?= session()->get('role') === 'admin'
                                    ? base_url('admin/view-profile')
                                    : base_url('student/view-profile') ?>"><i class="bx bx-user-circle"></i> View Profile</a>

                    <?php if (session()->get('role') === 'admin'): ?>
                        <a class="dropdown-item" href="<?= base_url('admin/view-users') ?>"><i class="bx bx-group"></i> View Users</a>
                    <?php endif; ?>



                    <a class="dropdown-item" href="<?= base_url('user/logout') ?>"><i class="bx bx-log-out"></i> Logout</a>
                </div>
            </li>
        <?php endif; ?>

    </ul>
</nav>