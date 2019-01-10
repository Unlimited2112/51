<?php

interface Searchable 
{
	/**
	 * @return array
	 */
	public function getSearchFields();
	
	/**
	 * @param string $whereSQLQuery
	 * @return string
	 */
	public function getSearchSQLQuery($whereSQLQuery);
	
	/**
	 * @param string $whereSQLQuery
	 * @return string
	 */
	public function getSearchCountSQLQuery($whereSQLQuery);

	/**
	 * @param integer $item_id
	 * @param string $item_uri
	 * @return string
	 */
	public function getSearchUriProcessor($item_id, $item_uri);
}