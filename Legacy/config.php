<?php
#namespace needs to be here so it doesn't conflict with actual Snippets\Config if mixing libraries
namespace Helpers; 

class Config {
	//Default MSSQL Connection Info (used only if MSSQL helper instantiation is parameterless)
    public static function MSSQL_DB_NAME()   {return \Config::get('database.connections.sqlsrv.database');} 
    public static function MSSQL_DB_SERVER() {return \Config::get('database.connections.sqlsrv.host');}
    public static function MSSQL_DB_PORT()   {return \Config::get('1433');}
    public static function MSSQL_DB_USER()   {return \Config::get('database.connections.sqlsrv.username');}
    public static function MSSQL_DB_PASS()   {return \Config::get('database.connections.sqlsrv.password');}

    //Default MYSQL Connection Info (used only if MYSQL helper instantiation is parameterless)
    public static function MYSQL_DB_NAME()   {return \Config::get('database.connections.mysql.database');}
    public static function MYSQL_DB_SERVER() {return \Config::get('database.connections.mysql.host');}
    public static function MYSQL_DB_PORT()   {return \Config::get('3306');}
    public static function MYSQL_DB_USER()   {return \Config::get('database.connections.mysql.username');}
    public static function MYSQL_DB_PASS()   {return \Config::get('database.connections.mysql.password');}
}
