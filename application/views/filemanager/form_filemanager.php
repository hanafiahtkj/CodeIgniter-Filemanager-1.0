<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Filemanager 1.0</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('public/assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
	
	<!-- Font Awesome CSS -->
    <link href="<?php echo base_url('public/assets/plugins/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('public/assets/css/custom_style.css'); ?>" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">File Manager 1.0</a>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('form'); ?>">
                  <span data-feather="home"></span>
                  Form
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('tabel'); ?>">
                  <span data-feather="file"></span>
                  Tabel
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('summernote'); ?>">
                  <span data-feather="shopping-cart"></span>
                  Summernote
                </a>
              </li>
            </ul>
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Contact Me</span>
              <a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
              </a>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  FB : fb.com/hanafi.ah.3975
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Twitter : @Hanafi_Dev
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  WA : 085348084883
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Filemanager & Form</h1>
          </div>

          <form action="POST" id="form-file-manager">
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Title</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" placeholder="Title">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Image</label>
              <div class="col-sm-10">
                <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail" data-original-title="" title="">
                <img src="<?php echo base_url('public/assets/img/input_icon.png'); ?>" alt="" title="" data-placeholder="<?php echo base_url('public/assets/img/input_icon.png'); ?>" style="max-width:100px;">
                </a>
                <input name="image" value="" id="input-image" type="hidden">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary" id="save-btn">Kirim</button>
              </div>
            </div>
          </form>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url('public/assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('public/assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('public/assets/js/common.js'); ?>"></script>
    <script>
      $('body').on('submit', '#form-file-manager', function (e) {
        e.preventDefault();
        alert('Success');
      });
    </script>
  </body>
</html>
