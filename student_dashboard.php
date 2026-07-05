<?php
require_once 'db.php';

// Authentication Check
if (!isset($_SESSION['student_id'])) {
    header("Location: index.php");
    exit;
}

$student_id = intval($_SESSION['student_id']);
$student = null;

if (DB_ACTIVE) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $student = $res->fetch_assoc();
    }
} else {
    // Fallback
    $file = getFallbackFile();
    $data = json_decode(file_get_contents($file), true);
    foreach ($data['students'] as $s) {
        if ($s['id'] == $student_id) {
            $student = $s;
            break;
        }
    }
}

if (!$student) {
    // If student record not found, destroy session and redirect
    unset($_SESSION['student_id']);
    header("Location: index.php");
    exit;
}

// Handle Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['student_id']);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Al Ihsan Da'awa College</title>
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
        .detail-label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
            color: #c5a85c;
            margin-bottom: 2px;
        }
        .detail-value {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 15px;
        }
        .badge-batch {
            background-color: #c5a85c;
            color: #1b365d;
            font-weight: 700;
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <header class="dashboard-header">
        <div class="container d-flex justify-content-between align-items-center">
            <h1 class="header-title">AL IHSAN STUDENT PORTAL</h1>
            <a href="student_dashboard.php?logout=1" class="btn btn-outline-light btn-sm fw-bold px-3">Logout <i class="fa fa-sign-out-alt ms-1"></i></a>
        </div>
    </header>

    <div class="container my-5">
        <div class="row">
            
            <!-- Left Profile Summary Card -->
            <div class="col-lg-3">
                <div class="card text-center p-4">
                    <div class="profile-img-placeholder">
                        <?= strtoupper(substr($student['name'], 0, 1)) ?>
                    </div>
                    <h5 class="fw-bold mb-1" style="color: #1b365d;"><?= htmlspecialchars($student['name']) ?></h5>
                    <p class="text-muted small mb-3">Enrolled Student</p>
                    <div class="mb-3">
                        <span class="badge-batch">Batch: <?= htmlspecialchars($student['current_batch']) ?></span>
                    </div>
                    <hr>
                    <div class="text-start mt-3">
                        <p class="mb-2"><strong style="color: #1b365d;">Academic Year:</strong> <span class="float-end text-muted"><?= htmlspecialchars($student['academic_year'] ?: 'N/A') ?></span></p>
                        <p class="mb-0"><strong style="color: #1b365d;">Medium:</strong> <span class="float-end text-muted"><?= htmlspecialchars($student['medium'] ?: 'N/A') ?></span></p>
                    </div>
                </div>
            </div>

            <!-- Right Detail Panels -->
            <div class="col-lg-9">
                
                <!-- 1. Personal Details -->
                <div class="card">
                    <div class="card-header"><i class="fa fa-user me-2"></i> Personal Information</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="detail-label">Date of Birth</div>
                                <div class="detail-value"><?= htmlspecialchars($student['dob'] ? date('d-M-Y', strtotime($student['dob'])) : 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Place of Birth</div>
                                <div class="detail-value"><?= htmlspecialchars($student['pob'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Age</div>
                                <div class="detail-value"><?= htmlspecialchars($student['age'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Gender</div>
                                <div class="detail-value"><?= htmlspecialchars($student['gender'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Mother Tongue</div>
                                <div class="detail-value"><?= htmlspecialchars($student['mother_tongue'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Student Aadhaar Number</div>
                                <div class="detail-value"><?= htmlspecialchars($student['student_aadhaar'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Religion</div>
                                <div class="detail-value"><?= htmlspecialchars($student['religion'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Caste</div>
                                <div class="detail-value"><?= htmlspecialchars($student['caste'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Nationality</div>
                                <div class="detail-value"><?= htmlspecialchars($student['nationality'] ?: 'N/A') ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Address & Contact -->
                <div class="card">
                    <div class="card-header"><i class="fa fa-envelope me-2"></i> Correspondence & Contact</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="detail-label">Residential Address</div>
                                <div class="detail-value"><?= htmlspecialchars($student['address'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Pin Code</div>
                                <div class="detail-value"><?= htmlspecialchars($student['pincode'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Email ID</div>
                                <div class="detail-value"><?= htmlspecialchars($student['email'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">WhatsApp Number</div>
                                <div class="detail-value"><?= htmlspecialchars($student['whatsapp_no'] ?: 'N/A') ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Parental Details -->
                <div class="card">
                    <div class="card-header"><i class="fa fa-users me-2"></i> Parent & Guardian Information</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-label">Father's Name</div>
                                <div class="detail-value"><?= htmlspecialchars($student['father_name'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Mother's Name</div>
                                <div class="detail-value"><?= htmlspecialchars($student['mother_name'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Father's Phone</div>
                                <div class="detail-value"><?= htmlspecialchars($student['father_phone'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Mother's Phone</div>
                                <div class="detail-value"><?= htmlspecialchars($student['mother_phone'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Father's Aadhaar Number</div>
                                <div class="detail-value"><?= htmlspecialchars($student['father_aadhaar'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Mother's Aadhaar Number</div>
                                <div class="detail-value"><?= htmlspecialchars($student['mother_aadhaar'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Ration / Patient Card Number</div>
                                <div class="detail-value"><?= htmlspecialchars($student['ration_card'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Father's Occupation</div>
                                <div class="detail-value"><?= htmlspecialchars($student['father_occupation'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Mother's Occupation</div>
                                <div class="detail-value"><?= htmlspecialchars($student['mother_occupation'] ?: 'N/A') ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Prior Education -->
                <div class="card">
                    <div class="card-header"><i class="fa fa-graduation-cap me-2"></i> Previous School Records</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-label">Last School Attended</div>
                                <div class="detail-value"><?= htmlspecialchars($student['last_school_name'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">School Medium</div>
                                <div class="detail-value"><?= htmlspecialchars($student['last_school_medium'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-8">
                                <div class="detail-label">School Address</div>
                                <div class="detail-value"><?= htmlspecialchars($student['last_school_address'] ?: 'N/A') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="detail-label">Last School Class</div>
                                <div class="detail-value"><?= htmlspecialchars($student['last_school_class'] ?: 'N/A') ?></div>
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
