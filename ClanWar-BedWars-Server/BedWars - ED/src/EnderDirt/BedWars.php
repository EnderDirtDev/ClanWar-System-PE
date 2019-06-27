<?php

namespace EnderDirt;

//Base
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
//Utils
use pocketmine\utils\TextFormat as Color;
use pocketmine\utils\Config;
//EventListener
use pocketmine\event\Listener;
//PlayerEvents
use pocketmine\Player;
use pocketmine\event\player\PlayerHungerChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerBedEnterEvent;
//ItemUndBlock
use pocketmine\block\Block;
use pocketmine\item\Item;
//BlockEvents
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
//EntityEvents
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\entity\Effect;
//Level
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
//Sounds
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\level\sound\GhastSound;
//Commands
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
//Tile
use pocketmine\tile\Sign;
use pocketmine\tile\Chest;
use pocketmine\tile\Tile;
//Nbt
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
//Inventar
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\Inventory;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\inventory\CraftItemEvent;
//Scoreboard
use Scoreboards\Scoreboards;

class BedWars extends PluginBase implements Listener {

    public $prefix = Color::AQUA . "BedWars" . Color::DARK_GRAY . " : ";
    public $arenaname = "";
    public $mode = 0;
    public $players = 0;

    public function onEnable()
    {

        if (is_dir($this->getDataFolder()) !== true) {

            mkdir($this->getDataFolder());

        }

        if (is_dir("/home/ClanWars/BedWars") !== true) {

            mkdir("/home/ClanWars/BedWars");

        }

        if (is_dir("/home/ClanWars/BedWars/players") !== true) {

            mkdir("/home/ClanWars/BedWars/players");

        }

        if (is_dir($this->getDataFolder() . "/maps") !== true) {

            mkdir($this->getDataFolder() . "/maps");

        }

        $this->saveDefaultConfig();
        $this->reloadConfig();

        $config = $this->getConfig();
        $config->set("Ingame", false);
        $config->set("Reset", false);
        $config->set("ResetTime", 15);
        $config->set("WaitTime", 10);
        $config->set("PlayTime", 3600);
        $config->set("Players", 0);
        $config->set("Win", "-");
        $config->set("Blau", 0);
        $config->set("Rot", 0);
        $config->set("BlauBett", false);
        $config->set("RotBett", false);
        $config->set("player1" , "");
        $config->set("player2" , "");
        $config->set("player3" , "");
        $config->set("player4" , "");
        $config->set("player5" , "");
        $config->set("player6" , "");
        $config->set("player7" , "");
        $config->set("player8" , "");
        $config->save();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getScheduler()->scheduleRepeatingTask(new GameSender($this), 20);
        $this->getScheduler()->scheduleRepeatingTask(new PlayerSender($this), 10);
        $this->getScheduler()->scheduleRepeatingTask(new DropBronze($this), 15);
        $this->getScheduler()->scheduleRepeatingTask(new DropIron($this), 250);
        $this->getScheduler()->scheduleRepeatingTask(new DropGold($this), 600);
        $this->getLogger()->info($this->prefix . Color::GRAY . "wurde aktiviert!");
        $this->getLogger()->info($this->prefix . Color::GRAY . "Made By" . Color::DARK_PURPLE . " EnderDirt!");

    }

    public function copymap($src, $dst) {

        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {

            if (($file != '.') && ($file != '..')) {

                if (is_dir($src . '/' . $file)) {

                    $this->copymap($src . '/' . $file, $dst . '/' . $file);

                } else {

                    copy($src . '/' . $file, $dst . '/' . $file);

                }

            }

        }

        closedir($dir);

    }

    public function deleteDirectory($dirPath) {

        if (is_dir($dirPath)) {

            $objects = scandir($dirPath);
            foreach ($objects as $object) {

                if ($object != "." && $object != "..") {

                    if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {

                        $this->deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);

                    } else {

                        unlink($dirPath . DIRECTORY_SEPARATOR . $object);

                    }

                }

            }

            reset($objects);
            rmdir($dirPath);

        }

    }

    public function deletePlayerFromArena(Player $player) {

        $config = $this->getConfig();
        if ($player->getName() === $config->get("player1")) {

            $config->set("player1", "");
            $config->save();

        } else if ($player->getName() === $config->get("player2")) {

            $config->set("player2", "");
            $config->save();

        } else if ($player->getName() === $config->get("player3")) {

            $config->set("player3", "");
            $config->save();

        } else if ($player->getName() === $config->get("player4")) {

            $config->set("player4", "");
            $config->save();

        } else if ($player->getName() === $config->get("player5")) {

            $config->set("player5", "");
            $config->save();

        } else if ($player->getName() === $config->get("player6")) {

            $config->set("player6", "");
            $config->save();

        } else if ($player->getName() === $config->get("player7")) {

            $config->set("player7", "");
            $config->save();

        } else if ($player->getName() === $config->get("player8")) {

            $config->set("player8", "");
            $config->save();

        } else {

            $this->getServer()->broadcastMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::RED . "Es ist ein Fehler aufgetreten! (#ERROR02)");

        }

    }

    public function giveKit(Player $player)
    {

        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();

    }

    public function spawn(Player $player) {

        $pos = $player->getPosition();
        $player->setSpawn($pos);

    }

    public function teleportIngame(Player $player) {

        $config = $this->getConfig();
        $level = $this->getServer()->getLevelByName($config->get("Arena"));
        $af = new Config($this->getDataFolder() . "/" . $config->get("Arena") . ".yml", Config::YAML);
        $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
        if (!$this->getServer()->getLevelByName($config->get("Arena")) instanceof Level) {

            $this->getServer()->loadLevel($config->get("Arena"));

        }

        if ($pf->get("Team") === "Blau") {

            $player->teleport(new Position($af->get("s1x"), $af->get("s1y")+1, $af->get("s1z"), $level));
            $player->setDisplayName(Color::WHITE . "[" . Color::BLUE . "Blau" . Color::WHITE . "] " . $player->getName() . Color::WHITE);
            $player->setNameTag(Color::WHITE . "[" . Color::BLUE . "Blau" . Color::WHITE . "] " . $player->getName() . Color::WHITE);

        } else if ($pf->get("Team") === "Rot") {

            $player->teleport(new Position($af->get("s2x"), $af->get("s2y")+1, $af->get("s2z"), $level));
            $player->setDisplayName(Color::WHITE . "[" . Color::RED . "Rot" . Color::WHITE . "] " . $player->getName() . Color::WHITE);
            $player->setNameTag(Color::WHITE . "[" . Color::RED . "Rot" . Color::WHITE . "] " . $player->getName() . Color::WHITE);

        } else {

            $this->getServer()->broadcastMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::RED . "Es ist ein Fehler aufgetreten! (#ERROR05)");

        }

    }
	
	public function setBlau(Player $player)
    {

        $config = $this->getConfig();
        $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
        if ($pf->get("Team") === "-") {

            $config->set("Blau", $config->get("Blau") + 1);
            $config->save();
            $pf->set("Team", "Blau");
            $pf->save();
            $player->sendMessage($this->prefix . Color::GRAY . "Du bist dem Team: " . Color::BLUE . "Blau" . Color::GRAY . " beigetreten!");
            $player->setDisplayName(Color::WHITE . "[" . Color::BLUE . "Blau" . Color::WHITE . "] " . $player->getName() . Color::WHITE);
            $player->setNameTag(Color::WHITE . "[" . Color::BLUE . "Blau" . Color::WHITE . "] " . $player->getName() . Color::WHITE);

        }

    }
	
	public function setRot(Player $player)
    {

        $config = $this->getConfig();
        $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
        if ($pf->get("Team") === "-") {

            $config->set("Rot", $config->get("Rot") + 1);
            $config->save();
            $pf->set("Team", "Rot");
            $pf->save();
            $player->sendMessage($this->prefix . Color::GRAY . "Du bist dem Team: " . Color::RED . "Rot" . Color::GRAY . " beigetreten!");
            $player->setDisplayName(Color::WHITE . "[" . Color::RED . "Rot" . Color::WHITE . "] " . $player->getName() . Color::WHITE);
            $player->setNameTag(Color::WHITE . "[" . Color::RED . "Rot" . Color::WHITE . "] " . $player->getName() . Color::WHITE);

        }

    }

    public function setTeam(Player $player) {

        $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
        if ($pf->get("Team") === "-") {

            $sf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
            $cwfile = new Config("/home/ClanWars/ClanWars.yml", Config::YAML);
            if ($cwfile->get("ClanWar1Blau") === $sf->get("Clan")) {

                $this->setBlau($player);
                $pf->set("Team", "Blau");
                $pf->save();

            } else if ($cwfile->get("ClanWar1Rot") === $sf->get("Clan")) {

                $this->setRot($player);
                $pf->set("Team", "Rot");
                $pf->save();

            } else {

                $this->getServer()->broadcastMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::RED . "Es ist ein Fehler aufgetreten! (#ERROR08)");

            }

        }

    }

    public function onLogin(PlayerLoginEvent $event) {

        $player = $event->getPlayer();;
        if (!is_file("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml")) {

            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            $pf->set("Slot", 0);
            $pf->set("Team", "-");
            $pf->set("Damager", "Void");
			$pf->set("Wins", 0);
			$pf->set("Kills", 0);
			$pf->set("Deaths", 0);
            $pf->set("Stick", 280);
            $pf->save();

        }

    }

    public function onJoin(PlayerJoinEvent $event) {

        $player = $event->getPlayer();
        $config = $this->getConfig();
        $spawn = $this->getServer()->getDefaultLevel()->getSafeSpawn();
        $this->getServer()->getDefaultLevel()->loadChunk($spawn->getX(), $spawn->getZ());
        $player->teleport($spawn, 0, 0);
        $player->setGamemode(2);
        $player->setHealth(20);
        $player->setFood(20);
        $player->removeAllEffects();
        $player->setAllowFlight(false);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $team = Item::get(355, 14, 1);
        $team->setCustomName(Color::GOLD . "Teams");
        $player->getInventory()->setItem(4, $team);
        $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
        $pf->set("Team", "-");
        $pf->set("Damager", "Void");
        $pf->save();
        if ($config->get("Ingame") === true) {

            $event->setJoinMessage("");
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $player->setGamemode(3);
            $level = $this->getServer()->getLevelByName($config->get("Arena"));
            $af = new Config($this->getDataFolder() . "/" . $config->get("Arena") . ".yml", Config::YAML);
            $player->teleport(new Position($af->get("s1x"), $af->get("s1y")+1, $af->get("s1z"), $level));

        } else {

            if ($this->players === 0) {

                $this->players++;
                $config->set("Ingame", false);
                $config->set("Reset", false);
                $config->set("ResetTime", 15);
                $config->set("WaitTime", 10);
                $config->set("PlayTime", 3600);
                $config->set("Blau", 0);
                $config->set("Rot", 0);
                $config->set("BlauBett", false);
                $config->set("RotBett", false);
                $config->set("player1", $player->getName());
                $player->setGamemode(2);
                $config->save();
                $event->setJoinMessage($this->prefix . Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server Betreten! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($this->players === 1) {

                $this->players++;
                $config->set("player2", $player->getName());
                $player->setGamemode(2);
                $config->save();
                $event->setJoinMessage($this->prefix . Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server Betreten! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($this->players === 2) {

                $this->players++;
                $config->set("player3", $player->getName());
                $player->setGamemode(2);
                $config->save();
                $event->setJoinMessage($this->prefix . Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server Betreten! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($this->players === 3) {

                $this->players++;
                $config->set("player4", $player->getName());
                $player->setGamemode(2);
                $config->save();
                $event->setJoinMessage($this->prefix . Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server Betreten! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($this->players === 4) {

                $this->players++;
                $config->set("player5", $player->getName());
                $player->setGamemode(2);
                $config->save();
                $event->setJoinMessage($this->prefix . Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server Betreten! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($this->players === 5) {

                $this->players++;
                $config->set("player6", $player->getName());
                $player->setGamemode(2);
                $config->save();
                $event->setJoinMessage($this->prefix . Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server Betreten! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($this->players === 6) {

                $this->players++;
                $config->set("player7", $player->getName());
                $player->setGamemode(2);
                $config->save();
                $event->setJoinMessage($this->prefix . Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server Betreten! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($this->players === 7) {

                $this->players++;
                $config->set("player8", $player->getName());
				$config->set("WaitTime", 0);
                $player->setGamemode(2);
                $config->save();
                $event->setJoinMessage($this->prefix . Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server Betreten! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($this->players === 8) {

                $cwfig = new Config("/home/ClanWars/config.yml", Config::YAML);
                $player->transfer($cwfig->get("IP"), 19132);

            }

        }

    }

    public function onQuit(PlayerQuitEvent $event) {

        $player = $event->getPlayer();
        $config = $this->getConfig();
        if ($config->get("Ingame") === false) {

            if ($player->getName() === $config->get("player1")) {

                $this->players--;
                $p2 = $config->get("player2");
                $p3 = $config->get("player3");
                $p4 = $config->get("player4");
                $p5 = $config->get("player5");
                $p6 = $config->get("player6");
                $p7 = $config->get("player7");
                $p8 = $config->get("player8");

                $config->set("player1", $p2);
                $config->set("player2", $p3);
                $config->set("player3", $p4);
                $config->set("player4", $p5);
                $config->set("player5", $p6);
                $config->set("player6", $p7);
                $config->set("player7", $p8);
                $config->set("player8", "");
                $config->save();
                $event->setQuitMessage($this->prefix .Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server verlassen! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($player->getName() === $config->get("player2")) {

                $this->players--;
                $p3 = $config->get("player3");
                $p4 = $config->get("player4");
                $p5 = $config->get("player5");
                $p6 = $config->get("player6");
                $p7 = $config->get("player7");
                $p8 = $config->get("player8");

                $config->set("player2", $p3);
                $config->set("player3", $p4);
                $config->set("player4", $p5);
                $config->set("player5", $p6);
                $config->set("player6", $p7);
                $config->set("player7", $p8);
                $config->set("player8", "");
                $config->save();
                $event->setQuitMessage($this->prefix .Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server verlassen! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($player->getName() === $config->get("player3")) {

                $this->players--;
                $p4 = $config->get("player4");
                $p5 = $config->get("player5");
                $p6 = $config->get("player6");
                $p7 = $config->get("player7");
                $p8 = $config->get("player8");

                $config->set("player3", $p4);
                $config->set("player4", $p5);
                $config->set("player5", $p6);
                $config->set("player6", $p7);
                $config->set("player7", $p8);
                $config->set("player8", "");
                $config->save();
                $event->setQuitMessage($this->prefix .Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server verlassen! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($player->getName() === $config->get("player4")) {

                $this->players--;
                $p5 = $config->get("player5");
                $p6 = $config->get("player6");
                $p7 = $config->get("player7");
                $p8 = $config->get("player8");

                $config->set("player4", $p5);
                $config->set("player5", $p6);
                $config->set("player6", $p7);
                $config->set("player7", $p8);
                $config->set("player8", "");
                $config->save();
                $event->setQuitMessage($this->prefix .Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server verlassen! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($player->getName() === $config->get("player5")) {

                $this->players--;
                $p6 = $config->get("player6");
                $p7 = $config->get("player7");
                $p8 = $config->get("player8");

                $config->set("player5", $p6);
                $config->set("player6", $p7);
                $config->set("player7", $p8);
                $config->set("player8", "");
                $config->save();
                $event->setQuitMessage($this->prefix .Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server verlassen! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($player->getName() === $config->get("player6")) {

                $this->players--;
                $p7 = $config->get("player7");
                $p8 = $config->get("player8");

                $config->set("player6", $p7);
                $config->set("player7", $p8);
                $config->set("player8", "");
                $config->save();
                $event->setQuitMessage($this->prefix .Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server verlassen! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($player->getName() === $config->get("player7")) {

                $this->players--;
                $p8 = $config->get("player8");

                $config->set("player7", $p8);
                $config->set("player8", "");
                $config->save();
                $event->setQuitMessage($this->prefix .Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server verlassen! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            } else if ($player->getName() === $config->get("player8")) {

                $this->players--;

                $config->set("player8", "");
                $config->save();
                $event->setQuitMessage($this->prefix .Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server verlassen! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

            }

        } else {

            if ($this->players < 1) {

                $this->players = 0;

            } else {

                $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
                if ($pf->get("Team") === "Blau") {

                    $this->players--;
                    $config->set("Blau", $config->get("Blau")-1);
                    $config->save();
                    $this->deletePlayerFromArena($player);
                    $event->setQuitMessage($this->prefix .Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server verlassen! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

                } else if ($pf->get("Team") === "Rot") {

                    $this->players--;
                    $config->set("Rot", $config->get("Rot")-1);
                    $config->save();
                    $this->deletePlayerFromArena($player);
                    $event->setQuitMessage($this->prefix .Color::DARK_GRAY . $player->getName() . Color::GRAY . " hat den Server verlassen! " . Color::DARK_GRAY . "[" . Color::GRAY . $this->players . Color::DARK_GRAY . "/" . Color::GRAY . "8" . Color::DARK_GRAY . "]");

                } else {

                    $event->setQuitMessage("");

                }

            }

        }

    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {

        switch ($command->getName()) {

            case "BedWars":
                if (isset($args[0])) {

                    if (strtolower($args[0]) === "make") {

                        if ($sender->isOp()) {

                            if (isset($args[1])) {

                                if (file_exists($this->getServer()->getDataPath() . "/worlds/" . $args[1])) {

                                    if (!$this->getServer()->getLevelByName($args[1]) instanceof Level) {

                                        $this->getServer()->loadLevel($args[1]);

                                    }

                                    $spawn = $this->getServer()->getLevelByName($args[1])->getSafeSpawn();
                                    $this->getServer()->getLevelByName($args[1])->loadChunk($spawn->getX(), $spawn->getZ());
                                    $sender->teleport($spawn, 0, 0);
                                    $config = $this->getConfig();
                                    $config->set("Arena", $args[1]);
                                    $config->save();
                                    $sender->sendMessage($this->prefix . Color::GRAY . "Du hast die Arena " . Color::AQUA . $args[1] . Color::GRAY . " ausgewaehlt. Jetzt musst du den Spawn fuer das Blaue Team tippen");
                                    $this->mode++;
                                    return true;

                                }

                            }

                        }

                    }

                }

        }

        if ($command->getName() === "Start") {

            $pg = new Config("/home/ClanWars/players/" . $sender->getName() . ".yml", Config::YAML);
            if ($pg->get("NickP") === true) {

                $config = $this->getConfig();
                if ($config->get("Ingame") === false) {

                    if ($config->get("WaitTime") <= 5) {

                        $sender->sendMessage($this->prefix . Color::GRAY . "Das Spiel startet schon!");

                    } else {

                        $config->set("WaitTime", 5);
                        $config->save();

                    }

                } else {

                    $sender->sendMessage($this->prefix . Color::GRAY . "Die Runde hat schon begonnen!");

                }

            } else {

                $sender->sendMessage($this->prefix . Color::GRAY . "Du hast keine Berechtigung fuer diesen befehl!");

            }

        }
		
		if ($command->getName() === "Stats") {
			
			if (isset($args[0])) {
				
				if (is_file("/home/ClanWars/BedWars/players/" . $args[0] . ".yml")) {
					
					$pf = new Config("/home/ClanWars/BedWars/players/" . $args[0] . ".yml", Config::YAML);
					$kills = $pf->get("Kills");
				    $deaths = $pf->get("Deaths");
				    $wins = $pf->get("Wins");
					if ($kills === 0) {
						
						$kd = $deaths;
						
					} else if ($deaths === 0) {
						
						$kd = $kills;
						
					} else {
						
						$kd = $kills/$deaths;
						
			        }
				
				    $sender->sendMessage(Color::GRAY . "Seine/Ihre Stats für " . $this->prefix);
				    $sender->sendMessage(Color::AQUA . "Wins: " . Color::GRAY . $wins);
				    $sender->sendMessage(Color::AQUA . "Kills: " . Color::GRAY . $kills);
				    $sender->sendMessage(Color::AQUA . "Deaths: " . Color::GRAY . $deaths);
				    $sender->sendMessage(Color::AQUA . "K/D: " . Color::GRAY . $kd);
					
				}
				
			} else {
				
				$pf = new Config("/home/ClanWars/BedWars/players/" . $sender->getName() . ".yml", Config::YAML);
				$kills = $pf->get("Kills");
				$deaths = $pf->get("Deaths");
				$wins = $pf->get("Wins");
				if ($kills === 0) {
						
					$kd = $deaths;
						
				} else if ($deaths === 0) {
						
					$kd = $kills;
						
				} else {
						
					$kd = $kills/$deaths;
						
			    }
				
				$sender->sendMessage(Color::GRAY . "Deine Stats für " . $this->prefix);
				$sender->sendMessage(Color::AQUA . "Wins: " . Color::GRAY . $wins);
				$sender->sendMessage(Color::AQUA . "Kills: " . Color::GRAY . $kills);
				$sender->sendMessage(Color::AQUA . "Deaths: " . Color::GRAY . $deaths);
				$sender->sendMessage(Color::AQUA . "K/D: " . Color::GRAY . $kd);
				
			}
            

        }

        return true;

    }

    public function onChat(PlayerChatEvent $event) {

        $player = $event->getPlayer();
        $msg = $event->getMessage();
        $config = $this->getConfig();
        $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
        if ($config->get("Ingame") === true) {

            $words = explode(" ", $msg);
            if ($words[0] === "@a" or $words[0] === "@all") {

                $event->setFormat(Color::WHITE . "[" . Color::YELLOW . "@a" . Color::WHITE . "] " . $player->getDisplayName() . " : " . Color::GRAY . $msg);

            } else {

                $event->setCancelled(true);
                if ($pf->get("Team") === "Blau") {

                    foreach ($player->getLevel()->getPlayers() as $p) {

                        $pf2 = new Config("/home/ClanWars/BedWars/players/" . $p->getName() . ".yml", Config::YAML);
                        if ($pf2->get("Team") === "Blau") {

                            $p->sendMessage(Color::WHITE . "[" . Color::BLUE . "Team" . Color::WHITE . "] " . $player->getDisplayName() . " : " . Color::GRAY . $msg);

                        }

                    }

                } else if ($pf->get("Team") === "Rot") {

                    foreach ($player->getLevel()->getPlayers() as $p) {

                        $pf2 = new Config("/home/ClanWars/BedWars/players/" . $p->getName() . ".yml", Config::YAML);
                        if ($pf2->get("Team") === "Rot") {

                            $p->sendMessage(Color::WHITE . "[" . Color::RED . "Team" . Color::WHITE . "] " . $player->getDisplayName() . " : " . Color::GRAY . $msg);

                        }

                    }

                } else {

                    $event->setCancelled(true);

                }

            }

        } else {

            $event->setFormat($player->getDisplayName() . " : " . Color::GRAY . $msg);

        }

    }

    public function onInteract(PlayerInteractEvent $event) {

        $player = $event->getPlayer();
        $block = $event->getBlock();
        $config = $this->getConfig();
        $af = new Config($this->getDataFolder() . "/" . $config->get("Arena") . ".yml", Config::YAML);
        if ($this->mode === 1 && $player->isOp()) {

            $af->set("s1x", $block->getX() + 0.5);
            $af->set("s1y", $block->getY() + 1);
            $af->set("s1z", $block->getZ() + 0.5);
            $af->save();

            $player->sendMessage($this->prefix . "Jetzt den roten Spawn");
            $this->mode++;

        } else if ($this->mode === 2 && $player->isOp()) {

            $af->set("s2x", $block->getX() + 0.5);
            $af->set("s2y", $block->getY() + 1);
            $af->set("s2z", $block->getZ() + 0.5);
            $af->save();

            $player->sendMessage($this->prefix . "Jetzt das blaue Bett");
            $this->mode++;

        }  else if ($this->mode === 3 && $player->isOp()) {

            if ($player->getLevel()->getBlock(new Vector3($block->getX() + 1, $block->getY(), $block->getZ()))->getId() == 26) {

                $block2 = $player->getLevel()->getBlock(new Vector3($block->getX() + 1, $block->getY(), $block->getZ()));
                $af->set("sb1x", $block->getX());
                $af->set("sb1y", $block->getY());
                $af->set("sb1z", $block->getZ());
                $af->set("sb1x1", $block2->getX());
                $af->set("sb1y1", $block2->getY());
                $af->set("sb1z1", $block2->getZ());
                $af->save();

            }

            if ($player->getLevel()->getBlock(new Vector3($block->getX() - 1, $block->getY(), $block->getZ()))->getId() == 26) {

                $block2 = $player->getLevel()->getBlock(new Vector3($block->getX() - 1, $block->getY(), $block->getZ()));
                $af->set("sb1x", $block->getX());
                $af->set("sb1y", $block->getY());
                $af->set("sb1z", $block->getZ());
                $af->set("sb1x1", $block2->getX());
                $af->set("sb1y1", $block2->getY());
                $af->set("sb1z1", $block2->getZ());
                $af->save();

            }

            if ($player->getLevel()->getBlock(new Vector3($block->getX(), $block->getY(), $block->getZ() + 1))->getId() == 26) {

                $block2 = $player->getLevel()->getBlock(new Vector3($block->getX(), $block->getY(), $block->getZ() + 1));
                $af->set("sb1x", $block->getX());
                $af->set("sb1y", $block->getY());
                $af->set("sb1z", $block->getZ());
                $af->set("sb1x1", $block2->getX());
                $af->set("sb1y1", $block2->getY());
                $af->set("sb1z1", $block2->getZ());
                $af->save();

            }

            if ($player->getLevel()->getBlock(new Vector3($block->getX(), $block->getY(), $block->getZ() - 1))->getId() == 26) {

                $block2 = $player->getLevel()->getBlock(new Vector3($block->getX(), $block->getY(), $block->getZ() - 1));
                $af->set("sb1x", $block->getX());
                $af->set("sb1y", $block->getY());
                $af->set("sb1z", $block->getZ());
                $af->set("sb1x1", $block2->getX());
                $af->set("sb1y1", $block2->getY());
                $af->set("sb1z1", $block2->getZ());
                $af->save();

            }

            $player->sendMessage($this->prefix . "Jetzt das rote Bett");
            $this->mode++;

        } else if ($this->mode === 4 && $player->isOp()) {

            if ($player->getLevel()->getBlock(new Vector3($block->getX() + 1, $block->getY(), $block->getZ()))->getId() == 26) {

                $block2 = $player->getLevel()->getBlock(new Vector3($block->getX() + 1, $block->getY(), $block->getZ()));
                $af->set("sb2x", $block->getX());
                $af->set("sb2y", $block->getY());
                $af->set("sb2z", $block->getZ());
                $af->set("sb2x1", $block2->getX());
                $af->set("sb2y1", $block2->getY());
                $af->set("sb2z1", $block2->getZ());
                $af->save();

            }

            if ($player->getLevel()->getBlock(new Vector3($block->getX() - 1, $block->getY(), $block->getZ()))->getId() == 26) {

                $block2 = $player->getLevel()->getBlock(new Vector3($block->getX() - 1, $block->getY(), $block->getZ()));
                $af->set("sb2x", $block->getX());
                $af->set("sb2y", $block->getY());
                $af->set("sb2z", $block->getZ());
                $af->set("sb2x1", $block2->getX());
                $af->set("sb2y1", $block2->getY());
                $af->set("sb2z1", $block2->getZ());
                $af->save();

            }

            if ($player->getLevel()->getBlock(new Vector3($block->getX(), $block->getY(), $block->getZ() + 1))->getId() == 26) {

                $block2 = $player->getLevel()->getBlock(new Vector3($block->getX(), $block->getY(), $block->getZ() + 1));
                $af->set("sb2x", $block->getX());
                $af->set("sb2y", $block->getY());
                $af->set("sb2z", $block->getZ());
                $af->set("sb2x1", $block2->getX());
                $af->set("sb2y1", $block2->getY());
                $af->set("sb2z1", $block2->getZ());
                $af->save();

            }

            if ($player->getLevel()->getBlock(new Vector3($block->getX(), $block->getY(), $block->getZ() - 1))->getId() == 26) {

                $block2 = $player->getLevel()->getBlock(new Vector3($block->getX(), $block->getY(), $block->getZ() - 1));
                $af->set("sb2x", $block->getX());
                $af->set("sb2y", $block->getY());
                $af->set("sb2z", $block->getZ());
                $af->set("sb2x1", $block2->getX());
                $af->set("sb2y1", $block2->getY());
                $af->set("sb2z1", $block2->getZ());
                $af->save();

            }

            $player->sendMessage($this->prefix . Color::GRAY . "Die Arena ist nun Spielbereit!");
            $this->mode = 0;

            $this->copymap($this->getServer()->getDataPath() . "/worlds/" . $player->getLevel()->getFolderName(), $this->getDataFolder() . "/maps/" . $player->getLevel()->getFolderName());
            $spawn = $this->getServer()->getDefaultLevel()->getSafeSpawn();
            $this->getServer()->getDefaultLevel()->loadChunk($spawn->getX(), $spawn->getZ());
            $player->teleport($spawn, 0, 0);

        }

    }

    public function onDamage(EntityDamageEvent $event) {

        $player = $event->getEntity();
        $config = $this->getConfig();
        if ($config->get("Ingame") === false) {

            $event->setCancelled(true);

        } else {

            if ($event instanceof EntityDamageByEntityEvent) {

                $damager = $event->getDamager();
                if ($damager instanceof Player) {

                    $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
                    $pf2 = new Config("/home/ClanWars/BedWars/players/" . $damager->getName() . ".yml", Config::YAML);
                    if ($pf2->get("Team") === "Blau") {

                        if ($pf->get("Team") === "Blau") {

                            $event->setCancelled(true);

                        } else if ($pf->get("Team") === "Rot") {
							
							$pf->set("Damager", $damager->getName());
                            $pf->save();
                            if ($damager->getInventory()->getItemInHand()->getId() === Item::STICK) {

                                $event->setKnockBack(0.15 * 1.5 + 0.300);

                            }

                        }

                    } else if ($pf2->get("Team") === "Rot") {

                        if ($pf->get("Team") === "Rot") {

                            $event->setCancelled(true);

                        } else if ($pf->get("Team") === "Blau") {
							
							$pf->set("Damager", $damager->getName());
                            $pf->save();
                            if ($damager->getInventory()->getItemInHand()->getId() === Item::STICK) {

                                $event->setKnockBack(0.15 * 1.5 + 0.300);

                            }

                        }

                    } else {
						
						$pf->set("Damager", "Void");
                        $pf->save();
						
					}

                }

            }

        }

    }

    public function onBedEnter(PlayerBedEnterEvent $event) {

        $event->setCancelled(true);

    }

    public function onCraft(CraftItemEvent $event) {

        $event->setCancelled(true);

    }
	
	public function onExplode(EntityExplodeEvent $event) {

        $blocks = $event->getBlockList();
        $explodeblocks = [];
        foreach ($blocks as $block) {

            if ($block->getId() === Block::RED_SANDSTONE) {

                $explodeblocks [] = $block;
                continue;

            } else if ($block->getId() === 20) {

                $explodeblocks [] = $block;
                continue;

            } else if ($block->getId() === 30) {

                $explodeblocks [] = $block;
                continue;

            } else if ($block->getId() === 54) {

                $explodeblocks [] = $block;
                continue;

            } else if ($block->getId() === 121) {

                $explodeblocks [] = $block;
                continue;

            } else if ($block->getId() === 65) {

                $explodeblocks [] = $block;
                continue;

            }

        }

        $event->setBlockList($explodeblocks);

    }

    public function onPlace(BlockPlaceEvent $event) {

        $config = $this->getConfig();
        if ($config->get("Ingame") === false) {

            $event->setCancelled();

        }

    }

    public function onBreak(BlockBreakEvent $event)
    {

        $player = $event->getPlayer();
        $block = $event->getBlock();
        $x = $block->getX();
        $y = $block->getY();
        $z = $block->getZ();
        $config = $this->getConfig();
        $af = new Config($this->getDataFolder() . "/" . $config->get("Arena") . ".yml", Config::YAML);
        $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
        foreach ($player->getLevel()->getPlayers() as $p) {

            if ($config->get("Ingame") === false) {

                $event->setCancelled();

            } else if ($block->getId() === Block::BED_BLOCK) {

                $event->setDrops(array());
                if ($x === $af->get("sb1x") && $y === $af->get("sb1y") && $z === $af->get("sb1z")) {

                    if ($pf->get("Team") === "Blau") {

                        $event->setCancelled(true);
                        $player->sendMessage($this->prefix . Color::RED . "Du kannst dein Bett nicht abbauen!");

                    } else {

                        $p->sendMessage($this->prefix . Color::GRAY . "Das " . Color::BLUE . "BLAUE" . Color::GRAY . " Bett wurde zerstoert!");
                        $config->set("BlauBett", false);
                        $config->save();
                        $pf2 = new Config("/home/ClanWars/BedWars/players/" . $p->getName() . ".yml", Config::YAML);
                        if ($pf2->get("Team") === "Blau") {

                            $p->addTitle(Color::RED . "Dein Bett wurde", Color::RED . "zerstoert!", 20, 40, 20);

                        }

                    }

                } else if ($x === $af->get("sb1x1") && $y === $af->get("sb1y1") && $z === $af->get("sb1z1")) {

                    if ($pf->get("Team") === "Blau") {

                        $event->setCancelled(true);
                        $player->sendMessage($this->prefix . Color::RED . "Du kannst dein Bett nicht abbauen!");

                    } else {

                        $p->sendMessage($this->prefix . Color::GRAY . "Das " . Color::BLUE . "BLAUE" . Color::GRAY . " Bett wurde zerstoert!");
                        $config->set("BlauBett", false);
                        $config->save();
                        $pf2 = new Config("/home/ClanWars/BedWars/players/" . $p->getName() . ".yml", Config::YAML);
                        if ($pf2->get("Team") === "Blau") {

                            $p->addTitle(Color::RED . "Dein Bett wurde", Color::RED . "zerstoert!", 20, 40, 20);

                        }

                    }

                } else if ($x === $af->get("sb2x") && $y === $af->get("sb2y") && $z === $af->get("sb2z")) {

                    if ($pf->get("Team") === "Rot") {

                        $event->setCancelled(true);
                        $player->sendMessage($this->prefix . Color::RED . "Du kannst dein Bett nicht abbauen!");

                    } else {

                        $p->sendMessage($this->prefix . Color::GRAY . "Das " . Color::RED . "ROTE" . Color::GRAY . " Bett wurde zerstoert!");
                        $config->set("RotBett", false);
                        $config->save();
                        $pf2 = new Config("/home/ClanWars/BedWars/players/" . $p->getName() . ".yml", Config::YAML);
                        if ($pf2->get("Team") === "Rot") {

                            $p->addTitle(Color::RED . "Dein Bett wurde", Color::RED . "zerstoert!", 20, 40, 20);

                        }

                    }

                } else if ($x === $af->get("sb2x1") && $y === $af->get("sb2y1") && $z === $af->get("sb2z1")) {

                    if ($pf->get("Team") === "Rot") {

                        $event->setCancelled(true);
                        $player->sendMessage($this->prefix . Color::RED . "Du kannst dein Bett nicht abbauen!");

                    } else {

                        $p->sendMessage($this->prefix . Color::GRAY . "Das " . Color::RED . "ROTE" . Color::GRAY . " Bett wurde zerstoert!");
                        $config->set("RotBett", false);
                        $config->save();
                        $pf2 = new Config("/home/ClanWars/BedWars/players/" . $p->getName() . ".yml", Config::YAML);
                        if ($pf2->get("Team") === "Rot") {

                            $p->addTitle(Color::RED . "Dein Bett wurde", Color::RED . "zerstoert!", 20, 40, 20);

                        }

                    }

                }

            } else if ($block->getId() === Block::RED_SANDSTONE) {

                $event->setCancelled(false);

            } else if ($block->getId() === 20) {

                $event->setCancelled(false);

            } else if ($block->getId() === 54) {

                $event->setCancelled(false);

            } else if ($block->getId() === 121) {

                $event->setCancelled(false);

            } else if ($block->getId() === 130) {

                $event->setCancelled(false);
                $event->setDrops(array(Item::get(130, 0, 1)));

            } else if ($block->getId() === 65) {

                $event->setCancelled(false);

            } else if ($block->getId() === 46) {

                $event->setCancelled(false);

            } else if ($block->getId() === 30) {

                $event->setCancelled(false);
                $event->setDrops(array());
                $player->getLevel()->setBlock(new Vector3($x, ($y), $z), Block::get(Block::AIR));

            } else {

                $event->setCancelled(true);

            }

        }

    }

    public function onDeath(PlayerDeathEvent $event)
    {

        $player = $event->getPlayer();
        $event->setDrops(array());
        $config = $this->getConfig();
        $pff = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
        $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
		$pf2 = new Config("/home/ClanWars/BedWars/players/" . $pf->get("Damager") . ".yml", Config::YAML);
        if ($pf->get("Team") === "Blau") {

            $event->setDeathMessage($this->prefix . Color::DARK_GRAY . Color::BLUE . $player->getName() . Color::GRAY . " wurde von " . Color::RED . $pf->get("Damager") . Color::GRAY . " getoetet!");
            if ($config->get("BlauBett") === false) {
				
				$config->set("Blau", $config->get("Blau")-1);
                $config->save();
				$pf2->set("Kills", $pf->get("Kills")+1);
				$pf2->save();
				$pf->set("Death", $pf->get("Death")+1);
				$pf->save();
                $player->setGamemode(3);
                $this->deletePlayerFromArena($player);
                $this->players--;

            }

            $pf->set("Damager", "Void");
            $pf->save();

        } else if ($pf->get("Team") === "Rot") {

            $event->setDeathMessage($this->prefix . Color::DARK_GRAY . Color::RED . $player->getName() . Color::GRAY . " wurde von " . Color::BLUE . $pf->get("Damager") . Color::GRAY . " getoetet!");
            if ($config->get("RotBett") === false) {
				
				$config->set("Rot", $config->get("Rot")-1);
                $config->save();
				$pf2->set("Kills", $pf->get("Kills")+1);
				$pf2->save();
				$pf->set("Death", $pf->get("Death")+1);
				$pf->save();
                $player->setGamemode(3);
                $this->deletePlayerFromArena($player);
                $this->players--;

            }

            $pf->set("Damager", "Void");
            $pf->save();

        }

    }

    public function onRespawn(PlayerRespawnEvent $event)
    {

        $player = $event->getPlayer();
        $pos = $player->getPosition();
        $config = $this->getConfig();
        $level = $this->getServer()->getLevelByName($config->get("Arena"));
        $af = new Config($this->getDataFolder() . "/" . $config->get("Arena") . ".yml", Config::YAML);
        $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
        if ($pf->get("Team") === "Blau") {

            if ($config->get("BlauBett") === true) {

                $this->giveKit($player);
                $player->teleport(new Position($af->get("s1x"), $af->get("s1y"), $af->get("s1z"), $level));

            } else if ($config->get("BlauBett") === false) {

                $player->sendMessage($this->prefix . Color::RED . "Du konntest nicht mehr respawnen!");
                $player->teleport(new Position($af->get("s1x"), $af->get("s1y"), $af->get("s1z"), $level));
                $player->getInventory()->clearAll();
                $pf->set("Team", "-");
                $pf->save();

            }

        } else if ($pf->get("Team") === "Rot") {

            if ($config->get("RotBett") === true) {

                $this->giveKit($player);
                $player->teleport(new Position($af->get("s1x"), $af->get("s1y"), $af->get("s1z"), $level));

            } else if ($config->get("RotBett") === false) {

                $player->sendMessage($this->prefix . Color::RED . "Du konntest nicht mehr respawnen!");
                $player->teleport(new Position($af->get("s1x"), $af->get("s1y"), $af->get("s1z"), $level));
                $player->getInventory()->clearAll();
                $pf->set("Team", "-");
                $pf->save();

            }

        } else {

            $this->getServer()->broadcastMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::RED . "Es ist ein Fehler aufgetreten! (#ERROR04)");

        }

    }

}

class PlayerSender extends Task
{

    public function __construct($plugin)
    {

        $this->plugin = $plugin;

    }

    public function onRun(int $currentTick) : void
    {

        $config = $this->plugin->getConfig();
        $all  = $this->plugin->getServer()->getOnlinePlayers();
        $config->set("Players", $this->plugin->players);
        $config->save();
        if ($config->get("Ingame") === true) {
			
			if ($config->get("Blau") <= 0) {
				
				$config->set("Win", "Rot");
				$config->save();
				
			} else if ($config->get("Rot") <= 0) {
				
				$config->set("Win", "Blau");
				$config->save();
				
			}
			
			if ($config->get("BlauBett") === true) {
				
				$config->set("BlauBettStatus", "+");
				$config->save();
				
			} else {
				
				$config->set("BlauBettStatus", "-");
				$config->save();
				
			}
			
			if ($config->get("RotBett") === true) {
				
				$config->set("RotBettStatus", "+");
				$config->save();
				
			} else {
				
				$config->set("RotBettStatus", "-");
				$config->save();
				
			}

            if ($config->get("Blau") <= 0) {

                if ($config->get("Rot") <= 0) {

                    $this->plugin->getServer()->broadcastMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::RED . "Es ist ein Fehler aufgetreten! (#ERROR06)");

                } else {

                    $config->set("Win", "Rot");
                    $config->save();

                }

            } else if ($config->get("Rot") <= 0) {

                if ($config->get("Blau") <= 0) {

                    $this->plugin->getServer()->broadcastMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::RED . "Es ist ein Fehler aufgetreten! (#ERROR07)");

                } else {

                    $config->set("Win", "Blau");
                    $config->save();

                }

            }

        }

        if (count($all) === 0) {

            if ($config->get("Ingame") === true) {

                $config->set("Ingame", false);
                $config->set("Reset", false);
                $config->set("ResetTime", 15);
                $config->set("WaitTime", 10);
                $config->set("PlayTime", 3600);
                $config->set("Players", 0);
                $config->set("Win", "-");
                $config->set("Blau", 0);
                $config->set("Rot", 0);
                $config->set("BlauBett", false);
                $config->set("RotBett", false);
                $config->set("player1" , "");
                $config->set("player2" , "");
                $config->set("player3" , "");
                $config->set("player4" , "");
                $config->set("player5" , "");
                $config->set("player6" , "");
                $config->set("player7" , "");
                $config->set("player8" , "");
                $config->save();

            }

        }

    }

}

class GameSender extends Task
{

    public function __construct($plugin)
    {

        $this->plugin = $plugin;

    }

    public function onRun(int $currentTick) : void
    {

        $level = $this->plugin->getServer()->getDefaultLevel();
        $config = $this->plugin->getConfig();
        $all = $this->plugin->getServer()->getOnlinePlayers();
        $api = Scoreboards::getInstance();
        if ($config->get("Ingame") === false) {

            if ($this->plugin->players < 8) {

                foreach ($all as $player) {

                    $player->sendPopup(Color::GRAY . ">> Warten auf weitere Spieler <<");
                    $api->new($player, "ObjectiveName", Color::AQUA . "BedWars");
                    $api->setLine($player, 1, " ");
                    $api->setLine($player, 2, Color::BLUE . " Blau: " . Color::GRAY . $config->get("Blau") . Color::DARK_GRAY . "/" . Color::GRAY . "4" . " ");
                    $api->setLine($player, 3, Color::RED . " Rot: " . Color::GRAY . $config->get("Rot") . Color::DARK_GRAY . "/" . Color::GRAY . "4" . " ");
                    $api->setLine($player, 4, "     ");
                    $api->setLine($player, 5, Color::AQUA . " Map: " . Color::GRAY . $config->get("Arena") . " ");
                    $api->setLine($player, 6, "       ");
                    $api->setLine($player, 7, Color::AQUA . " Spieler: " . Color::GRAY . $this->plugin->players . Color::WHITE . "/" . Color::GRAY . "8 ");
                    $api->getObjectiveName($player);

                }

            }

            if ($this->plugin->players >= 8) {

                $config->set("WaitTime", $config->get("WaitTime") - 1);
                $config->save();
                $time = $config->get("WaitTime") + 1;
                foreach ($all as $player) {

                    $api->new($player, "ObjectiveName", Color::AQUA . "BedWars");
                    $api->setLine($player, 1, " ");
                    $api->setLine($player, 2, Color::BLUE . " Blau: " . Color::GRAY . $config->get("Blau") . Color::DARK_GRAY . "/" . Color::GRAY . "4" . " ");
                    $api->setLine($player, 3, Color::RED . " Rot: " . Color::GRAY . $config->get("Rot") . Color::DARK_GRAY . "/" . Color::GRAY . "4" . " ");
                    $api->setLine($player, 4, "     ");
                    $api->setLine($player, 5, Color::AQUA . " Map: " . Color::GRAY . $config->get("Arena") . " ");
                    $api->setLine($player, 6, "       ");
                    $api->setLine($player, 7, Color::AQUA . " Spieler: " . Color::GRAY . $this->plugin->players . Color::WHITE . "/" . Color::GRAY . "8 ");
                    $api->getObjectiveName($player);

                }

                if ($time % 5 === 0 && $time > 0) {

                    foreach ($all as $player) {

                        $player->sendMessage($this->plugin->prefix . Color::GRAY . "Das Match startet in " . Color::AQUA . $time . Color::GRAY . " Sekunden!");

                    }

                } else if ($time === 15) {

                    foreach ($all as $player) {

                        $player->sendMessage($this->plugin->prefix . Color::GRAY . "Das Match startet in " . Color::AQUA . $time . Color::GRAY . " Sekunden!");

                    }

                } else if ($time === 4 || $time === 3 || $time === 2 || $time === 1) {

                    foreach ($all as $player) {
						
                        $player->sendMessage($this->plugin->prefix . Color::GRAY . "Das Match startet in " . Color::AQUA . $time . Color::GRAY . " Sekunden!");

                    }

                } else if ($time === 0) {
					
					$level = $this->plugin->getServer()->getLevelByName($config->get("Arena"));
                    $level->setTime(0);
                    $level->stopTime();

                    $config->set("BlauBett", true);
                    $config->set("RotBett", true);
                    $config->set("Ingame", true);
					$config->save();
                    foreach ($all as $player) {

                        $player->sendMessage($this->plugin->prefix . Color::GRAY . "Das Match endet in " . Color::AQUA . "60" . Color::GRAY . " Minuten!");
                        $this->plugin->setTeam($player);
                        $player->setHealth(20);
                        $player->setFood(20);
						$player->setGamemode(0);
                        $this->plugin->teleportIngame($player);
                        $this->plugin->spawn($player);
                        $this->plugin->giveKit($player);
						
                    }

                }

            }

        } else if ($config->get("Ingame") === true) {

            $all = $this->plugin->getServer()->getOnlinePlayers();
            if ($this->plugin->players <= 1) {

                foreach ($all as $player) {

                    if ($config->get("Win") === "Rot") {

                        if ($player->getGamemode() === 0) {

                            $config->set("Win", $player->getName());
                            $config->save();

                        }

                        $cwfile = new Config("/home/ClanWars/ClanWars.yml", Config::YAML);
                        $player->addTitle(Color::RED . $cwfile->get("ClanWar1Rot"), Color::GRAY . "hat Gewonnen", 20, 40, 20);

                    }

                    $player->addTitle(Color::GRAY . $config->get("Win"), Color::GRAY . "hat Gewonnen", 20, 40, 20);

                    $player->getInventory()->clearAll();
                    $player->setHealth(20);
                    $player->setFood(20);
                    $player->removeAllEffects();
                    $spawn = $this->plugin->getServer()->getDefaultLevel()->getSafeSpawn();
                    $this->plugin->getServer()->getDefaultLevel()->loadChunk($spawn->getX(), $spawn->getZ());
                    $player->teleport($spawn, 0, 0);
                    $config->set("Ingame", false);
                    $config->set("Reset", true);
                    $config->set("ResetTime", 15);
                    $config->set("WaitTime", 10);
                    $config->set("PlayTime", 3600);
                    $config->set("Players", 0);
                    $config->set("Win", "-");
                    $config->set("Blau", 0);
                    $config->set("Rot", 0);
                    $config->set("BlauBett", false);
                    $config->set("RotBett", false);
                    $config->set("player1", "");
                    $config->set("player2", "");
                    $config->set("player3", "");
                    $config->set("player4", "");
                    $config->set("player5", "");
                    $config->set("player6", "");
                    $config->set("player7", "");
                    $config->set("player8", "");
                    $config->save();
                    $sf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
                    $clan = new Config("/home/ClanWars/Clans/" . $sf->get("Clan") . ".yml", Config::YAML);
                    $clan->set("ClanWar", false);
                    $clan->save();
                    $cwfile = new Config("/home/ClanWars/ClanWars.yml", Config::YAML);
                    $cwfile->set("ClanWar1Blau", "");
                    $cwfile->set("ClanWar1Rot", "");
                    $cwfile->set("ClanWar1", false);
                    $cwfile->save();
                    $this->plugin->players = 0;
                    $levelname = $config->get("Arena");
                    $lev = $this->plugin->getServer()->getLevelByName($levelname);
                    $this->plugin->getServer()->unloadLevel($lev);
                    $this->plugin->deleteDirectory($this->plugin->getServer()->getDataPath() . "/worlds/" . $levelname);
                    $this->plugin->copymap($this->plugin->getDataFolder() . "/maps/" . $levelname, $this->plugin->getServer()->getDataPath() . "/worlds/" . $levelname);
                    $this->plugin->getServer()->loadLevel($levelname);

                }

            } else if ($this->plugin->players > 0) {

                foreach ($all as $player) {

                    $api->new($player, "ObjectiveName", Color::AQUA . "BedWars");
                    $api->setLine($player, 1, " ");
                    $api->setLine($player, 2, Color::BLUE . " Blau: " . Color::GRAY . $config->get("Blau") . Color::DARK_GRAY . "/" . Color::GRAY . "4 [" . Color::YELLOW . $config->get("BlauBettStatus") . Color::GRAY . "]" . " ");
                    $api->setLine($player, 3, Color::RED . " Rot: " . Color::GRAY . $config->get("Rot") . Color::DARK_GRAY . "/" . Color::GRAY . "4 [" . Color::YELLOW . $config->get("RotBettStatus") . Color::GRAY . "]" . " ");
                    $api->setLine($player, 4, "     ");
                    $api->setLine($player, 5, Color::AQUA . " Map: " . Color::GRAY . $config->get("Arena") . " ");
                    $api->setLine($player, 6, "       ");
                    $api->setLine($player, 7, Color::AQUA . " Spieler: " . Color::GRAY . $this->plugin->players . Color::WHITE . "/" . Color::GRAY . "8 ");
                    $api->getObjectiveName($player);
					
					$pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
					if ($pf->get("Team") === "Blau") {
						
						$player->sendPopup(Color::WHITE . "Team: " . Color::BLUE . "Blau");
						
					} else if ($pf->get("Team") === "Rot") {
						
						$player->sendPopup(Color::WHITE . "Team: " . Color::RED . "Rot");
						
					} else {
						
						$player->sendPopup(Color::GRAY . "Spectator");
						
					}

                }

                $config->set("PlayTime", $config->get("PlayTime") - 1);
                $config->save();
                $time = $config->get("PlayTime") + 1;
                foreach ($all as $player) {

                    if ($time === 60) {

                        $player->sendMessage($this->plugin->prefix . Color::GRAY . "Das Match endet in " . Color::AQUA . $time . Color::GRAY . " Sekunden!");

                    } else if ($time === 1 || $time === 2 || $time === 3 || $time === 4 || $time === 5 || $time === 15 || $time === 30) {

                        $player->sendMessage($this->plugin->prefix . Color::GRAY . "Das Match endet in " . Color::AQUA . $time . Color::GRAY . " Sekunden!");

                    } else if ($time === 0) {

                        $player->getInventory()->clearAll();
                        $player->setHealth(20);
                        $player->setFood(20);
                        $player->removeAllEffects();
                        $player->sendMessage($this->plugin->prefix . Color::GREEN . "Du hast das Match gewonnen!");
                        $this->plugin->getServer()->broadcastMessage($this->plugin->prefix . $player->getName() . Color::GREEN . " hat das Match in " . Color::WHITE . $config->get("Arena") . Color::GREEN . " Gewonnen!");
                        $spawn = $this->plugin->getServer()->getDefaultLevel()->getSafeSpawn();
                        $this->plugin->getServer()->getDefaultLevel()->loadChunk($spawn->getX(), $spawn->getZ());
                        $player->teleport($spawn, 0, 0);
                        $pff = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
                        $pff->set("Team", "-");
                        $pff->save();
                        $config->set("Ingame", false);
                        $config->set("Reset", true);
                        $config->set("ResetTime", 15);
                        $config->set("WaitTime", 10);
                        $config->set("PlayTime", 3600);
                        $config->set("Players", 0);
                        $config->set("Win", "-");
                        $config->set("Blau", 0);
                        $config->set("Rot", 0);
                        $config->set("BlauBett", false);
                        $config->set("RotBett", false);
                        $config->set("player1", "");
                        $config->set("player2", "");
                        $config->set("player3", "");
                        $config->set("player4", "");
                        $config->set("player5", "");
                        $config->set("player6", "");
                        $config->set("player7", "");
                        $config->set("player8", "");
                        $config->save();
                        $sf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
                        $clan = new Config("/home/ClanWars/Clans/" . $sf->get("Clan") . ".yml", Config::YAML);
                        $clan->set("ClanWar", false);
                        $clan->save();
                        $cwfile = new Config("/home/ClanWars/ClanWars.yml", Config::YAML);
                        $cwfile->set("ClanWar1Blau", "");
                        $cwfile->set("ClanWar1Rot", "");
                        $cwfile->set("ClanWar1", false);
                        $cwfile->save();
                        $this->plugin->players = 0;
                        $levelname = $config->get("Arena");
                        $lev = $this->plugin->getServer()->getLevelByName($levelname);
                        $this->plugin->getServer()->unloadLevel($lev);
                        $this->plugin->deleteDirectory($this->plugin->getServer()->getDataPath() . "/worlds/" . $levelname);
                        $this->plugin->copymap($this->plugin->getDataFolder() . "/maps/" . $levelname, $this->plugin->getServer()->getDataPath() . "/worlds/" . $levelname);
                        $this->plugin->getServer()->loadLevel($levelname);

                    }

                }

                if ($config->get("Win") === "Rot") {

                    foreach ($all as $player) {

                        $cwfile = new Config("/home/ClanWars/ClanWars.yml", Config::YAML);
                        $player->addTitle(Color::RED . $cwfile->get("ClanWar1Rot"), Color::GRAY . "hat Gewonnen", 20, 40, 20);
                        $player->getInventory()->clearAll();
                        $player->getArmorInventory()->clearAll();
                        $player->setHealth(20);
                        $player->setFood(20);
                        $player->removeAllEffects();
                        $spawn = $this->plugin->getServer()->getDefaultLevel()->getSafeSpawn();
                        $this->plugin->getServer()->getDefaultLevel()->loadChunk($spawn->getX(), $spawn->getZ());
                        $player->teleport($spawn, 0, 0);
                        $pff = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
                        $pff->set("Team", "-");
                        $pff->save();

                    }

                    $cwfile = new Config("/home/ClanWars/ClanWars.yml", Config::YAML);
                    $this->plugin->getServer()->broadcastMessage($this->plugin->prefix . Color::GRAY . "Das Team: " . Color::RED . $cwfile->get("ClanWar1Rot") . Color::GRAY . " hat das Spiel gewonnen!");
                    $config->set("Ingame", false);
                    $config->set("Reset", true);
                    $config->set("ResetTime", 15);
                    $config->set("WaitTime", 10);
                    $config->set("PlayTime", 3600);
                    $config->set("Players", 0);
                    $config->set("Win", "-");
                    $config->set("Blau", 0);
                    $config->set("Rot", 0);
                    $config->set("BlauBett", false);
                    $config->set("RotBett", false);
                    $config->set("player1", "");
                    $config->set("player2", "");
                    $config->set("player3", "");
                    $config->set("player4", "");
                    $config->set("player5", "");
                    $config->set("player6", "");
                    $config->set("player7", "");
                    $config->set("player8", "");
                    $config->save();
                    $sf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
                    $clan = new Config("/home/ClanWars/Clans/" . $sf->get("Clan") . ".yml", Config::YAML);
                    $clan->set("ClanWar", false);
                    $clan->save();
                    $cwfile = new Config("/home/ClanWars/ClanWars.yml", Config::YAML);
                    $cwfile->set("ClanWar1Blau", "");
                    $cwfile->set("ClanWar1Rot", "");
                    $cwfile->set("ClanWar1", false);
                    $cwfile->save();
                    $this->plugin->players = 0;
                    $levelname = $config->get("Arena");
                    $lev = $this->plugin->getServer()->getLevelByName($levelname);
                    $this->plugin->getServer()->unloadLevel($lev);
                    $this->plugin->deleteDirectory($this->plugin->getServer()->getDataPath() . "/worlds/" . $levelname);
                    $this->plugin->copymap($this->plugin->getDataFolder() . "/maps/" . $levelname, $this->plugin->getServer()->getDataPath() . "/worlds/" . $levelname);
                    $this->plugin->getServer()->loadLevel($levelname);

                }

                if ($config->get("Win") === "Blau") {

                    foreach ($all as $player) {

                        $cwfile = new Config("/home/ClanWars/ClanWars.yml", Config::YAML);
                        $player->addTitle(Color::BLUE . $cwfile->get("ClanWar1Blau"), Color::GRAY . "hat Gewonnen", 20, 40, 20);
                        $player->getInventory()->clearAll();
                        $player->getArmorInventory()->clearAll();
                        $player->setHealth(20);
                        $player->setFood(20);
                        $player->removeAllEffects();
                        $spawn = $this->plugin->getServer()->getDefaultLevel()->getSafeSpawn();
                        $this->plugin->getServer()->getDefaultLevel()->loadChunk($spawn->getX(), $spawn->getZ());
                        $player->teleport($spawn, 0, 0);
                        $pff = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
                        $pff->set("Team", "-");
                        $pff->save();

                    }

                    $cwfile = new Config("/home/ClanWars/ClanWars.yml", Config::YAML);
                    $this->plugin->getServer()->broadcastMessage($this->plugin->prefix . Color::GRAY . "Das Team: " . Color::BLUE . $cwfile->get("ClanWar1Blau") . Color::GRAY . " hat das Spiel gewonnen!");
                    $config->set("Ingame", false);
                    $config->set("Reset", true);
                    $config->set("ResetTime", 15);
                    $config->set("WaitTime", 10);
                    $config->set("PlayTime", 3600);
                    $config->set("Players", 0);
                    $config->set("Win", "-");
                    $config->set("Blau", 0);
                    $config->set("Rot", 0);
                    $config->set("BlauBett", false);
                    $config->set("RotBett", false);
                    $config->set("player1", "");
                    $config->set("player2", "");
                    $config->set("player3", "");
                    $config->set("player4", "");
                    $config->set("player5", "");
                    $config->set("player6", "");
                    $config->set("player7", "");
                    $config->set("player8", "");
                    $config->save();
                    $sf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
                    $clan = new Config("/home/ClanWars/Clans/" . $sf->get("Clan") . ".yml", Config::YAML);
                    $clan->set("ClanWar", false);
                    $clan->save();
                    $cwfile = new Config("/home/ClanWars/ClanWars.yml", Config::YAML);
                    $cwfile->set("ClanWar1Blau", "");
                    $cwfile->set("ClanWar1Rot", "");
                    $cwfile->set("ClanWar1", false);
                    $cwfile->save();
                    $this->plugin->players = 0;
                    $levelname = $config->get("Arena");
                    $lev = $this->plugin->getServer()->getLevelByName($levelname);
                    $this->plugin->getServer()->unloadLevel($lev);
                    $this->plugin->deleteDirectory($this->plugin->getServer()->getDataPath() . "/worlds/" . $levelname);
                    $this->plugin->copymap($this->plugin->getDataFolder() . "/maps/" . $levelname, $this->plugin->getServer()->getDataPath() . "/worlds/" . $levelname);
                    $this->plugin->getServer()->loadLevel($levelname);

                }

            }

        }

        if ($config->get("Reset") === true) {

            $config->set("ResetTime", $config->get("ResetTime") - 1);
            $config->save();
            $time = $config->get("ResetTime") + 1;
            if ($time === 15) {
                
                $this->plugin->getServer()->broadcastMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::GRAY . "Der Server restartet in " . Color::YELLOW . $time . Color::GRAY . " Sekunden!");

            } else if ($time === 10) {

                $this->plugin->getServer()->broadcastMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::GRAY . "Der Server restartet in " . Color::YELLOW . $time . Color::GRAY . " Sekunden!");

            } else if ($time === 5) {

                $this->plugin->getServer()->broadcastMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::GRAY . "Der Server restartet in " . Color::YELLOW . $time . Color::GRAY . " Sekunden!");

            } else if ($time === 0) {
                
                foreach ($all as $player) {

                    $cwfig = new Config("/home/ClanWars/config.yml", Config::YAML);
                    $player->transfer($cwfig->get("IP"), 19132);

                }

                $config->set("Reset", false);
                $config->set("ResetTime", 15);
                $config->save();
                $this->plugin->players = 0;

            }

        }

    }

}

class DropBronze extends Task {

    public function __construct($plugin)
    {

        $this->plugin = $plugin;

    }

    public function onRun(int $currentTick) : void {

        $config = $this->plugin->getConfig();
        $all = $this->plugin->getServer()->getOnlinePlayers();
        if ($config->get("Ingame") === true) {

            if (!$this->plugin->getServer()->getLevelByName($config->get("Arena")) instanceof Level) {

                $this->plugin->getServer()->loadLevel($config->get("Arena"));

            }

            $levelname = $config->get("Arena");
            $level = $this->plugin->getServer()->getLevelByName($levelname);
            $tiles = $level->getTiles();
            foreach ($tiles as $tile) {

                if ($tile instanceof Sign) {

                    $text = $tile->getText();
                    if ($text[0] === "Bronze") {

                        $level->dropItem(new Vector3($tile->getX() + 0.5, $tile->getY() + 2, $tile->getZ() + 0.5), Item::get(336, 0, 1));

                    }

                }

            }

        }

    }

}

class DropIron extends Task {

    public function __construct($plugin)
    {

        $this->plugin = $plugin;

    }

    public function onRun(int $currentTick) : void {

        $config = $this->plugin->getConfig();
        $all = $this->plugin->getServer()->getOnlinePlayers();
        if ($config->get("Ingame") === true) {

            $levelname = $config->get("Arena");
            $level = $this->plugin->getServer()->getLevelByName($levelname);
            $tiles = $level->getTiles();
            foreach ($tiles as $tile) {

                if ($tile instanceof Sign) {

                    $text = $tile->getText();
                    if ($text[0] === "Iron") {

                        $level->dropItem(new Vector3($tile->getX() + 0.5, $tile->getY() + 2, $tile->getZ() + 0.5), Item::get(265, 0, 1));

                    }

                }

            }

        }

    }

}

class DropGold extends Task {

    public function __construct($plugin)
    {

        $this->plugin = $plugin;

    }

    public function onRun(int $currentTick) : void {

        $config = $this->plugin->getConfig();
        $all = $this->plugin->getServer()->getOnlinePlayers();
        if ($config->get("Ingame") === true) {

            $levelname = $config->get("Arena");
            $level = $this->plugin->getServer()->getLevelByName($levelname);
            $tiles = $level->getTiles();
            foreach ($tiles as $tile) {

                if ($tile instanceof Sign) {

                    $text = $tile->getText();
                    if ($text[0] === "Gold") {

                        $level->dropItem(new Vector3($tile->getX() + 0.5, $tile->getY() + 2, $tile->getZ() + 0.5), Item::get(266, 0, 1));

                    }

                }

            }

        }

    }

}