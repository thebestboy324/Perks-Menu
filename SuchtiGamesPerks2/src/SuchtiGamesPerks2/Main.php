<?php

declare(strict_types=1);

namespace SuchtiGamesPerks2;

use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\block\BlockFactory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\Item\Item;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use DateTime;
use pocketmine\Server;

class Main extends PluginBase implements Listener {

    public $prefix = "§8[§aPerks§8]";
    /** @var string */
    public $noperm = TextFormat::AQUA . "[" . TextFormat::RED . "Perks" . TextFormat::AQUA . "] Du benötigst einen höheren Rang oder ein Perk um /perk nutzen zukönnen.";

    /**
     * @return void
     */

    public function checkDepends(){
        $this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        if(is_null($this->formapi)){
            $this->getLogger()->info("§4Please install FormAPI Plugin!!");
            }
        }
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        switch (strtolower($command->getName())) {
            case "perks":
                if ($sender instanceof Player) {
                    if (!isset($args[0])) {
                        if (!$sender->hasPermission("perks.command") or !$sender->hasPermission("perks.admin")) {
                            $sender->sendMessage($this->noperm);
                            return true;
                        } else {
                            $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
                            $form = $api->createSimpleForm(function (Player $sender, $data) {
                                $result = $data;
                                if ($result == null) {
                                }
                                switch ($result) {
                                    case 0:
                                        $sender->addTitle("§aPerks", "§8von thebestboy324");
                                        break;
                                    case 1:
                                        $command = "perks help";
                                        $this->getServer()->getCommandMap()->dispatch($sender, $command);
                                        break;
                                    case 2:
                                        $command = "perks jump";
                                        $this->getServer()->getCommandMap()->dispatch($sender, $command);
                                        break;
                                    case 3:
                                        $command = "perks break";
                                        $this->getServer()->getCommandMap()->dispatch($sender, $command);
                                        break;
                                    case 4:
                                        $command = "perks speed";
                                        $this->getServer()->getCommandMap()->dispatch($sender, $command);
                                        break;
                                    case 5:
                                        $command = "perks fire";
                                        $this->getServer()->getCommandMap()->dispatch($sender, $command);
                                        break;
                                    case 6:
                                        $command = "perks damage";
                                        $this->getServer()->getCommandMap()->dispatch($sender, $command);
                                        break;
                                    case 7:
                                        $command = "perks night";
                                        $this->getServer()->getCommandMap()->dispatch($sender, $command);
                                        break;
                                }
                            });
                            $form->setTitle("§l§aPerks Menu");
                            $form->setContent("§7Klick auf ein Perk um es zu aktivieren oder deaktivieren.");
                            $form->addButton("§4 Abruch", 0);
                            $form->addButton("§6 Hilfe", 1);
                            $form->addButton("§a Höher Springen", 2);
                            $form->addButton("§2 Schneller abbauen ", 3);
                            $form->addButton("§a Schneller Rennen ", 4);
                            $form->addButton("§2 Kein Feuer Schaden", 5);
                            $form->addButton("§a Unsterblichkeit", 6);
                            $form->addButton("§2 Nachtsicht", 7);

                            $form->sendToPlayer($sender);
                        }
                        return true;
                    }
                    $arg = array_shift($args);
                    switch ($arg) {
                        case "version":
                            if (!$sender->hasPermission("perks.version") or !$sender->hasPermission("perks.admin")) {
                                $sender->sendMessage($this->noperm);
                                return true;
                            }
                            $sender->sendMessage($this->prefix . "§r" . TextFormat::BLUE . "Perks Version 2.1.0 by thebstboy324");
                            return true;
                        case "help":
                        case "?":
                            $sender->sendMessage(TextFormat::AQUA . "§aBenutze /perks um in das Perk menu zukommen");
                            return true;
                            break;
                        case "break":
                            if ($sender->hasPermission("perks.break") or $sender->hasPermission("perks.admin")) {
                                if (!$sender->hasPermission("perks.break.2") or !$sender->hasPermission("perks.admin")) {
                                    if ($sender->hasEffect(3)) {
                                        $sender->removeEffect(3);
                                        $sender->sendMessage($this->prefix . "§4 das schneller abbauen Perk§r§4 wurde deaktiviert!");

                                    } else {
                                        $effect = new EffectInstance(Effect::getEffect(3), 100000, 2, false);
                                        $sender->addEffect($effect);
                                        $sender->sendMessage($this->prefix . "§a Das schneller abbauen Perk§r§a wurde aktiviert!");
                                    }
                                    $sender->getServer()->getCommandMap()->dispatch($sender, "perks break2");
                                }
                            }
                            break;
                        case "jump":
                            if ($sender->hasPermission("perks.jump") or $sender->hasPermission("perks.admin")) {
                                if ($sender->hasEffect(8)) {
                                    $sender->removeEffect(8);
                                    $sender->sendMessage($this->prefix . "§4 das höher Springen Perk§r§4 wurde deaktiviert!");

                                } else {
                                    $effect = new EffectInstance(Effect::getEffect(8), 100000, 2, false);
                                    $sender->addEffect($effect);
                                    $sender->sendMessage($this->prefix . "§a Das höher springen Perk§r§a wurde aktiviert!");
                                }
                            }
                            break;
                        case "speed":
                            if ($sender->hasPermission("perks.atmung") or $sender->hasPermission("perks.admin")) {
                                if ($sender->hasEffect(1)) {
                                    $sender->removeEffect(1);
                                    $sender->sendMessage($this->prefix . "§4 Das schneller laufen Perk§r§4 wurde deaktiviert!");
                                } else {
                                    $effect = new EffectInstance(Effect::getEffect(1), 100000, 3, false);
                                    $sender->addEffect($effect);
                                    $sender->sendMessage($this->prefix . "§a Das schneller laufen Perk§r§a wurde aktiviert!");
                                }
                            }
                            break;
                        case "fire":
                            if ($sender->hasPermission("perks.fire") or $sender->hasPermission("perks.admin")) {
                                if ($sender->hasEffect(12)) {
                                    $sender->removeEffect(12);
                                    $sender->sendMessage($this->prefix . "§4 Das kein feuerschaden Perk§r§4 wurde deaktiviert!");

                                } else {
                                    $effect = new EffectInstance(Effect::getEffect(12), 100000, 2, false);
                                    $sender->addEffect($effect);
                                    $sender->sendMessage($this->prefix . "§a Das kein feuerschaden Perk§r§a wurde aktiviert!");
                                }
                            }
                            break;
                        case "damage":
                            if ($sender->hasPermission("perks.atmung") or $sender->hasPermission("perks.admin")) {
                                if ($sender->hasEffect(6)) {
                                    $sender->removeEffect(6);
                                    $sender->removeEffect(10);
                                    $sender->removeEffect(11);
                                    $sender->sendMessage($this->prefix . "§4 Das unsterblichkeit Perk§r§4 wurde deaktiviert!");
                                } else {
                                    $effect = new EffectInstance(Effect::getEffect(6), 100000, 250, false);
                                    $efect = new EffectInstance(Effect::getEffect(10), 100000, 250, false);
                                    $ef = new EffectInstance(Effect::getEffect(11), 100000, 250, false);
                                    $sender->addEffect($effect);
                                    $sender->addEffect($efect);
                                    $sender->addEffect($ef);
                                    $sender->sendMessage($this->prefix . "§a Das unsterblichkeit Perk§r§a wurde aktiviert!");
                                }
                            }
                            break;
                        case "night":
                            if ($sender->hasPermission("perks.fire") or $sender->hasPermission("perks.admin")) {
                                if ($sender->hasEffect(16)) {
                                    $sender->removeEffect(16);
                                    $sender->sendMessage($this->prefix . "§4 Das nachtsicht Perk§r§4 wurde deaktiviert!");

                                } else {
                                    $effect = new EffectInstance(Effect::getEffect(16), 100000, 3, false);
                                    $sender->addEffect($effect);
                                    $sender->sendMessage($this->prefix . "§a Das nachtsicht Perk§r§a wurde aktiviert!");
                                }
                            }
                    }
                }
        }
        return true;
    }
}