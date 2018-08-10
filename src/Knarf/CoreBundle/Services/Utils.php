<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Knarf\CoreBundle\Services;

/**
 * Description of Utils
 *
 * @author franck
 */
class Utils 
{
    /**
     * @param array $dirs
     * @return array
     */
    public function getBundlesList(array $dirs = ['src'])
    {
        $folders = array();
        foreach ($dirs as $dir) {
            $currentDirectory = getcwd();
            if (false !== strpos($currentDirectory, 'web')){
                $dir = '../' . $dir;
            }
            if (is_dir($dir)) {
                $subDir = scandir($dir);
                foreach ($subDir as $folder) {
                    if (strpos($folder, '.') === false && is_dir($dir.DIRECTORY_SEPARATOR.$folder)) {
                        $folders[] = $folder;
                    }
                }
            }
        }
        return $folders;
    }
}
