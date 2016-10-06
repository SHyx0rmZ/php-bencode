<?php

namespace SHyx0rmZ\Bencode\Test;

use SHyx0rmZ\Bencode\Encoder;

final class EncoderTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var Encoder
     */
    private $encoder;

    public function setUp() {
        $this->encoder = new Encoder();
    }

    public function testCanEncodeZero() {
        $this->assertEquals('i0e', $this->encoder->encode(0));
    }

    public function testCanEncodePositiveInteger() {
        $this->assertEquals('i1337e', $this->encoder->encode(1337));
    }

    public function testCanEncodeNegativeInteger() {
        $this->assertEquals('i-81e', $this->encoder->encode(-81));
    }

    public function testCanEncodeEmptyString() {
        $this->assertEquals('0:', $this->encoder->encode(''));
    }

    public function testCanEncodeNonEmptyString() {
        $this->assertEquals('13:Hello, world!', $this->encoder->encode('Hello, world!'));
    }

    public function testCanEncodeEmptyArray() {
        $this->assertEquals('le', $this->encoder->encode([]));
    }

    public function testCanEncodeNonEmptyArray() {
        $this->assertEquals('l1:a1:b1:ce', $this->encoder->encode([ 'a', 'b', 'c' ]));
    }

    public function testCanEncodeNonEmptyArrayWithNonConsecutiveKeys() {
        $this->assertEquals('l1:a1:b1:ce', $this->encoder->encode([ 2 => 'a', 'b', 4 => 'c' ]));
    }

    public function testCanEncodeNonEmptyArrayWithNonIntegerKeysAsDictionary() {
        $this->assertEquals('d1:ai1e1:bi2e1:ci3ee', $this->encoder->encode([ 'a' => 1, 'b' => 2, 'c' => 3 ]));
    }

    public function testCanEncodeNonEmptyArrayWithNonIntegerKeysAsDictionaryAndKeysWillBeSorted() {
        $this->assertEquals('d1:ai2e1:bi3e1:ci1ee', $this->encoder->encode([ 'c' => 1, 'a' => 2, 'b' => 3 ]));
    }

    public function testCanNotEncodeNonEmptyArrayWithMixedKeyTypes() {
        $this->expectException(\UnexpectedValueException::class);

        $this->encoder->encode([ 'a' => 5, 5]);
    }

    public function testCanEncodeEmptyStdClass() {
        $this->assertEquals('de', $this->encoder->encode(new \stdClass()));
    }

    public function testCanEncodeNonEmptyStdClass() {
        $object = new \stdClass();
        $object->a = 1;
        $object->b = 2;
        $object->c = 3;

        $this->assertEquals('d1:ai1e1:bi2e1:ci3ee', $this->encoder->encode($object));
    }

    public function testCanEncodeNonEmptyStdClassAndKeysWillBeSorted() {
        $object = new \stdClass();
        $object->c = 1;
        $object->a = 2;
        $object->b = 3;

        $this->assertEquals('d1:ai2e1:bi3e1:ci1ee', $this->encoder->encode($object));
    }

    public function testCanNotEncodeFloats() {
        $this->expectException(\UnexpectedValueException::class);

        $this->encoder->encode(13.37);
    }
}
