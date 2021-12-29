<?php
namespace PTK\DonRac;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use PTK\DonRac\Command\DonRacCommandExecutor;
use PTK\DonRac\Entity\EntityManager;
use pocketmine\utils\TextFormat as F;
use PTK\DonRac\Task\TaskCreator;

/**
 * Class DRMain
 * @package PTK\DonRac
 */
class DRMain extends PluginBase
{
    /**
     * @var DRMain
     */
    private static $instance;
    public $config;
    /**
     * @var \PTK\DonRac\Entity\EntityManager
     */
    private $entityManager;

    function __construct()
    {
        self::$instance = $this;
        $this->entityManager = new EntityManager($this);
    }

    /**
     * @return DRMain
     */
    static function getInstance()
    {
        return self::$instance;
    }

    /**
     * @return EntityManager
     */
    function getEntityManager()
    {
        return $this->entityManager;
    }

    function onEnable()
    {
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder() . "Config.yml", Config::YAML, array(
            "Clear-msg" => "§b[§l§b⭐Dolpan⭐ §r- §9Dọn Rác§b]§a Đã dọn @count vật phẩm rơi trên đất!",
            "ThongBao-msg" => "§b[§l§b⭐Dolpan⭐ §r- §9Dọn Rác§b]§c Rác sẽ được dọn sau 2 phút nữa",
            "Clear-time" => 240
        ));
        new TaskCreator();
        $this->getLogger()->info(F::GREEN . "Plugin Dọn Rác Bởi PTK Đã Được Kích Hoạt Phiên Bản:" . $this->getDescription()->getVersion() . "!");
    }

    /**
     * @param CommandSender $s
     * @param Command $cmd
     * @param string $label
     * @param array $args
     * @return bool|DonRacCommandExecutor
     */
    function onCommand(CommandSender $s, Command $cmd, $label, array $args)
    {
        return new DonRacCommandExecutor($s, $cmd, $args);
    }

    function onDisable()
    {
        $this->config->save();
        $this->getLogger()->info(F::RED . " Plugin Dọn Rác Bởi PTK Đã Tắt Phiên Bản:" . $this->getDescription()->getVersion() . "!");
    }
}