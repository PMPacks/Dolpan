<?php
namespace PTK\DonRac\Task;

use pocketmine\Server;
use PTK\DonRac\DRMain;

/**
 * Class TaskCreator
 * @package PTK\DonRac\Task
 */
class TaskCreator
{
    function __construct()
    {
        $this->main = DRMain::getInstance();
        $this->createTasks($this->main);
    }

    /**
     * @param DRMain $main
     */
    private function createTasks(DRMain $main)
    {
        Server::getInstance()->getScheduler()->scheduleRepeatingTask(new ClearTask($main), $main->config->getAll()["Clear-time"] * 20);
        Server::getInstance()->getScheduler()->scheduleRepeatingTask(new MsgTask($main), ($main->config->getAll()["Clear-time"] - 60) * 20);
    }
}