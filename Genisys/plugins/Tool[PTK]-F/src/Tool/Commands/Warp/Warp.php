<?php
namespace Tool\Commands\Warp;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Warp extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "warp", "Dịch chuyển đến 1 warp", "[[name] [player]]", true, ["warps"]);
        $this->setPermission("tool.warp.use");
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
        if($this->getAPI()->getToolPlugin()->getConfig()->get("warps") !== true) {
            $sender->sendMessage(TextFormat::RED . "Lệnh này đã bị tắt!!");
            return false;
        }
        if(count($args) === 0){
            if(($list = $this->getAPI()->warpList(false)) === false){
                $sender->sendMessage(TextFormat::AQUA . "There are no Warps currently available");
                return false;
            }
            $sender->sendMessage(TextFormat::AQUA . "§b→§aNhững warp còn hoạt động:\n" . $list);
            return true;
        }
        if(!($warp = $this->getAPI()->getWarp($args[0]))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Warp không tồn tại!");
            return false;
        }
        if(!isset($args[1]) && !$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[1])){
            if(!$sender->hasPermission("tool.warp.other")){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Bạn không thể dịch chuyển người chơi khác đến warp/");
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[1]))){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
                return false;
            }
        }
        if(!$sender->hasPermission("tool.warps.*") && !$sender->hasPermission("tool.warps.$args[0]")){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] You can't teleport to that warp");
            return false;
        }
        $player->teleport($warp);
        $player->sendMessage(TextFormat::GREEN . "§b→§aĐang dịch chuyển tới warp§b " . TextFormat::AQUA . $warp->getName() . TextFormat::GREEN . "§a...");
        if($player !== $sender){
            $sender->sendMessage(TextFormat::GREEN . "§b→§aĐang dịch chuyển người chơi§b " . TextFormat::YELLOW . $player->getDisplayName() . TextFormat::GREEN . "§a đến warp§b " . TextFormat::AQUA . $warp->getName() . TextFormat::GREEN . "...");
        }
        return true;
    }
} 
