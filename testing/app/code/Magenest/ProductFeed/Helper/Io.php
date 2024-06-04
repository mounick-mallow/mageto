<?php

/**
 * Copyright © 2020 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_ProductFeed extension
 * NOTICE OF LICENSE
 *
 * @category Magenest
 * @package Magenest_ProductFeed
 */

namespace Magenest\ProductFeed\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\LocalizedException;

class Io extends AbstractHelper
{
    /**
     * Write content to file
     *
     * @param string $filename
     * @param string $content
     * @param string $mode
     * @return $this
     * @throws \Exception
     */
    public function write($filename, $content, $mode = 'w')
    {

        $wait = true;
        $fp = fopen($filename, $mode);
        if ($this->isWin()) {
            fwrite($fp, $content);
        } else {
            flock($fp, LOCK_SH, $wait);
            fwrite($fp, $content);
            flock($fp, LOCK_UN);
        }
        fclose($fp);

        chmod($filename, 0777);

        if (!$this->fileExists($filename)) {
            throw new LocalizedException(__("File %1 not created.", $filename));
        }

        return $this;
    }

    /**
     * Copy file from one place to another
     *
     * @param string $from
     * @param string $to
     * @return $this
     * @throws \Exception
     */
    public function copy(string $from, string $to)
    {
        if (!$this->fileExists($from)) {
            throw new LocalizedException(__("File %1 not exists.", $from));
        }

        copy($from, $to);
        chmod($to, 0777);

        if (!$this->fileExists($to)) {
            throw new LocalizedException(__("File %1 not copied to %2", $from, $to));
        }

        return $this;
    }

    /**
     * Is file exists?
     *
     * @param string $file
     * @return bool
     */
    public function fileExists(string $file): bool
    {
        $result = file_exists($file);
        if ($result) {
            $result = is_file($file);
        }

        return $result;
    }

    /**
     * Is directory exists?
     *
     * @param string $path
     * @return bool
     */
    public function dirExists(string $path): bool
    {
        $result = file_exists($path);
        if ($result) {
            $result = is_dir($path);
        }

        return $result;
    }

    /**
     * Remove file
     *
     * @param string $file
     * @return bool
     */
    public function unlink(string $file): bool
    {
        return unlink($file);
    }

    /**
     * Create directory (recursive)
     *
     * @param string $dir
     * @param int    $mode
     * @param bool   $recursive
     * @return bool
     */
    public function mkdir(string $dir, int $mode = 0777, bool $recursive = true): bool
    {
        $result = mkdir($dir, $mode, $recursive);

        if ($result) {
            chmod($dir, $mode);
        }

        return $result;
    }

    /**
     * Remove directory
     *
     * @param string $dir
     * @param bool   $recursive
     * @return bool
     * @throws \Exception
     */
    public function rmdir(string $dir, bool $recursive = true): bool
    {
        if (!$this->dirExists($dir)) {
            return true;
        }

        $result = self::rmdirRecursive($dir, $recursive);

        if (!$result) {
            throw new LocalizedException(__("Can't remove folder %1", $dir));
        }

        return $result;
    }

    /**
     * Remove directory
     *
     * @param string $dir
     * @param bool   $recursive
     * @return bool
     */
    public function rmdirRecursive(string $dir, bool $recursive = true): bool
    {
        if ($recursive) {
            if (is_dir($dir)) {
                foreach (scandir($dir) as $item) {
                    if (!strcmp($item, '.') || !strcmp($item, '..')) {
                        continue;
                    }
                    $this->rmdirRecursive($dir . '/' . $item, $recursive);
                }
                $result = rmdir($dir);
            } else {
                $result = unlink($dir);
            }
        } else {
            $result = rmdir($dir);
        }

        return $result;
    }

    /**
     * Check if current OS is Windows
     *
     * @return bool
     */
    public function isWin(): bool
    {
        return (strtolower(substr(PHP_OS, 0, 3)) == 'win') ? true : false;
    }
}
