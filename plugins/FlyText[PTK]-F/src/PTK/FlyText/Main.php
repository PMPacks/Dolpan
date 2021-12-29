<?php

	namespace PTK\FlyText;

	use pocketmine\plugin\PluginBase;
	use pocketmine\event\Listener;
	use pocketmine\utils\Config;
	use pocketmine\utils\TextFormat;
	use pocketmine\level\particle\FloatingTextParticle;
	use pocketmine\math\Vector3;
	use pocketmine\event\player\PlayerJoinEvent;
	use pocketmine\command\Command;
	use pocketmine\command\CommandSender;
	use pocketmine\Player;
	use pocketmine\Server;

	class Main extends PluginBase implements Listener {
		private $config;

		public function onEnable() {
			if(!is_dir($this->getDataFolder()))
				@mkdir($this->getDataFolder());
			$this->config = (new Config($this->getDataFolder().'Chữ Bay.yml', Config::YAML))->getAll();
			$this->getServer()->getPluginManager()->registerEvents($this, $this);
			$this->getLogger()->info(TextFormat::AQUA."Đã kích hoạt");
		}

		public function onDisable() {
			$cfg = new Config($this->getDataFolder().'Chữ Bay.yml', Config::YAML);
			$cfg->setAll($this->config);
			$cfg->save();
			$this->getLogger()->info(TextFormat::AQUA."Đã tắt");
		}

		public function onJoin(PlayerJoinEvent $event) {
			$player = $event->getPlayer();
			if($player instanceof Player) {
				foreach($this->config as $coord => $text) {
					$coord = explode(':', $coord);
					$x = $coord[0];
					$y = $coord[1];
					$z = $coord[2];
					$br = explode("\\n", $text);
					$text = "";
					foreach($br as $line) 
						$text .= $line."\n";
					$player->getLevel()->addParticle(new FloatingTextParticle(new Vector3($x, $y, $z), '', $text), array($player));
				}
			}
		}

		public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
			if($sender instanceof Player) {
				if($command->getName() == 'addtext') {
					if(count($args) > 0) {
						$text = "";
						foreach($args as $word)
							$text .= "$word ";
						$text = trim($text);
						$x = $sender->getX();
						$y = $sender->getY() + 2;
						$z = $sender->getZ();
						$this->config[$x.':'.$y.':'.$z] = $text;
						$cfg = new Config($this->getDataFolder().'Chữ Bay.yml', Config::YAML);
						$cfg->setAll($this->config);
						$cfg->save();
						$br = explode("\\n", $text);
						$text = "";
						foreach($br as $line)
							$text .= $line."\n";
						$sender->getLevel()->addParticle(new FloatingTextParticle(new Vector3($x, $y, $z), '', $text));
						$sender->sendMessage(TextFormat::AQUA."[Chữ Bay] Đã tạo chữ bay ở vị trí $x $y $z");
					} else $sender->sendMessage(TextFormat::AQUA."Không thể tạo");
				}
			} else $sender->sendMessage(TextFormat::RED."Chỉ dành cho người chơi");
		}

	}

?>