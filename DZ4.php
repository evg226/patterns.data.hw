<?php

/**
 * Abstract Products
 */

abstract class DBConnection{
    protected $dbType;
    private $host = HOST?HOST:'http://localhost';
    private $dbname = DB?DB:'testDB';
    private $user = USER?USER:'test';
    private $pass = PASS?PASS:'1';

    public function getConnection(){
        return new PDO ("$this->dbType:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
    }

}

abstract class DBQuery{
    protected $queryString;
    public function  buildQuery($table,$queryType,$fields,$condition){
        $this->queryString=
            (new QueryBuilder(new Query))
            ->setTable($table)
            ->setType($queryType)
            ->setBody($fields)
            ->setWhere($condition)
            ->getString();
    }
}

abstract class DBData {
    protected $dbConnection;
    protected $dbQuery;
    public function __construct(DBConnection $dbConnection,DBQuery $dbQuery)
    {
        $this->dbConnection=$dbConnection;
        $this->dbQuery=$dbQuery;
    }
    public function getData ():array{
        //Код по получение данных с примением подготовленных DBConnection и DBQuery
    }

    public function createData ():int{
        //Код по создание элемента с примением подготовленных DBConnection и DBQuery
    }

    //..
    //другие опции CRUD - update, delete
}

/**
 * builder для DBQuery
 */
class Query{
    public $table;
    public $type;
    public $body;
    public $condition;
}

class QueryBuilder{
    protected $query;
    public function __construct(Query $query)
    {
        $this->query=$query;
    }
    public function setTable(string $table){
        $this->query->table=$table;
        return $this;
    }
    public function setType(string $type){
        $this->query->type=$type;
        return $this;
    }
    public function setBody(array $fields){
        $this->query->body=$fields;
        return $this;
    }
    public function setWhere(array $conditions){
        $this->query->condition=$conditions;
    }
    public function getString():string{
        $result='';
        //логика по формированию SQL-запроса из составных частей
        return  $result;
    }

}

/**
 * Concrete ProductsA
 */
class MysqlConnection extends DBConnection{
    public function __construct()
    {
        $this->dbType="mysql";
    }
}
class MysqlQuery extends DBQuery {

}


/**
 * Concrete ProductsB
 */
class PostgresqlConnection extends DBConnection{
    public function __construct()
    {
        $this->dbType="postgres";
    }
}
class PostgresqlQuery extends DBQuery {}

/**
 * Concrete ProductsC
 */
class OracleConnection extends DBConnection{
    public function __construct()
    {
        $this->dbType="oci";
    }
}
class OracleQuery implements DBQueryBuilder {}


/**
 * Abstract factory
 */
interface IDB {
    public function createDBConnection():DBConnection;
    public function createDBQueryBuilder():DBQueryBuilder;
}

/**
 * Concrete Factory1
 */
class MysqlDB implements IDB {

    public function createDBConnection(): DBConnection
    {
        return new MysqlConnection();
    }

    public function createDBQueryBuilder(): DBQueryBuilder
    {
        return new MysqlQueryBuilder();
    }
}

/**
 * Concrete Factory2
 */
class PostgresqlDB implements IDB {

    public function createDBConnection(): DBConnection
    {
        return new PostgresqlConnection();
    }

    public function createDBQueryBuilder(): DBQueryBuilder
    {
        return new PostgresqlQueryBuilder();
    }
}

/**
 * Concrete Factory3
 */
class OracleDB implements IDB {

    public function createDBConnection(): DBConnection
    {
        return new OracleConnection();
    }

    public function createDBQueryBuilder(): DBQueryBuilder
    {
        return new OracleQueryBuilder();
    }
}

