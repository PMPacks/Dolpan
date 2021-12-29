<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ClearInventory extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "clearinventory", "Xóa sạch túi đồ của bạn hoặc ai đó", "<tên người chơi>", true, ["ci", "clean", "clearinvent"]);
        $this->setPermission("tool.clearinventory.use");
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
        if((!isset($args[0]) &&  !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("tool.clearinventory.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
                return false;
            }
        }
        if(($gm = $player->getGamemode()) === 1 || $gm === 3){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] " . (isset($args[0]) ? "Người chơi§b " . $player->getDisplayName() : "Bạn ") . " §cđang ở " . $this->getAPI()->getServer()->getGamemodeString($gm) . " nên không thể xóa!");
            return false;
        }
        $player->getInventory()->clearAll();
        $player->sendMessage(TextFormat::AQUA . "§b→ §aTúi đồ của bạn đã được xóa!");
        if($player !== $sender){
            $sender->sendMessage("§b→ §aTúi đồ của người chơi " . TextFormat::AQUA . $player->getDisplayName() . " §ađã được xóa!");
        }
        return true;
    }
}
