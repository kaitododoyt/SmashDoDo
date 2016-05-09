<?php
# plugin hecho por KaitoDoDo ®Apache License®, unauthorized copy is strictly prohibited and may be penalized
namespace KaitoDoDo\Smash;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\PluginTask;
use pocketmine\event\Listener;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat as TE;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\tile\Sign;
use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\entity\Effect;
use pocketmine\tile\Chest;
use pocketmine\inventory\ChestInventory;

class Smash extends PluginBase implements Listener {

        public $prefix = TE::DARK_PURPLE."[".TE::AQUA."SMASHDoDo".TE::DARK_PURPLE."]";
        public $mode = 0;
	public $levels = array();
	public $currentLevel = "";
        
	public function onEnable()
	{
		$this->getLogger()->info(TE::AQUA."SmashDoDo".TE::BLUE." by KaitoDoDo");
                $this->getServer()->getPluginManager()->registerEvents($this ,$this);
                @mkdir($this->getDataFolder());
                $config2 = new Config($this->getDataFolder() . "/rank.yml", Config::YAML);
		$config2->save();
                $config = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
                if($config->get("arenas")!=null)
		{
			$this->levels = $config->get("arenas");
		}
		foreach($this->levels as $lev)
		{
			$this->getServer()->loadLevel($lev);
		}
		$items = array(array(257,241,1),array(258,241,1),array(259,0,1),array(260,0,5),array(261,0,1),array(262,0,5),array(267,241,1),array(268,40,1),array(270,40,1),array(271,40,1),array(272,120,1),array(274,120,1),array(275,120,1),array(276,1555,1),array(278,1555,1),array(279,1555,1),array(283,18,1),array(285,18,1),array(286,18,1),array(297,0,3),array(354,0,1));
		if($config->get("chestitems")==null)
		{
			$config->set("chestitems",$items);
		}
		$config->save();
                $slots = new Config($this->getDataFolder() . "/slots.yml", Config::YAML);
                $slots->save();
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new GameSender($this), 20);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new RefreshSigns($this), 10);
                }
        
                public function onDeath(PlayerDeathEvent $event){
                $jugador = $event->getEntity();
                $mapa = $jugador->getLevel()->getFolderName();
                if(in_array($mapa,$this->levels))
		{
                if($event->getEntity()->getLastDamageCause() instanceof EntityDamageByEntityEvent)
                {
                $asassin = $event->getEntity()->getLastDamageCause()->getDamager();
                if($asassin instanceof Player){
                $event->setDeathMessage("");
                foreach($jugador->getLevel()->getPlayers() as $pl){
                                $muerto = $jugador->getNameTag();
                                $asesino = $asassin->getNameTag();
				$pl->sendMessage(TE::RED . $muerto . TE::YELLOW . " was exterminated by " . TE::GREEN . $asesino . TE::YELLOW . ".");
			}
                }
                }
                $jugador->setNameTag($jugador->getName());
                $jugador->setMaxHealth(20);
                }
        }
        
        public function onMove(PlayerMoveEvent $event)
	{
		$player = $event->getPlayer();
		$level = $player->getLevel()->getFolderName();
		if(in_array($level,$this->levels))
		{
			$config = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
			$sofar = $config->get($level . "StartTime");
			if($sofar > 0)
			{
                            $from = $event->getFrom();
                            $to = $event->getTo();
                            if($from->x !== $to->x or $from->z !== $to->z)
                            {
                                $event->setCancelled();
                            }
			}
		}
	}
        
        public function onLog(PlayerLoginEvent $event)
	{
		$player = $event->getPlayer();
                if(in_array($player->getLevel()->getFolderName(),$this->levels))
		{
		$player->getInventory()->clearAll();
		$spawn = $this->getServer()->getDefaultLevel()->getSafeSpawn();
		$this->getServer()->getDefaultLevel()->loadChunk($spawn->getFloorX(), $spawn->getFloorZ());
		$player->teleport($spawn,0,0);
                }
	}
        
        public function onQuit(PlayerQuitEvent $event)
        {
            $pl = $event->getPlayer();
            $level = $pl->getLevel()->getFolderName();
            if(in_array($level,$this->levels))
            {
                $pl->removeAllEffects();
                $pl->getInventory()->clearAll();
                $slots = new Config($this->getDataFolder() . "/slots.yml", Config::YAML);
                $pl->setNameTag($pl->getName());
                $pl->setMaxHealth(20);
                if($slots->get("slot1".$level)==$pl->getName())
                {
                    $slots->set("slot1".$level, 0);
                }
                if($slots->get("slot2".$level)==$pl->getName())
                {
                    $slots->set("slot2".$level, 0);
                }
                if($slots->get("slot3".$level)==$pl->getName())
                {
                    $slots->set("slot3".$level, 0);
                }
                if($slots->get("slot4".$level)==$pl->getName())
                {
                    $slots->set("slot4".$level, 0);
                }
                if($slots->get("slot5".$level)==$pl->getName())
                {
                    $slots->set("slot5".$level, 0);
                }
                if($slots->get("slot6".$level)==$pl->getName())
                {
                    $slots->set("slot6".$level, 0);
                }
                if($slots->get("slot7".$level)==$pl->getName())
                {
                    $slots->set("slot7".$level, 0);
                }
                if($slots->get("slot8".$level)==$pl->getName())
                {
                    $slots->set("slot8".$level, 0);
                }
                $slots->save();
            }
        }
        
        public function onBlockBreak(BlockBreakEvent $event)
	{
		$player = $event->getPlayer();
		$level = $player->getLevel()->getFolderName();
		if(in_array($level,$this->levels))
		{
                    $event->setCancelled(true);
		}
	}
	
	public function onBlockPlace(BlockPlaceEvent $event)
	{
		$player = $event->getPlayer();
		$level = $player->getLevel()->getFolderName();
		if(in_array($level,$this->levels))
		{
                    $event->setCancelled();
		}
	}
        
        public function onDamag(EntityDamageEvent $event) {
            if ($event instanceof EntityDamageByEntityEvent) {
                if ($event->getEntity() instanceof Player && $event->getDamager() instanceof Player) {
                     $golpeado = $event->getEntity();
                     $level = $golpeado->getLevel()->getFolderName();
                     if(in_array($level,$this->levels))
                     {
                         if($golpeado->getHealth()>=95)
                         {
                         $event->setKnockback(0);
                         }
                         elseif($golpeado->getHealth()>=90)
                         {
                         $event->setKnockback(0.1);
                         }
                         elseif($golpeado->getHealth()>=85)
                         {
                         $event->setKnockback(0.2);
                         }
                         elseif($golpeado->getHealth()>=80)
                         {
                         $event->setKnockback(0.4);
                         }
                         elseif($golpeado->getHealth()>=75)
                         {
                         $event->setKnockback(0.6);
                         }
                         elseif($golpeado->getHealth()>=70)
                         {
                         $event->setKnockback(0.8);
                         }
                         elseif($golpeado->getHealth()>=65)
                         {
                         $event->setKnockback(1.0);
                         }
                         elseif($golpeado->getHealth()>=60)
                         {
                         $event->setKnockback(1.2);
                         }
                         elseif($golpeado->getHealth()>=55)
                         {
                         $event->setKnockback(1.4);
                         }
                         elseif($golpeado->getHealth()>=50)
                         {
                         $event->setKnockback(1.6);
                         }
                         elseif($golpeado->getHealth()>=40)
                         {
                         $event->setKnockback(2.0);
                         }
                         elseif($golpeado->getHealth()>=30)
                         {
                         $event->setKnockback(2.4);
                         }
                         elseif($golpeado->getHealth()>=20)
                         {
                         $event->setKnockback(2.8);
                         }
                         elseif($golpeado->getHealth()>=10)
                         {
                         $event->setKnockback(3.2);
                         }
                     }
                     
                }
            }
        }
        
        public function onCommand(CommandSender $player, Command $cmd, $label, array $args) {
        switch($cmd->getName()){
			case "smash":
				if($player->isOp())
				{
					if(!empty($args[0]))
					{
						if($args[0]=="make")
						{
							if(!empty($args[1]))
							{
								if(file_exists($this->getServer()->getDataPath() . "/worlds/" . $args[1]))
								{
									$this->getServer()->loadLevel($args[1]);
									$this->getServer()->getLevelByName($args[1])->loadChunk($this->getServer()->getLevelByName($args[1])->getSafeSpawn()->getFloorX(), $this->getServer()->getLevelByName($args[1])->getSafeSpawn()->getFloorZ());
									array_push($this->levels,$args[1]);
									$this->currentLevel = $args[1];
									$this->mode = 1;
									$player->sendMessage($this->prefix . "Touch the spawn points!");
									$player->setGamemode(1);
									$player->teleport($this->getServer()->getLevelByName($args[1])->getSafeSpawn(),0,0);
								}
								else
								{
									$player->sendMessage($this->prefix . "ERROR missing world.");
								}
							}
							else
							{
								$player->sendMessage($this->prefix . "ERROR missing parameters.");
							}
						}
						else
						{
							$player->sendMessage($this->prefix . "Invalid Command.");
						}
					}
					else
					{
					 $player->sendMessage($this->prefix . "SmashDoDo Commands!");
                                         $player->sendMessage($this->prefix . "/smash make [world]: Create smash game!");
                                         $player->sendMessage($this->prefix . "/smstart: start the game");
					}
				}
				else
				{
				}
			return true;
			
                        case "smstart":
                            if($player->isOp())
				{
                                $player->sendMessage(TE::DARK_PURPLE."Starting in 10 sec...");
                                $config = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
                                $config->set("arenas",$this->levels);
                                foreach($this->levels as $arena)
                                {
                                    $config->set($arena . "PlayTime", 780);
                                    $config->set($arena . "StartTime", 10);
                                }
                                $config->save();
                                }
                                return true;
	}
        }
        
        public function onInteract(PlayerInteractEvent $event)
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$tile = $player->getLevel()->getTile($block);
		
		if($tile instanceof Sign) 
		{
			if($this->mode==26)
			{
				$tile->setText(TE::AQUA . "[Join]",TE::GREEN  . "0 / 8","§f" . $this->currentLevel,$this->prefix);
				$this->refreshArenas();
				$this->currentLevel = "";
				$this->mode = 0;
				$player->sendMessage($this->prefix . "Arena Registered!");
			}
			else
			{
				$text = $tile->getText();
				if($text[3] == $this->prefix)
				{
					if($text[0]==TE::AQUA . "[Join]")
					{
						$config = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
                                                $slots = new Config($this->getDataFolder() . "/slots.yml", Config::YAML);
                                                $namemap = str_replace("§f", "", $text[2]);
						$level = $this->getServer()->getLevelByName($namemap);
                                                if($slots->get("slot1".$namemap)==null)
                                                {
                                                        $thespawn = $config->get($namemap . "Spawn1");
                                                        $slots->set("slot1".$namemap, $player->getName());
                                                        $slots->save();
                                                }
                                                else if($slots->get("slot2".$namemap)==null)
                                                {
                                                        $thespawn = $config->get($namemap . "Spawn2");
                                                        $slots->set("slot2".$namemap, $player->getName());
                                                        $slots->save();
                                                }
                                                else if($slots->get("slot3".$namemap)==null)
                                                {
                                                        $thespawn = $config->get($namemap . "Spawn3");
                                                        $slots->set("slot3".$namemap, $player->getName());
                                                        $slots->save();
                                                }
                                                else if($slots->get("slot4".$namemap)==null)
                                                {
                                                        $thespawn = $config->get($namemap . "Spawn4");
                                                        $slots->set("slot4".$namemap, $player->getName());
                                                        $slots->save();
                                                }
                                                else if($slots->get("slot5".$namemap)==null)
                                                {
                                                        $thespawn = $config->get($namemap . "Spawn5");
                                                        $slots->set("slot5".$namemap, $player->getName());
                                                        $slots->save();
                                                }
                                                else if($slots->get("slot6".$namemap)==null)
                                                {
                                                        $thespawn = $config->get($namemap . "Spawn6");
                                                        $slots->set("slot6".$namemap, $player->getName());
                                                        $slots->save();
                                                }
                                                else if($slots->get("slot7".$namemap)==null)
                                                {
                                                        $thespawn = $config->get($namemap . "Spawn7");
                                                        $slots->set("slot7".$namemap, $player->getName());
                                                        $slots->save();
                                                }
                                                else if($slots->get("slot8".$namemap)==null)
                                                {
                                                        $thespawn = $config->get($namemap . "Spawn8");
                                                        $slots->set("slot8".$namemap, $player->getName());
                                                        $slots->save();
                                                }
                                                else
                                                {
                                                    $player->sendMessage($this->prefix .TE::RED. "SmashDoDo without slots");
                                                    goto ter;
                                                }
                                                $player->sendMessage($this->prefix . "Ready to SmashDoDo?");
                                                foreach($level->getPlayers() as $playersinarena)
                                                        {
                                                        $playersinarena->sendMessage($player->getName() . TE::DARK_AQUA . " has enter in the battle");
                                                        }
						$spawn = new Position($thespawn[0]+0.5,$thespawn[1],$thespawn[2]+0.5,$level);
						$level->loadChunk($spawn->getFloorX(), $spawn->getFloorZ());
						$player->teleport($spawn,0,0);
						$player->getInventory()->clearAll();
                                                $player->removeAllEffects();
                                                $player->setMaxHealth(100);
                                                $player->setHealth(100);
                                                $player->setFood(20);
                                                $player->setNameTag($player->getName().TE::GREEN." [100/100]");
                                                if((strpos($player->getNameTag(), "§8[§6VIP§a+§8]") !== false)||(strpos($player->getNameTag(), "§8[§fYou§cTuber§a+§8]") !== false))
                                                {
                                                $salto = Effect::getEffect(8);
                                                $salto->setAmplifier(6);
                                                $salto->setVisible(true);
                                                $salto->setDuration(1000000);
                                                $player->addEffect($salto);
                                                $speed = Effect::getEffect(1);
                                                $speed->setAmplifier(5);
                                                $speed->setVisible(true);
                                                $speed->setDuration(1000000);
                                                $player->addEffect($speed);
                                                }
                                                else
                                                {
                                                $salto = Effect::getEffect(8);
                                                $salto->setAmplifier(5);
                                                $salto->setVisible(true);
                                                $salto->setDuration(1000000);
                                                $player->addEffect($salto);
                                                $speed = Effect::getEffect(1);
                                                $speed->setAmplifier(4);
                                                $speed->setVisible(true);
                                                $speed->setDuration(1000000);
                                                $player->addEffect($speed);
                                                }
                                                ter:
					}
					else
					{
						$player->sendMessage($this->prefix . "You cannot join");
					}
				}
			}
		}
		else if($this->mode>=1&&$this->mode<=7)
		{
			$config = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
			$config->set($this->currentLevel . "Spawn" . $this->mode, array($block->getX(),$block->getY()+1,$block->getZ()));
			$player->sendMessage($this->prefix . "Spawn " . $this->mode . " has been registered!");
			$this->mode++;
			$config->save();
		}
		else if($this->mode==8)
		{
			$config = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
			$config->set($this->currentLevel . "Spawn" . $this->mode, array($block->getX(),$block->getY()+1,$block->getZ()));
			$player->sendMessage($this->prefix . "Spawn " . $this->mode . " has been registered!");
			$config->set("arenas",$this->levels);
			$player->sendMessage($this->prefix . "Touch Sign to register Arena!");
			$spawn = $this->getServer()->getDefaultLevel()->getSafeSpawn();
			$this->getServer()->getDefaultLevel()->loadChunk($spawn->getFloorX(), $spawn->getFloorZ());
			$player->teleport($spawn,0,0);
			$config->save();
			$this->mode=26;
		}
	}
	
	public function refreshArenas()
	{
		$config = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
		$config->set("arenas",$this->levels);
		foreach($this->levels as $arena)
		{
                    $config->set($arena . "PlayTime", 780);
                    $config->set($arena . "StartTime", 90);
		}
		$config->save();
	}
        
        public function onEntityDamage(EntityDamageEvent $event){
		$player = $event->getEntity();
                $level = $player->getLevel()->getFolderName();
		if(in_array($level,$this->levels))
		{
		if($player instanceof Player){
			$this->actNameTag($player);
		}
                }
	}
	
	public function onEntityRegainHealth(EntityRegainHealthEvent $event){
		$player = $event->getEntity();
                $level = $player->getLevel()->getFolderName();
		if(in_array($level,$this->levels))
		{
		if($player instanceof Player){
			$this->actNameTag($player);
		}
                }
	}
        
        public function actNameTag($player){
            $hp = $player->getHealth();
            if($hp>=70)
            {
            $player->setNameTag($player->getName().TE::GREEN." [".$player->getHealth()."/".$player->getMaxHealth()."]");
            }
            elseif($hp>=50)
            {
            $player->setNameTag($player->getName().TE::GOLD." [".$player->getHealth()."/".$player->getMaxHealth()."]");
            }
            elseif($hp>=30)
            {
            $player->setNameTag($player->getName().TE::RED." [".$player->getHealth()."/".$player->getMaxHealth()."]");
            }
            elseif($hp>=0)
            {
            $player->setNameTag($player->getName().TE::DARK_RED." [".$player->getHealth()."/".$player->getMaxHealth()."]");
            }
	}
}

class RefreshSigns extends PluginTask {
    public $prefix = TE::DARK_PURPLE."[".TE::AQUA."SMASHDoDo".TE::DARK_PURPLE."]";
	public function __construct($plugin)
	{
		$this->plugin = $plugin;
		parent::__construct($plugin);
	}
  
	public function onRun($tick)
	{
		$allplayers = $this->plugin->getServer()->getOnlinePlayers();
		$level = $this->plugin->getServer()->getDefaultLevel();
		$tiles = $level->getTiles();
		foreach($tiles as $t) {
			if($t instanceof Sign) {	
				$text = $t->getText();
				if($text[3]==$this->prefix)
				{
					$aop = 0;
                                        $namemap = str_replace("§f", "", $text[2]);
					foreach($allplayers as $player){if($player->getLevel()->getFolderName()==$namemap){$aop=$aop+1;}}
					$ingame = TE::AQUA . "[Join]";
					$config = new Config($this->plugin->getDataFolder() . "/config.yml", Config::YAML);
					if($config->get($namemap . "PlayTime")!=780)
					{
						$ingame = TE::DARK_PURPLE . "[Running]";
					}
					else if($aop>=8)
					{
						$ingame = TE::GOLD . "[Full]";
					}
					$t->setText($ingame,TE::GREEN  . $aop . " / 8",$text[2],$this->prefix);
				}
			}
		}
	}
}

class GameSender extends PluginTask {
    public $prefix = TE::DARK_PURPLE."[".TE::AQUA."SMASHDoDo".TE::DARK_PURPLE."]";
	public function __construct($plugin)
	{
		$this->plugin = $plugin;
		parent::__construct($plugin);
	}
  
	public function onRun($tick)
	{
		$config = new Config($this->plugin->getDataFolder() . "/config.yml", Config::YAML);
		$arenas = $config->get("arenas");
		if(!empty($arenas))
		{
			foreach($arenas as $arena)
			{
				$time = $config->get($arena . "PlayTime");
				$timeToStart = $config->get($arena . "StartTime");
				$levelArena = $this->plugin->getServer()->getLevelByName($arena);
				if($levelArena instanceof Level)
				{
					$playersArena = $levelArena->getPlayers();
					if(count($playersArena)==0)
					{
						$config->set($arena . "PlayTime", 780);
						$config->set($arena . "StartTime", 90);
					}
					else
					{
						if(count($playersArena)>=2)
						{
							if($timeToStart>0)
							{
								$timeToStart--;
								foreach($playersArena as $pl)
								{
									$pl->sendPopup(TE::GREEN . $timeToStart .TE::YELLOW. " seconds to start".TE::RESET);
								}
                                                                if($timeToStart==89)
                                                                {
                                                                    $levelArena->setTime(7000);
                                                                    $levelArena->stopTime();
                                                                }
                                                                if($timeToStart<=0)
                                                                {
                                                                    $levelArena->setTime(7000);
                                                                    $levelArena->stopTime();
                                                                    $slots = new Config($this->plugin->getDataFolder() . "/slots.yml", Config::YAML);
                                                                    $slots->set("slot1".$arena, 0);
                                                                    $slots->set("slot2".$arena, 0);
                                                                    $slots->set("slot3".$arena, 0);
                                                                    $slots->set("slot4".$arena, 0);
                                                                    $slots->set("slot5".$arena, 0);
                                                                    $slots->set("slot6".$arena, 0);
                                                                    $slots->set("slot7".$arena, 0);
                                                                    $slots->set("slot8".$arena, 0);
                                                                    $slots->save();
                                                                }
								$config->set($arena . "StartTime", $timeToStart);
							}
							else
							{
								$aop = count($levelArena->getPlayers());
								if($aop==1)
								{
									foreach($playersArena as $pl)
									{
										$this->getOwner()->getServer()->broadcastMessage($this->prefix.TE::GREEN.$pl->getName().TE::AQUA." Won SmashDoDoBros");
										$pl->getInventory()->clearAll();
										$pl->removeAllEffects();
										$pl->teleport($this->getOwner()->getServer()->getDefaultLevel()->getSafeSpawn(),0,0);
                                                                                $pl->setMaxHealth(20);
                                                                                $pl->setHealth(20);
                                                                                $pl->setFood(20);
                                                                                $pl->setNameTag($pl->getName());
                                                                                }
                                                                                $config->set($arena . "PlayTime", 780);
                                                                                $config->set($arena . "StartTime", 90);
								}
								$time--;
                                                                if($time == 779)
                                                                {
                                                                    foreach($playersArena as $pl)
									{
                                                                        $pl->sendMessage("§bSmashDoDo is starting");
                                                                        $pl->sendMessage("§aPick a weapon of the chests");
                                                                        }
                                                                        $this->cofres($levelArena);
                                                                }
                                                                if($time == 600)
                                                                {
                                                                    foreach($playersArena as $pl)
									{
                                                                        $pl->sendMessage("§aPick a weapon of the chests");
                                                                        }
                                                                        $this->cofres($levelArena);
                                                                }
                                                                if($time == 550)
								{
									foreach($playersArena as $pl)
									{
										$pl->sendMessage("§e>--------------------------");
                                                                                $pl->sendMessage("§e>§bSmashDoDo by KaitoDoDoYT");
                                                                                $pl->sendMessage("§e>--------------------------");
									}
								}
                                                                if($time == 400)
                                                                {
                                                                    foreach($playersArena as $pl)
									{
                                                                        $pl->sendMessage("§aPick a weapon of the chests");
                                                                        }
                                                                        $this->cofres($levelArena);
                                                                }
                                                                if($time == 200)
                                                                {
                                                                    foreach($playersArena as $pl)
									{
                                                                        $pl->sendMessage("§aPick a weapon of the chests");
                                                                        }
                                                                        $this->cofres($levelArena);
                                                                }
								if($time>=300)
								{
								$time2 = $time - 180;
								$minutes = $time2 / 60;
								}
								else
								{
									$minutes = $time / 60;
									if(is_int($minutes) && $minutes>0)
									{
										foreach($playersArena as $pl)
										{
											$pl->sendMessage($this->prefix .TE::YELLOW. $time .TE::DARK_AQUA. " minutes remaining");
										}
									}
									else if($time == 30 || $time == 15 || $time == 10 || $time ==5 || $time ==4 || $time ==3 || $time ==2 || $time ==1)
									{
										foreach($playersArena as $pl)
										{
											$pl->sendMessage($this->prefix .TE::YELLOW. $time .TE::DARK_AQUA. " seconds remaining");
										}
									}
									if($time <= 0)
									{
										foreach($playersArena as $pl)
										{
											$pl->teleport($this->plugin->getServer()->getDefaultLevel()->getSafeSpawn(),0,0);
											$pl->sendMessage($this->prefix .TE::GOLD."SmashDoDoBros without Winners".$arena);
											$pl->getInventory()->clearAll();
                                                                                        $pl->removeAllEffects();
                                                                                        $pl->setFood(20);
                                                                                        $pl->setMaxHealth(20);
                                                                                        $pl->setHealth(20);
                                                                                        $pl->setNameTag($pl->getName());
										}
										$time = 780;
									}
								}
								$config->set($arena . "PlayTime", $time);
							}
						}
						else
						{
							if($timeToStart<=0)
							{
								foreach($playersArena as $pl)
								{
									$this->getOwner()->getServer()->broadcastMessage($this->prefix.TE::GREEN.$pl->getName().TE::AQUA." Gano SmashDoDoBros");
									$pl->teleport($this->getOwner()->getServer()->getDefaultLevel()->getSafeSpawn(),0,0);
									$pl->getInventory()->clearAll();
                                                                        $pl->removeAllEffects();
                                                                        $pl->setMaxHealth(20);
                                                                        $pl->setHealth(20);
                                                                        $pl->setFood(20);
                                                                        $pl->setNameTag($pl->getName());
								}
								$config->set($arena . "PlayTime", 780);
								$config->set($arena . "StartTime", 90);
							}
							else
							{
								foreach($playersArena as $pl)
								{
									$pl->sendPopup(TE::DARK_AQUA ."Need more Fighters".TE::RESET);
								}
								$config->set($arena . "PlayTime", 780);
								$config->set($arena . "StartTime", 90);
							}
						}
					}
				}
			}
		}
		$config->save();
	}
        
        public function cofres($level)
	{
		$config = new Config($this->plugin->getDataFolder() . "/config.yml", Config::YAML);
		$tiles = $level->getTiles();
		foreach($tiles as $t) {
			if($t instanceof Chest) 
			{
				$chest = $t;
				$chest->getInventory()->clearAll();
				if($chest->getInventory() instanceof ChestInventory)
				{
					for($i=0;$i<=26;$i++)
					{
						$rand = rand(1,4);
						if($rand==1)
						{
							$k = array_rand($config->get("chestitems"));
							$v = $config->get("chestitems")[$k];
							$chest->getInventory()->setItem($i, Item::get($v[0],$v[1],$v[2]));
						}
					}									
				}
			}
		}
	}
}