<?php

namespace item_undo;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandExecutor;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

//use pocketmine\event\player\PlayerDropItemEvent as dropevent;
//use pocketmine\event\player\PlayerInteractEvent as InteractEvent;
use pocketmine\Player;
use pocketmine\Server;

use pocketmine\item\item;
use pocketmine\level\level;
use pocketmine\block\Block;
use pocketmine\math\Vector3;

use pocketmine\utils\Config;

use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\CallbackTask;
use pocketmine\scheduler\Task;

class item_undo extends PluginBase implements Listener{
	//終了時
	//保持
	private $permission = [];//?
	//破棄
	private $tmp = [];
	private $times = [];
	public $items = [];//メモリ解放対象
	public $islock = false;//$this->itemsはアクセス禁止かどうか(守らなくても大丈夫)
	//設定
	//メモリ削減の為、定期的にメモリ解放を行う(3分/1回)
	//行わなかった場合は、再起動まで多くのメモリ使用。
	//行った場合は3分に1回CPU使用。
	public $release = true;
	
	//このプラグインのコマンドをユーザーが実行したときにコマンド処理と同時にメモリ解放を行う
	public $Advanced_release = false;//準備中(めんdなんて言わせない。)
	
	public function onEnable(){
	//$mine = Server::getInstance()->getPluginManager()->getPlugin("drop");
	/*if(!file_exists($dataFolder)){
		mkdir($dataFolder, 0744, true);
	}*/
		//$this->settings = new Config($dataFolder."settings.yml", Config::YAML);
		//$this->id = $this->settings->get("block_id");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if($this->release === true){
			$this->getLogger()->info("start cleaner....");
			$_time = 3;
			$task = new Tick_cleaner($this);
			$this->getServer()->getScheduler()->scheduleRepeatingTask($task,20*(60*$_time));
		}
		
	}
	/*public function pd(dropevent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		if(isset($permission[$name]) === false){
			return;
			//初めての要求 or 設定されていない。
		}
		if($permission[$name] === true){
			$item = $event->getItem();
			$event->setCancelled();
			//$player->getInventory()->getItemInHand()->getID();
			if($player->getInventory()->contains($item)){
				$player->getInventory()->addItem($item);////
			}
		}
	}*/
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $params){
		$user = $sender->getName();
		//print($user);
		switch($label){
			case "drop":
				if(isset($params[0]) === false){
					$this->help($sender);
					return true;
				}
				switch(mb_strtolower($params[0])){
					case "on":
					case "0":
					case "true":
					case "y":
					$permission[$user] = 0;
					break;
					
					case "off":
					case "1":
					case "false":
					//case "":
					$permission[$user] = 1;
					break;
					
					case "unset":
					case "u":
					$this->unset($sender);
					break;
					default:
					$this->help($sender);
					break;
				}
			break;
			case "undo";
				if(isset($params[0]) === false){
					$this->item_map($sender);
					break;
				}

//↓真夏の夜の淫夢にに対する抗体(完全にネタ要素)、嫌ならコメントアウトして、どうぞ。
$this->test($params[0],$sender);

	if($params[0] == "u"){
		$this->unset($sender);
		break;
	}

if($params[0] < -1 ||$params[0] > 10 || strpos($params[0],'\x00') !== false || strpos($params[0],'\0') !== false){
$this->item_map($sender);
break;
}
//↑セキュリティ対策。
			$this->undo($sender,preg_replace('/[^0-9]/', '',$params[0]));
			break;
		}
	return true;
	}
	
	/*public function onBlockTap(InteractEvent $event){
		
	}*/
	
	
	public function help($player){
		$state = "off";
		$name = $player->getName();
		if(isset($this->permission[$name]) === false) $this->permission[$name] = 1;//仮
		if($this->permission[$name] == 0) $state = "on";
		
		$player->sendMessage("§aドロップ禁止機能§r /drop [§eon§r || §eoff§r] /drop [§au§r|§aunset§r] 今手に持っているアイテムを§e消去§rします。\n §aドロップ禁止機能§r::§e${state}§r");
	}
	
	#unsetのコマンド処理
	public function unset($player){
		$item = $player->getInventory()->getItemInHand();
		//->getID();
		$name=$player->getName();
		
		
		//seiren.phpより
		$nowtime=microtime(true);
		if(isset($this->times[$name]) === true){
			$st = $this->times[$name]-$nowtime;
			if($st < -1){//1秒以上 <
				if($st < -20){//3秒以上>
					//$this->times[$name]=$nowtime;
					$this->help_item($player);
					return;
				}
			//1秒以上3秒未満の場合
			//$player->sendMessage("メッセージ");
			$this->Erase_item($player);
			unset($this->times[$name]);
			}else{
				//未満
				$player->sendMessage("警告をご確認ください。");
				return;
			}
			//
		}else{
		//タップ1回目の処理
			$this->times[$name]=$nowtime;
			$this->help_item($player);
			return;
		}
	}
		#unsetのコマンド処理-->アイテムデーター確認処理
	public function help_item($player){
		$item = $player->getInventory()->getItemInHand();
		$itemid = $item->getID();
		$customname =  $item->getName();
$player->sendMessage("§3id:${itemid} §e名前:${customname}§r を§d消去§rしようとしています。\nよろしければ§l1秒以上§r、§l20秒以内§lにに§l同じこと§rをしてください。");

	}

	#unsetのコマンド処理-->アイテムデーター確認処理-->アイテム消去処理
	public function Erase_item($player){
		$name = $player->getName();
		$item = $player->getInventory()->getItemInHand();
		$this->items[$name][] = ["expiration_date" => microtime(true),"backupitem" => $item];//bcadd(microtime(true),30,4)
			//名前、メインデータ
		$itemid = $item->getID();
		if($itemid === 0){
		$this->getLogger()->info("要求されたアイテムは無効アイテムです。");
		}
		if(count($this->items[$name]) >= 5){
			unset($this->items[$name][0]);
			$this->items[$name] = array_values($this->items[$name]);
		}
		//ガチャ.phpより
		$item1 = Item::get(0,0,0);
		$player->getInventory()->setItemInHand($item1);//アイテム上書き
		$player->getInventory()->sendContents($player);//アイテムスロット更新!!
		$customname =  $item->getName();
		$player->sendMessage("${customname}を§e削除§rしました。\n §eあやまって捨てたとき§rは、§d1分以内§rに§d/undo 0§rをしてください。");
	}
	public function item_map($player){
		$name = $player->getName();
		if(isset($this->items[$name]) === true){
			$player->sendMessage("読み込んでいます....");
			$return = "";
			foreach($this->items[$name] as $key => $date){//
$return = $return.":/undo ${key} , 名前::".$date["backupitem"]->getName()."復元の有効期限::".bcsub($date["expiration_date"],microtime(true))."秒\n";
			}
			$player->sendMessage("${return}復元は/undo 番号 をしてください。\n※§l有効期限§rは§lアイテムの復元§rが§l保証§rされる§l時間§rの事を指す。");
		}else{
			$player->sendMessage("表示出来るものは何もありません！\n§lアイテムを捨てる§rには §d/drop u§r や所定のブロックをタップ！");
		}
	}
	public function cleaner(){//メモリ解放(解放出来るものは全て)
	$this->getLogger()->info("クリーナーだよ!!!!");
	$islock = true;
		foreach($this->items as $key => $date){//
			foreach($date as $key1 => $date1){//
				if($this->items[$key][$key1]["expiration_date"]-microtime(true) < -60){//
	$this->getLogger()->info($this->items[$key][$key1]["expiration_date"]-microtime(true));
					unset($this->items[$key][$key1]);
				}//30秒以上
			}
		}
		$this->items = array_values($this->items);
		$islock = false;
	}
	public function undo($player,$no){
	$name = $player->getName();
		if(isset($this->items[$name][$no]) === true){
			if($player->getInventory()->canAddItem($this->items[$name][$no]["backupitem"])){
				$player->getInventory()->addItem($this->items[$name][$no]["backupitem"]);
				unset($this->items[$name][$no]);//無限増殖防止コード
				$this->items[$name] = array_values($this->items[$name]);//無限増殖防止コード
				//無限増殖防止コードは消さないください(´・ω・｀)
			}else{
				$this->items[$name][$no]["expiration_date"] = bcadd($this->items[$name][$no]["expiration_date"],15,4);
$player->sendMessage("§eインベントリ§rに§e空き§rがありません。要求された§eアイテム§rの§a有効期限を15秒§r伸ばしました。");
			}
		}else{
			$player->sendMessage("指定した番号は存在しません。");
			$this->item_map($player);
		} 
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//↓準下ネタ注意(114514等)
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function test($no,$player){
		switch($no){
			case "114514":
			case "810":
			case "1919":
			case 114514:
			case 810:
			case 1919:
				$player->sendMessage("[???] 心が濁ってますねぇ...");
			break;
		
			case "4545":
			case "0712":
			case 4545:
			case 0712:
				$player->sendMessage("[???] banされたいの...かな？(なおbanしない模様)");
			break;
			
			case "889464":
			case 889464:
				$player->sendMessage("[???] ないです");
			break;
			case "334":
			case 334:
				$player->sendMessage("[???] 何でや阪神関係ないやろ(なお本当に関係ない模様)");
			break;
		}
	}
}








class Tick_cleaner extends PluginTask{
	public function __construct(PluginBase $owner){
		parent::__construct($owner);
		$this->owner = $owner;
	}

	public function onRun($currentTick){
		//$owner = new item_undo();
		$this->owner->cleaner();
		//$owner->getLogger()->info("クリーナーだよ！！！！！");
	}
}

