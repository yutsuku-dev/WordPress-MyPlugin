<?php

declare(strict_types=1);

/*
 * MyPlugin
 *
 * @package           MyPlugin
 * @author            Dawid "moh" Odzienkowski
 *
 * @wordpress-plugin
 * Plugin Name:       MyPlugin
 * Description:       Sample integration with jsonplaceholder.typicode.com.
 * Version:           0.0.1
 * Requires at least: 5.7
 * Requires PHP:      7.4
 * Author:            Dawid "moh" Odzienkowski
 * Author URI:        https://yutsuku.net
 * Text Domain:       my-lovely-users-table
 */

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

use \Yutsuku\WordPress\MyPlugin;

$plugin = new MyPlugin(__FILE__);
