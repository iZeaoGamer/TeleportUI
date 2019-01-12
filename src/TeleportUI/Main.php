<?php

namespace TeleportUI;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\{command\ConsoleCommandSender, Server, Player, utils\TextFormat};
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("TeleportUI by @retired_dev!");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        $player = $sender->getPlayer();
        switch($command->getName()){
            case "tpa":
                $this->teleportForm($player);
        }
        return true;
    }

    public function teleportForm($player){
        if($player instanceof Player){
            $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
            $form = $api->createSimpleForm(function (Player $sender, $data){
                if(isset($data[0])){
                    switch($data[0]){
                        case 0:
                            $this->tpaskForm($sender);
                            break;
                        case 1:
                             $this->tpahereForm($sender);
                            break;
                        case 2:
                            $this->tpacceptForm($sender);
                            break;
                        case 3:
                            $this->tpdenyForm($sender);
                            break;
                    }
                }
            });
            $form->setTitle("Teleport");
            $form->setContent("Teleport to other players!");
            $form->addButton("Tpask");
            $form->addButton("Tpahere");
            $form->addButton("Tpaccept");
            $form->addButton("Tpdeny");
            $form->sendToPlayer($player);
        }
    }

    public function tpaskForm($player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $event, $data){
            $player = $event->getPlayer();
            $result = $data[0];
            if($result != null){
                $this->askName = $result;
                $this->getServer()->getCommandMap()->dispatch($player, "tpask " . $this->askName);
            }
        });
        $form->setTitle(TextFormat::GREEN . "Tpask Request");
        $form->addInput("Request to teleport to this player!");
        $form->sendToPlayer($player);
    }
    
    public function tpahereForm($player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $event, $data){
            $player = $event->getPlayer();
            $result = $data[0];
            if($result != null){
                $this->ahereName = $result;
                $this->getServer()->getCommandMap()->dispatch($player, "tpahere " . $this->ahereName);
            }
        });
        $form->setTitle(TextFormat::GREEN . "Tpahere Request");
        $form->addInput("Request to teleport this player to you!");
        $form->sendToPlayer($player);
    }
    
    public function tpacceptForm($player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $event, $data){
            $player = $event->getPlayer();
            $result = $data[0];
            if($result != null){
                $this->acceptName = $result;
                $this->getServer()->getCommandMap()->dispatch($player, "tpaccept " . $this->acceptName);
            }
        });
        $form->setTitle(TextFormat::GREEN . "Tpaccept player's request");
        $form->addInput("Accept player's teleport request!");
        $form->sendToPlayer($player);
    }
    
    public function tpdenyForm($player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $event, $data){
            $player = $event->getPlayer();
            $result = $data[0];
            if($result != null){
                $this->denyName = $result;
                $this->getServer()->getCommandMap()->dispatch($player, "tpdeny " . $this->denyName);
            }
        });
        $form->setTitle(TextFormat::GREEN . "Tpdeny player's request");
        $form->addInput("Deny player's teleport request!");
        $form->sendToPlayer($player);
    }


}
