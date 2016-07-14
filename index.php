<?php

require_once 'vendor/FileFinderImplementation.php';

echo '<br>First<br>';
# search for all .conf or .ini files in directories /etc/ and /var/log/
$fileList = new FileFinderImplementation();
$fileList
    ->isFile()
    ->inDir('/etc/')
    ->inDir('/var/log/')
    ->match('/.*\.conf$/')
    ->match('/.*\.log$/')
    ->match('/.*\.ini$/');

$files = $fileList->getList();

foreach ($files as $file) {
    print $file . "<br>";
}

echo '<br>Two<br>';
#  search for all files in /tmp
$fileList = (new FileFinderImplementation())
    ->inDir('/tmp')
    ->isFile();

$files = $fileList->getList();

foreach ($files as $file) {
    print $file . "<br>";
}

echo '<br>Three<br>';
#  search for .doc files in /tmp
$fileList = (new FileFinderImplementation())
    ->match('/.*\.doc$/')
    ->isFile()
    ->inDir('/tmp/');
$files = $fileList->getList();

foreach ($files as $file) {
    print $file . "<br>";
}

echo '<br>Four<br>';
# should throw an exception if no dirs were provided
$files = (new FileFinderImplementation())
    ->isFile()
    ->match('/.*\.ini$/')
    ->getList(); # -> exception
