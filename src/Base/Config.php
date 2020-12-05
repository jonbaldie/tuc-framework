<?php

namespace Tuc\Base;

class Config
{
    /**
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->configs[$name];
    }

    /**
     * @param string $name
     * @param array $set
     * @return void
     */
    public function register(string $name, array $set): void
    {
        $this->configs[$name] = $set;

        $this->configs = $this->flatten($this->configs);
    }

    /**
     * @param array $array
     * @param string $prefix
     * @return array
     */
    protected function flatten($array, $prefix = ''): array
    {
        $result = [];
        
        foreach($array as $key => $value) {
            if (is_array($value)) {
                $result = $result + $this->flatten($value, $prefix . $key . '.');
            } else {
                $result[$prefix.$key] = $value;
            }
        }

        return $result;
    }
}
