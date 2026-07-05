<?php include 'header.php'; ?>

<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 1;

$title = "Admission Entrance Test 2026";
$date = "June 15, 2026";
$time = "10:00 am";
$place = "College Assembly Hall";
$content = "The entrance examination for candidates seeking admission to the Foundational & Intermediate courses for the 2026 academic term will be held at the College Assembly Hall. All registered applicants are required to carry a printout of their admission card and report at least 30 minutes before the commencement of the exam. Contact the administrator's desk for seating queries.";

if ($id === 2) {
    $title = "ADSA Annual Academic Conference";
    $date = "August 24, 2026";
    $time = "09:00 am";
    $place = "Seminar Hall";
    $content = "The annual student-led seminar, organized by the Al-Ihsan Da'awa Student's Association (ADSA), is scheduled to hold at the Seminar Hall. The conference will feature presentations on comparative study methodologies, da'awa challenges, and Islamic social reforms. Guest lectures will be delivered by seasoned scholars.";
}
?>

<!-- banner area start -->
<div class="rts-breadcrumb-area breadcrumb-bg" style="background-image: url(assets/images/banner/bg2.jpg); padding: 80px 0; background-position: center; background-size: cover; position: relative;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner text-center" style="position: relative; z-index: 2;">
                    <h1 class="title text-white" style="font-size: 35px; font-weight: 800; text-transform: uppercase;">Event Details</h1>
                    <ul class="breadcrumb-navigation" style="display: flex; justify-content: center; gap: 10px; list-style: none; padding: 0; color: rgba(255,255,255,0.8); font-size: 14px;">
                        <li><a href="index.php" style="color: #fff; text-decoration: none;">Home</a></li>
                        <li>/</li>
                        <li><a href="events.php" style="color: #fff; text-decoration: none;">Events</a></li>
                        <li>/</li>
                        <li class="active">Details</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner area end -->

<!-- content area start -->
<section class="event-details-page py-5 my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="event-detail-card p-5" style="background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: 8px;">
                    <h2 style="color: #1b365d; font-weight: 800; line-height: 1.3; margin-bottom: 25px; border-bottom: 2px solid #eee; padding-bottom: 15px;"><?= $title ?></h2>
                    
                    <div class="event-info-grid mb-5 p-4" style="background: #f9f9f9; border-radius: 6px; border: 1px solid #eee; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <div>
                            <span class="d-block text-muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase;">Date</span>
                            <span style="font-size: 16px; font-weight: 600; color: #333;"><i class="fal fa-calendar me-2" style="color: #c5a85c;"></i> <?= $date ?></span>
                        </div>
                        <div>
                            <span class="d-block text-muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase;">Time</span>
                            <span style="font-size: 16px; font-weight: 600; color: #333;"><i class="fa-sharp fa-thin fa-clock me-2" style="color: #c5a85c;"></i> <?= $time ?></span>
                        </div>
                        <div>
                            <span class="d-block text-muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase;">Location</span>
                            <span style="font-size: 16px; font-weight: 600; color: #333;"><i class="fa-sharp fa-thin fa-location-dot me-2" style="color: #c5a85c;"></i> <?= $place ?></span>
                        </div>
                    </div>
                    
                    <p style="font-size: 16px; line-height: 2; color: #444; text-align: justify; white-space: pre-line;">
                        <?= $content ?>
                    </p>
                    
                    <div class="back-btn mt-5 pt-4" style="border-top: 1px solid #eee;">
                        <a href="events.php" class="btn btn-outline-primary" style="border-color: #1b365d; color: #1b365d; border-radius: 0; padding: 10px 25px; font-weight: 600;">
                            <i class="fa-solid fa-arrow-left me-2"></i> Back to Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- content area end -->

<?php include 'footer.php'; ?>

