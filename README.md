# Error Reporting
I'm constantly looking for a better error reporting system. I think I've built this one a hundred times. But now I'm integrating it into Croogo as a plugin.

## INSTALLATION
There are a number of ways to install this plugin, here are a few:

### Git Bash
* Open gitbash and change directories into your croogo root directory. That will be CakephpRoot/app.
* Then add this plugin as a submodule, run:
    git submodule add git@github.com:Jonathonbyrd/Error.git Plugin/Error
* The plugin should now be downloaded to your croogo installation and ready to be activated.

### Linux Command Line
* Open Terminal and change directories into your plugin directory. That will be CakephpRoot/app/Plugin.
* Create the directory
    $ mkdir Error && cd Error
* Download the plugin
    $ wget https://github.com/Jonathonbyrd/Error/archive/master.zip
* Unzip your plugin
    $ unzip master.zip
* The plugin should now be downloaded to your croogo installation and ready to be activated.

### Activate the downloaded plugin
* Log into your Croogo installation
* Navigate to "Extensions > Plguins"
* Activate the "Error and Debugging" Plugin

## Seeing your plugin in action
This plugin is designed to only output errors when told. That includes and print statements that you've included in your code. You will need to enable debugging in croogo to view any output from this plugin.

### Enable Debugging in Croogo
* Open your file browser
* Open the Croogo Configuration file "app/Config/croogo.php"
* Locate the following line of code "Configure::write('debug', 0);"
* Upgrade your debug mode to level one or higher
    Configure::write('debug', 1);