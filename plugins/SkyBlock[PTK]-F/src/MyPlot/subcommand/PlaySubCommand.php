<?php
namespace MyPlot\subcommand;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\Server;

class PlaySubCommand extends SubCommand
{


    public function canUse(CommandSender $sender) {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.play");
    }
    
    public function pl(Player $player)
    {
        return $player;
    }

    public function execute(CommandSender $sender, array $args) {
        if (!empty($args)) {
            return false;
        }
        $player = $sender->getServer()->getPlayer($sender->getName());
        $levelName = $player->getLevel()->getName();
        if (!$this->getPlugin()->isLevelLoaded($levelName)) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("play.notplotworld"));
            return true;
        }
        if (($plot = $this->getPlugin()->getProvider()->getNextFreePlot($levelName)) !== null) {
            $this->getPlugin()->teleportPlayerToPlot($player, $plot);
            $sender->sendMessage($this->translateString("play.success", [$plot->X, $plot->Z]));
			$sender->sendMessage(TextFormat::GREEN . "§b[§eDolpan§b-§aSkyBlock§b]§a Xin hãy sử dụng §e/sb claim §ađể sở hữu đảo");
        } else {
            $sender->sendMessage(TextFormat::RED . $this->translateString("play.noplots"));
        }
        return true;
    }
}