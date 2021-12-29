<?php

namespace onebone\itemcloud;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\CallbackTask;

class MainClass extends PluginBase implements Listener{
	/**
	 * @var MainClass
	 */
	private static $instance;

	/**
	 * @var ItemCloud[]
	 */
	private $clouds;

	/**
	 * @return MainClass
	 */
	public static function getInstance(){
		return self::$instance;
	}

	/**
	 * @param Player|string $player
	 *
	 * @return ItemCloud|bool
	 */
	public function getCloudForPlayer($player){
		if($player instanceof Player){
			$player = $player->getName();
		}
		$player = strtolower($player);

		if(isset($this->clouds[$player])){
			return $this->clouds[$player];
		}
		return false;
	}

	/**************************   Below part is a non-API part   ***********************************/

	public function onEnable(){
		if(!self::$instance instanceof MainClass){
			self::$instance = $this;
		}
		@mkdir($this->getDataFolder());
		if(!is_file($this->getDataFolder()."ItemCloud.dat")){
			file_put_contents($this->getDataFolder()."ItemCloud.dat", serialize([]));
		}
		$data = unserialize(file_get_contents($this->getDataFolder()."ItemCloud.dat"));

		$this->saveDefaultConfig();
		if(is_numeric($interval = $this->getConfig()->get("auto-save-interval"))){
			$this->getServer()->getScheduler()->scheduleDelayedRepeatingTask(new CallbackTask([$this, "save"], []), $interval * 1200, 1);
		}
		
		$this->clouds = [];
		foreach($data as $datam){
			$this->clouds[$datam[1]] = new ItemCloud($datam[0], $datam[1]);
		}
	}

	public function onCommand(CommandSender $sender, Command $command, $label, array $params){
		switch($command->getName()){
			case "itemcloud":
				if(!$sender instanceof Player){
					$sender->sendMessage("Please run this command in-game");
					return true;
				}
				$sub = array_shift($params);
				switch($sub){
					case "register":
						if(isset($this->clouds[strtolower($sender->getName())])){
							$sender->sendMessage("[Ngân Hàng Vật Phẩm] Bạn đã có một tài khoản từ trước");
							break;
						}
						$this->clouds[strtolower($sender->getName())] = new ItemCloud([], $sender->getName());
						$sender->sendMessage("[Ngân Hàng Vật Phẩm] Bạn đã đăng kí tài khoản thành công");
						break;
					case "upload":
						if(!isset($this->clouds[strtolower($sender->getName())])){
							$sender->sendMessage("[Ngân Hàng Vật Phẩm] Bạn hãy đăng kí một tài khoản trước.");
							break;
						}
						$id = array_shift($params);
						$amount = array_shift($params);
						if(trim($id) === "" or !is_numeric($amount)){
							usage:
							$sender->sendMessage("Sử dụng lệnh: /itemcloud upload <ID đồ[:thứ tự]> <số lượng>");
							break;
						}
						$amount = (int) $amount;
						$e = explode(":", $id);
						if(!isset($e[1])){
							$e[1] = 0;
						}
						if(!is_numeric($e[0]) or !is_numeric($e[1])){
							goto usage;
						}

						$count = 0;
						foreach($sender->getInventory()->getContents() as $item){
							if($item->getID() == $e[0] and $item->getDamage() == $e[1]){
								$count += $item->getCount();
							}
						}
						if($amount <= $count){
							$this->clouds[strtolower($sender->getName())]->addItem($e[0], $e[1], $amount, true);
							$sender->sendMessage("[Ngân Hàng Vật Phẩm] Đã upload vật phẩm lên tài khoản của bạn.");
						}else{
							$sender->sendMessage("[Ngân Hàng Vật Phẩm] Bạn không có vật phẩm để upload.");
						}
						break;
					case "download":
						$name = strtolower($sender->getName());
						if(!isset($this->clouds[$name])){
							$sender->sendMessage("[Ngân Hàng Vật Phẩm] Hãy đăng kí tài khoản trước.");
							break;
						}
						$id = array_shift($params);
						$amount = array_shift($params);
						if(trim($id) === "" or !is_numeric($amount)){
							usage2:
							$sender->sendMessage("Sử dụng: /itemcloud download <ID đồ[:thứ tự]> <số lượng>");
							break;
						}
						$amount = (int)$amount;
						$e = explode(":", $id);
						if(!isset($e[1])){
							$e[1] = 0;
						}
						if(!is_numeric($e[0]) or !is_numeric($e[1])){
							goto usage2;
						}
						
						if(!$this->clouds[$name]->itemExists($e[0], $e[1], $amount)){
							$sender->sendMessage("[Ngân Hàng Vật Phẩm] Bạn không có vật phẩm trong tài khoản.");
							break;
						}
						$item = Item::get((int)$e[0], (int)$e[1], $amount);
						if($sender->getInventory()->canAddItem($item)){
							$this->clouds[$name]->removeItem($e[0], $e[1], $amount);
							$sender->getInventory()->addItem($item);
							$sender->sendMessage("[Ngân Hàng Vật Phẩm] Bạn đã download vật phẩm xuống.");
						}else{
							$sender->sendMessage("[Ngân Hàng Vật Phẩm] Bạn không có đủ số lượng vật phẩm để download.");
						}
						break;
					case "list":
						$name = strtolower($sender->getName());
						if(!isset($this->clouds[$name])){
							$sender->sendMessage("[Ngân Hàng Vật Phẩm] Hãy đăng kí tài khoản trước.");
							break;
						}
						$output = "[ItemCloud] Item list : \n";
						foreach($this->clouds[$name]->getItems() as $item => $count){
							$output .= "$item : $count\n";
						}
						$sender->sendMessage($output);
						break;
					case "count":
						$name = strtolower($sender->getName());
						if(!isset($this->clouds[$name])){
							$sender->sendMessage("[Ngân Hàng Vật Phẩm] Hãy đăng kí tài khoản trước.");
							break;
						}
						$id = array_shift($params);
						$e = explode(":", $id);
						if(!isset($e[1])){
							$e[1] = 0;
						}

						if(($count = $this->clouds[$name]->getCount($e[0], $e[1])) === false){
							$sender->sendMessage("[Ngân Hàng Vật Phẩm] Không có ".$e[0].":".$e[1]." trong tài khoản của bạn.");
							break;
						}else{
							$sender->sendMessage("[Ngân Hàng Vật Phẩm] Số lượng của ".$e[0].":".$e[1]." = ".$count);
						}
						break;
					default:
						$sender->sendMessage("[Ngân Hàng Vật Phẩm] Sử dụng: ".$command->getUsage());
				}
				return true;
		}
		return false;
	}

	public function save(){
		$save = [];
		foreach($this->clouds as $cloud){
			$save[] = $cloud->getAll();
		}
		file_put_contents($this->getDataFolder()."ItemCloud.dat", serialize($save));
	}

	public function onDisable(){
		$this->save();
		$this->clouds = [];
	}
}