<?php

namespace App\Supports;

/**
 * Class FieldsParser
 * @package App\Support
 */
class FieldsParser
{

    public function parse($input): array
    {
        $chars = str_split($input);
        return [
            'fields' => $this->parseFields($chars)
        ];
    }

    protected function parseFields(array $chars): array
    {
        $end = count($chars);
        $current = '';
        $output = [];
        $i = 0;
        while ($i < $end) {
            $char = $chars[$i];
            switch ($char) {
                case ',':
                    $trimmedName = trim($current);
                    if ($trimmedName && !array_key_exists($trimmedName, $output)) {
                        $output[$trimmedName] = true;
                    }
                    $current = '';
                    break;
                case '{':
                    $trimmedName = trim($current);
                    if (empty($trimmedName)) {
                        throw new \InvalidArgumentException('Nested operator {} do not match');
                    }
                    $nestedLength = $this->getNestedLength($chars, $i);
                    $output[$trimmedName] = array_key_exists($trimmedName, $output) ? $output[$trimmedName] : [];
                    $nested = array_slice($chars, $i + 1, $nestedLength - 2);
                    $output[$trimmedName]['fields'] = $this->parseFields($nested);
                    $current = '';
                    $i += $nestedLength;
                    break;
                case '.':
                    $trimmedName = trim($current);
                    $methodLength = $this->getMethodLength($chars, $i);
                    $output[$trimmedName] = array_key_exists($trimmedName, $output) ? $output[$trimmedName] : [];
                    $method = array_slice($chars, $i + 1, $methodLength - 1);
                    list($methodName, $param) = $this->parseMethod($method);
                    $output[$trimmedName][$methodName] = $param;
                    $i += $methodLength - 1;
                    break;
                default:
                    $current .= $char;
            }
            $i++;
        }
        $trimmedName = trim($current);
        if ($trimmedName && !array_key_exists($trimmedName, $output)) {
            $output[$trimmedName] = true;
        }
        return $output;
    }

    protected function getNestedLength(array $chars, $offset)
    {
        $nested = 0;
        $end = count($chars);
        $i = $offset;
        while ($i < $end) {
            $char = $chars[$i];
            switch ($char) {
                case '{':
                    $nested++;
                    break;
                case '}':
                    $nested--;
                    if ($nested === 0) {
                        return $i + 1 - $offset;
                    }
                    break;
            }
            $i++;
        }
        throw new \InvalidArgumentException('Nested operator {} do not match');
    }

    protected function getMethodLength(array $chars, $offset)
    {
        $pos = strpos(join($chars), ')', $offset);
        if ($pos > $offset) {
            return $pos - $offset + 1;
        } else {
            throw new \InvalidArgumentException('Method operator () do not match');
        }
    }

    protected function parseMethod(array $chars): array
    {
        $str = join($chars);
        $re = '/(\w+)\(([-\w, ]+)\)/';
        preg_match_all($re, $str, $matches);
        $methodName = $matches[1][0];
        $param = $matches[2][0];
        switch ($methodName) {
            case 'order':
            case 'limit':
                return [$methodName, $param];
            default:
                throw new \InvalidArgumentException('Method name "' . $methodName . '" is not a valid name');
        }
    }
}
