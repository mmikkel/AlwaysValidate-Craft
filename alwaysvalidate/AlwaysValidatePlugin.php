<?php namespace Craft;

/**
 * Always Validate will force validation for disabled elements.
 *
 * @author      Mats Mikkel Rummelhoff <http://mmikkel.no>
 * @package     Always Validate
 * @since       Craft 2.3
 * @copyright   Copyright (c) 2015, Mats Mikkel Rummelhoff
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @link        https://github.com/mmikkel/AlwaysValidate-Craft
 */

class AlwaysValidatePlugin extends BasePlugin
{

    protected   $_version = '1.0',
                $_developer = 'Mats Mikkel Rummelhoff',
                $_developerUrl = 'http://mmikkel.no',
                $_pluginName = 'Always Validate',
                $_pluginUrl = 'https://github.com/mmikkel/AlwaysValidate-Craft',
                $_minVersion = '2.3';

    public function getName()
    {
         return $this->_pluginName;
    }

    public function getVersion()
    {
        return $this->_version;
    }

    public function getDeveloper()
    {
        return $this->_developer;
    }

    public function getDeveloperUrl()
    {
        return $this->_developerUrl;
    }

    public function getPluginUrl()
    {
        return $this->_pluginUrl;
    }

    public function getCraftRequiredVersion()
    {
        return $this->_minVersion;
    }

    public function isCraftRequiredVersion()
    {
        return version_compare( craft()->getVersion(), $this->getCraftRequiredVersion(), '>=' );
    }

    public function init()
    {
        parent::init();
        $this->addEventListeners();
    }

    protected function addEventListeners()
    {
        craft()->on('entries.beforeSaveEntry',array($this,'onBeforeSaveEntry'));
        craft()->on('categories.beforeSaveCategory',array($this,'onBeforeSaveCategory'));
    }

    // Event handlers
    public function onBeforeSaveEntry(Event $event)
    {
        $entry = $event->params['entry'];
        if(!$entry->enabled && !craft()->content->validateContent($entry))
        {
            $entry->addErrors($entry->getContent()->getErrors());
            $event->performAction = false;
        }
        return $event;
    }

    public function onBeforeSaveCategory(Event $event)
    {
        $category = $event->params['category'];
        if(!$category->enabled && !craft()->content->validateContent($category))
        {
            $category->addErrors($category->getContent()->getErrors());
            $event->performAction = false;
        }
        return $event;
    }

}
