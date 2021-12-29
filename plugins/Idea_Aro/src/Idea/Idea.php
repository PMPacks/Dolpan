<?php

namespace Idea;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as color;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\utils\Config; 

class Idea extends PluginBase Implements Listener{

  public $tag = "§9[§eIdea§9] §f";

  public function onEnable(){
 $this->getServer()->getPluginManager()->registerEvents($this, $this);

if(!is_dir($this->getDataFolder())){ 
   @mkdir($this->getDataFolder()); 
} 
$this->config = new Config($this->getDataFolder()."config.yml",Config::YAML);
 $this->getLogger()->info("§c>§a==========Idea==========§c<");
      }

  public function onDisable(){}

   public function onCommand(CommandSender $sender, Command $command, $label, array $args){
  if($sender instanceof Player){
        switch(strtolower($command->getName())){
           case "idea":
                    if(!isset($args[0])){
      $sender->sendMessage($this->tag."§6Xin hãy sử dụng /idea <Tên Của Bạn> [Góp Ý]");
      $sender->sendMessage($this->tag."§aBạn ghi rõ ý tưởng hộ mình, cảm ơn bạn đã đóng góp"); 
              return true;
                }
  $pl = $sender->getServer()->getPlayer($args[0]);
 // if($pl instanceof Player){
    if(isset($args[1])){
  $motivo = implode(" ", $args);
								$worte = explode(" ", $motivo);
								unset($worte[0]);
								$motivo = implode(" ", $worte);
  $this->config->set($motivo);
  $this->config->save();
         $sender->sendMessage($this->tag."§6>§fÝ tưởng của bạn đã được gửi đi");
     foreach($this->getServer()->getOnlinePlayers() as $p){
									if($p->isOp()){
										$p->sendMessage("§6>§c---------------§8[§eIdea§r§8]§c---------------§6<\n§9Người gửi§8: §e".$args[0]."\n§9Thông tin ý tưởng§8: §e".$motivo."\n§6>§c---------------------------------------§6<");
                  }
            }
       } else {
   $sender->sendMessage($this->tag."§6Xin hãy sử dụng /idea <Teen Của Bạn> [Góp Ý]");
         return true;
     }
   //} else {
   // $sender->sendMessage("§c".$args[0]." §ekhông trực tuyến");
   // return true;
                  // }
             }
       } else {
    $sender->sendMessage($this->tag."§cLệnh chỉ hoạt động trong trò chơi!");
      return true;       
      }
  }
}
    
    
    
      