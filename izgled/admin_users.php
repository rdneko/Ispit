<?php
session_start();
include '../izgled/config.php';

// Odjava
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: ../izgled/Sajt.php");
    exit();
}

// Obrada izmene korisnika
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_user"])) {
    $user_id = intval($_POST["user_id"]);
    $username = htmlspecialchars(trim($_POST["username"]));
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $is_admin = isset($_POST["is_admin"]) ? 1 : 0;

    $errors = [];

    if (empty($username)) {
        $errors[] = "Korisničko ime je obavezno.";
    }
    if (empty($email)) {
        $errors[] = "Email je obavezan.";
    } elseif (!$email) {
        $errors[] = "Neispravan format email adrese.";
    }

    $hashed_password = null;
    if (!empty($password)) {
        if (strlen($password) < 6) {
            $errors[] = "Lozinka mora imati najmanje 6 karaktera.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Lozinke se ne podudaraju.";
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    if (empty($errors)) {
        if (!empty($hashed_password)) {
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, is_admin=? WHERE id=?");
            $stmt->bind_param("sssii", $username, $email, $hashed_password, $is_admin, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, is_admin=? WHERE id=?");
            $stmt->bind_param("ssii", $username, $email, $is_admin, $user_id);
        }

        if ($stmt->execute()) {
            $_SESSION['admin_success'] = "Podaci korisnika uspešno ažurirani!";
        } else {
            $_SESSION['admin_error'] = "Greška pri ažuriranju korisnika: " . $conn->error;
        }
        $stmt->close();
    } else {
        $_SESSION['admin_error'] = implode("<br>", $errors);
    }
    header("Location: admin_users.php");
    exit();
}

// Dohvatanje korisnika iz baze
$sql = "SELECT id, username, email, is_admin FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Korisnici</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_login.css">
    <style>
        .action-buttons a {
            margin-right: 5px;
            text-decoration: none;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-check {
            margin: 15px 0;
        }
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1><i class="fas fa-cogs"></i> Admin Panel</h1>
        </div>
        <div class="admin-nav container">
            <a href="admin.php"><i class="fas fa-boxes"></i> Proizvodi</a>
            <a href="admin_users.php" class="active"><i class="fas fa-users"></i> Korisnici</a>
            <a href="shop.php"><i class="fas fa-store"></i> Prodavnica</a>
            <a href="?logout" style="float: right;"><i class="fas fa-sign-out-alt"></i> Odjava</a>
        </div>
    </header>

    <div class="container">
        <?php if (isset($_SESSION['admin_success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['admin_success'] ?>
                <?php unset($_SESSION['admin_success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['admin_error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['admin_error'] ?>
                <?php unset($_SESSION['admin_error']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-users"></i> Lista korisnika
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Korisničko ime</th>
                            <th>Email</th>
                            <th>Administrator</th>
                            <th>Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= ($row['is_admin'] == 1) ? '<i class="fas fa-check text-success"></i> Da' : '<i class="fas fa-times text-danger"></i> Ne' ?></td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm edit-user-btn"
                                           data-id="<?= $row['id'] ?>"
                                           data-username="<?= htmlspecialchars($row['username']) ?>"
                                           data-email="<?= htmlspecialchars($row['email']) ?>"
                                           data-is-admin="<?= $row['is_admin'] ?>">
                                            <i class="fas fa-edit"></i> Izmeni
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="5">Nema registrovanih korisnika.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <h2><i class="fas fa-edit"></i> Izmeni korisnika</h2>
            <form method="POST">
                <input type="hidden" name="update_user" value="1">
                <input type="hidden" name="user_id" id="editUserId">

                <div class="form-group">
                    <label for="editUsername">Korisničko ime</label>
                    <input type="text" class="form-control" id="editUsername" name="username" required>
                </div>
                <div class="form-group">
                    <label for="editEmail">Email</label>
                    <input type="email" class="form-control" id="editEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="editPassword">Nova lozinka (ostavite prazno da ne menjate)</label>
                    <input type="password" class="form-control" id="editPassword" name="password">
                </div>
                <div class="form-group">
                    <label for="editConfirmPassword">Potvrdite novu lozinku</label>
                    <input type="password" class="form-control" id="editConfirmPassword" name="confirm_password">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="editIsAdmin" name="is_admin">
                    <label class="form-check-label" for="editIsAdmin">Administrator</label>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Sačuvaj izmene</button>
                <button type="button" class="btn btn-danger" onclick="closeEditUserModal()"><i class="fas fa-times"></i> Otkaži</button>
            </form>
        </div>
    </div>

    <script>
        const editUserModal = document.getElementById('editUserModal');
        const editUserButtons = document.querySelectorAll('.edit-user-btn');
        const editUserIdInput = document.getElementById('editUserId');
        const editUsernameInput = document.getElementById('editUsername');
        const editEmailInput = document.getElementById('editEmail');
        const editIsAdminInput = document.getElementById('editIsAdmin');

        editUserButtons.forEach(button => {
            button.addEventListener('click', function() {
                editUserIdInput.value = this.dataset.id;
                editUsernameInput.value = this.dataset.username;
                editEmailInput.value = this.dataset.email;
                editIsAdminInput.checked = this.dataset.isAdmin === '1';
                editUserModal.style.display = 'flex';
            });
        });

        function closeEditUserModal() {
            editUserModal.style.display = 'none';
        }

        window.addEventListener('click', function(event) {
            if (event.target === editUserModal) {
                closeEditUserModal();
            }
        });
    </script>
</body>
</html>