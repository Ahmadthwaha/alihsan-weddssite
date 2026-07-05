<?php 
header("Location: apply.php");
exit;
include 'header.php'; ?>

<!-- banner area start -->
<div class="rts-breadcrumb-area breadcrumb-bg" style="background-image: url(assets/images/banner/bg2.jpg); padding: 80px 0; background-position: center; background-size: cover; position: relative;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-inner text-center" style="position: relative; z-index: 2;">
                    <h1 class="title text-white" style="font-size: 45px; font-weight: 800; text-transform: uppercase;">Admission Fees</h1>
                    <ul class="breadcrumb-navigation" style="display: flex; justify-content: center; gap: 10px; list-style: none; padding: 0; color: rgba(255,255,255,0.8); font-size: 14px;">
                        <li><a href="index.php" style="color: #fff; text-decoration: none;">Home</a></li>
                        <li>/</li>
                        <li class="active">Fees</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- banner area end -->

<!-- content area start -->
<section class="fee-page py-5 my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="fee-card p-5" style="background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: 8px;">
                    <h3 class="mb-4" style="color: #1b365d; font-weight: 800;">College Fee Structure</h3>
                    <p class="text-muted mb-5">At Al Ihsan Da'awa College, our fees are structured to keep religious and moral education affordable for all segments of society. Scholarship programs are available for deserving students.</p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" style="border: 1px solid #ddd;">
                            <thead style="background: #1b365d; color: #fff;">
                                <tr>
                                    <th class="p-3" style="font-weight: bold;">Program Name</th>
                                    <th class="p-3" style="font-weight: bold;">Admission Fee</th>
                                    <th class="p-3" style="font-weight: bold;">Tution Fee (Per Term)</th>
                                    <th class="p-3" style="font-weight: bold;">ADSA Association Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-3" style="font-weight: 600; color: #333;">Foundational & Intermediate Program</td>
                                    <td class="p-3">₹ 1,500</td>
                                    <td class="p-3">₹ 3,000</td>
                                    <td class="p-3">₹ 250</td>
                                </tr>
                                <tr>
                                    <td class="p-3" style="font-weight: 600; color: #333;">Bachelor's Program in Da'awa</td>
                                    <td class="p-3">₹ 2,000</td>
                                    <td class="p-3">₹ 4,500</td>
                                    <td class="p-3">₹ 300</td>
                                </tr>
                                <tr>
                                    <td class="p-3" style="font-weight: 600; color: #333;">Short-Run Skills Course</td>
                                    <td class="p-3">₹ 500</td>
                                    <td class="p-3">₹ 1,200</td>
                                    <td class="p-3">₹ 100</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-warning mt-5 p-4" role="alert" style="background-color: #fdf8e2; border-color: #f7e1b5; border-radius: 4px; border-left: 4px solid #c5a85c;">
                        <h5 class="alert-heading" style="color: #634a00; font-weight: bold;"><i class="fa-solid fa-circle-info me-2"></i>Scholarship Information</h5>
                        <p style="margin: 10px 0 0; color: #665; line-height: 1.6;">
                            Students who demonstrate outstanding performance in academics or show financial need are encouraged to apply for the ADSA Scholarship Scheme. Applications can be requested through the Office of the Principal.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- content area end -->

<?php include 'footer.php'; ?>

