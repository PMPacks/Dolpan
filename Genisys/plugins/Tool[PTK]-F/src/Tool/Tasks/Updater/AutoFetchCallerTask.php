<?php
namespace Tool\Tasks\Updater;

use Tool\BaseFiles\BaseTask;
use Tool\BaseFiles\BaseAPI;
use pocketmine\utils\TextFormat;

class AutoFetchCallerTask extends BaseTask{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api);
    }

    /**
     * @param int $currentTick
     */
    public function onRun($currentTick){
        $this->getAPI()->getServer()->getLogger()->debug(TextFormat::YELLOW . "Running Tool[PTK]'s AutoFetchCallerTask");
        $this->getAPI()->fetchTool[PTK]Update(false);
    }
}