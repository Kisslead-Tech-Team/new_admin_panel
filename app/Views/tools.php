<!DOCTYPE html>

<!-- TITLE -->
<title>Tools</title>
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
                    <h1 class="page-title fw-semibold fs-18 mb-0">Tools List</h1>

                </div>
                <!-- Page Header Close -->


                <div class="row">
                    <div class="col-xl-12">
                        <div class="card custom-card">
                            <div class="card-header">
                                <a id="add_tools"
                                    class="btn btn-end btn-outline-primary btn-wave d-sm-flex align-items-center justify-content-between">
                                    Add Tools
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-bordered text-nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Tools Name</th>
                                                <th>URL</th>
                                                <th>Brand Name</th>
                                                <th>Category Name</th>
                                                <th  style="max-width: 250px;">Description</th>
                                                <th>Images</th>
                                                <th>Brochure PDF</th>
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
        aria-labelledby="toolsModelTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg  modal-dialog-">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="toolsModelTitle">Add Tools</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tools-form">
                        <div class="container">
                            <div class="row">

                                <div class="col-lg-6 mt-3">
                                    <div class="form-floating">
                                        <select class="form-select" id="brand-select"
                                            aria-label="Floating label select example" name="brand_id">
                                            <option value="">Select a Brand</option>


                                        </select>
                                        <label for="brand-select"><span class='text-danger'>*</span>Brand Name</label>
                                    </div>

                                </div>



                                <div class="col-lg-6 mt-3">
                                    <div class="form-floating">
                                        <select class="form-select" id="category-select"
                                            aria-label="Floating label select example" name="category_id">
                                            <option value="">Select a Category</option>
                                        </select>
                                        <label for="category-select"><span class='text-danger'>*</span>Category
                                            Name</label>
                                    </div>

                                </div>





                                <div class="col-lg-12 mt-3">
                                    <div class="form-floating mb-4 floating">
                                        <input class="form-control tools_name" id="tools_name"
                                            placeholder="Enter the tools name" name="tools_name">
                                        <label for="tools_name"><span class='text-danger'>*</span> Tools Name</label>
                                        <span class="error text-danger mt-5 tools_name"></span>
                                    </div>
                                </div>


                                <div class="col-lg-12 mt-3">
                                    <div class="form-floating mb-4 floating">
                                        <textarea class="form-control tools_description" id="tools_description"
                                            placeholder="Enter the tools description" name="tools_description"
                                            style="height: 200px"></textarea>
                                        <label for="tools_description">
                                            <span class='text-danger'>*</span> Tools Description
                                        </label>
                                        <span class="error text-danger mt-5 tools_description"></span>
                                    </div>
                                </div>


                                <div class="col-lg-12 mt-3">
                                    <div>
                                        <label for="tools_brochure" class="form-label">
                                            <span class='text-danger'></span> Tools Brochure &nbsp;
                                            <span class="text text-success text-small">
                                                Allowed File: PDF only
                                            </span>
                                        </label>
                                        <div>
                                            <input class="form-control" type="file" id="tools_brochure"
                                                name="tools_brochure" accept="application/pdf">


                                                <div id="pdfwrapper"></div>



                                            <span class="error text-danger tools_brochure mt-5"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-3">
                                    <div>
                                        <label for="tools_img" class="form-label">
                                            <span class='text-danger'>*</span> Tools Images &nbsp;
                                            <span class="text text-success text-small">
                                                Allowed Files: png, jpeg, jpg [1080 x 1440px]
                                            </span>
                                        </label>
                                        <div>
                                            <input class="form-control" type="file" id="tools_img" name="tools_img[]" accept="image/png, image/jpeg, image/jpg, image/webp" multiple>

                                            <div id="tools_image_container" class='image-container'></div>
                                            <div id="tools_ex_image_container" class='image-container'></div>

                                            <span class="error text-danger tools_img mt-5"></span>
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


    <script src="<?= base_url() ?>assets/custom/js/tools.js"></script>

</body>

</html>