<?php

namespace PTK\BUYEC;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\enchantment\Enchantment;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase {
	
	public $eco;
	public $enchants;
	
	public function onEnable() {
		$this->eco = EconomyAPI::getInstance();
		
		$this->enchant = ["0:Protection","1:Fire Protection","2:Feather Falling","3:Blast Protection","4:Projectile Protection","5:Thorns","6:Respiration","7:Depth Strider","8:Aqua Affinity","9:Sharpness","10:Smite","11:Bane of Athropods","12:Knockback","13:Fire Aspect","14:Looting","15:Efficiency","16:Silk Touch","17:Unbreaking","18:Fortune","19:Power","20:Punch","21:Flame","22:Infinity","23:Luck of the Sea","24:Lure"];
	}
	
	/*
	!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	!                                !
	!  BUYEC By PTK-KienPham                  !
	!  Không Được Chỉnh Sửa Hoặc Edit    !
	!                                !
	!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	*/
	
	public function onCommand(CommandSender $s, Command $cmd, $label, array $args) {
		
		if($cmd->getName() == "buyec") {
			
		  if(isset($args[0]) && isset($args[1])) {
			  
			  if(is_numeric($args[0])) {
				  
				if(is_numeric($args[1])) {
					
				  if($this->eco->myMoney($s->getName()) >= $args[1] ."000") {
					  
					  
		$enchantLevel = $args[1] <= 10 ? $args[1] : 1;
		$enchantId = $args[0];
		
		
		$enchantment = Enchantment::getEnchantment($enchantId);
		
		if($enchantment->getId() === Enchantment::TYPE_INVALID){
			
			$enchantment = Enchantment::getEnchantmentByName($enchantId);
			
			if($enchantment->getId() === Enchantment::TYPE_INVALID){
	    		$s->sendMessage("§b[§6BUYEC§b]§c Không tìm thấy phù phép này!");
	            $s->sendMessage("§b[§6BUYEC§b]§c Hãy sử dụng lệnh: /listec 1 để xem danh sách phù phép!");
				return true;
			}
		}
		$id = $enchantment->getId();
		$maxLevel = Enchantment::getEnchantMaxLevel($id);
		if($enchantLevel > $maxLevel or $enchantLevel <= 0){
			$s->sendMessage("§b[§6BUYEC§b]§c Cấp Độ Phù Phép Cao Nhất Là :". $maxLevel);
			return true;
		}
		
		$enchantment->setLevel($enchantLevel);
		$item = $s->getInventory()->getItemInHand();
		if($item->getId() <= 0){
			$s->sendMessage("§b[§6BUYEC§b]§c Không tìm thây vật phẩm trên tay bạn!");
			return true;
		}
		
		if(Enchantment::getEnchantAbility($item) === 0){
			$s->sendMessage("§b[§6BUYEC§b]§c Phù phép này không phù hợp với vật phẩm bạn đang cầm!");
			return true;
		}
		$item->addEnchantment($enchantment);
		$s->getInventory()->setItemInHand($item);
						
						$this->eco->reduceMoney($s->getName(), $args[1] ."000");
						
						$s->sendMessage("§b[§6BUYEC§b]§a Phù phép thành công!");
						return true;
				  } else {
					  $s->sendMessage("§b[§6BUYEC§b]§c Bạn không có đủ tiền để phù phép!");
					  return false;
				  }
				} else {
					$s->sendMessage("§b[§6BUYEC§b]§c Cấp độ phải là số!");
					return false;
				}
			  
		  } else {
			  $s->sendMessage("§b[§6BUYEC§b]§c ID phải là số!");
		     return false;
		  }
		} else {
			$s->sendMessage("§b[§6BUYEC§b]§c Hãy sử dụng lệnh: /buyec <id> <cấp độ>");
			$s->sendMessage("§b[§6BUYEC§b]§c Hãy sử dụng lệnh: /listec 1 để xem danh sách phù phép!");
			return false;
		}
		}
		
		if($cmd->getName() == "listec") {
			
		  if(isset($args[0])) {

			 			  $pages = array_chunk($this->enchant, 5);
			  if($args[0] <= count($pages) || $args[0] < 1) {
				  
			  
			  $s->sendMessage("§b[§6BUYEC§b]§c Trang số ". $args[0] ." trên ". count($pages));
			  $s->sendMessage("§b[§6BUYEC§b]§c (Tên trước, ID sau)");
			  foreach($pages[$args[0] - 1] as $enchant) {
				  $is = explode(":", $enchant);
				  $s->sendMessage("§b♦§a ". $is[1] .": ". $is[0]);
			  }
			  $s->sendMessage("§b[§6BUYEC§b]§c Để mua một enchant, thực hiện lệnh: /buyec <ID> <cấp [tối đa là cấp 10]>");
			  return true;
		  } else {
			  $s->sendMessage("§b[§6BUYEC§b]§c Không tìm thấy trang này!");
			  return false;
		  }
		  } else {
			  $s->sendMessage("§b[§6BUYEC§b]§c Hãy sử dụng lệnh: /listec 1 để xem!");
			  return true;
		  }
		}
	}
}