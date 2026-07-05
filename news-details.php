<?php include 'header.php'; ?>

<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 1;

$title = "ADSA Islamic Cultural Summit Concluded";
$cat = "Campus News";
$date = "Nov 15, 2025";
$author = "ADSA";
$img = "uploads/news/68ede6f0342d6.jpg";
$content = "The annual Islamic Cultural Summit organized by the Al-Ihsan Da'awa Student's Association (ADSA) was successfully completed today. The event witnessed diverse academic debates, cultural exhibitions, and Quranic recitation challenges, highlighting the dynamic talents of our 150 students. Outstanding contestants were awarded trophies by the principal in the presence of faculty members.";

if ($id === 2) {
    $title = "Choice Based Credit System (CBCS) Active";
    $cat = "Academic Update";
    $date = "Sep 20, 2025";
    $author = "Principal";
    $img = "uploads/news/68ac1c4eac522.jpg";
    $content = "Al Ihsan Da'awa College has officially transitioned to the new Choice Based Credit System (CBCS). The new curriculum has been successfully distributed to all academic terms. This system offers greater course flexibility and credit-based evaluation to students, in line with modern educational standards.";
} elseif ($id === 3) {
    $title = "Entrance Examination Portal Guidelines";
    $cat = "Admissions";
    $date = "Jun 10, 2025";
    $author = "Admin";
    $img = "uploads/news/684d752070617.jpg";
    $content = "The administration has published clear guidelines for candidates sitting the upcoming Entrance Examinations. All applicants must submit their printouts at the campus registration desk at least 30 minutes before the examination time. A helpdesk has been set up at the lobby to assist candidates with their login credentials.";
}
?>

<!-- banner area start -->
<div class="rts-breadcrumb-area breadcrumb-bg" style="background-image: url(assets/images/banner/bg2.jpg); padding: 80px 0; background-position: center; background-size: cover; position: relative;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner text-center" style="position: relative; z-index: 2;">
                    <h1 class="title text-white" style="font-size: 35px; font-weight: 800; text-transform: uppercase;">News Details</h1>
                    <ul class="breadcrumb-navigation" style="display: flex; justify-content: center; gap: 10px; list-style: none; padding: 0; color: rgba(255,255,255,0.8); font-size: 14px;">
                        <li><a href="index.php" style="color: #fff; text-decoration: none;">Home</a></li>
                        <li>/</li>
                        <li><a href="news.php" style="color: #fff; text-decoration: none;">News</a></li>
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
<section class="news-details-page py-5 my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="news-detail-card p-5" style="background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: 8px;">
                    <img src="<?= $img ?>" style="width: 100%; height: auto; max-height: 400px; object-fit: cover; border-radius: 8px; margin-bottom: 30px;" alt="<?= $title ?>">
                    
                    <span class="badge" style="background: #c5a85c; color: #fff; padding: 8px 15px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 15px; display: inline-block;"><?= $cat ?></span>
                    
                    <h2 style="color: #1b365d; font-weight: 800; line-height: 1.3; margin-bottom: 20px;"><?= $title ?></h2>
                    
                    <div class="news-meta mb-4" style="display: flex; gap: 20px; color: #888; font-size: 14px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
                        <div><i class="fa-thin fa-user me-1" style="color: #1b365d;"></i> By <?= $author ?></div>
                        <div><i class="fa-sharp fa-thin fa-calendar me-1" style="color: #1b365d;"></i> Published on <?= $date ?></div>
                    </div>
                    
                    <p style="font-size: 16px; line-height: 2; color: #444; text-align: justify; white-space: pre-line;">
                        <?= $content ?>
                    </p>
                    
                    <div class="back-btn mt-5 pt-4" style="border-top: 1px solid #eee;">
                        <a href="news.php" class="btn btn-outline-primary" style="border-color: #1b365d; color: #1b365d; border-radius: 0; padding: 10px 25px; font-weight: 600;">
                            <i class="fa-solid fa-arrow-left me-2"></i> Back to News
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- content area end -->

<?php include 'footer.php'; ?>

