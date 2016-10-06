<?php

namespace SHyx0rmZ\Bencode;

class Decoder {
    /**
     * @param string $data
     * @return string|integer|array|\stdClass
     */
    public function decode($data) {
        list($value, $rest) = $this->decodeValue($data);

        return $value;
    }

    /**
     * @param string $data
     * @return string|integer|array|\stdClass
     */
    protected function decodeValue($data) {
        switch ($data[0]) {
            case 'd':
                return $this->decodeDictionary(substr($data, 1), new \stdClass());
            case 'i':
                return $this->decodeInteger(substr($data, 1), 'e');
            case 'l':
                return $this->decodeList(substr($data, 1), []);
            default:
                return $this->decodeString($data);
        }
    }

    /**
     * @param string $data
     * @param \stdClass $map
     * @return array
     */
    protected function decodeDictionary($data, $map) {
        if ('e' == $data[0]) {
            return [ $map, substr($data, 1) ];
        }

        list($key, $rest1) = $this->decodeString($data);
        list($value, $rest2) = $this->decodeValue($rest1);

        $map->{$key} = $value;

        return $this->decodeDictionary($rest2, $map);
    }

    /**
     * @param string $data
     * @param string $delimiter
     * @return array
     */
    protected function decodeInteger($data, $delimiter) {
        list($digits, $rest) = explode($delimiter, $data, 2);

        return [ intval($digits), $rest ];
    }

    /**
     * @param string $data
     * @param array $list
     * @return array
     */
    protected function decodeList($data, $list) {
        if ('e' == $data[0]) {
            return [ $list, substr($data, 1) ];
        }

        list($element, $rest) = $this->decodeValue($data);

        $list[] = $element;

        return $this->decodeList($rest, $list);
    }

    /**
     * @param string $data
     * @return array
     */
    protected function decodeString($data) {
        list($length, $rest) = $this->decodeInteger($data, ':');

        return [ substr($rest, 0, $length), substr($rest, $length) ];
    }
}
