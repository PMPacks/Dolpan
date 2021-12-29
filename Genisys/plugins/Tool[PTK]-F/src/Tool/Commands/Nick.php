<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Nick extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "nick", "Đổi tên nick của bạn", "<new nick|off> [player]", true, ["nickname"]);
        $this->setPermission("tool.nick.use");
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
        if((!isset($args[1]) && !$sender instanceof Player) || (count($args) < 1 || count($args) > 2)){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $nick = ($n = strtolower($alias[0])) === "off" || $n === "remove" || (bool) $n === false ? false : $args[0];
        $player = $sender;
        if(isset($args[1])){
            if(!$sender->hasPermission("tool.nick.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[1]))){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
                return false;
            }
        }
        if(!$nick){
            $this->getAPI()->removeNick($player);
        }else{
            if(!$this->getAPI()->setNick($player, $nick)){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Bạn không có quyền để đặt màu cho nick của người chơi này!");
            }
        }
        $player->sendMessage("§b→§aNick của bạn đã được " . ($m = !$nick ? "đặt về mặc định" : "sửa thành " . TextFormat::AQUA . $nick));
        if($player !== $sender){
            $sender->sendMessage("§b→§aNick của người chơi " . TextFormat::AQUA . $player->getName() . TextFormat::GREEN . " đã được " . $m);
        }
        return true;
    }
}
