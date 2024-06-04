<?php

namespace WeltPixel\AdvanceCategorySorting\Plugin;

class Utility extends \WeltPixel\Backend\Plugin\Utility
{
    /**
     * Get Module Name
     *
     * @return string
     */
    protected function getModuleName()
    {
        return $this->convertToString(
            [
                '87', '101', '108', '116', '80', '105', '120', '101', '108', '95', '65', '100', '118', '97', '110',
                '99', '101', '67', '97', '116', '101', '103', '111', '114', '121', '83', '111', '114', '116', '105',
                '110', '103'
            ]
        );
    }

    /**
     * Get Admin Paths
     *
     * @return array
     */
    protected function _getAdminPaths()
    {
        return [
            $this->convertToString(
                [
                    '115', '121', '115', '116', '101', '109', '95', '99', '111', '110', '102', '105', '103', '47',
                    '101', '100', '105', '116', '47', '115', '101', '99', '116', '105', '111', '110', '47', '119',
                    '101', '108', '116', '112', '105', '120', '101', '108', '95', '97', '100', '118', '97', '110',
                    '99', '101', '95', '99', '97', '116', '101', '103', '111', '114', '121', '95', '115', '111',
                    '114', '116', '105', '110', '103'
                ]
            )
        ];
    }
}
