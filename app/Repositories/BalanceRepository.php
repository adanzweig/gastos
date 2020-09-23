<?php

namespace App\Repositories;

use App\Models\Balance;
use InfyOm\Generator\Common\BaseRepository;

class BalanceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'amount',
        'type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Balance::class;
    }
}
