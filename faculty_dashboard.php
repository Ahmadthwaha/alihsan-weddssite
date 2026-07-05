<?php
require_once 'db.php';

// Authentication Check
if (!isset($_SESSION['faculty_id'])) {
    header("Location: index.php");
    exit;
}

$faculty_id = intval($_SESSION['faculty_id']);
$faculty = null;
$message = '';
$error = '';

if (DB_ACTIVE) {
    $stmt = $conn->prepare("SELECT * FROM faculties WHERE id = ?");
    $stmt->bind_param("i", $faculty_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $faculty = $res->fetch_assoc();
    }
} else {
    // Fallback
    $file = getFallbackFile();
    $data = json_decode(file_get_contents($file), true);
    foreach ($data['faculties'] as $f) {
        if ($f['id'] == $faculty_id) {
            $faculty = $f;
            break;
        }
    }
}

if (!$faculty) {
    unset($_SESSION['faculty_id']);
    header("Location: index.php");
    exit;
}

// Handle Password Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $new_pass = trim($_POST['new_password']);
    if (!empty($new_pass)) {
        $hashed_pass = password_hash($new_pass, PASSWORD_BCRYPT);
        
        if (DB_ACTIVE) {
            $stmt = $conn->prepare("UPDATE faculties SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_pass, $faculty_id);
            if ($stmt->execute()) {
                $message = "Password updated successfully!";
            } else {
                $error = "Error updating password: " . $stmt->error;
            }
        } else {
            // Fallback storage
            $file = getFallbackFile();
            $data = json_decode(file_get_contents($file), true);
            foreach ($data['faculties'] as &$f) {
                if ($f['id'] == $faculty_id) {
                    $f['password'] = $new_pass;
                    break;
                }
            }
            file_put_contents($file, json_encode($data));
            $message = "Password updated successfully (Local Data)!";
        }
    } else {
        $error = "New password cannot be empty.";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['faculty_id']);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Portal - Al Ihsan Da'awa College</title>
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/fontawesome.css">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Poppins', sans-serif;
            color: #1f2937;
        }
        .dashboard-header {
            background-color: #1b365d;
            color: #fff;
            padding: 20px 0;
            border-bottom: 4px solid #c5a85c;
        }
        .header-title {
            font-weight: 800;
            margin: 0;
            letter-spacing: 0.5px;
            font-size: 22px;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            margin-bottom: 30px;
        }
        .card-header {
            background-color: #1b365d;
            color: #fff;
            font-weight: 700;
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
            border-bottom: none;
            font-size: 16px;
        }
        .profile-img-placeholder {
            width: 90px;
            height: 90px;
            background-color: #e5e7eb;
            color: #1b365d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            margin: 0 auto 15px;
            border: 3px solid #c5a85c;
        }
        .btn-primary {
            background-color: #1b365d;
            border-color: #1b365d;
        }
        .btn-primary:hover {
            background-color: #152a4a;
            border-color: #152a4a;
        }
    </style>
</head>
<body>

    <header class="dashboard-header">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="header-title">AL IHSAN FACULTY PORTAL</h1>
            <a href="faculty_dashboard.php?logout=1" class="btn btn-outline-light btn-sm fw-bold px-3">Logout <i class="fa fa-sign-out-alt ms-1"></i></a>
        </div>
    </header>

    <div class="container my-5">
        <div class="row">
            
            <!-- Left Info Panel -->
            <div class="col-lg-4">
                <div class="card text-center p-4">
                    <div class="profile-img-placeholder">
                        <?= strtoupper(substr($faculty['name'], 0, 1)) ?>
                    </div>
                    <h5 class="fw-bold mb-1" style="color: #1b365d;"><?= htmlspecialchars($faculty['name']) ?></h5>
                    <p class="text-muted small mb-3"><?= htmlspecialchars($faculty['designation'] ?: 'Faculty Member') ?></p>
                    <span class="badge bg-success p-2 mb-3">Active Account</span>
                    <hr>
                    <div class="text-start mt-3">
                        <p class="mb-2"><strong style="color: #1b365d;">Username:</strong> <span class="float-end text-muted"><?= htmlspecialchars($faculty['username']) ?></span></p>
                        <p class="mb-0"><strong style="color: #1b365d;">Registered:</strong> <span class="float-end text-muted"><?= date('d-M-Y', strtotime($faculty['created_at'] ?? 'now')) ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Right Controls Panel -->
            <div class="col-lg-8">
                
                <?php if (!empty($message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Change Password Card -->
                <div class="card">
                    <div class="card-header"><i class="fa fa-key me-2"></i> Update Security Password</div>
                    <div class="card-body">
                        <form action="faculty_dashboard.php" method="POST">
                            <input type="hidden" name="update_password" value="1">
                            <div class="mb-3">
                                <label class="form-label fw-bold">New Login Password</label>
                                <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                                <div class="form-text">Choose a secure password to protect your faculty profile.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>

                <!-- Academic Context Summary -->
                <div class="card">
                    <div class="card-header"><i class="fa fa-info-circle me-2"></i> Portal Overview</div>
                    <div class="card-body">
                        <p>Welcome to your personal portal at Al Ihsan Da'awa College. You have access to administrative notifications, syllabus references, and batch listings.</p>
                        <div class="row text-center mt-4">
                            <div class="col-md-6 border-end">
                                <h3 style="color: #1b365d; font-weight: 800;"><?= getStatCount('students') ?></h3>
                                <p class="text-muted">Total Enrolled Students</p>
                            </div>
                            <div class="col-md-6">
                                <h3 style="color: #1b365d; font-weight: 800;"><?= getStatCount('events') ?></h3>
                                <p class="text-muted">Scheduled Events</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/plugins/bootstrap.min.js"></script>
</body>
</html>
