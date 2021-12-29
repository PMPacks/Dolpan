<?php
namespace PTK\DonRac\Task;

use pocketmine\scheduler\PluginTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat as F;
use PTK\DonRac\DRMain;

/**
 * Class MsgTask
 * @package PTK\DonRac\Task
 */
class MsgTask extends PluginTask
{
    /**
     * @param DRMain $main
     */
    function __construct(DRMain $main)
    {
        parent::__construct($main);
        $this->plugin = $main;
    }

    /**
     * @param $currentTick
     */
    function onRun($currentTick)
    {
        Server::getInstance()->broadcastMessage(F::GREEN . DRMain::getInstance()->config->get("ThongBao-msg"));
    }
}