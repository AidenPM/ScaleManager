<?php

namespace AidenKR\ScaleManager;

use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\ItemTypeIds;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Filesystem;
use pocketmine\utils\SingletonTrait;
use AidenKR\ScaleManager\listener\EventListener;
use AidenKR\ScaleManager\command\ScaleCoinCommand;
use AidenKR\ScaleManager\command\ScaleShopCommand;
use AidenKR\ScaleManager\command\ScaleCommand;

class ScaleManager extends PluginBase
{
    use SingletonTrait;

    /** @var string */
    public static string $prefix = '§l§a[!] §r§7';

    /** @var array */
    public array $data = [];

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    protected function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getServer()->getCommandMap()->registerAll('AidenKR', [
            new ScaleCommand($this), new ScaleShopCommand($this)
        ]);

        if (!file_exists($this->getDataFolder() . "config.json")) {
            if (!is_file($this->getDataFolder() . "config.json")) return;

            $this->data = json_decode(file_get_contents($this->getDataFolder() . "config.json"), true);
        }
        CreativeInventory::getInstance()->add($this->getScaleCoin());
    }

    protected function onDisable(): void
    {
        Filesystem::safeFilePutContents($this->getDataFolder() . "config.json", json_encode($this->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function getScaleCoin(): Item
    {
        $item = new Item(new ItemIdentifier(ItemTypeIds::CLAY), "Scale Shop Coin");
        $item->setCustomName("§l§e< §f크기 코인 §e>");
        $item->setLore([]);
        $item->getNamedTag()->setString("scaleCoin", "");
        return $item;
    }

    public function getScale(string $name): float
    {
        if(!isset($this->data[strtolower($name)])) {
            return $this->data[strtolower($name)];
        }
        return 1;
    }

    public function addScale(string $name, float $scale)
    {
        $this->data[strtolower($name)]["scale"] += $scale;
    }

    public function setScale(string $name, float $scale)
    {
        $this->data[strtolower($name)]["scale"] = $scale;
    }

    public function reduceScale(string $name, float $scale)
    {
        $this->data[strtolower($name)]["scale"] -= $scale;
    }
}
