<?php

namespace src\Integration;

use src\Dtos\InputDto;
use src\Dtos\ResponseDto;

/**
 * @property string $host
 * @property string $user
 * @property string $password
 */
class DataProvider
{
    /**
     * @param string $host
     * @param string $user
     * @param string $password
     */
    public function __construct(
        protected string $host,
        protected string $user,
        protected string $password
    )
    {
    }

    /**
     * @param InputDto $input
     * @return array
     */
    public function get(InputDto $input): ResponseDto
    {
        // returns a response from external service
        return new ResponseDto();
    }
}
