<?php

declare(strict_types=1);

namespace Krbeasley\PhpCsv;

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

    public function read() : CSV {
        if (!$file_contents = file_get_contents($this->file_path)) {
            throw new \Exception('Unable to read file ' . $this->file_path);
        }

        // split the string on new lines
        preg_match_all("/[\S ]+/", $file_contents, $content_array);

        // grab the first element as headers
        $header_string = $content_array[0];
        unset($content_array[0]); // remove the headers leaving just the content

        return $this;
    }
}