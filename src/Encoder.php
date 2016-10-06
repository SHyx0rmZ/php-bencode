<?php

namespace SHyx0rmZ\Bencode;

class Encoder {
    /**
     * @param string|integer|array|\stdClass $data
     * @return string
     * @throws \UnexpectedValueException
     */
    public function encode($data) {
        if (is_array($data)) {
            list($allStrings, $allIntegers) = array_reduce(array_keys($data), function ($flags, $key) {
                list($allStrings, $allIntegers) = $flags;
                return [ $allStrings && is_string($key), $allIntegers && is_integer($key) ];
            }, [ true, true ]);

            if ($allIntegers) {
                return $this->encodeList($data);
            }
            elseif ($allStrings) {
                return $this->encodeDictionary($data);
            }
            else {
                throw new \UnexpectedValueException();
            }
        }
        elseif (is_object($data) && $data instanceof \stdClass) {
            return $this->encodeDictionary(get_object_vars($data));
        }
        elseif (is_integer($data)) {
            return $this->encodeInteger($data);
        }
        elseif (is_string($data)) {
            return $this->encodeString($data);
        }
        else {
            throw new \UnexpectedValueException();
        }
    }

    /**
     * @param array|\stdClass $data
     * @return string
     */
    protected function encodeDictionary($data) {
        ksort($data, SORT_STRING);
        return 'd' . implode('', array_map(function ($key, $value) { return $this->encodeString($key) . $this->encode($value); }, array_keys($data), $data)) . 'e';
    }

    /**
     * @param integer $data
     * @return string
     */
    protected function encodeInteger($data) {
        return 'i' . (string)$data . 'e';
    }

    /**
     * @param array $data
     * @return string
     */
    protected function encodeList($data) {
        return 'l' . implode('', array_map(function ($element) { return $this->encode($element); }, $data)) . 'e';
    }

    /**
     * @param string $data
     * @return string
     */
    protected function encodeString($data) {
        return strlen($data) . ':' . $data;
    }
}
