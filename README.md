## Installation
 + Download the simphp file from the downloads section.
 + Open the PHP file and edit the fields inside the CONFIG section.
 + Upload the file to your web server.

## Usage
First off, note that simPHP stats can only be displayed on PHP-enabled webpages (with .php ext).

There are two simple lines of code that you need to display your views.

This first line should be placed as close to the top of the page as possible.

    <?php require("path/to/simphp.php"); ?>

Replace "path/to/simphp.php" with the relative or absolute path to the simphp.php file from the webpage.
If you don't know the absolute path to the simphp.php file, visit the file directly in your browser, placing "?display=true" at the end (minus quotes). For example:

 `http://example.com/simphp.php?display=true`


This line of code should be placed where you want the information to be displayed.

    <?php echo $info; ?>

Example:
 
     <p class="hits"><?php echo $info; ?></p>

See the simphp.php file itself for more config options.`
