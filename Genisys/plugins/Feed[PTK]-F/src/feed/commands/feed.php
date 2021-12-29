<?php
namespace feed\commands;
use pocketmine\command as cmd;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use feed\Loader;

class feed extends cmd\Command implements cmd\PluginIdentifiableCommand{
  private $plugin;
  public function __construct(Loader $plugin){
    parent::__construct("feed", "Lấp đầy thành thức ăn!", "/feed (tên người chơi)", ["food", "eat"]);
    $this->setPermission("feedme.feed");
    $this->plugin = $plugin;
  }
    public function getPlugin(){
    return $this->plugin;
  }
  public function execute(cmd\CommandSender $sender, $label, array $args){
    if ($sender instanceof Player) {
        if (count($args) != 0) {
            if ($sender->hasPermission("feedme.other")) {
                $name = $args[0];
                $player = $this->plugin->getServer()->getPlayer($name);
                if($player instanceof Player){ 
                    // Send some pointless messeges
                    $sender->sendMessage(TextFormat::BLUE . "Đã lấp đầy thành thức ăn của ".$name." !");
                    $player->sendMessage(TextFormat::BLUE . $sender->getName()." đã lấp đầy thúc ăn cho bạn!");
                    // set food to 20
                    $player->setFood(20);
                    return true;
                } else{ $sender->sendMessage(TextFormat::BLUE . "Người chơi không hoạt động!"); return true; }  
            } else { $sender->sendMessage(TextFormat::DARK_RED . "Ain't nobody got that permissions!"); return true; }
        } else { // If args is missing set your own food to 20
            $sender->sendMessage(TextFormat::BLUE . "Đã lấp đầy thanh thức ăn của bạn!");
            $sender->setFood(20);
            return true;   
        }
    } else { $sender->sendMessage(TextFormat::DARK_RED . "Nah not gonna feed that console"); return true; }
  }
}
