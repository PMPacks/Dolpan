<?php
namespace vip;
use pocketmine\command\Command;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandReader;
use pocketmine\command\CommandExecuter;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\player\PlayerJoinEvent;

class main extends PluginBase implements Listener{
	public function onEnable(){
		@mkdir($this->getDataFolder());
		$this->vip = new Config($this->getDataFolder()."vip.yml",Config::YAML);
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
		$this->getLogger()->info(TextFormat::GREEN."Vip Day Enable");		
	}
	 
	public function onPlayerJoin(PlayerJoinEvent $event){
		$t = $this->vip->getAll();
		$p = $event->getPlayer();
		$e = $p->getdisplayName();
		$n = strtolower($e);
		if(isset($t[$n])){
	    $date1 = strtotime($t[$n]["date"]);
	    $date2 = strtotime(date("y-m-d"));
	    $date3 = ceil(($date2 - $date1)/86400);
	    $date4 = ($t[$n]["day"]-$date3);
		if($date4 < 1){
		    $p->sendMessage('§f•§6§c1§f0§d2§ePE§f• §cVip Của Bạn Đã Hết Hạn!');
			$this->vip->remove($n);
			$this->vip->save();
			$this->getLogger()->info(TextFormat::BLUE.'§f•§c1§f0§d2§ePE§f•'.$n.' đã hết hạn, xóa vip thành công');
			$this->getServer()->dispatchCommand(new ConsoleCommandSender(),'setgroup '.$n.' Guest');
	        }else{
		        $p->sendMessage('§f•§c1§f0§d2§ePE§f• §dVIP bạn còn lại: §a'.$date4.' §dngày');
				$this->getLogger()->info(TextFormat::GREEN.'§f•§c1§f0§d2§ePE§f• VIP '.$n.' đã Online, VIP Còn Lại: '.$date4.' ngày');
	        }
		}
	} 
	 
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		$name = $sender->getName();
		$t = $this->vip->getAll();
		switch($command->getName()){
			case"setvip" && isset($args[0]):
				switch($args[0]){
					case"vip"&& isset($args[1]) && isset($args[2]):
						$date = date("Y-m-d");
						$nameX = strtolower($args[1]);
						$vipp = $this->getServer()->getPlayer($nameX);
					    if($sender->hasPermission('setvip.command.vip')){
							if(!isset($t[$nameX])){
								if($args[2] > 0 && $args[2] < 900){	
									$t[$nameX]["date"] = $date;
									$t[$nameX]["day"] = $args[2];
									$this->vip->setAll($t);
									$this->vip->save();
									$sender->sendMessage('§f•§c1§f0§d2§ePE§f• §aBạn là Vip Trong §e'.$args[2].' §aNgày. Cảm Ơn Bạn Đã Ủng Hộ');
									$this->getServer()->dispatchCommand(new ConsoleCommandSender(),'setgroup '.$nameX.' 1');
									if($vipp instanceof Player) $vipp->sendMessage('§f•§6MineAro§f• §aBạn là Vip Trong §e'.$args[2].' §angày. Cảm Ơn Bạn!!');
										$this->getLogger()->info('§aSuccessfully add §e'.$nameX.' §aas VIP for §e'.$nameX.' §aDay(s)');
										break;
								}else{
									$sender->sendMessage("§f•§6§c1§f0§d2§ePE§f• §cVIP Nhiều Hơn 0 ngày và ít hơn 30 ngày.");
									break;
								}
							}else{
							$sender->sendMessage("§f•§6§c1§f0§d2§ePE§f• §cVIP Member này Chưa Hết Hạn, Dùng §a/vip remove §c Để Xóa Vip Trước");
							break;
						}
						}else{
							$sender->sendMessage("Bạn không có quyền");
							break;
						}
					break;
					
					case"remove" && isset($args[1]):
					    if($sender->hasPermission('setvip.command.remove')){
					    if(isset($t[$args[1]])){
							$vipr = $this->getServer()->getPlayer($args[1]);
							$this->vip->remove($args[1]);
							$this->vip->save();
							$sender->sendMessage("§f•§6MineAro§f• §aSuccessfully remove VIP from player");
							$this->getServer()->dispatchCommand(new ConsoleCommandSender(),'setgroup '.$args[1].' Guest');
							if($vipr instanceof Player) $vipr->sendMessage('§f•§6MineAro§f• §aYour VIP have been removed');
							$this->getLogger()->info('§a Xóa Vip Thành Công Sau§e'.$args[1].'');
							break;
						}else{
							$sender->sendMessage("§f•§6MineAro§f• Người này chưa từng có VIP");
							break;
						}break;
						}else{
							$sender->sendMessage(TextFormat::RED."Bạn không Có Quyền");
							break;
					    }
					break;
					
					case"help":
					    if($sender->hasPermission('setvip.command.help')){
							$sender->sendMessage(TextFormat::GREEN."===Aro VIP Help===");
							$sender->sendMessage(TextFormat::GREEN."/setvip <VipLever> <name> <day(s)>");
							$sender->sendMessage(TextFormat::GREEN."/vip để xem VipLevel");
							$sender->sendMessage(TextFormat::GREEN."/setvip remove <name>");
						break;
						}else{
							$sender->sendMessage(TextFormat::RED."Bạn không Có Quyền");
							break;
					    }
					break;
					
			break;
		}			
	}
}
}