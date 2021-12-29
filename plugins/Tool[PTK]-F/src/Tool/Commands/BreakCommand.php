<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class BreakCommand extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "break", "Phá khối bạn đang nhìn", null, false);
        $this->setPermission("tool.break.use");
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
        if(!$sender instanceof Player || count($args) !== 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(($block = $sender->getTargetBlock(100, [Block::AIR])) === null){
            $sender->sendMessage(TextFormat::RED . "[Lỗi] Không có block nào được đặt ở đây cả!");
            return false;
        }elseif($block->getId() === Block::BEDROCK && !$sender->hasPermission("tool.break.bedrock")){
            $sender->sendMessage(TextFormat::RED . "[Lỗi] Bạn không thể phá bedrock!");
            return false;
        }
        $sender->getLevel()->setBlock($block, new Air(), true, true);
        $sender->sendMessage(TextFormat::RED . "§b→§aĐã phá khối trước mặt bạn!");
        return true;
    }
} 