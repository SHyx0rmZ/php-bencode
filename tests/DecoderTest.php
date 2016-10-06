<?php

namespace SHyx0rmZ\Bencode\Test;

use SHyx0rmZ\Bencode\Decoder;

final class DecoderTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var Decoder
     */
    private $decoder;

    public function setUp() {
        $this->decoder = new Decoder();
    }

    public function testCanDecodeZeroInteger() {
        $this->assertEquals(0, $this->decoder->decode('i0e'));
    }

    public function testCanDecodePositiveInteger() {
        $this->assertEquals(1337, $this->decoder->decode('i1337e'));
    }

    public function testCanDecodeNegativeInteger() {
        $this->assertEquals(-81, $this->decoder->decode('i-81e'));
    }

    public function testCanDecodeEmptyString() {
        $this->assertEquals('', $this->decoder->decode('0:'));
    }

    public function testCanDecodeNonEmptyString() {
        $this->assertEquals('Hello, world!', $this->decoder->decode('13:Hello, world!'));
    }

    public function testCanDecodeEmptyList() {
        $this->assertEquals([], $this->decoder->decode('le'));
    }

    public function testCanDecodeNonEmptyList() {
        $this->assertEquals([ 'a', 'b', 'c' ], $this->decoder->decode('l1:a1:b1:ce'));
    }

    public function testCanDecodeEmptyDictionary() {
        $this->assertEquals(new \stdClass(), $this->decoder->decode('de'));
    }

    public function testCanDecodeNonEmptyDictionary() {
        $object= new \stdClass();
        $object->a = 1;
        $object->b = 2;
        $object->c = 3;

        $this->assertEquals($object, $this->decoder->decode('d1:ai1e1:bi2e1:ci3ee'));
    }
}
