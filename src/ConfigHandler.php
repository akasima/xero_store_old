<?php
namespace Akasima\RichShop;

use Xpressengine\Config\ConfigManager;

/**
 * ConfigHandler
 *
 * @package Akasima\RichShop
 */
class ConfigHandler
{
    /**
     * rich_shop (plugin id)
     * @var string
     */
    protected $key;

    /** @var ConfigManager  */
    protected $configManager;

    public function __construct(ConfigManager $configManager)
    {
        $this->key = Plugin::getId();
        $this->configManager = $configManager;
    }

    /**
     * get config
     *
     * @return \Xpressengine\Config\ConfigEntity
     */
    public function get()
    {
        return $this->configManager->get($this->key);
    }

}
