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
 * AI class
 *
 * @package    tool_aiconnect
 * @copyright  2024 Marcus Green
 * @author     Marcus Green
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace tool_aiconnect\ai;

use curl;
use moodle_exception;

class ai {

    private string $openaiapikey;

    private $model;
    private float $temperature;

    private string $endpoint;

    public function __construct($model = null) {
        $this->model = $model ?? get_config('tool_aiconnect', 'model');
        $this->openaiapikey = get_config('tool_aiconnect', 'apikey');
        $this->temperature = get_config('tool_aiconnect', 'temperature');
        $this->endpoint = get_config('tool_aiconnect', 'endpoint');
    }

    /**
     * Makes a request to the specified URL with the given data and API key.
     *
     * @param string $url The URL to make the request to.
     * @param array $data The data to send with the request.
     * @param string $apikey The API key to authenticate the request.
     * @return array The response from the request.
     * @throws moodle_exception If the API key is empty.
     */
    private function make_request($data, $apikey, $multipart = null) {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');
        if (empty($apikey)) {
            throw new moodle_exception('emptyapikey', 'tool_aiconnect', '', null,
                'Empty API Key.');
        }
        $headers = $multipart ? [
            "Content-Type: multipart/form-data"
        ] : [
            "Content-Type: application/json;charset=utf-8"
        ];

        $headers[] = "Authorization: Bearer $apikey";
        $curl = new curl();
        $options = [
            "CURLOPT_RETURNTRANSFER" => true,
            "CURLOPT_HTTPHEADER" => $headers,
        ];
        $start = microtime(true);

        $response = $curl->post($this->endpoint, json_encode($data), $options);

        $end = microtime(true);
        $executiontime = round($end - $start, 2);

        if (json_decode($response) == null) {
            return ['curl_error' => $response, 'execution_time' => $executiontime];
        }
        return ['response' => json_decode($response, true), 'execution_time' => $executiontime];
    }

    /**
     * Generates a completion for the given prompt text.
     *
     * @param string $prompttext The prompt text.
     * @return string|array The generated completion or null if the model is empty.
     * @throws moodle_exception If the model is empty.
     */
    public function prompt_completion($prompttext) {
        if (empty($this->model)) {
            throw new moodle_exception('misssingmodelerror', 'tool_aiconnect', '', null, 'Empty query model.');
        }
        $data = $this->get_prompt_data($prompttext);
        $result = $this->make_request($data, $this->openaiapikey);

        if (isset($result['choices'][0]['text'])) {
            return $result['choices'][0]['text'];
        } else if (isset($result['choices'][0]['message'])) {
            return $result['choices'][0]['message'];
        } else {
            return $result;
        }
    }

    /**
     * Retrieves the data for the prompt based on the URL and prompt text.
     *
     * @param string $url The prompt URL.
     * @param string $prompttext The prompt text.
     * @return array The prompt data.
     */
    private function get_prompt_data($prompttext) : array {
            $data = [
                'model' => $this->model,
                'temperature' => $this->temperature,
                'messages' => [
                    ['role' => 'system', 'content' => 'You: ' . $prompttext],
                ],
            ];
            return $data;
    }

}

