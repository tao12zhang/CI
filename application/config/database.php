<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['dsn']      The full DSN string describe a connection to the database.
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database driver. e.g.: mysqli.
|			Currently supported:
|				 cubrid, ibase, mssql, mysql, mysqli, oci8,
|				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Query Builder class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['encrypt']  Whether or not to use an encrypted connection.
|
|			'mysql' (deprecated), 'sqlsrv' and 'pdo/sqlsrv' drivers accept TRUE/FALSE
|			'mysqli' and 'pdo/mysql' drivers accept an array with the following options:
|
|				'ssl_key'    - Path to the private key file
|				'ssl_cert'   - Path to the public key certificate file
|				'ssl_ca'     - Path to the certificate authority file
|				'ssl_capath' - Path to a directory containing trusted CA certificats in PEM format
|				'ssl_cipher' - List of *allowed* ciphers to be used for the encryption, separated by colons (':')
|				'ssl_verify' - TRUE/FALSE; Whether verify the server certificate or not ('mysqli' only)
|
|	['compress'] Whether or not to use client compression (MySQL only)
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|	['ssl_options']	Used to set various SSL options that can be used when making SSL connections.
|	['failover'] array - A array with 0 or more data for connections if the main should fail.
|	['save_queries'] TRUE/FALSE - Whether to "save" all executed queries.
| 				NOTE: Disabling this will also effectively disable both
| 				$this->db->last_query() and profiling of DB queries.
| 				When you run a query, with this setting set to TRUE (default),
| 				CodeIgniter will store the SQL statement for debugging purposes.
| 				However, this may cause high memory usage, especially if you run
| 				a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/
$active_group = 'default';
$query_builder = TRUE;


$db['default'] = array(
    'dsn'	=> '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'fengchu',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
//主
$db['SNOB']['0']['hostname'] = '127.0.0.1';
$db['SNOB']['0']['username'] = 'root';
$db['SNOB']['0']['password'] = '';
$db['SNOB']['0']['database'] = 'fengchu';
$db['SNOB']['0']['port']     = '3306';
$db['SNOB']['0']['dbdriver'] = 'mysqli';
$db['SNOB']['0']['dbprefix'] = '';
$db['SNOB']['0']['pconnect'] = FALSE;
$db['SNOB']['0']['db_debug'] = TRUE;
$db['SNOB']['0']['cache_on'] = FALSE;
$db['SNOB']['0']['cachedir'] = '';
$db['SNOB']['0']['char_set'] = 'utf8';
$db['SNOB']['0']['dbcollat'] = 'utf8_general_ci';
$db['SNOB']['0']['swap_pre'] = '';
$db['SNOB']['0']['autoinit'] = TRUE;
$db['SNOB']['0']['stricton'] = FALSE;
$db['SNOB']['0']['isMaster'] = true;


// 从
$db['SNOB']['1']['hostname'] = '127.0.0.1';
$db['SNOB']['1']['username'] = 'root';
$db['SNOB']['1']['password'] = '';
$db['SNOB']['1']['database'] = 'fengchu';
$db['SNOB']['1']['port']     = '3306';
$db['SNOB']['1']['dbdriver'] = 'mysqli';
$db['SNOB']['1']['dbprefix'] = '';
$db['SNOB']['1']['pconnect'] = FALSE;
$db['SNOB']['1']['db_debug'] = TRUE;
$db['SNOB']['1']['cache_on'] = FALSE;
$db['SNOB']['1']['cachedir'] = '';
$db['SNOB']['1']['char_set'] = 'utf8';
$db['SNOB']['1']['dbcollat'] = 'utf8_general_ci';
$db['SNOB']['1']['swap_pre'] = '';
$db['SNOB']['1']['autoinit'] = TRUE;
$db['SNOB']['1']['stricton'] = FALSE;
$db['SNOB']['1']['isMaster'] = FALSE;

$config['database']=$db;