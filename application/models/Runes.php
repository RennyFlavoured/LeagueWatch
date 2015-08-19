<?php
class Model_Runes extends Zend_Db_Table_Abstract
{
	protected $_name = 'runes';
	protected $_primary = 'rune_entry_id';

	public function createRunes($data)
	{

		// Get actual data
		$result = $this->insert($data);
		return $result;
	}
	
	public function getSummoner($ID)
	{
		if (empty($ID)) {throw new Exception('Empty ID');}
		$select = $this->select();

		$select->where('summoner_id = ?', $ID);

		// Get actual data
		$stmt = $select->query();
		$results = $stmt->fetchAll();
		if (count($results) == 1) return $results[0];

		return null;
	}

}