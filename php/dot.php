<?php

namespace dot;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\CallbackTask;

class dot extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if(!file_exists($this->getDataFolder())){
			mkdir($this->getDataFolder(), 0744, true);
		}
		//phpinfo();
	}
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		switch(strtolower($label)) {
			case "do":
			if(!$sender->isOP()){
				$this->getLogger()->info($sender->getName()."はop権限がありません。");
				return true;
			}
				$this->getServer()->broadcastMessage("[auto_dot]処理を開始します");
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
					
					
				$this->autodot($im,$sender->getLevel(),$sender->x,$sender->y,$sender->z);
				$this->getServer()->broadcastMessage("[auto_dot] 処理が終了しました。");
				$this->getServer()->broadcastMessage("[auto_dot] 変更を開始します");
				return true;
			break;
		}
	}
	
	public function autodot($im,$level = null,$startx,$starty,$startz,$mode = 0,$mode1 = 0){
		$woolColors = json_decode(base64_decode('W1sxMTIsMTg1LDI2LDM1LDVdLFsxMTQsNzIsNDEsMzUsMTJdLFsyMzgsMTQxLDE3MiwzNSw2XSxbMTQyLDE0MiwxMzUsMzUsOF0sWzI0OSwxOTgsNDAsMzUsNF0sWzIzNCwyMzYsMjM3LDM1LDBdLFs4NSwxMTAsMjgsMzUsMTNdLFsxMjIsNDIsMTczLDM1LDEwXSxbMjEsMTM4LDE0NSwzNSw5XSxbMTY5LDg4LDMzLDEyLDFdLFsyMzYsMjMzLDIyNiwxNTUsMF0sWzEwNCw3OCw0Nyw1LDFdLFsxOTYsMTc5LDEyMyw1LDJdLFsyMTksMjExLDE2MCwxMiwwXSxbOTgsMjE5LDIxNCw1NywwXSxbMTU0LDExMCw3Nyw1LDNdLFsyMTksMjE5LDIxOSw0MiwwXSxbMTkwLDY5LDE4MCwzNSwyXSxbNjEsNDAsMTgsNSw1XSxbMTYxLDM5LDM1LDM1LDE0XSxbNzQsMTgxLDIxMywyMzcsM10sWzEzMiw1NiwxNzgsMjM3LDEwXSxbMTA0LDExOCw1MywxNTksNV0sWzE0Myw2MSw0NywxNTksMTRdLFs3Nyw1MSwzNiwxNTksMTJdLFs3Niw4Myw0MiwxNTksMTNdLFsxMTgsNzAsODYsMTU5LDEwXSxbMzcsMjMsMTYsMTU5LDE1XSxbNTgsNDIsMzYsMTU5LDddLFs4Nyw5MSw5MSwxNTksOV0sWzk0LDE2OSwyNSwyMzYsNV0sWzIxLDExOSwxMzYsMjM2LDldLFsxNjksNDgsMTU5LDIzNiwyXSxbMTksMTksMTksMTczLDBdLFsxNjksOTIsNTEsNSw0XSxbMjQ5LDIzNiw3OSw0MSwwXSxbNzQsNjAsOTEsMTU5LDExXSxbNDUsNDcsMTQzLDIzNiwxMV0sWzk2LDYwLDMyLDIzNiwxMl0sWzEzNSwxMDcsOTgsMTU5LDhdLFsxNjIsNzgsNzksMTU5LDZdLFs3Myw5MSwzNiwyMzYsMTNdLFs4LDEwLDE1LDIzNiwxNV0sWzIxMCwxNzgsMTYxLDE1OSwwXSxbMTU3LDEyOCw3OSw1LDBdLFszOSw2NywxMzgsMjIsMF0sWzE1OSwxNjQsMTc3LDgyLDBdLFsxMTMsMTA5LDEzOCwxNTksM10sWzIzMywxOTksNTUsMjM3LDRdLFsxNjIsODQsMzgsMTU5LDFdLFs3MCw3MywxNjcsMjM3LDExXSxbMjE0LDEwMSwxNDMsMjM2LDZdLFsxOTMsODQsMTg1LDIzNywyXSxbMjI3LDEzMiwzMiwyMzcsMV0sWzM2LDEzNywxOTksMjM2LDNdLFsxMDAsMzIsMTU2LDIzNiwxMF0sWzEyNiw4NSw1NCwyMzcsMTJdLFs3Nyw4MSw4NSwyMzcsN10sWzIyNiwyMjcsMjI4LDIzNywwXSxbMjI5LDE1MywxODEsMjM3LDZdLFsxNTUsMTU1LDE0OCwyMzcsOF0sWzI0MSwxNzUsMjEsMjM2LDRdLFs1NSw1OCw2MiwyMzYsN10sWzk3LDExOSw0NSwyMzcsMTNdLFsxNjgsNTQsNTEsMjM3LDE0XSxbMjA3LDIxMywyMTQsMjM2LDBdLFsxMjUsMTg5LDQyLDIzNyw1XSxbMjUsMjcsMzIsMjM3LDE1XSxbMTUwLDg4LDEwOSwxNTksMl0sWzM3LDE0OCwxNTcsMjM3LDldLFsyMjQsOTcsMSwyMzYsMV0sWzE0MiwzMywzMywyMzYsMTRdLFsxMjUsMTI1LDExNSwyMzYsOF0sWzIxLDIxLDI2LDM1LDE1XSxbMTI1LDEyNSwxMjUsMSwwXSxbMTgzLDE4MywxODYsMSw0XSxbMTU5LDExNSw5OCwxLDJdLFsyNDEsMTE4LDIwLDM1LDFdLFs1OCwxNzUsMjE3LDM1LDNdLFs2Myw2OCw3MiwzNSw3XSxbNTMsNTcsMTU3LDM1LDExXSxbMTMzLDEzMywxMzUsMSw2XV0='), true);
		$getblock = new \SplFixedArray(count($woolColors));//82
		foreach($woolColors as $key => $wc)
			$getblock[$key] = Block::get($wc[3],$wc[4]);
		$width  = imagesx($im);
		$height = imagesy($im);
		$this->getLogger()->info("たて".$width."よこ".$height);
		$cache = [];
		$return = [];
		for($y = 0 ; $y < $height ; $y++){
			for($x = 0 ; $x < $width ; $x++){
				$data = imagecolorat($im,$x,$y);
				$r = ($data >> 16) & 0xFF;
				$g = ($data >> 8) & 0xFF;
				$b = $data & 0xFF;
				
				//$a = ($data & 0x7F000000) >> 24;
				//if($a === 127) continue;//完全に透明の場合は無視
				
				$selectedColor = 0;
				$minDist = -1;
				if(isset($cache[$data])){
					$selectedColor = $cache[$data];
				}else{
				 	foreach($woolColors as $i => $woolcolor){
				 		$dist = ($r-$woolcolor[0]) * ($r-$woolcolor[0]) + ($g-$woolcolor[1]) * ($g-$woolcolor[1]) + ($b-$woolcolor[2]) * ($b-$woolcolor[2]);//β
				 		if($minDist === -1||$minDist > $dist){
				 			$minDist = $dist;
				 			$selectedColor = $i;
				 		}
				 	}
		 		 	$cache[$data] = $selectedColor;
		 		}
		 		$return[$y][] = $selectedColor;
			}
			//$this->getLogger()->info($y);
		}
	$this->getServer()->getScheduler()->scheduleDelayedTask(new CallbackTask(
		[$this, "autodot_run"],
		[$return,$startx,$starty,$startz,$getblock,$level]
	),5);//メッセージ送信
	//$this->autodot_run($return,$startx,$starty,$startz,$getblock,$level);
	}
	public function autodot_run($data,$startx,$starty,$startz,$getblock,$level){
		$vector = new Vector3($startx, $starty, $startz);
		foreach($data as $y => $data_y){
			foreach($data_y as $x => $color){
				$vector->y = $starty - $y;
				$vector->x = $startx - $x;
				$level->setBlock($vector, $getblock[$color], false, false);
			}
		}
		$this->getServer()->broadcastMessage("変更が終了しました。");
	}
}
