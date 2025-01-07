<?php

/**
 * Sebastien Tille
 * Date: January 7th, 2025
 */

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../src/helpers/FormatHelper.php';

/**
 * This class is used to test the FormatHelper class,
 */
class FormatHelperTest extends TestCase {

    /**
     * Tests the getFullDate method.
     */
    public function testGetFullDate() {
        $date1 = '2025-01-07';
        $date2 = '1950-02-02';

        $expected1 = '7 January 2025';
        $expected2 = '2 February 1950';

        $actual1 = FormatHelper::getFullDate($date1);
        $actual2 = FormatHelper::getFullDate($date2);

        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
    }
}
