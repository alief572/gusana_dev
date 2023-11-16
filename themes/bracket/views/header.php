<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/favicon'); ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/favicon'); ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/favicon'); ?>/favicon-16x16.png">
    <link rel="manifest" href="<?= base_url('assets/favicon'); ?>/site.webmanifest">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?= base_url('assets/favicon'); ?>/apple-touch-icon.png">
    <meta name="theme-color" content="#ffffff">

    <meta property="og:image" content="<?= base_url('assets/logo.png'); ?>">
    <meta property="og:image:secure_url" content="<?= base_url('assets/logo.png'); ?>">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="">
    <meta name="author" content="Sentralsistem">

    <title>
        <?= isset($template['title']) ? $template['title'] : ''; ?>
        <?= isset($idt->nm_perusahaan) ? ' | ' . $idt->nm_perusahaan : 'not-set'; ?>
    </title>

    <!-- vendor css -->
    <script src="<?= base_url(); ?>themes/bracket/assets/lib/jquery/jquery.min.js"></script>
    <link href="<?= base_url(); ?>themes/bracket/assets/lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>themes/bracket/assets/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>themes/bracket/assets/lib/highlightjs/styles/github.css" rel="stylesheet">
    <link href="<?= base_url(); ?>themes/bracket/assets/lib/select2/css/select2.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>themes/bracket/assets/lib/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>themes/bracket/assets/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url(); ?>themes/bracket/assets/lib/rickshaw/rickshaw.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>themes/bracket/assets/lib/spinkit/css/spinkit.css">
    <link rel="stylesheet" href="<?= base_url(); ?>themes/bracket/assets/lib/lobiani/css/lobibox.min.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>themes/bracket/assets/lib/sweetalert2/sweetalert2.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>themes/bracket/assets/lib/animate/animate.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>themes/bracket/assets/lib/summernote/summernote-bs4.css">

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>themes/bracket/assets/css/bracket.css">
    <link rel="stylesheet" href="<?= base_url(); ?>themes/bracket/assets/css/bracket.oreo.css">
    <link rel="stylesheet" href="<?= base_url(); ?>themes/bracket/assets/css/ssc-style.css">
    <!-- <link rel="stylesheet" href="<?= base_url(); ?>themes/bracket/assets/css/bracket.dark.css"> -->

    <style>
        .dataTables_wrapper #dataTable_processing {
            height: 100% !important;
            background-color: #ffffff82 !important;
            top: 0;
            left: 50%;
            width: 100%;
            margin-left: -50%;
            margin-top: 0;
            padding-top: 0;
            z-index: 1;
        }

        .sk-wave .sk-rect {
            background-color: #ff790b !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 2.7rem;
            display: block;
        }

        .dataTables_length span.select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 2.7rem;
            display: block;
            min-width: 50px;
            text-align: center;
        }

        .select2-container .select2-selection--single .select2-selection__clear {
            z-index: 5;
        }

        .dataTables_length .select2-container {
            width: 70px;
        }

        .select2-results__option {
            border-radius: 2px;
            margin-bottom: 1px;
            font-size: 1em;
        }

        table td {
            vertical-align: middle !important;
        }

        .sk-wave {
            margin: auto;
            top: 50%;
            position: absolute;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1
        }

        table.dataTable thead th.sorting::before,
        table.dataTable thead th.sorting_asc::before,
        table.dataTable thead th.sorting_desc::before,
        table.dataTable thead td.sorting::before,
        table.dataTable thead td.sorting_asc::before,
        table.dataTable thead td.sorting_desc::before,
        table.dataTable thead th.sorting::after,
        table.dataTable thead th.sorting_asc::after,
        table.dataTable thead th.sorting_desc::after,
        table.dataTable thead td.sorting::after,
        table.dataTable thead td.sorting_asc::after,
        table.dataTable thead td.sorting_desc::after {
            z-index: 0;
        }
    </style>

    <script type="text/javascript">
        var baseurl = "<?= base_url(); ?>";
        var siteurl = "<?= site_url(); ?>";
        var base_url = "<?= site_url(); ?>";
        var thisController = '<?= $this->uri->segment(1); ?>' + '/';
        var thisFunction = '<?= $this->uri->segment(2); ?>' + '/';
    </script>
</head>

<body>
    <!-- ########## START: LEFT PANEL ########## -->
    <div class="br-logo bg-royal ">
        <a href="/">
            <!--<img src="<?php echo base_url('assets/csj-logo-white.png'); ?>" class="text-center" alt="csj logo" width="60">-->
            <i>Gusana ERP</i>
        </a>
    </div>
    <div class="br-sideleft sideleft-scrollbar">
        <label class="sidebar-label pd-x-10 mg-t-20">Navigation</label>
        <?= $this->menu_generator->build_menus(); ?>
    </div><!-- br-sideleft -->
    <!-- ########## END: LEFT PANEL ########## -->

    <!-- ########## START: HEAD PANEL ########## -->
    <div class="br-header bg-royal  ">
        <div class="br-header-left">
            <div class="navicon-left hidden-md-down border-0"><a id="btnLeftMenu" href="#"><i class="icon ion-navicon-round"></i></a></div>
            <div class="navicon-left hidden-lg-up"><a id="btnLeftMenuMobile" href="#"><i class="icon ion-navicon-round"></i></a></div>
            <div class="input-group hidden-xs-down transition border-0">
                <h4 class="text-white mb-0"><?= isset($template['title']) ? $template['title'] : ''; ?></h4>
            </div><!-- input-group -->
        </div><!-- br-header-left -->
        <div class="br-header-right">
            <nav class="nav">
                <div class="dropdown">
                    <a href="#" class="nav-link pd-x-7 pos-relative">
                        <i class="icon ion-ios-email-outline tx-24"></i>
                        <!-- start: if statement -->
                        <span class="square-8 bg-danger pos-absolute t-15 r-0 rounded-circle"></span>
                        <!-- end: if statement -->
                    </a>
                </div><!-- dropdown -->
                <div class="dropdown">
                    <a href="#" class="nav-link pd-x-7 pos-relative">
                        <i class="icon ion-ios-bell-outline tx-24"></i>
                        <!-- start: if statement -->
                        <span class="square-8 bg-danger pos-absolute t-15 r-5 rounded-circle"></span>
                        <!-- end: if statement -->
                    </a>
                </div><!-- dropdown -->
                <div class="dropdown">
                    <a href="#" class="nav-link nav-link-profile" data-toggle="dropdown">
                        <span class="logged-name hidden-md-down"><?= $this->session->app_session['full_name']; ?></span>
                        <img src="https://via.placeholder.com/500" class="wd-32 rounded-circle" alt="">
                        <span class="square-10 bg-success"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-header wd-250">
                        <div class="tx-center">
                            <a href="#"><img src="https://via.placeholder.com/500" class="wd-80 rounded-circle" alt=""></a>
                            <h6 class="logged-fullname">
                                <?= $this->session->app_session['full_name']; ?>
                                <p><?= $this->session->app_session['email']; ?></p>
                        </div>

                        <ul class="list-unstyled user-profile-nav">
                            <li><a href="<?= base_url('users/edit_profile/' . $this->session->app_session['id_user']); ?>"><i class="icon ion-ios-person"></i> Edit Profile</a></li>
                            <li><a href="<?= base_url('logout'); ?>"><i class="icon ion-power"></i> Sign Out</a></li>
                        </ul>
                    </div><!-- dropdown-menu -->
                </div><!-- dropdown -->
            </nav>

        </div><!-- br-header-right -->
    </div><!-- br-header -->
    <!-- ########## END: HEAD PANEL ########## -->

    <div class="br-mainpanel mg-b-50">
        <!-- <div class="br-pageheader">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="index.html">Bracket</a>
                <span class="breadcrumb-item active">Blank Page</span>
            </nav>
        </div> -->
        <!-- br-pageheader -->
        <!-- <div class="br-pagetitle">
            <i class="icon icon ion-ios-book-outline"></i>
            <div>
                <h4>Blank Page (Default Layout)</h4>
                <p class="mg-b-0">Introducing Bracket Plus admin template, the most handsome admin template of all time.
                    </p>
                </div>
            </div> -->
        <!-- d-flex -->

        <!-- <div class="br-pagebody"> -->
        <!-- start you own content here -->
        <!-- <div class="br-pagebpdy pd-x-30 pd-t-20">
        </div> -->