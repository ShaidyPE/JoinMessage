<?php

namespace ShaidyPE;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};

class JoinMessage extends PluginBase implements Listener
{
	/** @var PurePerms $pp */
	private static $pp;

	/** VOID */
	public function onEnable(): void{
		self::$pp = $this->getServer()->getPluginManager()->getPlugin("PurePerms");

		if(self::$pp === null){
			$this->getLogger()->error("Plugin PurePerms not found!");

			return;
		}

		$this->saveResource("config.yml");

		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	/**
	 * @param PlayerJoinEvent $ev
	*/
	public function onJoin(PlayerJoinEvent $ev): void{
		$ev->setJoinMessage(null);

		/** @var string $group */
		$group = self::$pp->getUserDataMgr()->getGroup($ev->getPlayer());

		/** @var string $message */
		$message = str_replace("{nick}", $ev->getPlayer()->getName(), $this->getConfig()->getNested("messages.". $group));

		if($message !== null)
			$this->getServer()->broadcastMessage($message);
	}

	/**
	 * @param PlayerQuitEvent $ev
	*/
	public function onQuit(PlayerQuitEvent $ev): void{
		$ev->setQuitMessage(null);
	}
}