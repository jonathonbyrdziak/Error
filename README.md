# Error Reporting
I'm constantly looking for a better error reporting system. I think I've built this one a hundred times. But now I'm integrating it into Croogo as a plugin.

## INSTALLATION
There are a number of ways to install this plugin, here are a few:

### Git Bash
* Open gitbash and change directories into your croogo root directory. That will be CakephpRoot/app.
* Then add this plugin as a submodule, run:

`git submodule add git@github.com:Jonathonbyrd/Error.git Plugin/Error`

* The plugin should now be downloaded to your croogo installation and ready to be activated.

### Linux Command Line
* Open Terminal and change directories into your plugin directory. That will be CakephpRoot/app/Plugin.
* Create the directory

`$ mkdir Error && cd Error`
    
* Download the plugin

`$ wget https://github.com/Jonathonbyrd/Error/archive/master.zip`
    
* Unzip your plugin
    
`$ unzip master.zip`
    
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

`Configure::write('debug', 1);`
    
### Using the quick output function
I've gotten incredibly tired of writing the following code a thousand times

`echo '<pre>';print_r($outputThis);echo '</pre>;die;`

So I've created a nice little quick output function that will not only write this code, but give us a whole bunch of other valuable information assocated with our variable. The function will tell us:
* What file we called the output from
* What line we called the output from
* What the name of the variable is that we are outputting,
* Then style the output with the most readable function possible. For example, if the variable is null, we normally will not see any output when using print_r, so this function will use var_dump instead, to show us an empty string, or a null value. Furthermore, if the variable is an array, then var_export is used to show us the most human readable form of the array possible.

Line numbers and styling are also used when outputing data using this function, to give us the absolute most readable data possible.

To use this function you would pass any number of variables that you would like outputted, to the `p()` function.

`p($first, $second, $etc);`

All Done!

### Using the logging function
First make sure that the "Logs" folder within the installation directory of this plugin is fully writable.

Now, simply pass a message to the `l();` function. Your message will be saved, along with the date, time and your IP address to the Logs/default.log file.

Usage for this function is as follows:

`l($message, $logFileName);`
    