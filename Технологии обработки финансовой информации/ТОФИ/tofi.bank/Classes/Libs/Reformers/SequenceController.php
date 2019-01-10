<?php
/**
 * @name	SequenceController
 */
class SequenceController 
{
	public $UnickArr = array();
	public $SortIdTable;
	public $SortIdField;
	public $SortIdTableIdField;
	/**
	 * @var DataBase
	 */
	public $DataBase;
	
	public function __construct($DataBase, $SortIdTable, $SortIdField = "sort_id", $SortIdTableIdField = "id") 
	{
		$this->SortIdTable = $SortIdTable;
		$this->SortIdField = $SortIdField;
		$this->SortIdTableIdField = $SortIdTableIdField;
		$this->DataBase = $DataBase;
	}

	public function getMax() 
	{		
		$new_sort_id = 0;
		
		$max_query = '	SELECT
				max('.$this->SortIdField.') as max_sort_id
		';
		$count_query = '	SELECT
				count('.$this->SortIdField.') as cnt
		';
		$query = '
			FROM
				'.$this->SortIdTable.'
			WHERE
				1=1
		';
		foreach ($this->UnickArr as $field => $value) {
			$query .= " and (".$field." = '".$value."')";
		} 
		
		$res = $this->DataBase->selectCustomSql($max_query.$query);
		if($res->rowCount())
		{
			$row = $res->fetch();
			$new_sort_id = $row["max_sort_id"];
		}
		$res = $this->DataBase->selectCustomSql($count_query.$query);
		if($res->rowCount())
		{
			$row = $res->fetch();
			if($row['cnt'] != $new_sort_id)
			{
				$new_sort_id = $row['cnt'];
			}
		}
		
		return $new_sort_id;
	}
	
	public function getNext() 
	{		
		return $this->getMax()+1;
	}

	public function recalculate()
	{
		$res = $this->DataBase->selectSql($this->SortIdTable, $this->UnickArr, array($this->SortIdField => "ASC"));
		$i = 1;
		foreach($res as $row) {
			$this->DataBase->updateSql($this->SortIdTable, array($this->SortIdField => $i), array($this->SortIdTableIdField => $row[$this->SortIdTableIdField]));
			$i++;
		}
	}

	public function moveUp($SortId)
	{
		if($SortId <= $this->getMax()  and $SortId > 1)
		{
			$itemFrom = $this->DataBase->selectSql($this->SortIdTable, $this->UnickArr + array($this->SortIdField => $SortId));
			$itemTo = $this->DataBase->selectSql($this->SortIdTable, $this->UnickArr + array($this->SortIdField => $SortId-1));

			if($itemFrom->rowCount() and $itemTo->rowCount())
			{
				$itemFromRow = $itemFrom->fetch();
				$itemToRow = $itemTo->fetch();
				$this->DataBase->updateSql($this->SortIdTable, array($this->SortIdField => ($SortId-1)), array($this->SortIdTableIdField => $itemFromRow[$this->SortIdTableIdField]));
				$this->DataBase->updateSql($this->SortIdTable, array($this->SortIdField => ($SortId)), array($this->SortIdTableIdField => $itemToRow[$this->SortIdTableIdField]));
			}
		}
	}

	public function moveDown($SortId)
	{
		if($SortId < $this->getMax()  and $SortId >= 1)
		{
			$itemFrom = $this->DataBase->selectSql($this->SortIdTable, $this->UnickArr + array($this->SortIdField => $SortId));
			$itemTo = $this->DataBase->selectSql($this->SortIdTable, $this->UnickArr + array($this->SortIdField => $SortId+1));

			if($itemFrom->rowCount() and $itemTo->rowCount())
			{
				$itemFromRow = $itemFrom->fetch();
				$itemToRow = $itemTo->fetch();
				$this->DataBase->updateSql($this->SortIdTable, array($this->SortIdField => ($SortId+1)), array($this->SortIdTableIdField => $itemFromRow[$this->SortIdTableIdField]));
				$this->DataBase->updateSql($this->SortIdTable, array($this->SortIdField => ($SortId)), array($this->SortIdTableIdField => $itemToRow[$this->SortIdTableIdField]));
			}
		}
	}
	
	public function shift($SortId1, $SortId2)
	{
		$itemFrom = $this->DataBase->selectSql($this->SortIdTable, $this->UnickArr + array($this->SortIdField => $SortId1));
		$itemTo = $this->DataBase->selectSql($this->SortIdTable, $this->UnickArr + array($this->SortIdField => $SortId2));

		if($itemFrom->rowCount() and $itemTo->rowCount())
		{
			$itemFromRow = $itemFrom->fetch();
			$itemToRow = $itemTo->fetch();
			$this->DataBase->updateSql($this->SortIdTable, array($this->SortIdField => ($SortId2)), array($this->SortIdTableIdField => $itemFromRow[$this->SortIdTableIdField]));
			$this->DataBase->updateSql($this->SortIdTable, array($this->SortIdField => ($SortId1)), array($this->SortIdTableIdField => $itemToRow[$this->SortIdTableIdField]));
		}
	}
}

function sequence_view_list($seq)
{
	if($seq>1)
		$str = '<center><a href="?seq_up='.$seq.'"><img style="padding-right: 5px;" src="'.Config::$imagesPath.'cms/tree/list_up.gif" alt="" border="0" /></a>';
	else 
		$str = '<center><img style="padding-right: 5px;" src="'.Config::$imagesPath.'cms/tree/list_up_inact.gif" alt="" border="0" />';
	
	$max = InCache("SequenceMaxValue", 1, "SequenceController");
	
	if((int)$seq<(int)$max)
		$str .= '<a href="?seq_down='.$seq.'"><img src="'.Config::$imagesPath.'cms/tree/list_down.gif" alt="" border="0" /></a></center>';
	else 
		$str .= '<img src="'.Config::$imagesPath.'cms/tree/list_down_inact.gif" alt="" border="0" /></center>';
	return $str;
}

function sequence_view_list2($seq)
{
	if($seq>1)
		$str = '<center><a href="?seq_up2='.$seq.'"><img style="padding-right: 5px;" src="'.Config::$imagesPath.'cms/tree/list_up.gif" alt="" border="0" /></a>';
	else 
		$str = '<center><img style="padding-right: 5px;" src="'.Config::$imagesPath.'cms/tree/list_up_inact.gif" alt="" border="0" />';
	
	$max = InCache("SequenceMaxValue", 1, "SequenceController2");
	
	if((int)$seq<(int)$max)
		$str .= '<a href="?seq_down2='.$seq.'"><img src="'.Config::$imagesPath.'cms/tree/list_down.gif" alt="" border="0" /></a></center>';
	else 
		$str .= '<img src="'.Config::$imagesPath.'cms/tree/list_down_inact.gif" alt="" border="0" /></center>';
	return $str;
}