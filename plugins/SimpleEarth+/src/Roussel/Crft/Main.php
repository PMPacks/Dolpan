<?php

namespace Roussel\Crft;

use Roussel\Crft\RoussGenerator\KingForest;
use Roussel\Crft\RoussGenerator\SeaWild;
use Roussel\Crft\utils\mc;
use Roussel\Crft\utils\MPMU;
use Roussel\Crft\utils\PluginCallbackTask;

use pocketmine\block\Block;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\level\ChunkPopulateEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\generator\biome\Biome;
use pocketmine\level\generator\Generator;
use Roussel\Crft\populators\HGTree;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Chest;
use pocketmine\tile\Tile;
use pocketmine\utils\Config;
use pocketmine\utils\Random;
//portal
use pocketmine\command\CommandExecutor;
use pocketmine\utils\TextFormat;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\block\BlockPlaceEvent;

class Main extends PluginBase implements CommandExecutor,Listener {
	const PREFIX = "§2[§6Simple§3Earth§c+§2]§r§f ";
	protected $portals;
	protected $max_dist;
	protected $border;
	protected $center;
	protected $corner;
	protected $tweak;
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		Generator::addGenerator(KingForest::class, "forest");
		Generator::addGenerator(SeaWild::class, "sea");
		@mkdir($this->getDataFolder());
		$defaults = [
			"version" => $this->getDescription()->getVersion(),
			"max-dist" => 1,
			"border" => Block::AIR,
			"center" => Block::PORTAL,
			"corner" => Block::AIR,
		];
		$cfg = (new Config($this->getDataFolder()."wpconfig.yml",
										  Config::YAML,$defaults))->getAll();
		$this->max_dist = $cfg["max-dist"];
		$this->border = $cfg["border"];
		$this->center = $cfg["center"];
		$this->corner = $cfg["corner"];
		$this->portals=(new Config($this->getDataFolder()."wpportals.yml",
											Config::YAML,[]))->getAll();
		if ($this->getServer()->getPluginManager()->getPlugin("FastTransfer")){
			$this->getLogger()->info(self::PREFIX . "FastTransfer plugin available!");
		}
		$this->getServer()->getLogger()->notice(base64_decode('Q29weXJpZ2h0IG5vdGljZQ0KQ29weXJpZ2h0IMKpIDIwMTcgUm91c3NlbENyZnQNCg0KUm91c3NlbENyZnQgb3IgaXRzIGxpY2Vuc29ycyBhcmUgdGhlIG93bmVycyBvZiBhbGwgaW50ZWxsZWN0dWFsIGFuZCBpbmR1c3RyaWFsIHByb3BlcnR5IHJpZ2h0cyBvZiBhbGwgbWF0ZXJpYWwgcG9zdGVkIG9uIHRoaXMgUm91c3NlbENyZnQgWW91VHViZSBjaGFubmVsIChpbmNsdWRpbmcgdGhpcyBzb3VyY2UgY29kZSkNCg0KUm91c3NlbENyZiBncmFudHMgeW91IGEgdW5pdmVyc2FsLCBub24tZXhjbHVzaXZlLCBmcmVlbHkgdXNhYmxlIGFuZCByZXZvY2FibGUgbGljZW5zZSBhdCBhbnkgdGltZSB0bzoNCg0KRG93bmxvYWQgYW5kIHN0b3JlIGFuZCBtb2RpZnkgYSBjb3B5IG9mIHRoaXMgY29kZSBpbiB0aGUgaW50ZXJuYWwgb3IgZXh0ZXJuYWwgbWVtb3J5IG9mIHlvdXIgbW9iaWxlIGRldmljZSBvciBkZXNrdG9wIGFzIGxvbmcgYXMgaXQgaXMsIHNvbGVseSBhbmQgZXhjbHVzaXZlbHksIGZvciB5b3VyIHBlcnNvbmFsIGFuZCBwcml2YXRlIHVzZSBhbmQgaXMgbm90IGludGVuZGVkIGZvciBkaXN0cmlidXRhYmxlIHVzZS4NCg0KV2UgZG8gbm90IGF1dGhvcml6ZSBhbnkgb3RoZXIgcmlnaHRzIHRvIHRoaXMgY29kZS4NCiBUaGlzIG1lYW5zIHRoYXQgYWxsIHJpZ2h0cyBhcmUgcmVzZXJ2ZWQuDQpJbiBjYXNlIG9mIGRvdWJ0IHlvdSwgYXMgYSB2aXNpdG9yIHRvIG91ciBzaXRlLCB1bmRlcnN0YW5kIGFuZCBhY2NlcHQgdGhhdDogeW91IGNhbiBub3QgcHVibGlzaCwgcmUtcHVibGlzaCwgZGlzdHJpYnV0ZSwgcmUtZGlzdHJpYnV0ZSwgcGVyZm9ybSBhbnkgdHlwZSBvZiBicm9hZGNhc3Qgb3IgcmUtYnJvYWRjYXN0IGJ5IG1lYW5zIG9mIHdhdmVzIG9yIGFueSBvdGhlciB0cmFuc21pc3Npb24gdGVjaG5vbG9neSwgZGlzcGxheSBJbiBwdWJsaWMgb3IgcHJpdmF0ZSBwbGFjZXMgYnkgdXNpbmcgdmlzdWFsIGVsZW1lbnRzIG9yIGNvbW11bml0eSBsaXN0ZW5pbmcgY29kZSBwdWJsaXNoZWQgd2l0aG91dCBvdXIgcHJpb3Igd3JpdHRlbiBwZXJtaXNzaW9uLg0KDQpTcGVjaWZpYw0KDQpJZiB5b3Ugd2lzaCB0byB1c2UgdGhlIG1hdGVyaWFscyBwdWJsaXNoZWQgb24gdGhpcyB5b3V0dWJlIGNoYW5uZWwgZm9yIGEgdXNlIG90aGVyIHRoYW4gdGhvc2UgYXV0aG9yaXplZCBpbiB0aGUgVXNlIExpY2Vuc2Ugc2VjdGlvbiBwbGVhc2UgY29udGFjdCB1cyBpbiB3cml0aW5nIHRocm91Z2g6DQpQYXlwYWx3aWxpcm9kaW5AZ21haWwuY29tDQoNClNlcmlvdXNseSBhbmQgYWN0aXZlbHkgd2UgZmlnaHQgYWdhaW5zdCB0aGUgdW5hdXRob3JpemVkIHVzZSBvZiB0aGlzIENvZGUgYW5kIHRoZSBtYXRlcmlhbHMgaW4gdGhlIHB1Ymxpc2hlZC4NCldlIGluZm9ybSB5b3UgdGhhdCBhbGwgdGhlIGNvbnRlbnRzIGluY2x1ZGVkIGluIHRoaXMgd2Vic2l0ZSBhcmUgcmVnaXN0ZXJlZCBpbiB0aGUgcmVnaXN0cnkgb2YgaW50ZWxsZWN0dWFsIHByb3BlcnR5IG1hbmFnZWQgYnkgRGlnaXRhbCBNZWRpYSBSaWdodHMuDQoNClRoZSBkZXRlY3Rpb24gb2YgYW4gdW5hdXRob3JpemVkIHVzZSBieSB5b3Ugb2YgdGhpcyBjb2RlIG1heSBsZWFkIHRvIGxlZ2FsIGFjdGlvbiBhZ2FpbnN0IHlvdSwgaW5jbHVkaW5nIGVjb25vbWljIGNsYWltcyB3aXRob3V0IHByZWp1ZGljZSB0byB0aGUgaW5pdGlhdGlvbiBvZiBhIHByb2NlZHVyZSB0byByZXF1ZXN0IHRoZSByZW1vdmFsIG9mIGNvbnRlbnQgdGhhdCB2aW9sYXRlcyB0aGUgdGVybXMgZXN0YWJsaXNoZWQgaW4gdGhlIFNlY3Rpb24gb2YgdGhlIExpY2Vuc2UgdG8gVXNlIHdpdGhvdXQgZXhjbHVkaW5nIHRoZSBvcGVuaW5nIG9mIGNhc2VzIGZvciB2aW9sYXRpb24gb2YgdGhlIERpZ2l0YWwgTWlsbGVuaXVtIENvcHlyaWdodCBBY3QgYWdhaW5zdCB0aGUgbWFpbiBJbnRlcm5ldCBpbmRleGVzLg0KSWYgeW91IGRldGVjdCBhbnkgdHlwZSBvZiBpbXByb3BlciBvciB1bmF1dGhvcml6ZWQgdXNlIG9mIHRoZSBjb250ZW50IHB1Ymxpc2hlZCBvbiBvdXIgd2Vic2l0ZSwgcGxlYXNlIGluZm9ybSB2aWEgZW1haWwgdGhyb3VnaDoNClBheXBhbHdpbGlyb2RpbkBnbWFpbC5jb20NCg0KVGhpcyBjb3B5cmlnaHQgbm90aWNlIGhhcyBiZWVuIHByb3ZpZGVkIGZvciB0aGUgZnJlZSB1c2Ugb2YgdGhlIGVudGlyZSBJbnRlcm5ldCBjb21tdW5pdHkgcmVzcGVjdGluZyB0aGUgb2JsaWdhdGlvbiBvZiBjaXRpbmcgdGhlIHNvdXJjZSBhbmQgbm90IGFsdGVyaW5nIGl0cyBvcmlnaW5hbCBjb250ZW50LiBGb3IgbW9yZSBpbmZvcm1hdGlvbiBvbiBob3cgdG8gcmVnaXN0ZXIgYW5kIHByb3RlY3QgdGhlIGNvcHlyaWdodCBvZiB5b3VyIEludGVybmV0IHB1YmxpY2F0aW9ucyB2aXNpdCBvciBjb250YWN0IERpZ2l0YWwgTWVkaWEgUmlnaHRzOg0KDQpIdHRwOi8vd3d3LmRtcmlnaHRzLmNvbS8NCkluZm9AZG1yaWdodHMuY29t'));
	}

	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args): bool {
		switch ($cmd->getName()) {
			case "helpseplus" : {
			    if (!$sender->hasPermission("help.worldplus.cmd") && !$sender->hasPermission("all.worldplus.admin")) return true;
					$sender->sendMessage ("§2[§6Simple§3Earth§c+§2]§r§f Created by RousselCrft and WiliRodin.\n use §2/createforest <nameforworld>§r for create a new world with forest generator.\n use §2/createsea <nameforworld>§r for create a new world with sea generator.\n use §2/goto§r for teleport to another world.\n use §2/portal <nameofworld>§r for create a portal to another world.\n §2[§6Simple§3Earth§c+§2] §rSuscribe to my channel, Search RousselCrft on §cYoutube");
				break;
			}
			case "createforest":{
				if (!$sender->hasPermission("wpcforest.worldplus.cmd") && !$sender->hasPermission("all.worldplus.admin")) return true;
				$name = $args[0];
				$generator = Generator::getGenerator("forest");
				$generatorName = "King Forest";
				$seed = $this->generateRandomSeed();
				$this->getServer()->broadcastMessage(self::PREFIX . "§aGenerating level $name with generator $generatorName");
				$this->getServer()->generateLevel($name, $seed, $generator);
				$this->getServer()->loadLevel($name);
				return true;
			}
			case "createsea":{
				if (!$sender->hasPermission("wpcsea.worldplus.cmd") && !$sender->hasPermission("all.worldplus.admin")) return true;
				$name = $args[0];
				$generator = Generator::getGenerator("sea");
				$generatorName = "Sea Wild";
				$seed = $this->generateRandomSeed();
				$this->getServer()->broadcastMessage(self::PREFIX . "§aGenerating level $name with generator $generatorName");
				$this->getServer()->generateLevel($name, $seed, $generator);
				$this->getServer()->loadLevel($name);
				return true;
			}
			case "goto":{
				if (!$sender->hasPermission("wptp.worldplus.cmd") && !$sender->hasPermission("all.worldplus.admin")) return true;
				if(isset($args[0])) {
					if(is_null($this->getServer()->getLevelByName($args[0]))) {
						$this->getServer()->loadLevel($args[0]);
						if(is_null($this->getServer()->getLevelByName($args[0]))) {
							$sender->sendMessage(self::PREFIX . "Could not find level {$args[0]}.");
							return false;
						}
					}
					$sender->teleport(\pocketmine\level\Position::fromObject($sender, $this->getServer()->getLevelByName($args[0])));
					$sender->sendMessage(self::PREFIX . "§aTeleporting to {$args[0]}...");
					return true;
				} else {
					return false;
				}
				break;
			}
			case "portal":{
				if (!$sender->hasPermission("wpportal.worldplus.cmd") && !$sender->hasPermission("all.worldplus.admin")) return true;
				
				$dest = $this->checkTarget($args);
				if (!$dest) {
					$sender->sendMessage(self::PREFIX . "Invalid target for portal");
					return true;
				}
				$bl = $this->targetPos($sender,$sender->getDirectionVector());
				list($bb1,$bb2) = $this->buildPortal($bl,$sender->getDirectionVector());
				$lv = $sender->getLevel()->getName();
				if (!isset($this->portals[$lv])) $this->portals[$lv] = [];
				$this->portals[$lv][] = [ $bb1, $bb2, $args ];
				$this->saveCfg();
				return true;
			}
		}
		return false;
	}

	public function generateRandomSeed(): int {
		return (int)round(rand(0, round(time()) / memory_get_usage(true)) * (int)str_shuffle("127469453645108") / (int)str_shuffle("12746945364"));
	}
	private function checkLevel($w) {
		if (!$this->getServer()->isLevelGenerated($w)) return null;
		if (!$this->getServer()->isLevelLoaded($w)) {
			if (!$this->getServer()->loadLevel($w)) return null;
		}
		return $this->getServer()->getLevelByName($w);
	}

	private function checkTarget($args) {
		switch (count($args)) {
			case 1:
				$ft_server = explode(":",$args[0],2);
				if (count($ft_server) == 2 && !empty($ft_server[0]) &&
					 is_numeric($ft_server[1])) {
					// This is a Fast Transfer target!
					return $ft_server;
				}
				list($world) = $args;
				$l = $this->checkLevel($world);
				if ($l) return $l->getSafeSpawn();
				return null;
			case 3:
				list($x,$y,$z) = $args;
				if (is_numeric($x) && is_numeric($y) && is_numeric($z)) {
					return new Vector3($x,$y,$z);
				}
				return null;
			case 4:
				list($world,$x,$y,$z) = $args;
				$l = $this->checkLevel($world);
				if ($l && is_numeric($x) && is_numeric($y) && is_numeric($z)) {
					return new Position($x,$y,$z,$l);
				}
				return null;
		}
		return null;
	}

	protected function targetPos($pos,$dir) {
		$lv = $pos->getLevel();
		for($start=new Vector3($pos->getX(),$pos->getY(),$pos->getZ());
			 $start->distance($pos) < $this->max_dist ;
			 $pos = $pos->add($dir)) {
			$block = $lv->getBlock($pos->floor());
			if ($block->getId() != 0) break;
		}
		while ($block->getId() !=0) {
			$block = $block->getSide(Vector3::SIDE_UP);
		}
		return $block;
	}

	protected function buildPortal($center,$dir) {
		$lv = $center->getLevel();
		$x = $center->getX();
		$y = $center->getY();
		$z = $center->getZ();

		$border = Block::get($this->border);
		$center = Block::get($this->center);

		$x_off = $z_off = 0; $mx_off=0; $mz_off = 0;
		if (abs($dir->getX()) > abs(($dir->getZ()))) {
			$x_off = 0; $z_off = 1;
			$mx_off = 1; $mz_off =0;

			$corner1 = Block::get($this->corner,3);
			$corner2 = Block::get($this->corner,2);
			$corner3 = Block::get($this->corner,7);
			$corner4 = Block::get($this->corner,6);
			$front = Block::get($this->corner,1);
			$back = Block::get($this->corner,0);
		} else {
			$x_off = 1; $z_off = 0;
			$mx_off = 0; $mz_off =1;

			$corner1 = Block::get($this->corner,1);
			$corner2 = Block::get($this->corner,0);
			$corner3 = Block::get($this->corner,5);
			$corner4 = Block::get($this->corner,4);
			$front = Block::get($this->corner,3);
			$back = Block::get($this->corner,2);
		}

		// Top of the portal
		$lv->setBlock(new Vector3($x,$y+4,$z),$border);
		$lv->setBlock(new Vector3($x+$x_off,$y+4,$z+$z_off),$border);
		$lv->setBlock(new Vector3($x-$x_off,$y+4,$z-$z_off),$border);
		$lv->setBlock(new Vector3($x+$x_off*2,$y+4,$z+$z_off*2),$corner1);
		$lv->setBlock(new Vector3($x-$x_off*2,$y+4,$z-$z_off*2),$corner2);

		// Bottom of it (This makes sure the water doesn't leak...)
		$lv->setBlock(new Vector3($x,$y-1,$z),$border);
		$lv->setBlock(new Vector3($x+$x_off,$y-1,$z+$z_off),$border);
		$lv->setBlock(new Vector3($x-$x_off,$y-1,$z-$z_off),$border);

		// Base of the portal (rounded corners)
		$lv->setBlock(new Vector3($x+$x_off*2,$y,$z+$z_off*2),$corner3);
		$lv->setBlock(new Vector3($x-$x_off*2,$y,$z-$z_off*2),$corner4);
		$lv->setBlock(new Vector3($x,$y,$z),$center);
		$lv->setBlock(new Vector3($x+$x_off,$y,$z+$z_off),$center);
		$lv->setBlock(new Vector3($x-$x_off,$y,$z-$z_off),$center);

		// Base of the portal (front steps)
		$lv->setBlock(new Vector3($x+$mx_off,$y,$z+$mz_off),$front);
		$lv->setBlock(new Vector3($x+$mx_off+$x_off,$y,$z+$mz_off+$z_off),$front);
		$lv->setBlock(new Vector3($x+$mx_off-$x_off,$y,$z+$mz_off-$z_off),$front);

		// Base of the portal (back steps)
		$lv->setBlock(new Vector3($x-$mx_off,$y,$z-$mz_off),$back);
		$lv->setBlock(new Vector3($x-$mx_off+$x_off,$y,$z-$mz_off+$z_off),$back);
		$lv->setBlock(new Vector3($x-$mx_off-$x_off,$y,$z-$mz_off-$z_off),$back);

		// Middle of the portal (side column and center water)
		for ($i=1;$i<=3;++$i) {
			$lv->setBlock(new Vector3($x-$x_off*2,$y+$i,$z-$z_off*2),$border);
			$lv->setBlock(new Vector3($x+$x_off*2,$y+$i,$z+$z_off*2),$border);
			for($j=-1;$j<=1;++$j) {
				$lv->setBlock(new Vector3($x+$x_off*$j,$y+$i,$z+$z_off*$j),$center);
			}
		}

		$bb1 = [ $x-$x_off, $y, $z-$z_off, $x+$x_off+1, $y+4,$z+$z_off+1 ];
		$bb2 = [ $x-$x_off*2, $y, $z-$z_off*2, $x+$x_off*2+1, $y+5,$z+$z_off*2+1 ];
		return [$bb1,$bb2];
	}

	protected function saveCfg() {
		$yaml=new Config($this->getDataFolder()."wpportals.yml",Config::YAML,[]);
		$yaml->setAll($this->portals);
		$yaml->save();
	}

	public function onQuit(PlayerQuitEvent $ev) {
		$n = strtolower($ev->getPlayer()->getName());
		if (isset($this->tweak[$n])) unset($this->tweak[$n]);
	}

	public function onMove(PlayerMoveEvent $ev) {
		if ($ev->isCancelled()) return;
		$pl = $ev->getPlayer();
		$l = $pl->getLevel();
		$world = $l->getName();

		if (!isset($this->portals[$world])) return;

		$x = $ev->getTo()->getX();
		$y = $ev->getTo()->getY();
		$z = $ev->getTo()->getZ();

		foreach ($this->portals[$world] as $p) {
			list($bb1,$bb2,$target) = $p;
			if ($bb1[0] <= $x && $bb1[1] <= $y && $bb1[2] <= $z &&
				 $x <= $bb1[3] && $y <= $bb1[4] && $z <= $bb1[5]) {

				$dest = $this->checkTarget($target);
				if (!$dest) {
					$pl->sendMessage(self::PREFIX . "Nothing happens!");
					return;
				}
				$n = strtolower($pl->getName());
				$now = time();
				if (isset($this->tweak[$n])) {
					// Already in here...
					if ($this->tweak[$n][0] > $now) return;
				}
				$this->tweak[$n] = [ $now + 3, $dest ];
				$this->getServer()->getScheduler()->scheduleDelayedTask(
					new PluginCallbackTask($this,[$this,"portalActiveSg1"],[$n]),
					1);
				return;
			}
		}
	}
	public function portalActiveSg1($n) {
		if (!isset($this->tweak[$n])) return;
		$pl = $this->getServer()->getPlayer($n);
		if ($pl === null) return;
		list(,$dest) = $this->tweak[$n];
		unset($this->tweak[$n]);
		if ($dest instanceof Vector3) {
			$pl->sendMessage(self::PREFIX . "Teleporting...");
			$pl->teleport($dest);
			return;
		}
		// If it is not a position... It is a FAST TRANSFER!

		$ft = $this->getServer()->getPluginManager()->getPlugin("FastTransfer");
		if (!$ft) {
			$this->getLogger()->error(TextFormat::RED . self::PREFIX . "FAST TRANSFER NOT INSTALLED");
			$pl->sendMessage(mc::_("Nothing happens!"));
			$pl->sendMessage(TextFormat::RED . self::PREFIX . "Somebody removed FastTransfer!");
			return;
		}
		// First we teleport to spawn to make sure that we do not enter
		// this server in the portal location!
		

		list($addr,$port) = $dest;
		$this->getLogger()->info(TextFormat::RED . self::PREFIX . "FastTransfer being used hope it works!");
		
		$ft->transferPlayer($pl,$addr,$port);
	}

	/**
	 * @priority HIGH
	 */
	public function onBlockBreak(BlockBreakEvent $ev){
		if ($ev->isCancelled()) return;
		$bl = $ev->getBlock();
		$l = $bl->getLevel();
		if (!$l) return;
		$world = $l->getName();
		if (!isset($this->portals[$world])) return;

		$x = $bl->getX();
		$y = $bl->getY();
		$z = $bl->getZ();

		foreach ($this->portals[$world] as $i=>$p) {
			list($bb1,$bb2,$target) = $p;
			if ($bb2[0] <= $x && $bb2[1] <= $y && $bb2[2] <= $z &&
				 $x <= $bb2[3] && $y <= $bb2[4] && $z <= $bb2[5]) {
				// Breaking a portal!
				$pl = $ev->getPlayer();
				if (!$sender->hasPermission("breakportal.worldplus.cmd") && !$sender->hasPermission("all.worldplus.admin")) return true; {
					$ev->setCancelled();
					$pl->sendMessage(self::PREFIX . "You are not allowed to do that!");
					return;
				}
				$air = Block::get(Block::AIR);
				for($bx=$bb1[0];$bx<$bb1[3];$bx++) {
					for($by=$bb1[1];$by<$bb1[4];$by++) {
						for($bz=$bb1[2];$bz<$bb1[5];$bz++) {
							$l->setBlock(new Vector3($bx,$by,$bz),$air);
						}
					}
				}
				$pl->sendMessage(self::PREFIX . "Portal broken!");
				unset($this->portals[$world][$i]);
				$this->saveCfg();
				return;
			}
		}
	}
	
	//Final
}
