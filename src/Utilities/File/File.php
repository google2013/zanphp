<?php
namespace Zan\Framework\Utilities\File;
use Zan\Framework\Foundation\Contract\Async;

/**
 * Created by PhpStorm.
 * User: suqian
 * Date: 16/7/28
 * Time: 下午3:04
 */
class File implements Async
{
    CONST READ = 0;
    CONST WRITE = 1;

    protected $fileName = '';

    protected $size = 8000;

    protected $offset = 0;

    protected $offsetChanged = false;

    protected $callback = null;

    protected $isEof = false;

    protected $handle = 0;

    protected $content = '';

    public function __construct($fileName){
        $this->fileName = $fileName;
    }

    public function read($length = 8000){
        $this->handle = self::READ;
        $this->size = $length;
        yield $this;
    }

    public function eof(){
        yield $this->isEof;
    }

    public function seek($offset = -1){
        $this->offset = $offset;
        $this->offsetChanged = true;
    }

    public function tell(){
        yield $this->offset;
    }

    public function write($content){
        if($this->offset == 0 && !$this->offsetChanged) {
            $this->offset = -1;
        }
        $this->handle = self::WRITE;
        $this->content = $content;
        yield $this;
    }

    public function execute(callable $callback, $task){
        if($this->handle == self::READ) {
            $this->setCallback($this->getReadCallback($callback))->readHandle();
        }else{
            $this->setCallback($this->getWriteCallback($callback))->writeHandle();
        }
    }

    public function readHandle(){
        swoole_async_read($this->fileName,$this->callback,$this->size,$this->offset);
    }

    public function writeHandle(){
        swoole_async_write($this->fileName,$this->content, $this->offset, $this->callback);
    }

    public function setCallback(callable $callback){
        $this->callback = $callback;
        return $this;
    }

    private function getReadCallback(callable $callback)
    {
        return function($fileName,$content) use ($callback) {
            $len = strlen($content);
            $this->offset += $len;
            if($len < $this->size){
                $this->isEof = true;
            }
            call_user_func($callback, $content);
        };
    }

    private function getWriteCallback(callable $callback)
    {
        return function($response,$contentLength) use ($callback) {
            $response = $response ? true : false;
            call_user_func($callback, $response);
        };
    }
}