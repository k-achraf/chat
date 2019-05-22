<?php
include 'DB.php';

class User
{

    private $table;

    private $pdo;

    public $information = [];

    private $pk;

    private $data = [];

    public function __construct()
    {
        $this->table = 'users';
        $this->pdo = DB::getInstance()->pdo;
        $this->pk = 'id';
    }

    public function __get($name)
    {
        return $this->information[$name];
    }

    public function __set($name, $value)
    {
        $this->information[$name] = $value;
    }

    public static function all(){
        $ins = new static();
        $sql = 'SELECT * FROM ' . $ins->table;

        $data = $ins->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    private function prepareValues(){
        $data = [];
        foreach ($this->information as $key => $value){
            $data["z{$key}"] = $value;
        }
        return $data;
    }

    private function register(){
        $keys = array_keys($this->information);

        if (count($this->information) > 0){
            $sql = "INSERT INTO {$this->table} (`".implode('`, `',$keys)."`) VALUES (:z".implode(', :z',$keys).")";

            $this->pdo->prepare($sql)->execute($this->prepareValues());
        }
        else{
            die('Please complete the data');
        }
    }

    private function login(){
//        $_SESSION['id'] = $this->data['id'];
//        $_SESSION['name'] = $this->data['name'];
//        $_SESSION['email'] = $this->data['email'];

        $this->data = $this->data[0];
        $_SESSION['user'] = $this->data;
    }

    public function submit(){
        $sql = "SELECT * FROM {$this->table} WHERE email = '{$this->information['email']}'";

        $this->data = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);


        if (empty($this->data)){
            $this->register();
            $this->login();
        }
        else{
            $this->login();
        }
    }

    public static function find($pk){
        $ins = new static();

        $sql = "SELECT * FROM {$ins->table} WHERE {$ins->pk} = '{$pk}'";
        $data = $ins->pdo
                    ->query($sql)
                    ->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($data)){
            return $data[0];
        }
        return [];
    }
}