<?php
namespace PTK\NapThe\Commands;
use PTK\NapThe\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecuter;
use pocketmine\command\ConsoleCommandSender;
use _64FF00\PurePerms\PurePerms;

class MuaRankCommand{
    public function execute(Main $plugin, CommandSender $sender, $label, array $args){
        if (!isset($args[1])) return $plugin->registeredCommands['help']->execute($plugin, $sender, $label, $args);
        switch ($args[1]){
            case 1:
                if ($plugin->api->take($sender->getName(), $plugin->config->get("Rank.1"))){
                    $plugin->getServer()->dispatchCommand(new ConsoleCommandSender(),'setvip 1 '.strtolower($sender->getName()).' 10');
                    $plugin->purePerms->setGroup($sender, $plugin->purePerms->getGroup("1"));
                } else {
                    $sender->sendMessage($plugin->prefix."§cCó gì đó sai sai, kiểm tra lại nào");
                }
                break;
            case 2:
                if ($plugin->api->take($sender->getName(), $plugin->config->get("Rank.2"))){
                    $plugin->getServer()->dispatchCommand(new ConsoleCommandSender(),'setvip 2 '.strtolower($sender->getName()).' 20');
                    $plugin->purePerms->setGroup($sender, $plugin->purePerms->getGroup("2"));                    
                }else {
                    $sender->sendMessage($plugin->prefix."§cCó gì đó sai sai, kiểm tra lại nào");
                }
                break;
            case 3:
                if ($plugin->api->take($sender->getName(), $plugin->config->get("Rank.3"))){
                    $plugin->getServer()->dispatchCommand(new ConsoleCommandSender(),'setvip 3 '.strtolower($sender->getName()).' 30');
                    $plugin->purePerms->setGroup($sender, $plugin->purePerms->getGroup("3"));
                }else {
                    $sender->sendMessage($plugin->prefix."§cCó gì đó sai sai, kiểm tra lại nào");
                }
                break;
            case 4:
                if ($plugin->api->take($sender->getName(), $plugin->config->get("Rank.4"))){
                    $plugin->getServer()->dispatchCommand(new ConsoleCommandSender(),'setvip 4 '.strtolower($sender->getName()).' 40');
                    $plugin->purePerms->setGroup($sender, $plugin->purePerms->getGroup("4"));
                }else {
                    $sender->sendMessage($plugin->prefix."§cCó gì đó sai sai, kiểm tra lại nào");
                }
                break;
            case 5:
                if ($plugin->api->take($sender->getName(), $plugin->config->get("Rank.5"))){
					$plugin->getServer()->dispatchCommand(new ConsoleCommandSender(),'setvip 5 '.strtolower($sender->getName()).' 50');
                    $plugin->purePerms->setGroup($sender, $plugin->purePerms->getGroup("5"));
                }else {
                    $sender->sendMessage($plugin->prefix."§cCó gì đó sai sai, kiểm tra lại nào");
                }
                break;
			case 6:
                if ($plugin->api->take($sender->getName(), $plugin->config->get("Rank.6"))){
					$plugin->getServer()->dispatchCommand(new ConsoleCommandSender(),'setvip 6 '.strtolower($sender->getName()).' 60');
                    $plugin->purePerms->setGroup($sender, $plugin->purePerms->getGroup("6"));
                }else {
                    $sender->sendMessage($plugin->prefix."§cCó gì đó sai sai, kiểm tra lại nào");
                }
                break;
        }
        return true;
    }
}

