<?php
namespace Tool\Commands;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class More extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "more", "Get a stack of the item you're holding", null, false);
        $this->setPermission("tool.more");
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
        if(($gm = $sender->getGamemode()) === Player::CREATIVE || $gm === Player::SPECTATOR){
            $sender->sendMessage(TextFormat::RED . "§c[Lỗi] You're in " . $this->getAPI()->getServer()->getGamemodeString($gm) . " mode");
            return false;
        }
        $item = $sender->getInventory()->getItemInHand();
        if($item->getId() === Item::AIR){
            $sender->sendMessage(TextFormat::RED . "You can't get a stack of AIR");
            return false;
        }
        $item->setCount($sender->hasPermission("tool.oversizedstacks") ? $this->getPlugin()->getConfig()->get("oversized-stacks") : $item->getMaxStackSize());
        $sender->sendMessage(TextFormat::AQUA . "Filled up the item stack to " . $item->getCount());
        return true;
    }
}
