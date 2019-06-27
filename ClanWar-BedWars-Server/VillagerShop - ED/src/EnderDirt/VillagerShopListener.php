<?php

namespace EnderDirt;

use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Player;
use pocketmine\utils\TextFormat as Color;
use pocketmine\utils\Config;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class VillagerShopListener {

	protected $plugin;

	public function __construct(VillagerShop $plugin) {
		
		$this->plugin = $plugin;
		
	}
	
	public function onTransaction(Player $player, Item $itemClickedOn, Item $itemClickedWith) : bool {
		
		if ($itemClickedOn->getCustomName() === Color::YELLOW . "Bloecke") {
			
			$this->plugin->setBlockShop($player);
			
        } else if ($itemClickedOn->getCustomName() === Color::RED . "Waffen") {
			
			$this->plugin->setWaffenShop($player);
			
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Essen") {
			
			$this->plugin->setFoodShop($player);
			
        } else if ($itemClickedOn->getCustomName() === Color::AQUA . "SpitzHacken") {
			
			$this->plugin->setAxeShop($player);
			
        } else if ($itemClickedOn->getCustomName() === Color::GRAY . "Ruestung") {
			
			$this->plugin->setRusShop($player);
			
        } else if ($itemClickedOn->getCustomName() === Color::DARK_RED . "Bogen") {
			
			$this->plugin->setBowShop($player);
			
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Chest") {
			
			$this->plugin->setChestShop($player);
			
        } else if ($itemClickedOn->getCustomName() === Color::LIGHT_PURPLE . "Special Items") {
			
			$this->plugin->setSpecialShop($player);
			
        }
        
        if ($itemClickedOn->getCustomName() === Color::GOLD . "SandStone") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
			if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");
				
			} else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 0) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 1));
                    $player->getInventory()->addItem(Item::get(179, 0, 4));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::AQUA . "SandStone") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 7) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 8));
                    $player->getInventory()->addItem(Item::get(179, 0, 32));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GRAY . "SandStone") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 13) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 14));
                    $player->getInventory()->addItem(Item::get(179, 0, 64));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GRAY . "SandStone All") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 15) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 16));
                    $player->getInventory()->addItem(Item::get(179, 0, 64));

                } else if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() < 17) {

                    $count = $player->getInventory()->getItem($pf->get("Slot"))->getCount();
                    $player->getInventory()->removeItem(Item::get(336, 0, $count));
                    $player->getInventory()->addItem(Item::get(179, 0, $count * 4));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GRAY . "CobWeb") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 15) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 16));
                    $player->getInventory()->addItem(Item::get(30, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GRAY . "Leiter") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 1) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 2));
                    $player->getInventory()->addItem(Item::get(65, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::AQUA . "EndStone") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 5) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 6));
                    $player->getInventory()->addItem(Item::get(121, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GRAY . "Glas") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 10) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 11));
                    $player->getInventory()->addItem(Item::get(20, 0, 2));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::AQUA . "KnockBackStick") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 7) {

                    $stick = Item::get(280, 0, 1);
                    $player->getInventory()->removeItem(Item::get(336, 0, 8));
                    $player->getInventory()->addItem($stick);

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Gold Schwert Level 1") {
        	
        	$this->plugin->checkIron($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 0) {

                    $player->getInventory()->removeItem(Item::get(265, 0, 1));
                    $player->getInventory()->addItem(Item::get(283, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Gold Schwert Level 2") {
        	
        	$this->plugin->checkIron($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 2) {

                    $enchantment = Enchantment::getEnchantment(9);
                    $sword = Item::get(283, 0, 1);
                    $sword->addEnchantment(new EnchantmentInstance($enchantment, 1));
                    $player->getInventory()->removeItem(Item::get(265, 0, 3));
                    $player->getInventory()->addItem($sword);

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Gold Schwert Level 3") {
        	
        	$this->plugin->checkIron($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 6) {

                    $enchantment = Enchantment::getEnchantment(9);
                    $sword = Item::get(283, 0, 1);
                    $sword->addEnchantment(new EnchantmentInstance($enchantment, 2));
                    $player->getInventory()->removeItem(Item::get(265, 0, 7));
                    $player->getInventory()->addItem($sword);

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::WHITE . "Eisen Schwert") {
        	
        	$this->plugin->checkGold($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 4) {

                    $player->getInventory()->removeItem(Item::get(266, 0, 5));
                    $player->getInventory()->addItem(Item::get(267, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Steak") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 3) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 4));
                    $player->getInventory()->addItem(Item::get(364, 0, 2));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::RED . "Apfel") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 1) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 2));
                    $player->getInventory()->addItem(Item::get(260, 0, 4));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Gold Apfel") {
        	
        	$this->plugin->checkGold($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 0) {

                    $player->getInventory()->removeItem(Item::get(266, 0, 1));
                    $player->getInventory()->addItem(Item::get(322, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GRAY . "Holz SpitzHacke") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 3) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 4));
                    $player->getInventory()->addItem(Item::get(270, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::DARK_GRAY . "Stein SpitzHacke") {
        	
        	$this->plugin->checkIron($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 1) {

                    $player->getInventory()->removeItem(Item::get(265, 0, 2));
                    $player->getInventory()->addItem(Item::get(274, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::WHITE . "Eisen SpitzHacke") {
        	
        	$this->plugin->checkGold($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 0) {

                    $player->getInventory()->removeItem(Item::get(266, 0, 1));
                    $player->getInventory()->addItem(Item::get(257, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::AQUA . "Bogen") {
        	
        	$this->plugin->checkGold($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 2) {

                    $enchantment = Enchantment::getEnchantment(22);
                    $bow = Item::get(261, 0, 1);
                    $bow->addEnchantment(new EnchantmentInstance($enchantment, 1));
                    $player->getInventory()->removeItem(Item::get(266, 0, 3));
                    $player->getInventory()->addItem($bow);

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::AQUA . "Pfeile") {
        	
        	$this->plugin->checkGold($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 0) {

                    $player->getInventory()->removeItem(Item::get(266, 0, 1));
                    $player->getInventory()->addItem(Item::get(262, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Helm") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 0) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 1));
                    $player->getInventory()->addItem(Item::get(298, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Hose") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 0) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 1));
                    $player->getInventory()->addItem(Item::get(300, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Schuhe") {
        	
        	$this->plugin->checkBronze($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 0) {

                    $player->getInventory()->removeItem(Item::get(336, 0, 1));
                    $player->getInventory()->addItem(Item::get(301, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Chestplate Level 1") {
        	
        	$this->plugin->checkIron($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 0) {

                    $enchantment = Enchantment::getEnchantment(0);
                    $chest = Item::get(303, 0, 1);
                    $chest->addEnchantment(new EnchantmentInstance($enchantment, 1));
                    $player->getInventory()->removeItem(Item::get(265, 0, 1));
                    $player->getInventory()->addItem($chest);

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Chestplate Level 2") {
        	
        	$this->plugin->checkIron($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 2) {

                    $enchantment = Enchantment::getEnchantment(0);
                    $chest = Item::get(303, 0, 1);
                    $chest->addEnchantment(new EnchantmentInstance($enchantment, 2));
                    $player->getInventory()->removeItem(Item::get(265, 0, 3));
                    $player->getInventory()->addItem($chest);

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GOLD . "Chestplate Level 3") {
        	
        	$this->plugin->checkIron($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 6) {

                    $enchantment = Enchantment::getEnchantment(0);
                    $chest = Item::get(303, 0, 1);
                    $chest->addEnchantment(new EnchantmentInstance($enchantment, 3));
                    $player->getInventory()->removeItem(Item::get(265, 0, 7));
                    $player->getInventory()->addItem($chest);

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::YELLOW . "Chest") {
        	
        	$this->plugin->checkIron($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 0) {

                    $player->getInventory()->removeItem(Item::get(265, 0, 1));
                    $player->getInventory()->addItem(Item::get(54, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::DARK_PURPLE . "Ender Chest") {
        	
        	$this->plugin->checkGold($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 0) {

                    $player->getInventory()->removeItem(Item::get(266, 0, 1));
                    $player->getInventory()->addItem(Item::get(130, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::GREEN . "Speed Potion") {
        	
        	$this->plugin->checkIron($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 1) {

                    $player->getInventory()->removeItem(Item::get(265, 0, 2));
                    $player->getInventory()->addItem(Item::get(441, 15, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::YELLOW . "Jump Potion") {
        	
        	$this->plugin->checkIron($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 1) {

                    $player->getInventory()->removeItem(Item::get(265, 0, 2));
                    $player->getInventory()->addItem(Item::get(441, 10, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::DARK_PURPLE . "Regeneration Potion") {
        	
        	$this->plugin->checkIron($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 4) {

                    $player->getInventory()->removeItem(Item::get(265, 0, 5));
                    $player->getInventory()->addItem(Item::get(441, 28, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::LIGHT_PURPLE . "Health Potion") {
        	
        	$this->plugin->checkGold($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 1) {

                    $player->getInventory()->removeItem(Item::get(266, 0, 2));
                    $player->getInventory()->addItem(Item::get(441, 21, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::LIGHT_PURPLE . "Staerke Potion") {
        	
        	$this->plugin->checkGold($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 4) {

                    $player->getInventory()->removeItem(Item::get(266, 0, 5));
                    $player->getInventory()->addItem(Item::get(441, 31, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::DARK_PURPLE . "EnderPearl") {
        	
        	$this->plugin->checkGold($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 12) {

                    $player->getInventory()->removeItem(Item::get(266, 0, 13));
                    $player->getInventory()->addItem(Item::get(368, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }
        	
        } else if ($itemClickedOn->getCustomName() === Color::RED . "TNT") {

            $this->plugin->checkGold($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 0) {

                    $player->getInventory()->removeItem(Item::get(266, 0, 1));
                    $player->getInventory()->addItem(Item::get(46, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }

        } else if ($itemClickedOn->getCustomName() === Color::GRAY . "Flint") {

            $this->plugin->checkIron($player);
            $pf = new Config("/home/ClanWars/BedWars/players/" . $player->getName() . ".yml", Config::YAML);
            if ($pf->get("Slot") === 36) {

                $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

            } else {

                if ($player->getInventory()->getItem($pf->get("Slot"))->getCount() > 1) {

                    $player->getInventory()->removeItem(Item::get(265, 0, 2));
                    $player->getInventory()->addItem(Item::get(259, 0, 1));

                } else {

                    $player->sendMessage(Color::RED . "Du hast zu wenig Ressourcen");

                }

                $pf->set("Slot", 36);
                $pf->save();

            }

        }
		
		return true;
		
	}
	
}