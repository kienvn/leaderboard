<?php

require_once("idatabase.php");

/**
 * @version 0.2
 * @author RadoRado (a.k.a Rado)
 * Should be more generic in the bright future
 * For now, it's going to work with MySQL
 * The class requires config_util.php(Config class) It's up to the user to include it
 */
class Database implements IDatabase {

    // ----------------------------------------
    // PRIVATE FIELDS
    // ----------------------------------------
    /**
     * @var string Holds the last executed query
     */
    private $last_query = "";
    /**
     * @var Holds the connection link to the database.
     */
    private $connection = null;
    /**
     * @var The basic configuration options for a database connection
     */
    private $db_config = array(
        "DB_USER" => "",
        "DB_PASS" => "",
        "DB_NAME" => "",
        "DB_HOST" => ""
    );
    private $errors = array(
        "ESCAPE_ERROR" => "Error on escaping when called %s function",
        "CHECK_RESULT_ERROR" => "There was error with %s SQL statement.Error : %s",
        "DATABASE_CONNECTION_ERROR" => "Error connecting to MySQL database. %s",
        "SELECTING_DATABASE_ERROR" => "Error selecting database with name %s. %s");
    /**
     * @var bool Flag to indicate if magic quotes are active on the current server
     */
    private $magic_quotes_active = null;
    /**
     * @var bool Flag to indicate if mysql_real_escape_string function exists (PHP version >= 4.3.0)
     */
    private $real_escape_string_exists = null;

    // ----------------------------------------
    // CONSTRUCTOR METHOD
    // ----------------------------------------
    public function __construct($config, $create_table_name = false) {
        Config::apply($this->db_config, $config);
        $this->magic_quotes_active = get_magic_quotes_gpc();
        $this->real_escape_string_exists = function_exists("mysql_real_escape_string"); // i.e. PHP >= v4.3.0
        $this->openConnection($create_table_name);
    }

    // ----------------------------------------
    // INTERFACE METHODS
    // ----------------------------------------

    /**
     * Performs MySQL database query.
     * Values should be escaped by now.
     * @param <string> $sql - the SQL statement
     * @return <handler> link to the MySQL resource
     */
    public function query($sql) {
        $this->last_query = $sql;
        $res = mysql_query($sql, $this->connection);
        $this->check_result($res); // if there's an error, an exception will be thrown

        return $res;
    }

    /**
     * Sets the encoding of the database using SET NAMES query
     * @param <string> $encoding
     * @return void
     */
    public function setEncoding($encoding) {
        $this->query("SET NAMES " . $encoding);
    }

    /**
     * Wrapper for mysql_fetch_assoc
     * @param <Resource> $result
     * @return <array> Associative array containing the next row from the query
     */
    public function fetchAssoc($result) {
        return mysql_fetch_assoc($result);
    }

    /**
     * Wrapper for mysql_affected_rows
     * @return <integer> The number of the rows affected after the last UPDATE | INSERT | DELETE
     */
    public function affectedRows() {
        return mysql_affected_rows($this->connection);
    }

    /**
     * Wrapper for mysql_fetch_object
     * @param <Resource> $result
     * @return <Object> Object, containing the next row from the query
     */
    public function fetchObject($result) {
        return mysql_fetch_object($result);
    }

    /**
     * Wrapper for mysql_fetch_array
     * @param <Resource> $result
     * @return <array> Associative array containing the next row from the query
     */
    public function fetchArray($result) {
        return mysql_fetch_array($result);
    }

    /**
     * Wrapper for mysql_error
     * @return <string> The last error from querying the database
     */
    public function lastError() {
        return mysql_error($this->connection);
    }

    /**
     * Wrapper for mysql_insert_id
     * @return <integer> The last id from an INSERT query
     */
    public function lastInsertedId() {
        return mysql_insert_id($this->connection);
    }

    /**
     * Wrapper for mysql_num_rows
     * @param <Resource> $result
     * @return <integer> number of rows that were SELECTed
     */
    public function numRows($result) {
        return mysql_num_rows($result);
    }

    /**
     * Opens connection to a MySQL database.
     * If there's an error, an Exception will be thrown
     * @param <boolean> $createTable - if true, database with the given name is created
     * @return void
     */
    public function openConnection($createTable = false) {
        $this->connection = mysql_connect(
                        $this->db_config["DB_HOST"],
                        $this->db_config["DB_USER"],
                        $this->db_config["DB_PASS"]);

        if (!$this->connection) { /* Something went wrong */
            throw new Exception(sprintf($this->errors["DATABASE_CONNECTION_ERROR"], mysql_error()));
        } else {
            if ($createTable == true) {
                $this->query("CREATE DATABASE " . $this->db_config["DB_NAME"]);
            }
            $select = mysql_select_db($this->db_config["DB_NAME"], $this->connection);
            if (!$select) {
                throw new Exception(sprintf($this->errors["SELECTING_DATABASE_ERROR"], $this->db_config["DB_NAME"], mysql_error()));
            }
        }
    }

    /**
     * Returns the link to the database
     * @return <Link identifier>
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Closes the connection to the MySQL database
     * and unsets the link to it
     */
    public function closeConnection() {
        if (isset($this->connection)) {
            // don't actually care if it returns false
            mysql_close($this->connection);
            unset($this->connection);
        }
    }

    /**
     * Sanitizes the given parameter
     * So it's not dangerous to be put in the database
     * This method performs mysql_real_escape_string (or addslashes, if PHP version is < 4.3.0)
     * Checks if magic_quotes is active and removes any useless slashes
     * This method has the functionality to apply user-provided functions to $value
     * Those functions are passed as an array of strings (the second parameter)
     * @param  $value - the value that's going to be escaped
     * @param $more_functions - optional parameter, an array of strings that are
     * user-provided function names, that should be applied to the $value
     * @return the sanitized $value
     */
    public function escape($value, $more_functions = null /* array of strings */) {
        if ($this->real_escape_string_exists) { // PHP >= v4.3.0
            if ($this->magic_quotes_active) {
                $value = stripslashes($value);
            }
            $value = mysql_real_escape_string($value);
        } else { // <= PHP v4.3.0
            if (!$this->magic_quotes_active) {
                $value = addslashes($value);
            }
        }

        if ($more_functions != null) {
            foreach ($more_functions as $func) {
                $res = call_user_func($func, $value);
                if (!$res) { /* False value means error */
                    throw new Exception(sprintf($this->errors["ESCAPE_ERROR"]), $func);
                }
                $value = $res;
            }
        }
        return $value;
    }

    // ----------------------------------------
    // PRIVATE METHODS
    // ----------------------------------------
    private function check_result($sql_res) {
        if (!$sql_res) {
            throw new Exception(sprintf($this->errors["CHECK_RESULT_ERROR"], $this->last_query, mysql_error()));
        }
    }

}

