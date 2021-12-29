<?php
namespace MyPlot\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;

class HelpSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender) {
        return $sender->hasPermission("myplot.command.help");
    }

    /**
     * @return \MyPlot\Commands
     */
    private function getCommandHandler()
    {
        return $this->getPlugin()->getCommand($this->translateString("command.name"));
    }

    public function execute(CommandSender $sender, array $args) {
        if (count($args) === 0) {
            $pageNumber = 1;
        } elseif (is_numeric($args[0])) {
            $pageNumber = (int) array_shift($args);
            if ($pageNumber <= 0) {
                $pageNumber = 1;
            }
        } else {
            return false;
        }

        if ($sender instanceof ConsoleCommandSender) {
            $pageHeight = PHP_INT_MAX;
        } else {
            $pageHeight = 5;
        }

        $commands = [];
        foreach ($this->getCommandHandler()->getCommands() as $command) {
            if ($command->canUse($sender)) {
                $commands[$command->getName()] = $command;
            }
        }
        ksort($commands, SORT_NATURAL | SORT_FLAG_CASE);
        $commands = array_chunk($commands, $pageHeight);
        /** @var SubCommand[][] $commands */

							//////
            $sender->sendMessage("§l§c-=- §a§lSkyBlock - Help §c-=-");
			$sender->sendMessage("§6☆§eHãy sử dụng lệnh:§a /sb play§b Để §eĐi đến một hòn đảo");
			$sender->sendMessage("§6☆§eHãy sử dụng lệnh:§a /sb claim§b Để §eMua ngay hòn đảo bạn đang đứng");
			$sender->sendMessage("§6☆§eHãy sử dụng lệnh:§a /sb add §e<Tên Người Chơi>§b Để §eThêm người vào đảo của bạn");
			$sender->sendMessage("§6☆§eHãy sử dụng lệnh:§a /sb kick §e<Tên Người Chơi>§b Để §eXóa người chơi trong đảo của bạn");
			$sender->sendMessage("§6☆§eHãy sử dụng lệnh:§a /sb homes§b Để §eDanh sách đảo của bạn");
			$sender->sendMessage("§6☆§eHãy sử dụng lệnh:§a /sb home §e<ID đảo> §b Để §eDịch chuyển về đảo của bạn");
			$sender->sendMessage("§6☆§eHãy sử dụng lệnh:§a /sb info§b Để §eXem thông tin hòn đảo");
			$sender->sendMessage("§6☆§eHãy sử dụng lệnh:§a /sb give §e<Tên người chơi> §b Để §eCho người khác hòn đảo của bạn");
	$sender->sendMessage("§6☆§eHãy sử dụng lệnh:§a /sb warp §e[x;y] §b Để §eDi chuyển đến hòn đảo nào đó");
	$sender->sendMessage("§6☆§eHãy sử dụng lệnh:§a /sb del §b Để §eXóa hòn đảo của bạn!");
        return true;
    }
}
