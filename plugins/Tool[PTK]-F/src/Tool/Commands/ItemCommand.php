<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ItemCommand extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "item", "Đưa 1 vật phẩm nào đó cho bạn", "<item[:damage]> [amount]", false, ["i"]);
        $this->setPermission("tool.item");
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
        if(!$sender instanceof Player || (count($args) < 1 || count($args) > 2)){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(($gm = $sender->getGamemode()) === Player::CREATIVE || $gm === Player::SPECTATOR){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Bạn đang ở " . $this->getAPI()->getServer()->getGamemodeString($gm) . "§c!");
            return false;
        }

        //Getting the item...
        $item = $this->getAPI()->getItem($item_name = array_shift($args));

        if($item->getId() === Item::AIR){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Không tìm thấy vật phẩm nào tên là \"" . $item_name . "\"");
            return false;
        }elseif(!$sender->hasPermission("tool.itemspawn.item-all") && !$sender->hasPermission("tool.itemspawn.item-" . $item->getName() && !$sender->hasPermission("tool.itemspawn.item-" . $item->getId()))){
            $sender->sendMessage(TextFormat::RED . "You can't spawn this item");
            return false;
        }

        //Setting the amount...
        $amount = array_shift($args);
        $item->setCount(isset($amount) && is_numeric($amount) ? $amount : ($sender->hasPermission("tool.oversizedstacks") ? $this->getPlugin()->getConfig()->get("oversized-stacks") : $item->getMaxStackSize()));

        //Getting other values... TODO
        /*foreach($args as $a){
            //Example
            if(stripos(strtolower($a), "color") !== false){
                $v = explode(":", $a);
                $color = $v[1];
            }
        }*/

        //Giving the item...
        $sender->getInventory()->setItem($sender->getInventory()->firstEmpty(), $item);
        $sender->sendMessage(TextFormat::YELLOW . "§b→§aĐã đưa " . TextFormat::AQUA . $item->getCount() . TextFormat::YELLOW . "§a vật phẩm " . TextFormat::AQUA . ($item->getName() === "Unknown" ? $item_name : $item->getName()) . "§a cho bạn!");
        return false;
    }
}
