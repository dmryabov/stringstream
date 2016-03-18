<?php
/**
 * @author: Viskov Sergey
 * @date: 3/18/16
 * @time: 1:15 PM
 */

namespace LTDBeget\stringstream;


use LTDBeget\ascii\AsciiChar;

/**
 * String stream data structure
 * Class StringStream
 * @package LTDBeget\stringstream
 */
class StringStream
{
    /**
     * StringStream constructor.
     * @param string $string
     */
    public function __construct(string $string)
    {
        if(empty($string)) {
            throw new \BadMethodCallException("Cannot make stream from empty string");
        }

        $this->originalString = $string;
        $this->stream = unpack('C*', $string);
        $this->length = count($this->stream);

        $this->pointerAtStart = true;
        $this->pointerAtEnd = false;
    }

    /**
     * Original string data of stream
     * @return string
     */
    public function getString()
    {
        return $this->originalString;
    }

    /**
     * Length of string
     * @return int
     */
    public function length()
    {
        return $this->length;
    }

    /**
     * Current char of stream
     * @return string
     */
    public function current() : string
    {
        return chr(current($this->stream));
    }

    /**
     * Current char of stream as AsciiChar
     * @return AsciiChar
     */
    public function currentAscii() : AsciiChar
    {
        return AsciiChar::get(current($this->stream));
    }

    /**
     * Position in stream of current char
     * @return int
     */
    public function position() : int
    {
        return key($this->stream);
    }

    /**
     * go to next char in stream
     */
    public function next()
    {
        $this->pointerAtStart = false;
        $this->pointerAtEnd = next($this->stream) === false;
    }

    /**
     * go to previous char of stream
     */
    public function previous()
    {
        $this->pointerAtEnd = false;
        $this->pointerAtStart = prev($this->stream) === false;
    }

    /**
     * go to start of stream
     */
    public function start()
    {
        reset($this->stream);
    }

    /**
     * is start of stream
     * @return bool
     */
    public function isStart() : bool
    {
        return $this->pointerAtStart;
    }

    /**
     * go to end of stream
     */
    public function end()
    {
        end($this->stream);
    }

    /**
     * is end of stream
     * @return bool
     */
    public function isEnd() : bool
    {
        return $this->pointerAtEnd;
    }

    /**
     * ignore chars in stream while it is white space
     */
    public function ignoreWhitespace()
    {
        ignoreWhitespace:
        if (! $this->isEnd() && $this->currentAscii()->isWhiteSpace()) {
            $this->next();
            goto ignoreWhitespace;
        }
    }

    /**
     * ignore chars in stream while it is horizontal space
     */
    public function ignoreHorizontalSpace()
    {
        ignoreHorizontalSpace:
        if (!$this->isEnd() && $this->currentAscii()->isHorizontalSpace()) {
            $this->next();
            goto ignoreHorizontalSpace;
        }
    }

    /**
     * ignore chars in stream while it is vertical space
     */
    public function ignoreVerticalSpace()
    {
        ignoreHorizontalSpace:
        if (! $this->isEnd() && $this->currentAscii()->isVerticalSpace()) {
            $this->next();
            goto ignoreHorizontalSpace;
        }
    }

    /**
     * @var string
     */
    private $originalString;

    /**
     * @var array
     */
    private $stream;

    /**
     * @var int
     */
    private $length;

    /**
     * if next returns false it member will be true, else false
     * @var bool
     */
    private $pointerAtEnd;

    /**
     * if prev returns false it member will be true, else false
     * @var bool
     */
    private $pointerAtStart;
}