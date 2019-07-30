<?php

namespace AppBundle\Service;


class Counter
{
    /** @var string */
    private $filepath;

    /**
     * @param string $filepath
     */
    public function __construct($filepath)
    {
        $this->filepath = $filepath;

        if (!is_file($this->filepath)) {
            $this->createFile();
        }
    }

    public function increase()
    {
        $num = (int) file_get_contents($this->filepath) + 1;
        file_put_contents($this->filepath, $num);
    }

    /**
     * @return int
     */
    public function get()
    {
        return (int) file_get_contents($this->filepath);
    }

    private function createFile()
    {
        $file = fopen($this->filepath, 'w+');
        file_put_contents($this->filepath, 0);
        fclose($file);
    }
}
