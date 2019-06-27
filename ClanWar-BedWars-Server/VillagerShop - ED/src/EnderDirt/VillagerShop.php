<?php

namespace EnderDirt;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;

//Base
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\PluginTask;
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
//ItemUndBlock
use pocketmine\block\Block;
use pocketmine\item\Item;
//BlockEvents
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
//EntityEvents
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
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

class VillagerShop extends PluginBase implements Listener {
	
	public $prefix = Color::WHITE . "[" . Color::YELLOW . "VillagerShop" . Color::WHITE . "] ";
	
	private static $instance;
	
	public function onEnable() {
		
		self::$instance = $this;
		
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info($this->prefix . Color::GREEN . "wurde aktiviert!");
        $this->getLogger()->info($this->prefix . Color::AQUA . "Made By" . Color::GREEN . " EnderDirt!");
		
    }
    
    public static function getInstance() : Main {
    	
		return self::$instance;
		
	}
	
	public function onHit(EntityDamageEvent $event) {
		
        $player = $event->getEntity();

        if (!$player instanceof Player) {
        	
            if ($event instanceof EntityDamageByEntityEvent) {
            	
                $damager = $event->getDamager();
                if ($damager instanceof Player) {
                	
                    $event->setCancelled();
                    if(!InvMenuHandler::isRegistered()){
        	
                      InvMenuHandler::register($this);
               
                    }
            
                	$menu = InvMenu::create(InvMenu::TYPE_CHEST);
                    $menu->readOnly();
                    $blocke = Item::get(24, 0, 1);
                    $waffen = Item::get(280, 0, 1);
                    $pickaxe = Item::get(285, 0, 1);
                    $ruestung = Item::get(311, 0, 1);
                    $food = Item::get(260, 0, 1);
                    $bow = Item::get(261, 0, 1);
                    $chest = Item::get(54, 0, 1);
                    $special = Item::get(384, 0, 1);
                    $blocke->setCustomName(Color::YELLOW . "Bloecke");
                    $waffen->setCustomName(Color::RED . "Waffen");
                    $pickaxe->setCustomName(Color::AQUA . "SpitzHacken");
                    $ruestung->setCustomName(Color::GRAY . "Ruestung");
                    $food->setCustomName(Color::GOLD . "Essen");
                    $bow->setCustomName(Color::DARK_RED . "Bogen");
                    $chest->setCustomName(Color::GOLD . "Chest");
                    $special->setCustomName(Color::LIGHT_PURPLE . "Special Items");
                    $menu->getInventory()->setItem(0, $blocke);
                    $menu->getInventory()->setItem(3, $waffen);
                    $menu->getInventory()->setItem(2, $pickaxe);
                    $menu->getInventory()->setItem(1, $ruestung);
                    $menu->getInventory()->setItem(5, $food);
                    $menu->getInventory()->setItem(4, $bow);
                    $menu->getInventory()->setItem(6, $chest);
                    $menu->getInventory()->setItem(7, $special);
                    $menu->send($damager);
                    $menu->setListener([new VillagerShopListener($this), "onTransaction"]);
                    
                } else {

                    $event->setCancelled(true);

                }
                
            }
            
        }
        
    }
    
    public function setBlockShop(Player $player) {
    	
    	$menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->readOnly();
        $blocke = Item::get(24, 0, 1);
        $waffen = Item::get(280, 0, 1);
        $pickaxe = Item::get(285, 0, 1);
        $ruestung = Item::get(311, 0, 1);
        $food = Item::get(260, 0, 1);
        $bow = Item::get(261, 0, 1);
        $chest = Item::get(54, 0, 1);
        $special = Item::get(384, 0, 1);
        $blocke->setCustomName(Color::YELLOW . "Bloecke");
        $waffen->setCustomName(Color::RED . "Waffen");
        $pickaxe->setCustomName(Color::AQUA . "SpitzHacken");
        $ruestung->setCustomName(Color::GRAY . "Ruestung");
        $food->setCustomName(Color::GOLD . "Essen");
        $bow->setCustomName(Color::DARK_RED . "Bogen");
        $chest->setCustomName(Color::GOLD . "Chest");
        $special->setCustomName(Color::LIGHT_PURPLE . "Special Items");
        $menu->getInventory()->setItem(0, $blocke);
        $menu->getInventory()->setItem(3, $waffen);
        $menu->getInventory()->setItem(2, $pickaxe);
        $menu->getInventory()->setItem(1, $ruestung);
        $menu->getInventory()->setItem(5, $food);
        $menu->getInventory()->setItem(4, $bow);
        $menu->getInventory()->setItem(6, $chest);
        $menu->getInventory()->setItem(7, $special);
        $sand1 = Item::get(24, 0, 4);
        $sand2 = Item::get(24, 0, 32);
        $sand3 = Item::get(24, 0, 64);
        $sand4 = Item::get(24, 0, 1);
        $end1 = Item::get(121, 0, 1);
        $glas1 = Item::get(20, 0, 2);
        $web1 = Item::get(30, 0, 1);
        $web2 = Item::get(65, 0, 1);
        $sand1->setCustomName(Color::GOLD . "SandStone");
        $sand2->setCustomName(Color::AQUA . "SandStone");
        $sand3->setCustomName(Color::GRAY . "SandStone");
        $sand4->setCustomName(Color::GRAY . "SandStone All");
        $end1->setCustomName(Color::AQUA . "EndStone");
        $glas1->setCustomName(Color::GRAY . "Glas");
        $web1->setCustomName(Color::GRAY . "CobWeb");
        $web2->setCustomName(Color::GRAY . "Leiter");
        $menu->getInventory()->setItem(9, $sand1);
        //$menu->getInventory()->setItem(10, $sand2);
        //$menu->getInventory()->setItem(11, $sand3);
        $menu->getInventory()->setItem(10, $sand4);
        $menu->getInventory()->setItem(11, $end1);
        $menu->getInventory()->setItem(12, $glas1);
        $menu->getInventory()->setItem(13, $web1);
        $menu->getInventory()->setItem(14, $web2);
        $b1 = Item::get(336, 0, 1);
        $b4 = Item::get(336, 0, 8);
        $b5 = Item::get(336, 0, 14);
        $b8 = Item::get(336, 0, 1);
        $b6 = Item::get(336, 0, 16);
        $b2 = Item::get(336, 0, 6);
        $b3 = Item::get(336, 0, 11);
        $b7 = Item::get(336, 0, 2);
        $menu->getInventory()->setItem(18, $b1);
     //   $menu->getInventory()->setItem(19, $b4);
    //    $menu->getInventory()->setItem(20, $b5);
        $menu->getInventory()->setItem(19, $b8);
        $menu->getInventory()->setItem(20, $b2);
        $menu->getInventory()->setItem(21, $b3);
        $menu->getInventory()->setItem(22, $b6);
        $menu->getInventory()->setItem(23, $b7);
        $menu->send($player);
        $menu->setListener([new VillagerShopListener($this), "onTransaction"]);
    	
    }
    
    public function setWaffenShop(Player $player) {
    	
    	$menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->readOnly();
        $blocke = Item::get(24, 0, 1);
        $waffen = Item::get(280, 0, 1);
        $pickaxe = Item::get(285, 0, 1);
        $ruestung = Item::get(311, 0, 1);
        $food = Item::get(260, 0, 1);
        $bow = Item::get(261, 0, 1);
        $chest = Item::get(54, 0, 1);
        $special = Item::get(384, 0, 1);
        $blocke->setCustomName(Color::YELLOW . "Bloecke");
        $waffen->setCustomName(Color::RED . "Waffen");
        $pickaxe->setCustomName(Color::AQUA . "SpitzHacken");
        $ruestung->setCustomName(Color::GRAY . "Ruestung");
        $food->setCustomName(Color::GOLD . "Essen");
        $bow->setCustomName(Color::DARK_RED . "Bogen");
        $chest->setCustomName(Color::GOLD . "Chest");
        $special->setCustomName(Color::LIGHT_PURPLE . "Special Items");
        $menu->getInventory()->setItem(0, $blocke);
        $menu->getInventory()->setItem(3, $waffen);
        $menu->getInventory()->setItem(2, $pickaxe);
        $menu->getInventory()->setItem(1, $ruestung);
        $menu->getInventory()->setItem(5, $food);
        $menu->getInventory()->setItem(4, $bow);
        $menu->getInventory()->setItem(6, $chest);
        $menu->getInventory()->setItem(7, $special);
        $stick = Item::get(280, 0, 1);
        $gsword = Item::get(283, 0, 1);
        $gsword2 = Item::get(283, 0, 1);
        $gsword3 = Item::get(283, 0, 1);
        $esword = Item::get(267, 0, 1);
        $stick->setCustomName(Color::AQUA . "KnockBackStick");
        $gsword->setCustomName(Color::GOLD . "Gold Schwert Level 1");
        $gsword2->setCustomName(Color::GOLD . "Gold Schwert Level 2");
        $gsword3->setCustomName(Color::GOLD . "Gold Schwert Level 3");
        $esword->setCustomName(Color::WHITE . "Eisen Schwert");
        $menu->getInventory()->setItem(9, $stick);
        $menu->getInventory()->setItem(10, $gsword);
        $menu->getInventory()->setItem(11, $gsword2);
        $menu->getInventory()->setItem(12, $gsword3);
        $menu->getInventory()->setItem(13, $esword);
        $b1 = Item::get(336, 0, 8);
        $b2 = Item::get(265, 0, 1);
        $b3 = Item::get(266, 0, 5);
        $b4 = Item::get(265, 0, 3);
        $b5 = Item::get(265, 0, 7);
        $menu->getInventory()->setItem(18, $b1);
        $menu->getInventory()->setItem(19, $b2);
        $menu->getInventory()->setItem(20, $b4);
        $menu->getInventory()->setItem(21, $b5);
        $menu->getInventory()->setItem(22, $b3);
        $menu->send($player);
        $menu->setListener([new VillagerShopListener($this), "onTransaction"]);
    	
    }
    
    public function setAxeShop(Player $player) {
    	
    	$menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->readOnly();
        $blocke = Item::get(24, 0, 1);
        $waffen = Item::get(280, 0, 1);
        $pickaxe = Item::get(285, 0, 1);
        $ruestung = Item::get(311, 0, 1);
        $food = Item::get(260, 0, 1);
        $bow = Item::get(261, 0, 1);
        $chest = Item::get(54, 0, 1);
        $special = Item::get(384, 0, 1);
        $blocke->setCustomName(Color::YELLOW . "Bloecke");
        $waffen->setCustomName(Color::RED . "Waffen");
        $pickaxe->setCustomName(Color::AQUA . "SpitzHacken");
        $ruestung->setCustomName(Color::GRAY . "Ruestung");
        $food->setCustomName(Color::GOLD . "Essen");
        $bow->setCustomName(Color::DARK_RED . "Bogen");
        $chest->setCustomName(Color::GOLD . "Chest");
        $special->setCustomName(Color::LIGHT_PURPLE . "Special Items");
        $menu->getInventory()->setItem(0, $blocke);
        $menu->getInventory()->setItem(3, $waffen);
        $menu->getInventory()->setItem(2, $pickaxe);
        $menu->getInventory()->setItem(1, $ruestung);
        $menu->getInventory()->setItem(5, $food);
        $menu->getInventory()->setItem(4, $bow);
        $menu->getInventory()->setItem(6, $chest);
        $menu->getInventory()->setItem(7, $special);
        $waxe = Item::get(270, 0, 1);
        $saxe = Item::get(274, 0, 1);
        $eaxe = Item::get(257, 0, 1);
        $waxe->setCustomName(Color::GRAY . "Holz SpitzHacke");
        $saxe->setCustomName(Color::DARK_GRAY . "Stein SpitzHacke");
        $eaxe->setCustomName(Color::WHITE . "Eisen SpitzHacke");
        $menu->getInventory()->setItem(9, $waxe);
        $menu->getInventory()->setItem(10, $saxe);
        $menu->getInventory()->setItem(11, $eaxe);
        $b1 = Item::get(336, 0, 4);
        $b2 = Item::get(265, 0, 2);
        $b3 = Item::get(266, 0, 1);
        $menu->getInventory()->setItem(18, $b1);
        $menu->getInventory()->setItem(19, $b2);
        $menu->getInventory()->setItem(20, $b3);
        $menu->send($player);
        $menu->setListener([new VillagerShopListener($this), "onTransaction"]);
    	
    }
    
    public function setFoodShop(Player $player) {
    	
    	$menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->readOnly();
        $blocke = Item::get(24, 0, 1);
        $waffen = Item::get(280, 0, 1);
        $pickaxe = Item::get(285, 0, 1);
        $ruestung = Item::get(311, 0, 1);
        $food = Item::get(260, 0, 1);
        $bow = Item::get(261, 0, 1);
        $chest = Item::get(54, 0, 1);
        $special = Item::get(384, 0, 1);
        $blocke->setCustomName(Color::YELLOW . "Bloecke");
        $waffen->setCustomName(Color::RED . "Waffen");
        $pickaxe->setCustomName(Color::AQUA . "SpitzHacken");
        $ruestung->setCustomName(Color::GRAY . "Ruestung");
        $food->setCustomName(Color::GOLD . "Essen");
        $bow->setCustomName(Color::DARK_RED . "Bogen");
        $chest->setCustomName(Color::GOLD . "Chest");
        $special->setCustomName(Color::LIGHT_PURPLE . "Special Items");
        $menu->getInventory()->setItem(0, $blocke);
        $menu->getInventory()->setItem(3, $waffen);
        $menu->getInventory()->setItem(2, $pickaxe);
        $menu->getInventory()->setItem(1, $ruestung);
        $menu->getInventory()->setItem(5, $food);
        $menu->getInventory()->setItem(4, $bow);
        $menu->getInventory()->setItem(6, $chest);
        $menu->getInventory()->setItem(7, $special);
        $steak = Item::get(364, 0, 2);
        $apple = Item::get(260, 0, 4);
        $gapple = Item::get(322, 0, 1);
        $steak->setCustomName(Color::GOLD . "Steak");
        $apple->setCustomName(Color::RED . "Apfel");
        $gapple->setCustomName(Color::GOLD . "Gold Apfel");
        $menu->getInventory()->setItem(9, $steak);
        $menu->getInventory()->setItem(10, $apple);
        $menu->getInventory()->setItem(11, $gapple);
        $b1 = Item::get(336, 0, 4);
        $b2 = Item::get(336, 0, 2);
        $b3 = Item::get(266, 0, 1);
        $menu->getInventory()->setItem(18, $b1);
        $menu->getInventory()->setItem(19, $b2);
        $menu->getInventory()->setItem(20, $b3);
        $menu->send($player);
        $menu->setListener([new VillagerShopListener($this), "onTransaction"]);
    	
    }
    
    public function setBowShop(Player $player) {
    	
    	$menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->readOnly();
        $blocke = Item::get(24, 0, 1);
        $waffen = Item::get(280, 0, 1);
        $pickaxe = Item::get(285, 0, 1);
        $ruestung = Item::get(311, 0, 1);
        $food = Item::get(260, 0, 1);
        $bow = Item::get(261, 0, 1);
        $chest = Item::get(54, 0, 1);
        $special = Item::get(384, 0, 1);
        $blocke->setCustomName(Color::YELLOW . "Bloecke");
        $waffen->setCustomName(Color::RED . "Waffen");
        $pickaxe->setCustomName(Color::AQUA . "SpitzHacken");
        $ruestung->setCustomName(Color::GRAY . "Ruestung");
        $food->setCustomName(Color::GOLD . "Essen");
        $bow->setCustomName(Color::DARK_RED . "Bogen");
        $chest->setCustomName(Color::GOLD . "Chest");
        $special->setCustomName(Color::LIGHT_PURPLE . "Special Items");
        $menu->getInventory()->setItem(0, $blocke);
        $menu->getInventory()->setItem(3, $waffen);
        $menu->getInventory()->setItem(2, $pickaxe);
        $menu->getInventory()->setItem(1, $ruestung);
        $menu->getInventory()->setItem(5, $food);
        $menu->getInventory()->setItem(4, $bow);
        $menu->getInventory()->setItem(6, $chest);
        $menu->getInventory()->setItem(7, $special);
        $bow = Item::get(261, 0, 1);
        $pfeile = Item::get(262, 0, 1);
        $bow->setCustomName(Color::AQUA . "Bogen");
        $pfeile->setCustomName(Color::AQUA . "Pfeile");
        $menu->getInventory()->setItem(9, $bow);
        $menu->getInventory()->setItem(10, $pfeile);
        $b1 = Item::get(266, 0, 1);
        $b2 = Item::get(266, 0, 3);
        $menu->getInventory()->setItem(18, $b2);
        $menu->getInventory()->setItem(19, $b1);
        $menu->send($player);
        $menu->setListener([new VillagerShopListener($this), "onTransaction"]);
    	
    }
    
    public function setRusShop(Player $player) {
    	
    	$menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->readOnly();
        $blocke = Item::get(24, 0, 1);
        $waffen = Item::get(280, 0, 1);
        $pickaxe = Item::get(285, 0, 1);
        $ruestung = Item::get(311, 0, 1);
        $food = Item::get(260, 0, 1);
        $bow = Item::get(261, 0, 1);
        $chest = Item::get(54, 0, 1);
        $special = Item::get(384, 0, 1);
        $blocke->setCustomName(Color::YELLOW . "Bloecke");
        $waffen->setCustomName(Color::RED . "Waffen");
        $pickaxe->setCustomName(Color::AQUA . "SpitzHacken");
        $ruestung->setCustomName(Color::GRAY . "Ruestung");
        $food->setCustomName(Color::GOLD . "Essen");
        $bow->setCustomName(Color::DARK_RED . "Bogen");
        $chest->setCustomName(Color::GOLD . "Chest");
        $special->setCustomName(Color::LIGHT_PURPLE . "Special Items");
        $menu->getInventory()->setItem(0, $blocke);
        $menu->getInventory()->setItem(3, $waffen);
        $menu->getInventory()->setItem(2, $pickaxe);
        $menu->getInventory()->setItem(1, $ruestung);
        $menu->getInventory()->setItem(5, $food);
        $menu->getInventory()->setItem(4, $bow);
        $menu->getInventory()->setItem(6, $chest);
        $menu->getInventory()->setItem(7, $special);
        $helm = Item::get(298, 0, 1);
        $chestplate = Item::get(303, 0, 1);
        $chestplate2 = Item::get(303, 0, 1);
        $chestplate3 = Item::get(303, 0, 1);
        $hose = Item::get(300, 0, 1);
        $boots = Item::get(301, 0, 1);
        $helm->setCustomName(Color::GOLD . "Helm");
        $chestplate->setCustomName(Color::GOLD . "Chestplate Level 1");
        $chestplate2->setCustomName(Color::GOLD . "Chestplate Level 2");
        $chestplate3->setCustomName(Color::GOLD . "Chestplate Level 3");
        $hose->setCustomName(Color::GOLD . "Hose");
        $boots->setCustomName(Color::GOLD . "Schuhe");
        $menu->getInventory()->setItem(9, $helm);
        $menu->getInventory()->setItem(10, $hose);
        $menu->getInventory()->setItem(11, $boots);
        $menu->getInventory()->setItem(13, $chestplate);
        $menu->getInventory()->setItem(14, $chestplate2);
        $menu->getInventory()->setItem(15, $chestplate3);
        $b1 = Item::get(336, 0, 1);
        $b2 = Item::get(265, 0, 1);
        $b3 = Item::get(265, 0, 3);
        $b4 = Item::get(265, 0, 7);
        $menu->getInventory()->setItem(18, $b1);
        $menu->getInventory()->setItem(19, $b1);
        $menu->getInventory()->setItem(20, $b1);
        $menu->getInventory()->setItem(22, $b2);
        $menu->getInventory()->setItem(23, $b3);
        $menu->getInventory()->setItem(24, $b4);
        $menu->send($player);
        $menu->setListener([new VillagerShopListener($this), "onTransaction"]);
    	
    }
    
    public function setChestShop(Player $player) {
    	
    	$menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->readOnly();
        $blocke = Item::get(24, 0, 1);
        $waffen = Item::get(280, 0, 1);
        $pickaxe = Item::get(285, 0, 1);
        $ruestung = Item::get(311, 0, 1);
        $food = Item::get(260, 0, 1);
        $bow = Item::get(261, 0, 1);
        $chest = Item::get(54, 0, 1);
        $special = Item::get(384, 0, 1);
        $blocke->setCustomName(Color::YELLOW . "Bloecke");
        $waffen->setCustomName(Color::RED . "Waffen");
        $pickaxe->setCustomName(Color::AQUA . "SpitzHacken");
        $ruestung->setCustomName(Color::GRAY . "Ruestung");
        $food->setCustomName(Color::GOLD . "Essen");
        $bow->setCustomName(Color::DARK_RED . "Bogen");
        $chest->setCustomName(Color::GOLD . "Chest");
        $special->setCustomName(Color::LIGHT_PURPLE . "Special Items");
        $menu->getInventory()->setItem(0, $blocke);
        $menu->getInventory()->setItem(3, $waffen);
        $menu->getInventory()->setItem(2, $pickaxe);
        $menu->getInventory()->setItem(1, $ruestung);
        $menu->getInventory()->setItem(5, $food);
        $menu->getInventory()->setItem(4, $bow);
        $menu->getInventory()->setItem(6, $chest);
        $menu->getInventory()->setItem(7, $special);
        $nchest = Item::get(54, 0, 1);
        $echest = Item::get(130, 0, 1);
        $nchest->setCustomName(Color::YELLOW . "Chest");
        $echest->setCustomName(Color::DARK_PURPLE . "Ender Chest");
        $menu->getInventory()->setItem(9, $nchest);
        $b1 = Item::get(265, 0, 1);
        $b2 = Item::get(266, 0, 1);
        $menu->getInventory()->setItem(18, $b1);
        $menu->send($player);
        $menu->setListener([new VillagerShopListener($this), "onTransaction"]);
    	
    }
    
    public function setSpecialShop(Player $player) {
    	
    	$menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->readOnly();
        $blocke = Item::get(24, 0, 1);
        $waffen = Item::get(280, 0, 1);
        $pickaxe = Item::get(285, 0, 1);
        $ruestung = Item::get(311, 0, 1);
        $food = Item::get(260, 0, 1);
        $bow = Item::get(261, 0, 1);
        $chest = Item::get(54, 0, 1);
        $special = Item::get(384, 0, 1);
        $blocke->setCustomName(Color::YELLOW . "Bloecke");
        $waffen->setCustomName(Color::RED . "Waffen");
        $pickaxe->setCustomName(Color::AQUA . "SpitzHacken");
        $ruestung->setCustomName(Color::GRAY . "Ruestung");
        $food->setCustomName(Color::GOLD . "Essen");
        $bow->setCustomName(Color::DARK_RED . "Bogen");
        $chest->setCustomName(Color::GOLD . "Chest");
        $special->setCustomName(Color::LIGHT_PURPLE . "Special Items");
        $menu->getInventory()->setItem(0, $blocke);
        $menu->getInventory()->setItem(3, $waffen);
        $menu->getInventory()->setItem(2, $pickaxe);
        $menu->getInventory()->setItem(1, $ruestung);
        $menu->getInventory()->setItem(5, $food);
        $menu->getInventory()->setItem(4, $bow);
        $menu->getInventory()->setItem(6, $chest);
        $menu->getInventory()->setItem(7, $special);
        $speed = Item::get(441, 15, 1);
        $jump = Item::get(441, 10, 1);
        $reg = Item::get(441, 28, 1);
        $health = Item::get(441, 21, 1);
        $strength = Item::get(441, 31, 1);
        $pearl = Item::get(368, 0, 1);
        $tnt = Item::get(46, 0, 1);
        $flint = Item::get(259, 0, 1);
        $speed->setCustomName(Color::GREEN . "Speed Potion");
        $jump->setCustomName(Color::YELLOW . "Jump Potion");
        $reg->setCustomName(Color::DARK_PURPLE . "Regeneration Potion");
        $health->setCustomName(Color::LIGHT_PURPLE . "Health Potion");
        $strength->setCustomName(Color::LIGHT_PURPLE . "Staerke Potion");
        $pearl->setCustomName(Color::DARK_PURPLE . "EnderPearl");
        $tnt->setCustomName(Color::RED . "TNT");
        $flint->setCustomName(Color::GRAY . "Flint");
    /*    $menu->getInventory()->setItem(9, $speed);
        $menu->getInventory()->setItem(10, $jump);
        $menu->getInventory()->setItem(11, $reg);
        $menu->getInventory()->setItem(12, $health);
        $menu->getInventory()->setItem(13, $strength);*/
        $menu->getInventory()->setItem(9, $pearl);
        $menu->getInventory()->setItem(10, $tnt);
        $menu->getInventory()->setItem(11, $flint);
        $b1 = Item::get(265, 0, 2);
        $b2 = Item::get(265, 0, 2);
        $b3 = Item::get(265, 0, 5);
        $b4 = Item::get(266, 0, 2);
        $b5 = Item::get(266, 0, 5);
        $b6 = Item::get(266, 0, 13);
        $b7 = Item::get(266, 0, 1);
        $b8 = Item::get(265, 0, 2);
        /*$menu->getInventory()->setItem(18, $b1);
        $menu->getInventory()->setItem(19, $b2);
        $menu->getInventory()->setItem(20, $b3);
        $menu->getInventory()->setItem(21, $b4);
        $menu->getInventory()->setItem(22, $b5);*/
        $menu->getInventory()->setItem(18, $b6);
        $menu->getInventory()->setItem(19, $b7);
        $menu->getInventory()->setItem(20, $b8);
        $menu->send($player);
        $menu->setListener([new VillagerShopListener($this), "onTransaction"]);
    	
    }
    
    public function checkBronze(Player $player) {
    	
    	$pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
    	if ($player->getInventory()->getItem(0)->getId() === 336) {
    	
    	    $pf->set("Slot", 0);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(1)->getId() === 336) {
    	
    	    $pf->set("Slot", 1);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(2)->getId() === 336) {
    	
    	    $pf->set("Slot", 2);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(3)->getId() === 336) {
    	
    	    $pf->set("Slot", 3);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(4)->getId() === 336) {
    	
    	    $pf->set("Slot", 4);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(5)->getId() === 336) {
    	
    	    $pf->set("Slot", 5);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(6)->getId() === 336) {
    	
    	    $pf->set("Slot", 6);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(7)->getId() === 336) {
    	
    	    $pf->set("Slot", 7);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(8)->getId() === 336) {
    	
    	    $pf->set("Slot", 8);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(9)->getId() === 336) {
    	
    	    $pf->set("Slot", 9);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(10)->getId() === 336) {
    	
    	    $pf->set("Slot", 10);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(11)->getId() === 336) {
    	
    	    $pf->set("Slot", 11);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(12)->getId() === 336) {
    	
    	    $pf->set("Slot", 12);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(13)->getId() === 336) {
    	
    	    $pf->set("Slot", 13);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(14)->getId() === 336) {
    	
    	    $pf->set("Slot", 14);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(15)->getId() === 336) {
    	
    	    $pf->set("Slot", 15);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(16)->getId() === 336) {
    	
    	    $pf->set("Slot", 16);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(17)->getId() === 336) {
    	
    	    $pf->set("Slot", 17);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(18)->getId() === 336) {
    	
    	    $pf->set("Slot", 18);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(19)->getId() === 336) {
    	
    	    $pf->set("Slot", 19);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(20)->getId() === 336) {
    	
    	    $pf->set("Slot", 20);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(21)->getId() === 336) {
    	
    	    $pf->set("Slot", 21);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(22)->getId() === 336) {
    	
    	    $pf->set("Slot", 22);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(23)->getId() === 336) {
    	
    	    $pf->set("Slot", 23);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(24)->getId() === 336) {
    	
    	    $pf->set("Slot", 24);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(25)->getId() === 336) {
    	
    	    $pf->set("Slot", 25);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(26)->getId() === 336) {
    	
    	    $pf->set("Slot", 26);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(27)->getId() === 336) {
    	
    	    $pf->set("Slot", 27);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(28)->getId() === 336) {
    	
    	    $pf->set("Slot", 28);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(29)->getId() === 336) {
    	
    	    $pf->set("Slot", 29);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(30)->getId() === 336) {
    	
    	    $pf->set("Slot", 30);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(31)->getId() === 336) {
    	
    	    $pf->set("Slot", 31);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(32)->getId() === 336) {
    	
    	    $pf->set("Slot", 32);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(33)->getId() === 336) {
    	
    	    $pf->set("Slot", 33);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(34)->getId() === 336) {
    	
    	    $pf->set("Slot", 34);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(35)->getId() === 336) {
    	
    	    $pf->set("Slot", 35);
            $pf->save();
    
        }
    	
    }
    
    public function checkIron(Player $player) {
    	
    	$pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
    	if ($player->getInventory()->getItem(0)->getId() === 265) {
    	
    	    $pf->set("Slot", 0);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(1)->getId() === 265) {
    	
    	    $pf->set("Slot", 1);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(2)->getId() === 265) {
    	
    	    $pf->set("Slot", 2);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(3)->getId() === 265) {
    	
    	    $pf->set("Slot", 3);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(4)->getId() === 265) {
    	
    	    $pf->set("Slot", 4);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(5)->getId() === 265) {
    	
    	    $pf->set("Slot", 5);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(6)->getId() === 265) {
    	
    	    $pf->set("Slot", 6);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(7)->getId() === 265) {
    	
    	    $pf->set("Slot", 7);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(8)->getId() === 265) {
    	
    	    $pf->set("Slot", 8);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(9)->getId() === 265) {
    	
    	    $pf->set("Slot", 9);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(10)->getId() === 265) {
    	
    	    $pf->set("Slot", 10);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(11)->getId() === 265) {
    	
    	    $pf->set("Slot", 11);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(12)->getId() === 265) {
    	
    	    $pf->set("Slot", 12);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(13)->getId() === 265) {
    	
    	    $pf->set("Slot", 13);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(14)->getId() === 265) {
    	
    	    $pf->set("Slot", 14);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(15)->getId() === 265) {
    	
    	    $pf->set("Slot", 15);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(16)->getId() === 265) {
    	
    	    $pf->set("Slot", 16);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(17)->getId() === 265) {
    	
    	    $pf->set("Slot", 17);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(18)->getId() === 265) {
    	
    	    $pf->set("Slot", 18);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(19)->getId() === 265) {
    	
    	    $pf->set("Slot", 19);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(20)->getId() === 265) {
    	
    	    $pf->set("Slot", 20);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(21)->getId() === 265) {
    	
    	    $pf->set("Slot", 21);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(22)->getId() === 265) {
    	
    	    $pf->set("Slot", 22);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(23)->getId() === 265) {
    	
    	    $pf->set("Slot", 23);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(24)->getId() === 265) {
    	
    	    $pf->set("Slot", 24);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(25)->getId() === 265) {
    	
    	    $pf->set("Slot", 25);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(26)->getId() === 265) {
    	
    	    $pf->set("Slot", 26);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(27)->getId() === 265) {
    	
    	    $pf->set("Slot", 27);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(28)->getId() === 265) {
    	
    	    $pf->set("Slot", 28);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(29)->getId() === 265) {
    	
    	    $pf->set("Slot", 29);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(30)->getId() === 265) {
    	
    	    $pf->set("Slot", 30);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(31)->getId() === 265) {
    	
    	    $pf->set("Slot", 31);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(32)->getId() === 265) {
    	
    	    $pf->set("Slot", 32);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(33)->getId() === 265) {
    	
    	    $pf->set("Slot", 33);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(34)->getId() === 265) {
    	
    	    $pf->set("Slot", 34);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(35)->getId() === 265) {
    	
    	    $pf->set("Slot", 35);
            $pf->save();
    
        }
    	
    }
    
    public function checkGold(Player $player) {
    	
    	$pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
    	if ($player->getInventory()->getItem(0)->getId() === 266) {
    	
    	    $pf->set("Slot", 0);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(1)->getId() === 266) {
    	
    	    $pf->set("Slot", 1);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(2)->getId() === 266) {
    	
    	    $pf->set("Slot", 2);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(3)->getId() === 266) {
    	
    	    $pf->set("Slot", 3);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(4)->getId() === 266) {
    	
    	    $pf->set("Slot", 4);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(5)->getId() === 266) {
    	
    	    $pf->set("Slot", 5);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(6)->getId() === 266) {
    	
    	    $pf->set("Slot", 6);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(7)->getId() === 266) {
    	
    	    $pf->set("Slot", 7);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(8)->getId() === 266) {
    	
    	    $pf->set("Slot", 8);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(9)->getId() === 266) {
    	
    	    $pf->set("Slot", 9);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(10)->getId() === 266) {
    	
    	    $pf->set("Slot", 10);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(11)->getId() === 266) {
    	
    	    $pf->set("Slot", 11);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(12)->getId() === 266) {
    	
    	    $pf->set("Slot", 12);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(13)->getId() === 266) {
    	
    	    $pf->set("Slot", 13);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(14)->getId() === 266) {
    	
    	    $pf->set("Slot", 14);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(15)->getId() === 266) {
    	
    	    $pf->set("Slot", 15);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(16)->getId() === 266) {
    	
    	    $pf->set("Slot", 16);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(17)->getId() === 266) {
    	
    	    $pf->set("Slot", 17);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(18)->getId() === 266) {
    	
    	    $pf->set("Slot", 18);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(19)->getId() === 266) {
    	
    	    $pf->set("Slot", 19);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(20)->getId() === 266) {
    	
    	    $pf->set("Slot", 20);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(21)->getId() === 266) {
    	
    	    $pf->set("Slot", 21);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(22)->getId() === 266) {
    	
    	    $pf->set("Slot", 22);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(23)->getId() === 266) {
    	
    	    $pf->set("Slot", 23);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(24)->getId() === 266) {
    	
    	    $pf->set("Slot", 24);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(25)->getId() === 266) {
    	
    	    $pf->set("Slot", 25);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(26)->getId() === 266) {
    	
    	    $pf->set("Slot", 26);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(27)->getId() === 266) {
    	
    	    $pf->set("Slot", 27);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(28)->getId() === 266) {
    	
    	    $pf->set("Slot", 28);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(29)->getId() === 266) {
    	
    	    $pf->set("Slot", 29);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(30)->getId() === 266) {
    	
    	    $pf->set("Slot", 30);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(31)->getId() === 266) {
    	
    	    $pf->set("Slot", 31);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(32)->getId() === 266) {
    	
    	    $pf->set("Slot", 32);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(33)->getId() === 266) {
    	
    	    $pf->set("Slot", 33);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(34)->getId() === 266) {
    	
    	    $pf->set("Slot", 34);
            $pf->save();
    
        } else if ($player->getInventory()->getItem(35)->getId() === 266) {
    	
    	    $pf->set("Slot", 35);
            $pf->save();
    
        }
    	
    }
    
}