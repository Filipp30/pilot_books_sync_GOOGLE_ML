<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class UlmBook extends Model
{
    use HasUuids;

    protected $table = 'ulm_book';
    protected $primaryKey = 'id';

    protected $fillable = [
        'date',
        'departure_place',
        'departure_time',
        'arrival_place',
        'arrival_time',
        'aircraft_model',
        'aircraft_registration',
        'single_pilot_time_se',
        'single_pilot_time_me',
        'multi_pilot_time',
        'total_time_of_flight',
        'name_pic',
        'landings_day',
        'landings_night',
        'operational_condition_time_night',
        'operational_condition_time_ifr',
        'pft_pic',
        'pft_co_pilot',
        'pft_dual',
        'pft_instructor',
        'fstd_session_date',
        'fstd_session_type',
        'fstd_session_total_time',
        'remarks_and_endorsements',
        'errors',
    ];
}
