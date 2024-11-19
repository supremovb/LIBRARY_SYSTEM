<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
    <ul class="navbar-nav ml-auto ml-n3">
        <!-- Dashboard Link as Navbar Item -->
        <li class="nav-item">
            <a href="<?= base_url('dashboard') ?>" class="nav-link">Dashboard</a>
        </li>

        <!-- Dropdown for Books (Only visible for Admin) -->
<?php if (session()->get('role') === 'admin'): ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Books
        </a>
        <div class="dropdown-menu" aria-labelledby="booksDropdown">
            <a class="dropdown-item" href="<?= base_url('admin/add-category') ?>">Add Category</a>
            <a class="dropdown-item" href="<?= base_url('admin/create-book') ?>">Add Book</a>
            <a class="dropdown-item" href="<?= base_url('admin/view-books') ?>">View Books</a>
            <a class="dropdown-item" href="<?= base_url('admin/approve_reject_transactions') ?>">View Borrowed Books</a>
        </div>
    </li>
<?php endif; ?>


        <!-- Conditionally Display "My Borrowed Books" for Students -->
        <?php if (isset($userRole) && $userRole === 'Student'): ?>
            <li class="nav-item">
                <a href="<?= base_url('student/my-borrowed-books') ?>" class="nav-link">My Borrowed Books</a>
            </li>
        <?php endif; ?>

        <!-- Dropdown for Logged-in User -->
        <?php if (session()->get('logged_in')): ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= session()->get('firstname') ?> <span class="caret"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="userDropdown">
            <!-- Show "View Profile" for both students and admins -->
            <a class="dropdown-item" 
               href="<?= session()->get('role') === 'admin' 
                        ? base_url('admin/view-profile') 
                        : base_url('student/view-profile') ?>">
                View Profile
            </a>
            <a class="dropdown-item" href="<?= base_url('user/logout') ?>">Logout</a>
        </div>
    </li>
<?php endif; ?>

    </ul>
</nav>
