<?php
/**
 *  DevForce.app.php
 *
 * @version   1.0
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Copyright &copy; 2014 Tomoaki Nagahara
 * @package   devforce
 */

/**
 * DevForce
 *
 * @version   1.0
 * @author    Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright Copyright &copy; 2014 Tomoaki Nagahara
 * @package   devforce
 */
class DevForce extends App
{	
	function Dispatch($route=null)
	{
		//	Get login id
		if( $id = $this->model('Login')->GetLoginId() ){
			return parent::Dispatch();
		}
	
		//	Get route
		if(!$route){
			$route = $this->GetRoute();
		//	$this->d($route);
		}
		
		switch( $route['path'] ){
			case '/ajax':
				$this->SetEnv('layout',false);
				$this->SetEnv('mime','text/javascript');
				$json = array();
				$json['status']	 = 'LOGOUT';
				$json['error']	 = 'Does not logged-in.';
				print json_encode($json);
				$this->SetDispatchFlag(true);
				$this->Content();
				die();
				break;
			
			case '/setup':
				break;
				
			case '/login':
				break;
				
			default:
				$route = array();
				$route['path'] = '/login';
				$route['file'] = $this->GetEnv('controller-name');
				$route['args'] = array(null);
		}
		
		return parent::Dispatch($route);
	}
	
	const _CONFIG_ACCOUNT_	 = 'account';
	const _CONFIG_PAGE_		 = 'page';
	private $_config = null;
	
	/**
	 * Application setup
	 * 
	 */
	function Setup()
	{
		//	init application config.
		if(!$this->_config){
			$this->_config = new Config();
		}
		
		//	read application config file.
		foreach($this->model('File')->Get('./config') as $args){
		//	$this->d($args);
			if($args['ext'] !== 'php'){ continue; }
			include('./config/'.$args['name']);
		}
	}
	
	function SetConfig( $key, Config $config )
	{
		$this->_config->$key = $config;
	}
	
	function GetConfig( $key )
	{
		if( isset( $this->_config->$key ) ){
			return $this->_config->$key;
		}else{
			return null;
		}
	}
	
	function GetConfigPage( $page )
	{
		if( isset( $this->_config->{self::_CONFIG_PAGE_}->$page ) ){
			return $this->_config->{self::_CONFIG_PAGE_}->$page;
		}else{
			return null;
		}
	}
	
	function CheckPermitAccount( $config, $name, $role, &$error )
	{
		//	init
		$allow = 0;
		$deny = __LINE__;
		
		if(empty($config)){
			$deny = __LINE__;
		}
		
		$word = array();
		$word = explode(',',str_replace(' ', '', $role));
		$word[] = $name;
		
		//	check permission(allow)
		if( isset($config->allow) ){
			if( trim($config->allow) === '*' ){
				$allow = true;
			}else{
				$allow = false;
				foreach( explode(',', str_replace(' ','',$config->allow)) as $var ){
					if( $io = array_search($var,$word) !== false ){
						$allow = true;
						break;
					}
				}
			}
		}
		
		//	Check permission(deny)
		if( isset($config->deny) ){
			if( trim($config->deny) === '*' ){
				$deny = __LINE__;
			}else{
				$deny = 0;
				foreach( explode(',',$config->deny) as $var ){
					if( $io = array_search($var,$word) !== false ){
						$deny = __LINE__;
						break;
					}
				}
			}
		}
		
		//	Check deny
		if( $deny ){
			$error = "Access is denied. ($deny)";
			return false;
		}
		
		//	Check allow
		if(!$allow ){
			$error = "Access is not allowed. ($allow)";
			return false;
		}
		
		return true;
	}
	
	function CheckPermitColumn( $config, $name, $role, $column, $dml, &$error )
	{
		//	init
		$allow = 1;
		$deny  = 0;
		
		//	
		if(empty($config)){
			$deny = 1;
		}
		
		//	string
		if( is_string($config->column) ){
			if(!preg_match("/ $column,/",' '.trim($config->column).',')){
				$allow = 0;
				$deny  = "$column: ".$config->column;
			}
		}
		
		//	object / array
		if( is_object($config->column) or is_array($config->column) ){			
			foreach($config->column as $column_name => $column_config){
				//	compare column name
				if( $column === $column_name){
					//	name, role permit
					if( isset($column_config->allow) and preg_match("/$name|$role/",$column_config->allow) ){
						$allow = 2;
					}else if( isset($column_config->deny) and preg_match("/$name|$role/",$column_config->deny) ){
						$deny  = 3;
					}else{
						
					}
					//	permit per dml 
					if( isset($column_config->$dml) ){
					//	$this->d($column_config->$dml);
						if( is_bool($column_config->$dml) ){
							$deny = $column_config->$dml ? false: __LINE__;
						}else if( is_string($column_config->$dml) ){
							$deny = preg_match("/$name|$role/",$column_config->$dml) ? false: "$name:$role,".$column_config->$dml;
						}
						
						if( isset($column_config->$dml->allow) ){
							$deny = preg_match("/\*|$name|$role/",$column_config->$dml->allow) ? false: "$name:$role,".$column_config->$dml->allow;
						} 
						if( isset($column_config->$dml->deny) ){
							if(preg_match("/\*|$name|$role/",$column_config->$dml->deny)){
								$deny = "$name:$role,".$column_config->$dml->deny;
							}
						}
					}
					continue;
				}
				//	next
			}
		}else{
			$allow = 3;
		}
		
		//	
		if( $config->pkey === $column or $column === 'timestamp' ){
			$deny = 10;
		}
		
		//	Check deny
		if( $deny ){
			$error = "This column access to denied. ($deny)";
			return false;
		}
		
		//	Check allow
		if(!$allow ){
			$error = "This column access is not allow. ($allow)";
			return false;
		}
		
		return true;
	}
	
	const _MYSQL_SUPER_USER_ = '_MYSQL_SUPER_USER_';
	const _MYSQL_SUPER_USER_PASSWORD_ = '_MYSQL_SUPER_USER_PASSWORD_';
	
	function pdo($name=null)
	{
		//  get pdo object
		$pdo = parent::pdo($name);
		
		//  check connection
		if(!$pdo->isConnect()){
			//	check super user and password 
			if( is_null($_SERVER[self::_MYSQL_SUPER_USER_]) or is_null($_SERVER[self::_MYSQL_SUPER_USER_PASSWORD_]) ){
				$mes = "Does not set super user or super user's password.";
			//	$this->StackError($mes);
			//	$this->d($_SERVER);
				$this->mark(self::_MYSQL_SUPER_USER_.'='.$_SERVER[self::_MYSQL_SUPER_USER_]);
				$this->mark(self::_MYSQL_SUPER_USER_PASSWORD_.'='.$_SERVER[self::_MYSQL_SUPER_USER_PASSWORD_]);
				throw new Exception($mes);
			}
			
			//	init database config
			$config = new Config();
			$config->driver   = 'mysql';
			$config->host     = 'localhost';
			$config->port     = null;
			$config->database = 'test';
			$config->user     = $_SERVER[self::_MYSQL_SUPER_USER_];
			$config->password = $_SERVER[self::_MYSQL_SUPER_USER_PASSWORD_];
			$config->charset  = 'utf8';
			
			//  database connection
			if(!$io = $pdo->Connect($config)){
				
				//  Notice to admin
				$config->myname = get_class($this);
				$config->Caller = $this->GetCallerLine();
			}
		}
		return $pdo;
	}
	
	function GetSelectOfPage( $name, $role, $page, &$error )
	{
		//	page config
		$config = $this->GetConfigPage($page);
		
		//	Init
		$host		 = isset($config->host)     ? $config->host:     null;
		$database	 = isset($config->database) ? $config->database: null;
		$table		 = isset($config->table)    ? $config->table:    null;
		$pkey		 = isset($config->pkey)     ? $config->pkey:     null;
	//	$column		 = isset($config->column)   ? $config->column:   null;
		$order		 = isset($config->order)    ? $config->order:    $pkey;
		$limit		 = isset($config->limit)    ? $config->limit:    null;
	//	$id			 = Toolbox::GetRequest('id');
	//	$column		 = Toolbox::GetRequest('column');
	//	$value		 = Toolbox::GetRequest('value');
		
		//	column
		if( empty($config->column) ){
			$column = null;
		}else if( is_string($config->column) ){
			$column = $config->column;
		}else if( is_object($config->column) or is_array($config->column) ){
			$column = $config->pkey;
			foreach($config->column as $name => $temp){
				if( $name === $config->pkey ){ continue; }
				$column .= ", $name";
			}
			/*
			rtrim($column,',');//does not work
			$column{strlen($column)-1} = '';
			*/
		}
		//$this->mark($column);
		
		//	Init select
		$select = new Config();
		$select->host	 = $host;
		$select->database= $database;
		$select->table	 = $table;
		$select->column	 = $column;
		$select->order	 = $order;
		$select->limit	 = $limit;
				
		//	Memcache measures
		$select->name = $name;
		$select->role = $role;
		
		return $select;
	}
	
	function GetUpdateOfPage( $name, $role, $page, &$error )
	{
		//	page config
		$config = $this->GetConfigPage($page);
		
		//	init
		$host		 = isset($config->host)     ? $config->host:     null;
		$database	 = isset($config->database) ? $config->database: null;
		$table		 = isset($config->table)    ? $config->table:    null;
		$pkey		 = isset($config->pkey)     ? $config->pkey:     null;
		$id			 = Toolbox::GetRequest('id');
		$column		 = Toolbox::GetRequest('column');
		$value		 = Toolbox::GetRequest('value');
		$limit		 = 1;
		
		//	Check
		foreach( array('host'=>$host,'database'=>$database,'table'=>$table,'pkey'=>$pkey,'id'=>$id,'column'=>$column,'value'=>$value) as $key => $var ){
			if( is_null($var) ){
				$error = "$key is null.";
				return false;
			}
		}
		
		//	create
		$update = new Config();
		$update->host	 = $host;
		$update->database= $database;
		$update->table	 = $table;
		$update->limit	 = $limit;
		$update->where->{$pkey} = $id;
		$update->set->$column = $value;
		
		return $update;
	}
	
	function GetPageList()
	{
		foreach($this->_config->{self::_CONFIG_PAGE_} as $name => $config ){
			$label = isset($config->label) ? $config->label: $name;
			$href = $this->ConvertURL("app:/page:$name");
			$page['label'] = $label;
			$page['href']  = $href;
			$pages[] = $page;
		}
		
		return $pages;
	}
	
	function GetColumnNameList( $record, $page )
	{
		//	page config
		$config = $this->GetConfigPage($page);
		
		if(empty($config->column)){
			foreach($record[0] as $key => $var){
				$list[] = $key;
			}
		}else if(is_string($config->column)){
			$list = explode(',',trim($config->column));
		}else{
			$list[] = $config->pkey;
			foreach($config->column as $key => $var){
				if( $config->pkey === $key ){ continue; }
				$list[] = isset($var->label) ? $var->label: $key;
			}
		}
		
		return $list;
	}
	
	function GetPageDescription( $page )
	{
		//	page config
		$config = $this->GetConfigPage($page);
		return isset($config->description) ? $config->description: null;
	}
	
	function GetColumnStruct( $page )
	{
		//	init
		list($name,$role) = explode(':',$this->model('Login')->GetLoginID());
		//	page config
		$config = $this->GetConfigPage($page);
		$struct = $this->pdo()->GetTableStruct($config->table,$config->database);
		
		if( empty($config->column) ){
			//	was no.
			$result = $struct;
		}else if( is_string($config->column) ){
			//	only column name.
			$list = explode(',',trim($config->column));
			//	Added a pkey at forcibly.
			$result[$config->pkey] = $struct[$config->pkey];
			//	is choose the column.
			foreach($list as $column_name){
				$column_name = trim($column_name);
				$result[$column_name] = $struct[$column_name];
			}
		}else if( is_object($config->column) or is_array($config->column) ){
			//	was detail setting.
			//	Added a pkey at forcibly.
			$result[$config->pkey] = $struct[$config->pkey];
			//	is choose the column.
			foreach($config->column as $column_name => $column){
				//	copy a struct.
				$result[$column_name] = $struct[$column_name];
				//	if parameter is hidden.
				if(!empty($column->hidden) ){
					//	this column to hidden.
					$result[$column_name]['hidden'] = true;
				}
				//	update have been rejected.
				$result[$column_name]['disable'] = $this->CheckPermitColumn($config, $name, $role, $column_name, 'update', $error) ? false: true;
				//	label
				if(!empty($column->label)){
					$result[$column_name]['label'] = $column->label;
				}else{
					$result[$column_name]['label'] = $column_name;
				}
			}
		}
		
	//	$this->d($result['timestamp']);
		
		return $result;
	}
	
	function isLogin()
	{
		return $this->model('Login')->GetLoginID() ? true: false;
	}
	
	function GetLoginName()
	{
		if(!$id = $this->model('Login')->GetLoginID()){
			return null;
		}
		list($name,$role) = explode(':',$id);
		return $name;
	}
}
