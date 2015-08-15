<?php
class Model_RunePages extends Zend_Db_Table_Abstract
{
	protected $_name = 'runepages';
	protected $_primary = 'entry_id';

	public function createRunePage($data)
	{
		// Get actual data
		$result = $this->insert($data);
		return $result;
	}

	public function getRunePages($data)
	{
		if (empty($data)) {throw new Exception('Empty Data');}
		$select = $this->select();

		$select->where('summoner_id = ?', $data);

		// Get actual data
		$stmt = $select->query();
		$results = $stmt->fetchAll();
		if (count($results) == 1) return $results[0];

		return null;
	}

}