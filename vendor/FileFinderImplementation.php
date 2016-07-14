<?php

class FileFinderImplementation
{
    public $dirNames;
    public $type;

    public $matches;

    public $_list;

    /**
     * @return $this
     */
    public function isFile(){
        $this->type = 'file';
        return $this;
    }

    /**
     * @return $this
     */
    public function isDir(){
        $this->type = 'dir';
        return $this;
    }

    /**
     * @param $dir
     * @return $this
     */
    public function inDir($dir){
        $this->dirNames[] = $dir;
        return $this;
    }

    /**
     * @param $match
     * @return $this
     */
    public function match($match){
        $this->matches[] = $match;
        return $this;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getList(){
        $this->run();
        return $this->_list;
    }

    /**
     * Entry checks for compliance with the expression
     * @param $entry
     */
    private function checkMatch($entry){
        foreach ($this->matches as $match) {
            if(preg_match($match, $entry, $matches)){
                $this->_list[] = $entry;
            }
        }
    }

    /**
     * search folders / files, checking them against the expression
     * @return array
     * @throws Exception
     */
    public function run(){
        //if not specified DIR
        if($this->dirNames === null){
            throw new Exception ('не указана директория');
        }

        foreach ($this->dirNames as $dir) {
            //if use isFile
            if ($this->type === 'file') {

                //folder isset? if YES
                if (is_dir($dir)) {
                    $handle = opendir($dir);

                    while (false !== ($entry = readdir($handle))) {
                        //is a file?
                        if (is_file($dir . '/' . $entry)){

                            if(isset($this->matches)){//if passed an expression - check him
                                $this->checkMatch($entry);
                            }else{//if no expression -> save all files
                                $this->_list[] = $entry;
                            }

                        }
                    }
                //create an error msg if it's not a folder
                } else {
                    $this->_list['error'] = $dir . ' - No such file or directory.<br>';
                }
            //if use isDir
            } elseif ($this->type === 'dir') {

                //folder isset? if YES
                if (is_dir($dir)) {
                    $handle = opendir($dir);

                    while (false !== ($entry = readdir($handle))) {

                        //is a dir?
                        if (is_dir($dir . '/' . $entry) && $entry !== '.' && $entry !== '..'){
                            if($this->matches !== null){//if passed an expression - check him
                                $this->checkMatch($entry);
                            }else{//if no expression -> save all dirs
                                $this->_list[] = $entry;
                            }
                        }

                    }
                //create an error msg if it's not a folder
                } else {
                    $this->_list['error'] = $dir . ' - No such file or directory.<br>';
                }
            }
        }

        //sort the array if there isset
        if(isset($this->_list) && !isset($this->_list['error'])){
            sort($this->_list, SORT_NATURAL | SORT_FLAG_CASE);
        }else{//create msg that there are no entrys
            $this->_list[] = $dir. ' - no entrys';
        }

        return $this->_list;
    }
}