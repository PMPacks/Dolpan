<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Vanish extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "vanish", "Ẩn thân khỏi người chơi khác!", "[player]", true, ["v"]);
        $this->setPermission("tool.vanish.use");
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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("tool.vanish.other")){
                $sender->sendMessage($this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
                return false;
            }
        }
        $this->getAPI()->switchVanish($player);
        $player->sendMessage("§b→§aBạn đã được " . ($s = $this->getAPI()->isVanished($player) ? "ẩn thân!" : "tắt ẩn thân!"));
        if($player !== $sender){
            $sender->sendMessage("§b→§aNgười chơi " . TextFormat::AQUA .  $player->getDisplayName() . TextFormat::GREEN . " đã được $s");
        }
        return true;
    }
}
