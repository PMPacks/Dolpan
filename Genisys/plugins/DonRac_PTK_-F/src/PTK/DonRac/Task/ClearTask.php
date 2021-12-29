<?php
namespace PTK\DonRac\Task;

use pocketmine\utils\TextFormat as F;
use pocketmine\scheduler\PluginTask;
use pocketmine\Server;
use PTK\DonRac\DRMain;

/**
 * Class ClearTask
 * @package PTK\DonRac\Task
 */
class ClearTask extends PluginTask
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
        $msg = DRMain::getInstance()->config->get("Clear-msg");
        $msg = str_replace("@count", DRMain::getInstance()->getEntityManager()->removeEntities(), $msg);
        Server::getInstance()->broadcastMessage(F::GREEN . $msg);
    }
}