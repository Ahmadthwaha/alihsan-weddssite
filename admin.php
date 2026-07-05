<?php
require_once 'db.php';

// Login handler
if (isset($_POST['action']) && $_POST['action'] === 'admin_login') {
    $user = isset($_POST['username']) ? trim($_POST['username']) : '';
    $pass = isset($_POST['password']) ? trim($_POST['password']) : '';
    if ($user === 'admin99' && $pass === 'admin@786') {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $login_error = "Invalid Username or Password!";
    }
}

// Logout handler
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in']);
    header("Location: admin.php");
    exit;
}

// If not logged in, render the login page instead of the admin panel
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - Al Ihsan Da'awa College</title>
        <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
        <style>
            body {
                background: linear-gradient(135deg, #1b365d 0%, #0d1e3d 100%);
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Poppins', sans-serif;
            }
            .login-card {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                padding: 40px;
                width: 100%;
                max-width: 400px;
                border: 2px solid #c5a85c;
            }
            .login-title {
                color: #1b365d;
                font-weight: 800;
                text-align: center;
                margin-bottom: 25px;
            }
            .btn-primary {
                background-color: #1b365d;
                border-color: #1b365d;
            }
            .btn-primary:hover {
                background-color: #0d1e3d;
                border-color: #0d1e3d;
            }
        </style>
    </head>
    <body>
        <div class="login-card">
            <h3 class="login-title">ADMIN PANEL LOGIN</h3>
            <?php if (isset($login_error)): ?>
                <div class="alert alert-danger text-center p-2 small"><?= $login_error ?></div>
            <?php endif; ?>
            <form action="admin.php" method="POST">
                <input type="hidden" name="action" value="admin_login">
                <div class="mb-3">
                    <label class="form-label fw-bold" style="color: #1b365d;">Username</label>
                    <input type="text" name="username" class="form-control" required autocomplete="off">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" style="color: #1b365d;">Password</label>
                    <input type="password" name="password" class="form-control" required autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mt-2">Log In</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$message = '';
$error = '';
$action = isset($_POST['action']) ? $_POST['action'] : '';

$batches = ['8th', '9th', '10th', 'HISc-1', 'HISc-2', 'BISc-1', 'BISc-2', 'BISc-3', 'BISc-4', 'BISc-5'];

// Batch promotion handler
if ($action === 'promote_batch') {
    $batch = $_POST['batch'];
    $idx = array_search($batch, $batches);
    if ($idx !== false) {
        if ($batch === 'BISc-5') {
            // Graduate all active students in BISc-5
            if (DB_ACTIVE) {
                $conn->query("UPDATE students SET is_graduated = 1, graduation_batch = 'BISc-5' WHERE current_batch = 'BISc-5' AND is_graduated = 0");
            } else {
                $file = getFallbackFile();
                $data = json_decode(file_get_contents($file), true);
                foreach ($data['students'] as &$s) {
                    if ($s['current_batch'] === 'BISc-5' && (!isset($s['is_graduated']) || $s['is_graduated'] == 0)) {
                        $s['is_graduated'] = 1;
                        $s['graduation_batch'] = 'BISc-5';
                    }
                }
                file_put_contents($file, json_encode($data));
            }
            $message = "Batch BISc-5 has been successfully promoted to Graduates!";
        } else {
            $next_batch = $batches[$idx + 1];
            if (DB_ACTIVE) {
                $stmt = $conn->prepare("UPDATE students SET current_batch = ? WHERE current_batch = ? AND is_graduated = 0");
                $stmt->bind_param("ss", $next_batch, $batch);
                $stmt->execute();
            } else {
                $file = getFallbackFile();
                $data = json_decode(file_get_contents($file), true);
                foreach ($data['students'] as &$s) {
                    if ($s['current_batch'] === $batch && (!isset($s['is_graduated']) || $s['is_graduated'] == 0)) {
                        $s['current_batch'] = $next_batch;
                    }
                }
                file_put_contents($file, json_encode($data));
            }
            $message = "Batch '$batch' promoted to '$next_batch' successfully!";
        }
    }
}

// Graduate single student handler
if (isset($_GET['graduate_student'])) {
    $id = intval($_GET['graduate_student']);
    if (DB_ACTIVE) {
        $conn->query("UPDATE students SET is_graduated = 1, graduation_batch = current_batch WHERE id = $id");
    } else {
        $file = getFallbackFile();
        $data = json_decode(file_get_contents($file), true);
        foreach ($data['students'] as &$s) {
            if ($s['id'] == $id) {
                $s['is_graduated'] = 1;
                $s['graduation_batch'] = $s['current_batch'];
                break;
            }
        }
        file_put_contents($file, json_encode($data));
    }
    $message = "Student graduated successfully!";
}

// Delete student handler
if (isset($_GET['delete_student'])) {
    $id = intval($_GET['delete_student']);
    if (DB_ACTIVE) {
        $conn->query("DELETE FROM students WHERE id = $id");
    } else {
        $file = getFallbackFile();
        $data = json_decode(file_get_contents($file), true);
        $filtered = [];
        foreach ($data['students'] as $s) {
            if ($s['id'] != $id) {
                $filtered[] = $s;
            }
        }
        $data['students'] = $filtered;
        file_put_contents($file, json_encode($data));
    }
    $message = "Student record deleted!";
}

// Direct alumni adder
if ($action === 'add_direct_graduate') {
    $name = trim($_POST['name']);
    $batch_name = trim($_POST['batch']);
    if (!empty($name)) {
        if (DB_ACTIVE) {
            $stmt = $conn->prepare("INSERT INTO alumni (name, batch) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $batch_name);
            $stmt->execute();
        } else {
            $file = getFallbackFile();
            $data = json_decode(file_get_contents($file), true);
            $data['alumni'][] = [
                'id' => time(),
                'name' => $name,
                'batch' => $batch_name
            ];
            file_put_contents($file, json_encode($data));
        }
        $message = "Graduate record added directly!";
    } else {
        $error = "Graduate name is required.";
    }
}

// Delete direct alumni handler
if (isset($_GET['delete_alumni'])) {
    $id = intval($_GET['delete_alumni']);
    if (DB_ACTIVE) {
        $conn->query("DELETE FROM alumni WHERE id = $id");
    } else {
        $file = getFallbackFile();
        $data = json_decode(file_get_contents($file), true);
        $filtered = [];
        foreach ($data['alumni'] as $a) {
            if ($a['id'] != $id) {
                $filtered[] = $a;
            }
        }
        $data['alumni'] = $filtered;
        file_put_contents($file, json_encode($data));
    }
    $message = "Graduate record deleted!";
}

// Set principal handler
if (isset($_GET['set_principal'])) {
    $id = intval($_GET['set_principal']);
    if (DB_ACTIVE) {
        $conn->query("UPDATE faculties SET is_principal = 0");
        $conn->query("UPDATE faculties SET is_principal = 1 WHERE id = $id");
    } else {
        $file = getFallbackFile();
        $data = json_decode(file_get_contents($file), true);
        foreach ($data['faculties'] as &$f) {
            $f['is_principal'] = ($f['id'] == $id) ? 1 : 0;
        }
        file_put_contents($file, json_encode($data));
    }
    $message = "Faculty member selected as Principal successfully!";
}

// Accept student request handler
if (isset($_GET['accept_student'])) {
    $id = intval($_GET['accept_student']);
    if (DB_ACTIVE) {
        $conn->query("UPDATE students SET status = 'accepted' WHERE id = $id");
    } else {
        $file = getFallbackFile();
        $data = json_decode(file_get_contents($file), true);
        foreach ($data['students'] as &$s) {
            if ($s['id'] == $id) {
                $s['status'] = 'accepted';
                break;
            }
        }
        file_put_contents($file, json_encode($data));
    }
    $message = "Student request accepted successfully! The student is now admitted to their batch.";
}

// Faculty adder/updater
if ($action === 'add_faculty') {
    $name = trim($_POST['name']);
    $designation = trim($_POST['designation']);
    $fuser = trim($_POST['username']);
    $fpass = trim($_POST['password']);
    $photo_path = '';
    
    if (!empty($name) && !empty($fuser) && !empty($fpass)) {
        // Upload photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/uploads/documents/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('fac_', true) . '.' . $ext;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $filename)) {
                $photo_path = 'uploads/documents/' . $filename;
            }
        }
        
        $hashed_pass = password_hash($fpass, PASSWORD_BCRYPT);
        
        if (DB_ACTIVE) {
            // Check username uniqueness
            $check = $conn->prepare("SELECT id FROM faculties WHERE username = ?");
            $check->bind_param("s", $fuser);
            $check->execute();
            $check_res = $check->get_result();
            if ($check_res && $check_res->num_rows > 0) {
                $error = "Username already exists!";
            } else {
                $stmt = $conn->prepare("INSERT INTO faculties (name, designation, username, password, photo, is_principal) VALUES (?, ?, ?, ?, ?, 0)");
                $stmt->bind_param("sssss", $name, $designation, $fuser, $hashed_pass, $photo_path);
                if ($stmt->execute()) {
                    $message = "Faculty member added successfully!";
                } else {
                    $error = "Error adding faculty: " . $stmt->error;
                }
            }
        } else {
            $file = getFallbackFile();
            $data = json_decode(file_get_contents($file), true);
            $exists = false;
            foreach ($data['faculties'] as $f) {
                if ($f['username'] === $fuser) {
                    $exists = true;
                    break;
                }
            }
            if ($exists) {
                $error = "Username already exists in local data!";
            } else {
                $data['faculties'][] = [
                    'id' => time(),
                    'name' => $name,
                    'designation' => $designation,
                    'username' => $fuser,
                    'password' => $fpass, // plaintext fallback
                    'photo' => $photo_path,
                    'is_principal' => 0
                ];
                file_put_contents($file, json_encode($data));
                $message = "Faculty member added (Local Fallback)!";
            }
        }
    } else {
        $error = "Name, Username, and Password are required fields.";
    }
}

// Delete faculty handler
if (isset($_GET['delete_faculty'])) {
    $id = intval($_GET['delete_faculty']);
    if (DB_ACTIVE) {
        $conn->query("DELETE FROM faculties WHERE id = $id");
    } else {
        $file = getFallbackFile();
        $data = json_decode(file_get_contents($file), true);
        $filtered = [];
        foreach ($data['faculties'] as $f) {
            if ($f['id'] != $id) {
                $filtered[] = $f;
            }
        }
        $data['faculties'] = $filtered;
        file_put_contents($file, json_encode($data));
    }
    $message = "Faculty record deleted!";
}

// Calendar event adder
if ($action === 'add_event') {
    $title = trim($_POST['title']);
    $date = trim($_POST['event_date']);
    $time = trim($_POST['event_time']);
    $desc = trim($_POST['description']);
    
    if (!empty($title) && !empty($date)) {
        if (DB_ACTIVE) {
            $stmt = $conn->prepare("INSERT INTO events (title, event_date, event_time, description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $title, $date, $time, $desc);
            $stmt->execute();
        } else {
            $file = getFallbackFile();
            $data = json_decode(file_get_contents($file), true);
            $data['events'][] = [
                'id' => time(),
                'title' => $title,
                'event_date' => $date,
                'event_time' => $time,
                'description' => $desc
            ];
            file_put_contents($file, json_encode($data));
        }
        $message = "Calendar event added successfully!";
    } else {
        $error = "Event Title and Event Date are required.";
    }
}

// Delete calendar event handler
if (isset($_GET['delete_event'])) {
    $id = intval($_GET['delete_event']);
    if (DB_ACTIVE) {
        $conn->query("DELETE FROM events WHERE id = $id");
    } else {
        $file = getFallbackFile();
        $data = json_decode(file_get_contents($file), true);
        $filtered = [];
        foreach ($data['events'] as $ev) {
            if ($ev['id'] != $id) {
                $filtered[] = $ev;
            }
        }
        $data['events'] = $filtered;
        file_put_contents($file, json_encode($data));
    }
    $message = "Calendar event deleted!";
}

// Load current lists
$students_all = getRecords('students');
$faculties = getRecords('faculties');
$alumni_direct = getRecords('alumni');
$events = getRecords('events');

// Filter requested and accepted students
$requested_students = [];
$admitted_students = [];

foreach ($students_all as $s) {
    if (isset($s['status']) && $s['status'] === 'requested') {
        $requested_students[] = $s;
    } else {
        $admitted_students[] = $s;
    }
}

// Filter active students into their batches
$students_by_batch = [];
foreach ($batches as $b) {
    $students_by_batch[$b] = [];
}
$graduated_students = [];

foreach ($admitted_students as $s) {
    if (isset($s['is_graduated']) && $s['is_graduated'] == 1) {
        $graduated_students[] = $s;
    } else {
        if (isset($students_by_batch[$s['current_batch']])) {
            $students_by_batch[$s['current_batch']][] = $s;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Control Admin - Al Ihsan Da'awa College</title>
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/fontawesome.css">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Poppins', sans-serif;
            color: #1f2937;
        }
        .navbar-brand {
            font-weight: 800;
            color: #1b365d !important;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .card-header {
            background-color: #1b365d;
            color: #fff;
            font-weight: 700;
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-primary {
            background-color: #1b365d;
            border-color: #1b365d;
        }
        .btn-primary:hover {
            background-color: #152a4a;
            border-color: #152a4a;
        }
        .btn-gold {
            background-color: #c5a85c;
            color: #1b365d;
            font-weight: 700;
            border: none;
        }
        .btn-gold:hover {
            background-color: #b0944e;
            color: #1b365d;
        }
        .nav-tabs .nav-link.active {
            background-color: #1b365d;
            color: white;
            border-color: #1b365d;
        }
        .nav-tabs .nav-link {
            color: #1b365d;
            font-weight: 600;
        }
        .batch-sidebar {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }
        .batch-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            color: #1b365d;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin-bottom: 5px;
            transition: all 0.2s;
        }
        .batch-link:hover, .batch-link.active {
            background-color: #1b365d;
            color: #ffffff;
        }
        .batch-link .badge {
            background-color: #c5a85c;
            color: #1b365d;
        }
        .batch-link.active .badge {
            background-color: #ffffff;
            color: #1b365d;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand" href="index.php">AL IHSAN DA'AWA COLLEGE - ADMIN PANEL</a>
            <div>
                <a href="index.php" class="btn btn-outline-secondary btn-sm fw-bold me-2">Back to Site</a>
                <a href="admin.php?logout=1" class="btn btn-danger btn-sm fw-bold">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        
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

        <ul class="nav nav-tabs mb-4" id="adminTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="batches-tab" data-bs-toggle="tab" data-bs-target="#batches-pane" type="button" role="tab" aria-controls="batches-pane" aria-selected="true"><i class="fa fa-layer-group me-2"></i>Student Batches</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="requests-tab" data-bs-toggle="tab" data-bs-target="#requests-pane" type="button" role="tab" aria-controls="requests-pane" aria-selected="false"><i class="fa fa-envelope-open-text me-2"></i>Admission Requests (<?= count($requested_students) ?>)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="graduates-tab" data-bs-toggle="tab" data-bs-target="#graduates-pane" type="button" role="tab" aria-controls="graduates-pane" aria-selected="false"><i class="fa fa-graduation-cap me-2"></i>Graduates (<?= count($graduated_students) + count($alumni_direct) ?>)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="faculties-tab" data-bs-toggle="tab" data-bs-target="#faculties-pane" type="button" role="tab" aria-controls="faculties-pane" aria-selected="false"><i class="fa fa-chalkboard-teacher me-2"></i>Faculties (<?= count($faculties) ?>)</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events-pane" type="button" role="tab" aria-controls="events-pane" aria-selected="false"><i class="fa fa-calendar-alt me-2"></i>Calendar Events (<?= count($events) ?>)</button>
            </li>
        </ul>

        <div class="tab-content" id="adminTabContent">
            
            <!-- Tab 1: Student Batches (with Promotions) -->
            <div class="tab-pane fade show active" id="batches-pane" role="tabpanel" aria-labelledby="batches-tab">
                <div class="row">
                    <!-- Batch selection sidebar -->
                    <div class="col-md-3">
                        <h6 class="fw-bold mb-3" style="color: #1b365d;">Batch Sequence</h6>
                        <div class="batch-sidebar nav flex-column" role="tablist">
                            <?php foreach ($batches as $index => $b): ?>
                                <a href="#batch-content-<?= $b ?>" 
                                   class="batch-link <?= $index === 0 ? 'active' : '' ?>" 
                                   data-bs-toggle="tab" 
                                   data-bs-target="#batch-content-<?= $b ?>"
                                   role="tab"
                                   aria-controls="batch-content-<?= $b ?>"
                                   aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
                                    <span><?= $b ?> Batch</span>
                                    <span class="badge rounded-pill"><?= count($students_by_batch[$b]) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Batch student lists and promoting options -->
                    <div class="col-md-9">
                        <div class="tab-content">
                            <?php foreach ($batches as $index => $b): ?>
                                <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="batch-content-<?= $b ?>" role="tabpanel">
                                    
                                    <div class="card">
                                        <div class="card-header">
                                            <span class="fw-bold"><?= $b ?> Batch Student Registry</span>
                                            
                                            <!-- Promotion Button -->
                                            <form action="admin.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to promote the ENTIRE <?= $b ?> batch? All students in this batch will move to the next level.')">
                                                <input type="hidden" name="action" value="promote_batch">
                                                <input type="hidden" name="batch" value="<?= $b ?>">
                                                <button type="submit" class="btn btn-gold btn-sm px-3">
                                                    <i class="fa fa-arrow-alt-circle-up me-1"></i> 
                                                    <?= $b === 'BISc-5' ? 'Graduate Batch' : 'Promote Batch' ?>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="ps-3">Name</th>
                                                            <th>Age</th>
                                                            <th>DOB</th>
                                                            <th>Father's Name</th>
                                                            <th>Documents</th>
                                                            <th class="text-end pe-3">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (empty($students_by_batch[$b])): ?>
                                                            <tr>
                                                                <td colspan="5" class="text-center p-4 text-muted">No students in this batch. Admitted students will register here.</td>
                                                            </tr>
                                                        <?php else: ?>
                                                            <?php foreach ($students_by_batch[$b] as $s): ?>
                                                                <tr>
                                                                    <td class="ps-3 fw-bold"><?= htmlspecialchars($s['name']) ?></td>
                                                                    <td><?= htmlspecialchars($s['age'] ?: 'N/A') ?></td>
                                                                    <td><?= htmlspecialchars($s['dob'] ?: 'N/A') ?></td>
                                                                    <td><?= htmlspecialchars($s['father_name'] ?: 'N/A') ?></td>
                                                                    <td>
                                                                        <?php if (!empty($s['document_card'])): ?>
                                                                            <a href="<?= htmlspecialchars($s['document_card']) ?>" target="_blank" class="badge bg-primary text-decoration-none me-1"><i class="fa fa-id-card me-1"></i>Aadhaar</a>
                                                                        <?php else: ?>
                                                                            <span class="text-muted small">No Aadhaar</span>
                                                                        <?php endif; ?>
                                                                        <?php if (!empty($s['birth_certificate'])): ?>
                                                                            <a href="<?= htmlspecialchars($s['birth_certificate']) ?>" target="_blank" class="badge bg-info text-decoration-none me-1"><i class="fa fa-file-alt me-1"></i>Birth Cert</a>
                                                                        <?php else: ?>
                                                                            <span class="text-muted small">No Cert</span>
                                                                        <?php endif; ?>
                                                                        <?php if (!empty($s['marks_card'])): ?>
                                                                            <a href="<?= htmlspecialchars($s['marks_card']) ?>" target="_blank" class="badge bg-warning text-dark text-decoration-none"><i class="fa fa-file-invoice me-1"></i>Marks Card</a>
                                                                        <?php else: ?>
                                                                            <span class="text-muted small">No Marks Card</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td class="text-end pe-3">
                                                                        <a href="admin.php?graduate_student=<?= $s['id'] ?>" class="btn btn-sm btn-success me-1" onclick="return confirm('Graduate this student?')"><i class="fa fa-graduation-cap me-1"></i>Graduate</a>
                                                                        <a href="admin.php?delete_student=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this student?')"><i class="fa fa-trash"></i></a>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 1.5: Admission Requests -->
            <div class="tab-pane fade" id="requests-pane" role="tabpanel" aria-labelledby="requests-tab">
                <div class="card">
                    <div class="card-header">
                        <span>Admission Requests Pending Approval</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Photo</th>
                                        <th>Name</th>
                                        <th>Target Batch</th>
                                        <th>Contact Details</th>
                                        <th>Documents</th>
                                        <th class="text-end pe-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($requested_students)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center p-4 text-muted">No pending admission requests found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($requested_students as $r): ?>
                                            <tr>
                                                <td class="ps-3">
                                                    <?php if (!empty($r['photo'])): ?>
                                                        <img src="<?= htmlspecialchars($r['photo']) ?>" alt="Student Photo" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ccc;">
                                                    <?php else: ?>
                                                        <div style="width: 50px; height: 50px; background-color: #eee; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999;">
                                                            <i class="fa fa-user fa-lg"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="fw-bold"><?= htmlspecialchars($r['name']) ?></td>
                                                <td><span class="badge bg-secondary"><?= htmlspecialchars($r['current_batch']) ?> Batch</span></td>
                                                <td>
                                                    <span class="small"><strong>Father:</strong> <?= htmlspecialchars($r['father_name'] ?: 'N/A') ?> (<?= htmlspecialchars($r['father_phone'] ?: 'N/A') ?>)</span><br>
                                                    <span class="small text-muted"><strong>WhatsApp:</strong> <?= htmlspecialchars($r['whatsapp_no'] ?: 'N/A') ?></span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($r['document_card'])): ?>
                                                        <a href="<?= htmlspecialchars($r['document_card']) ?>" target="_blank" class="badge bg-primary text-decoration-none me-1"><i class="fa fa-id-card"></i> Aadhaar</a>
                                                    <?php endif; ?>
                                                    <?php if (!empty($r['birth_certificate'])): ?>
                                                        <a href="<?= htmlspecialchars($r['birth_certificate']) ?>" target="_blank" class="badge bg-info text-decoration-none me-1"><i class="fa fa-file-alt"></i> Birth Cert</a>
                                                    <?php endif; ?>
                                                    <?php if (!empty($r['marks_card'])): ?>
                                                        <a href="<?= htmlspecialchars($r['marks_card']) ?>" target="_blank" class="badge bg-warning text-dark text-decoration-none"><i class="fa fa-file-invoice"></i> Marks Card</a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end pe-3">
                                                    <a href="admin.php?accept_student=<?= $r['id'] ?>" class="btn btn-sm btn-success me-1" onclick="return confirm('Accept this student\'s request and assign them to batch?')"><i class="fa fa-check me-1"></i>Accept</a>
                                                    <a href="admin.php?delete_student=<?= $r['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject and delete this request?')"><i class="fa fa-times me-1"></i>Reject</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Graduates / Alumni -->
            <div class="tab-pane fade" id="graduates-pane" role="tabpanel" aria-labelledby="graduates-tab">
                <div class="row">
                    <!-- Form to add direct graduates -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Add Existing Graduate</div>
                            <div class="card-body">
                                <form action="admin.php" method="POST">
                                    <input type="hidden" name="action" value="add_direct_graduate">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Graduate Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter graduate name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Graduation Year / Batch</label>
                                        <input type="text" name="batch" class="form-control" placeholder="e.g. BISc-5 (Batch of 2025)" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Add Graduate</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Table showing all graduates -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Graduate Student Directory</div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Name</th>
                                                <th>Graduated Batch</th>
                                                <th>Documents</th>
                                                <th>Source</th>
                                                <th class="text-end pe-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($graduated_students) && empty($alumni_direct)): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center p-4 text-muted">No graduates found. Promote BISc-5 or add above.</td>
                                                </tr>
                                            <?php else: ?>
                                                <!-- Promoted Graduates -->
                                                <?php foreach ($graduated_students as $s): ?>
                                                    <tr>
                                                        <td class="ps-3 fw-bold"><?= htmlspecialchars($s['name']) ?></td>
                                                         <td><?= htmlspecialchars($s['graduation_batch'] ?: 'BISc-5') ?></td>
                                                         <td>
                                                             <?php if (!empty($s['document_card'])): ?>
                                                                 <a href="<?= htmlspecialchars($s['document_card']) ?>" target="_blank" class="badge bg-primary text-decoration-none me-1"><i class="fa fa-id-card"></i> Aadhaar</a>
                                                             <?php endif; ?>
                                                             <?php if (!empty($s['birth_certificate'])): ?>
                                                                 <a href="<?= htmlspecialchars($s['birth_certificate']) ?>" target="_blank" class="badge bg-info text-decoration-none me-1"><i class="fa fa-file-alt"></i> Birth Cert</a>
                                                             <?php endif; ?>
                                                             <?php if (!empty($s['marks_card'])): ?>
                                                                 <a href="<?= htmlspecialchars($s['marks_card']) ?>" target="_blank" class="badge bg-warning text-dark text-decoration-none"><i class="fa fa-file-invoice"></i> Marks Card</a>
                                                             <?php endif; ?>
                                                         </td>
                                                         <td><span class="badge bg-success">Promoted Student</span></td>
                                                         <td class="text-end pe-3">
                                                             <a href="admin.php?delete_student=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete graduate record?')">Delete</a>
                                                         </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                
                                                <!-- Direct Alumni Graduates -->
                                                <?php foreach ($alumni_direct as $a): ?>
                                                    <tr>
                                                        <td class="ps-3 fw-bold"><?= htmlspecialchars($a['name']) ?></td>
                                                         <td><?= htmlspecialchars($a['batch'] ?: 'N/A') ?></td>
                                                         <td><span class="text-muted small">Direct Input</span></td>
                                                         <td><span class="badge bg-secondary">Direct Alumni Entry</span></td>
                                                         <td class="text-end pe-3">
                                                             <a href="admin.php?delete_alumni=<?= $a['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete alumni record?')">Delete</a>
                                                         </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Faculty Management -->
            <div class="tab-pane fade" id="faculties-pane" role="tabpanel" aria-labelledby="faculties-tab">
                <div class="row">
                    <!-- Form to add faculties -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Register Faculty Member</div>
                            <div class="card-body">
                                <form action="admin.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="add_faculty">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Faculty Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Faculty Photo</label>
                                        <input type="file" name="photo" class="form-control" accept="image/*">
                                        <div class="form-text">Optional. Upload a professional headshot.</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Designation</label>
                                        <input type="text" name="designation" class="form-control" placeholder="e.g. Principal, Sunni Scholar, Arabic Prof">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Username</label>
                                        <input type="text" name="username" class="form-control" placeholder="Login username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Login Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Secret password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Add Faculty Member</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Table showing faculties -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Faculty Registry</div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Photo</th>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Username</th>
                                                <th class="text-end pe-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($faculties)): ?>
                                                <tr>
                                                    <td colspan="5" class="text-center p-4 text-muted">No faculty members registered. Add one on the left.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($faculties as $f): ?>
                                                    <tr>
                                                        <td class="ps-3">
                                                            <?php if (!empty($f['photo'])): ?>
                                                                <img src="<?= htmlspecialchars($f['photo']) ?>" alt="Faculty Photo" style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%; border: 2px solid #1b365d;">
                                                            <?php else: ?>
                                                                <div style="width: 45px; height: 45px; background-color: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #999; border: 2px solid #ccc;">
                                                                    <i class="fa fa-user-tie"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="fw-bold">
                                                            <?= htmlspecialchars($f['name']) ?>
                                                            <?php if (isset($f['is_principal']) && $f['is_principal'] == 1): ?>
                                                                <span class="badge bg-warning text-dark ms-2"><i class="fa fa-crown me-1"></i>Principal</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= htmlspecialchars($f['designation']) ?></td>
                                                        <td><code><?= htmlspecialchars($f['username']) ?></code></td>
                                                        <td class="text-end pe-3">
                                                            <?php if (!isset($f['is_principal']) || $f['is_principal'] == 0): ?>
                                                                <a href="admin.php?set_principal=<?= $f['id'] ?>" class="btn btn-sm btn-outline-warning me-1 text-dark" onclick="return confirm('Select this faculty member as Principal?')"><i class="fa fa-award me-1"></i>Set Principal</a>
                                                            <?php endif; ?>
                                                            <a href="admin.php?delete_faculty=<?= $f['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete faculty member?')"><i class="fa fa-trash"></i></a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Calendar Events -->
            <div class="tab-pane fade" id="events-pane" role="tabpanel" aria-labelledby="events-tab">
                <div class="row">
                    <!-- Form to add events -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Create Calendar Event</div>
                            <div class="card-body">
                                <form action="admin.php" method="POST">
                                    <input type="hidden" name="action" value="add_event">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Event Title</label>
                                        <input type="text" name="title" class="form-control" placeholder="Event Name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Event Date</label>
                                        <input type="date" name="event_date" class="form-control" style="height: auto;" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Event Time</label>
                                        <input type="time" name="event_time" class="form-control" style="height: auto;">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Description</label>
                                        <textarea name="description" class="form-control" rows="3" placeholder="Details of the event"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Add to Calendar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Table showing events -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Calendar Events List</div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3">Title</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th class="text-end pe-3">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($events)): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center p-4 text-muted">No calendar events added. Add one on the left.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($events as $ev): ?>
                                                    <tr>
                                                        <td class="ps-3 fw-bold"><?= htmlspecialchars($ev['title']) ?></td>
                                                        <td><?= date('d-M-Y', strtotime($ev['event_date'])) ?></td>
                                                        <td><?= !empty($ev['event_time']) ? date('h:i A', strtotime($ev['event_time'])) : 'All Day' ?></td>
                                                        <td class="text-end pe-3">
                                                            <a href="admin.php?delete_event=<?= $ev['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete calendar event?')">Delete</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
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
