<?php

namespace PTK\MineLevelSystem;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\TextFormat as TF;

class Eventlistener implements Listener
{

	/** @var MineLevelSystem */
	private $ls;
	
	/** @var Array */
	public $message = [
			"ls" => "[".TF::BLUE."MineLevelSystem".TF::WHITE."]"
			];
	
	public function __construct(Main $plugin)
	{
		$this->ls = $plugin;
		$this->ls->getLogger()->info("Đã Chạy: LevelSyetem.Listener");
		$this->ls->getServer()->getPluginManager()->registerEvents($this, $this->ls);
	}


	public function onJoin(PlayerJoinEvent $event)
	{
		$player = $event->getPlayer();
		$name = $player->getName();
		if(!$this->ls->isRegist($name))
		{
			$player->sendMessage($this->message["ls"]."Rất vui khi được gặp bạn, ".$name."Chúc bạn vui vẻ trong server!");
			$player->sendMessage($this->message["ls"].$name."Dữ liệu của bạn đã được tạo thành công!");
			$this->ls->registUser($name);
		}else{
			$player->sendMessage($this->message["ls"]."Chào mừng quay trở lại, ".$name. "Chúc bạn vui vẻ!");
		}
	}


public function onBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		$block = $event->getBlock();
        if($block->getId() === Block::DIAMOND_BLOCK or $block->getId() === Block::EMERALD_BLOCK or $block->getId() === Block::REDSTONE_BLOCK or $block->getId() === Block::GOLD_BLOCK or $block->getId() === Block::IRON_BLOCK or $block->getId() === Block::LAPIS_BLOCK) {
				$dmname = $player->getName();
				$dmexp = $this->ls->getExp($dmname);
				$dmluexp = $this->ls->getLevelUpExp($dmname);
				$delevel = $this->ls->getLevel($dename);
				$gexp = $delevel*2 + 1;
				$ndmexp = $dmexp + $gexp;
				$dmlevel = $this->ls->getLevel($dmname);
				for($pl = 0; $ndmexp >= $dmluexp; $pl++)
				{
					$ndmexp -= $dmluexp;
					$dmluexp += mt_rand(0, 5) + 5;
				}
				$this->ls->addLevel($dmname, $pl);
				$this->ls->setExp($dmname, $ndmexp);
				$this->ls->setLevelUpExp($dmname, $dmluexp);
				$player->sendMessage($this->message["ls"]."Bạn đã nhận được nhận được ".$gexp."kinh nghiệm!");
				if($pl > 0)
				{
					$level = $dmlevel + $pl;
					$this->ls->getServer()->broadcastMessage($this->message["ls"].$dmname." đã được thăng từ cấp ".$dmlevel."→".$level);
				}
			}
		}
	}

