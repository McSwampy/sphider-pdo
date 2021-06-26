<?php
/**
 * Spider Version 1.3.*
 * 
 * This program is licensed under the GNU GPL.
 * 
 * [auth] Vincent Seaborne v.seaborne@gmail.com
 * 
 */
class DB {
	
	public static $I;
	private static $conn;
	private static $prepare;
	
	public function __construct(){
		try {
		  self::$conn = new PDO('mysql:host=localhost;port=3307;dbname=sphider', 'root', '');
		  self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			
		}
	}
	
	public static function getSQL(string $sql){
		try{
			self::query($sql);
			return self::$prepare->fetchAll(PDO::FETCH_ASSOC); 
		}catch(\Throwable $e){
			return false;
		}
	}
	
	public static function insert($array, $table, $replace=false){
		$sql												= ($replace == true) ? 'REPLACE INTO `'.$table.'`' : 'INSERT INTO `'.$table.'`';
		$columns											= '';
		$values												= '';
		foreach($array as $k=>$v){
			$columns										.= '`'.$k.'`,';
			$values											.= '\''.self::sqlClean($v).'\',';
		}
		$sql												.= ' ('.rtrim($columns, ',').') VALUES ('.rtrim($values, ',').');';
		self::query($sql);
		return self::$conn->lastInsertId();
	}
	
	public static function getRecord($table, $where=[], $fields='*', $offset=0){
		$rec								= self::getRecords($table, $where, $fields, $offset, 1);
		if(isset($rec[0]))
			return $rec[0];
		return false;
	}
	
	public static function getRecordsOrdered($table, $orderBy, $where=[], $fields='*', $offset=0, $limit=null){
		$where											= self::buildWhere($where);
		if($limit != null)
			$limit										= ' LIMIT '.$limit.' OFFSET '.$offset;
		self::query('SELECT '.$fields.' FROM `'.$table.'`'.$where.' ORDER BY '.$orderBy.$limit);
		return self::$prepare->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	public static function getRecords($table, $where=[], $fields='*', $offset=0, $limit=null){
		$where											= self::buildWhere($where);
		if($limit != null)
			$limit										= ' LIMIT '.$limit.' OFFSET '.$offset;
		$orderBy										= ($orderBy == '') ? '' : ' ORDER BY '.$orderBy;
		self::query('SELECT '.$fields.' FROM `'.$table.'`'.$where.$limit.$orderBy);
		return self::$prepare->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	public static function update($updateArray, $table, $where=[]){
		$where											= self::buildWhere($where);
		$updateContent									= '';
		foreach($updateArray as $k=>$v){
			$updateContent								.= '`'.$k.'`=\''.$v.'\',';
		}
		self::query('UPDATE `'.$table.'` SET '.rtrim($updateContent, ',').$where);
	}
	
	private static function sqlClean(string $s){
		return str_replace('\'', '\\\'', $s);
	}
	
	private static function query($sql){
		try{
			if(self::$I == null)
				self::$I = new DB();
			self::$prepare = self::$conn->prepare($sql);
			self::$prepare->execute();
		}catch(\Throwable $e){
			echo $e->getMessage();
		}
	}
	
	private static function buildWhere($array){
		$where												= '';
		if(is_array($array) && !empty($array)){
			$where											= ' WHERE ';
			foreach($array as $k=>$v)
				$where										.= '`'.$k.'`=\''.$v.'\' AND ';
		}
		return rtrim($where, ' AND ');
	}
}