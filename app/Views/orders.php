<!DOCTYPE html>

<!-- TITLE -->
<title>Orders</title>
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
                    <h1 class="page-title fw-semibold fs-18 mb-0">Orders List</h1>
                </div>
                <!-- Page Header Close -->


                <div class="row">
                    <div class="col-xl-12">
                        <div class="card custom-card">
                            <div>
                               <button id="export_orders" type="button"class="btn btn-success btn-wave d-sm-flex align-items-center justify-content-between m-2"> Export Orders</button>

                               </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-bordered text-nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Contact</th>
                                                <th>Tool Name</th>
                                                <th>Quantity</th>
                                                <th>Status</th>
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
        aria-labelledby="ordersModelTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg  modal-dialog-">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rdersModelTitle">Change Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="orders-form">
                        <div class="container">
                            <div class="row">
                <div class="col-lg-12 mt-3">
                    <div class="form-floating mb-4 floating">
                        <select class="form-control" id="status" name="status">
                            <option value="">-- Select Status --</option>
                            <option value="1">New</option>
                            <option value="2">Completed</option>
                        </select>
                        <label for="status"><span class='text-danger'>*</span>Status</label>
                        <span class="error text-danger mt-5 status"></span>
                    </div>
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

<script src="<?= base_url() ?>assets/build/assets/libs/xlsx.full.min.js" ></script>

<script src="<?= base_url() ?>assets/custom/js/orders.js" ></script>

</body>

</html>