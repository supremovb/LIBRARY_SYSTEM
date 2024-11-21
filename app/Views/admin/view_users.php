<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.4/dist/sweetalert2.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 40px;
        }
        table th, table td {
            text-align: center;
        }
        .btn-sm {
            padding: 5px 10px;
        }
        .table {
            border-radius: 10px;
        }
        #search-bar {
            margin-bottom: 20px;
            max-width: 300px;
        }
    </style>
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <i class="bx bx-book-reader"></i> Library System
    </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                
                <li class="nav-item">
                    <a href="<?= base_url('admin/dashboard') ?>" class="nav-link"><i class="bx bx-home"></i> Dashboard</a>
                </li>
                
                <?php if (session()->get('role') === 'admin'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-book"></i> Books
                        </a>
                        <div class="dropdown-menu" aria-labelledby="booksDropdown">
                            <a class="dropdown-item" href="<?= base_url('admin/categories') ?>"><i class="bx bx-list-ul"></i> Categories</a>
                            <a class="dropdown-item" href="<?= base_url('admin/add-category') ?>"><i class="bx bx-plus"></i> Add Category</a>
                            <a class="dropdown-item" href="<?= base_url('admin/create-book') ?>"><i class="bx bx-plus"></i> Add Book</a>
                            <a class="dropdown-item" href="<?= base_url('admin/view-books') ?>"><i class="bx bx-book-open"></i> View Books</a>
                            <a class="dropdown-item" href="<?= base_url('admin/approve_reject_transactions') ?>"><i class="bx bx-check"></i> View Borrowed Books</a>
                        </div>
                    </li>
                <?php endif; ?>

                
                <?php if (session()->get('logged_in')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= session()->get('firstname') ?> <span class="caret"></span> <i class="bx bx-user-circle"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="<?= session()->get('role') === 'admin' ? base_url('admin/view-profile') : base_url('student/view-profile') ?>"><i class="bx bx-user"></i> View Profile</a>
                            <a class="dropdown-item" href="<?= base_url('user/logout') ?>"><i class="bx bx-log-out"></i> Logout</a>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
<h2 class="text-center"><i class="bx bx-group"></i> View Users</h2>


    
    <div class="d-flex align-items-center mb-3">
        <input type="text" id="search-bar" class="form-control" placeholder="Search Users" onkeyup="searchTable()" />
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="user-table">
            <thead class="table-dark">
                <tr>
                    <th>User ID</th>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['user_id'] ?></td>
                        <td><?= $user['student_id'] ?></td>
                        <td><?= $user['firstname'] ?> <?= $user['lastname'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['course'] ?></td>
                        <td><?= $user['year'] ?></td>
                        <td><?= ucfirst($user['role']) ?></td>
                        <td>
                            
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editRoleModal" onclick="setUserRole(<?= $user['user_id'] ?>, '<?= $user['role'] ?>')"><i class="bx bx-edit"></i> Edit</button>
                            
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $user['user_id'] ?>)"><i class="bx bx-trash"></i> Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit User Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editRoleForm" method="POST" action="#">
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" name="role" id="role" required>
                            <option value="admin">Admin</option>
                            <option value="student">Student</option>
                            <option value="librarian">Librarian</option>
                        </select>
                    </div>
                    <input type="hidden" name="user_id" id="user_id" value="">
                    <button type="submit" class="btn btn-primary">Save Role</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.4/dist/sweetalert2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.18/dist/sweetalert2.all.min.js"></script>


<script>
    function setUserRole(userId, currentRole) {
        document.getElementById('user_id').value = userId;
        document.getElementById('role').value = currentRole;
    }



    function searchTable() {
        const input = document.getElementById('search-bar').value.toLowerCase();
        const rows = document.getElementById('user-table').getElementsByTagName('tr');
        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            rows[i].style.display = [...cells].some(cell => cell.textContent.toLowerCase().includes(input)) ? '' : 'none';
        }
    }


function deleteUser(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {

            fetch('<?= base_url('admin/delete-user') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'The user has been deleted.'
                    }).then(() => {
                        location.reload(); // Reload the page to reflect the change
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong!'
                });
            });
        }
    });
}

</script>

<script>

    document.getElementById('editRoleForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const userId = document.getElementById('user_id').value;
        const role = document.getElementById('role').value;

        fetch('<?= base_url('admin/edit-role') ?>', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json' 
            },
            body: JSON.stringify({ user_id: userId, role })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Role Updated',
                    text: data.message
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong!'
            });
        });
    });
</script>


</body>
</html>
