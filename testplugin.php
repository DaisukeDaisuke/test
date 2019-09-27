<?php
/*
 This code does not work!
*/

namespace testplugin;

use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\block\Block;
use pocketmine\level\Position;
use pocketmine\item\Item;

use pocketmine\network\mcpe\protocol\ScriptCustomEventPacket;

use pocketmine\resourcepacks\ResourcePack;
use pocketmine\resourcepacks\ResourcePackManager;

use pocketmine\network\mcpe\protocol\ResourcePackClientResponsePacket;
use pocketmine\network\mcpe\protocol\ResourcePackChunkRequestPacket;
use pocketmine\network\mcpe\protocol\ResourcePackChunkDataPacket;
use pocketmine\network\mcpe\protocol\ResourcePackDataInfoPacket;
use pocketmine\network\mcpe\protocol\ResourcePacksInfoPacket;
use pocketmine\network\mcpe\protocol\ResourcePackStackPacket;

class testplugin extends PluginBase implements Listener{
	/** @var ResourcePackManager */
	public $ResourcePackManager = null;//behaviorPack
	/** @var bool */
	public $IsExperimentalGamePlay = false;

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!file_exists($this->getDataFolder())){
			mkdir($this->getDataFolder(),0774,true);
		}
		$this->saveResource("resource_packs.yml");
		$this->ResourcePackManager = new ResourcePackManager($this->getDataFolder(),$this->getLogger());
		$resourcePacksConfig = new Config($this->getDataFolder() . "resource_packs.yml", Config::YAML, []);
		$this->IsExperimentalGamePlay = (bool) $resourcePacksConfig->get("ExperimentalGamePlay");
	}

	public function send(DataPacketSendEvent $event){
		if($event->getPacket() instanceof ResourcePackStackPacket){
			$event->getPacket()->isExperimental = $this->IsExperimentalGamePlay;
			$event->getPacket()->behaviorPackStack = $this->ResourcePackManager->getResourceStack();
			//var_dump($event->getPacket());
		}else if($event->getPacket() instanceof ResourcePacksInfoPacket){
			$event->getPacket()->behaviorPackEntries = $this->ResourcePackManager->getResourceStack();
			$event->getPacket()->hasScripts = true;
		}
	}
	public function Receive(DataPacketReceiveEvent $event){
		if($event->getPacket() instanceof ResourcePackClientResponsePacket){
			$packet = $event->getPacket();
			var_dump($packet->packIds,$this->ResourcePackManager->getPackIdList());
			switch($packet->status){
				case ResourcePackClientResponsePacket::STATUS_REFUSED:
					//
					var_dump("REFUSED!!");
				break;
				case ResourcePackClientResponsePacket::STATUS_SEND_PACKS:
					$manager = $this->ResourcePackManager;
					foreach($packet->packIds as $key => $uuid){
						//dirty hack for mojang's dirty hack for versions
						$splitPos = strpos($uuid, "_");
						if($splitPos !== false){
							$uuid = substr($uuid, 0, $splitPos);
						}
						$pack = $manager->getPackById($uuid);
						if(!($pack instanceof ResourcePack)){
							//
							continue;
						}
						$pk = new ResourcePackDataInfoPacket();
						$pk->packId = $pack->getPackId();
						$pk->maxChunkSize = 1048576; //1MB
						$pk->chunkCount = (int) ceil($pack->getPackSize() / $pk->maxChunkSize);
						$pk->compressedPackSize = $pack->getPackSize();
						$pk->sha256 = $pack->getSha256();
						$event->getPlayer()->dataPacket($pk);
						unset($event->getPacket()->packIds[$key]);
					}
					var_dump($event->getPacket()->packIds);
				break;
			}
		}else if($event->getPacket() instanceof ResourcePackChunkRequestPacket){
			var_dump($event->getPacket()->packId);
			$packet = $event->getPacket();
			$manager = $this->ResourcePackManager;
			$pack = $manager->getPackById($packet->packId);
			if(!($pack instanceof ResourcePack)){
				//
				return;
			}
			$pk = new ResourcePackChunkDataPacket();
			$pk->packId = $pack->getPackId();
			$pk->chunkIndex = $packet->chunkIndex;
			$pk->data = $pack->getPackChunk(1048576 * $packet->chunkIndex, 1048576);
			$pk->progress = (1048576 * $packet->chunkIndex);
			$event->getPlayer()->dataPacket($pk);
			$event->setCancelled();
		}
	}
}
