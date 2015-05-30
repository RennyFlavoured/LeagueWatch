<?php
class Model_Summoners extends Zend_Db_Table_Abstract
{
	protected $_name = 'summoners';
	protected $_primary = 'summoner_id';

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

	public function createSummoner($data)
	{
		if (empty($Email)) {throw new Exception('Empty Email');}
		$select = $this->select();

		$select->where('Email = ?', $Email);

		// Get actual data
		$stmt = $select->query();
		$results = $stmt->fetchAll();
		if (count($results) == 1) return $results[0];

		return null;
	}

	public function searchAdmins($Filter = null, $Return = 'data')
	{
		$select = $this->select();

		if ((! empty($Filter)) && (is_array($Filter)))
		{
			//if (! empty($Filter['date'])) $select->where('TO_DAYS(DateCreated) = TO_DAYS(?)', $Filter['date']);
			//if (! empty($Filter['language'])) $select->where('Language LIKE ?', $Filter['language']);
		}

		$select->order(array('AdminID DESC'));

		// Return select object for pagination
		if ($Return == 'select') return $select;

		// Return actual data (*scared*)
		$stmt = $select->query();
		return $stmt->fetchAll();
	}

	public function updateAdmin($Data, $AdminID)
	{
		if (empty($AdminID)) {throw new Exception('Empty AdminID');}

		$AdminSQL = $this->getAdapter()->quoteInto('AdminID = ?', $AdminID);
		return $this->update($Data, $AdminSQL);
	}

	public static function renderPassword($Password, $Salt)
	{
		return md5($Password.$Salt);
	}
}