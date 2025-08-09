<!DOCTYPE html>
<!-- TITLE -->
<title>Dashboard</title>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-menu-styles="dark" data-toggled="close">

<?php require ('components/head.php') ?>

<body>

    <!-- LOADER -->
    <div id="loader">
        <img src="<?php echo base_url() ?>assets/build/assets/images/media/loader.svg" alt="">
    </div>
    <!-- END LOADER -->

    <!-- PAGE -->
    <div class="page">
        <?php require ('components/topnav.php') ?>

        <?php require ('components/sidenavbar.php') ?>

        <!-- MAIN-CONTENT -->
        <div class="main-content app-content">
            <div class="container-fluid">

                <!-- Page Header -->
                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                    <h1 class="page-title fw-semibold fs-18 mb-0">Dashboard</h1>
                    
                </div>
                <!-- Page Header Close -->

                <!-- Start::row-1 -->
                <div class="row">
                    <div class="col-xxl-12 col-xl-12">
                        <div class="row">
                            <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="card custom-card hrm-main-card primary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-top">
                                            <div class="me-3">
                                                <span class="avatar bg-primary">
                                                    <i class="ri-team-line fs-18"></i>
                                                </span>
                                            </div>
                                            <div class="flex-fill">
                                                <span class="fw-semibold text-muted d-block mb-2">Users</span>
                                                <h5 class="fw-semibold mb-2 user-count">0</h5>
                                                <!-- <p class="mb-0">
                                                    <span class="badge bg-primary-transparent">This Month</span>
                                                </p> -->
                                            </div>
                                            <!-- <div>
                                                <span class="fs-14 fw-semibold text-success">+1.03%</span>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="card custom-card hrm-main-card secondary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-top">
                                            <div class="me-3">
                                                <span class="avatar bg-secondary">
                                                    <i class="ri-user-unfollow-line fs-18"></i>
                                                </span>
                                            </div>
                                            <div class="flex-fill">
                                                <span class="fw-semibold text-muted d-block mb-2">Cats</span>
                                                <h5 class="fw-semibold mb-2">528</h5>
                                                <p class="mb-0">
                                                    <span class="badge bg-secondary-transparent">This Month</span>
                                                </p>
                                            </div>
                                            <div>
                                                <span class="fs-14 fw-semibold text-success">+0.36%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <div class="card custom-card  hrm-main-card warning">
                                    <div class="card-body">
                                        <div class="d-flex align-items-top">
                                            <div class="me-3">
                                                <span class="avatar bg-warning">
                                                    <i class="ri-service-line fs-18"></i>
                                                </span>
                                            </div>
                                            <div class="flex-fill">
                                                <span class="fw-semibold text-muted d-block mb-2">Pets
                                                    Accesories</span>
                                                <h5 class="fw-semibold mb-2">8,289</h5>
                                                <p class="mb-0">
                                                    <span class="badge bg-warning-transparent">This Month</span>
                                                </p>
                                            </div>
                                            <div>
                                                <span class="fs-14 fw-semibold text-danger">-1.28%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer mt-auto py-3 bg-white text-center">
            <div class="container">
                <span class="text-muted"> Copyright © <span id="year"></span> <a href="javascript:void(0);"
                        class="text-dark fw-semibold">Petz 24x7</a>
            </div>
        </footer>
    </div>

    <!-- FOOTER-->
    <?php require ('components/footer.php') ?>

    <script src="<?= base_url() ?>assets/custom/js/dashboard.js"></script>

</body>

</html>