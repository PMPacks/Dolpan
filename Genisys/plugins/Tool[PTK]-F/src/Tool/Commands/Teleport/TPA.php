<?php
namespace Tool\Commands\Teleport;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPA extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tpa", "Dịch chuyển đến ai đó những cần được đồng ý", "<Tên Người Chơi>", false, ["call", "tpask"]);
        $this->setPermission("tool.tpa");
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
        if(!$sender instanceof Player || count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không tồn tại!");
            return false;
        }
        if($player->getName() === $sender->getName()){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Xin hãy nhập tên củ người chơi!");
            return false;
        }
        $this->getAPI()->requestTPTo($sender, $player);
        $player->sendMessage("§b→§aNgười chơi " . TextFormat::AQUA . $sender->getName() . TextFormat::GREEN . " muốn tpa đến chỗ của bạn, xin hãy sử dụng:\n§b♦§6Lệnh /dongy để đồng ý tpa.\n§b♦§6Lệnh /tuchoi để từ chối tpa.");
        $sender->sendMessage("§b→§aBạn đã gửi đề nghị tpa đến người chơi " . TextFormat::AQUA . $player->getDisplayName() . "§a xin hãy chờ người chơi này trả lời!");
        return true;
    }
} 
