<?php
/**
 * FlxPHP 2.2
 * Author:FlxSNX<211154860@qq.com>
 * [FlxPHP] - 数据操作
 */

namespace extend;
class DB
{
	private static $instance = null;

	private $conf = null;

    private $conn = null;

    private static $sconn = null;

    private static $sql = null;

    private static $action = null;

    private static $table = null;

    private static $where = null;

    private static $order = null;

    private static $limit = null;

	private function __construct(){
		global $Flx;
		$_CFG = $Flx->_CFG;
		$this->conn = mysqli_connect($_CFG['DB']['host'],$_CFG['DB']['user'],$_CFG['DB']['pass'],$_CFG['DB']['name'],$_CFG['DB']['port']);
		if(mysqli_connect_errno() > 0){
            error_page(
                [
                    'title' => 'BKM Mysql Error',
                    'info' => '数据库连接失败['.mysqli_connect_errno().']:'.mysqli_connect_error(),
                    'text' => '请检查数据库配置'
                ]
            );
        }else{
            mysqli_query($this->conn,"set names 'utf8mb4'");
        }
	}

	public static function init(){
		if(!self::$instance instanceof self){
            self::$instance = new DB();
        }

        return self::$instance;
	}


	public static function table($name){
		if(!self::$instance instanceof self){
            self::$instance = new DB();
        }

        self::$instance->varinit();

        self::$table = '`'. $name .'`';

        return self::$instance;
	}

	public function where($key,$d='',$value=''){
		if(is_array($key) and !$d and !$value){
			if(@$key['where'] and $key['d']){
				$wherearr = $key['where'];
				$wheres = count($key['where']);
				$darr = $key['d'];
				$ds = count($key['d']);
			}else{
				$wherearr = $key;
				$wheres = count($key);
				$ds = 0;
			}
			$i = 0;
			if($ds == $wheres){
				foreach ($wherearr as $k => $v) {
					if(!strstr(self::$where,'WHERE')){
						self::$where = 'WHERE `' . $k . '`' . ' ' . $darr[$i] . ' ' . '\'' . $v . '\'';
					}else{
						self::$where .= ' AND `' . $k . '`' . ' ' . $darr[$i] . ' ' . '\'' . $v . '\'';
					}
					$i++;
				}
			}else{
				foreach ($wherearr as $k => $v) {
					if(!strstr(self::$where,'WHERE')){
						self::$where = 'WHERE `' . $k . '`' . ' ' . '=' . ' ' . '\'' . $v . '\'';
					}else{
						self::$where .= ' AND `' . $k . '`' . ' ' . '=' . ' ' . '\'' . $v . '\'';
					}
					$i++;
				}
			}
			return $this;
		}else{
			if(!strstr(self::$where,'WHERE')){
				if($d == '' and $value == ''){
					self::$where = 'WHERE ' . $key;
					return $this;
				}
				if($value == ''){
					self::$where = 'WHERE `' . $key . '`' . ' = ' . '\'' . $d . '\'';
					return $this;
				}else{
					self::$where = 'WHERE `' . $key . '`' . ' ' . $d . ' ' . '\'' . $value . '\'';
					return $this;
				}
			}else{
				if($d == '' and $value == ''){
					self::$where .= ' AND ' . $key;
					return $this;
				}
				if($value == ''){
					self::$where .= ' AND `' . $key . '`' . ' = ' . '\'' . $d . '\'';
					return $this;
				}else{
					self::$where .= ' AND `' . $key . '`' . ' ' . $d . ' ' . '\'' . $value . '\'';
					return $this;
				}
			}
		}
	}

	public function whereor($key,$d='',$value=''){
		if(is_array($key) and !$d and !$value){
			if($key['where'] and $key['d']){
				$wherearr = $key['where'];
				$wheres = count($key['where']);
				$darr = $key['d'];
				$ds = count($key['d']);
			}else{
				$wherearr = $key;
				$wheres = count($key);
				$ds = 0;
			}
			$i = 0;
			if($ds == $wheres){
				foreach ($wherearr as $k => $v) {
					self::$where .= ' OR `' . $key . '`' . ' ' . $darr[$i] . ' ' . '\'' . $value . '\'';
					$i++;
				}
			}else{
				foreach ($wherearr as $k => $v) {
					self::$where .= ' OR `' . $k . '`' . ' = ' . '\'' . $v . '\'';
					$i++;
				}
			}
			return $this;
		}else{
			if($d == '' and $value == ''){
				self::$where .= ' OR ' . $key;
				return $this;
			}
			if($value == ''){
				self::$where .= ' OR `' . $key . '`' . ' = ' . '\'' . $d . '\'';
				return $this;
			}else{
				self::$where .= ' OR `' . $key . '`' . ' ' . $d . ' ' . '\'' . $value . '\'';
				return $this;
			}
		}
	}

	public function order($order){
		self::$order = 'ORDER BY ' . $order;
		return $this;
	}

	public function limit($s,$e=''){
		if($e == ''){
			self::$limit = 'LIMIT '  . $s;
		}else{
			self::$limit = 'LIMIT '  . $s . ',' . $e;
		}

		return $this;
	}

	public function find(){
		self::$action = 'SELECT * FROM';
		self::$sql = trim(self::$action . ' ' . self::$table . ' ' . self::$where . ' ' . self::$order . ' ' . self::$limit);
		// var_dump(self::$sql);
		$result = mysqli_fetch_assoc(mysqli_query($this->conn,self::$sql));
		return $result;
	}

	public function select(){
		self::$action = 'SELECT * FROM';
		self::$sql = trim(self::$action . ' ' . self::$table . ' ' . self::$where . ' ' . self::$order . ' ' . self::$limit);
		// var_dump(self::$sql);
		$result = mysqli_query($this->conn,self::$sql);
		while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return $rows;
	}

	public function update($updatearr){
		$i = 0;
		$set = null;
		foreach($updatearr as $key => $value){
			$i++;
			if($i != count($updatearr)){
				$set .= '`' . $key . '` = ' . '\'' . $value . '\',';
			}else{
				$set .= '`' . $key . '` = ' . '\'' . $value . '\'';
			}
		}
		self::$action = 'UPDATE ' .self::$table. ' SET ' . $set;
		self::$sql = trim(self::$action . ' ' . self::$where . ' ' . self::$limit);
		// var_dump(self::$sql);
		mysqli_query($this->conn,self::$sql);
		$result = mysqli_affected_rows($this->conn);
		return $result;
	}

	public function delete(){
		self::$action = 'DELETE FROM';
		self::$sql = trim(self::$action . ' ' . self::$table . ' ' . self::$where . ' ' . self::$limit);
		// var_dump(self::$sql);
		mysqli_query($this->conn,self::$sql);
		$result = mysqli_affected_rows($this->conn);
		return $result;
	}

	public function add($addarr){
		self::$action = 'INSERT INTO';
		$i = 0;
		$addkey = null;
		$addvalue = null;
		foreach($addarr as $key => $value){
			$i++;
			if($i != count($addarr)){
				$addkey .= '`' . $key .'`,';
				$addvalue .= '\'' . $value .'\',';
			}else{
				$addkey .= '`' . $key .'`';
				$addvalue .= '\'' . $value .'\'';
			}
		}
		$addkey = '(' . $addkey .')';
		$addvalue = '(' . $addvalue .')';
		self::$sql = trim(self::$action . ' ' . self::$table . ' ' . $addkey . ' VALUES ' . $addvalue);
		// var_dump(self::$sql);
		mysqli_query($this->conn,self::$sql);
		$result = mysqli_insert_id($this->conn);
		return $result;
	}

	public function countx()
    {
        self::$action = 'SELECT count(*) FROM';
		self::$sql = trim(self::$action . ' ' . self::$table . ' ' . self::$where);
		// var_dump(self::$sql);
		$result = mysqli_fetch_array(mysqli_query($this->conn,self::$sql));
		return $result[0];
    }

	private function varinit(){
		self::$action = null;

    	self::$table = null;

    	self::$where = null;

    	self::$order = null;

    	self::$limit = null;

    	self::$sql = null;
	}

	public function query($sql)
    {
        return mysqli_query($this->conn,$sql);
    }


    public function fetch($sql)
    {
        $result = mysqli_query($this->conn,$sql);
        return mysqli_fetch_assoc($result);
    }

	public function fetchAll($sql)
    {
        $result = mysqli_query($this->conn,$sql);
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return $rows;
    }

    public function count($sql)
    {
        $result = mysqli_query($this->conn,$sql);
        $count = mysqli_fetch_array($result);
        return $count[0];
    }

    public function close()
    {
        return mysqli_close($this->conn);
	}

}