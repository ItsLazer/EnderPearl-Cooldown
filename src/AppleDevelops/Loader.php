<?php

namespace AppleDevelops;

use pocketmine\entity\EnderPearl;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase implements Listener{

    private $coolDown = 60;
    private $timer = [];

    public function onEnable(){
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->coolDown = $this->getConfig()->get("cooldown-timer");
    }

    public function onLaunch(ProjectileLaunchEvent $event){
        if ($event->isCancelled()) return;

        if ($event->getEntity() instanceof EnderPearl) {
            $shooter = $event->getEntity()->shootingEntity;

            if ($shooter instanceof Player) {

                $name = strtolower($shooter->getDisplayName());

                if (!isset($this->timer[$name]) or time() > $this->timer[$name]) {
                    $this->timer[$name] = time() + $this->coolDown;
                } else {
                    $shooter->sendPopup($this->getConfig()->get("cooldown-message"));
                    $event->setCancelled();     
                }
            }
        }
    }
}
