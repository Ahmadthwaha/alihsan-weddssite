<?php 
require_once 'db.php';

$login_error = '';
if (isset($_POST['login_type'])) {
    $login_type = $_POST['login_type'];
    if ($login_type === 'student') {
        $sname = trim($_POST['student_name']);
        $sdob = trim($_POST['student_dob']);
        
        if (DB_ACTIVE) {
            $stmt = $conn->prepare("SELECT * FROM students WHERE name = ? AND dob = ? AND is_graduated = 0");
            $stmt->bind_param("ss", $sname, $sdob);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $student = $res->fetch_assoc();
                $_SESSION['student_id'] = $student['id'];
                header("Location: student_dashboard.php");
                exit;
            } else {
                $login_error = "Student record not found. Please match Name and Date of Birth exactly.";
            }
        } else {
            // Fallback
            $file = getFallbackFile();
            $data = json_decode(file_get_contents($file), true);
            $found = false;
            foreach ($data['students'] as $s) {
                if (strcasecmp($s['name'], $sname) == 0 && isset($s['dob']) && $s['dob'] === $sdob && (!isset($s['is_graduated']) || $s['is_graduated'] == 0)) {
                    $_SESSION['student_id'] = $s['id'];
                    $found = true;
                    header("Location: student_dashboard.php");
                    exit;
                }
            }
            if (!$found) {
                $login_error = "Student record not found in local data.";
            }
        }
    } elseif ($login_type === 'faculty') {
        $fuser = trim($_POST['faculty_username']);
        $fpass = trim($_POST['faculty_password']);
        
        if (DB_ACTIVE) {
            $stmt = $conn->prepare("SELECT * FROM faculties WHERE username = ?");
            $stmt->bind_param("s", $fuser);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $faculty = $res->fetch_assoc();
                if (password_verify($fpass, $faculty['password']) || $fpass === $faculty['password']) {
                    $_SESSION['faculty_id'] = $faculty['id'];
                    header("Location: faculty_dashboard.php");
                    exit;
                } else {
                    $login_error = "Incorrect password!";
                }
            } else {
                $login_error = "Faculty username not found!";
            }
        } else {
            // Fallback
            $file = getFallbackFile();
            $data = json_decode(file_get_contents($file), true);
            $found = false;
            foreach ($data['faculties'] as $f) {
                if ($f['username'] === $fuser && $f['password'] === $fpass) {
                    $_SESSION['faculty_id'] = $f['id'];
                    $found = true;
                    header("Location: faculty_dashboard.php");
                    exit;
                }
            }
            if (!$found) {
                $login_error = "Faculty username not found in local data.";
            }
        }
    }
}

include 'header.php';
?>

<style>
    .rts-theme-btn .small-text {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 2px;
        line-height: 1;
    }

    /* Main button text */
    .rts-theme-btn .main-text {
        font-size: 18px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        line-height: 1;
    }

    /* Standard button sizing */
    .rts-slider-btn .rts-theme-btn {
        min-width: 140px;
        min-height: 60px;
        padding: 12px 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
</style>

<!-- hero slider start -->
<div class="rts-hero-slider rt-relative v_2">
    <div class="rts-hero-slider-active swiper swiper-data" data-swiper='{
            "slidesPerView":1,
            "effect": "fade",
            "loop": false,
            "speed": 1000,
            "navigation":{
                "nextEl":".rt-next",
                "prevEl":".rt-prev"
            },
            "autoplay":{
                "delay":"7000"
            }
}'>
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            <div class="swiper-slide">
                <div class="rts-slider-height rts-slider-overlay rt-relative">
                    <div class="rts-slider-bg" data-background=""
                        style="background: linear-gradient(135deg, #1b365d 0%, #0c1a30 100%) !important;"></div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-xl-6 col-md-8 col-sm-9">
                                <div class="rts-slider text-center">
                                    <div class="rts-slider-content">
                                        <h6 class="rts-subtitle" style="justify-content: center;">
                                            <img src="assets/images/icon/e-cap.svg" alt="education hat"> Knowledge meets Devotion
                                        </h6>
                                        <h1 class="rts-slider-title">
                                            <span class="text-white"><?= getStatCount('students') ?></span>
                                            <br>
                                            <span class="text-white">Students</span>
                                        </h1>
                                        <p class="rts-slider-desc">
                                            Empowering students with a blend of Islamic moral values and modern education, preparing them for a bright future.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="swiper-slide">
                <div class="rts-slider-height rts-slider-overlay rt-relative">
                    <div class="rts-slider-bg" data-background=""
                        style="background: linear-gradient(135deg, #1b365d 0%, #0c1a30 100%) !important;"></div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-xl-6 col-md-8 col-sm-9">
                                <div class="rts-slider text-center">
                                    <div class="rts-slider-content">
                                        <h6 class="rts-subtitle" style="justify-content: center;">
                                            <img src="assets/images/icon/e-cap.svg" alt="education hat"> Excellence in Mentorship
                                        </h6>
                                        <h1 class="rts-slider-title">
                                            <span class="text-white"><?= getStatCount('faculties') ?></span>
                                            <br>
                                            <span class="text-white">Faculties</span>
                                        </h1>
                                        <p class="rts-slider-desc">
                                            Our highly qualified and dedicated faculty team guides students through academic rigors and ethical growth.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="swiper-slide">
                <div class="rts-slider-height rts-slider-overlay rt-relative">
                    <div class="rts-slider-bg" data-background=""
                        style="background: linear-gradient(135deg, #1b365d 0%, #0c1a30 100%) !important;"></div>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-xl-6 col-md-8 col-sm-9">
                                <div class="rts-slider text-center">
                                    <div class="rts-slider-content">
                                        <h6 class="rts-subtitle" style="justify-content: center;">
                                            <img src="assets/images/icon/e-cap.svg" alt="education hat"> Our Legacy
                                        </h6>
                                        <h1 class="rts-slider-title">
                                            <span class="text-white"><?= getStatCount('alumni') ?></span>
                                            <br>
                                            <span class="text-white">Alumni</span>
                                        </h1>
                                        <p class="rts-slider-desc">
                                            Our alumni spread across the globe are a testament to our commitment to academic excellence and moral integrity.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- hero slider end -->

<!-- About Al Ihsan -->
<section class="about py-5 my-5 v__1">
    <div class="container">
        <div class="row justify-content-md-center align-items-center">
            <div class="col-lg-12 col-md-12 pb-5">
                <div class="about__content text-center text-lg-start">
                    <h2 class="rts__title">About Al Ihsan Da'awa College</h2>
                    <p class="rts__description" style="font-size: 16px; line-height: 1.8;">
                        Al Ihsan Dawah College is located in Muloor, Udupi district, Karnataka, near the National Highway. The institution offers regular-mode PUC and degree courses along with Mukhthasar-level religious studies. Additionally, students have access to government-recognized Arabic-Urdu diploma courses, computer courses, and practical Dawah training in North regions.
                    </p>
                    <p class="rts__description" style="font-size: 16px; line-height: 1.8;">
                        Hafiz students can pursue Quran research studies along with Daura, and the college provides excellent coaching in comparative religion and ideal studies. Training in modern technology and AI tools is also available. Graduates of the institution are awarded the Ihsani degree.
                    </p>
                    <a href="about.php" class="rts-nbg-btn btn-arrow">Learn more<span><i class="fa-sharp fa-regular fa-arrow-right"></i></span></a>
                </div>
            </div>
            
            <div class="col-lg-12 col-md-12 pt-5">
                <div class="rts-funfact-wrapper row text-center">
                    <div class="single-cta-item col-md-4 p-5">
                        <h2 class="single-cta-item__title text-center"><?= getStatCount('students') ?></h2>
                        <p class="text-center">Students Enrolled</p>
                    </div>
                    <div style="background-color:rgb(236, 236, 255)" class="single-cta-item col-md-4 p-5">
                        <h2 class="single-cta-item__title text-center text-dark"><?= getStatCount('faculties') ?></h2>
                        <p class="text-center text-dark">Dedicated Faculty</p>
                    </div>
                    <div class="single-cta-item col-md-4 p-5">
                        <h2 class="single-cta-item__title text-center"><?= getStatCount('alumni') ?></h2>
                        <p class="text-center">Active Alumni</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About End -->

<!-- Login & Useful Links -->
<section style="background-color:rgb(236, 236, 255);" class="tution rts__light rts-section-padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 col-md-6">
                <div class="tution__single--box">
                    <h5 class="tution__single--box--title" style="color: #1b365d; font-weight: 800;">Academic Portals</h5>

                    <?php if (!empty($login_error)): ?>
                        <div class="alert alert-danger p-2" style="font-size: 12px; border-radius: 0px; margin-bottom: 15px; border-left: 3px solid #dc3545; background-color: #fff5f5; color: #dc3545;">
                            <?= $login_error ?>
                        </div>
                    <?php endif; ?>

                    <ul class="nav nav-tabs" id="loginTab" role="tablist" style="border-bottom: 2px solid #1b365d;">
                        <li class="nav-item" style="flex: 1; text-align: center;">
                            <a class="nav-link active p-3" id="student-new-tab" data-bs-toggle="tab" href="#student-new"
                                role="tab" aria-controls="student-new" aria-selected="true" style="font-weight: 700; border-radius: 0;">
                                Student Portal
                            </a>
                        </li>
                        <li class="nav-item" style="flex: 1; text-align: center;">
                            <a class="nav-link p-3" id="student-old-tab" data-bs-toggle="tab" href="#student-old"
                                role="tab" aria-controls="student-old" aria-selected="false" style="font-weight: 700; border-radius: 0;">
                                Faculty Portal
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="loginTabContent" style="padding-top: 20px;">
                        <!-- Student Portal -->
                        <div class="tab-pane fade show active" id="student-new" role="tabpanel" aria-labelledby="student-new-tab">
                            <form action="index.php" method="POST">
                                <input type="hidden" name="login_type" value="student">
                                <div class="mb-3">
                                    <label class="form-label" style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #666; margin-bottom: 5px;">Student Full Name *</label>
                                    <input style="border: 1px solid var(--rt-primary); padding: 10px 15px; width: 100%; border-radius: 4px; font-size: 14px;" autocomplete="off" type="text" name="student_name" placeholder="Enter student name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #666; margin-bottom: 5px;">Date of Birth *</label>
                                    <input style="border: 1px solid var(--rt-primary); padding: 10px 15px; width: 100%; border-radius: 4px; font-size: 14px; height: auto;" autocomplete="off" type="date" name="student_dob" required>
                                </div>
                                <div class="mb-3">
                                    <button style="border: 1px solid var(--rt-primary); width: 100%; border-radius: 4px;" type="submit" class="rts-theme-btn2">Login Portal</button>
                                </div>
                            </form>
                        </div>

                        <!-- Faculty Portal -->
                        <div class="tab-pane fade" id="student-old" role="tabpanel" aria-labelledby="student-old-tab">
                            <form action="index.php" method="POST">
                                <input type="hidden" name="login_type" value="faculty">
                                <div class="mb-3">
                                    <label class="form-label" style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #666; margin-bottom: 5px;">Username / ID *</label>
                                    <input style="border: 1px solid var(--rt-primary); padding: 10px 15px; width: 100%; border-radius: 4px; font-size: 14px;" autocomplete="off" type="text" name="faculty_username" placeholder="Enter faculty username" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-size: 11px; font-weight: 600; text-transform: uppercase; color: #666; margin-bottom: 5px;">Password *</label>
                                    <input style="border: 1px solid var(--rt-primary); padding: 10px 15px; width: 100%; border-radius: 4px; font-size: 14px;" autocomplete="off" type="password" name="faculty_password" placeholder="Enter password" required>
                                </div>
                                <div class="mb-3">
                                    <button style="border: 1px solid var(--rt-primary); width: 100%; border-radius: 4px;" type="submit" class="rts-theme-btn2">Login Portal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="tution__single--box v__1">
                    <h5 class="tution__single--box--title">Useful Links</h5>
                    <ul class="tution__single--box--feature">
                        <li><a href="apply.php">Online Admission Information</a></li>
                        <li><a href="about.php?p=adsa">Al Ihsan ADSA Students Association</a></li>
                        <li><a href="program.php">Academic Calendar & Programs</a></li>
                        <li><a href="about.php">About Al Ihsan Da'awa College</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Login & Useful Links end -->

<!-- Programs -->
<section class="rts__section rts-section-padding">
    <div class="container">
        <div class="row">
            <div class="rts__section--wrapper text-center text-md-start">
                <h2 class="rts__section--title">Our Programs</h2>
            </div>
        </div>
        <div class="row d-flex justify-content-center g-5">
            <div class="col-lg-5 col-md-6 col-sm-12">
                <div class="rts__program--item" style="background-image: url(assets/images/program/bachelor.jpg?v=2);">
                    <h5 class="rts__program--item--title">Bachelor's Programs</h5>
                    <p class="rts__program--item--description">Deepen your knowledge in Islamic jurisprudence, Da'awa methodologies, and humanities.</p>
                    <a href="program.php?p=bachelors-program" class="rts-nbg-btn btn-arrow">Learn More<span><i class="fa-sharp fa-regular fa-arrow-right"></i></span></a>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <div class="rts__program--item" style="background-image: url(assets/images/program/short.jpg);">
                    <h5 class="rts__program--item--title">Short-Run Courses</h5>
                    <p class="rts__program--item--description">Accelerate your skills in public speaking, writing, and leadership through ADSA.</p>
                    <a href="program.php?p=short-courses" class="rts-nbg-btn btn-arrow">Learn More<span><i class="fa-sharp fa-regular fa-arrow-right"></i></span></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Programs end -->

<?php include 'footer.php'; ?>

