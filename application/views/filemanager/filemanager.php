<div class="modal-dialog modal-lg" role="document" id="file-manager">
<div class="modal-content">
  <div class="modal-header">
	<h5 class="modal-title">File Manager</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  <span aria-hidden="true">&times;</span>
	</button>
  </div>
  <div class="modal-body">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-5 mb-3">
				<a href="<?php echo $parent; ?>" data-toggle="tooltip" id="button-parent" class="btn btn-secondary" data-placement="top" title="Parent"><i class="fa fa-level-up"></i></a> 
				<a href="<?php echo $refresh; ?>" data-toggle="tooltip" id="button-refresh" class="btn btn-success" data-placement="top" title="Refresh"><i class="fa fa-refresh"></i></a>
				<button type="button" data-toggle="tooltip" id="button-upload" class="btn btn-primary" data-placement="top" title="Upload"><i class="fa fa-upload"></i></button>
				<button type="button" data-toggle="tooltip" id="button-folder" class="btn btn-info" data-placement="top" title="New Folder"><i class="fa fa-folder"></i></button>
				<button type="button" data-toggle="tooltip" id="button-delete" class="btn btn-danger" data-placement="top" title="Delete"><i class="fa fa-trash-o"></i></button>
				<input type="hidden" name="directory" value="<?php echo $directory; ?>"/>
			</div>
			<div class="col-sm-7">
			  <div class="input-group">
				<input type="text" name="search" value="<?php echo $filter_name; ?>" placeholder="Search.." class="form-control">
				<div class="input-group-append">
					<button type="button" data-toggle="tooltip" title="" id="button-search" class="btn btn-primary" data-original-title="Search"><i class="fa fa-search"></i></button>
				</div>
			  </div>
			</div>
		</div>
		<hr>
		<div class="row">
		<?php foreach ($images as $img) : ?>
			<div class="col-sm-3 col-xs-6 text-center mb-3">
			<?php if ($img['type'] == 'directory') : ?>
				<div class="text-center"><a href="<?php echo $img['href']; ?>" class="directory" style="vertical-align: middle;"><i class="fa fa-folder fa-5x"></i></a></div>
				<label><input type="checkbox" name="path[]" value="<?php echo $img['path']; ?>" class="flat-red"> <?php echo $img['name']; ?></label>
			<?php else : ?>
				<a href="<?php echo $img['href']; ?>" class="thumbnail"><img src="<?php echo $img['thumb']; ?>" alt="<?php echo $img['name']; ?>" title="<?php echo $img['name']; ?>" style="max-height: 6em;"/></a>
				<label><input type="checkbox" name="path[]" value="<?php echo $img['path']; ?>" class="flat-red" /> <?php echo $img['name']; ?></label>
			<?php endif; ?>
			</div>
		<?php endforeach; ?>
		</div>
  </div>
  <div class="modal-footer"><?php echo $pagination; ?></div>
</div>
</div>
<script>
<?php if (isset($target)) : ?>
$('a.thumbnail').on('click', function(e) {
	e.preventDefault();
	
	<?php if (isset($thumb)) : ?> 
	
		$('#<?php echo $thumb; ?>').find('img').attr('src', $(this).find('img').attr('src'));
		
	<?php endif; ?>

	$('#<?php echo $target; ?>').val($(this).parent().find('input').val());

	$('#modal-image').modal('hide');
});
<?php endif; ?>

$('#file-manager [data-toggle="tooltip"]').tooltip({
	container : '#file-manager .modal-content'
});

$('a.directory').on('click', function(e) {
	e.preventDefault();
	$('#modal-image').load($(this).attr('href'));
});

$('#button-parent').on('click', function(e) {
	e.preventDefault();
	$('#modal-image').load($(this).attr('href'));
});

$('#button-refresh').on('click', function(e) {
	e.preventDefault();
	$('#modal-image').load($(this).attr('href'));
});

$('#modal-image').on('click', '.modal-footer a', function(e) {
	e.preventDefault();
	$('#modal-image').load($(this).attr('href'));
});
</script>
<script>
$('#modal-image').on('click', '#button-search', function(e) {
	var url = '<?php echo base_url('filemanager'); ?>';
	var directory = $('#modal-image input[name=\'directory\']').val();
	if (directory) {
		url += '?directory=' + encodeURIComponent(directory);
	}
	var filter_name = $('#modal-image input[name=\'search\']').val();
	
	url += (directory) ? '&filter_name=' + encodeURIComponent(filter_name) : '?filter_name=' + encodeURIComponent(filter_name);
	
	<?php if (isset($thumb)) : ?>
		url += '&thumb=<?php echo $thumb; ?>';
	<?php endif; ?>
	
	<?php if (isset($target)) : ?>
		url += '&target=<?php echo $target; ?>';
	<?php endif; ?>
  
	$('#modal-image').load(url);
});

$('#button-upload').on('click', function() {
	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" value=""/></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: '<?php echo base_url('filemanager/upload?directory='. $directory); ?>',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
					$('#button-upload').prop('disabled', true);
				},
				complete: function() {
					$('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
					$('#button-upload').prop('disabled', false);
				},
				success: function(json) {
					if (json['error']) {
						alert(json['error']);
					}

					if (json['success']) {
						alert(json['success']);

						$('#button-refresh').trigger('click');
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

$('#file-manager #button-folder').popover({
	html: true,
	container: '#file-manager .modal-content',
	placement: 'bottom',
	trigger: 'click',
	title: 'New Folder',
	content: function() {
		html  = '<div class="input-group">';
		html += '  <input type="text" name="folder" value="" placeholder="Folder Name" class="form-control">';
		html += '  <div class="input-group-append"><button type="button" title="New Folder" id="button-create" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></div>';
		html += '</div>';

		return html;
	}
});

$('#button-folder').on('shown.bs.popover', function() {
	$('#button-create').on('click', function() {
		$.ajax({
			url: '<?php echo base_url('filemanager/folder?directory='. $directory); ?>',
			type: 'post',
			dataType: 'json',
			data: 'folder=' + encodeURIComponent($('input[name=\'folder\']').val()),
			beforeSend: function() {
				$('#button-create').prop('disabled', true);
			},
			complete: function() {
				$('#button-create').prop('disabled', false);
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}

				if (json['success']) {
					alert(json['success']);

					$('#button-refresh').trigger('click');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
});

$('#file-manager #button-delete').on('click', function(e) {
	if (confirm('Are you sure')) {
		$.ajax({
			url: '<?php echo base_url('filemanager/delete'); ?>',
			type: 'post',
			dataType: 'json',
			data: $('input[name^=\'path\']:checked'),
			beforeSend: function() {
				$('#button-delete').prop('disabled', true);
			},
			complete: function() {
				$('#button-delete').prop('disabled', false);
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}

				if (json['success']) {
					alert(json['success']);

					$('#button-refresh').trigger('click');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
</script>