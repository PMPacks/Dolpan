<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Repair extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "repair", "Sửa đồ của bạn", "[all|hand]", false, ["fix"]);
        $this->setPermission("tool.repair.use");
    }

    /**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $alias, array $args): bool{
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(isset($args[0])) {
            $a = strtolower($args[0]);
        }
        if(!($a === "hand" || $a === "all")){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if($a === "all"){
            if(!$sender->hasPermission("tool.repair.all")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }
            foreach($sender->getInventory()->getContents() as $item){
                $inventory = $sender->getInventory();
			$item = $inventory->getItem();
			$item->setDamage(0);
			$inventory->setItem($item);
            }
            $m = TextFormat::GREEN . "§b→§aTất cả vật phẳm trong kho đồ của bạn đã được sửa!";
            if($sender->hasPermission("tool.repair.armor")){
                foreach($sender->getInventory()->getArmorContents() as $item){
                    $inventory = $sender->getInventory();
			$item = $inventory->getItem();
			$item->setDamage(0);
			$inventory->setItem($item);
                }
                $m .= TextFormat::AQUA . "§b→§aBao gồm cả áo giáp!";
            }
        }else{
            if(!$this->getAPI()->isRepairable($sender->getInventory()->getItemInHand())){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Vật phẩm này không thể sửa!!");
                return false;
            }
			$inventory = $sender->getInventory();
			$item = $inventory->getItemInHand();
			$item->setDamage(0);
			$inventory->setItemInHand($item);
            $m = TextFormat::GREEN . "§b→§aVật phẳm trên tay của bạn đã được sửa!";
        }
        $sender->sendMessage($m);
        return true;
    }
}
