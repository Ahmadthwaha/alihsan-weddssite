<?php
// Enforce HTTPS Redirection in production
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== 'localhost' && $_SERVER['HTTP_HOST'] !== '127.0.0.1') {
        $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('Location: ' . $location);
        exit;
    }
}

// Session security configuration
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== "off") {
        ini_set('session.cookie_secure', 1);
    }
    session_start();
}
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'alihsan';

// Connect to MySQL
// Turn off mysqli exceptions to prevent HTTP 500 fatal errors on query failures
mysqli_report(MYSQLI_REPORT_OFF);

// Try direct connection to the database first (essential for shared hosting where CREATE DATABASE is restricted)
$conn = @new mysqli($host, $user, $pass, $dbname);
$db_connected = false;

if (!$conn->connect_error) {
    $db_connected = true;
} else {
    // If direct connection failed, try connecting to host and creating the database (for local XAMPP)
    $conn = @new mysqli($host, $user, $pass);
    if (!$conn->connect_error) {
        $conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8 COLLATE utf8_general_ci");
        if ($conn->select_db($dbname)) {
            $db_connected = true;
        }
    }
}

if (!$db_connected) {
    define('DB_ACTIVE', false);
} else {
    
    // Self-healing database upgrade checks
    $check_stud = $conn->query("SHOW TABLES LIKE 'students'");
    if ($check_stud && $check_stud->num_rows > 0) {
        $check_col = $conn->query("SHOW COLUMNS FROM `students` LIKE 'status'");
        if ($check_col && $check_col->num_rows === 0) {
            $conn->query("DROP TABLE IF EXISTS `students`");
        }
    }
    
    $check_fac = $conn->query("SHOW TABLES LIKE 'faculties'");
    if ($check_fac && $check_fac->num_rows > 0) {
        $check_col_fac = $conn->query("SHOW COLUMNS FROM `faculties` LIKE 'photo'");
        if ($check_col_fac && $check_col_fac->num_rows === 0) {
            $conn->query("DROP TABLE IF EXISTS `faculties`");
        }
    }
    
    // Create students table with 32 details (including photo and status)
    $conn->query("CREATE TABLE IF NOT EXISTS `students` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `current_batch` VARCHAR(50) NOT NULL, -- 8th, 9th, 10th, HISc-1, HISc-2, BISc-1, BISc-2, BISc-3, BISc-4, BISc-5
        `medium` VARCHAR(50) NULL,
        `gender` VARCHAR(20) NULL,
        `dob` DATE NULL,
        `pob` VARCHAR(100) NULL,
        `age` INT NULL,
        `address` TEXT NULL,
        `pincode` VARCHAR(20) NULL,
        `email` VARCHAR(100) NULL,
        `father_name` VARCHAR(100) NULL,
        `mother_name` VARCHAR(100) NULL,
        `father_phone` VARCHAR(20) NULL,
        `mother_phone` VARCHAR(20) NULL,
        `whatsapp_no` VARCHAR(20) NULL,
        `religion` VARCHAR(100) NULL,
        `caste` VARCHAR(100) NULL,
        `mother_tongue` VARCHAR(100) NULL,
        `nationality` VARCHAR(100) NULL,
        `father_aadhaar` VARCHAR(20) NULL,
        `mother_aadhaar` VARCHAR(20) NULL,
        `student_aadhaar` VARCHAR(20) NULL,
        `father_occupation` VARCHAR(100) NULL,
        `mother_occupation` VARCHAR(100) NULL,
        `ration_card` VARCHAR(50) NULL,
        `last_school_name` VARCHAR(150) NULL,
        `last_school_address` TEXT NULL,
        `last_school_class` VARCHAR(50) NULL,
        `last_school_medium` VARCHAR(50) NULL,
        `academic_year` VARCHAR(20) NULL,
        `is_graduated` TINYINT DEFAULT 0,
        `graduation_batch` VARCHAR(50) NULL,
        `document_card` VARCHAR(255) NULL,
        `birth_certificate` VARCHAR(255) NULL,
        `marks_card` VARCHAR(255) NULL,
        `photo` VARCHAR(255) NULL,
        `status` VARCHAR(20) DEFAULT 'requested',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create faculties table with photo and is_principal
    $conn->query("CREATE TABLE IF NOT EXISTS `faculties` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `designation` VARCHAR(100) NULL,
        `username` VARCHAR(50) UNIQUE NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `photo` VARCHAR(255) NULL,
        `is_principal` TINYINT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create alumni table for direct graduates
    $conn->query("CREATE TABLE IF NOT EXISTS `alumni` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(100) NOT NULL,
        `batch` VARCHAR(50) NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Create events table for monthly calendar
    $conn->query("CREATE TABLE IF NOT EXISTS `events` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(150) NOT NULL,
        `event_date` DATE NOT NULL,
        `event_time` TIME NULL,
        `description` TEXT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    define('DB_ACTIVE', true);
}

// Helper to get counts
function getStatCount($table) {
    global $conn;
    if (!defined('DB_ACTIVE') || !DB_ACTIVE) {
        return getFallbackCount($table);
    }
    
    if ($table === 'students') {
        // Count active students only (not graduated and accepted status)
        $res = $conn->query("SELECT COUNT(*) as total FROM `students` WHERE `is_graduated` = 0 AND (status IS NULL OR status = 'accepted')");
        $row = $res->fetch_assoc();
        return $row['total'];
    } elseif ($table === 'alumni') {
        // Count older direct alumni AND newly graduated students
        $res1 = $conn->query("SELECT COUNT(*) as total FROM `alumni`");
        $row1 = $res1->fetch_assoc();
        
        $res2 = $conn->query("SELECT COUNT(*) as total FROM `students` WHERE `is_graduated` = 1");
        $row2 = $res2->fetch_assoc();
        
        return $row1['total'] + $row2['total'];
    }
    
    $result = $conn->query("SELECT COUNT(*) as total FROM `$table`");
    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    return 0;
}

// Helper to ensure fallback file and directory exist with a valid structure
function getFallbackFile() {
    $dir = __DIR__ . '/data';
    $file = $dir . '/stats_fallback.json';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    if (!file_exists($file)) {
        @file_put_contents($file, json_encode([
            'students' => [],
            'faculties' => [],
            'alumni' => [],
            'events' => []
        ]));
    } else {
        // Safe check: if file exists but is empty or invalid JSON, re-initialize
        $content = @file_get_contents($file);
        $data = json_decode($content, true);
        if (!$data || !is_array($data)) {
            @file_put_contents($file, json_encode([
                'students' => [],
                'faculties' => [],
                'alumni' => [],
                'events' => []
            ]));
        }
    }
    return $file;
}

// Fallback logic using local JSON file
function getFallbackCount($type) {
    $file = getFallbackFile();
    $data = json_decode(file_get_contents($file), true);
    
    if ($type === 'students') {
        $activeCount = 0;
        foreach ($data['students'] as $s) {
            if ((!isset($s['is_graduated']) || $s['is_graduated'] == 0) && (!isset($s['status']) || $s['status'] === 'accepted')) {
                $activeCount++;
            }
        }
        return $activeCount;
    } elseif ($type === 'alumni') {
        $gradCount = 0;
        foreach ($data['students'] as $s) {
            if (isset($s['is_graduated']) && $s['is_graduated'] == 1) {
                $gradCount++;
            }
        }
        return count($data['alumni']) + $gradCount;
    }
    
    return isset($data[$type]) ? count($data[$type]) : 0;
}

// Get all records for list
function getRecords($table) {
    global $conn;
    if (!defined('DB_ACTIVE') || !DB_ACTIVE) {
        $file = getFallbackFile();
        $data = json_decode(file_get_contents($file), true);
        return isset($data[$table]) ? $data[$table] : [];
    }
    
    $result = $conn->query("SELECT * FROM `$table` ORDER BY id DESC");
    $records = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
    }
    return $records;
}
