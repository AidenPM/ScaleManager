<?php

namespace AidenKR\ScaleManager\command;

use AidenKR\ScaleManager\form\ScaleShopForm;
use AidenKR\ScaleManager\ScaleManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ScaleShopCommand extends Command
{
    /** @var ScaleManager */
    private ScaleManager $plugin;

    public function __construct(ScaleManager $plugin)
    {
        parent::__construct("크기상점", "크기상점 명령어 입니다.");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $player = $sender;

        if ($player instanceof Player) {
            $player->sendForm(new ScaleShopForm($this->plugin, $player));
        }
    }
}
