<?php

declare(strict_types=1);

namespace NhanAZ\AnonChat;

use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerChatEvent;

class Main extends PluginBase implements Listener {

	private $defFormat = "&f[&9Anonymous&f] <msg>";

	private $showMsgToCsl = true;

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
	}

	public function onChat(PlayerChatEvent $event): void {
		$playerName = $event->getPlayer()->getName();
		$msg = $event->getMessage();
		if (substr($msg, 0, 3) == "[*]") {
			$event->cancel();
			$msg = substr($msg, 3);
			$format = $this->getConfig()->get("format", $this->defFormat);
			$showMsgToCsl = $this->getConfig()->get("showMsgToCsl", $this->showMsgToCsl);
			$message = str_replace("<msg>", $msg, $format);
			$anonName = ($showMsgToCsl ? "[{$playerName}] " : "");
			$this->getServer()->broadcastMessage(TextFormat::colorize($message));
			$this->getLogger()->info(TextFormat::colorize($anonName . $msg));
		}
	}
}
