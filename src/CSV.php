<?php

declare(strict_types=1);

namespace Krbeasley\PhpCsv;

use PHPUnit\Framework\TestStatus\Warning;

class CSV
{
    protected string $delimiter;
    protected string $file_path;
    protected ?array $headers;
    protected ?array $contents;

    public function __construct(string $file_path, string $delimiter = ',') {
        $this->file_path = $file_path;
        $this->delimiter = $delimiter;
        $this->headers = null;
        $this->contents = null;
    }

    public function read(bool $include_headers = true) : CSV {
        if (!$file_contents = file_get_contents($this->file_path)) {
            throw new \Exception('Unable to read file ' . $this->file_path);
        }

        // split the string on new lines
        if (!preg_match_all("/[\S ]+/", $file_contents, $content_array)){
            throw new \Exception('There was an error parsing the input file. File: ' . $this->file_path); 
        }

        //  Remove outer match array.
        //  matches = [[$matches]]
        $content_array = $content_array[0]; 

        // Split each line on the delimiter
        for ($i = 0; $i < count($content_array); $i++) {
            $content_array[$i] = $this->splitLine($content_array[$i]);
        }

        if ($include_headers) { 
            // grab the first line as headers
            $this->headers = array_shift($content_array);
        }

        // set the contents
        $this->contents = $content_array;

        return $this;
    }

    public function getHeaders() : array {
        return $this->headers;
    }

    public function getContents() : array {
        return $this->contents;
    }

    /** Split a string upon the CSV object's delimter. ',' by default.
    *
    * @param string $line
    * @return array
    */
    public function splitLine(string $line) : array
    {
        // echo "SPLIT: " . $line . PHP_EOL;
        $line_arr = [];

        // Loop through the string until you stop coming across delimiters, each time appending
        // the the text that preceeds the delimiter to the line array. That preceeding text is
        // then stripped from the line before the line goes through this cycle again.

        while(($del_location = strpos($line, $this->delimiter)) !== false) {

            // Update the delimiter location to the next delimiter if the current one is escaped. 
            if ($this->charIsEscaped($line, $this->delimiter) ||
                $this->charIsQuoted($line)) {
                $offset = $del_location + 1;
                $del_location = strpos($line, $this->delimiter, $offset);
            }

            // Take the text that preceeds the delimter and remove it from the original line.
            $first = substr($line, 0, $del_location);
            $line = substr($line, $del_location + 1);

            // Append the preceeding text to the line array.
            $line_arr[] = trim($first);

            // Loop again :)
        }

        // After all delimters are exhausted, append what's left of the line to the line array
        $line_arr[] = trim($line);

        return $line_arr;
    }


    /** Check if the first ocurrence of a specified char is preceeded by a '\' escape character.
    *   Returns a `null` value when the specified char cannot be located.
    *
    * @param string $string -- Haystack
    * @param string $char   -- Needle
    * @return ?bool
    */
    public function charIsEscaped(string $string, string $char) : ?bool {
        if (!$char_index = strpos($string, $char)) {
            return null;
        }

        $prev_char = substr($string, $char_index - 1, 1);

        // This checks if the previous character is the escape character then checks again to 
        // ensure the escape character is itself not escaped.  
        if ($prev_char === '\\') {
            $prev_prev_char = substr($string, $char_index - 2, 1);
            return $prev_prev_char != '\\';
        }

        return false;
    }

    /** Check if the string starts or ends with single or double quotes. This is should be used only
    *   when parsing CSV's or other delimited 'spreadsheet' files.
    *
    * @param string $string
    */
    public function charIsQuoted(string $string) : bool {
        if (str_starts_with($string, '"') || str_ends_with($string, '"')) {
            return true;
        } else if (str_starts_with($string, "'") || str_ends_with($string, "'")) {
            return true;
        }


        return false;
    }

    /** Get the all of the values for a specified column index
    *
    * @param int $col_index
    * @returns array
    */
    public function getColumnValues(int $col_index) : ?array {
        $col_values = [];

        foreach ($this->contents as $item) {
            try {
                $col_values[] = $item[$col_index];
            } catch (\Exception) {
                return null;
            }
        }

        return $col_values;
    }

    /** Get all the values for a named column. Throws error if there are not defined headers
    *   for the current CSV. 
    *
    * @param string $col_name
    * @returns ?array
    * @throws \Exception
    */
    public function getNamedColumnValues(string $col_name) : ?array {
        if (is_null($this->headers)) {
            throw new \Exception("There are no defined column names for this spreadsheet.");
        }
        
        for ($i = 0; $i < count($this->headers); $i++) {
            if ($this->headers[$i] === $col_name) {
                return $this->getColumnValues($i);
            }
        }

        return null;
    }
}
