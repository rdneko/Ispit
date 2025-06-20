<?php
session_start();
include 'config.php';

// Provera admin privilegija
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Odjava
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Obrada formulara
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : null;
    $name = htmlspecialchars(trim($_POST["name"]));
    $description = htmlspecialchars(trim($_POST["description"]));
    $price = floatval($_POST["price"]);
    
    // Promjena: Direktorijum za čuvanje slika - samo images/ (bez uploads/)
    $upload_dir = "../images/";
    
    // Obrada upload-ovane slike
    $image_path = '';
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['image_file']['tmp_name'];
        $file_name = basename($_FILES['image_file']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = uniqid('img_', true) . '.' . $file_ext;
            $file_path = $upload_dir . $new_file_name;
            
            if (move_uploaded_file($file_tmp, $file_path)) {
                // Promjena: Čuva samo u images/ folderu
                $image_path = "images/" . $new_file_name;
            } else {
                $_SESSION['admin_error'] = "Greška pri upload-u slike.";
                header("Location: admin.php");
                exit();
            }
        } else {
            $_SESSION['admin_error'] = "Dozvoljeni formati slika su: JPG, JPEG, PNG, GIF.";
            header("Location: admin.php");
            exit();
        }
    } elseif (isset($_POST["image"]) && !empty($_POST["image"])) {
        $image_path = htmlspecialchars(trim($_POST["image"]));
    }

    // Validacija
    if (empty($name) || empty($description) || $price <= 0 || empty($image_path)) {
        $_SESSION['admin_error'] = "Sva polja su obavezna i cena mora biti pozitivna";
        header("Location: admin.php");
        exit();
    }

    try {
        if (isset($_POST["add"])) {
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $name, $description, $price, $image_path);
        } elseif (isset($_POST["update"])) {
            $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?");
            $stmt->bind_param("ssdsi", $name, $description, $price, $image_path, $id);
        }

        $stmt->execute();
        $stmt->close();
        $_SESSION['admin_success'] = isset($_POST["add"]) ? "Proizvod uspešno dodat!" : "Proizvod uspešno ažuriran!";
    } catch (Exception $e) {
        $_SESSION['admin_error'] = "Greška: " . $e->getMessage();
    }

    header("Location: admin.php");
    exit();
}

// Brisanje proizvoda
if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    try {
        $result = $conn->query("SELECT image FROM products WHERE id = $id");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Promjena: Dodajemo ../ jer slike su u images/ folderu
            $image_path = "../" . $row['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        $conn->query("DELETE FROM products WHERE id = $id");
        $_SESSION['admin_success'] = "Proizvod uspešno obrisan!";
    } catch (Exception $e) {
        $_SESSION['admin_error'] = "Greška pri brisanju: " . $e->getMessage();
    }
    header("Location: admin.php");
    exit();
}

// Dohvatanje proizvoda
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Solarni Paneli</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_login.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><i class="fas fa-cogs"></i> Admin Panel</h1>
        </div>
        <div class="admin-nav container">
            <a href="admin.php"><i class="fas fa-boxes"></i> Proizvodi</a>
            <a href="admin_users.php"><i class="fas fa-users"></i> Korisnici</a>
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
                <i class="fas fa-plus-circle"></i> Dodaj novi proizvod
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Naziv proizvoda</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Opis proizvoda</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Cena (RSD)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" min="0" required>
                    </div>
                    
                    <div class="upload-container">
                        <label class="upload-label">Upload slike</label>
                        <div class="upload-wrapper" id="uploadWrapper">
                            <input type="file" id="image_file" name="image_file" accept="image/*" class="upload-input">
                            <div class="upload-style">
                                <span class="upload-text" id="fileText">Izaberite fajl | Nije izabran fajl</span>
                                <span class="upload-button">Pretraži</span>
                            </div>
                        </div>
                        <p class="upload-hint">Dozvoljeni formati: JPG, JPEG, PNG, GIF</p>
                    </div>
                    
                    <input type="hidden" name="image" id="image" value="">
                    <button type="submit" name="add" class="btn btn-primary">
                        <i class="fas fa-save"></i> Sačuvaj proizvod
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-list"></i> Lista proizvoda
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Slika</th>
                            <th>Naziv</th>
                            <th>Opis</th>
                            <th>Cena (RSD)</th>
                            <th>Akcije</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($product = $products->fetch_assoc()): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td>
                                <img src="../<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                            </td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars(substr($product['description'], 0, 50)) ?>...</td>
                            <td><?= number_format($product['price'], 2, ',', '.') ?></td>
                            <td class="action-buttons">
                                <a href="#" class="btn btn-primary btn-sm edit-btn"
                                   data-id="<?= $product['id'] ?>"
                                   data-name="<?= htmlspecialchars($product['name']) ?>"
                                   data-description="<?= htmlspecialchars($product['description']) ?>"
                                   data-price="<?= $product['price'] ?>"
                                   data-image="<?= htmlspecialchars($product['image']) ?>">
                                    <i class="fas fa-edit"></i> Izmeni
                                </a>
                                <a href="admin.php?delete=<?= $product['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Da li ste sigurni da želite da obrišete ovaj proizvod?')">
                                    <i class="fas fa-trash"></i> Obriši
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2><i class="fas fa-edit"></i> Izmeni proizvod</h2>
            <form method="POST" id="editForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="editId">
                <input type="hidden" name="update" value="1">
                <input type="hidden" name="image" id="editImageHidden">

                <div class="form-group">
                    <label for="editName">Naziv proizvoda</label>
                    <input type="text" class="form-control" id="editName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="editDescription">Opis proizvoda</label>
                    <textarea class="form-control" id="editDescription" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="editPrice">Cena (RSD)</label>
                    <input type="number" step="0.01" class="form-control" id="editPrice" name="price" min="0" required>
                </div>
                
                <div class="upload-container">
                    <label class="upload-label">Upload nove slike</label>
                    <div class="upload-wrapper" id="editUploadWrapper">
                        <input type="file" id="editImageFile" name="image_file" accept="image/*" class="upload-input">
                        <div class="upload-style">
                            <span class="upload-text" id="editFileText">Zadrži postojeću sliku</span>
                            <span class="upload-button">Pretraži</span>
                        </div>
                    </div>
                    <p class="upload-hint">Dozvoljeni formati: JPG, JPEG, PNG, GIF</p>
                </div>
                
                <div class="form-group">
                    <label>Trenutna slika:</label>
                    <img src="../" id="editCurrentImage" class="product-image" style="display: block; margin-top: 5px; max-width: 200px;">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Sačuvaj izmene
                </button>
                <button type="button" class="btn btn-danger" onclick="closeModal()">
                    <i class="fas fa-times"></i> Otkaži
                </button>
            </form>
        </div>
    </div>

    <script>
        // Upload komponenta
        document.getElementById('image_file').addEventListener('change', function(e) {
            const wrapper = document.getElementById('uploadWrapper');
            const fileText = document.getElementById('fileText');
            
            if (this.files.length > 0) {
                wrapper.classList.add('has-file');
                fileText.textContent = this.files[0].name;
            } else {
                wrapper.classList.remove('has-file');
                fileText.textContent = 'Izaberite fajl | Nije izabran fajl';
            }
        });

        // Edit modal upload komponenta
        document.getElementById('editImageFile').addEventListener('change', function(e) {
            const wrapper = document.getElementById('editUploadWrapper');
            const fileText = document.getElementById('editFileText');
            
            if (this.files.length > 0) {
                wrapper.classList.add('has-file');
                fileText.textContent = this.files[0].name;
            } else {
                wrapper.classList.remove('has-file');
                fileText.textContent = 'Zadrži postojeću sliku';
            }
        });

        // Otvaranje modala za izmenu
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                document.getElementById('editId').value = this.dataset.id;
                document.getElementById('editName').value = this.dataset.name;
                document.getElementById('editDescription').value = this.dataset.description;
                document.getElementById('editPrice').value = this.dataset.price;
                document.getElementById('editImageHidden').value = this.dataset.image;
                
                // Prikaz trenutne slike
                const currentImage = document.getElementById('editCurrentImage');
                currentImage.src = "../" + this.dataset.image;
                currentImage.style.display = 'block';

                // Resetuj upload
                document.getElementById('editUploadWrapper').classList.remove('has-file');
                document.getElementById('editFileText').textContent = 'Zadrži postojeću sliku';
                document.getElementById('editImageFile').value = '';

                document.getElementById('editModal').style.display = 'flex';
            });
        });

        // Zatvaranje modala
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Zatvaranje modala klikom izvan
        window.addEventListener('click', function(e) {
            if (e.target === document.getElementById('editModal')) {
                closeModal();
            }
        });
    </script>
</body>
</html>