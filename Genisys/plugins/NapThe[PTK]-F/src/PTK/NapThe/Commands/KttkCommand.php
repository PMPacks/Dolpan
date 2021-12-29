<?php
namespace PTK\NapThe\Commands;
use PTK\NapThe\Main;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerJoinEvent;
class KttkCommand{
    public function execute(Main $plugin, CommandSender $sender, $label, array $args){
        $amount = $plugin->api->look($sender->getName());
        $sender->sendMessage($plugin->prefix."§eTài khoản của bạn hiện có ".$amount . " Points.");
        return true;
    }
	
}


