<?php
namespace Tool\Commands\Economy;

use Tool\BaseFiles\BaseAPI;
use Tool\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Balance extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "balance", "See how many money do you have", "[player]", true, ["bal", "money"]);
        $this->setPermission("tool.balance.use");
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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("tool.balance.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!$player = $this->getAPI()->getPlayer($args[0])){
                $sender->sendMessage(TextFormat::RED . "§c[Lỗi] Người chơi không onine hoặc không tồn tại!");
                return false;
            }
        }
        $sender->sendMessage(TextFormat::AQUA . ($player === $sender ? "Your current balance is " : $player->getDisplayName() . TextFormat::AQUA . " has ") . TextFormat::YELLOW . $this->getAPI()->getCurrencySymbol() . $this->getAPI()->getPlayerBalance($player));
        return true;
    }
}