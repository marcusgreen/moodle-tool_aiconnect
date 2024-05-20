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
 * A lightweight mainly confirming installation works
 *
 * @package    tool_aiconnect
 * @copyright  2024 Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_aiconnect;

/**
 * Basic setup and test run to confirm it installs
 *
 * @package tool_aiconnect
 */
class test_aiconnect extends \advanced_testcase {

    /**
     * Where most of the functionality lives
     *
     * @var ai $ai
     *
     */

    /**
     * The class with most of the functionality
     * @var ai
     */
     public $ai;

    /**
     * Initialise everything
     *
     * @return void
     */
    public function setUp(): void {
        $this->ai = new ai\ai();
    }
    /**
     * Work around the get_prompt_data method
     * being private
     *
     * @return void
     */
    public function test_get_prompt_data() :void {
         $this->assertTrue(true);
         $mockai = $this->getMockBuilder(ai\ai::class)
            // ->disableOriginalConstructor()    // you may need the constructor on integration tests only
             ->getMock();
         $getpromptdata = new \ReflectionMethod(
                 ai\ai::class,
                 'get_prompt_data'
             );
         $getpromptdata->setAccessible(true);

         $result = $getpromptdata->invokeArgs(
             $mockai,
             ['myprompt']
         );
         $this->assertStringContainsString("You: myprompt", $result['messages'][0]['content']);
     }

     /**
     * This doesn't do anything especially useful.
     * @return void
     */
    public function test_prompt_completion() :void {
        $result = $this->ai->prompt_completion('query');
        $this->assertIsArray($result);
    }
}
