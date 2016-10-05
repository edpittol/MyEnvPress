<?php

namespace MyEnvPress\Script;

use Composer\Script\Event;
use MyEnvPress\Extra\Themes;
use MyEnvPress\Extra\Plugins;
use MyEnvPress\Extra\Data;
use MyEnvPress\Helper\CommandHelper;

class InstallScript
{
	/**
	 * Remove public directory before composer packages installation.
	 * 
	 * @param Event $event The pre-install command event.
	 */
	public static function preInstall(Event $event)
	{
		echo "Removing public directory...\n";
		exec("rm -rf public");
	}

	/**
	 * Config and install WordPress after composer packages installation.
	 *
	 * @param Event $event The post-install command event.
	 */
	public static function postInstall(Event $event)
	{
		$command = new CommandHelper();
		
		echo "Installing WordPress...\n";
		$command->run('core download');
		
		echo "Configuring site...\n";
		$command->run('core config');
		
		echo "Reseting database data...\n";
		$command->run('db reset --yes');
		
		echo "Installing site...\n";
		$command->run('core install');

		echo "Installing extra packages and data...\n";
		$themes = new Themes();
		$themes->execute();
		
		$plugins = new Plugins();
		$plugins->execute();
		
		$data = new Data();
		$data->execute();
	}
}