<!DOCTYPE html>

<!-- TITLE -->
<title>Gallery</title>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-menu-styles="dark" data-toggled="close">

<?php require ('components/head.php') ?>

<body>

    <!-- PAGE -->
    <div class="page">
        <?php require ('components/topnav.php') ?>

        <?php require ('components/sidenavbar.php') ?>

        <!-- MAIN-CONTENT -->
        <div class="main-content app-content">
            <div class="container-fluid">

                <!-- Page Header -->
                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                    <h1 class="page-title fw-semibold fs-18 mb-0">Gallery List</h1>

                </div>
                <!-- Page Header Close -->


                <div class="row">
                    <div class="col-xl-12">
                        <div class="card custom-card">
                            <div class="card-header">
                                <a id="add_gallery"
                                    class="btn btn-end btn-outline-primary btn-wave d-sm-flex align-items-center justify-content-between">
                                    Add Gallery
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-bordered text-nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Gallery Name</th>
                                                <th>Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- data -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade bs-example-modal-lg" id="popup-modal" tabindex="-1" role="dialog"
        aria-labelledby="galleryModelTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg  modal-dialog-">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galleryModelTitle">Add Gallery</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="gallery-form">
                        <div class="container">
                            <div class="row">

                                <div class="col-lg-12 mt-3">
                                    <div class="form-floating mb-4 floating">
                                        <input class="form-control gallery_name" id="gallery_name"
                                            placeholder="Enter the gallery name" name="gallery_name">
                                        <label for="gallery_name"><span class='text-danger'>*</span> Gallery
                                            Name</label>
                                        <span class="error text-danger mt-5 gallery_name"></span>
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-3">
                                    <div>
                                        <label for="gallery_img" class="form-label"><span class='text-danger'>*</span>
                                            Gallery Image &nbsp;
                                            <span class="text text-success text-small">AllowedFiles : png,jpeg,jpg [1080
                                                x 1440px]</span>
                                        </label>
                                        <div>
                                            <input class="form-control" type="file" id="gallery_img" name="gallery_img"
                                                accept="image/png, image/jpeg, image/jpg, image/webp">

                                            <img src="" id="gallery_image_url" alt="image" width="130px"
                                                style="padding-top: 15px; display:none;">

                                            <span class="error text-danger gallery_img mt-5"></span>
                                        </div>



                                    </div>
                                </div>

                                <div class="mt-3 d-flex justify-content-end align-items-end">
                                    <a class="btn btn-success" id="btn-submit">Submit</a>
                                </div>

                                </hr>
                    </form>

                </div>

            </div>
        </div>

    </div>

    </div>
    <?php require ('components/footer.php') ?>


    <script src="<?= base_url() ?>assets/custom/js/gallery.js"></script>

</body>

</html>