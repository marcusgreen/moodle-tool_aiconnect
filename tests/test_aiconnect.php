<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * TODO describe file test_aiconnect
 *
 * @package    tool_aiconnect
 * @copyright  2024 2924 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_aiconnect\ai;

use SebastianBergmann\RecursionContext\InvalidArgumentException;
use PHPUnit\Framework\ExpectationFailedException;

class test_aiconnect extends \advanced_testcase {
    /**
     *
     * @var \stdClass $ai
     */
    public $ai;

    public function setUp(): void {
        $this->ai = new ai();
    }
    /**
     * This doesn't do anything especially useful.
     * @return void
     */
    public function test_prompt_completion() :void{
        $result = $this->ai->prompt_completion('query');
        $this->assertIsArray($result);
    }
}
