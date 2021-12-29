<?php
namespace Tool\Tasks\AFK;

use Tool\BaseFiles\BaseTask;
use Tool\BaseFiles\BaseAPI;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class AFKKickTask extends BaseTask{
    /** @var Player  */
    protected $player;

    /**
     * @param BaseAPI $api
     * @param Player $player
     */
    public function __construct(BaseAPI $api, Player $player){
        parent::__construct($api);
        $this->player = $player;
    }

    /**
     * @param int $currentTick
     */
    public function onRun($currentTick){
        // TODO: Remember access to API for tasks...
        $this->getAPI()->getServer()->getLogger()->debug(TextFormat::YELLOW . "Running Tool's AFKKickTask");
        if($this->player instanceof Player && $this->player->isOnline() && $this->getAPI()->isAFK($this->player) && !$this->player->hasPermission("tool.afk.kickexempt") && time() - $this->getAPI()->getLastPlayerMovement($this->player) >= $this->getAPI()->getToolPlugin()->getConfig()->getNested("afk.auto-set")){
            $this->player->kick("You have been kicked for idling more than " . (($time = floor($this->getAPI()->getToolPlugin()->getConfig()->getNested("afk.auto-kick"))) / 60 >= 1 ? ($time / 60) . " minutes" : $time . " seconds"), false);
        }
    }
} 