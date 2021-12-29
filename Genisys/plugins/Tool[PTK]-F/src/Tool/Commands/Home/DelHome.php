<?php
namespace Tool\Commands\Home;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class DelHome extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "delhome", "Xóa 1 căn nhà nào đó", "<Tên Căn Nhà>", false, ["rmhome", "removehome"]);
        $this->setPermission("tool.delhome");
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
        if(!$sender instanceof Player || count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!$this->getAPI()->homeExists($sender, $args[0])){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Bạn không có căn nhà nào với tên như vậy cả!");
            return false;
        }
        $this->getAPI()->removeHome($sender, $args[0]);
        $sender->sendMessage(TextFormat::GREEN . "§b→§aBạn đã xóa căn nhà thành công!");
        return true;
    }
} 
