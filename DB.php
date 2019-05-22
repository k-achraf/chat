<?php

class DB
{

    /**
     * @var null $_instance
     * the class instance
     */
    private static $_instance = null;

    public $pdo;

    public function __construct()
    {
        try{
            $this->pdo = new PDO('mysql:host=localhost;dbname=chat' , 'root' , '', [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
        catch (PDOException $e){
            die($e->getMessage());
        }
    }

    public static function getInstance(){
        if (!isset(self::$_instance)){
            self::$_instance = new static();
        }
        return self::$_instance;
    }
}