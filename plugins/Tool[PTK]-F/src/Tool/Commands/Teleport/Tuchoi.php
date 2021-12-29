<?php
namespace Tool\Commands\Teleport;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Tuchoi extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tuchoi", "Từ chối tpa", "[Tên Người Chơi]", false, ["tpno"]);
        $this->setPermission("tool.tuchoi");
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
        if($this->getAPI()->getToolPlugin()->getConfig()->get("teleporting") !== true) {
            $sender->sendMessage(TextFormat::RED . "Lệnh này đã bị tắt!!");
            return false;
        }
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($request = $this->getAPI()->hasARequest($sender))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Bạn hiện không có yêu cầu nào!");
            return false;
        }
        switch(count($args)){
            case 0:
                if(!($player = $this->getAPI()->getPlayer(($name = $this->getAPI()->getLatestRequest($sender))))){
                    $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Yêu cầu không tồn tại!");
                    return false;
                }
                break;
            case 1:
                if(!($player = $this->getAPI()->getPlayer($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
                    return false;
                }
                if(!($request = $this->getAPI()->hasARequestFrom($sender, $player))){
                    $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi " . TextFormat::AQUA . $player->getDisplayName() . "§c không muốn dịch chuyển đến chỗ của bạn!");
                    return false;
                }
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        $player->sendMessage("§b→§aNgười chơi " . TextFormat::AQUA . $sender->getDisplayName() . TextFormat::RED . " từ chối bạn!");
        $sender->sendMessage(TextFormat::GREEN . "§b→§aĐã từ chối người chơi " . TextFormat::AQUA . $player->getDisplayName() . TextFormat::GREEN . " được dịch chuyển đến chỗ của bạn");
        $this->getAPI()->removeTPRequest($player, $sender);
        return true;
    }
} 