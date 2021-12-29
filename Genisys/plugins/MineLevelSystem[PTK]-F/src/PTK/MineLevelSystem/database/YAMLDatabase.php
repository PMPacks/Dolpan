<?php

namespace PTK\MineLevelSystem\database;

use pocketmine\utils\Config;
use PTK\MineLevelSystem\Main;
use PTK\MineLevelSystem\database\DatabaseManager;

class YAMLDatabase implements DatabaseManager
{

	/** @var Config */
	public $Config;

	/** @var MineLevelSystem */
	protected $ls;



	public function __construct(MineLevelSystem $ls)
	{
		$this->ls = $ls;
		$this->ls->getLogger()->info("起動: MineLevelSystem.YAMLDatabase");
		$this->Config = new Config($this->ls->getDataFolder() . "players.yml", Config::YAML);
	}


		public function getLevel(string $user)
	{
		if($this->Config->exists($user))
		{
			return $this->Config->get($user)["level"];
		}else{
			return false;
		}
	}


	public function setLevel(string $user, int $level)
	{
		if($this->Config->exists($user))
		{
			$cd = $this->Config->get($user);
			$cd["level"] = $level;
			$this->Config->set($user, $cd);
			$this->Config->save();
		}
	}


	public function getExp(string $user)
	{
		if($this->Config->exists($user))
		{
			return $this->Config->get($user)["exp"];
		}else{
			return false;
		}
	}


	public function setExp(string $user, int $exp)
	{
		if($this->Config->exists($user))
		{
			$cd = $this->Config->get($user);
			$cd["exp"] = $exp;
			$this->Config->set($user, $cd);
			$this->Config->save();
		}
	}


	public function getLevelUpExp(string $user)
	{
		if($this->Config->exists($user))
		{
			return $this->Config->get($user)["levelupexp"];
		}else{
			return false;
		}
	}


	public function setLevelUpExp(string $user, int $exp)
	{
		if($this->Config->exists($user))
		{
			$cd = $this->Config->get($user);
			$cd["levelupexp"] = $exp;
			$this->Config->set($user, $cd);
			$this->Config->save();
		}
	}


	public function isRegist(string $user)
	{
		return $this->Config->exists($user);
	}


	public function registUser(string $user)
	{
		$data = array(
			"level" => 1,
			"exp" => 0,
			"levelupexp" => 2,
			"kill" => 0,
			"death" => 0
			);
		$this->Config->set($user, $data);
		$this->Config->save();
	}


	public function getKill(string $user)
	{

	}


	public function setKill(string $user, int $kill)
	{

	}


	public function getDeath(string $user)
	{

	}


	public function setDeath(string $user, int $death)
	{

	}

}
