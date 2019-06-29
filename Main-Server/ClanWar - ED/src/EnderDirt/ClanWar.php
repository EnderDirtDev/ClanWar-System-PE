<?php

namespace EnderDirt;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as Color;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\inventory\BaseInventory;
use pocketmine\inventory\PlayerInventory;
use pocketmine\Server;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\item\Item;
use pocketmine\entity\Entity;
use pocketmine\entity\Effect;
use pocketmine\block\Block;
use pocketmine\level\Position;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\event\player\PlayerChatEvent;

use Scoreboards\Scoreboards;

class ClanWar extends PluginBase implements Listener {

    public $prefix = Color::AQUA . "ClanWar" . Color::DARK_GRAY . " : ";

    public $load = 1;

    public function onEnable()
    {

        $this->getLogger()->info($this->prefix . Color::GRAY . "lade...");

        if (is_dir("/home/ClanWars") !== true) {

            mkdir("/home/ClanWars");

        }

        if (is_dir("/home/ClanWars/players") !== true) {

            mkdir("/home/ClanWars/players");

        }

        if (is_dir("/home/ClanWars/Clans") !== true) {

            mkdir("/home/ClanWars/Clans");

        }

        if (!is_file("/home/ClanWars/config.yml")) {

            $cwfig = new Config("/home/ClanWars/config.yml", Config::YAML);
            $cwfig->set("IP", "5.555.55.55");
            $cwfig->set("Port-1", 1);
            $cwfig->set("Port-2", 2);
            $cwfig->set("Port-3", 3);
            $cwfig->set("Port-4", 4);
            $cwfig->set("Port-5", 5);
            $cwfig->save();

            $this->getLogger()->info($this->prefix . Color::GREEN . "Es wurde ein Ordner im /home/ Verzeichnis erstellt!");

        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getScheduler()->scheduleRepeatingTask(new ClanWarTask($this), 20);
        $this->getLogger()->info($this->prefix . Color::GREEN . "wurde geladen!");
        $this->getLogger()->info($this->prefix . Color::AQUA . "Made By" . Color::GREEN . " EnderDirt!");

    }

    public function onDisable()
    {

        $this->getLogger()->info($this->prefix . Color::GRAY . "lade...");
        $this->getLogger()->info($this->prefix . Color::RED . "konnte nicht geladen werden!");

    }

    public function mainItems(Player $player) {

        $player->getInventory()->clearAll();
        $pf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
        if ($pf->get("Clan") === "") {

        } else {

            $clans = Item::get(446, 0, 1);
            $cw = Item::get(345, 0, 1);
            $clans->setCustomName(Color::GOLD . "Clans");
            $cw->setCustomName(Color::GREEN . "ClanWar Queue");
            $player->getInventory()->setItem(4, $clans);
            $player->getInventory()->setItem(2, $cw);

        }

    }

    public function onJoin(PlayerJoinEvent $event)
    {

        $player = $event->getPlayer();

        $event->setJoinMessage("");

        $player->setHealth(20);
        $player->setFood(20);
        $player->setGamemode(2);

        $player->getInventory()->clearAll();
        $this->mainItems($player);

    }

    public function onQuit(PlayerQuitEvent $event)
    {

        $event->setQuitMessage("");

    }

    public function onMove(PlayerMoveEvent $event) {

        $player = $event->getPlayer();
        $player->setFood(20);

    }

    public function clanCheck(Player $player) {

        $pf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
        if ($pf->get("Clan") === "") {

            $player->sendMessage(Color::RED . "Du bist in keinem Clan");

        } else {

            $this->clanSiteA($player);

        }

    }

    public function clanSiteA(Player $player) {

        $pf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
        $clan = new Config("/home/ClanWars/Clans/" . $pf->get("Clan") . ".yml", Config::YAML);
        $player->getInventory()->clearAll();
        if ($clan->get("player1") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(0, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player1"));
            $player->getInventory()->setItem(0, $c1);

        }

        if ($clan->get("player2") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(1, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player2"));
            $player->getInventory()->setItem(1, $c1);

        }

        if ($clan->get("player3") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(2, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player3"));
            $player->getInventory()->setItem(2, $c1);

        }

        if ($clan->get("player4") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(6, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player4"));
            $player->getInventory()->setItem(6, $c1);

        }

        if ($clan->get("player5") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(7, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player5"));
            $player->getInventory()->setItem(7, $c1);

        }

        if ($clan->get("player6") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(8, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player6"));
            $player->getInventory()->setItem(8, $c1);

        }

        $back = Item::get(372, 0, 1);
        $site2 = Item::get(288, 0, 1);
        $back->setCustomName(Color::RED . "Back");
        $site2->setCustomName(Color::BLUE . "Seite 2");
        $player->getInventory()->setItem(4, $back);
        $player->getInventory()->setItem(5, $site2);

    }

    public function clanSiteB(Player $player) {

        $pf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
        $clan = new Config("/home/ClanWars/Clans/" . $pf->get("Clan") . ".yml", Config::YAML);
        $player->getInventory()->clearAll();
        if ($clan->get("player7") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(0, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player7"));
            $player->getInventory()->setItem(0, $c1);

        }

        if ($clan->get("player8") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(1, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player8"));
            $player->getInventory()->setItem(1, $c1);

        }

        if ($clan->get("player9") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(2, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player9"));
            $player->getInventory()->setItem(2, $c1);

        }

        if ($clan->get("player10") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(6, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player10"));
            $player->getInventory()->setItem(6, $c1);

        }

        if ($clan->get("player11") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(7, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player11"));
            $player->getInventory()->setItem(7, $c1);

        }

        if ($clan->get("player12") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(8, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player12"));
            $player->getInventory()->setItem(8, $c1);

        }

        $back = Item::get(372, 0, 1);
        $site1 = Item::get(288, 0, 1);
        $site3 = Item::get(288, 0, 1);
        $back->setCustomName(Color::RED . "Back");
        $site1->setCustomName(Color::BLUE . "Seite 1");
        $site3->setCustomName(Color::BLUE . "Seite 3");
        $player->getInventory()->setItem(3, $site1);
        $player->getInventory()->setItem(4, $back);
        $player->getInventory()->setItem(5, $site3);

    }

    public function clanSiteC(Player $player) {

        $pf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
        $clan = new Config("/home/ClanWars/Clans/" . $pf->get("Clan") . ".yml", Config::YAML);
        $player->getInventory()->clearAll();
        if ($clan->get("player13") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(0, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player13"));
            $player->getInventory()->setItem(0, $c1);

        }

        if ($clan->get("player14") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(1, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player14"));
            $player->getInventory()->setItem(1, $c1);

        }

        if ($clan->get("player15") === "") {

            $c1 = Item::get(397, 0, 1);
            $c1->setCustomName(Color::RED . "Leer");
            $player->getInventory()->setItem(2, $c1);

        } else {

            $c1 = Item::get(397, 3, 1);
            $c1->setCustomName(Color::GOLD . $clan->get("player15"));
            $player->getInventory()->setItem(2, $c1);

        }

        $back = Item::get(372, 0, 1);
        $site2 = Item::get(288, 0, 1);
        $back->setCustomName(Color::RED . "Back");
        $site2->setCustomName(Color::BLUE . "Seite 2");
        $player->getInventory()->setItem(3, $site2);
        $player->getInventory()->setItem(4, $back);

    }

    public function onInteract(PlayerInteractEvent $event)
    {

        $player = $event->getPlayer();
        $pf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
        $clan = new Config("/home/ClanWars/Clans/" . $pf->get("Clan") . ".yml", Config::YAML);
        if ($player->getInventory()->getItemInHand()->getCustomName() === Color::RED . "Back") {

            $player->getInventory()->clearAll();
            $this->mainItems($player);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . "Clans") {

            $player->getInventory()->clearAll();
            $rank = Item::get(395, 0, 1);
            $clan = Item::get(446, 0, 1);
            $back = Item::get(372, 0, 1);
            $rank->setCustomName(Color::YELLOW . "Clan Ranking");
            $clan->setCustomName(Color::YELLOW . "Dein Clan");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $rank);
            $player->getInventory()->setItem(4, $back);
            $player->getInventory()->setItem(6, $clan);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::YELLOW . "Clan Ranking") {

            $player->getInventory()->clearAll();
            $bw = Item::get(336, 0, 1);
            $back = Item::get(372, 0, 1);
            $bw->setCustomName(Color::AQUA . "BedWars-Ranking");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $bw);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::AQUA . "BedWars-Ranking") {

            $player->sendMessage($this->prefix . Color::GRAY . "Dieses System befindet sich noch in der Entwicklung!");
            $this->mainItems($player);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::YELLOW . "Dein Clan") {

            $this->clanCheck($player);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::BLUE . "Seite 1") {

            $this->clanSiteA($player);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::BLUE . "Seite 2") {

            $this->clanSiteB($player);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::BLUE . "Seite 3") {

            $this->clanSiteC($player);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player1")) {

            $pf->set("Player", $clan->get("player1"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player2")) {

            $pf->set("Player", $clan->get("player2"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player3")) {

            $pf->set("Player", $clan->get("player3"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player4")) {

            $pf->set("Player", $clan->get("player4"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player5")) {

            $pf->set("Player", $clan->get("player5"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player6")) {

            $pf->set("Player", $clan->get("player6"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player7")) {

            $pf->set("Player", $clan->get("player7"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player8")) {

            $pf->set("Player", $clan->get("player8"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player9")) {

            $pf->set("Player", $clan->get("player9"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player10")) {

            $pf->set("Player", $clan->get("player10"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player11")) {

            $pf->set("Player", $clan->get("player11"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player12")) {

            $pf->set("Player", $clan->get("player12"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player13")) {

            $pf->set("Player", $clan->get("player13"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player14")) {

            $pf->set("Player", $clan->get("player14"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GOLD . $clan->get("player15")) {

            $pf->set("Player", $clan->get("player15"));
            $pf->save();
            $player->getInventory()->clearAll();
            $invite = Item::get(341, 0, 1);
            $back = Item::get(372, 0, 1);
            $invite->setCustomName(Color::GREEN . "ClanWar-Einladen");
            $back->setCustomName(Color::RED . "Back");
            $player->getInventory()->setItem(2, $invite);
            $player->getInventory()->setItem(4, $back);

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GREEN . "ClanWar-Einladen") {

            if ($clan->get("Owner") === $player->getName()) {

                if (file_exists("/home/ClanWars/players/" . $pf->get("Player") . ".yml")) {

                    $sf = new Config("/home/ClanWars/players/" . $pf->get("Player") . ".yml", Config::YAML);
                    if ($sf->get("Clan") === $pf->get("Clan")) {

                        if ($clan->get("ClanWarMember") >= 4) {

                            $player->sendMessage($this->prefix . Color::RED . "Die ClanWar Member Liste ist voll!");

                        } else {

                            if ($sf->get("ClanWar") === false) {

                                if ($clan->get("ClanWarMember1") === "") {

                                    $v = $this->getServer()->getPlayerExact($pf->get("Player"));
                                    if (!$v == null) {

                                        $clan->set("ClanWarMember1", $pf->get("Player"));
                                        $clan->set("ClanWarMember", $clan->get("ClanWarMember") + 1);
                                        $clan->save();

                                        $sf->set("ClanWar", true);
                                        $sf->save();

                                        $player->sendMessage($this->prefix . Color::GREEN . "Der Spieler wurde erfolgreich zur ClanWar Liste hinzugefuegt!");
                                        $v->sendMessage($this->prefix . Color::GREEN . "Du wurdest erfolgreich zur ClanWar Liste hinzugefuegt!");

                                    } else {

                                        $player->sendMessage($this->prefix . Color::RED . "Der Spieler ist derzeit Offline!");

                                    }

                                } else if ($clan->get("ClanWarMember2") === "") {


                                    $v = $this->getServer()->getPlayerExact($pf->get("Player"));
                                    if (!$v == null) {

                                        $clan->set("ClanWarMember2", $pf->get("Player"));
                                        $clan->set("ClanWarMember", $clan->get("ClanWarMember") + 1);
                                        $clan->save();

                                        $sf->set("ClanWar", true);
                                        $sf->save();

                                        $player->sendMessage($this->prefix . Color::GREEN . "Der Spieler wurde erfolgreich zur ClanWar Liste hinzugefuegt!");
                                        $v->sendMessage($this->prefix . Color::GREEN . "Du wurdest erfolgreich zur ClanWar Liste hinzugefuegt!");

                                    } else {

                                        $player->sendMessage($this->prefix . Color::RED . "Der Spieler ist derzeit Offline!");

                                    }

                                } else if ($clan->get("ClanWarMember3") === "") {


                                    $v = $this->getServer()->getPlayerExact($pf->get("Player"));
                                    if (!$v == null) {

                                        $clan->set("ClanWarMember3", $pf->get("Player"));
                                        $clan->set("ClanWarMember", $clan->get("ClanWarMember") + 1);
                                        $clan->save();

                                        $sf->set("ClanWar", true);
                                        $sf->save();

                                        $player->sendMessage($this->prefix . Color::GREEN . "Der Spieler wurde erfolgreich zur ClanWar Liste hinzugefuegt!");
                                        $v->sendMessage($this->prefix . Color::GREEN . "Du wurdest erfolgreich zur ClanWar Liste hinzugefuegt!");

                                    } else {

                                        $player->sendMessage($this->prefix . Color::RED . "Der Spieler ist derzeit Offline!");

                                    }

                                } else if ($clan->get("ClanWarMember4") === "") {


                                    $v = $this->getServer()->getPlayerExact($pf->get("Player"));
                                    if (!$v == null) {

                                        $clan->set("ClanWarMember4", $pf->get("Player"));
                                        $clan->set("ClanWarMember", $clan->get("ClanWarMember") + 1);
                                        $clan->save();

                                        $sf->set("ClanWar", true);
                                        $sf->save();

                                        $player->sendMessage($this->prefix . Color::GREEN . "Der Spieler wurde erfolgreich zur ClanWar Liste hinzugefuegt!");
                                        $v->sendMessage($this->prefix . Color::GREEN . "Du wurdest erfolgreich zur ClanWar Liste hinzugefuegt!");

                                    } else {

                                        $player->sendMessage($this->prefix . Color::RED . "Der Spieler ist derzeit Offline!");

                                    }

                                } else {

                                    $player->sendMessage($this->prefix . Color::RED . "FEHLER CODE #0002");

                                }

                            } else {

                                $player->sendMessage($this->prefix . Color::RED . "Der Spieler befindet sich schon in der Liste!");

                            }

                        }

                    } else {

                        $player->sendMessage($this->prefix . Color::RED . "Dieser Spieler ist nicht in deinem Clan!");

                    }

                } else {

                    $player->sendMessage($this->prefix . Color::RED . "Diesen Spieler gibt es nicht!");

                }

            } else {

                $player->sendMessage($this->prefix . Color::RED . "Du brauchst in deinem Clan dafuer eine hoehere Position!");

            }

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::GREEN . "ClanWar Queue") {

            $pf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Clan") === "") {

                $player->sendMessage($this->prefix . Color::RED . "Du befindest dich in keinem Clan!");

            } else {

                $clan = new Config("/home/ClanWars/Clans/" . $pf->get("Clan") . ".yml", Config::YAML);
                if ($clan->get("ClanWar") === false) {

                    if ($clan->get("Owner") === $player->getName()) {

                        if ($clan->get("ClanWarMember") === 4) {

                            $cwfile = new Config("/home/ClanWars/ClanWars.yml", Config::YAML);
                            if ($cwfile->get("ClanWar1") === false) {

                                $cwfile->set("ClanWar1", true);
                                $cwfile->save();
                                if ($cwfile->get("ClanWar1Blau") === "") {

                                    $cwfile->set("ClanWar1Blau", $pf->get("Clan"));
                                    $cwfile->save();
                                    $clan->set("ClanWar", true);
                                    $clan->save();
                                    $player->sendMessage($this->prefix . Color::GREEN . "Du bist erfolgreich der Queue beigetreten!");

                                    $player->getInventory()->clearAll();

                                    $cw = Item::get(345, 0, 1);
                                    $cw->setCustomName(Color::RED . "ClanWar Queue");
                                    $player->getInventory()->setItem(4, $cw);

                                } else {

                                    if ($cwfile->get("ClanWar1Rot") === "") {

                                        $cwfile->set("ClanWar1Rot", $pf->get("Clan"));
                                        $cwfile->set("ClanWar", true);
                                        $cwfile->save();
                                        $clan->set("ClanWar", true);
                                        $clan->save();
                                        $player->sendMessage($this->prefix . Color::GREEN . "Du bist erfolgreich der Queue beigetreten!");

                                        $player->getInventory()->clearAll();

                                        $cw = Item::get(345, 0, 1);
                                        $cw->setCustomName(Color::RED . "ClanWar Queue");
                                        $player->getInventory()->setItem(4, $cw);

                                        $cr1 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember1"));
                                        if (!$cr1 == null) {

                                            $cr1->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr1->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember1") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar1");
                                            $sf->save();

                                        }

                                        $cr2 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember2"));
                                        if (!$cr2 == null) {

                                            $cr2->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr2->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember2") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar1");
                                            $sf->save();

                                        }

                                        $cr3 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember3"));
                                        if (!$cr3 == null) {

                                            $cr3->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr3->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember3") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar1");
                                            $sf->save();

                                        }

                                        $cr4 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember4"));
                                        if (!$cr4 == null) {

                                            $cr4->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr4->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember4") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar1");
                                            $sf->save();

                                        }

                                        $clan2 = new Config("/home/ClanWars/Clans/" . $cwfile->get("ClanWar1Blau") . ".yml", Config::YAML);
                                        $cr5 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember1"));
                                        if (!$cr5 == null) {

                                            $cr5->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr5->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember1") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar1");
                                            $sf->save();

                                        }

                                        $cr6 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember2"));
                                        if (!$cr6 == null) {

                                            $cr6->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr6->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember2") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar1");
                                            $sf->save();

                                        }

                                        $cr7 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember3"));
                                        if (!$cr7 == null) {

                                            $cr7->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr7->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember3") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar1");
                                            $sf->save();

                                        }

                                        $cr8 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember4"));
                                        if (!$cr8 == null) {

                                            $cr8->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr8->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember4") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar1");
                                            $sf->save();

                                        }

                                    } else {

                                        $player->sendMessage($this->prefix . Color::RED . "FEHLER CODE #0001");

                                    }

                                }

                            } else if ($cwfile->get("ClanWar2") === false) {

                                $cwfile->set("ClanWar2", true);
                                $cwfile->save();
                                if ($cwfile->get("ClanWar2Blau") === "") {

                                    $cwfile->set("ClanWar2Blau", $pf->get("Clan"));
                                    $cwfile->save();
                                    $clan->set("ClanWar", true);
                                    $clan->save();
                                    $player->sendMessage($this->prefix . Color::GREEN . "Du bist erfolgreich der Queue beigetreten!");

                                    $player->getInventory()->clearAll();

                                    $cw = Item::get(345, 0, 1);
                                    $cw->setCustomName(Color::RED . "ClanWar Queue");
                                    $player->getInventory()->setItem(4, $cw);

                                } else {

                                    if ($cwfile->get("ClanWar2Rot") === "") {

                                        $cwfile->set("ClanWar2Rot", $pf->get("Clan"));
                                        $cwfile->set("ClanWar", true);
                                        $cwfile->save();
                                        $clan->set("ClanWar", true);
                                        $clan->save();
                                        $player->sendMessage($this->prefix . Color::GREEN . "Du bist erfolgreich der Queue beigetreten!");

                                        $player->getInventory()->clearAll();

                                        $cw = Item::get(345, 0, 1);
                                        $cw->setCustomName(Color::RED . "ClanWar Queue");
                                        $player->getInventory()->setItem(4, $cw);

                                        $cr1 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember1"));
                                        if (!$cr1 == null) {

                                            $cr1->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr1->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember1") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar2");
                                            $sf->save();

                                        }

                                        $cr2 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember2"));
                                        if (!$cr2 == null) {

                                            $cr2->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr2->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember2") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar2");
                                            $sf->save();

                                        }

                                        $cr3 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember3"));
                                        if (!$cr3 == null) {

                                            $cr3->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr3->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember3") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar2");
                                            $sf->save();

                                        }

                                        $cr4 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember4"));
                                        if (!$cr4 == null) {

                                            $cr4->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr4->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember4") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar2");
                                            $sf->save();

                                        }

                                        $clan2 = new Config("/home/ClanWars/Clans/" . $cwfile->get("ClanWar1Blau") . ".yml", Config::YAML);
                                        $cr5 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember1"));
                                        if (!$cr5 == null) {

                                            $cr5->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr5->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember1") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar2");
                                            $sf->save();

                                        }

                                        $cr6 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember2"));
                                        if (!$cr6 == null) {

                                            $cr6->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr6->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember2") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar2");
                                            $sf->save();

                                        }

                                        $cr7 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember3"));
                                        if (!$cr7 == null) {

                                            $cr7->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr7->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember3") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar2");
                                            $sf->save();

                                        }

                                        $cr8 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember4"));
                                        if (!$cr8 == null) {

                                            $cr8->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr8->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember4") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar2");
                                            $sf->save();

                                        }

                                    } else {

                                        $player->sendMessage($this->prefix . Color::RED . "FEHLER CODE #0001");

                                    }

                                }

                            } else if ($cwfile->get("ClanWar3") === false) {

                                $cwfile->set("ClanWar3", true);
                                $cwfile->save();
                                if ($cwfile->get("ClanWar3Blau") === "") {

                                    $cwfile->set("ClanWar3Blau", $pf->get("Clan"));
                                    $cwfile->save();
                                    $clan->set("ClanWar", true);
                                    $clan->save();
                                    $player->sendMessage($this->prefix . Color::GREEN . "Du bist erfolgreich der Queue beigetreten!");

                                    $player->getInventory()->clearAll();

                                    $cw = Item::get(345, 0, 1);
                                    $cw->setCustomName(Color::RED . "ClanWar Queue");
                                    $player->getInventory()->setItem(4, $cw);

                                } else {

                                    if ($cwfile->get("ClanWar3Rot") === "") {

                                        $cwfile->set("ClanWar3Rot", $pf->get("Clan"));
                                        $cwfile->set("ClanWar", true);
                                        $cwfile->save();
                                        $clan->set("ClanWar", true);
                                        $clan->save();
                                        $player->sendMessage($this->prefix . Color::GREEN . "Du bist erfolgreich der Queue beigetreten!");

                                        $player->getInventory()->clearAll();

                                        $cw = Item::get(345, 0, 1);
                                        $cw->setCustomName(Color::RED . "ClanWar Queue");
                                        $player->getInventory()->setItem(4, $cw);

                                        $cr1 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember1"));
                                        if (!$cr1 == null) {

                                            $cr1->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr1->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember1") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar3");
                                            $sf->save();

                                        }

                                        $cr2 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember2"));
                                        if (!$cr2 == null) {

                                            $cr2->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr2->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember2") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar3");
                                            $sf->save();

                                        }

                                        $cr3 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember3"));
                                        if (!$cr3 == null) {

                                            $cr3->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr3->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember3") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar3");
                                            $sf->save();

                                        }

                                        $cr4 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember4"));
                                        if (!$cr4 == null) {

                                            $cr4->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr4->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember4") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar3");
                                            $sf->save();

                                        }

                                        $clan2 = new Config("/home/ClanWars/Clans/" . $cwfile->get("ClanWar1Blau") . ".yml", Config::YAML);
                                        $cr5 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember1"));
                                        if (!$cr5 == null) {

                                            $cr5->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr5->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember1") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar3");
                                            $sf->save();

                                        }

                                        $cr6 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember2"));
                                        if (!$cr6 == null) {

                                            $cr6->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr6->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember2") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar3");
                                            $sf->save();

                                        }

                                        $cr7 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember3"));
                                        if (!$cr7 == null) {

                                            $cr7->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr7->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember3") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar3");
                                            $sf->save();

                                        }

                                        $cr8 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember4"));
                                        if (!$cr8 == null) {

                                            $cr8->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr8->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember4") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar3");
                                            $sf->save();

                                        }

                                    } else {

                                        $player->sendMessage($this->prefix . Color::RED . "FEHLER CODE #0001");

                                    }

                                }

                            } else if ($cwfile->get("ClanWar4") === false) {

                                $cwfile->set("ClanWar4", true);
                                $cwfile->save();
                                if ($cwfile->get("ClanWar4Blau") === "") {

                                    $cwfile->set("ClanWar4Blau", $pf->get("Clan"));
                                    $cwfile->save();
                                    $clan->set("ClanWar", true);
                                    $clan->save();
                                    $player->sendMessage($this->prefix . Color::GREEN . "Du bist erfolgreich der Queue beigetreten!");

                                    $player->getInventory()->clearAll();

                                    $cw = Item::get(345, 0, 1);
                                    $cw->setCustomName(Color::RED . "ClanWar Queue");
                                    $player->getInventory()->setItem(4, $cw);

                                } else {

                                    if ($cwfile->get("ClanWar4Rot") === "") {

                                        $cwfile->set("ClanWar4Rot", $pf->get("Clan"));
                                        $cwfile->set("ClanWar", true);
                                        $cwfile->save();
                                        $clan->set("ClanWar", true);
                                        $clan->save();
                                        $player->sendMessage($this->prefix . Color::GREEN . "Du bist erfolgreich der Queue beigetreten!");

                                        $player->getInventory()->clearAll();

                                        $cw = Item::get(345, 0, 1);
                                        $cw->setCustomName(Color::RED . "ClanWar Queue");
                                        $player->getInventory()->setItem(4, $cw);

                                        $cr1 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember1"));
                                        if (!$cr1 == null) {

                                            $cr1->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr1->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember1") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar4");
                                            $sf->save();

                                        }

                                        $cr2 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember2"));
                                        if (!$cr2 == null) {

                                            $cr2->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr2->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember2") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar4");
                                            $sf->save();

                                        }

                                        $cr3 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember3"));
                                        if (!$cr3 == null) {

                                            $cr3->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr3->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember3") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar4");
                                            $sf->save();

                                        }

                                        $cr4 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember4"));
                                        if (!$cr4 == null) {

                                            $cr4->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr4->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember4") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar4");
                                            $sf->save();

                                        }

                                        $clan2 = new Config("/home/ClanWars/Clans/" . $cwfile->get("ClanWar1Blau") . ".yml", Config::YAML);
                                        $cr5 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember1"));
                                        if (!$cr5 == null) {

                                            $cr5->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr5->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember1") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar4");
                                            $sf->save();

                                        }

                                        $cr6 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember2"));
                                        if (!$cr6 == null) {

                                            $cr6->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr6->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember2") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar4");
                                            $sf->save();

                                        }

                                        $cr7 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember3"));
                                        if (!$cr7 == null) {

                                            $cr7->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr7->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember3") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar4");
                                            $sf->save();

                                        }

                                        $cr8 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember4"));
                                        if (!$cr8 == null) {

                                            $cr8->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr8->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember4") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar4");
                                            $sf->save();

                                        }

                                    } else {

                                        $player->sendMessage($this->prefix . Color::RED . "FEHLER CODE #0001");

                                    }

                                }

                            } else if ($cwfile->get("ClanWar5") === false) {

                                $cwfile->set("ClanWar5", true);
                                $cwfile->save();
                                if ($cwfile->get("ClanWar5Blau") === "") {

                                    $cwfile->set("ClanWar5Blau", $pf->get("Clan"));
                                    $cwfile->save();
                                    $clan->set("ClanWar", true);
                                    $clan->save();
                                    $player->sendMessage($this->prefix . Color::GREEN . "Du bist erfolgreich der Queue beigetreten!");

                                    $player->getInventory()->clearAll();

                                    $cw = Item::get(345, 0, 1);
                                    $cw->setCustomName(Color::RED . "ClanWar Queue");
                                    $player->getInventory()->setItem(4, $cw);

                                } else {

                                    if ($cwfile->get("ClanWar5Rot") === "") {

                                        $cwfile->set("ClanWar5Rot", $pf->get("Clan"));
                                        $cwfile->set("ClanWar", true);
                                        $cwfile->save();
                                        $clan->set("ClanWar", true);
                                        $clan->save();
                                        $player->sendMessage($this->prefix . Color::GREEN . "Du bist erfolgreich der Queue beigetreten!");

                                        $player->getInventory()->clearAll();

                                        $cw = Item::get(345, 0, 1);
                                        $cw->setCustomName(Color::RED . "ClanWar Queue");
                                        $player->getInventory()->setItem(4, $cw);

                                        $cr1 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember1"));
                                        if (!$cr1 == null) {

                                            $cr1->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr1->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember1") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar5");
                                            $sf->save();

                                        }

                                        $cr2 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember2"));
                                        if (!$cr2 == null) {

                                            $cr2->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr2->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember2") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar5");
                                            $sf->save();

                                        }

                                        $cr3 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember3"));
                                        if (!$cr3 == null) {

                                            $cr3->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr3->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember3") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar5");
                                            $sf->save();

                                        }

                                        $cr4 = $this->getServer()->getPlayerExact($clan->get("ClanWarMember4"));
                                        if (!$cr4 == null) {

                                            $cr4->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr4->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan->get("ClanWarMember4") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar5");
                                            $sf->save();

                                        }

                                        $clan2 = new Config("/home/ClanWars/Clans/" . $cwfile->get("ClanWar1Blau") . ".yml", Config::YAML);
                                        $cr5 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember1"));
                                        if (!$cr5 == null) {

                                            $cr5->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr5->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember1") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar5");
                                            $sf->save();

                                        }

                                        $cr6 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember2"));
                                        if (!$cr6 == null) {

                                            $cr6->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr6->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember2") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar5");
                                            $sf->save();

                                        }

                                        $cr7 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember3"));
                                        if (!$cr7 == null) {

                                            $cr7->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr7->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember3") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar5");
                                            $sf->save();

                                        }

                                        $cr8 = $this->getServer()->getPlayerExact($clan2->get("ClanWarMember4"));
                                        if (!$cr8 == null) {

                                            $cr8->sendMessage($this->prefix . Color::GREEN . "Es wurde ein gegner Clan gefunden!");
                                            $cr8->sendMessage($this->prefix . Color::GREEN . "Du wirst in kuerze transferiert!");
                                            $sf = new Config("/home/ClanWars/players/" . $clan2->get("ClanWarMember4") . ".yml", Config::YAML);
                                            $sf->set("Transfer", "ClanWar5");
                                            $sf->save();

                                        }

                                    } else {

                                        $player->sendMessage($this->prefix . Color::RED . "FEHLER CODE #0001");

                                    }

                                }

                            } //Hier

                        } else {

                            $player->sendMessage($this->prefix . Color::RED . "Bitte such zuerst aus deinem Clan Mitglieder aus!");

                        }

                    } else {

                        $player->sendMessage($this->prefix . Color::RED . "Du brauchst in deinem Clan dafuer eine hoehere Position!");

                    }

                } else {

                    $player->sendMessage($this->prefix . Color::RED . "Dein Clan befindet sich schon in der Queue!");

                }

            }

        } else if ($player->getInventory()->getItemInHand()->getCustomName() === Color::RED . "ClanWar Queue") {

            $pf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
            $clan = new Config("/home/ClanWars/Clans/" . $pf->get("Clan") . ".yml", Config::YAML);
            $cwfile = new Config("/home/ClanWars/ClanWars.yml", Config::YAML);
            if ($cwfile->get("ClanWar1Blau") === $pf->get("Clan")) {

                $cwfile->set("ClanWar1Blau", "");
                $cwfile->save();
                $clan->set("ClanWar", false);
                $clan->save();

                $player->sendMessage($this->prefix . Color::RED . "Du hast die Queue nun verlassen!");

                $player->getInventory()->clearAll();

                $cw = Item::get(345, 0, 1);
                $cw->setCustomName(Color::GREEN . "ClanWar Queue");
                $player->getInventory()->setItem(4, $cw);

            }

        }

    }

    public function onDamage(EntityDamageEvent $event) {

        $event->setCancelled(true);

    }

}

class ClanWarTask extends Task
{

    public function __construct($plugin)
    {

        $this->plugin = $plugin;
        $this->prefix = $this->plugin->prefix;

    }

    public function onRun(int $currentTick) : void
    {

        if ($this->plugin->load === 1) {

            $this->plugin->load = 2;

        } else if ($this->plugin->load === 2) {

            $this->plugin->load = 3;

        } else if ($this->plugin->load === 3) {

            $this->plugin->load = 1;

        }

        $all = $this->plugin->getServer()->getOnlinePlayers();
        foreach ($all as $player) {

            $pf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
            $api = Scoreboards::getInstance();
            $api->new($player, "ObjectiveName", Color::AQUA . " ClanWars ");
            if ($pf->get("Clan") === "") {

                $api->setLine($player, 1, " ");
                $api->setLine($player, 2, Color::AQUA . " Clan: " . Color::GRAY . "[" . Color::YELLOW . "-" . Color::GRAY . "]" . " ");
                $api->getObjectiveName($player);

                $player->sendPopup(Color::RED . "Du befindest dich in keinem Clan!");

            } else {

                $clan = new Config("/home/ClanWars/Clans/" . $pf->get("Clan") . ".yml", Config::YAML);

                $api->setLine($player, 1, " ");
                $api->setLine($player, 2, Color::AQUA . " Clan: " . Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "]" . " ");
                $api->setLine($player, 3, "   ");
                $api->setLine($player, 4, Color::AQUA . " ClanWar-Member:" . " ");
                $api->setLine($player, 5, "    ");
                $api->setLine($player, 6, Color::AQUA . " 1: " . Color::GRAY . $clan->get("ClanWarMember1") . " ");
                $api->setLine($player, 7, Color::AQUA . " 2: " . Color::GRAY . $clan->get("ClanWarMember2") . " ");
                $api->setLine($player, 8, Color::AQUA . " 3: " . Color::GRAY . $clan->get("ClanWarMember3") . " ");
                $api->setLine($player, 9, Color::AQUA . " 4: " . Color::GRAY . $clan->get("ClanWarMember4") . " ");
                $api->getObjectiveName($player);

                if ($pf->get("Transfer") === "ClanWar1") {

                    $pf->set("Transfer", "");
                    $pf->save();
                    $clan->set("ClanWarMember1", "");
                    $clan->set("ClanWarMember2", "");
                    $clan->set("ClanWarMember3", "");
                    $clan->set("ClanWarMember4", "");
                    $clan->set("ClanWarMember", 0);
                    $clan->save();
                    $player->sendMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::GREEN . "Du wirst nun Transferiert!");
                    $cwfig = new Config("/home/ClanWars/config.yml", Config::YAML);
                    $player->transfer($cwfig->get("IP"), $cwfig->get("Port-1"));

                } else if ($pf->get("Transfer") === "ClanWar2") {

                    $pf->set("Transfer", "");
                    $pf->save();
                    $clan->set("ClanWarMember1", "");
                    $clan->set("ClanWarMember2", "");
                    $clan->set("ClanWarMember3", "");
                    $clan->set("ClanWarMember4", "");
                    $clan->set("ClanWarMember", 0);
                    $clan->save();
                    $player->sendMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::GREEN . "Du wirst nun Transferiert!");
                    $cwfig = new Config("/home/ClanWars/config.yml", Config::YAML);
                    $player->transfer($cwfig->get("IP"), $cwfig->get("Port-2"));

                } else if ($pf->get("Transfer") === "ClanWar3") {

                    $pf->set("Transfer", "");
                    $pf->save();
                    $clan->set("ClanWarMember1", "");
                    $clan->set("ClanWarMember2", "");
                    $clan->set("ClanWarMember3", "");
                    $clan->set("ClanWarMember4", "");
                    $clan->set("ClanWarMember", 0);
                    $clan->save();
                    $player->sendMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::GREEN . "Du wirst nun Transferiert!");
                    $cwfig = new Config("/home/ClanWars/config.yml", Config::YAML);
                    $player->transfer($cwfig->get("IP"), $cwfig->get("Port-3"));

                } else if ($pf->get("Transfer") === "ClanWar4") {

                    $pf->set("Transfer", "");
                    $pf->save();
                    $clan->set("ClanWarMember1", "");
                    $clan->set("ClanWarMember2", "");
                    $clan->set("ClanWarMember3", "");
                    $clan->set("ClanWarMember4", "");
                    $clan->set("ClanWarMember", 0);
                    $clan->save();
                    $player->sendMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::GREEN . "Du wirst nun Transferiert!");
                    $cwfig = new Config("/home/ClanWars/config.yml", Config::YAML);
                    $player->transfer($cwfig->get("IP"), $cwfig->get("Port-4"));

                } else if ($pf->get("Transfer") === "ClanWar5") {

                    $pf->set("Transfer", "");
                    $pf->save();
                    $clan->set("ClanWarMember1", "");
                    $clan->set("ClanWarMember2", "");
                    $clan->set("ClanWarMember3", "");
                    $clan->set("ClanWarMember4", "");
                    $clan->set("ClanWarMember", 0);
                    $clan->save();
                    $player->sendMessage(Color::YELLOW . "SERVER" . Color::DARK_GRAY . " : " . Color::GREEN . "Du wirst nun Transferiert!");
                    $cwfig = new Config("/home/ClanWars/config.yml", Config::YAML);
                    $player->transfer($cwfig->get("IP"), $cwfig->get("Port-5"));

                }

                if ($clan->get("ClanWar") === true) {

                    if ($this->plugin->load === 1) {

                        $player->sendPopup(Color::GREEN . "Warte auf einen weiteren Clan.");

                    } else if ($this->plugin->load === 2) {

                        $player->sendPopup(Color::GREEN . "Warte auf einen weiteren Clan..");

                    } else if ($this->plugin->load === 3) {

                        $player->sendPopup(Color::GREEN . "Warte auf einen weiteren Clan...");

                    }

                } else {

                    $player->sendPopup(Color::GRAY . "Um Spielen zu koennen trete der Queue bei!");

                }

            }

        }

    }

}