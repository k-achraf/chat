<?php


class Room
{
    private $table;

    private $pdo;

    public $information = [];

    private $pk;

    public function __construct()
    {
        $this->table = 'messages';
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
        $sql = "SELECT c.*, u.name FROM messages c JOIN users u ON(c.user_id = u.id) ORDER BY c.id DESC";

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

    public function insert(){
        $keys = array_keys($this->information);

        if (count($this->information) > 0){
            $sql = "INSERT INTO {$this->table} (`".implode('`, `',$keys)."`) VALUES (:z".implode(', :z',$keys).")";

            $this->pdo->prepare($sql)->execute($this->prepareValues());
        }
        else{
            die('Please complete the data');
        }
    }
}