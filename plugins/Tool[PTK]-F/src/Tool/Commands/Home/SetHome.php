<?php
namespace Tool\Commands\Home;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SetHome extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "sethome", "Tạo hoặc cập nhặt những căn nhà của bạn", "<Tên Căn Nhà>", false, ["createhome"]);
        $this->setPermission("tool.sethome");
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
        if(strtolower($args[0]) === "bed"){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] You can only set a \"bed\" home by sleeping in a bed");
            return false;
        }elseif(trim($args[0] === "")){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Please provide a Home name");
            return false;
        }
        if(!$this->getAPI()->setHome($sender, strtolower($args[0]), $sender->getLocation(), $sender->getYaw(), $sender->getPitch())){
            $sender->sendMessage(TextFormat::RED . "Invalid home name given! Please be sure to only use alphanumerical characters and underscores");
            return false;
        }
        $sender->sendMessage(TextFormat::GREEN . "§b→§aCăn nhà của bạn đã được " . ($this->getAPI()->homeExists($sender, $args[0]) ? "cập nhật địa điểm.l!" : "tạo!"));
        return true;
    }
} 
