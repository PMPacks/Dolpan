<?php
namespace PTK\NapThe\Storage;

class MySQL{    
    protected $hostname;
    protected $user;
    protected $pass;
    protected $database;

    private $CREATE_DB= "CREATE TABLE IF NOT EXISTS `points` (
        `id` INT NOT NULL AUTO_INCREMENT,
        `username` VARCHAR(45) NOT NULL,
        `points` INT(11) NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`),
        UNIQUE INDEX `username_UNIQUE` (`username` ASC))"; //check ok
    private $LOOK = "SELECT * FROM `points` WHERE `username` = '%s'"; //check ok
    private $UPDATE = "UPDATE `points` SET `points`='%s' WHERE `username`='%s';"; //check ok
    private $CRATE_USER = "INSERT INTO `points` (`username`) VALUES ('%s')"; //check ok
    
    /**-------------------------------------------------**/
    
    public function update($playerName, $amount){ 
        $connection = new \mysqli($this->hostname,$this->user,$this->pass);
        $connection->select_db($this->database);       
        if ($this->look($playerName)==-1){
            $connection->query(sprintf($this->CRATE_USER,strtolower($playerName)));
        }       
        $connection->query(sprintf($this->UPDATE,$amount,strtolower($playerName)));
        $connection->close();
    }
    
    public function look($playerName){ 
        $amount = -1;
        $connection = new \mysqli($this->hostname,$this->user,$this->pass, $this->database, 3306);
        $result = $connection->query(sprintf($this->LOOK, strtolower($playerName)));
        if ($result->num_rows > 0){
            $amount = $result->fetch_assoc()["points"];
        }
        $connection->close();
        return $amount;
    }
    
    public function setupMySQL($host, $username, $password, $database){
        $this->hostname=$host;
        $this->user=$username;
        $this->pass=$password;
        $this->database=$database;
        
        $connection = new \mysqli($this->hostname,$this->user,$this->pass); 
        $connection->query("CREATE SCHEMA IF NOT EXISTS `". $this->database ."`"); //check ok
        $connection->select_db($this->database);
        $connection->query($this->CREATE_DB);
        $connection->close();
    }
}