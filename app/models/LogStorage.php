<?php

class LogStorage {

    /**
     * Return all log file paths with filename as array key.
     * @return array
     */
    public static function all()
    {
        $files = glob(storage_path().'/logs/log-*.txt');
        $logs = [];
        $sorted = [];
        
		foreach ($files as $file) {
			$sorted[filectime($file)] = $file;
		}
        
		krsort($sorted, SORT_NUMERIC);
		
		foreach ($sorted as $file) {
            $logs[basename($file)] = new static($file);
        }
		
        return $logs;
    }

    /**
     * Find file and return instance of LogStorage
     * @param  string $filename
     * @return LogStorage
     */
    public static function find($filename)
    {
        return array_get(static::all(), $filename);
    }

    /**
     * Storage path
     * @var string
     */
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function exists()
    {
        return file_exists($this->path);
    }

    public function getFilename()
    {
        return basename($this->path);
    }

    public function getContent()
    {
        $logs = array();
        foreach (file($this->path) as $line) {
            $line = trim($line);
            if (preg_match('/^\[(.+?)\] log\./', $line)) {
                $logs[] = array(
                    'name' => $line,
                    'details' => [],
                );
            } else {
                end($logs);
                $logs[key($logs)]['details'][] = $line;
            }
        }

        return $logs;
    }

    /**
     * Magic get function
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (method_exists($this, 'get'.studly_case($key))) {
            return $this->{'get'.studly_case($key)}($key);
        }

        return parent::__get($key);
    }

}
