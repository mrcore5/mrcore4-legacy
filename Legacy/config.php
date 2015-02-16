<?php
#namespace needs to be here so it doesn't conflict with actual Snippets\Config if mixing libraries
namespace Helpers; 

class Config {
	//Default MSSQL Connection Info (used only if MSSQL helper instantiation is parameterless)
    public static function MSSQL_DB_NAME()   {return \Config::get('my.legacy.MSSQL_DB_NAME');} 
    public static function MSSQL_DB_SERVER() {return \Config::get('my.legacy.MSSQL_DB_SERVER');}
    public static function MSSQL_DB_PORT()   {return \Config::get('my.legacy.MSSQL_DB_PORT');}
    public static function MSSQL_DB_USER()   {return \Config::get('my.legacy.MSSQL_DB_USER');}
    public static function MSSQL_DB_PASS()   {return \Config::get('my.legacy.MSSQL_DB_PASS');}

    //Default MYSQL Connection Info (used only if MYSQL helper instantiation is parameterless)
    public static function MYSQL_DB_NAME()   {return \Config::get('my.legacy.MYSQL_DB_NAME');}
    public static function MYSQL_DB_SERVER() {return \Config::get('my.legacy.MYSQL_DB_SERVER');}
    public static function MYSQL_DB_PORT()   {return \Config::get('my.legacy.MYSQL_DB_PORT');}
    public static function MYSQL_DB_USER()   {return \Config::get('my.legacy.MYSQL_DB_USER');}
    public static function MYSQL_DB_PASS()   {return \Config::get('my.legacy.MYSQL_DB_PASS');}
}