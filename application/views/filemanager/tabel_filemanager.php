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
            <h1 class="h2">Filemanager & Tabel</h1>
          </div>

          <form action="POST" id="form-file-manager">
            <div class="mb-3 pull-right">
								<button type="button" class="btn btn-success" id="select-btn"><i class="fa fa-plus"></i> Select Files</button>
						</div>
						<table class="table table-bordered table-hover" id="table_image">
							<thead>
								<tr role="row" class="heading">
									<th width="10%"> Image </th>
									<th width="60%"> File Name </th>
									<th width="20%"> Sort Order </th>
									<th width="10%"> Action</th>
								</tr>
							</thead>
							<tbody>
								<tr id="no_result"><td colspan="7" class="text-center">Tidak ada data ditemukan</td></tr>
							</tbody>
						</table>
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
    <script>
      var noImage = 0;
      $('#select-btn').on('click', function(e) {
        var $button = $(this);
        var $icon   = $button.find('> i');
        $('#modal-image').remove();
        $.ajax({
          url: '<?php echo base_url('filemanager'); ?>',
          dataType: 'html',
          beforeSend: function() {
            $button.prop('disabled', true);
            if ($icon.length) {
              $icon.attr('class', 'fa fa-circle-o-notch fa-spin');
            }
          },
          complete: function() {
            $button.prop('disabled', false);
            if ($icon.length) {
              $icon.attr('class', 'fa fa-pencil');
            }
          },
          success: function(html) {
            $('body').append('<div id="modal-image" class="modal">' + html + '</div>');	
            $('#modal-image').modal('show');
            $('#modal-image').delegate('a.thumbnail', 'click', function(e) {
              e.preventDefault();
              noImage = noImage + 1;
              var imageSrc = $(this).find('img').attr('src');
              var imagePath = $(this).parent().find('input').val();
              if ($('#no_result').length == 1) {
                $('#no_result').remove();
              } 	
              $('#table_image tbody').append('<tr id="' + noImage +'"><td><img class="img-thumbnail" src="' + imageSrc + '"></td><td>' + imagePath + '</td><td><input type="text" class="form-control" name="images[' + noImage +'][sort_order]" value="' + noImage +'"> </td><td><a href="javascript:;" class="btn btn-secondary btn-sm btn_remove" data-id="'+ noImage +'"><i class="fa fa-times"></i> Remove </a><input type="hidden" name="images[' + noImage +'][name]" value="' + imagePath + '" /></td></tr>');						
              $('#modal-image').modal('hide');
            });
          }
        });
      });

      $('#table_image tbody').on('click', '.btn_remove', function(e) {
        var id = $(this).data('id');
        $('tr#'+ id).remove();
      });
      </script>
  </body>
</html>
