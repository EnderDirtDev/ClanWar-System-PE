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

class Clan extends PluginBase implements Listener
{

    public $prefix = Color::YELLOW . "Clans" . Color::DARK_GRAY . " : ";

    public function onEnable()
    {

        $this->getLogger()->info($this->prefix . Color::GRAY . "lade...");

        if (is_dir("/home/ClanWars/Clans") !== true) {

            mkdir("/home/ClanWars/Clans");

        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info($this->prefix . Color::GREEN . "wurde geladen!");
        $this->getLogger()->info($this->prefix . Color::AQUA . "Made By" . Color::GREEN . " EnderDirt!");

    }

    public function onDisable()
    {

        $this->getLogger()->info($this->prefix . Color::GRAY . "lade...");
        $this->getLogger()->info($this->prefix . Color::RED . "konnte nicht geladen werden!");

    }

    public function onJoin(PlayerJoinEvent $event)
    {

        $player = $event->getPlayer();
        $this->setGroup($player);

    }

    public function onQuit(PlayerQuitEvent $event)
    {

        $player = $event->getPlayer();
        $pf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
        $clan = new Config("/home/ClanWars/Clans/" . $pf->get("Clan") . ".yml", Config::YAML);
        if ($pf->get("ClanWar") === true) {

            $pf->set("ClanWar", false);
            $pf->save();
            $clan->set("ClanWarMember", $clan->get("ClanWarMember")-1);
            $clan->save();
            if ($clan->get("ClanWarMember1") === $player->getName()) {

                $clan->set("ClanWarMember1", "");
                $clan->save();

            } else if ($clan->get("ClanWarMember2") === $player->getName()) {

                $clan->set("ClanWarMember2", "");
                $clan->save();

            } else if ($clan->get("ClanWarMember3") === $player->getName()) {

                $clan->set("ClanWarMember3", "");
                $clan->save();

            } else if ($clan->get("ClanWarMember4") === $player->getName()) {

                $clan->set("ClanWarMember4", "");
                $clan->save();

            } else {

                $player->sendMessage($this->prefix . Color::RED . "FEHLER CODE #0003");

            }

        }

    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool
    {

        if ($command->getName() === "Clan") {

            if (isset($args[0])) {

                if (strtolower($args[0]) === "make") {

                    if (isset($args[1])) {

                        if (file_exists("/home/ClanWars/Clans/" . $args[1] . ".yml")) {

                            $sender->sendMessage($this->prefix . Color::RED . "Diesen Clan gibt es schon!");

                        } else {

                            $pf = new Config("/home/ClanWars/players/" . $sender->getName() . ".yml", Config::YAML);
                            if ($pf->get("Clan") === "") {

                                $clan = new Config("/home/ClanWars/Clans/" . $args[1] . ".yml", Config::YAML);
                                $clan->set("Owner", $sender->getName());
                                $clan->set("player1", $sender->getName());
                                $clan->set("player2", "");
                                $clan->set("player3", "");
                                $clan->set("player4", "");
                                $clan->set("player5", "");
                                $clan->set("player6", "");
                                $clan->set("player7", "");
                                $clan->set("player8", "");
                                $clan->set("player9", "");
                                $clan->set("player10", "");
                                $clan->set("player11", "");
                                $clan->set("player12", "");
                                $clan->set("player13", "");
                                $clan->set("player14", "");
                                $clan->set("player15", "");
                                $clan->set("Member", 1);
                                $clan->set("ClanWarMember", 0);
                                $clan->set("ClanWarMember1", "");
                                $clan->set("ClanWarMember2", "");
                                $clan->set("ClanWarMember3", "");
                                $clan->set("ClanWarMember4", "");
                                $clan->set("ClanWar", false);
                                $clan->save();
                                $pf->set("Clan", $args[1]);
                                $pf->save();
                                $this->setGroup($sender);
                                $sender->sendMessage($this->prefix . Color::GREEN . "Der Clan: " . Color::YELLOW . $pf->get("Clan") . Color::GREEN . " wurde erfolgreich erstellt!");
                                $cw = Item::get(345, 0, 1);
                                $cw->setCustomName(Color::GREEN . "ClanWar Queue");
                                $sender->getInventory()->setItem(4, $cw);

                            } else {

                                $sender->sendMessage($this->prefix . Color::RED . "Du befindest dich schon in einem Clan!");

                            }

                        }

                    } else {

                        $sender->sendMessage($this->prefix . Color::RED . "/clan make <ClanName>");

                    }

                } else if (strtolower($args[0]) === "accept") {

                    $pf = new Config("/home/ClanWars/players/" . $sender->getName() . ".yml", Config::YAML);
                    if ($pf->get("Clan") === "") {

                        if ($pf->get("ClanAnfrage") === "") {

                            $sender->sendMessage($this->prefix . Color::RED . "Du hast keine Clan Anfrage bekommen!");

                        } else {

                            $clan = new Config("/home/ClanWars/Clans/" . $pf->get("ClanAnfrage") . ".yml", Config::YAML);
                            if ($clan->get("Member") >= 15) {

                                $sender->sendMessage($this->prefix . Color::RED . "Dieser Clan ist derzeit voll!");

                            } else {

                                $member = $clan->get("Member") + 1;
                                $clan->set("player" . $member, $sender->getName());
                                $clan->set("Member", $clan->get("Member") + 1);
                                $clan->save();
                                $pf->set("Clan", $pf->get("ClanAnfrage"));
                                $pf->set("ClanAnfrage", "");
                                $pf->save();
                                $this->setGroup($sender);
                                $sender->sendMessage($this->prefix . Color::GREEN . "Du bist dem Clan erfolgreich beigetreten!");
                                $cw = Item::get(345, 0, 1);
                                $cw->setCustomName(Color::GREEN . "ClanWar Queue");
                                $sender->getInventory()->setItem(4, $cw);

                            }

                        }

                    } else {

                        $sender->sendMessage($this->prefix . Color::RED . "Du bist schon in einem Clan!");

                    }

                } else if (strtolower($args[0]) === "leave") {

                    $pf = new Config("/home/ClanWars/players/" . $sender->getName() . ".yml", Config::YAML);
                    if ($pf->get("Clan") === "") {

                        $sender->sendMessage($this->prefix . Color::RED . "Du bist in keinem Clan!");

                    } else {

                        $clan = new Config("/home/ClanWars/Clans/" . $pf->get("Clan") . ".yml", Config::YAML);
                        if ($clan->get("Member") > 1) {

                            if ($clan->get("player1") === $sender->getName()) {

                                $clan->set("Owner", $clan->get("player2"));
                                $clan->set("player1", $clan->get("player2"));
                                $clan->set("player2", $clan->get("player3"));
                                $clan->set("player3", $clan->get("player4"));
                                $clan->set("player4", $clan->get("player5"));
                                $clan->set("player5", $clan->get("player6"));
                                $clan->set("player6", $clan->get("player7"));
                                $clan->set("player7", $clan->get("player8"));
                                $clan->set("player8", $clan->get("player9"));
                                $clan->set("player9", $clan->get("player10"));
                                $clan->set("player10", $clan->get("player11"));
                                $clan->set("player11", $clan->get("player12"));
                                $clan->set("player12", $clan->get("player13"));
                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player2") === $sender->getName()) {

                                $clan->set("player2", $clan->get("player3"));
                                $clan->set("player3", $clan->get("player4"));
                                $clan->set("player4", $clan->get("player5"));
                                $clan->set("player5", $clan->get("player6"));
                                $clan->set("player6", $clan->get("player7"));
                                $clan->set("player7", $clan->get("player8"));
                                $clan->set("player8", $clan->get("player9"));
                                $clan->set("player9", $clan->get("player10"));
                                $clan->set("player10", $clan->get("player11"));
                                $clan->set("player11", $clan->get("player12"));
                                $clan->set("player12", $clan->get("player13"));
                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player3") === $sender->getName()) {

                                $clan->set("player3", $clan->get("player4"));
                                $clan->set("player4", $clan->get("player5"));
                                $clan->set("player5", $clan->get("player6"));
                                $clan->set("player6", $clan->get("player7"));
                                $clan->set("player7", $clan->get("player8"));
                                $clan->set("player8", $clan->get("player9"));
                                $clan->set("player9", $clan->get("player10"));
                                $clan->set("player10", $clan->get("player11"));
                                $clan->set("player11", $clan->get("player12"));
                                $clan->set("player12", $clan->get("player13"));
                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player4") === $sender->getName()) {

                                $clan->set("player4", $clan->get("player5"));
                                $clan->set("player5", $clan->get("player6"));
                                $clan->set("player6", $clan->get("player7"));
                                $clan->set("player7", $clan->get("player8"));
                                $clan->set("player8", $clan->get("player9"));
                                $clan->set("player9", $clan->get("player10"));
                                $clan->set("player10", $clan->get("player11"));
                                $clan->set("player11", $clan->get("player12"));
                                $clan->set("player12", $clan->get("player13"));
                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player5") === $sender->getName()) {

                                $clan->set("player5", $clan->get("player6"));
                                $clan->set("player6", $clan->get("player7"));
                                $clan->set("player7", $clan->get("player8"));
                                $clan->set("player8", $clan->get("player9"));
                                $clan->set("player9", $clan->get("player10"));
                                $clan->set("player10", $clan->get("player11"));
                                $clan->set("player11", $clan->get("player12"));
                                $clan->set("player12", $clan->get("player13"));
                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player6") === $sender->getName()) {

                                $clan->set("player6", $clan->get("player7"));
                                $clan->set("player7", $clan->get("player8"));
                                $clan->set("player8", $clan->get("player9"));
                                $clan->set("player9", $clan->get("player10"));
                                $clan->set("player10", $clan->get("player11"));
                                $clan->set("player11", $clan->get("player12"));
                                $clan->set("player12", $clan->get("player13"));
                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player7") === $sender->getName()) {

                                $clan->set("player7", $clan->get("player8"));
                                $clan->set("player8", $clan->get("player9"));
                                $clan->set("player9", $clan->get("player10"));
                                $clan->set("player10", $clan->get("player11"));
                                $clan->set("player11", $clan->get("player12"));
                                $clan->set("player12", $clan->get("player13"));
                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player8") === $sender->getName()) {

                                $clan->set("player8", $clan->get("player9"));
                                $clan->set("player9", $clan->get("player10"));
                                $clan->set("player10", $clan->get("player11"));
                                $clan->set("player11", $clan->get("player12"));
                                $clan->set("player12", $clan->get("player13"));
                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player9") === $sender->getName()) {

                                $clan->set("player9", $clan->get("player10"));
                                $clan->set("player10", $clan->get("player11"));
                                $clan->set("player11", $clan->get("player12"));
                                $clan->set("player12", $clan->get("player13"));
                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player10") === $sender->getName()) {

                                $clan->set("player10", $clan->get("player11"));
                                $clan->set("player11", $clan->get("player12"));
                                $clan->set("player12", $clan->get("player13"));
                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player11") === $sender->getName()) {

                                $clan->set("player11", $clan->get("player12"));
                                $clan->set("player12", $clan->get("player13"));
                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player12") === $sender->getName()) {

                                $clan->set("player12", $clan->get("player13"));
                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player13") === $sender->getName()) {

                                $clan->set("player13", $clan->get("player14"));
                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player14") === $sender->getName()) {

                                $clan->set("player14", $clan->get("player15"));
                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            } else if ($clan->get("player15") === $sender->getName()) {

                                $clan->set("player15", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();

                                $pf->set("Clan", "");
                                $pf->save();

                                $sender->sendMessage($this->prefix . Color::GREEN . "Du hast den Clan erfolgreich verlassen!");

                            }

                            $this->setGroup($sender);
                            $sender->getInventory()->clearAll();

                        } else {

                            $sender->sendMessage($this->prefix . Color::RED . "FEHLER CODE #0001");

                        }

                    }

                } else if (strtolower($args[0]) === "add") {

                    $pf = new Config("/home/ClanWars/players/" . $sender->getName() . ".yml", Config::YAML);
                    if (isset($args[1])) {

                        if ($pf->get("Clan") === "") {

                            $sender->sendMessage($this->prefix . Color::RED . "Du bist in keinem Clan!");

                        } else {

                            $clan = new Config("/home/ClanWars/Clans/" . $pf->get("Clan") . ".yml", Config::YAML);
                            if (file_exists("/home/ClanWars/players/" . $args[1] . ".yml")) {

                                if ($sender->getName() === $clan->get("Owner")) {

                                    if ($clan->get("Member") >= 15) {

                                        $sender->sendMessage($this->prefix . Color::RED . "Der Clan ist Voll!");

                                    } else {

                                        $sf = new Config("/home/ClanWars/players/" . $args[1] . ".yml", Config::YAML);
                                        if ($sf->get("Clan") === "") {

                                            $v = $this->getServer()->getPlayerExact($args[1]);
                                            if (!$v == null) {

                                                $sf->set("ClanAnfrage", $pf->get("Clan"));
                                                $sf->save();
                                                $v->sendMessage($this->prefix . Color::GRAY . "Der Clan " . Color::YELLOW . $sf->get("ClanAnfrage") . Color::GRAY . " hat dir eine Clan einladung geschickt!");
                                                $v->sendMessage($this->prefix . Color::GRAY . "Um die Anfrage anzunehmen /clan accept");
                                                $sender->sendMessage($this->prefix . Color::GREEN . "Die Clan Einladung wurde erfolgreich verschickt!");

                                            } else {

                                                $sender->sendMessage($this->prefix . Color::RED . "Dieser Spieler ist nicht Online!");

                                            }

                                        } else {

                                            $sender->sendMessage($this->prefix . Color::RED . "Dieser Spieler ist derzeit schon in einem Clan!");

                                        }

                                    }

                                } else {

                                    $sender->sendMessage($this->prefix . Color::RED . "Du bist kein Leader von diesem Clan!");

                                }

                            } else {

                                $sender->sendMessage($this->prefix . Color::RED . "Diesen Spieler gibt es nicht!");

                            }

                        }

                    } else {

                        $sender->sendMessage($this->prefix . Color::RED . "/clan add <PlayerName>");

                    }

                } else if (strtolower($args[0]) === "help") {

                    $sender->sendMessage($this->prefix . Color::GRAY . "/clan make <ClanName>");
                    $sender->sendMessage($this->prefix . Color::GRAY . "/clan add <PlayerName>");
                    $sender->sendMessage($this->prefix . Color::GRAY . "/clan accept");
                    $sender->sendMessage($this->prefix . Color::GRAY . "/clan leave");
                    $sender->sendMessage($this->prefix . Color::GRAY . "/clan war <Player1-4>");
                    $sender->sendMessage($this->prefix . Color::GRAY . "/clan war list");

                } else if (strtolower($args[0]) === "war") {

                    if (isset($args[1])) {

                        if (strtolower($args[1]) === "list") {

                            $pf = new Config("/home/ClanWars/players/" . $sender->getName() . ".yml", Config::YAML);
                            if ($pf->get("Clan") === "") {

                                $sender->sendMessage($this->prefix . Color::RED . "Du bist in keinem Clan!");

                            } else {

                                $clan = new Config("/home/ClanWars/Clans/" . $pf->get("Clan") . ".yml", Config::YAML);
                                $sender->sendMessage($this->prefix . Color::AQUA . "1: " . Color::GRAY . $clan->get("ClanWarMember1"));
                                $sender->sendMessage($this->prefix . Color::AQUA . "2: " . Color::GRAY . $clan->get("ClanWarMember2"));
                                $sender->sendMessage($this->prefix . Color::AQUA . "3: " . Color::GRAY . $clan->get("ClanWarMember3"));
                                $sender->sendMessage($this->prefix . Color::AQUA . "4: " . Color::GRAY . $clan->get("ClanWarMember4"));

                            }

                        } else {

                            $pf = new Config("/home/ClanWars/players/" . $sender->getName() . ".yml", Config::YAML);
                            if ($pf->get("Clan") === "") {

                                $sender->sendMessage($this->prefix . Color::RED . "Du bist in keinem Clan!");

                            } else {

                                $clan = new Config("/home/ClanWars/Clans/" . $pf->get("Clan") . ".yml", Config::YAML);
                                if ($clan->get("Owner") === $sender->getName()) {

                                    if (file_exists("/home/ClanWars/players/" . $args[1] . ".yml")) {

                                        $sf = new Config("/home/ClanWars/players/" . $args[1] . ".yml", Config::YAML);
                                        if ($sf->get("Clan") === $pf->get("Clan")) {

                                            if ($clan->get("ClanWarMember") >= 4) {

                                                $sender->sendMessage($this->prefix . Color::RED . "Die ClanWar Member Liste ist voll!");

                                            } else {

                                                if ($sf->get("ClanWar") === false) {

                                                    if ($clan->get("ClanWarMember1") === "") {


                                                        $v = $this->getServer()->getPlayerExact($args[1]);
                                                        if (!$v == null) {

                                                            $clan->set("ClanWarMember1", $args[1]);
                                                            $clan->set("ClanWarMember", $clan->get("ClanWarMember") + 1);
                                                            $clan->save();

                                                            $sf->set("ClanWar", true);
                                                            $sf->save();

                                                            $sender->sendMessage($this->prefix . Color::GREEN . "Der Spieler wurde erfolgreich zur ClanWar Liste hinzugefuegt!");
                                                            $v->sendMessage($this->prefix . Color::GREEN . "Du wurdest erfolgreich zur ClanWar Liste hinzugefuegt!");

                                                        } else {

                                                            $sender->sendMessage($this->prefix . Color::RED . "Der Spieler ist derzeit Offline!");

                                                        }

                                                    } else if ($clan->get("ClanWarMember2") === "") {


                                                        $v = $this->getServer()->getPlayerExact($args[1]);
                                                        if (!$v == null) {

                                                            $clan->set("ClanWarMember2", $args[1]);
                                                            $clan->set("ClanWarMember", $clan->get("ClanWarMember") + 1);
                                                            $clan->save();

                                                            $sf->set("ClanWar", true);
                                                            $sf->save();

                                                            $sender->sendMessage($this->prefix . Color::GREEN . "Der Spieler wurde erfolgreich zur ClanWar Liste hinzugefuegt!");
                                                            $v->sendMessage($this->prefix . Color::GREEN . "Du wurdest erfolgreich zur ClanWar Liste hinzugefuegt!");

                                                        } else {

                                                            $sender->sendMessage($this->prefix . Color::RED . "Der Spieler ist derzeit Offline!");

                                                        }

                                                    } else if ($clan->get("ClanWarMember3") === "") {


                                                        $v = $this->getServer()->getPlayerExact($args[1]);
                                                        if (!$v == null) {

                                                            $clan->set("ClanWarMember3", $args[1]);
                                                            $clan->set("ClanWarMember", $clan->get("ClanWarMember") + 1);
                                                            $clan->save();

                                                            $sf->set("ClanWar", true);
                                                            $sf->save();

                                                            $sender->sendMessage($this->prefix . Color::GREEN . "Der Spieler wurde erfolgreich zur ClanWar Liste hinzugefuegt!");
                                                            $v->sendMessage($this->prefix . Color::GREEN . "Du wurdest erfolgreich zur ClanWar Liste hinzugefuegt!");

                                                        } else {

                                                            $sender->sendMessage($this->prefix . Color::RED . "Der Spieler ist derzeit Offline!");

                                                        }

                                                    } else if ($clan->get("ClanWarMember4") === "") {


                                                        $v = $this->getServer()->getPlayerExact($args[1]);
                                                        if (!$v == null) {

                                                            $clan->set("ClanWarMember4", $args[1]);
                                                            $clan->set("ClanWarMember", $clan->get("ClanWarMember") + 1);
                                                            $clan->save();

                                                            $sf->set("ClanWar", true);
                                                            $sf->save();

                                                            $sender->sendMessage($this->prefix . Color::GREEN . "Der Spieler wurde erfolgreich zur ClanWar Liste hinzugefuegt!");
                                                            $v->sendMessage($this->prefix . Color::GREEN . "Du wurdest erfolgreich zur ClanWar Liste hinzugefuegt!");

                                                        } else {

                                                            $sender->sendMessage($this->prefix . Color::RED . "Der Spieler ist derzeit Offline!");

                                                        }

                                                    } else {

                                                        $sender->sendMessage($this->prefix . Color::RED . "FEHLER CODE #0002");

                                                    }

                                                } else {

                                                    $sender->sendMessage($this->prefix . Color::RED . "Der Spieler befindet sich schon in der Liste!");

                                                }

                                            }

                                        } else {

                                            $sender->sendMessage($this->prefix . Color::RED . "Dieser Spieler ist nicht in deinem Clan!");

                                        }

                                    } else {

                                        $sender->sendMessage($this->prefix . Color::RED . "Diesen Spieler gibt es nicht!");

                                    }

                                } else {

                                    $sender->sendMessage($this->prefix . Color::RED . "Du brauchst in deinem Clan dafuer eine hoehere Position!");

                                }

                            }

                        }

                    }

                } else {

                    $sender->sendMessage($this->prefix . Color::GRAY . "/clan war <Player1-4>");
                    $sender->sendMessage($this->prefix . Color::GRAY . "/clan war list");

                }

            } else {

                $sender->sendMessage($this->prefix . Color::GRAY . "/clan make <ClanName>");
                $sender->sendMessage($this->prefix . Color::GRAY . "/clan add <PlayerName>");
                $sender->sendMessage($this->prefix . Color::GRAY . "/clan accept");
                $sender->sendMessage($this->prefix . Color::GRAY . "/clan leave");
                $sender->sendMessage($this->prefix . Color::GRAY . "/clan war <Player1-4>");
                $sender->sendMessage($this->prefix . Color::GRAY . "/clan war list");

            }

        }

        if ($command->getName() === "Group") {

            if ($sender->isOp()) {

                if (isset($args[0])) {

                    if (file_exists("/home/ClanWars/players/" . $args[0] . ".yml")) {

                        if (isset($args[1])) {

                            if (strtolower($args[1]) === "owner") {

                                $pf = new Config("/home/ClanWars/players/" . $args[0] . ".yml", Config::YAML);
                                $pf->set("Group", "Owner");
                                $pf->save();
                                $sender->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Die Gruppe von " . Color::DARK_PURPLE . $args[0] . Color::GREEN . " wurde erfolgreich getauscht!");
                                $v = $this->getServer()->getPlayerExact($args[0]);
                                if (!$v == null) {

                                    $this->setGroup($v);
                                    $v->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Deine Gruppe wurde getauscht!");

                                }

                            } else if (strtolower($args[1]) === "admin") {

                                $pf = new Config("/home/ClanWars/players/" . $args[0] . ".yml", Config::YAML);
                                $pf->set("Group", "Admin");
                                $pf->save();
                                $sender->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Die Gruppe von " . Color::DARK_PURPLE . $args[0] . Color::GREEN . " wurde erfolgreich getauscht!");
                                $v = $this->getServer()->getPlayerExact($args[0]);
                                if (!$v == null) {

                                    $this->setGroup($v);
                                    $v->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Deine Gruppe wurde getauscht!");

                                }

                            } else if (strtolower($args[1]) === "builder") {

                                $pf = new Config("/home/ClanWars/players/" . $args[0] . ".yml", Config::YAML);
                                $pf->set("Group", "Builder");
                                $pf->save();
                                $sender->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Die Gruppe von " . Color::DARK_PURPLE . $args[0] . Color::GREEN . " wurde erfolgreich getauscht!");
                                $v = $this->getServer()->getPlayerExact($args[0]);
                                if (!$v == null) {

                                    $this->setGroup($v);
                                    $v->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Deine Gruppe wurde getauscht!");

                                }

                            } else if (strtolower($args[1]) === "moderator") {

                                $pf = new Config("/home/ClanWars/players/" . $args[0] . ".yml", Config::YAML);
                                $pf->set("Group", "Moderator");
                                $pf->save();
                                $sender->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Die Gruppe von " . Color::DARK_PURPLE . $args[0] . Color::GREEN . " wurde erfolgreich getauscht!");
                                $v = $this->getServer()->getPlayerExact($args[0]);
                                if (!$v == null) {

                                    $this->setGroup($v);
                                    $v->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Deine Gruppe wurde getauscht!");

                                }

                            } else if (strtolower($args[1]) === "supporter") {

                                $pf = new Config("/home/ClanWars/players/" . $args[0] . ".yml", Config::YAML);
                                $pf->set("Group", "Supporter");
                                $pf->save();
                                $sender->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Die Gruppe von " . Color::DARK_PURPLE . $args[0] . Color::GREEN . " wurde erfolgreich getauscht!");
                                $v = $this->getServer()->getPlayerExact($args[0]);
                                if (!$v == null) {

                                    $this->setGroup($v);
                                    $v->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Deine Gruppe wurde getauscht!");

                                }

                            } else if (strtolower($args[1]) === "youtuber") {

                                $pf = new Config("/home/ClanWars/players/" . $args[0] . ".yml", Config::YAML);
                                $pf->set("Group", "YouTuber");
                                $pf->save();
                                $sender->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Die Gruppe von " . Color::DARK_PURPLE . $args[0] . Color::GREEN . " wurde erfolgreich getauscht!");
                                $v = $this->getServer()->getPlayerExact($args[0]);
                                if (!$v == null) {

                                    $this->setGroup($v);
                                    $v->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Deine Gruppe wurde getauscht!");

                                }

                            } else if (strtolower($args[1]) === "vip+") {

                                $pf = new Config("/home/ClanWars/players/" . $args[0] . ".yml", Config::YAML);
                                $pf->set("Group", "VIP+");
                                $pf->save();
                                $sender->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Die Gruppe von " . Color::DARK_PURPLE . $args[0] . Color::GREEN . " wurde erfolgreich getauscht!");
                                $v = $this->getServer()->getPlayerExact($args[0]);
                                if (!$v == null) {

                                    $this->setGroup($v);
                                    $v->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Deine Gruppe wurde getauscht!");

                                }

                            } else if (strtolower($args[1]) === "vip") {

                                $pf = new Config("/home/ClanWars/players/" . $args[0] . ".yml", Config::YAML);
                                $pf->set("Group", "VIP");
                                $pf->save();
                                $sender->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Die Gruppe von " . Color::DARK_PURPLE . $args[0] . Color::GREEN . " wurde erfolgreich getauscht!");
                                $v = $this->getServer()->getPlayerExact($args[0]);
                                if (!$v == null) {

                                    $this->setGroup($v);
                                    $v->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Deine Gruppe wurde getauscht!");

                                }

                            } else if (strtolower($args[1]) === "default") {

                                $pf = new Config("/home/ClanWars/players/" . $args[0] . ".yml", Config::YAML);
                                $pf->set("Group", "Default");
                                $pf->save();
                                $sender->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Die Gruppe von " . Color::DARK_PURPLE . $args[0] . Color::GREEN . " wurde erfolgreich getauscht!");
                                $v = $this->getServer()->getPlayerExact($args[0]);
                                if (!$v == null) {

                                    $this->setGroup($v);
                                    $v->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GREEN . "Deine Gruppe wurde getauscht!");

                                }

                            }

                        }

                    } else {

                        $sender->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::RED . "Diesen Spieler gibt es nicht!");

                    }

                } else {

                    $sender->sendMessage(Color::DARK_PURPLE . "EnderCube" . Color::DARK_GRAY . " : " . Color::GRAY . "/group <PlayerName> <owner, admin, builder, moderator, supporter, youtuber, vip+, vip, default>");

                }

            }

        }


        return true;

    }

    //Group
    public function onChat(PlayerChatEvent $event) {

        $player = $event->getPlayer();
        $msg = $event->getMessage();
        $event->setFormat($player->getDisplayName() . " > " . $msg);

    }

    public function setGroup(Player $player)
    {

        $pf = new Config("/home/ClanWars/players/" . $player->getName() . ".yml", Config::YAML);
        if ($pf->get("Group") === "Default") {

            if ($pf->get("Clan") === "") {

                $player->setDisplayName(Color::DARK_GRAY . "Spieler" . Color::WHITE . " : " . Color::GRAY . $player->getName());
                $player->setNameTag(Color::DARK_GRAY . "Spieler" . Color::WHITE . " : " . Color::GRAY . $player->getName());

            } else {

                $player->setDisplayName(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::DARK_GRAY . "Spieler" . Color::WHITE . " : " . Color::GRAY . $player->getName());
                $player->setNameTag(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::DARK_GRAY . "Spieler" . Color::WHITE . " : " . Color::GRAY . $player->getName());

            }

        } else if ($pf->get("Group") === "Owner") {

            if ($pf->get("Clan") === "") {

                $player->setDisplayName(Color::DARK_RED . "Owner" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::DARK_RED . "Owner" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            } else {

                $player->setDisplayName(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::DARK_RED . "Owner" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::DARK_RED . "Owner" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            }

        } else if ($pf->get("Group") === "Admin") {

            if ($pf->get("Clan") === "") {

                $player->setDisplayName(Color::RED . "Admin" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::RED . "Admin" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            } else {

                $player->setDisplayName(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::RED . "Admin" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::RED . "Admin" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            }

        } else if ($pf->get("Group") === "Builder") {

            if ($pf->get("Clan") === "") {

                $player->setDisplayName(Color::GREEN . "Builder" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::GREEN . "Builder" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            } else {

                $player->setDisplayName(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::GREEN . "Builder" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::GREEN . "Builder" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            }

        } else if ($pf->get("Group") === "Moderator") {

            if ($pf->get("Clan") === "") {

                $player->setDisplayName(Color::RED . "Moderator" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::RED . "Moderator" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            } else {

                $player->setDisplayName(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::RED . "Moderator" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::RED . "Moderator" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            }

        } else if ($pf->get("Group") === "Supporter") {

            if ($pf->get("Clan") === "") {

                $player->setDisplayName(Color::BLUE . "Supporter" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::BLUE . "Supporter" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            } else {

                $player->setDisplayName(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::BLUE . "Supporter" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::BLUE . "Supporter" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            }

        } else if ($pf->get("Group") === "YouTuber") {

            if ($pf->get("Clan") === "") {

                $player->setDisplayName(Color::DARK_PURPLE . "YouTuber" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::DARK_PURPLE . "YouTuber" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            } else {

                $player->setDisplayName(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::DARK_PURPLE . "YouTuber" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::DARK_PURPLE . "YouTuber" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            }

        } else if ($pf->get("Group") === "VIP+") {

            if ($pf->get("Clan") === "") {

                $player->setDisplayName(Color::GOLD . "VIP+" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::GOLD . "VIP+" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            } else {

                $player->setDisplayName(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::GOLD . "VIP+" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::GOLD . "VIP+" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            }

        } else if ($pf->get("Group") === "VIP") {

            if ($pf->get("Clan") === "") {

                $player->setDisplayName(Color::GOLD . "VIP" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::GOLD . "VIP" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            } else {

                $player->setDisplayName(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::GOLD . "VIP" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);
                $player->setNameTag(Color::GRAY . "[" . Color::YELLOW . $pf->get("Clan") . Color::GRAY . "] " . Color::GOLD . "VIP" . Color::WHITE . " : " . Color::GRAY . $player->getName() . Color::WHITE);

            }

        }

    }
	
}