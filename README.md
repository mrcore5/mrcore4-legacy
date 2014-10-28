mrcore4-legacy
==============

Mrcore4 Legacy workbench helpers for mrcore5


Installation
============

Requires a `my.legacy` config array like so

    // Configs for the mreschke-mrcore4-legacy package
    Config::set('my.legacy', array(
        'MSSQL_DB_NAME' => 'Ebis_Prod',
        'MSSQL_DB_SERVER' => 'dyna-sql',
        'MSSQL_DB_PORT' => 1433,
        'MSSQL_DB_USER' => 'user',
        'MSSQL_DB_PASS' => 'pass',

        'MYSQL_DB_NAME' => 'mrcore4',
        'MYSQL_DB_SERVER' => 'localhost',
        'MYSQL_DB_PORT' => 3306,
        'MYSQL_DB_USER' => 'root',
        'MYSQL_DB_PASS' => 'pass',
    ));
