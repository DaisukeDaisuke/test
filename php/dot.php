<?php

namespace dot;

use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\protocol\UseItemPacket;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\level\particle\RedstoneParticle;
use pocketmine\level\particle\LargeExplodeParticle;
use pocketmine\utils\Config;
use pocketmine\scheduler\PluginTask;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\sound\ExplodeSound;
use pocketmine\block\Block;
use pocketmine\level\Position;
use pocketmine\level\Explosion;

class dot extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!file_exists($this->getDataFolder())){
			mkdir($this->getDataFolder(), 0744, true);
		}
  	}
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		switch(strtolower($label)) {
			case "do":
			if(!$sender->isOP()) return true;
				$this->getServer()->broadcastMessage("[auto_dot]変更を開始します");
				if(file_exists($this->getDataFolder()."target.png")) $path = $this->getDataFolder()."target.png";
				if(file_exists($this->getDataFolder()."target.gif")) $path = $this->getDataFolder()."target.gif";
				if(file_exists($this->getDataFolder()."target.jpg")) $path = $this->getDataFolder()."target.jpg";
				if(file_exists($this->getDataFolder()."target.jpeg")) $path = $this->getDataFolder()."target.jpeg";
				$imageinfo = getimagesize($path);
				switch($imageinfo[2]){
					case IMAGETYPE_GIF:
						$im = imagecreatefromgif($path);
					break;
					case IMAGETYPE_PNG:
						$im = imagecreatefrompng($path);
					break;
					case IMAGETYPE_JPEG:
						$im = imagecreatefromjpeg($path);
					break;
					default:
						$sender->sendMessage("問題が発生しました 処理を中断\n");
						$sender->sendMessage("we want gif or png file\n");
						$this->getServer()->broadcastMessage("[auto_dot] 変更が終了しました。");
					return true;
					}
				
				if(isset($args[0])&&isset($args[1])){
				$this->autodot($im,$sender->getLevel(),$sender->x,$sender->y,$sender->z,$args[0],$args[1]);
				}else if(isset($args[0])&&!isset($args[1])){
				$this->autodot($im,$sender->getLevel(),$sender->x,$sender->y,$sender->z,$args[0]);
				}else $this->autodot($im,$sender->getLevel(),$sender->x,$sender->y,$sender->z);
				$this->getServer()->broadcastMessage("[auto_dot] 変更が終了しました。");
				return true;
			break;
		}
	}
	function color($color1,$color2){//未使用
		return sqrt(($color2["red"]-$color1[0]) * ($color2["red"]-$color1[0]) + ($color2["green"]-$color1[1]) * ($color2["green"]-$color1[1]) + ($color2["blue"]-$color1[2]) * ($color2["blue"]-$color1[2])) / 1.73205080757; //sqrt(3);
	}
	public function autodot($im,$level = null,$startx,$starty,$startz,$mode = 0,$mode1 = 0){
		$woolColors = json_decode(base64_decode('W1sxMTIsMTg1LDI2LDM1LDVdLFsxMTQsNzIsNDEsMzUsMTJdLFsyMzgsMTQxLDE3MiwzNSw2XSxbMTQyLDE0MiwxMzUsMzUsOF0sWzI0OSwxOTgsNDAsMzUsNF0sWzIzNCwyMzYsMjM3LDM1LDBdLFs4NSwxMTAsMjgsMzUsMTNdLFsxMjIsNDIsMTczLDM1LDEwXSxbMjEsMTM4LDE0NSwzNSw5XSxbMTY5LDg4LDMzLDEyLDFdLFsyMzYsMjMzLDIyNiwxNTUsMF0sWzEwNCw3OCw0Nyw1LDFdLFsxOTYsMTc5LDEyMyw1LDJdLFsyMTksMjExLDE2MCwxMiwwXSxbOTgsMjE5LDIxNCw1NywwXSxbMTU0LDExMCw3Nyw1LDNdLFsyMTksMjE5LDIxOSw0MiwwXSxbMTkwLDY5LDE4MCwzNSwyXSxbNjEsNDAsMTgsNSw1XSxbMTYxLDM5LDM1LDM1LDE0XSxbNzQsMTgxLDIxMywyMzcsM10sWzEzMiw1NiwxNzgsMjM3LDEwXSxbMTA0LDExOCw1MywxNTksNV0sWzE0Myw2MSw0NywxNTksMTRdLFs3Nyw1MSwzNiwxNTksMTJdLFs3Niw4Myw0MiwxNTksMTNdLFsxMTgsNzAsODYsMTU5LDEwXSxbMzcsMjMsMTYsMTU5LDE1XSxbNTgsNDIsMzYsMTU5LDddLFs4Nyw5MSw5MSwxNTksOV0sWzk0LDE2OSwyNSwyMzYsNV0sWzIxLDExOSwxMzYsMjM2LDldLFsxNjksNDgsMTU5LDIzNiwyXSxbMTksMTksMTksMTczLDBdLFsxNjksOTIsNTEsNSw0XSxbMjQ5LDIzNiw3OSw0MSwwXSxbNzQsNjAsOTEsMTU5LDExXSxbNDUsNDcsMTQzLDIzNiwxMV0sWzk2LDYwLDMyLDIzNiwxMl0sWzEzNSwxMDcsOTgsMTU5LDhdLFsxNjIsNzgsNzksMTU5LDZdLFs3Myw5MSwzNiwyMzYsMTNdLFs4LDEwLDE1LDIzNiwxNV0sWzIxMCwxNzgsMTYxLDE1OSwwXSxbMTU3LDEyOCw3OSw1LDBdLFszOSw2NywxMzgsMjIsMF0sWzE1OSwxNjQsMTc3LDgyLDBdLFsxMTMsMTA5LDEzOCwxNTksM10sWzIzMywxOTksNTUsMjM3LDRdLFsxNjIsODQsMzgsMTU5LDFdLFs3MCw3MywxNjcsMjM3LDExXSxbMjE0LDEwMSwxNDMsMjM2LDZdLFsxOTMsODQsMTg1LDIzNywyXSxbMjI3LDEzMiwzMiwyMzcsMV0sWzM2LDEzNywxOTksMjM2LDNdLFsxMDAsMzIsMTU2LDIzNiwxMF0sWzEyNiw4NSw1NCwyMzcsMTJdLFs3Nyw4MSw4NSwyMzcsN10sWzIyNiwyMjcsMjI4LDIzNywwXSxbMjI5LDE1MywxODEsMjM3LDZdLFsxNTUsMTU1LDE0OCwyMzcsOF0sWzI0MSwxNzUsMjEsMjM2LDRdLFs1NSw1OCw2MiwyMzYsN10sWzk3LDExOSw0NSwyMzcsMTNdLFsxNjgsNTQsNTEsMjM3LDE0XSxbMjA3LDIxMywyMTQsMjM2LDBdLFsxMjUsMTg5LDQyLDIzNyw1XSxbMjUsMjcsMzIsMjM3LDE1XSxbMTUwLDg4LDEwOSwxNTksMl0sWzM3LDE0OCwxNTcsMjM3LDldLFsyMjQsOTcsMSwyMzYsMV0sWzE0MiwzMywzMywyMzYsMTRdLFsxMjUsMTI1LDExNSwyMzYsOF0sWzIxLDIxLDI2LDM1LDE1XSxbMTI1LDEyNSwxMjUsMSwwXSxbMTgzLDE4MywxODYsMSw0XSxbMTU5LDExNSw5OCwxLDJdLFsyNDEsMTE4LDIwLDM1LDFdLFs1OCwxNzUsMjE3LDM1LDNdLFs2Myw2OCw3MiwzNSw3XSxbNTMsNTcsMTU3LDM1LDExXSxbMTMzLDEzMywxMzUsMSw2XV0='), true);
		$this->getLogger()->info("キャッシュ生成中");
		$sqrt = new \SplFixedArray(195076);
		for($y1 = 0 ; $y1 <  195076; $y1++){
			$sqrt[$y1] = sqrt($y1);
		}
		$getblock = new \SplFixedArray(count($woolColors));//82
		foreach($woolColors as $key => $wc){
			$getblock[$key] = Block::get($wc[3],$wc[4]);
		}
		$this->getLogger()->info("完了");
		$width  = imagesx($im);
		$height = imagesy($im);
		$this->getLogger()->info("たて".$width."よこ".$height);
		$cash = [];
		for($y = 0 ; $y < $height ; $y++){
			for($x = 0 ; $x < $width ; $x++){
				$rgb = imagecolorsforindex($im, imagecolorat($im, $x, $y));
				$selectedColor = 0;
				$minDist = null;
				$rgds = $rgb["red"].",".$rgb["green"].",".$rgb["blue"];
				if(isset($cash[$rgds])){
					$selectedColor = $cash[$rgds];
				}else{
				 	foreach($woolColors as $i => $woolcolor){
				 		$dist = $sqrt[($rgb["red"]-$woolcolor[0]) * ($rgb["red"]-$woolcolor[0]) + ($rgb["green"]-$woolcolor[1]) * ($rgb["green"]-$woolcolor[1]) + ($rgb["blue"]-$woolcolor[2]) * ($rgb["blue"]-$woolcolor[2])] / 1.73205080757;
				 	 	if($minDist ==  null||$minDist > $dist){
				 	 		$minDist = $dist;
				 	 		$selectedColor = $i;
				  		}
			 	 	}
		 		 	$cash[$rgds] = $selectedColor;
		 	 	}
				$level->setBlock(new Vector3($startx - $x,$starty - $y,$startz), $getblock[$selectedColor]);
			}
			$this->getLogger()->info($y);
		}
		$this->getServer()->broadcastMessage("[auto_dot] 処理が終了しました。");
	}
}