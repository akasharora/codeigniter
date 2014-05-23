<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

	const DB_TABLE = 'abstract';
	const DB_TABLE_PK = 'abstract';

	/**
	* Create Record
	*/
	private function create()
	{	
		/* $this::DB_TABLE refers to the table while $this refers to the calling object */
		$this->db->insert($this::DB_TABLE,$this);
		$this->{$this::DB_TABLE_PK} = $this->db->insert_id();
	}

	/**
	* Update Record
	*/
	private function update()
	{	
		/* takes three params table name, calling object represented here by $this and primary key */
		$query = $this->db->update($this::DB_TABLE,$this,array($this::DB_TABLE_PK => $this->{$this::DB_TABLE_PK}));
		// echo $this->db->last_query();

	}

	/**
	* Populate from an array or standard class
	* @Param mixed $row
	*/
	public function populate($row)
	{
		foreach ($row as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	* Load from database
	* @Param type $id
	*/
	public function load($id)
	{
		$query = $this->db->get_where($this::DB_TABLE,array($this::DB_TABLE_PK=>$id) );
		$this->populate($query->row());
	}

	/**
	 * deletes the current record
	 */
	public function delete()
	{
		$this->db->delete($this::DB_TABLE,array($this::DB_TABLE_PK => $this->{$this::DB_TABLE_PK}) );
		unset($this->{$this::DB_TABLE_PK});
	}

	/**
	 * saves the record
	 */
	public function save()
	{	
		
		if(isset($this->{$this::DB_TABLE_PK}))
		{		
			$this->update();
		} else
		{
			$this->create();
		}
	}

	/**
	 * Get a array of models with an option for offset and limit
	 *
	 * @param int $limit optional
	 * @param int $offset optional; if set requires $limit
	 * @return array models populated bydatabse keyed by PK
	 */
	public function get($limit=0, $offset=0)
	{
		if($limit)
		{
			$query = $this->db->get($this::DB_TABLE,$limit,$offset);
		} else
		{
			$query = $this->db->get($this::DB_TABLE);
		}

		$ret_val = array();

		/* returns name of the class with object as param */
		$class = get_class($this);

		foreach ($query->result() as $row) 
		{
			$model = new $class;
			$model->populate($row);
			$ret_val[$row->{$this::DB_TABLE_PK}] = $model;
		}
		return $ret_val;
	}

}

/* End of file my_model.php */
/* Location: ./application/core/my_model.php */