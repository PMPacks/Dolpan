<?php
namespace Tool\Commands\Teleport;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPAHere extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tpahere", "Tpa ai đó tới chỗ bạn", "<Tên Người Chơi>", false);
        $this->setPermission("tool.tpahere");
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
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
            return false;
        }
        if($player->getName() === $sender->getName()){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Please provide another player name");
            return false;
        }
        $this->getAPI()->requestTPHere($sender, $player);
        $player->sendMessage("§b→§aNgười chơi " . TextFormat::AQUA . $sender->getName() . TextFormat::GREEN . " muốn bạn dịch chuyển đến chỗ của người chơi này, xin hãy sử dụng:\n§b♦§6Lệnh /dongy để đồng ý tpa.\n§b♦§6Lệnh /tuchoi để từ chối tpa.");
        $sender->sendMessage("§b→§aBạn đã gửi đề nghị tpa đến người chơi " . TextFormat::AQUA . $player->getDisplayName() . "§a xin hãy chờ người chơi này trả lời!");
        return true;
    }
} 