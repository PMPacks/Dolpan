<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class World extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "world", "Dịch chuyển giữa các thế giới", "<Tên Thế Giới>", false);
        $this->setPermission("tool.world");
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
        if(!$sender instanceof Player || count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!$sender->hasPermission("tool.worlds.*") && !$sender->hasPermission("tool.worlds." . strtolower($args[0]))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Bạn không có quyền dịch chuyển đến thế giới này.");
            return false;
        }
        if(!$sender->getServer()->isLevelGenerated($args[0])){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Thế giới không tồn tại!");
            return false;
        }elseif(!$sender->getServer()->isLevelLoaded($args[0])){
            $sender->sendMessage(TextFormat::YELLOW . "§b→§aThế giới này chưa được tải!Đang tải lại thế giới...");
            if(!$sender->getServer()->loadLevel($args[0])){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Không thể tải lại thế giới này!");
                return false;
            }
        }
        $sender->teleport($this->getAPI()->getServer()->getLevelByName($args[0])->getSpawnLocation(), 0, 0);
        $sender->sendMessage(TextFormat::YELLOW . "§b→§aĐang dịch chuyển...");
        return true;
    }
} 
