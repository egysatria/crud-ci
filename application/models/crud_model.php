<?php
class Crud_model extends CI_Model {

	var $table = "users";
	var $select_column = array("id", "nama_depan", "nama_belakang", "image");
	var $order_column = array(NULL, NULL, "nama_depan", "nama_belakang", NULL, NULL);

	function make_query()
	{
		$this->db->select($this->select_column);
		$this->db->from($this->table);
		if(isset($_POST['search']['value']))
		{
			$this->db->like("nama_depan", $_POST['search']['value']);
			$this->db->or_like("nama_belakang", $_POST['search']['value']);
		}
		if(isset($_POST['order']))
		{
			$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else
		{
			$this->db->order_by("id", "DESC");
		}
	}

	function make_datatables()
	{
		$this->make_query();
		if($_POST["length"] != 1)
		{
			$this->db->limit($_POST['length'], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	function get_filtered_data()
	{
		$this->make_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_all_data()
	{
		$this->db->select("*");
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	function insert_crud($data)
	{
		$this->db->insert('users', $data);
	}

	function fetch_single_user($userid)
	{
		$this->db->where("id", $userid);
		$query = $this->db->get("users");
		return $query->result();
	}

	function update_crud($userid, $data)
	{
		$this->db->where("id", $userid);
		$this->db->update("users", $data);
	}

	function delete_users($user_id)
	{
		$this->db->where("id", $user_id);
		$this->db->delete('users');
	}
}
