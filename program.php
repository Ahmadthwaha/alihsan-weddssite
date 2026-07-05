<?php include 'header.php'; ?>

<?php
$p = isset($_GET['p']) ? $_GET['p'] : 'all';
if ($p === 'foundational-intermediate') {
    $p = 'all';
}
?>

<!-- banner area start -->
<div class="rts-breadcrumb-area" style="background: linear-gradient(135deg, #1b365d 0%, #0d1e3d 100%); padding: 80px 0; position: relative;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner text-center" style="position: relative; z-index: 2;">
                    <h1 class="title text-white" style="font-size: 45px; font-weight: 800; text-transform: uppercase;">Academics & Programs</h1>
                    <ul class="breadcrumb-navigation" style="display: flex; justify-content: center; gap: 10px; list-style: none; padding: 0; color: rgba(255,255,255,0.8); font-size: 14px;">
                        <li><a href="index.php" style="color: #fff; text-decoration: none;">Home</a></li>
                        <li>/</li>
                        <li class="active" style="color: #c5a85c;">Programs</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner area end -->

<!-- content area start -->
<section class="programs-page py-5 my-5">
    <div class="container">
        <div class="row g-5">
            <!-- Left Sidebar Navigation -->
            <div class="col-lg-4">
                <div class="program-nav-sidebar p-4" style="background: #f9f9f9; border-radius: 8px; border: 1px solid #eee;">
                    <h5 class="mb-4" style="color: #1b365d; font-weight: 800;">Academic Programs</h5>
                    <ul class="nav flex-column" style="gap: 10px;">
                        <li class="nav-item">
                            <a class="nav-link <?= ($p === 'all') ? 'active text-white bg-primary' : 'text-dark' ?> p-3" href="program.php" style="border-radius: 4px; font-weight: 600; display: block; background: <?= ($p === 'all') ? '#1b365d !important' : 'transparent' ?>;">All Programs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($p === 'bachelors-program') ? 'active text-white bg-primary' : 'text-dark' ?> p-3" href="program.php?p=bachelors-program" style="border-radius: 4px; font-weight: 600; display: block; background: <?= ($p === 'bachelors-program') ? '#1b365d !important' : 'transparent' ?>;">Bachelor's Program</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($p === 'short-courses') ? 'active text-white bg-primary' : 'text-dark' ?> p-3" href="program.php?p=short-courses" style="border-radius: 4px; font-weight: 600; display: block; background: <?= ($p === 'short-courses') ? '#1b365d !important' : 'transparent' ?>;">Short-run Courses</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Program Details -->
            <div class="col-lg-8">
                <div class="program-details-content p-5" style="background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: 8px;">
                    
                    <?php if ($p === 'all' || $p === 'bachelors-program'): ?>
                        <div class="program-block mb-5" style="border-bottom: 1px solid #eee; padding-bottom: 40px;">
                            <h3 style="color: #1b365d; font-weight: 800; margin-bottom: 15px;">Bachelor's Programs</h3>
                            <p style="font-size: 16px; line-height: 1.8; color: #666;">
                                Our Bachelor's degree focuses on deep research in Da'awa methodologies, Islamic law, comparative religions, and humanities.
                            </p>
                            <h6 class="mt-4 style-bold" style="color: #333; font-weight: 700;">Key Topics Covered:</h6>
                            <ul style="list-style: square; padding-left: 20px; color: #666; margin-top: 10px; line-height: 1.8;">
                                <li>Comparative Religions and Da'awa Theory</li>
                                <li>Advanced Hadith Criticism and Sciences</li>
                                <li>Islamic Jurisprudence (Usul al-Fiqh) and Social Ethics</li>
                                <li>Sociology, English Literature, and Computer Studies</li>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($p === 'all' || $p === 'short-courses'): ?>
                        <div class="program-block mb-3">
                            <h3 style="color: #1b365d; font-weight: 800; margin-bottom: 15px;">Short-Run Courses</h3>
                            <p style="font-size: 16px; line-height: 1.8; color: #666;">
                                Designed for students and external community learners, these short-term workshops boost specialized practical skills.
                            </p>
                            <h6 class="mt-4 style-bold" style="color: #333; font-weight: 700;">Courses Offered:</h6>
                            <ul style="list-style: square; padding-left: 20px; color: #666; margin-top: 10px; line-height: 1.8;">
                                <li>Professional Development Program</li>
                                <li>Global Communication Skills</li>
                                <li>Project Management</li>
                                <li>Islamic Leadership Certificate</li>
                            </ul>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>
<!-- content area end -->

<?php include 'footer.php'; ?>

