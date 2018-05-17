<?php
/**
 * DocBlockGenerator
 *
 * This class will generate docblock outline for files/folders.
 *
 * Use from command line - params:
 * file/folder - the file or folder you want to docblock (php files)
 * -r - to have it recursively go through a folder
 * target function - to docblock only a specific method/function name
 *
 * Example:
 * php docblock.php target.php targetFunction
 * or
 * php docblock.php target/dir -r targetFunction
 *
 * Credit to Sean Coates for the getProtos function, modified a little.
 * http://seancoates.com/fun-with-the-tokenizer
 *
 * TODOs :
 * 1. add all proper docblock properties
 * 2. better checking for if docblock already exists
 * 3. docblocking for class properties
 * 4. try to gather more data for automatic insertion such as for @access
 *
 * @author    Anthony Gentile
 * @version   0.85
 * @link      http://agentile.com/docblock/
 */

include("DocBlockGenerator.class.php");

use DocBlockGenerator\DocBlockGenerator ;

$argv = empty($_SERVER['argv']) ? array(0 => '') : $_SERVER['argv'];

$current_dir = getcwd();

$options = array(
    'file_folder' => '',
    'target_function' => '',
    'recursive' => false
);

foreach ($argv as $k => $arg) {
    if ($k !== 0) {
        if (strtolower($arg) === '-r') {
            $options['recursive'] = true;
        } elseif (is_file($arg)) {
            $options['file_folder'] = $arg;
        } elseif (is_file($current_dir . '/' . $arg)) {
            $options['file_folder'] = $current_dir . '/' . $arg;
        } elseif (is_dir($arg)) {
            $options['file_folder'] = $arg;
        } elseif (is_dir($current_dir . '/' . $arg)) {
            $options['file_folder'] = $current_dir . '/' . $arg;
        } else {
            $options['target_function'] = $arg;
        }
    }
}

if (isset($argv[1])) {
    if (is_file($options['file_folder']) || is_dir($options['file_folder'])) {
        $doc_block_generator = new DocBlockGenerator($options['file_folder'], $options['target_function'], $options['recursive']);
        $doc_block_generator->start();
        $doc_block_generator->result();
    } else {
        die("\nThis is not a valid file or directory\n");
    }

} else {
    die("\nPlease provide a file or directory as a parameter\n");
}
