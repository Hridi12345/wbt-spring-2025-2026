<?php
// ================================================================
// FRONT CONTROLLER (router)
// Handles routing, AJAX search endpoint, logout, role checks.
// ================================================================
session_start();

require 'config.php';
require 'models.php';
require 'controllers.php';

$page = $_GET['page'] ?? 'login';

/* ------------- Logout ------------- */
if ($page === 'logout') {
    $_SESSION = [];
    session_destroy();
    setcookie('remember_user', '', time() - 3600, '/');
    header('Location: index.php?page=login');
    exit;
}

/* ------------- AJAX search endpoint -------------
   Called by inline JS in dashboards.
   ?page=ajax&type=salesperson&q=...   (admin only)
   ?page=ajax&type=car&q=...        (salesperson only)
*/
if ($page === 'ajax') {
    header('Content-Type: application/json');
    if (!isset($_SESSION['user'])) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    $type = $_GET['type'] ?? '';
    $q    = trim($_GET['q'] ?? '');

    if ($type === 'salesperson' && $_SESSION['user']['role'] === 'admin') {
        echo json_encode($q === '' ? getSalespeople($conn) : searchSalespeople($conn, $q));
    } elseif ($type === 'car' && $_SESSION['user']['role'] === 'salesperson') {
        echo json_encode($q === '' ? getCars($conn) : searchCars($conn, $q));
    } else {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
    }
    exit;
}

/* ------------- Auth gates ------------- */
$publicPages = ['login', 'register'];

// Already logged in -> skip login/register
if (in_array($page, $publicPages) && isset($_SESSION['user'])) {
    header('Location: index.php?page=' . $_SESSION['user']['role']);
    exit;
}

// Protected pages require login
if (!in_array($page, $publicPages) && !isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit;
}

// Role gate
if ($page === 'admin'     && $_SESSION['user']['role'] !== 'admin')      { header('Location: index.php?page=login'); exit; }
if ($page === 'salesperson' && $_SESSION['user']['role'] !== 'salesperson')  { header('Location: index.php?page=login'); exit; }

/* ------------- Dispatch ------------- */
switch ($page) {
    case 'login':     loginCtrl($conn);     break;
    case 'register':  registerCtrl($conn);  break;
    case 'admin':     adminCtrl($conn);     break;
    case 'salesperson': salespersonCtrl($conn); break;
    default:
        header('Location: index.php?page=login');
        exit;
}

mysqli_close($conn);
?>
