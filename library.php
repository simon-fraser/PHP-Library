<?php
/*
 * PHP Library -- A simplified toolkit for PHP
 *
 * @ version 1.3.1 Beta(β)
 * @ author Simon Fraser <http://simonf.co.uk>
 * @ acknowledgement php.net community, kirby toolkit
 * @ copyright Copyright (c) 2012 Simon Fraser
 * @ license MIT License <http://www.opensource.org/licenses/mit-license.php>
*/

conf::set('charset', 'utf-8');
conf::set('AllowSanetize', '<a><b><br><em><h1><h2><h3><h4><h5><h6><i><img><li><ol><p><strong><u><ul>');
error_reporting(E_ALL);
/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

/*
 * Array
 * Set of array methods
*/
class ar {


	/*
	 * Gets an element of an array by key
	 * @param  (array) $array - The source
	 * @param  (string|array) $key - Key or Keys to search for
	 * @return (array)
	*/
	static function get($array, $key) {
		 $result = array();
		 if (is_array($key)) {
			  foreach($key as $k) { $result[$k] = $array[$k]; }
		 } else {
			  $result = (isset($array[$key]))? $array[$key] : false;
		 }
		 return $result;
	}


	/*
	 * Insert a new element into array
	 * @param  (array) $array - The source
	 * @param  (string) $element - The element to be inserted
	 * @param  (int) $position - Optional position of new element, default is end
	 * @return (array) - The new array
	*/
	static function set($array, $element=null, $position=null) {
		if (empty($position)) {
			array_push($array, $element);
			return $array;
		} else {
			return array_merge( array_slice($array,0,(int)$position), (array)$element, array_slice($array, (int)$position) );
		}
	}


	/*
	 * Removes an element from an array
	 * @param  (array) $array - The source
	 * @param  (string) $search - The value or key to look for
	 * @param  (boolean) $key - To search for a key or value, default true
	 * @return (array)
	*/
	static function delete($array, $search, $key=true) {
		if($key) {
			unset($array[$search]);
		} else {

		for ($i=0; $i<sizeof($array) ; $i++) {
			$index = array_search($search, $array);
			if ($index!==false) {
				unset($array[$index]);
			}
		}
		}
		return $array;
	}


	/*
	 * Print out array on screen
	 * @param (array) $array - The source
	 * @echo  (string) - Will echo result on screen
	*/
	static function show($array) {
		$output = '<pre>';
		$output .= htmlspecialchars(@print_r($array, true));
		$output .= '</pre>';
		echo $output;
	}


	/*
	 * Shuffle an array
	 * @param  (array) $array - The source
	 * @return (array) - Shuffled array
	*/
	static function shuffle($array) {
		$keys = array_keys($array);
		shuffle($keys);
		return array_merge(array_flip($keys), $array);
	}


	/*
	 * Flatten an array to string
	 * @param  (array) $array - The source
	 * @param  (string) $pre_separator
	*/
	static function flatten($array,$pre_separator='',$post_separator=',') {
		$output = '';
		foreach ($array as $a) {
			$output .= $pre_separator.$a.$post_separator;
		}
		return $output;
	}


	/*
	 * Search an array
	 * @param  (array) $array - The source
	 * @param  (string) $search - Searching for
	 * @param  (boolean) $key - Return the key space or boolean yes / no
	 * @return (array|boolean)
	*/
	static function search($array, $search, $key=false) {
		$search = preg_grep('/'.preg_quote($search).'/i', $array);
		if ($key) return $search;
		return (empty($search))? false : true;
	}


} /* Array Methods */


/*
 * Cookies (Monster)
 * Set of methods to help with cookies
*/
class cm {


	/*
	 * Set and make Cookies
	 * @param  (string) $key - Cookie key value
	 * @param  (string) $value - Value to store into cookie
	 * @param  (int) $expires - Expiration date from time created (default 24 hours)
	 * @param  (string) $domain - Domain for cookie
	 * @return (boolean)
	*/
	static function set($key, $value=null, $expires=31536000, $domain='/') {
   	if(empty($key)) return false;
   	return setcookie($key, $value, time()+$expires, $domain);
	}


	/*
	* Get value from cookie based off key
	* @param  (string) $key - Cookie key to target
	* @return (boolean)
	*/
	static function get($key) {
		return ar::get($_COOKIE, $key);
	}


	/*
	* Remove cookie based off key
	* @param  (string) $key - Cookie key value
	* @param  (string) $domain - Domain for cookie
	* @return (boolean)
	*/
	static function remove($key, $domain='/') {
		$_COOKIE[$key] = false;
		return @setcookie($key, false, time()-86400, $domain);
	}


}


/*
 * Config Values
 * Configuration values, removes the need of GLOBALS
*/
class conf {


	/*
	 * The static config array
	 * @var array
	*/
	private static $config = array();


	/*
	 * Set a configuration value
	 * @param (string) $key - The reference key
	 * @param (string) $value - The configuration value
	*/
	static function set($key, $value=null) {
		self::$config[$key] = $value;
	}


	/*
	 * Retrieve a config value
	 * @param (string) $key - config key to get
	 * @return (string)
	*/
	static function get($key) {
		return ar::get(self::$config,$key);
	}


} /* Config Methods */


/*
 * Content & Cache methods
 * Content zone to store pieces of data
*/
class content {


	/*
	 * The static content array
	 * @var array
	*/
	private static $contents = array();


	/*
	 * Save ntnt value
	 * @param (string) $key - The reference key
	 * @param (string) $value - The value
	*/
	static function set($key, $value=null) {
		self::$contents[$key] = $value;
	}


	/*
	 * Retrieve a content value
	 * @param (string) $key - config key to get
	 * @return (string)
	*/
	static function get($key) {
		return str::sanetize(ar::get(self::$contents,$key));
	}


} /* Content & cache methods */


/*
 * Date & Time Class
 * Methods and formatting
 */
class timedate {


	/*
	 * Return date / time formated string
	 * @param  (string) $format - format parameter string like http://php.net/function.date.php
	 * @param  (string) $datetime - string to convert to date/time
	 * @return (string) - formated datetime string
	*/
	static function format($format="D jS M Y G:i:s", $datetime=null) {
		if (empty($datetime)) $datetime = time();
		if (preg_match('~^[1-9][0-9]*$~', $datetime)==0) {
			$date = strtotime($datetime);
		} else {
			$date = $datetime;
		}
		return date($format, $date);
	}


	/*
	 * Check for a valid Datetime string
	 * @param  (string) $datetime - string to check
	 * @return (boolean) - string valid or not
	*/
	static function valid($string) {
		if ($string=="" || $string=="0000-00-00" || $string=="0000-00-00 00:00:00") return false;
		return true;
	}


} /* Datetime methods */


/*
 * Database Controls
 * http://www.php.net/manual/en/mysqli.query.php
 *
 * Uses four configurations to run and should be set
 * db.user, db.password, db.name, (db.host) optional
 * Ex : conf::set('db.x','value');
 */
class db extends mysqli {


	/*
	 * Connection instance
	*/
	private static $instance;


	/*
	 * Connection status check
	 * @return (object|boolean) - Will return connection object or false
	*/
	public function connection() {
		return (is_resource(self::$instance))? self::$instance : false;
	}


	/*
	 * Connect function
	 * @return (object) - Connection object
	*/
	public function connect() {
		$connection = self::connection();
		$host = (conf::get('db.host')!='')? conf::get('db.host') : 'localhost';
		$user = (conf::get('db.user')!='')? conf::get('db.user') : 'root';
		$password = (conf::get('db.password')!='')? conf::get('db.password') : '';
		$database = (conf::get('db.name')!='')? conf::get('db.name') : '';
		@$connection = (!$connection)? new mysqli($host, $user, $password, $database) : $connection;
		if(!$connection) self::error('database connection failed');
		self::$instance = $connection;
		return $connection;
	}


	/*
	 * Database Disconnect
	*/
	public function disconnect() {
		$instance = self::connect();
		if(!$instance) return $instance;
		self::$instance->close();
	}


	/*
	 * All is lost error function
	 * @param (string) $msg - Error to show
	 * @return (die-string)
	*/
	public function error($msg) {
		die('Database error '.$msg);
	}


	/*
	 * Run a query
	 * @param  (string) $query - Query to be ran
	 * @param  (boolean) $object - To actually return as total object
	 * @return (array|object) - string on fault or DB Object
	*/
	public function query($query,$object=false) {
		$instance = self::connect();
		if(!$instance) return $instance;

		@$result = $instance->query($query) or self::error($instance->error);
		if ($object) return $result;

		if ($result->num_rows===0) {
			return false;
		} else {
			while ($row = $result->fetch_object()) {
				$return[] = $row;
			}
			return $return;
		}
		$result->close();
		self::disconnect();
	}


	/*
	 * Datebase read method
	 * @param  (string) $table - Query table name
	 * @param  (string) $after - Fields to return results from, default all
	 * @param  (string) $where - Where condition
	 * @param  (string) $order - Order By condition
	 * @param  (string) $limit - Limit condition
	 * @param  (boolean) $echo - Show constructed query as echo debug output
	 * @return (array-object) Query results
	*/
	public function read($table, $after="*", $where=null, $order=null, $limit=null, $echo=false) {
		$ourquery = "SELECT ".$after." FROM ".$table." ".$where." ".$order." ".$limit;
		if($echo == true) echo $ourquery;
		return self::query($ourquery);
	}


	/*
	 * Datebase single row & column read method
	 * @param  (string) $table - Query table name
	 * @param  (string) $after - Field to read
	 * @param  (string) $where - Where condition
	 * @param  (string) $order - Order By condition
	 * @param  (boolean) $echo - Show constructed query as echo debug output
	 * @return (array-object) Query results
	*/
	public function single($table, $after=null, $where=null, $order=null, $echo=false) {
		if (empty($after)) return false;
		$ourquery = "SELECT ".$after." FROM ".$table." ".$where." ".$order." LIMIT 1";
		if($echo == true) echo $ourquery;

		$result = self::query($ourquery,true);
		if ($result->num_rows===0) {
			return false;
		} else {
			while ($row = $result->fetch_object()) {
				return str::unquote($row->$after);
			}
		}
		$result->close();
		self::disconnect();
	}


	/*
	 * Inner Join read method
	 * @param  (string) $table_a - Query table name A
	 * @param  (string) $table_b - Query table name B
	 * @param  (string) $on - On join SQL command
	 * @param  (string) $after - Fields to return results from, default all
	 * @param  (string) $where - Where condition
	 * @param  (string) $order - Order By condition
	 * @param  (string) $limit - Limit condition
	 * @param  (boolean) $echo - Show constructed query as echo debug output
	 * @return (array-object) Query results
	 *
	*/
	public function join($table_a, $table_b, $on, $after="*", $where=null, $order=null, $limit=null, $echo=false) {
		$ourquery = "SELECT ".$after." FROM ".$table_a." INNER JOIN ".$table_b." ON ".$on." ".$where." ".$order." ".$limit;
		if($echo == true) echo $ourquery;
		return self::query($ourquery);
	}


	/*
	 * Insert row into database table
	 * @param  (string) $table - Query table name
	 * @param  (array) $labels - Array of field labels
	 * @param  (multidimension array) $values - Array of row values
	 * @param  (boolean) $echo - Show constructed query as echo debug output
	 * @return (int) - Returns ID of new (last) row
	*/
	public function insert($table, $labels, $values, $echo=false) {
		$ourlabels='';
		$ourvalues='';
		if (is_array($labels)) {
			// foreach ($labels as $label) {
			// 	$ourlabels .= ($label).",";
			// }
			// $ourlabels = substr($ourlabels,0,-1);
			$ourlabels = implode(',', $labels);
		}
		if (is_array($values)) {
			foreach ($values as $value) {
				$ourvalues .= "'".(str::escape($value))."',";
			}
			$ourvalues = substr($ourvalues,0,-1);
		}
		$ourquery = "INSERT INTO ".$table." (".$ourlabels.") VALUES (".$ourvalues.")";
		if($echo == true) echo $ourquery;
		self::query($ourquery,true);
		return self::$instance->insert_id;
		$result->close();
		self::disconnect();
	}


	/*
	 * Count of rows
	 * @param  (string) $table - Query table name
	 * @param  (string) $after - Fields to return results from, default all
	 * @param  (string) $where - Where condition
	 * @param  (string) $order - Order By condition
	 * @param  (string) $limit - Limit condition
	 * @param  (boolean) $echo - Show constructed query as echo debug output
	 * @return (int) - Returns integer of rows
	*/
	public function count($table, $after="*", $where=null, $order=null, $limit=null, $echo=false){
		$ourquery = "SELECT ".$after." FROM ".$table." ".$where." ".$order." ".$limit;
		if($echo == true) echo $ourquery;
		self::query($ourquery,true);
		return self::$instance->affected_rows;
		$result->close();
		self::disconnect();
	}


	/*
	 * Update query command
	 * @param  (string) $table - Query table name
	 * @param  (string) $set - Edit criteria (e.g) col='1'
	 * @param  (string) $criteria - Where condition
	 * @param  (boolean) $echo - Show constructed query as echo debug output
	 * @return (int) - Returns number of affected rows
	*/
	public function update($table, $set, $criteria, $echo=false) {
		$ourquery = "UPDATE ".$table." SET ".str::escape($set)." ".$criteria;
		if($echo == true) echo $ourquery;
		self::query($ourquery,true);
		return self::$instance->affected_rows;
		$result->close();
		self::disconnect();
	}


	/*
	 * Delete row from table
	 * @param  (string) $table - Query table name
	 * @param  (string) $where - Where condition
	 * @param  (boolean) $echo - Show constructed query as echo debug output
	 * @return (int) - Returns number of affected rows
	*/
	public function delete($table, $where, $echo=false){
		$ourquery = "DELETE FROM ".$table." ".str::escape($where);
		if($echo == true) echo $ourquery;
		self::query($ourquery,true);
		return self::$instance->affected_rows;
		$result->close();
		self::disconnect();
	}


	/*
	 * Return list of fields from database and field information
	 * @param  (string) $table - Query table name
	 * @return (array) - Array of field information
	*/
	public function fields($table) {
		$ourquery = "SELECT * FROM ".$table." LIMIT 1";
		$result = self::query($ourquery,true);
		return $result->fetch_fields();
		$result->close();
		self::disconnect();
	}


} /* Database Methods */


/*
 * Directory Methods
 * Easy to create/edit/delete directories
 */
class dir {


	/*
	 * Crawl and return information about a directory
	 * @param  (string) $dir - Directory to crawl
	 * @return (array) - Associative array about directory
	*/
	static function crawl($dir) {
		if(!is_dir($dir)) return array();
		$skip = array('.', '..', '.DS_Store');
		$files    = array_diff(scandir($dir),$skip);
		$modified = str::datetime("d/m/Y-G:i:s", filemtime($dir));
		$data = array(
			'name'     => basename($dir),
			'modified' => $modified,
			'files'    => array(),
			'children' => array()
		);

	 foreach($files as $file) {
		if(is_dir($dir.'/'.$file)) {
		  $data['children'][] = $file;
		} else {
		  $data['files'][] = $file;
		}
	 }

	 return $data;
	}


	/*
	 * Make new directory, will check for existing directory first
	 * @param  (string) $dir - Directory name/location to create
	 * @return (boolean)
	 */
	static function make($dir) {
		if(is_dir($dir)) return true;
		if(!@mkdir($dir, 0755)) return false;
		@chmod($dir, 0755);
		return true;
	}


	/*
	 * Move / Rename directory
	 * @param  (string) $old - current directory name
	 * @param  (string) $new - new directory name / location
	 * @return (boolean)
	*/
	static function move($old, $new) {
		if(!is_dir($old)) return false;
		return (@rename($old, $new) && is_dir($new)) ? true : false;
	}


	/*
	 * Remove a directory or simply empty it
	 * @param  (string) $dir - Directory to destroy
	 * @param  (boolean) $empty - true = empty and leave directory
	 * @return (boolean)
	*/
	static function remove($dir, $empty=false) {
		if(!is_dir($dir)) return false;

		$skip  = array('.', '..');
		$open = @opendir($dir);
		if(!$open) return false;

		while($file = @readdir($open)) {
			if (!in_array($file, $skip)) {
				if(is_dir($dir.'/'.$file)) {
				  self::remove($dir.'/'.$file);
				} else {
				  @unlink($dir.'/'.$file);
				}
			}
		}

		@closedir($open);
		if(!$empty) {
			return @rmdir($dir);
		} else {
			return true;
		}
	}


} /* Directory Methods */


/*
 * Regular Expression Methods
*/
class ex {

	/*
	 * Filter out expression
	 * @param  (mixed) $patern - patern to replace
	 * @param  (mixed) $subject - subject to run filter on
	 * @param  (mixed) $replace - replacement
	 * @return (array)
	*/
	static function filter($pattern,$subject,$replace) {
		return preg_filter($pattern, $replace, $subject);
	}


	/*
	 * Match expression
	 * @param  (mixed) $patern - patern to replace
	 * @param  (mixed) $subject - subject to run match on
	 * @return (array) - array of matches & offset location
	*/
	static function match($pattern,$subject) {
		preg_match_all($pattern, $subject, $matches);
		return $matches;
	}


	/*
	 * Replace expression
	 * @param  (mixed) $patern - patern to replace
	 * @param  (mixed) $subject - subject to run replacement on
	 * @param  (mixed) $replace - replacements to run replacements on
	 * @return (string) - Subject string with replacements made
	*/
	static function replace($pattern,$subject,$replace) {
		$match = preg_replace($pattern, $replace, $subject);
		return $match;
	}


	/*
	 * Split expression
	 * @param  (string) $patern - patern to replace
	 * @param  (string) $subject - subject to run split on
	 * @return (array) - array of matches & offset location
	*/
	static function split($pattern,$subject) {
		$match = preg_split($pattern, $subject);
		return $match;
	}


} /* Regular Expression */


/*
 * File System
 * Set of file methods
*/
class fs {


	/*
	 * Download Push
	 * @param  (string) $file - File to push download
	 * @return (file)
	 * http://php.net/function.readfile.php
	*/
	static function download($file) {
		if (!file_exists($file)) return false;
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.self::filename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: '.self::size($file,false));
		ob_clean();
		flush();
		readfile($file);
		exit;
	}


	/*
	 * Get the file extension
	 * @param  (string) $file - The file
	 * @return (boolean|string) returns extension, false on failure
	*/
	static function extension($file) {
		if (!file_exists($file)) return false;
		$ext = pathinfo($file);
		return $ext['extension'];
	}


	/*
	 * Get the filename
	 * @param  (string) $file - The file
	 * @return (boolean|string) returns filename, false on failure
	*/
	static function filename($file) {
		if (!file_exists($file)) return false;
		return basename($file);
	}


	static function info($file) {
		if (!file_exists($file)) return false;
		return stat($file);
	}


	/*
	 * Get filesize
	 * @param  (string) $file - The file
	 * @param  (boolean) $format - To format with size unit
	 * @return (string)
	 * http://php.net/function.filesize.php
	*/
	static function size($file,$format=true) {
		if (!file_exists($file)) return false;
		if (!$format) return filesize($file);
		return self::stringsize(filesize($file));
	}


	/*
	 * Get filesize
	 * @param  (string) $size - Size string
	 * @return (string)
	 * http://php.net/function.filesize.php
	*/
	static function stringsize($size) {
		$units = array(' b', ' kb', ' mb', ' gb', ' tb');
		for ($i=0; $size>=1024 && $i<4; $i++) $size/=1024;
		return round($size, 2).$units[$i];
	}


	/*
	 * Replacement for includes
	 * @param (string) $file - The file to include (require)
	*/
	static function load($file) {
		if (file_exists($file)) require($file);
	}


	/*
	 * Read file & return contents
	 * @param  (string) $file - File location
	 * @return (string) - File contents
	*/
	static function read($file) {
		if (!file_exists($file)) return false;
		$content = @file_get_contents($file);
		return $content;
	}


	/*
	 * Write to a file, will create if does not exist
	 * @param  (string) $file - The file to write
	 * @param  (string) $content - The file content to write
	 * @param  (string) $append - Wether to append to the end of the file
	 * @return (boolean)
	*/
	static function write($file,$content,$append=false) {
		$mode = ($append)? FILE_APPEND : false;
		$write = @file_put_contents($file, $content, $mode);
		@chmod($file, 0666);
		return $write;
	}


	/*
	 * Simply Append data to file
	 * @param  (string) $file - The file to write
	 * @param  (string) $content - The file content to write
	 * @return (boolean)
	*/
	static function append ($file,$content) {
		return self::write($file,$content,true);
	}


	/*
	 * Move file to new location
	 * @param  (string) $old - Current filename & location
	 * @param  (string) $new - New filename & location
	 * @return (boolean)
	*/
	static function move($old, $new) {
		if(!file_exists($old)) return false;
		return (@rename($old,$new) && file_exists($new))? true : false;
	}


	/*
	 * Remove File from system
	 * @param  (string) $file - The file
	 * @return (boolean)
	*/
	static function remove($file) {
		if (!file_exists($file)) return false;
		return (is_file($file) && file_exists($file))? @unlink($file) : false;
	}


} /* File Methods */


/*
 * FTP File transfers
*/
class ftp {


	/*
	 * Connection instance
	*/
	private static $instance;


	/*
	 * Connection status check
	 * @return (object|boolean) - Will return connection object or false
	*/
	public function connection() {
		return (is_resource(self::$instance))? self::$instance : false;
	}


	/*
	 * Connection Method
	*/
	public function connect() {
		$host = (conf::get('ftp.host')!='')? conf::get('ftp.host') : 'localhost';
		$user = (conf::get('ftp.user')!='')? conf::get('ftp.user') : 'root';
		$password = (conf::get('ftp.password')!='')? conf::get('ftp.password') : '';

		$connection = ftp_connect($host);
		@$login = ftp_login($connection, $user, $password);

		if (!$login) return false;
		self::$instance = $connection;
		return $connection;
	}


	/*
	 * Close Method
	*/
	public function close() {
		@ftp_close(self::instance());
	}


	/*
	 * Download File
	 * @param  (string) $local - Local File to create
	 * @param  (string) $remote - Remote file to target and download
	 * @return (boolean)
	*/
	public function get($local,$remote) {
		$instance = self::connection();
		if(!$instance) return $instance;

		$u = ftp_get($instance, $local, $remote, FTP_BINARY);
		if($u) return true;
		if(!$u) return false;
	}


	/*
	 * Listing Method
	 * @param  (string) $dir - Directory
	 * @return (array)
	 * @return (boolean)
	*/
	public function listing($dir='.') {
		$instance = self::connection();
		if(!$instance) return $instance;
		$ls = ftp_rawlist($instance, $dir);
		foreach ($ls as $list) {
			$info = preg_split("/[\s]+/", $list, 9);
         $point[] = array(
             'name'   	  => $info[8],
             'size'   	  => fs::stringsize($info[4]),
             'directory'  => $info[0]{0} == 'd'
         );
		}
		return @$point;
	}


	/*
	 * Make Directory
	 * @param  (string) $dir - Directory name to make
	 * @return (boolean)
	*/
	public function makedir($dir) {
		if(empty($dir)) return false;
		$instance = self::connection();
		if(!$instance) return $instance;

		$u = ftp_mkdir($instance, $dir);
		if($u) return true;
		if(!$u) return false;
	}


	/*
	 * Remove Directory
	 * @param  (string) $dir - Directory name to make
	 * @return (boolean)
	*/
	public function removedir($dir) {
		if(empty($dir)) return false;
		$instance = self::connection();
		if(!$instance) return $instance;

		$u = ftp_rmdir($instance, $dir);
		if($u) return true;
		if(!$u) return false;
	}


	/*
	 * Upload file via FTP
	 * @param  (string) $file - File to upload
	 * @param  (string) $remote - File name on remote
	 * @return (boolean)
	*/
	public function upload($file,$remote) {
		$instance = self::connection();
		if(!$instance) return $instance;

		$u = ftp_put($instance, $remote, $file, FTP_ASCII);
		if($u) return true;
		if(!$u) return false;
	}


	/*
	 * Delete file
	 * @param  (string) $file - File to upload
	 * @return (boolean)
	*/
	public function delete($file) {
		$instance = self::connection();
		if(!$instance) return $instance;

		$u = ftp_delete($instance, $file);
		if($u) return true;
		if(!$u) return false;
	}


} /* FTP Methods */


/*
 * HTML
 * Set of HTML creating Methods
*/
class html {


	/*
	 * Create a stylesheet link
	 * @param  (string) $file - File location of stylesheet
	 * @return (string)
	*/
	static function css($file) {
		return '<link rel="stylesheet" href="'.$file.'" />';
	}


	/*
	 * Creates a encoded email hyperlink
	 * @param  (string) $email - The email address
	 * @param  (string) $text - optional text to use as hyperlink, default use email
	 * @param  (string) $title - optional HTML title tag
	 * @param  (string) $class - optional HTML class
	 * @return (string) - A formatted mailto hyperlink
	*/
	static function email($email, $text=false, $class=false, $title=false) {
		$string = (empty($text))? $email : $text;
		$email  = str::ascii($email);

		$class = (!empty($class))? ' class="'.$class.'" ':'';
		$title = (!empty($title))? ' title="'.$title.'" ':' ';
		return '<a'.$title.$class.'href="mailto:'.$email.'">'.str::ascii($string).'</a>';
	}


	/*
	 * Create a javascript link
	 * @param  (string) $file - File location of script
	 * @return (string)
	*/
	static function js($file) {
		return '<script src="'.$file.'"></script>';
	}


	/*
	 * Creates a link
	 * @param  (string) $link - The URL
	 * @param  (string) $text - Text for the link tag, If false the URL will be used
	 * @param  (string) $title - optional HTML title tag
	 * @param  (string) $class - optional HTML class
	 * @return (string) - A formatted hyperlink
	*/
	static function link($link, $text=false, $class=false, $target=false, $title=false) {
		$text = ($text)?$text:$link;
		$class = (!empty($class))? ' class="'.$class.'" ':'';
		$title = (!empty($title))? ' title="'.$title.'" ':'';
		$target = (!empty($target))? ' target="_'.str::lower($target).'" ':'';
		return '<a '.$title.''.$class.'href="'.$link.'"'.$target.'>'.str::sanetize($text).'</a>';
	}


	/*
	 * Generate a Table
	 * @param  (array) $headings - An array of table headings
	 * @param  (multidimension array) $rows - Multidimension Array of rows
	 * @param  (string) $class - optional class string
	 * @param  (multidimension array) $tfoot - Multidimension Array of rows for tfoot
	 */
	static function table($headings=null, $rows, $class='', $tfoot=null) {

		$html = '<table border="0" cellpadding="0" cellspacing="0" class="'.$class.'">'."\n";

		   //Table Headings
		   if ($headings!=null) {
			   $html .= '<thead>'."\n";
			   	$html .= '<tr>'."\n";
			   		//Loop Headings
			   		foreach ($headings as $thead) {
			   			$html .= ' <th>'.$thead.'</th> ';
			   		}
			   	$html .= '</tr>'."\n";
			   $html .= '</thead>'."\n";
			} // !Headings

			//Table Foot
			if ($tfoot!=null) {
			   $html .= '<tfoot>'."\n";
			   	if (is_array($tfoot)) {
			   		if (is_array($tfoot[0])){ //Multi array
				   		foreach ($tfoot as $cells) {
				   			$html .= '<tr>';
				   			foreach ($cells as $key => $value) {
				   				$html .= ' <td>'.$value.'</td> ';
				   			}
				   			$html .= '</tr>'."\n";
				   		}
			   		} else { //Single array
			   			$html .= '<tr>';
			   			foreach ($tfoot as $cells) {
			   				$html .= ' <td>'.$cells.'</td> ';
			   			}
			   			$html .= '</tr>'."\n";
			   		}
			   	}//tfoot array
			   $html .= '</tfoot>'."\n";
			} // !Foot

			//Table Body
			if (is_array($rows)) {
		   $html .= '<tbody>'."\n";
		   	if (is_array($rows[0])) {
		   	foreach ($rows as $cells) {
		   		$html .= '<tr>';
		   		foreach ($cells as $key => $value) {
		   			$html .= ' <td>'.$value.'</td> ';
		   		}
		   		$html .= '</tr>'."\n";
		   	}
		   	} else {
		   		$html .= '<tr>';
		   		foreach ($rows as $cells) {
		   			$html .= ' <td>'.$cells.'</td> ';
		   		}
		   		$html .= '</tr>'."\n";
		   	}
		   $html .= '</tbody>'."\n";
			}

	 	$html .='</table>';

	 	return $html;
	}


} /* HTML Methods */


/*
 * JSON
*/
class json {


	/*
	 * Encode to json
	 * @param  (mixed) $value - Value to encode to json
	 * @return (string)
	*/
	static function create($value) {
		return json_encode($value);
	}


	/*
	 * Decode json object
	 * @param  (string) $js - Our JSON string
	 * @return (mixed)
	*/
	static function decode($js) {
		return json_decode($js);
	}


} /* Json Methods */


/*
 * Password Helpful Methods
*/
class password {


	/*
	 * Spoken Elements Array
	 * Our easly remembered words
	*/
	private static $memorables = Array('able','about','above','accept','accident','accuse','across','act','activist','actor','add','administration','admit','advise','affect','afraid','after','again','against','age','agency','aggression','ago','agree','agriculture','aid','aim','air','airplane','airport','alive','all','ally','almost','alone','along','already','also','although','always','ambassador','amend','ammunition','among','amount','anarchy','ancient','anger','animal','anniversary','announce','another','answer','any','apologize','appeal','appear','appoint','approve','area','argue','arms','army','around','arrest','arrive','art','artillery','as','ash','ask','assist','astronaut','asylum','atmosphere','atom','attack','attempt','attend','automobile','autumn','awake','award','away',
		'back','bad','balance','ball','balloon','ballot','ban','bank','bar','base','battle','beach','beat','beauty','because','become','bed','beg','begin','behind','believe','bell','belong','below','best','betray','better','between','big','bill','bird','bite','bitter','black','blame','blanket','bleed','blind','block','blood','blow','blue','boat','body','boil','bomb','bone','book','border','born','borrow','both','bottle','bottom','box','boy','brain','brave','bread','break','breathe','bridge','brief','bright','bring','broadcast','brother','brown','build','bullet','burn','burst','bury','bus','business','busy',
		'cabinet','call','calm','camera','campaign','can','cancel','cancer','candidate','cannon','capital','capture','car','care','careful','carry','case','cat','catch','cattle','cause','ceasefire','celebrate','cell','center','century','ceremony','chairman','champion','chance','change','charge','chase','cheat','check','cheer','chemicals','chieg','child','choose','church','circle','citizen','city','civil','civilian','clash','clean','clear','climb','clock','close','cloth','clothes','cloud','coal','coalition','coast','coffee','cold','collect','colony','color','comedy','command','comment','committee','common','communicate','company','compete','complete','compromise','computer','concern','condemn','condition','conference','confirm','conflict','congratulate','congress','connect','conservative','consider','contain','continent','continue','control','convention','cook','cool','cooperate','copy','correct','cost','costitution','cotton','count','country','court','cover','cow','coward','crash','create','creature','credit','crew','crime','criminal','crisis','criticize','crops','cross','crowd','cruel','crush','cry','culture','cure','current','custom','cut',
		'damage','dance','danger','dark','date','daughter','day','deal','debate','decide','declare','deep','defeat','defend','deficit','degree','delay','delegate','demand','demonstrate','denounce','deny','depend','deplore','deploy','describe','desert','design','desire','destroy','details','develop','device','different','difficult','dig','dinner','diplomat','direct','direction','dirty','disappear','disarm','discover','discuss','disease','dismiss','dispute','dissident','distance','distant','dive','divide','doctor','document','dollar','door','down','draft','dream','drink','drive','drown','dry','during','dust','duty',
		'each','early','earn','earth','earthquake','ease','east','easy','eat','economy','edge','educate','effect','effort','egg','either','elect','electricity','electron','element','embassy','emergency','emotion','employ','empty','end','enemy','energy','enforce','engine','engineer','enjoy','enough','enter','eqipment','equal','escape','especially','establish','even','event','ever','every','evidence','evil','evironment','exact','examine','example','excellent','except','exchange','excite','excuse','execute','exile','exist','expand','expect','expel','experiment','expert','explain','explode','explore','export','express','extend','extra','extreme',
		'face','fact','factory','fail','fair','fall','family','famous','fanatic','far','farm','fast','fat','fear','feast','federal','feed','feel','female','few','field','fierce','fight','fill','film','final','find','fine','finish','fire','firm','first','fish','fix','flag','flat','flee','float','flood','floor','flow','flower','fluid','fly','fog','follow','food','fool','foot','force','foreign','forget','forgive','form','former','forward','free','freeze','fresh','friend','frighten','front','fruit','fuel','funeral','furious','future',
		'gain','game','gas','gather','general','gentle','gift','girl','give','glass','goal','gold','good','goods','govern','government','grain','grass','gray','great','green','grind','ground','group','grow','guarantee','guard','guerilla','guide','guilty','gun',
		'hair','half','halt','hang','happen','happy','harbor','hard','harm','hat','hate','head','headquarters','health','hear','heart','heat','heavy','helicopter','help','hero','hide','high','hijack','hill','history','hit','hold','hole','holiday','holy','home','honest','honor','hope','horrible','horse','hospital','hostage','hostile','hostilities','hot','hotel','hour','house','how','however','huge','human','humor','hunger','hunt','hurry','hurt','husband',
		'ice','idea','illegal','imagine','immediate','import','important','improve','incident','incite','include','increase','independent','industry','inflation','influence','inform','injure','innocent','insane','insect','inspect','instead','instrument','insult','intelligent','intense','interest','interfere','international','intervene','invade','invent','invest','investigate','invite','involve','iron','island','issue',
		'jail','jewel','job','join','joint','joke','judge','jump','jungle','jury','just',
		'keep','kick','kind','kiss','knife','know',
		'labor','laboratory','lack','lake','land','language','large','last','late','laugh','launch','law','lead','leak','learn','leave','left','legal','lend','less','let','letter','level','lie','life','light','lightning','like','limit','line','link','liquid','list','listen','little','live','load','local','lonely','long','look','lose','loud','love','low','loyal','luck',
		'machine','mad','mail','main','major','majority','make','male','man','map','march','mark','marker','mass','material','may','mayor','mean','measure','meat','medicine','meet','melt','member','memorial','memory','mercenary','mercy','message','metal','method','microscope','middle','militant','military','milk','mind','mine','mineral','minister','minor','minority','minute','miss','missile','missing','mistake','mix','mob','moderate','modern','money','month','moon','more','morning','most','mother','motion','mountain','mourn','move','much','music','must','mystery',
		'naked','name','nation','navy','near','necessary','negotiate','neither','nerve','neutral','never','new','news','next','nice','night','noise','nominate','noon','normal','north','note','nothing','nowhere','nuclear','number','nurse',
		'obey','object','observe','occupy','ocean','offensive','offer','officer','official','often','oil','old','once','only','open','operate','opinion','oppose','opposite','oppress','orbit','orchestra','order','organize','other','overthrow',
		'pain','paint','palace','pamphlet','pan','paper','parachute','parade','pardon','parent','parliament','part','party','pass','passenger','passport','past','path','pay','peace','people','percent','perfect','perhaps','period','permanent','permit','person','physics','piano','picture','piece','pilot','pipe','pirate','place','planet','plant','play','please','plenty','plot','poem','point','poison','police','policy','politics','pollute','poor','popular','population','port','position','possess','possible','postpone','pour','power','praise','pray','pregnant','prepare','present','president','press','pressure','prevent','price','priest','prison','private','prize','probably','problem','produce','professor','program','progress','project','promise','propaganda','property','propose','protect','protest','proud','prove','provide','public','publication','publish','pull','pump','punish','purchase','pure','purpose',
		'question','quick','quiet',
		'rabbi','race','radar','radiation','radio','raid','railroad','rain','raise','rapid','rare','rate','reach','read','ready','real','realistic','reason','reasonable','rebel','receive','recent','recession','recognize','record','red','reduce','reform','refugee','refuse','regret','relations','release','remain','remember','remove','repair','repeat','report','repress','request','rescue','resign','resolution','responsible','rest','restrain','restrict','result','retire','return','revolt','rice','rich','ride','right','riot','rise','river','road','rock','rocket','roll','room','root','rope','rough','round','rub','rubber','ruin','rule','run',
		'sabotage','sacrifice','safe','sail','salt','same','satellite','satisfy','save','say','school','science','scream','sea','search','season','seat','second','secret','security','see','seek','seem','seize','self','sell','senate','send','sense','sentence','separate','series','serious','sermon','settle','several','severe','shake','shape','share','sharp','shell','shine','ship','shock','shoe','shoot','short','should','shout','show','shrink','shut','sick','side','sign','signal','silence','silver','similar','simple','since','sing','sink','situation','skeleton','skill','skull','sky','slave','sleep','slide','slow','small','smash','smell','smile','smoke','smooth','snow','social','soft','soldier','solid','solve','some','soon','sorry','sort','sound','south','space','speak','special','speed','spend','spill','spilt','spirit','split','sports','spread','spring','spy','stab','stamp','stand','star','start','starve','state','station','statue','stay','steal','steam','steel','step','stick','still','stomach','stone','stop','store','storm','story','stove','straight','strange','street','stretch','strike','strong','struggle','stubborn','study','stupid','submarine','substance','substitute','subversion','succeed','such','sudden','suffer','sugar','summer','sun','supervise','supply','support','suppose','suppress','sure','surplus','surprise','surrender','surround','survive','suspect','suspend','swallow','swear','sweet','swim','sympathy','system',
		'take','talk','tall','tank','target','task','taste','tax','teach','team','tear','tears','technical','telephone','telescope','television','tell','temperature','temporary','tense','term','terrible','territory','terror','test','textiles','thank','that','theater','thick','thin','thing','think','third','threaten','through','throw','tie','time','tired','tissue','today','together','tomorrow','tonight','tool','top','torture','touch','toward','town','trade','tradition','tragic','train','traitor','transport','trap','travel','treason','treasure','treat','treaty','tree','trial','tribe','trick','trip','troops','trouble','truce','truck','trust','turn',
		'under','understand','unite','universe','university','unless','until','up','urge','urgent','usual','valley','value','vehicle','version','veto','vicious','victim','victory','village','violate','violence','violin','virus','visit','voice','volcano','vote','voyage',
		'wages','wait','walk','wall','want','warm','warn','wash','waste','watch','water','wave','way','weak','wealth','weapon','wear','weather','weigh','welcome','well','west','wet','wheat','wheel','white','wide','wife','wild','will','willing','win','wind','window','wire','wise','wish','withdraw','without','woman','wonder','wood','woods','word','work','world','worry','worse','wound','wreck','write','wrong',
		'year','yellow','yesterday','young',
		'zealot','zebra');


	/*
	 * Simple Encypt Password
	 * Will put system salt into effect if set in configuration
	 * @param  (string) $string - Password / String to simple encode
	 * @return (string)
	*/
	static function encypt($string) {
		if (conf::get('salt')) $string .= conf::get('salt');
		$string = hash("sha512", $string);
		return $string;
	}


	/*
	 * Generate a Memorable Password instead of random letters
	 * @param  (int) $length - Optional word length
	 * @return (string) - Your new memorable password
	*/
	static function memorable($length=2) {
		$string = "";

		for ($i=0; $i<$length ; $i++) {
			$string .= ucfirst(self::$memorables[mt_rand(0, sizeof(self::$memorables))]);
		}
		return $string;
	}


} /* Password Methods */


/*
 * Requests
 * Set of methods for Requests
*/
class req {

	/*
	 * cUrl request a file
	 * @param  (string) $request - The file you are requesting
	 * @param  (string|boolean) $file - The local file to save to
	 * @param  (array) $post - Optional post variables
	 * @param  (array) $header - Optional Content HTTP Headers
	 * @return (file|string) - Returns result, or can save to file
	*/
	static function curl($request, $file=false, $post=false, $header=false) {
		if (empty($request)) return false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $request);
		if ($post) {
			curl_setopt($ch,CURLOPT_POST, 1);
			curl_setopt($ch,CURLOPT_POSTFIELDS ,$post);
		}
		if ($header) {
			curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$return = curl_exec($ch);
		curl_close($ch);

		if (empty($return)) return false;
		if (!$file) return $return;
		fs::write($file,$return);
		return true;
	}

	/*
	 * File get request
	 * @param  (string) $request - The file you are requesting
	 * @param  (string) $file - The local file to save to
	 * @param  (array) $post - Use POST
	 * @param  (array) $header - Optional Content HTTP Headers
	 * @return (file|string) - Returns result, or can save to file
	*/
	static function file($request, $file=null, $post=false, $header=false) {
		if (empty($request)) return false;
		$met = ($post)?'POST':'GET';
		if (empty($header)) $header = array("Content-Type: text/html;","charset=".conf::get('charset'));

		$options = array(
			'http' => array(
				 'method'=>$met,
				 'header'=>$header
			)
		);

		$con = stream_context_create($options);
		$return = file_get_contents($request, false, $con);


		if (empty($return)) return false;
		if (empty($file)) return $return;
		fs::write($file,$return);
		return true;
	}


	/*
	 * Find the refering link
	 * @param  (string) $default - referred the user to the page
	 * @return (string)
	*/
	static function referer($default=null) {
		if(empty($default)) $default = '/';
		return server::get('http_referer', $default);
	}


} /* Request Methods */


/*
 * Sessions
 * Set of methods to help with sessions
*/
class sess {


	/*
	 * Starts session
	 */
	static function start() {
		@session_start();
	}


	/*
	 * Ends session
	*/
	static function kill() {
		@session_destroy();
	}


	/*
	 * Create a new session variable
	 * @param (string) $key - Session variable key name
	 * @param (string) $value - String to save to new session variable
	*/
	static function set($key, $value=false) {
		if(!isset($_SESSION)) self::start();
		$_SESSION[$key] = $value;
	}


	/*
	 * Get a session key or the whole array
	 * @param  (string) $key - The key to return
	 * @return (array|string) - Return single variable if key set, or whole session array
	 */
	static function get($key=false) {
		if(!isset($_SESSION)) self::start();
		if(empty($key)) return $_SESSION;
		return ar::get($_SESSION, $key);
	}


	/*
	 * Deletes a session key
	 * @param  (string) $key - The key to delete
	 * @return (array) - The updated Session array
	*/
	static function delete($key) {
		if(!isset($_SESSION)) self::start();
		$_SESSION = ar::delete($_SESSION, $key, true);
		return $_SESSION;
	}


} /* Session Methods */


/*
 * Server
 * Easier method to get variables from global server
 */
class server {


	/*
	 * Get a value from the _SERVER array
	 * @param  (string) $key - The key to look for
	 * @return (boolean|string) - return string result, false on key fault
	*/
	static function get($key=false) {
		if(empty($key)) return $_SERVER;
		return ar::get($_SERVER, str::upper($key));
	}


	/*
	 * View broken down phpinfo
	 * @param  (string) $what - info constants
	 * @return (array)
	 * http://php.net/function.phpinfo.php
	*/
	static function info($what=INFO_ALL) {
		ob_start();
		phpinfo($what);
		$info = array();
		$lines = explode("\n", strip_tags(ob_get_clean(), "<tr><td><h2>"));
		$cat="General";
		foreach($lines as $line) {  preg_match("~<h2>(.*)</h2>~", $line, $match)? $cat = $match[1] : null;
			if(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val)) {
				 $info[$cat][$val[1]] = $val[2];
			} elseif(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val)) {
				 $info[$cat][$val[1]] = array("local" => $val[2], "master" => $val[3]);
			}
	  }
	  return $info;
	}


} /* Server Methods */


/*
 * String
 * Set of string methods
*/
class str {

  //
  // Convert word to hexadecimal color
  // @param (string) $string - String to convert
  // @return (string) - Converted Colour value
  //
  static function hexcolor ( $string ) {
    $hexcolor = md5 ( $string );
    $hexcolor = substr($hexcolor, 0, 6);
    return '#' . $hexcolor;
  }

  //
  // Curly Quotes and other Text Goodness
  // @param (string) $string - Text to process
  // @return (string) - Processed text
  //

  static function curly ($string) {
      if ($string == '') {
        return '';
      }
      $search = array(
                      ' \'',
                      '\' ',
                      ' "',
                      '" ',
                      ' “\'',
                      '\'” ',
                      ' ‘"',
                      ' "’',
                      '>\'',
                      '\'<',
                      '>"',
                      '"<',
                      '>“\'',
                      '\'”<',
                      '>‘"',
                      '>"’',
                      '…'
                      );

      $replace = array(
                      ' ‘',
                      '’ ',
                      ' “',
                      '” ',
                      ' “‘',
                      '’" ',
                      ' ‘“',
                      ' ”’',
                      '>‘',
                      '’<',
                      '>“',
                      '”<',
                      '>“‘',
                      '’"<',
                      '>‘“',
                      '>”’',
                      '…'
                       );

      $searchsingle = array(
                '"',
                "'"
                );

      $replacestart = array(
                '“',
                '‘'
                );

      $replaceend = array(
                '”',
                '’'
                );

      $string = str_replace('\'', '’', str_replace($search, $replace, $string));
      $string = preg_replace('/<([^<>]+)>/e', '"<" .str_replace("”", \'"\', "$1").">"', $string);
      $string = preg_replace('/<([^<>]+)>/e', '"<" .str_replace("’", "\'", "$1").">"', $string);
      $first = $string{0};
      $first = str_replace($searchsingle, $replacestart, $first);
      $string = $first . substr($string, 1);
      $invert = strrev( $string );
      $last = $invert{0};
      $last = str_replace($searchsingle, $replaceend, $last);
      $string = strrev(substr($invert, 1)) . $last;
      return $string;
  }


	/*
	 * Encode a string to ASCII
	 * @param  (string) $string - String to encode
	 * @return (string) - encoded string
	*/
	static function ascii($string) {
	 $encoded = '';
	 $length = str::length($string);
	 for($i=0; $i<$length; $i++) {
		$encoded .= '&#'.ord($string[$i]).';';
	 }
	 return $encoded;
	}


	/*
	 * Adds an apostrophe to a string if applicable - Works on a word basis
	 * @param  (string) $string - The string
	 * @return (string) - String + apostraphe
	*/
	static function apostrophe($string) {
		return (substr($string,-1,1)=='s' || substr($string,-1,1)=='z')? $string .= "'" : $string .= "'s";
	}


	/*
	 * Base 64 encode a string
	 * @param  (string) $string - String to encode
	 * @return (string)
	*/
	static function encode($string) {
		return base64_encode($string);
	}


	/*
	 * Base 64 decode a string
	 * @param  (string) $string - String to decode
	 * @return (string)
	*/
	static function decode($string) {
		return base64_decode($string);
	}


	/*
	 * Create a excerpt of text
	 * @param  (string) $string - The source string
	 * @param  (int) $length - Length of excert in characters
	 * @param  (string) $break - break to display
	 * @param  (boolean) $removehtml - remove HTML tags
	 * @return (string)
	*/
	static function excerpt($string, $length=140, $break='...', $removehtml=true) {
		if($removehtml) $string = strip_tags($string);
		$string = str_ireplace("\n", ' ', str::trim($string));

		if(strlen($string) <= $length) return $string;
		return ($length==0)? $string : substr($string, 0, strrpos(substr($string, 0, $length),' ')).$break;
	}


	/*
	 * Find a value in a string
	 * @param  (string) $string - String to search in
	 * @param  (string) $search - to search for
	 * @return (boolean)
	*/
	static function find($string,$search) {
		return (stripos($string, $search)===false)? false : true ;
	}


	/*
	 * Create a random string
	 * @param  (int) $length - length of new string
	 * @param  (string) $characters - list of allowed characters
	 * @return (string) - Your new string
	*/
	static function random($length=8, $characters='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
		$string="";
		for ($i=0; $i<=$length; $i++) {
			$string .= substr($chars, ((int)rand(0,strlen($chars))) -1,1);
		}
		return $string;
	}


	/*
	 * Sanetize string
	 * @param  (string) $text - Text string
	 * @param  (string) $nlc - New line convert, If false will be replaced
	 * @param  (string) $allowed - Allowed HTML tags, defaults provided
	 * @return (string)
	*/
	static function sanetize($text, $nlc=true, $allowed='') {
		$StringAllow = ($allowed!='')? $allowed : conf::get('AllowSanetize');

		$text = str::unquote($text);
		$text = strip_tags($text, $StringAllow);
		$text = ($nlc)?nl2br($text):$text;
		$encoding = mb_detect_encoding($text,'UTF-8, ISO-8859-1, GBK');
		$text = ($encoding!='UTF-8')?iconv($encoding,'utf-8',$text):$text;
		return str::trim($text);
	}


	/*
	 * Split string
	 * @param  (string) $string - String to split
	 * @param  (string) $by - Split at
	 * @return (array) - Array of results
	*/
	static function split($string,$by) {
		$string = trim($string, $by);
		return explode($by, $string);
	}


	/*
	 * Improved Trim method
	 * @param  (string) $string - String requiring trim
	 * @return (string)
	*/
	static function trim($string) {
		$string = preg_replace('/\s\s+/u',' ',$string);
		return trim($string);
	}


	/*
	 * Improved string to lower method
	 * @param  (string) $string - String requiring lowercased
	 * @return (string)
	*/
	static function lower($string) {
		return mb_strtolower($string, conf::get('charset'));
	}


	/*
	 * Improved string to upper method
	 * @param  (string) $string - String requiring uppercased
	 * @return (string)
	*/
	static function upper($string) {
		return mb_strtoupper($string, conf::get('charset'));
	}


	/*
	 * Improved uppercase words method
	 * @param  (string) $string - String requiring case title
	 * @return (string)
	*/
	static function titlecase($string) {
		return $str = mb_convert_case($string, MB_CASE_TITLE, conf::get('charset'));
	}


	/*
	 * Improved string length
	 * @param  (string) $string - string to measure
	 * @return (string)
	*/
	static function length($string) {
		return mb_strlen($string, conf::get('charset'));
	}


	/*
	 * Serialize to a storable representation of a value
	 * @param  (mixed) $value - Value to serialize
	 * @return (string)
	*/
	static function serial($value) {
		return serialize($value);
	}

	/*
	 * Unserialize a value to object
	 * @param  (mixed) $value - Value to unserialize
	 * @return (string)
	*/
	static function deserial($value) {
		return unserialize($value);
	}


	/*
	 * Switch to display either one or the other string dependend on a counter
	 * @param  (int) $count - counter
	 * @param  (string) $many - The string to be displayed for a counter > 1
	 * @param  (string) $one - The string to be displayed for a counter == 1
	 * @param  (string) $zero - The string to be displayed for a counter == 0
	 * @return (string) - The string
	 */
	static function plural($count, $many, $one, $zero=null) {
		if($count == 1) {
			return $one;
		} else if($count == 0 && !empty($zero)) {
			return $zero;
		} else {
			return $many;
		}
	}


	/*
	 * Create a word shortened sting
	 * @param  (string) $string - The source string
	 * @param  (int) $length - Length of excert in characters
	 * @param  (string) $break - break to display
	 * @param  (boolean) $removehtml - remove HTML tags
	 * @return (string)
	*/
	static function wordexcerpt($string, $length=140, $break='...', $removehtml=true) {
		$ins = self::excerpt($string, $length, $break, $removehtml);
		if(strlen($ins) <= $length) return $ins;
		return substr($ins, 0, strrpos($ins, ' ')).' '.$break;
	}


	/*
	 * Escape a string
	 * @param  (string) $string - String to escape
	 * @return  (string) - Our escaped string
	*/
	static function escape($string) {
		$string = addslashes($string);
		return (string)$string;
	}


	/*
	 * Unquotes an escaped stirng
	 * @param  (string) $string - String to unquote
	 * @return (string) - Our unquoted string
	*/
	static function unquote($string) {
		$string = stripslashes($string);
		$string = stripslashes($string);
		return (string)$string;
	}


	/*
	 * Convert any numbers in a string back to words
	 * @param  (string) $string - String to convert numbers to words
	 * @return (string) - An updated string with word numbers
	*/
	static function numbers($string){
	$nums  = array('0','1','2','3','4','5','6','7','8','9');
	$match = array('zero','one','two','three','four','five','six','seven','eight','nine');
	foreach ($nums as $key => $value) {
		$string = str_replace($nums[$key], $match[$key], $string);
	}
	return $string;
}


} /* String Methods */


/*
 * URL
 * Set of url methods
*/
class url {


	/*
	 * Return current URL
	 * @return (string)
	 */
	static function current() {
		$host = (server::get('https')!='' || server::get('server_port')==443)? "https://" : "http://";
		return $host.server::get('http_host').server::get('request_uri');
	}


	/*
	 * Lets go places
	 * @param  (string) $url - URL to switch to
	 * @param  (string) $status - Optional status code
	 * @return (header)
	*/
	static function go($url,$status=false) {
		if ($status) {
			switch($status) {
			case 301:
			  header('HTTP/1.1 301 Moved Permanently');
			  break;
			case 302:
			  header('HTTP/1.1 302 Found');
			  break;
			case 303:
			  header('HTTP/1.1 303 See Other');
			  break;
			}
		}
		header("Location: $url");
	}


	/*
	 * Make and return Shortened URL using goo.gl
	 * @param  (string) $url - URL to shorten
	 * @return (string)
	*/
	static function short($url='') {
		if (!isset($url)) return false;
		$v = json_decode(req::curl("https://www.googleapis.com/urlshortener/v1/url", false, json_encode(array('longUrl'=>$url)), array('Content-Type: application/json')),true);
		if (isset($v['error'])) return false;
		return $v['id'];
	}


	/*
	 * Strip back url for easy view
	 * @param  (string) $url - URL to tidy
	 * @param  (boolean) $domain - For domain only view
	 * @return (string)
	*/
	static function strip($url='',$domain=false) {
		$url = str_replace('http://','',$url);
		$url = str_replace('https://','',$url);
		$url = str_replace('ftp://','',$url);
		$url = str_replace('www.','',$url);
		if ($domain) {
			$a = str::split($url,'/');
			$url = ar::get($a, 0);
		}
		return $url;
	}


	/*
	 * Strips query string from URL
	 * @param  (string) $url - URL to strip
	 * @return (string)
	*/
	static function strip_query($url) {
		return preg_replace('/\?.*$/is', '', $url);
	}


	/*
	* Strips hash string from the URL
	* @param  (string) $url - URL to strip
	* @return (string)
	*/
	static function strip_hash($url) {
		return preg_replace('/#.*$/is', '', $url);
	}


	/*
	 * Wether a URL is valid
	 * @param  (string) $url - String to test
	 * @return (boolean)
	*/
	static function valid($url) {
		return preg_match('|^(https?\:\:?\/\/)?(www.)?[^.]+\.\w{2,8}|i', $url);
	}


} /* Url Methods */


/*
 * Video
 * Set of video methods
*/
class video {


	/*
	 * Embed code for Offsite Video Playback (YouTube|Vimeo)
	 * @param  (string) $url - Playback embed URL
	 * @param  (int) $width - Pixel width of video player
	 * @param  (int) $height - Pixel height of video player
	 * @param  (boolean) $fullscreen - Wether to allow full screen mode
	 * @return (string) - Our formatted HTML Output video
	*/
	static function embed($url, $width=500, $height=281, $fullscreen=true) {
		$screen = ($fullscreen)? "allowfullscreen" : "" ;
		return '<iframe width="'.$width.'" height="'.$height.'" src="'.$url.'" frameborder="0" '.$screen.'></iframe>';
	}


	/*
	 * Parse YouTube URL
	 * @param  (string) $link - String contating youtube url to get playcode
	 * @return (string) - Embed URL
	*/
	static function youtube($link) {
		$embedhost = "http://www.youtube.com/embed/";
		$uri = parse_url($link);

		//For seriously malformed urls
		if ($uri === false) return false;

		//Check for domain to decode
		switch ($uri['host']) {
			case 'youtu.be':
				return $embedhost.substr($uri['path'], 1);
				break;
			case 'youtube.com':
			case 'www.youtube.com':
				parse_str($uri['query'], $params);
				return $embedhost.$params['v'];
				break;
			default:
				return false;
				break;
		} //switch
	}


	/*
	 * Parse Vimeo URL
	 * @param  (string) $link - String contating vimeo URL to get playcode
	 * @param  (string) @colour - String contating hex colour for alternate vimeo players
	 * @return (string) - Embed URL
	*/
	static function vimeo($link,$colour='00fbff') {
		$embedhost = "http://player.vimeo.com/video/";
		$embedvars = "?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=$colour";
		$uri = parse_url($link);

		//For seriously malformed urls
		if ($uri === false) return false;

		return $embedhost.substr($uri['path'], 1).$embedvars;
	}


} /* Video methods */


/*
 * Visitor
 * Set of visitor information methods
*/
class visitor {


	/*
	 * Get visiting users browser information
	 * http://stackoverflow.com/questions/1895727/how-can-i-detect-the-browser-with-php-or-javascript#answer-1896680 - Hard to Beat this
	 *
	 * @return (array) - An array of information about the visitors browser
	*/
	static function browser() {
		$browser = array(
			'name'      => 'unknown',
			'version'   => '0.0.0',
			'majorver'  => 0,
			'minorver'  => 0,
			'build'     => 0,
			'useragent' => str::lower(server::get('HTTP_USER_AGENT'))
		);

		$browsers = array(
			'firefox', 'msie', 'opera', 'chrome', 'safari', 'mozilla', 'seamonkey', 'konqueror', 'netscape','gecko', 'navigator', 'mosaic', 'lynx', 'amaya', 'omniweb', 'avant', 'camino', 'flock', 'aol'
		);

		foreach($browsers as $_browser) {
			if (preg_match("/($_browser)[\/ ]?([0-9.]*)/", $browser['useragent'], $match)) {
				$browser['name'] = $match[1];
				$browser['version'] = $match[2];
				@list($browser['majorver'], $browser['minorver'], $browser['build']) = explode('.', $browser['version']);
				break;
			}
		}

		return $browser;
	}


	/*
	 * Get visiting users IP address
	 * @return (string) - IP address of visitor
	*/
	static function ip() {
		return server::get('remote_addr');
	}


	/*
	 * Get visiting users Operating System
	*/
	static function os() {
		$OS = array(
		'iPhone' 	 => 'iphone',
		'iPad'		 => 'ipad',
		'iPod'		 => 'ipod',
		'Android'	 => 'android',
		'BlackBerry' => 'blackberry',
		//May Expand Phones at a Later Date
		'Win 98' 	 => '(win98)|(windows 98)',
		'Win 2000' 	 => '(windows 2000)|(windows nt 5.0)',
		'Win ME' 	 => 'windows me',
		'Win NT 4.0' => '(winnt)|(windows nt 4.0)|(winnt4.0)|(windows nt)',
		'Win XP' 	 => '(windows xp)|(windows nt 5.1)',
		'Win Vista'  => 'windows nt 6.0',
		'Win 7' 		 => '(windows nt 6.1)|(windows nt 7.0)',
		'Linux' 		 => '(x11)|(linux)',
		'Mac OS' 	 => '(mac_powerpc)|(macintosh)|(mac os)'
		);

		$os = 'Unknown';
		$agent = str::lower(server::get('HTTP_USER_AGENT'));

		foreach($OS as $Name => $index) {
			if (preg_match("/$index/i", $agent)) {
				$os = $Name;
				break;
			}
		}
	return $os;
	}


}