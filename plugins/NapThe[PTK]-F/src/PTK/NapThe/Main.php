<?php
namespace PTK\NapThe;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use PTK\NapThe\Commands\NapTheCommand;
use PTK\NapThe\Commands\MuaRankCommand;
use PTK\NapThe\Commands\HelpCommand;
use PTK\NapThe\Commands\DoiXuCommand;
use PTK\NapThe\Commands\KttkCommand;
use PTK\NapThe\NapTheAPI;
use PTK\NapThe\Storage\MySQL;
use onebone\economyapi\EconomyAPI;
use _64FF00\PurePerms\PurePerms;
use pocketmine\event\player\PlayerJoinEvent;

class Main extends PluginBase{
    
    public $registeredCommands;
    public $prefix = '§cViTien§a> ';
    public $api;
    public $config;
    public $storage;
    public $eco;
    public $purePerms;
    
    public function onEnable() {
        $this->getLogger()->info(": §6---------------------§eNapThe•§bDolpan§6---------------------");
        
        $this->registerCommand();
        
        $this->api = new NapTheAPI();
        $this->api->NapTheAPI($this);
        
        $this->eco = EconomyAPI::getInstance();
        
        $this->purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
        
        $this->config = new Config($this->getDataFolder() ."config.yml", Config::YAML, [
            "Vippay.Merchant_id" => "input",
            "Vippay.API_USER" => "input",
            "Vippay.API_PASSWORD" => "input",
            "MySQL.Host" => "input",
            "MySQL.Username" => "input",
            "MySQL.Password" => "input",
            "MySQL.Database" => "input",
            "Rank.1" => "input",
            "Rank.2" => "input",
            "Rank.3" => "input",
            "Rank.4" => "input",
            "Rank.5" => "input",
			"Rank.6" => "input"
        ]);
        $this->getLogger()->info(": §aTải lên config thành công");
        $this->storage = new MySQL();
        $this->storage->setupMySQL(
                $this->config->get("MySQL.Host"), 
                $this->config->get("MySQL.Username"), 
                $this->config->get("MySQL.Password"), 
                $this->config->get("MySQL.Database")
            );        
    }
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
        if (strtolower($command->getName()) != "vitien") return false;
        if (isset($args[0]) && array_key_exists(strtolower($args[0]), $this->registeredCommands)){
            return $this->registeredCommands[strtolower($args[0])]->execute($this, $sender, $label, $args);
        }
        return $this->registeredCommands['help']->execute($this, $sender, $label, $args);
    }
    
    public function registerCommand(){
        $this->registeredCommands = array(
            "doixu" => new DoiXuCommand(),
            "napthe" => new NapTheCommand(),
            "kttk" => new KttkCommand(),
            "help" => new HelpCommand(),
            "muarank" => new MuaRankCommand()
        );
    }
}

