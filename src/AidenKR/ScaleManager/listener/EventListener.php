<?php

namespace AidenKR\ScaleManager\listener;

use AidenKR\ScaleManager\ScaleManager;
use pocketmine\block\BlockTypeIds;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;

class EventListener implements Listener
{
    /** @var ScaleManager */
    private ScaleManager $plugin;

    public function __construct(ScaleManager $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event): void
    {
        $name = $event->getPlayer()->getName();

        if (!isset($this->plugin->scale[strtolower($name)])) {
            $this->plugin->data[strtolower($name)]["scale"] = 1;
        }
        $this->plugin->setScale($name, $this->plugin->getScale($name));
        $event->getPlayer()->setScale($this->plugin->getScale($name));
    }

    public function onMove(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $block = $event->getFrom()->getWorld()->getBlock($player->getPosition());

        if ($block->getTypeId() === BlockTypeIds::RED_MUSHROOM_BLOCK) {
            $player->setScale(1);
            $player->sendMessage(ScaleManager::$prefix . "크기를 1로 설정했습니다.");
        }

        if ($block->getTypeId() === BlockTypeIds::CORAL_BLOCK) {
            $player->setScale($this->plugin->getScale($name));
            $player->sendMessage(ScaleManager::$prefix . "크기를 복구했습니다.");
        }
    }
}
