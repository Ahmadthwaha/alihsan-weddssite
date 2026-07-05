<?php 
require_once 'db.php';
include 'header.php';
?>

<?php
$p = isset($_GET['p']) ? $_GET['p'] : 'general';
$title = "About Us";
$subtitle = "About Al Ihsan Da'awa College";

if ($p === 'principal') {
    $title = "Office of Principal";
    $subtitle = "Mentorship & Academic Leadership";
} elseif ($p === 'adsa') {
    $title = "Al Ihsan ADSA";
    $subtitle = "Al-Ihsan Da'awa Student's Association";
} elseif ($p === 'dksc') {
    $title = "DKSC Sunni Center";
    $subtitle = "Markaz Tha-aleemil Ihsan DKSC Muloor Udupi";
}
?>

<!-- banner area start -->
<div class="rts-breadcrumb-area" style="background: linear-gradient(135deg, #1b365d 0%, #0d1e3d 100%); padding: 80px 0; position: relative;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner text-center" style="position: relative; z-index: 2;">
                    <h1 class="title text-white" style="font-size: 45px; font-weight: 800; text-transform: uppercase;"><?= $title ?></h1>
                    <ul class="breadcrumb-navigation" style="display: flex; justify-content: center; gap: 10px; list-style: none; padding: 0; color: rgba(255,255,255,0.8); font-size: 14px;">
                        <li><a href="index.php" style="color: #fff; text-decoration: none;">Home</a></li>
                        <li>/</li>
                        <li class="active" style="color: #c5a85c;"><?= $title ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner area end -->

<!-- content area start -->
<section class="about-page-content py-5 my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="about-card p-5" style="background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: 8px;">
                    <h3 class="mb-4" style="color: #1b365d; font-weight: 800; border-left: 5px solid #c5a85c; padding-left: 15px;"><?= $subtitle ?></h3>
                    
                    <?php if ($p === 'principal'): ?>
                        <?php
                        $principal = null;
                        if (DB_ACTIVE) {
                            $res = $conn->query("SELECT * FROM faculties WHERE is_principal = 1 LIMIT 1");
                            if ($res && $res->num_rows > 0) {
                                $principal = $res->fetch_assoc();
                            }
                        } else {
                            $file = getFallbackFile();
                            $data = json_decode(file_get_contents($file), true);
                            foreach ($data['faculties'] as $fac_item) {
                                if (isset($fac_item['is_principal']) && $fac_item['is_principal'] == 1) {
                                    $principal = $fac_item;
                                    break;
                                }
                            }
                        }
                        ?>
                        
                        <?php if ($principal): ?>
                            <div class="principal-profile-container" style="display: flex; gap: 30px; align-items: center; flex-wrap: wrap;">
                                <div class="principal-photo-frame" style="flex: 0 0 140px; width: 140px; height: 140px; border-radius: 50%; overflow: hidden; border: 4px solid #c5a85c; box-shadow: 0 8px 24px rgba(0,0,0,0.1);">
                                    <?php if (!empty($principal['photo'])): ?>
                                        <img src="<?= htmlspecialchars($principal['photo']) ?>" alt="Principal Photo" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <div style="width: 100%; height: 100%; background: #eee; display: flex; align-items: center; justify-content: center; color: #999;">
                                            <i class="fa fa-user fa-4x"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="principal-details" style="flex: 1; min-width: 250px;">
                                    <h3 style="color: #1b365d; font-weight: 800; margin-bottom: 5px;"><?= htmlspecialchars($principal['name']) ?></h3>
                                    <span class="badge mb-4" style="background-color: #c5a85c; color: #1b365d; font-weight: 800; padding: 6px 12px; font-size: 14px; display: inline-block;"><?= htmlspecialchars($principal['designation'] ?: 'Principal') ?></span>
                                    
                                    <p class="lead" style="font-size: 18px; line-height: 1.8; color: #333;">
                                        Welcome to the Office of the Principal. At Al Ihsan Da'awa College, our academic administrative leadership is focused on cultivating a high standard of scholarly study combined with moral growth.
                                    </p>
                                    <p style="font-size: 16px; line-height: 1.8; color: #666; margin-top: 20px;">
                                        We ensure that each of our 150 students is mentored individually by our team of 10 dedicated faculties. Our goal is to guide students to achieve deep religious competence and match it with modern sciences, preparing them to be useful leaders in society.
                                    </p>
                                </div>
                            </div>
                            <div class="principal-sign mt-5" style="border-top: 1px solid #eee; padding-top: 20px;">
                                <h6 style="color: #333; font-weight: 700; margin-bottom: 2px;"><?= htmlspecialchars($principal['name']) ?></h6>
                                <p style="font-size: 14px; color: #c5a85c; font-weight: 600;">Principal, Al Ihsan Da'awa College</p>
                            </div>
                        <?php else: ?>
                            <p class="lead" style="font-size: 18px; line-height: 1.8; color: #333;">
                                Welcome to the Office of the Principal. At Al Ihsan Da'awa College, our academic administrative leadership is focused on cultivating a high standard of scholarly study combined with moral growth.
                            </p>
                            <p style="font-size: 16px; line-height: 1.8; color: #666; margin-top: 20px;">
                                We ensure that each of our 150 students is mentored individually by our team of 10 dedicated faculties. Our goal is to guide students to achieve deep religious competence and match it with modern sciences, preparing them to be useful leaders in society.
                            </p>
                            <div class="principal-sign mt-5" style="border-top: 1px solid #eee; padding-top: 20px;">
                                <h6 style="color: #333; font-weight: 700; margin-bottom: 2px;">Principal Office</h6>
                                <p style="font-size: 14px; color: #c5a85c; font-weight: 600;">Al Ihsan Da'awa College, Udupi</p>
                            </div>
                        <?php endif; ?>

                    <?php elseif ($p === 'adsa'): ?>
                        <p class="lead" style="font-size: 18px; line-height: 1.8; color: #333;">
                            The <strong>Al-Ihsan Da'awa Student's Association (ADSA)</strong> is the vibrant heart of campus life. ADSA coordinates academic contests, cultural events, public publications, and social service initiatives.
                        </p>
                        <p style="font-size: 16px; line-height: 1.8; color: #666; margin-top: 20px;">
                            ADSA serves as a vital platform for students to hone their speech, writing, and leadership skills. Through various forums, publications, and field activities, ADSA ensures Al Ihsan students are actively engaged in personal development and social work, leaving a lasting legacy.
                        </p>
                        <div class="adsa-logo-box text-center my-5">
                            <img src="assets/images/logo/adsa_logo.png" style="height: 150px; width: auto;" alt="ADSA Logo">
                            <h5 style="margin-top: 15px; font-weight: 800; color: #1b365d;">ADSA STUDENT'S ASSOCIATION</h5>
                            <p style="font-size: 13px; color: #666;">Muloor, Uchila, Udupi Dist</p>
                        </div>

                    <?php elseif ($p === 'dksc'): ?>
                        <p class="lead" style="font-size: 16px; line-height: 1.8; color: #444; text-align: justify; margin-bottom: 20px;">
                            <strong>Dakshina Karnataka Sunni Centre</strong> is an organization established about 27 years ago with a strong ambition of serving the community by helping the needy people. It has grown into a leading organization in Karnataka with a subsidiary institute called Markaz Ta'alimil Ihsan at Mooloor in Udupi district.
                        </p>
                        <p style="font-size: 16px; line-height: 1.8; color: #666; text-align: justify; margin-bottom: 30px;">
                            Many departments such as Orphanage, Islamic Da’awa College, Residential School, Hifz, Darul Masakeen, English Medium School, Women's PU College, Science and Arts College, Nursery School, Zahratul Qur'an, and Women's Shariath are functioning, and more than 200 staff are serving. It has led a revolution in the religious, social, and educational fields with great courage. Today, it has grown into a great institution with more than 2400 students acquiring knowledge. The organization's journey so far is short and still has a long way to go. Many dreams lie ahead for the organization. We always look forward to your sincere prayers and help and cooperation in order for all these to become realized.
                        </p>

                        <!-- Leadership Circle Section -->
                        <div class="leadership-section my-5">
                            <h4 class="text-center fw-bold mb-4" style="color: #1b365d;">DKSC Core Leadership</h4>
                            <div class="row g-4 justify-content-center text-center">
                                <!-- Founder -->
                                <div class="col-md-4">
                                    <div class="leader-card p-3" onclick="showLeaderBio('founder')" style="cursor: pointer; transition: all 0.3s; border-radius: 8px;">
                                        <div class="leader-circle-frame" style="width: 140px; height: 140px; border-radius: 50%; overflow: hidden; margin: 0 auto 15px; border: 4px solid #c5a85c; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                            <img src="assets/images/about/founder.jpg" alt="Founder" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <h5 class="fw-bold mb-1" style="color: #1b365d; font-size: 16px;">A.B Hasanul Faizy Ajjavara</h5>
                                        <span class="badge" style="background-color: #c5a85c; color: #1b365d; font-weight: 700;">Founder President</span>
                                    </div>
                                </div>
                                <!-- President -->
                                <div class="col-md-4">
                                    <div class="leader-card p-3" onclick="showLeaderBio('president')" style="cursor: pointer; transition: all 0.3s; border-radius: 8px;">
                                        <div class="leader-circle-frame" style="width: 140px; height: 140px; border-radius: 50%; overflow: hidden; margin: 0 auto 15px; border: 4px solid #c5a85c; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                            <img src="assets/images/about/president.jpg" alt="President" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <h5 class="fw-bold mb-1" style="color: #1b365d; font-size: 16px;">Seyyad K.S Attakoya Thangal</h5>
                                        <span class="badge" style="background-color: #c5a85c; color: #1b365d; font-weight: 700;">Honourable President</span>
                                    </div>
                                </div>
                                <!-- Manager -->
                                <div class="col-md-4">
                                    <div class="leader-card p-3" onclick="showLeaderBio('manager')" style="cursor: pointer; transition: all 0.3s; border-radius: 8px;">
                                        <div class="leader-circle-frame" style="width: 140px; height: 140px; border-radius: 50%; overflow: hidden; margin: 0 auto 15px; border: 4px solid #c5a85c; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                            <img src="assets/images/about/manager.jpg" alt="Manager" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <h5 class="fw-bold mb-1" style="color: #1b365d; font-size: 16px;">DKSC Manager</h5>
                                        <span class="badge" style="background-color: #c5a85c; color: #1b365d; font-weight: 700;">General Manager</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Bio Panel Display -->
                            <div class="bio-display-panel mt-4 p-4 d-none" id="leaderBioPanel" style="background: #fdfbf7; border-left: 5px solid #c5a85c; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                                <h6 class="fw-bold mb-2" id="bioTitle" style="color: #1b365d;">Biography</h6>
                                <p class="mb-0" id="bioText" style="font-size: 15px; line-height: 1.7; color: #444; font-style: italic;"></p>
                            </div>
                        </div>

                        <p style="font-size: 16px; line-height: 1.8; color: #666; text-align: justify; margin-top: 30px;">
                            Assayed K. S. Atakoya Thangal Kumbol, who is a spiritual leader of South India and who leads many associations in the state, is the chief patron of the organization. Started with few students, the institution has grown up to include more than 2 thousand students who are getting quality education in many departments.
                        </p>

                        <script>
                            function showLeaderBio(leader) {
                                const bios = {
                                    founder: {
                                        title: "A.B Hasanul Faizy Ajjavara - Founder President",
                                        text: "“DKSC’s mission is to support all the people who are working so effectively, especially women and children, to change their own lives… That is what we are doing as we go through these board meetings,” said A.B Hasanul Faizy Ajjavara. “Thank you all in retrospect for all you have done, and our new leadership for all you will be doing in the coming year in helping us accomplish that common mission.”<br><br>Looking ahead to the challenges and opportunities of 2014, DKSC Founder President A.B Hasanul Faizy Ajjavara expressed his confidence in the solid foundation that has been laid for the organization."
                                    },
                                    president: {
                                        title: "Seyyad K.S Attakoya Thangal - Honourable President",
                                        text: "“DKSC’s mission is to support all the people who are working so effectively, especially women and children, to change their own lives… That is what we are doing as we go through these board meetings,” said Seyyad K.S Attakoya Thangal. “Thank you all in retrospect for all you have done, and our new leadership for all you will be doing in the coming year in helping us accomplish that common mission.”<br><br>Looking ahead to the challenges and opportunities of 2014, DKSC Honourable President Seyyad K.S Attakoya Thangal expressed his confidence in the solid foundation that has been laid for the organization."
                                    },
                                    manager: {
                                        title: "DKSC Manager - General Administration",
                                        text: "History proves the noble religion of Islam embeded with bounty principles reached towards India during the period of Prophet. The then arrival of Malik Deenar into southern coastal region of Kerala and Karnataka witnessed number of mosques in the region. The subsequent Alims and Umaras were taken entire responsibility of such noble cause of prevailing and maintaining of mosques and principle of Islam without any emmendments to them. A mosque at Mangalore and one at Barkur of Udupi were established among such mosques.<br><br>An ample monument of such mosques are the blind witnesses before us from history, it is a black truth we are not upto the helm of religion. Rather comparing number of organization working insupport to the religions activities, Karnataka state lacking such a volume of organizations which ultimately resulted decreesing the religious work and experienced sear city, are the common factor.<br><br>At the same time, non-resident Kannadigas were migrated to middle east to win their family bread, established employment by swetting their blood into water, found some challenge to achieve a bit towards their mother land, community and religion. Thanks to a matured incident, sunni leader and a Philosopher Janab Hasan-Ul-Faizy led such young generation where are organization was floated on 13th October 1995. DKSC was born and established as dream cradle child of thousands of Kannadigas working at abroad middle east."
                                    }
                                };
                                
                                const panel = document.getElementById('leaderBioPanel');
                                const title = document.getElementById('bioTitle');
                                const text = document.getElementById('bioText');
                                
                                title.innerText = bios[leader].title;
                                text.innerHTML = bios[leader].text;
                                panel.classList.remove('d-none');
                                
                                // Scroll smoothly to panel
                                panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                        </script>

                    <?php else: ?>
                        <p class="lead" style="font-size: 18px; line-height: 1.8; color: #333;">
                            Al Ihsan Dawah College is located in Muloor, Udupi district, Karnataka, near the National Highway. The institution offers regular-mode PUC and degree courses along with Mukhthasar-level religious studies.
                        </p>
                        <p style="font-size: 16px; line-height: 1.8; color: #666; margin-top: 20px;">
                            Additionally, students have access to government-recognized Arabic-Urdu diploma courses, computer courses, and practical Dawah training in North regions. Hafiz students can pursue Quran research studies along with Daura, and the college provides excellent coaching in comparative religion and ideal studies. Training in modern technology and AI tools is also available. Graduates of the institution are awarded the Ihsani degree.
                        </p>
                        
                        <div class="row mt-5 g-4 text-center">
                            <div class="col-md-4">
                                <div class="stat-box p-4" style="background: #f9f9f9; border-radius: 6px; border: 1px solid #eee;">
                                    <h2 style="color: #1b365d; font-weight: 800; margin: 0;"><?= getStatCount('students') ?></h2>
                                    <p style="margin: 5px 0 0; color: #666; font-weight: 500;">Enrolled Students</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-box p-4" style="background: #f9f9f9; border-radius: 6px; border: 1px solid #eee;">
                                    <h2 style="color: #1b365d; font-weight: 800; margin: 0;"><?= getStatCount('faculties') ?></h2>
                                    <p style="margin: 5px 0 0; color: #666; font-weight: 500;">Expert Faculty</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-box p-4" style="background: #f9f9f9; border-radius: 6px; border: 1px solid #eee;">
                                    <h2 style="color: #1b365d; font-weight: 800; margin: 0;"><?= getStatCount('alumni') ?></h2>
                                    <p style="margin: 5px 0 0; color: #666; font-weight: 500;">Graduated Alumni</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- content area end -->

<?php include 'footer.php'; ?>

