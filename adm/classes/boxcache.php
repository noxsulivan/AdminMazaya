<?php

/* 
*Please make sure that you're using the last version by checking http://code.google.com/p/boxcache
 */

class boxcache {

    protected $path;
    protected $cache;
    protected $debug = false;
    protected $messages = array();
    protected $autoclean = false;
	protected $cachetime; // 5 minutes

    public function __construct($path = null) {
		$this->cachetime = 60 * 60 * 24 * 10; // 5 minutes
        if (is_dir($path) && is_writable($path)) {
            $path = str_replace('\\', '/', $path);
            $path = (substr($path, -1, 1) == '/') ? $path : $path . '/';
            $this->path = $path;
            $this->trowDebug('The script was sucefully configured, your working path is: ' . $path, 2);
        } else {
            $this->trowDebug('You need to enter a valid and writable path to cache files. The path ' . $path . ' is invalid or not writable.', 1);
        }
    }

    public function write($name, $data = null, $expire = null) {
		if(empty($data)){
			$data = ob_get_flush();
		}
        if ($this->autoclean == true) {
            $this->clean();
        }

        if (empty($name) || empty($data)) {
            $this->trowDebug('You are required to enter a valid name for the cache and a valid data to be cached.', 1);
            return false;
        } else {
            $cache['filename'] = diretorio($name);
            $cache['data'] = ($data);
            //$cache['expire'] = empty($expire) ? date('Y-m-d H:i:s', strtotime('+1 day')) : date('Y-m-d H:i:s', strtotime($expire));
            $this->cache = $cache;
            $caching = $this->putContents();
            if ($caching == true) {
                $this->trowDebug(diretorio($name) . ' have been sucefully cached.', 2);
                return true;
            } else {
                $this->trowDebug('Something went wrong wen the script tryed to cache ' . diretorio($name), 1);
                return false;
            }
        }
    }

    public function get($name) {
		return false;
        if (!empty($name)) {
				if (file_exists($this->path . diretorio($name) . '.html') && (time() - $this->cachetime < filemtime($this->path . diretorio($name) . '.html'))) {
            		$data = $this->getContents($this->path . diretorio($name) . '.html');
                    echo $data."<!--".$this->path . diretorio($name) . '.html'." incache since ".date("d/m/Y H:i:s",filemtime($this->path . diretorio($name) . '.html'))." -->";
					return true;
                } else {
                    if (file_exists($this->path . diretorio($name) . '.html')) {
                        @unlink($this->path . md5( diretorio($name) ) . '.html');
                    }
					ob_start();
                    return false;
                }
		} else {
            $this->trowDebug('The name parameter can\'t be empty.', 1);
            ob_start();
			return false;
        }
    }

    protected function putContents() {
        if (!file_exists($this->path . $this->cache['filename'] . '.html')) {
            //file_put_contents($this->path . $this->cache['filename'] . '.html', $this->cache['expire'] . '::' . $this->cache['data']);
            file_put_contents($this->path . $this->cache['filename'] . '.html', $this->cache['data']);
            $this->trowDebug($this->cache['filename'] . ' was succefully cached', 2, $this->cache['data']);
            return true;
        } else {
            $this->trowDebug('The file ' . $this->path . $this->cache['filename'] . '.html' . 'already exists, skiping cache.', 4, $this->cache['data']);
            return false;
        }
    }

    protected function getContents($file) {
        if (file_exists($file)) {
            $this->trowDebug('The file ' . $file . ' have been readed succefully.', 2);
            return file_get_contents( $file );
        } else {
            $this->trowDebug('The file ' . $this->path . diretorio($name) . '.html' . ' doesn\'t exists, skiping.', 4);
            return false;
        }
    }

    public function delete($name) {
        if (file_exists($this->path . md5(diretorio($name)) . '.html')) {
            $this->trowDebug('The file ' . $this->path . md5(diretorio($name)) . '.html' . ' have been deleted succefully.', 2);
            unlink($this->path . md5(diretorio($name)) . '.html');
            return true;
        } else {
            $this->trowDebug('The file ' . $this->path . md5(diretorio($name)) . '.html' . ' doesn\'t exists, skiping.', 4);
            return false;
        }
    }

    public function purge() {
        $files = array_slice(scandir($this->path), 2);
        if (!empty($files)) {
            foreach ($files as $file) {
                if (file_exists($this->path . $file) && stripos('.html', $file) != false) {
                    $this->trowDebug('The file ' . $this->path . $file . ' have been deleted succefully.', 2);
                    unlink($this->path . $file);
                }
            }
        } else {
            $this->trowDebug('There is no files to be erased in the work path.', 4);
        }
    }

    public function clean() {
        $files = array_slice(scandir($this->path), 2);
        if (!empty($files)) {
            foreach ($files as $file) {
                if (file_exists($this->path . $file) && stripos('.html', $file) != false) {
                    $data = file_get_contents($this->path . $file);
                    $data = explode('::', $data);
                    if ($data[0] <= date('Y-m-d H:i:s')) {
                        $this->trowDebug('The file ' . $this->path . $file . ' have been cleaned succefully.', 2);
                        unlink($this->path . $file);
                    }
                }
            }
        } else {
            $this->trowDebug('There is no files to be cleaned in the work path.', 4);
        }
    }

    public function enableDebug() {
        $this->debug = true;
        $this->trowDebug('Debug enabled.', 4);
    }

    public function disableDebug() {
        $this->debug = false;
        $this->trowDebug('Debug disabled.', 4);
    }

    public function debug() {
        return $this->messages;
    }

    protected function trowDebug($msg, $level = 4, $serialize = null) {

        $levels = array(
            0 => 'unknown',
            1 => 'error',
            2 => 'success',
            3 => 'warning',
            4 => 'info'
        );

        if (!empty($msg) && $this->debug == true) {

            $microseconds = microtime();
            $microseconds = explode(' ', $microseconds);
            $microseconds = $microseconds[0];

            $tmp = array(
                'level' => array_key_exists($level, $levels) ? $levels[$level] : $levels[0],
                'message' => $msg,
                'timestamp' => date('Y-m-d H:i:s') . ' (' . $microseconds . ')',
                'data' => !empty($serialize) ? serialize($serialize) : null,
            );
			$this->messages[] = $tmp;
        }
    }

}

?>
