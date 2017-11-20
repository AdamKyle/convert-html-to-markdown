#!/usr/bin/php
<?php
// we need this for external libraries.
require 'vendor/autoload.php';

use League\HTMLToMarkdown\HtmlConverter;

// Get Directory Command Argument:
$directory = $argv[1];          // input directory where the .html files live
$outputDirectory = $argv[2];    // output directory for the .md files

/**
 * Get the directory contents.
 *
 * @param string $directory
 * @return Array
 */
function getDirectoryContents(string $directory) {
    $recursiveIteratorIterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory)
    );

    $files = [];
    $dirtoryBreakdown = [];

    forEach ($recursiveIteratorIterator as $file) {
        if($file->isDir()){
            continue;
        }

        $files[] = $file->getPathname();
    }

    forEach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'html') {
            $filePathArray = explode('/', $file);
            $fileBreakDown = array_slice($filePathArray, 4);
            $fileName      = explode('.html', end($fileBreakDown))[0];

            $dirtoryBreakdown[$fileName] = [
                'full_path' => $file,
                'break_down' => $fileBreakDown
            ];
        }
    }

    return $dirtoryBreakdown;
}

/**
 * Write the markdown files to a specified output directory based on directory contents.
 *
 * @param array $directoryContents
 * @param String $outputDirectory
 * @return null.
 */
function writeMarkDownFiles(array $directoryContents, string $outputDirectory) {
    $markDownForFile = '';
    $pathForFile = '';
    $directoryToCreate = '';

    forEach ($directoryContents as $fileName => $fileInfo) {
        forEach ($fileInfo as $key => $value) {
            if ($key === 'full_path') {
                $fileContents = file_get_contents($value);
                $converter    = new HtmlConverter();

                $markDownForFile = $converter->convert($fileContents);
                $markDownForFile = strip_tags($markDownForFile);
            }

            if ($key === 'break_down') {
                $newPath = implode('/', $value);
                $pathForFile = explode('.html', $newPath)[0];

                $directoryToCreate = substr(
                    $pathForFile,
                    0,
                    strrpos($pathForFile, '/')
                );
            }

            if (!file_exists($outputDirectory . $directoryToCreate)) {
                mkdir($outputDirectory . $directoryToCreate, 0777, true);

                file_put_contents(
                    $outputDirectory . $pathForFile . '.md',
                    $markDownForFile
                );
            } else {
                file_put_contents(
                    $outputDirectory . $pathForFile . '.md',
                    $markDownForFile
                );
            }
        }
    }
}

// Execute the above functions
writeMarkDownFiles(getDirectoryContents($directory), $outputDirectory);
