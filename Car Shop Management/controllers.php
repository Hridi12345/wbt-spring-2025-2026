<?php
// ================================================================
// CONTROLLERS - request handling + role-based logic
// ================================================================

/* ============== Login ============== */
function loginCtrl($conn) {
    $error = '';
    $prefill = $_COOKIE['remember_user'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        if ($u === '' || $p === '') {
            $error = 'Please fill in both fields.';
        } else {
            // Try admin first
            $admin = authAdmin($conn, $u, $p);
            if ($admin) {
                $_SESSION['user'] = [
                    'id' => $admin['id'], 'username' => $admin['username'],
                    'name' => 'Administrator', 'role' => 'admin'
                ];
                if ($remember) setcookie('remember_user', $u, time() + 86400 * 30, '/');
                else setcookie('remember_user', '', time() - 3600, '/');
                header('Location: index.php?page=admin');
                exit;
            }
            // Then salesperson
            $salesperson = authSalesperson($conn, $u, $p);
            if ($salesperson) {
                $_SESSION['user'] = [
                    'id' => $salesperson['id'], 'username' => $salesperson['username'],
                    'name' => $salesperson['name'], 'role' => 'salesperson'
                ];
                if ($remember) setcookie('remember_user', $u, time() + 86400 * 30, '/');
                else setcookie('remember_user', '', time() - 3600, '/');
                header('Location: index.php?page=salesperson');
                exit;
            }
            $error = 'Invalid username or password.';
        }
    }

    require 'views/login.php';
}

/* ============== Register (salesperson self-registration) ============== */
function registerCtrl($conn) {
    $error = $success = '';
    $old = ['name' => '', 'contact' => '', 'username' => ''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name  = trim($_POST['name'] ?? '');
        $contact  = trim($_POST['contact'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $old = compact('name', 'contact', 'username');

        if ($name === '' || $contact === '' || $username === '' || $password === '') {
            $error = 'All fields are required.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } elseif (salespersonUsernameExists($conn, $username)) {
            $error = 'Username is already taken.';
        } else {
            if (addSalesperson($conn, $name, $contact, $username, $password)) {
                $success = 'Account created! You can now log in.';
                $old = ['name' => '', 'contact' => '', 'username' => ''];
            } else {
                $error = 'Registration failed. Try again.';
            }
        }
    }

    require 'views/register.php';
}

/* ============== Admin Dashboard (manages salespeople) ============== */
function adminCtrl($conn) {
    $action = $_GET['action'] ?? 'list';
    $error = '';
    $editing = null;  // when set, view shows Edit form instead of Add form

    /* --- Add (POST) --- */
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $name  = trim($_POST['name'] ?? '');
        $contact  = trim($_POST['contact'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($name === '' || $contact === '' || $username === '' || $password === '') {
            $error = 'All fields are required.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif (salespersonUsernameExists($conn, $username)) {
            $error = 'Username is already taken.';
        } else {
            if (addSalesperson($conn, $name, $contact, $username, $password)) {
                header('Location: index.php?page=admin&msg=added');
                exit;
            }
            $error = 'Failed to add salesperson.';
        }
    }

    /* --- Update (POST) --- */
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id    = intval($_GET['id'] ?? 0);
        $name  = trim($_POST['name'] ?? '');
        $contact  = trim($_POST['contact'] ?? '');
        $username = trim($_POST['username'] ?? '');

        // ===== NULL VALIDATION on UPDATE =====
        if ($name === '' || $contact === '' || $username === '') {
            $error = 'No field can be empty (NULL). All fields are required.';
            $editing = ['id' => $id, 'name' => $name, 'contact' => $contact, 'username' => $username];
        } elseif (salespersonUsernameExists($conn, $username, $id)) {
            $error = 'That username is used by another salesperson.';
            $editing = ['id' => $id, 'name' => $name, 'contact' => $contact, 'username' => $username];
        } else {
            if (updateSalesperson($conn, $id, $name, $contact, $username)) {
                header('Location: index.php?page=admin&msg=updated');
                exit;
            }
            $error = 'Update failed.';
            $editing = ['id' => $id, 'name' => $name, 'contact' => $contact, 'username' => $username];
        }
    }

    /* --- Show edit form (GET) --- */
    if ($action === 'edit' && !$editing) {
        $id = intval($_GET['id'] ?? 0);
        $editing = getSalesperson($conn, $id);
    }

    /* --- Delete (GET) --- */
    if ($action === 'delete') {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) deleteSalesperson($conn, $id);
        header('Location: index.php?page=admin&msg=deleted');
        exit;
    }

    $salespeople = getSalespeople($conn);
    require 'views/admin.php';
}

/* ============== Salesperson Dashboard (manages cars) ============== */
function salespersonCtrl($conn) {
    $action = $_GET['action'] ?? 'list';
    $error = '';
    $editing = null;

    /* --- Add (POST) --- */
    if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $carName = trim($_POST['car_name'] ?? '');
        $brand   = trim($_POST['brand'] ?? '');
        $quantity = trim($_POST['quantity'] ?? '');
        $price = trim($_POST['price'] ?? '');

        if ($carName === '' || $brand === '' || $quantity === '' || $price === '') {
            $error = 'All fields are required.';
        } elseif (!ctype_digit($quantity) || intval($quantity) < 0) {
            $error = 'Quantity must be a non-negative whole number.';
        } elseif (!is_numeric($price) || floatval($price) < 0) {
            $error = 'Price must be a non-negative number.';
        } else {
            $salespersonId = $_SESSION['user']['id'];
            if (addCar($conn, $carName, $brand, intval($quantity), floatval($price), $salespersonId)) {
                header('Location: index.php?page=salesperson&msg=added');
                exit;
            }
            $error = 'Failed to add car.';
        }
    }

    /* --- Update (POST) --- */
    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id    = intval($_GET['id'] ?? 0);
        $carName = trim($_POST['car_name'] ?? '');
        $brand   = trim($_POST['brand'] ?? '');
        $quantity = trim($_POST['quantity'] ?? '');
        $price = trim($_POST['price'] ?? '');

        // ===== NULL VALIDATION on UPDATE =====
        if ($carName === '' || $brand === '' || $quantity === '' || $price === '') {
            $error = 'No field can be empty (NULL). All fields are required.';
            $editing = ['id' => $id, 'car_name' => $carName, 'brand' => $brand,
                        'quantity' => $quantity, 'price' => $price];
        } elseif (!ctype_digit($quantity) || intval($quantity) < 0) {
            $error = 'Quantity must be a non-negative whole number.';
            $editing = ['id' => $id, 'car_name' => $carName, 'brand' => $brand,
                        'quantity' => $quantity, 'price' => $price];
        } elseif (!is_numeric($price) || floatval($price) < 0) {
            $error = 'Price must be a non-negative number.';
            $editing = ['id' => $id, 'car_name' => $carName, 'brand' => $brand,
                        'quantity' => $quantity, 'price' => $price];
        } else {
            if (updateCar($conn, $id, $carName, $brand, intval($quantity), floatval($price))) {
                header('Location: index.php?page=salesperson&msg=updated');
                exit;
            }
            $error = 'Update failed.';
            $editing = ['id' => $id, 'car_name' => $carName, 'brand' => $brand,
                        'quantity' => $quantity, 'price' => $price];
        }
    }

    /* --- Show edit form --- */
    if ($action === 'edit' && !$editing) {
        $id = intval($_GET['id'] ?? 0);
        $editing = getCar($conn, $id);
    }

    /* --- Delete --- */
    if ($action === 'delete') {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) deleteCar($conn, $id);
        header('Location: index.php?page=salesperson&msg=deleted');
        exit;
    }

    $cars = getCars($conn);
    require 'views/salesperson.php';
}
?>
