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
 * Test page
 *
 * @package    tool_aiconnect
 * @copyright  2024 Marcus Green
 * @author     Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_aiconnect;

require_once(__DIR__ . '/../../../config.php');

use \context_system;

require_login();
if (!is_siteadmin($USER)) {
    throw new \require_login_exception('login required');
}
$PAGE->set_context(context_system::instance());

defined('MOODLE_INTERNAL') || die();

$ai = new ai\ai();

$gptresult = $ai->prompt_completion('Calculate the value of 2 times 4');

if ($gptresult && !isset($gptresult['curl_error'])) {
    if (isset($gptresult['error'])) {
        $gptinfo = "Inactive 🔴</br> Error message: " . $gptresult['error']['message'] . "</br>";
        $gptinfo .= "Error type: " . $gptresult['error']['type'] . "</br>";
        $gptinfo .= "Param: " . $gptresult['error']['param'] . "</br>";
        $gptinfo .= "Code: " . $gptresult['error']['code'] . "</br>";
    } else {
        $gptinfo = "Active 🟢";
    }
} else {
    $gptinfo = "Inactive 🔴, cURL error: " . $gptresult['curl_error'];
}
$PAGE->set_url('/local/tool_aiconnect/classes/ai/test.php');

echo $OUTPUT->header();
?>

    <style>
        table {
            border: 1px solid black;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>

    <table>
        <tr>
            <th>LLM status</th>
        </tr>
        <tr>
            <td><?php echo $gptinfo; ?></td>
        </tr>
        <tr>
            <td>Execution time: <?php echo $gptresult['execution_time'];?> ms</td>
        </tr>
    </table>
    <?php
    echo $OUTPUT->footer();
