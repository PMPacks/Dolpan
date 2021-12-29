<?php

namespace PTK\Dial;

use pocketmine\utils\TextFormat as T;
use pocketmine\utils\Random;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\level\sound\ExpPickupSound;
use pocketmine\level\sound\PopSound;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\level\sound\GhastSound;
use pocketmine\level\sound\EndermanTeleportSound;
class Main extends PluginBase {
	public $eco;
	
	public function onEnable(){
		$this->eco = EconomyAPI::getInstance();
		$this->getLogger()->info(T::GREEN . "Plugins Enable[Plugin Được Viết Bởi PTK-KienPham");
	}
	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$player->getlevel()->addSound(new EndermanTeleportSound($player));                                                
	}
	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		$player->getlevel()->addSound(new GhastSound($player));
		$player->getlevel()->addSound(new EndermanTeleportSound($player));
	}
	public function onQuit(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		$player->getlevel()->addSound(new EndermanTeleportSound($player));
	}
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
		
		if($cmd->getName() == "dial") {
			if($sender instanceof Player){
				$rand = mt_rand(1, 45);
				$sender->getLevel()->addSound(new AnvilUseSound($sender));
				if($this->eco->reduceMoney($sender->getName(), 2000)){
				switch($rand){
					case 1:
					$sender->sendMessage(T::AQUA ."§b[§bL§aO§6G§6-§aQuay Thưởng§b]:"  . T::YELLOW. " Chúc mừng bạn đã trúng được cúp sắt");
					$sender->getInventory()->addItem(Item::get(257));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					break;
					case 18:
					$sender->sendMessage(T::AQUA . "§b[§bL§aO§6G§6-§aQuay Thưởng§b]:" . T ::YELLOW. " Chúc mừng bạn đã trúng được cúp vàng");
					$sender->getInventory()->addItem(Item::get(285));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					break;
					case 31:
					$sender->sendMessage(T::AQUA . "§b[§bL§aO§6G§6-§aQuay Thưởng§b]:" . T::YELLOW. " Chúc mừng bạn đã trúng được cúp kim cương");
					$sender->getInventory()->addItem(Item::get(278));					
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					break;
					case 41:
					
					$sender->sendMessage(T::AQUA . "§b[§bL§aO§6G§6-§aQuay Thưởng§b]:" . T::YELLOW. " Chúc mừng bạn đã trúng được 5 diamond");
					$sender->getInventory()->addItem(Item::get(264, 0, 5));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					break;
					case 35:
					
					$sender->sendMessage(T::AQUA . "§b[§bL§aO§6G§6-§aQuay Thưởng§b]:" . T::YELLOW. " Chúc mừng bạn đã trúng được 5 Iron");
					$sender->getInventory()->addItem(Item::get(265, 0, 5));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					break;
					case 26:
					
					$sender->sendMessage(T::AQUA . "§b[§bL§aO§6G§6-§aQuay Thưởng§b]:" . T::YELLOW. " Chúc mừng bạn đã trúng được 5 Gold Ingot");
					$sender->getInventory()->addItem(Item::get(266, 0, 5));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					break;
					case 17:
					
					$sender->sendMessage(T::AQUA . "§b[§bL§aO§6G§6-§aQuay Thưởng§b]:" . T::YELLOW. " Bạn là người may mắn nhất bạn đã trúng full set Diamond");
					$sender->getInventory()->addItem(Item::get(310));
					$sender->getInventory()->addItem(Item::get(311));
					$sender->getInventory()->addItem(Item::get(312));
					$sender->getInventory()->addItem(Item::get(313));
					$sender->getInventory()->addItem(Item::get(278));
					$sender->getInventory()->addItem(Item::get(276));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getInventory()->addItem(Item::get(279));
					break;
					case 38:
					
					$sender->sendMessage(T::AQUA . "§b[§bL§aO§6G§6-§aQuay Thưởng§b]:" . T::YELLOW. " Chúc mừng bạn đã trúng được Full Set Gold");
					$sender->getInventory()->addItem(Item::get(314));
					$sender->getInventory()->addItem(Item::get(315));
					$sender->getInventory()->addItem(Item::get(316));
					$sender->getInventory()->addItem(Item::get(317));
					$sender->getInventory()->addItem(Item::get(283));
					$sender->getInventory()->addItem(Item::get(285));
					$sender->getInventory()->addItem(Item::get(286));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					$sender->getLevel()->addSound(new ExpPickupSound($sender));
					break;
					default:
					$sender->sendMessage(T::AQUA . "§b[§bL§aO§6G§6-§aQuay Thưởng§b]:" . T::YELLOW. " bạn không may mắn trong ngày hôm nay rồi");
					break;
				}
				}else{
					$sender->sendMessage(T::RED."Bạn không đủ tiền để quay thưởng [ you not enought money to do this ]");
					return true;
				}
			}
		}
	}
}