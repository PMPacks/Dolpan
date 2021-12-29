<?php
namespace MyPlot\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\block\Chest;
use pocketmine\item\Item;
use pocketmine\tile\Tile;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\NBT;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\Cow;

class ClaimSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender) {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.claim");
    }

    public function execute(CommandSender $sender, array $args) {
        if (count($args) > 1) {
            return false;
        }
        $name = "";
        if (isset($args[0])) {
            $name = $args[0];
        }
        $player = $sender->getServer()->getPlayer($sender->getName());
        $plot = $this->getPlugin()->getPlotByPosition($player->getPosition());
        if ($plot === null) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("notinplot"));
            return true;
        }
        if ($plot->owner != "") {
            if ($plot->owner === $sender->getName()) {
                $sender->sendMessage(TextFormat::RED . $this->translateString("claim.yourplot"));
            } else {
                $sender->sendMessage(TextFormat::RED . $this->translateString("claim.alreadyclaimed", [$plot->owner]));
            }
            return true;
        }

        $maxPlots = $this->getPlugin()->getMaxPlotsOfPlayer($player);
        $plotsOfPlayer = count($this->getPlugin()->getProvider()->getPlotsByOwner($player->getName()));
        if ($plotsOfPlayer >= $maxPlots) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("claim.maxplots", [$maxPlots]));
            return true;
        }

        $plotLevel = $this->getPlugin()->getLevelSettings($plot->levelName);
        $economy = $this->getPlugin()->getEconomyProvider();
        if ($economy !== null and !$economy->reduceMoney($player, $plotLevel->claimPrice)) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("claim.nomoney"));
            return true;
        }
		

        $plot->owner = $sender->getName();
        $plot->name = $name;
        if ($this->getPlugin()->getProvider()->savePlot($plot)) {
            $sender->sendMessage($this->translateString("claim.success"));
			
			$player->getInventory()->addItem(Item::get(8, 0, 2));
			$player->getInventory()->addItem(Item::get(85, 0, 2));
			$player->getInventory()->addItem(Item::get(278, 0, 1));
			$player->getInventory()->addItem(Item::get(277, 0, 1));
			$player->getInventory()->addItem(Item::get(279, 0, 1));
			$player->getInventory()->addItem(Item::get(6, 0, 2));
			$player->getInventory()->addItem(Item::get(81, 0, 3));
			$player->getInventory()->addItem(Item::get(295, 0, 5));
			$player->getInventory()->addItem(Item::get(338, 0, 3));
			$player->getInventory()->addItem(Item::get(352, 0, 10));
			$player->getInventory()->addItem(Item::get(361, 0, 3));
			$player->getInventory()->addItem(Item::get(362, 0, 3));
			$player->getInventory()->addItem(Item::get(391, 0, 3));
			$player->getInventory()->addItem(Item::get(392, 0, 3));
			$player->getInventory()->addItem(Item::get(54, 0, 2));
			$player->getInventory()->addItem(Item::get(364, 0, 5));
            $sender->sendMessage("§b[§eDolpan§b-§aSkyBlock§b] §aĐã Thêm§b Nước§a Và§6 Hàng Rào§a Vào Kho Đồ Của Bạn!");
            $sender->sendMessage("§b[§eDolpan§b-§aSkyBlock§b] §bHãy Sử Dụng Hàng Rào Thay Cho Dung Nham Để Làm Máy Farm Đá Ghi /khu farm để xem cách làm nếu bạn không biết!");
        } else {
            $sender->sendMessage(TextFormat::RED . $this->translateString("error"));
        }
        return true;
    }
}