<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title;?></title>

	<!-- bootstrap css -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assests/bootstrap/css/bootstrap.min.css') ?>">
	<!-- datatables css -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assests/datatables/datatables.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assests/sweet-alert/sweet-alert.min.css')?>" type="text/css" />

</head>
<body>

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<center><h1 class="page-header"><?php echo $title;?></h1> </center>

				<button class="btn btn-default pull pull-right" data-toggle="modal" data-target="#addMember">
					<span class="glyphicon glyphicon-plus-sign"></span>	Add Member
				</button>

				<br /> <br /> <br />
				<div class="table table-responsive">
					<table class="table table-striped table-bordered" id="user_data">					
						<thead>
							<tr>
								<th width="4%">No</th>			
								<th width="10%">Foto</th>			
								<th width="30%">Nama Depan</th>													
								<th width="30%">Nama Belakang</th>					
								<th width="8%">Edit</th>
								<th width="8%">Delete</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" tabindex="-1" role="dialog" id="addMember">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><span class="glyphicon glyphicon-plus-sign"></span>	Add Member</h4>
	      </div>
	      
	      <form class="form-horizontal" id="createMemberForm" method="post">

	      <div class="modal-body">
	      	<div class="messages"></div>

			  <div class="form-group"> <!--/here teh addclass has-error will appear -->
			    <label for="nama_depan" class="col-sm-2 control-label">Nama depan</label>
			    <div class="col-sm-10"> 
			      <input type="text" class="form-control" id="nama_depan" name="nama_depan" placeholder="Nama Depan">
				<!-- here the text will apper  -->
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="nama_belakang" class="col-sm-2 control-label">Nama Belakang</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="nama_belakang" name="nama_belakang" placeholder="Nama Belakang">
			    </div>
			  </div>
			   <div class="form-group">
			    <label for="image" class="col-sm-2 control-label">Image</label>
			    <div class="col-sm-10">
			      <input type="file" class="form-control image_id" id="image" name="image" style="margin-bottom: 20px">
			      <span class="users_upload_image"></span>
			    </div>
			  </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <input type="submit" name="action" class="btn btn-primary" value="Add">
	        <input type="hidden" name="users_id" id="users_id" value="Add">
	        <input type="hidden" name="action" id="action" value="Add">
	      </div>
	      </form> 
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<!-- jquery plugin -->
	<script type="text/javascript" src="<?php echo base_url('assests/jquery/jquery.min.js')?>"></script>
	<!-- bootstrap js -->
	<script type="text/javascript" src="<?php echo base_url('assests/bootstrap/js/bootstrap.min.js') ?>"></script>
	<!-- datatables js -->
	<script type="text/javascript" src="<?php echo base_url('assests/datatables/datatables.min.js') ?>"></script>
	<script src="<?php echo base_url('assests/sweet-alert/sweet-alert.min.js')?>"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		var dataTable = $('#user_data').DataTable({
			"processing" : false,
			"serverSide" : true,
			"order"		 : [],
			"ajax"		 : {
				url  : "<?php echo base_url(). 'crud/fetch_user'; ?>",
				type : "POST"
			},
			"columnDefs" : [
				{
					"targets"    : [0, 1, 4, 5],
					"orderable" : false,

				}
			]
		});

		$(document).on('submit', '#createMemberForm', function(event){
			event.preventDefault();
			var namadepan = $('#nama_depan').val(); 
			var namabelakang = $('#nama_belakang').val(); 
			var extension = $('#image').val().split('.').pop().toLowerCase();
			if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
			{
				swal('Oops...', 'Foto Hanya Boleh .gif, .png, .jpg, dan .jpeg', 'error');
				$('#image').val('');
				return false;
			}
			if(namadepan != '' && namabelakang != '')
			{
				$.ajax({
					url : "<?php echo base_url(). 'crud/user_action'; ?>",
					method : 'POST',
					data : new FormData(this),
					contentType : false,
					processData : false,
					dataType	: "json",
					success:function(data)
					{
						swal('Selamat', data.nilai, 'success');
						$('#createMemberForm')[0].reset();
						$('#addMember').modal('hide');
						dataTable.ajax.reload();
					}
				});
			}
			else
			{
				alert('Semua Data Harus di Isi !!');
			} 
		});

		$(document).on('click', '.update', function(){
			var userid = $(this).attr('id');
			$.ajax({
				url 	 : "<?php echo base_url(). 'crud/fetch_single_users'; ?>",
				method	 : "POST",
				data 	 :{userid:userid},
				dataType : "json",
				success	 : function(data)
				{
					$('#addMember').modal('show');
					$('#nama_depan').val(data.nama_depan);
					$('#nama_belakang').val(data.nama_belakang);
					$('.modal-title').text("Edit Users");
					$('#users_id').val(userid);
					$('.users_upload_image').html(data.users_image);
					$('#action').val("Edit");
				}
			});
		});

		$(document).on('click', '.delete', function(){
			var user_id = $(this).attr("id");
			 swal({   
	            title: "Are you sure?",   
	            text: "You will not be able to recover this imaginary file!",   
	            type: "warning",   
	            showCancelButton: true,   
	            confirmButtonColor: "#DD6B55",   
	            confirmButtonText: "Yes, delete it!",   
	            cancelButtonText: "No, cancel plx!",   
	            closeOnConfirm: false,   
	            closeOnCancel: false 
	        }, function(isConfirm){
	        	$.ajax({
	        		url : "<?php echo base_url(). 'crud/delete_single_user'; ?>",
	        		method : "POST",
	        		data : {user_id:user_id}
	        	}) 
	            if (isConfirm) {     
	                swal("Deleted!", "Your imaginary file has been deleted.", "success");
	                dataTable.ajax.reload();   
	            } else {     
	                swal("Cancelled", "Your imaginary file is safe :)", "error");   
	            } 
	        });
		});
	});
	</script>

</body>
</html>