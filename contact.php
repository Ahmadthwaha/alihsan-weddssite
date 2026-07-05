<?php 
header("Location: about.php");
exit;
include 'header.php'; ?>

<!-- banner area start -->
<div class="rts-breadcrumb-area breadcrumb-bg" style="background-image: url(assets/images/banner/bg2.jpg); padding: 80px 0; background-position: center; background-size: cover; position: relative;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner text-center" style="position: relative; z-index: 2;">
                    <h1 class="title text-white" style="font-size: 45px; font-weight: 800; text-transform: uppercase;">Contact Us</h1>
                    <ul class="breadcrumb-navigation" style="display: flex; justify-content: center; gap: 10px; list-style: none; padding: 0; color: rgba(255,255,255,0.8); font-size: 14px;">
                        <li><a href="index.php" style="color: #fff; text-decoration: none;">Home</a></li>
                        <li>/</li>
                        <li class="active">Contact</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner area end -->

<!-- content area start -->
<section class="contact-page py-5 my-5">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Info -->
            <div class="col-lg-5">
                <div class="contact-info-card p-5" style="background: #1b365d; color: #fff; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                    <h3 class="mb-5 style-bold" style="color: #fff; font-weight: 800; border-left: 5px solid #c5a85c; padding-left: 15px;">Al Ihsan Office</h3>
                    
                    <div class="info-block mb-4">
                        <h6 style="color: #c5a85c; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; margin-bottom: 8px;">Address</h6>
                        <p style="font-size: 15px; line-height: 1.6; color: rgba(255,255,255,0.8);">
                            Muloor, Uchila,<br>Udupi District, Karnataka, India - 574117
                        </p>
                    </div>

                    <div class="info-block mb-4">
                        <h6 style="color: #c5a85c; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; margin-bottom: 8px;">Call Us</h6>
                        <p style="font-size: 15px; line-height: 1.6; color: rgba(255,255,255,0.8);">
                            +91 9876543210 (Principal)<br>
                            +91 8765432109 (ADSA Office)
                        </p>
                    </div>

                    <div class="info-block mb-4">
                        <h6 style="color: #c5a85c; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; margin-bottom: 8px;">Email</h6>
                        <p style="font-size: 15px; line-height: 1.6; color: rgba(255,255,255,0.8);">
                            info@alihsancollege.com
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="contact-form-card p-5" style="background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: 8px; border: 1px solid #eee;">
                    <h3 class="mb-4" style="color: #1b365d; font-weight: 800;">Send Inquiry</h3>
                    <p class="text-muted mb-5">Have a question about our admissions, fee plans, or the student association? Drop us a line.</p>
                    
                    <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Message sent successfully! Our administrative office will get back to you shortly.'); window.location.reload();">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Your Name *</label>
                                <input type="text" class="form-control" style="border-radius: 0; border: 1px solid #ccc; padding: 12px;" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-weight: 600; color: #333;">Email Address *</label>
                                <input type="email" class="form-control" style="border-radius: 0; border: 1px solid #ccc; padding: 12px;" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" style="font-weight: 600; color: #333;">Subject *</label>
                                <input type="text" class="form-control" style="border-radius: 0; border: 1px solid #ccc; padding: 12px;" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" style="font-weight: 600; color: #333;">Message *</label>
                                <textarea class="form-control" rows="5" style="border-radius: 0; border: 1px solid #ccc; padding: 12px; resize: none;" required></textarea>
                            </div>
                            <div class="col-md-12 mt-5">
                                <button type="submit" class="rts-theme-btn" style="border: none; padding: 15px 40px; font-weight: bold; cursor: pointer;">
                                    <span class="main-text">Send Message <i class="fa-solid fa-arrow-right ms-2"></i></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- content area end -->

<?php include 'footer.php'; ?>

