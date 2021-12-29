<?php

namespace RL;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\entity\Entity;
use pocketmine\entity\Effect;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use onebone\economyapi\economyAPI;

class rl extends PluginBase implements Listener{

public $eco;

  public function onEnable(){
      $this->eco = EconomyAPI::getInstance();
      $this->getLogger()->info("PLUGIN Luật Lệ Độc Quyền For SPC  ON");
  }

  public function onCommand(CommandSender $sender, Command $command, $lable, array $args){
      if($command->getName() == "luat"){
          if(!isset($args[0])){
              $sender->sendMessage("§l§e#§e#§e#§e#§e#§e#§e#§e#§fLuật §e#§e#§e#§e#§e#§e#§e#§e#");
              $sender->sendMessage("§l§e●§bKhông spam chat gây lag:§cKick");
              $sender->sendMessage("§l§e●§bKhông chửi thề, gây war với nhau:§l§cMute");
              $sender->sendMessage("§l§e●§bKhông xin staff khi full:§cKick");
              $sender->sendMessage("§l§e●§bChơi vui vẻ thân thiện, hòa đồng");
              $sender->sendMessage("§l§e●§bTrở thành §6Vip §bđể nhận các món quà hấp dẫn");
              $sender->sendMessage("§l§d=§f=§d=§f=§d=§f=§d=§f=§cLuật cho Staff§f=§d=§f=§d=§f=§d=§f=§d=");
              $sender->sendMessage("§l§e●§dHELPER: GIÚP ĐỠ MEM, CHƠI HÒA ĐỒNG");
              $sender->sendMessage("§l§e●§bPOLICE: BẮT HACK, BUG VÀ LÀM THEO NHIỆM VỤ");
              $sender->sendMessage("§l§e●§eBUILDER: BẤT CỨ KHI NÀO HEOGM KÊU PHẢI CÓ MẶT");
              $sender->sendMessage("§l§c//WARNING//: SERVER VẪN ĐANG PHÁT TRIỂN VUI LÒNG GÓP Ý");
              $sender->sendMessage("§l§e#§e#§e#§e#§e#§e#§e#§e#§f§e#§e#§e#§e#§e#§e#§e#§e#");
}
          switch($args[0]){
             case "cupthan":
               $p = $sender->getName();
$pi = $sender->getInventory();
$i = Item::get(278, 0 ,1);
$c = Enchantment::getEnchantment(15);
$c->setLevel(10);
$i->addEnchantment($c);
$i->setCustomName("§l§bCÚP THẦN");
$pi->addItem($i);
$sender->sendMessage("§aBẠN ĐÃ NHẬN ĐƯỢC CÚP THẦN NHỜ ĐẶC QUYỀN VIP");
break;
               case "riuanhsang":
               $p = $sender->getName();
$pi = $sender->getInventory();
$i = Item::get(279, 0 ,1);
$c = Enchantment::getEnchantment(15);
$c->setLevel(10);
$i->addEnchantment($c);
$i->setCustomName("§l§eRÌU ÁNH SÁNG");
$pi->addItem($i);
$sender->sendMessage("§aBẠN ĐÃ NHẬN ĐƯỢC RÌU ÁNH SÁNG NHỜ ĐẶC QUYỀN VIP");
break;
               case "xengdianguc":
$p = $sender->getName();
$pi = $sender->getInventory();
$i = Item::get(277, 0 ,1);
$c = Enchantment::getEnchantment(15);
$c->setLevel(10);
$i->addEnchantment($c);
$i->setCustomName("§l§cXẺNG ĐỊA NGỤC");
$pi->addItem($i);
$sender->sendMessage("§bBẠN ĐÃ NHẬN ĐƯỢC XẺNG ĐỊA NGỤC NHỜ ĐẶC QUYỀN VIP");
break;
}
}
}
}


                