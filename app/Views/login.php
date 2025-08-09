<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-menu-styles="light" data-toggled="close">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta name="csrf-token" content="<?= csrf_hash() ?>">

<!-- HEAD -->

<head>

    <!-- META DATA eta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="">


    <!-- TITLE -->
    <title>Login</title>

    <!-- FAVICON -->
    <link rel="icon" href="<?php echo base_url() ?>assets/custom/img/favicon.png" type="image/x-icon">

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="<?php echo base_url() ?>assets/build/assets/libs/bootstrap/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- ICONS CSS -->
    <link href="<?php echo base_url() ?>assets/build/assets/icon-fonts/icons.css" rel="stylesheet">

    <!-- APP SCSS -->
    <link rel="preload" as="style" href="<?php echo base_url() ?>assets/build/assets/app-fce3f544.css" />
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/build/assets/app-fce3f544.css" />

    <!-- MAIN JS -->
    <script src="<?php echo base_url() ?>assets/build/assets/authentication-main.js"></script>


    <!-- SWIPER CSS -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/build/assets/libs/swiper/swiper-bundle.min.css">

    <!--TOAST CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/custom/css/custom.css">


    <script>
        var base_url = "<?php echo base_url() ?>"
    </script>
</head>

<body class="bg-white">
    <div class="row authentication mx-0">
        <div class="col-xxl-7 col-xl-7 col-lg-12">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-xxl-6 col-xl-7 col-lg-7 col-md-7 col-sm-8 col-12">

                    <div class="p-5">

                        <div class="mb-5 justify-content-center">

                            <a href="#">
                                <img src="<?php echo base_url() ?>assets/custom/img/logo.png" alt=""
                                    class="authentication-brand desktop-logo">
                                <img src="<?php echo base_url() ?>assets/custom/img/logo.png" alt=""
                                    class="authentication-brand desktop-dark">
                            </a>
                        </div>

                        <!-- <p class="mb-3 text-muted op-7 fw-normal">Welcome back Jhon !</p> -->

                        <!-- <p class="h5 fw-semibold mb-5 text-center">Sign In</p> -->
                        <div class="row gy-3">
                            <div class="col-xl-12 mt-0 admin-login">
                                <label for="username" class="form-label text-default ">User Name</label>
                                <input type="text" class="form-control form-control-lg" id="username" name="username"
                                    placeholder="user name">
                            </div>
                            <div class="col-xl-12 mb-3 admin-login">
                                <label for="signin-password"
                                    class="form-label text-default d-block">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="password"
                                        name="password" placeholder="password">
                                    <button class="btn btn-light" type="button"
                                        onclick="createpassword('password',this)" id="button-addon2"><i
                                            class="ri-eye-off-line align-middle"></i></button>
                                </div>

                            </div>
                            <div class="col-xl-12 d-grid mt-2">
                                <a id="signin-btn" class="btn btn-lg btn-primary">Sign In</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-5 col-xl-5 col-lg-5 d-xl-block d-none px-0">
            <div class="authentication-cover">

            </div>

        </div>

        <!-- SCRIPTS -->

        <!-- JQUERY -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

        <!-- BOOTSTRAP JS -->
        <script
            src="<?php echo base_url() ?>assets/build/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- SWIPER JS -->
        <script src="<?php echo base_url() ?>assets/build/assets/libs/swiper/swiper-bundle.min.js"></script>

        <!-- INTERNAL AUTHENTICATION JS -->
        <link rel="modulepreload" href="<?php echo base_url() ?>assets/build/assets/authentication-fa6f6b78.js" />
        <script type="module"
            src="<?php echo base_url() ?>assets/build/assets/authentication-fa6f6b78.js"></script>

        <!-- SHOW PASSWORD JS -->
        <script src="<?php echo base_url() ?>assets/build/assets/show-password.js"></script>

        <!--TOAST CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

        <!-- CUSTOM JS -->
        <script src="<?php echo base_url() ?>assets/custom/js/login.js"></script>




        <!-- END SCRIPTS -->

</body>

</html>