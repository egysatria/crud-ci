<?php
class Crud extends CI_Controller {

	public function index()
	{
		$data['title'] = "CRUD CI";
		$this->load->view('view_crud', $data);
	}

	public function fetch_user()
	{
		$this->load->model("crud_model");
		$fetch_data = $this->crud_model->make_datatables();
		$data = array();
		$no   = 1;
		foreach ($fetch_data as $row) 
		{
			$sub_array = array();
			$sub_array[] = $no++.". ";
			$sub_array[] = '<img src="'.base_url().'assests/images/'.$row->image.'" class="img-thumbnail" width="50" height="35" />';
			$sub_array[] = $row->nama_depan;
			$sub_array[] = $row->nama_belakang;
			$sub_array[] = '<button type="button" name="update" id="'.$row->id.'" class="btn btn-info btn-xs update">Update</button>';
			$sub_array[] = '<button type="button" name="delete" id="'.$row->id.'" class="btn btn-danger btn-xs delete">Delete</button>';
			$data[] = $sub_array;
		}

		$output = array(
			"draw" 				=> intval($_POST['draw']),
			"recordsTotal" 		=> $this->crud_model->get_all_data(),
			"recordsFiltered" 	=> $this->crud_model->get_filtered_data(),
			"data"				=> $data
		);

		echo json_encode($output);
	}

	public function user_action()
	{
		$data = array();

		if($_POST['action'] == "Add")
		{
			$insert_data = array(
				'nama_depan'	=> $this->input->post('nama_depan'),
				'nama_belakang'	=> $this->input->post('nama_belakang'),
				'image'			=> $this->upload_image()
			);

			$this->load->model('crud_model');
			$this->crud_model->insert_crud($insert_data);
			$data['nilai'] = "Data Berhasil Di Inputkan :)";
		}

		if($_POST['action'] == "Edit")
		{
			if($_FILES['image']['name'] != '')
			{
				$user_image = $this->upload_image();
			}
			else
			{
				$user_image = $this->input->post("hidden_user_image");
			}
			$update_data = array(
				'nama_depan' 	=> $this->input->post('nama_depan'),
				'nama_belakang' => $this->input->post('nama_belakang'),
				'image'			=> $user_image
			);

			$this->load->model('crud_model');
			$this->crud_model->update_crud($this->input->post('users_id'), $update_data);
			$data['nilai'] = "Data Berhasil Di Ubah !! :)";
		}

		echo json_encode($data);
	}

	public function upload_image()
	{
		if(isset($_FILES['image']))
		{
			$ex = explode(".", $_FILES['image']['name']);
		    $ft = "foto -".round(microtime(true)).".".end($ex);
		    $sumber = $_FILES['image']['tmp_name'];
		    $simpan = move_uploaded_file($sumber, "./assests/images/".$ft);
			return $ft;
		}
	}

	public function fetch_single_users()
	{
		$output = array();
		$this->load->model('crud_model');
		$data = $this->crud_model->fetch_single_user($_POST['userid']);
		foreach ($data as $row) 
		{
			$output['nama_depan'] = $row->nama_depan;
			$output['nama_belakang'] = $row->nama_belakang;
			if($row->image != "")
			{
				$output['users_image'] = '<img src="'.base_url().'./assests/images/'.$row->image.'" class="img-thumbnail" width="100" height="80" /><input type="hidden" name="hidden_user_image" value="'.$row->image.'" />';
			}
			else
			{
				$output['users_image'] = '<input type="hidden" name="hidden_user_image" value="" />';
			}
			$output['image'] = $row->image;
		}

		echo json_encode($output);
	}

	public function delete_single_user()
	{
		$this->load->model('crud_model');
		$this->crud_model->delete_users($_POST['user_id']);
	}
}
