<?php
namespace Tool\Commands\Home;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Home extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "home", "Dịch chuyển về nhà của bạn", "<Tên Nhà>", false, ["homes"]);
        $this->setPermission("tool.home.use");
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
        if($this->getAPI()->getToolPlugin()->getConfig()->get("homes") !== true) {
            $sender->sendMessage(TextFormat::RED . "Lệnh này đã bị tắt!!");
            return false;
        }
        if(!$sender instanceof Player || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(count($args) === 0){
            if(($list = $this->getAPI()->homesList($sender, false)) === false){
                $sender->sendMessage(TextFormat::AQUA . "§c[Lỗi] Bạn chưa đặt 1 căn nhà nào cả!");
                return false;
            }
            $sender->sendMessage(TextFormat::AQUA . "§b→§6Những căn nhà bạn đã đặt:\n" . $list);
            return true;
        }
        if(!($home = $this->getAPI()->getHome($sender, $args[0]))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Căn nhà này không tồn tại hoặc ở trong một thế giới chưa được oad!");
            return false;
        }
        $sender->teleport($home);
        $sender->sendMessage(TextFormat::GREEN . "§b→§aĐang dịch chuyển tới home " . TextFormat::AQUA . $home->getName() . TextFormat::GREEN . "...");
        return true;
    }
} 