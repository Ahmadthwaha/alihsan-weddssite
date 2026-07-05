<?php 
require_once 'db.php';
include 'header.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect all fields
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $current_batch = isset($_POST['current_batch']) ? trim($_POST['current_batch']) : '';
    $medium = isset($_POST['medium']) ? trim($_POST['medium']) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $dob = isset($_POST['dob']) && $_POST['dob'] !== '' ? trim($_POST['dob']) : null;
    $pob = isset($_POST['pob']) ? trim($_POST['pob']) : '';
    $age = isset($_POST['age']) && $_POST['age'] !== '' ? intval($_POST['age']) : null;
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $pincode = isset($_POST['pincode']) ? trim($_POST['pincode']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $father_name = isset($_POST['father_name']) ? trim($_POST['father_name']) : '';
    $mother_name = isset($_POST['mother_name']) ? trim($_POST['mother_name']) : '';
    $father_phone = isset($_POST['father_phone']) ? trim($_POST['father_phone']) : '';
    $mother_phone = isset($_POST['mother_phone']) ? trim($_POST['mother_phone']) : '';
    $whatsapp_no = isset($_POST['whatsapp_no']) ? trim($_POST['whatsapp_no']) : '';
    $religion = isset($_POST['religion']) ? trim($_POST['religion']) : '';
    $caste = isset($_POST['caste']) ? trim($_POST['caste']) : '';
    $mother_tongue = isset($_POST['mother_tongue']) ? trim($_POST['mother_tongue']) : '';
    $nationality = isset($_POST['nationality']) ? trim($_POST['nationality']) : '';
    $father_aadhaar = isset($_POST['father_aadhaar']) ? trim($_POST['father_aadhaar']) : '';
    $mother_aadhaar = isset($_POST['mother_aadhaar']) ? trim($_POST['mother_aadhaar']) : '';
    $student_aadhaar = isset($_POST['student_aadhaar']) ? trim($_POST['student_aadhaar']) : '';
    $father_occupation = isset($_POST['father_occupation']) ? trim($_POST['father_occupation']) : '';
    $mother_occupation = isset($_POST['mother_occupation']) ? trim($_POST['mother_occupation']) : '';
    $ration_card = isset($_POST['ration_card']) ? trim($_POST['ration_card']) : '';
    $last_school_name = isset($_POST['last_school_name']) ? trim($_POST['last_school_name']) : '';
    $last_school_address = isset($_POST['last_school_address']) ? trim($_POST['last_school_address']) : '';
    $last_school_class = isset($_POST['last_school_class']) ? trim($_POST['last_school_class']) : '';
    $last_school_medium = isset($_POST['last_school_medium']) ? trim($_POST['last_school_medium']) : '';
    $academic_year = isset($_POST['academic_year']) ? trim($_POST['academic_year']) : '';

    if (empty($name)) {
        $error_message = "Student full name is required.";
    } elseif (empty($current_batch)) {
        $error_message = "Please select the admitted class/batch.";
    } else {
        // Handle file uploads
        $document_card_path = '';
        $birth_certificate_path = '';
        $marks_card_path = '';
        $photo_path = '';
        $upload_dir = __DIR__ . '/uploads/documents/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Student passport photo upload (Optional)
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('photo_', true) . '.' . $ext;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $filename)) {
                $photo_path = 'uploads/documents/' . $filename;
            } else {
                $error_message = "Failed to save Student Photo file.";
            }
        } elseif (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $err = $_FILES['photo']['error'];
            if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
                $error_message = "Student Passport Photo exceeds the allowed file size limit.";
            } else {
                $error_message = "Error uploading Student Passport Photo.";
            }
        }

        // Aadhaar/ID Document upload (Optional)
        if (empty($error_message)) {
            if (isset($_FILES['document_card']) && $_FILES['document_card']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['document_card']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('doc_', true) . '.' . $ext;
                if (move_uploaded_file($_FILES['document_card']['tmp_name'], $upload_dir . $filename)) {
                    $document_card_path = 'uploads/documents/' . $filename;
                } else {
                    $error_message = "Failed to save Aadhaar/ID Document file.";
                }
            } elseif (isset($_FILES['document_card']) && $_FILES['document_card']['error'] !== UPLOAD_ERR_NO_FILE) {
                $err = $_FILES['document_card']['error'];
                if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
                    $error_message = "Aadhaar Card/ID Document exceeds the allowed file size limit.";
                } else {
                    $error_message = "Error uploading Aadhaar Card/ID Document.";
                }
            }
        }

        // Birth Certificate upload (Optional)
        if (empty($error_message)) {
            if (isset($_FILES['birth_certificate']) && $_FILES['birth_certificate']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['birth_certificate']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('birth_', true) . '.' . $ext;
                if (move_uploaded_file($_FILES['birth_certificate']['tmp_name'], $upload_dir . $filename)) {
                    $birth_certificate_path = 'uploads/documents/' . $filename;
                } else {
                    $error_message = "Failed to save Birth Certificate file.";
                }
            } elseif (isset($_FILES['birth_certificate']) && $_FILES['birth_certificate']['error'] !== UPLOAD_ERR_NO_FILE) {
                $err = $_FILES['birth_certificate']['error'];
                if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
                    $error_message = "Birth Certificate exceeds the allowed file size limit.";
                } else {
                    $error_message = "Error uploading Birth Certificate.";
                }
            }
        }

        // Marks Card upload (Optional)
        if (empty($error_message)) {
            if (isset($_FILES['marks_card']) && $_FILES['marks_card']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['marks_card']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('marks_', true) . '.' . $ext;
                if (move_uploaded_file($_FILES['marks_card']['tmp_name'], $upload_dir . $filename)) {
                    $marks_card_path = 'uploads/documents/' . $filename;
                } else {
                    $error_message = "Failed to save Marks Card file.";
                }
            } elseif (isset($_FILES['marks_card']) && $_FILES['marks_card']['error'] !== UPLOAD_ERR_NO_FILE) {
                $err = $_FILES['marks_card']['error'];
                if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
                    $error_message = "Marks Card/Report Sheet exceeds the allowed file size limit.";
                } else {
                    $error_message = "Error uploading Marks Card/Report Sheet.";
                }
            }
        }

        if (empty($error_message)) {
            if (DB_ACTIVE) {
                $stmt = $conn->prepare("INSERT INTO `students` (
                    `name`, `current_batch`, `medium`, `gender`, `dob`, `pob`, `age`, 
                    `address`, `pincode`, `email`, `father_name`, `mother_name`, `father_phone`, 
                    `mother_phone`, `whatsapp_no`, `religion`, `caste`, `mother_tongue`, 
                    `nationality`, `father_aadhaar`, `mother_aadhaar`, `student_aadhaar`, 
                    `father_occupation`, `mother_occupation`, `ration_card`, `last_school_name`, 
                    `last_school_address`, `last_school_class`, `last_school_medium`, `academic_year`,
                    `document_card`, `birth_certificate`, `marks_card`, `photo`, `status`
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'requested')");
                
                $stmt->bind_param("ssssssisssssssssssssssssssssssssss", 
                    $name, $current_batch, $medium, $gender, $dob, $pob, $age,
                    $address, $pincode, $email, $father_name, $mother_name, $father_phone,
                    $mother_phone, $whatsapp_no, $religion, $caste, $mother_tongue,
                    $nationality, $father_aadhaar, $mother_aadhaar, $student_aadhaar,
                    $father_occupation, $mother_occupation, $ration_card, $last_school_name,
                    $last_school_address, $last_school_class, $last_school_medium, $academic_year,
                    $document_card_path, $birth_certificate_path, $marks_card_path, $photo_path
                );
                
                if ($stmt->execute()) {
                    $success_message = "Application submitted successfully! Your request is pending admin approval.";
                } else {
                    $error_message = "Error submitting: " . $stmt->error;
                }
            } else {
                // Fallback storage
                $file = getFallbackFile();
                $data = json_decode(file_get_contents($file), true);
                if (!isset($data['students']) || !is_array($data['students'])) {
                    $data['students'] = [];
                }
                $data['students'][] = [
                    'id' => time(),
                    'name' => $name,
                    'current_batch' => $current_batch,
                    'medium' => $medium,
                    'gender' => $gender,
                    'dob' => $dob,
                    'pob' => $pob,
                    'age' => $age,
                    'address' => $address,
                    'pincode' => $pincode,
                    'email' => $email,
                    'father_name' => $father_name,
                    'mother_name' => $mother_name,
                    'father_phone' => $father_phone,
                    'mother_phone' => $mother_phone,
                    'whatsapp_no' => $whatsapp_no,
                    'religion' => $religion,
                    'caste' => $caste,
                    'mother_tongue' => $mother_tongue,
                    'nationality' => $nationality,
                    'father_aadhaar' => $father_aadhaar,
                    'mother_aadhaar' => $mother_aadhaar,
                    'student_aadhaar' => $student_aadhaar,
                    'father_occupation' => $father_occupation,
                    'mother_occupation' => $mother_occupation,
                    'ration_card' => $ration_card,
                    'last_school_name' => $last_school_name,
                    'last_school_address' => $last_school_address,
                    'last_school_class' => $last_school_class,
                    'last_school_medium' => $last_school_medium,
                    'academic_year' => $academic_year,
                    'is_graduated' => 0,
                    'document_card' => $document_card_path,
                    'birth_certificate' => $birth_certificate_path,
                    'marks_card' => $marks_card_path,
                    'photo' => $photo_path,
                    'status' => 'requested'
                ];
                file_put_contents($file, json_encode($data));
                $success_message = "Application submitted successfully (Local Fallback Data)! Pending admin approval.";
            }
        }
    }
}
?>

<!-- banner area start -->
<div class="rts-breadcrumb-area" style="background: linear-gradient(135deg, #1b365d 0%, #0d1e3d 100%); padding: 80px 0; position: relative;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner text-center" style="position: relative; z-index: 2;">
                    <h1 class="title text-white" style="font-size: 45px; font-weight: 800; text-transform: uppercase;">Online Admission</h1>
                    <ul class="breadcrumb-navigation" style="display: flex; justify-content: center; gap: 10px; list-style: none; padding: 0; color: rgba(255,255,255,0.8); font-size: 14px;">
                        <li><a href="index.php" style="color: #fff; text-decoration: none;">Home</a></li>
                        <li>/</li>
                        <li class="active" style="color: #c5a85c;">Apply Online</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner area end -->

<!-- content area start -->
<style>
    .apply-card {
        padding: 20px !important;
        border-radius: 12px !important;
    }
    @media (min-width: 768px) {
        .apply-card {
            padding: 50px !important;
        }
    }
    .form-control, .form-select {
        height: 48px !important;
        font-size: 14px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 6px !important;
        padding: 10px 15px !important;
        background-color: #f9fafb !important;
        transition: all 0.2s ease-in-out !important;
        color: #333 !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: #1b365d !important;
        background-color: #fff !important;
        box-shadow: 0 0 0 3px rgba(27, 54, 93, 0.1) !important;
    }
    .form-label {
        font-size: 13px !important;
        color: #374151 !important;
        margin-bottom: 6px !important;
        display: inline-block !important;
        font-weight: 700 !important;
    }
    h5.fw-bold {
        font-size: 16px !important;
        margin-top: 30px !important;
        margin-bottom: 20px !important;
        color: #1b365d !important;
        border-bottom: 2px solid #e5e7eb !important;
        padding-bottom: 8px !important;
    }
    .rts-theme-btn {
        background: #1b365d !important;
        color: #fff !important;
        border-radius: 6px !important;
        padding: 14px 30px !important;
        font-size: 15px !important;
        width: 100% !important;
        transition: all 0.2s !important;
    }
    @media (min-width: 768px) {
        .rts-theme-btn {
            width: auto !important;
        }
    }
    .rts-theme-btn:hover {
        background: #c5a85c !important;
        color: #1b365d !important;
    }
</style>
<section class="apply-page py-5 my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="apply-card p-5" style="background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: 8px;">
                    <h3 class="mb-2 text-center" style="color: #1b365d; font-weight: 800;">Admission Application Form</h3>
                    <p class="text-muted text-center mb-5">Please fill in the fields below. Note: Forms can be submitted even with partial information.</p>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success mb-4"><?= $success_message ?></div>
                    <?php endif; ?>

                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger mb-4"><?= $error_message ?></div>
                    <?php endif; ?>

                    <form action="apply.php" method="POST" enctype="multipart/form-data">
                        
                        <!-- 1. Admission Details -->
                        <h5 class="mb-4 fw-bold" style="color: #c5a85c; border-bottom: 2px solid #eee; padding-bottom: 8px;">1. Admission & Batch</h5>
                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Student Full Name *</label>
                                <input type="text" name="name" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="Full name of student" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Admitted to Class *</label>
                                <select name="current_batch" class="form-select" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" required>
                                    <option value="" disabled selected>Select Batch</option>
                                    <option value="8th">8th Batch</option>
                                    <option value="9th">9th Batch</option>
                                    <option value="10th">10th Batch</option>
                                    <option value="HISc-1">HISc-1</option>
                                    <option value="HISc-2">HISc-2</option>
                                    <option value="BISc-1">BISc-1</option>
                                    <option value="BISc-2">BISc-2</option>
                                    <option value="BISc-3">BISc-3</option>
                                    <option value="BISc-4">BISc-4</option>
                                    <option value="BISc-5">BISc-5</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Academic Year</label>
                                <input type="text" name="academic_year" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="e.g. 2026-2027">
                            </div>
                        </div>

                        <!-- 2. Student Profile -->
                        <h5 class="mb-4 fw-bold" style="color: #c5a85c; border-bottom: 2px solid #eee; padding-bottom: 8px;">2. Student Profile</h5>
                        <div class="row g-3 mb-5">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px; height: auto;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Place of Birth</label>
                                <input type="text" name="pob" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="City/Village">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Age</label>
                                <input type="number" name="age" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="Age">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Medium of Instruction</label>
                                <input type="text" name="medium" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="e.g. English, Kannada">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Gender</label>
                                <select name="gender" class="form-select" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;">
                                    <option value="" selected>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Mother Tongue</label>
                                <input type="text" name="mother_tongue" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="e.g. Kannada, Tulu">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Religion</label>
                                <input type="text" name="religion" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="e.g. Islam">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Caste</label>
                                <input type="text" name="caste" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="Caste category">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Nationality</label>
                                <input type="text" name="nationality" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" value="Indian">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Student Aadhaar Number</label>
                                <input type="text" name="student_aadhaar" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="12-digit Aadhaar">
                            </div>
                        </div>

                        <!-- 3. Address & Contact -->
                        <h5 class="mb-4 fw-bold" style="color: #c5a85c; border-bottom: 2px solid #eee; padding-bottom: 8px;">3. Contact Info & Address</h5>
                        <div class="row g-3 mb-5">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Address for Correspondence</label>
                                <input type="text" name="address" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="House Name, Street Name, Location">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Pin Code</label>
                                <input type="text" name="pincode" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="6-digit Pin">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email ID</label>
                                <input type="email" name="email" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="student@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Student WhatsApp Number</label>
                                <input type="tel" name="whatsapp_no" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="+91 Mobile number">
                            </div>
                        </div>

                        <!-- 4. Parents Profile -->
                        <h5 class="mb-4 fw-bold" style="color: #c5a85c; border-bottom: 2px solid #eee; padding-bottom: 8px;">4. Parental Profile</h5>
                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Father's Name</label>
                                <input type="text" name="father_name" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="Father's full name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mother's Name</label>
                                <input type="text" name="mother_name" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="Mother's full name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Father's Phone Number</label>
                                <input type="tel" name="father_phone" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="Father's contact">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mother's Phone Number</label>
                                <input type="tel" name="mother_phone" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="Mother's contact">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Father's Aadhaar Number</label>
                                <input type="text" name="father_aadhaar" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="12-digit Aadhaar">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Mother's Aadhaar Number</label>
                                <input type="text" name="mother_aadhaar" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="12-digit Aadhaar">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Ration / Patient Card Number</label>
                                <input type="text" name="ration_card" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="Ration card no">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Father's Occupation</label>
                                <input type="text" name="father_occupation" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="Occupation">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Mother's Occupation</label>
                                <input type="text" name="mother_occupation" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="Occupation">
                            </div>
                        </div>

                        <!-- 5. Previous Academic Details -->
                        <h5 class="mb-4 fw-bold" style="color: #c5a85c; border-bottom: 2px solid #eee; padding-bottom: 8px;">5. Last School Attended Details</h5>
                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Last School / College Name</label>
                                <input type="text" name="last_school_name" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="Name of school">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Last School Medium</label>
                                <input type="text" name="last_school_medium" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="e.g. Kannada, English">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Last School Address</label>
                                <input type="text" name="last_school_address" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="City, State">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Last Attended Class</label>
                                <input type="text" name="last_school_class" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px;" placeholder="e.g. Class 7, Class 10">
                            </div>
                        </div>

                        <!-- 6. Required Documents Upload -->
                        <h5 class="mb-4 fw-bold" style="color: #c5a85c; border-bottom: 2px solid #eee; padding-bottom: 8px;">6. Document & Photo Uploads (Optional)</h5>
                        <div class="row g-3 mb-5">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Passport Size Photo</label>
                                <input type="file" name="photo" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px; height: auto;" accept="image/*">
                                <div class="form-text">Please upload a clear passport size photograph.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Aadhaar Card / ID Document</label>
                                <input type="file" name="document_card" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px; height: auto;">
                                <div class="form-text">Please upload a clear scan or picture of Aadhaar Card.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Birth Certificate</label>
                                <input type="file" name="birth_certificate" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px; height: auto;">
                                <div class="form-text">Please upload a clear scan of the Birth Certificate.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Marks Card / Report Sheet</label>
                                <input type="file" name="marks_card" class="form-control" style="border-radius: 4px; border: 1px solid #ccc; padding: 10px; height: auto;">
                                <div class="form-text">Please upload a clear scan of the Marks Card.</div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-12 text-center mt-5">
                            <button type="submit" class="rts-theme-btn" style="border: none; padding: 15px 40px; font-weight: bold; cursor: pointer; background: #1b365d; color: #fff; border-radius: 4px;">
                                <span class="main-text">Submit Registration <i class="fa-solid fa-arrow-right ms-2"></i></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- content area end -->

<?php include 'footer.php'; ?>
