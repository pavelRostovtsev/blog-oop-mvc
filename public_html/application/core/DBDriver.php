<?php 

namespace public_html\application\core;
use PDO;
Class DBDriver
{	
	const FETCH_ALL = 'all';
	const FETCH_ONE = 'one';

	private $pdo;

	public function __construct($pdo) 
	{

		$this->pdo = $pdo;
	}

	public function select($sql, array $params = [], $fetch = self::FETCH_ALL)
	{	

		$stmt = $this->pdo->prepare($sql);
		if (!empty($params)) {
			foreach ($params as $key => $val) {
				if (is_int($val)) {
					$type = PDO::PARAM_INT;
				} else {
					$type = PDO::PARAM_STR;
				}
				$stmt->bindValue(':'.$key, $val, $type);
			}
		}
		$stmt->execute();

		return $fetch === self::FETCH_ALL ? $stmt->fetchAll(PDO::FETCH_ASSOC) : $stmt->fetch(PDO::FETCH_ASSOC);
	}
	public function column($sql, $params = []) {
		
		$stmt = $this->pdo->prepare($sql);
		if (!empty($params)) {
			foreach ($params as $key => $val) {
				if (is_int($val)) {
					$type = PDO::PARAM_INT;
				} else {
					$type = PDO::PARAM_STR;
				}
				$stmt->bindValue(':'.$key, $val, $type);
			}
		}
		$stmt->execute();
		return $stmt->fetchColumn();
	}

	public function insert($table, array $params= [])
	{
		// $params - ассоциативный массив, в ключе у нас столбец бд а в значении передаваймый параметр
		$colums = sprintf('(%s)', implode(',', array_keys($params)));
		$masks = sprintf('(:%s)', implode(', :', array_keys($params)));

		$sql = sprintf('INSERT INTO %s %s VALUES %s', $table, $colums,$masks);
		
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);

		return $this->pdo->LastInsertId();
	}
	
	public function delete($table, array $where= [])
	{
		
		if (count($where) === 3) {

			$operators = ["=" , '>', '<' , '>=', '<=' ];
			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];
			if(in_array($operator, $operators))
			{	
				$sql = sprintf('DELETE FROM %s WHERE %s %s ?', $table, $field, $operator);
				$stmt = $this->pdo->prepare($sql);
				$stmt->bindValue(1,$value);
				$stmt->execute();
				return true;
				
			}
		}

	}
	// оч намудренно вышло , но сука работает)
	// $params принимает массив. Ключ это название столбца, а значение будет новым значение этого столбца
	public function update($table, array $params, $where = []){
		if (!empty($params) ) {
			$i = 1;
			$updateValue = [];
			foreach ($params as $column => $value) {
				$updateValue[$i] = sprintf("%s = '%s'",$column,$value);
				$i++;
			}
			$updateValue = implode(',', $updateValue);

			if(empty($where)){
				$sql = sprintf('UPDATE %s SET %s', $table,$updateValue);
				$stmt = $this->pdo->prepare($sql);
				$stmt->execute();
				return true;
			} elseif(count($where) === 3) {

				$operators = ["=" , '>', '<' , '>=', '<=' ];
				$field = $where[0];
				$operator = $where[1];
				$value = $where[2];	
				if(in_array($operator, $operators)) {

					$sql = sprintf('UPDATE %s SET %s WHERE %s %s ?', $table,$updateValue, $field, $operator);
					$stmt = $this->pdo->prepare($sql);
					$stmt->bindValue(1,$value);
					$stmt->execute();
					return true;
				}			

			} else {
				return false;
			}
			
		}

		return false;
	}
}
