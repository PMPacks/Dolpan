<?php
namespace PTK\NapThe\Commands;
use PTK\NapThe\Main;
use pocketmine\command\CommandSender;
use onebone\economyapi\EconomyAPI;

class DoiXuCommand{
    public function execute(Main $plugin, CommandSender $sender, $label, array $args){
        if (!isset($args[1])) return $plugin->registeredCommands['help']->execute($plugin, $sender, $label, $args);
        if ($plugin->api->take($sender->getName(), abs(intval($args[1])))){
            $plugin->eco->addMoney($sender->getName(), abs(intval($args[1]))*450);
            $sender->sendMessage($plugin->prefix."§aBạn đã đổi thành công ".abs(intval($args[1]))." Points thành ".abs(intval($args[1]))*450 ." Xu");
            return true;
        } else {
            $sender->sendMessage($plugin->prefix."§cCó gì đó sai sai, kiểm tra lại nào");
            return false;
        }
        return false;
    }
}

