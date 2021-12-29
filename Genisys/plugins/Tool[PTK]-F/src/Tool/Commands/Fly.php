<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Fly extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "fly", "Bặt hoặc tắt chế độ bay!", "[player]");
        $this->setPermission("tool.fly.use");
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
            if(!$sender->hasPermission("tool.fly.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
                return false;
            }
        }
        $this->getAPI()->switchCanFly($player);
        $player->sendMessage(TextFormat::YELLOW . "§b→§aChế độ bay " . ($this->getAPI()->canFly($player) ? " §6đã được kích hoạt" : " §6đã được tắt") . "!");
        if($player !== $sender){
            $sender->sendMessage("§b→§aChế độ bay " . ($this->getAPI()->canFly($player) ? " §6đã được kích hoạt" : " §6đã được tắt") . " §acho người chơi " . TextFormat::AQUA . $player->getDisplayName());
        }
        return true;
    }
}