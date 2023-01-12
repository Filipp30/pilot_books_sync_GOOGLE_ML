<?php

namespace App\Traits;

use App\Models\Enums\Aerodromes;
use Carbon\Carbon;
use DateTime;
use Rexlabs\Enum\Enum;

trait RawCredentialsFormatter
{
    /**
     * @param string $inputStringDate
     * @return DateTime|null
     */
    protected static function getDateFromUnknownFormat(string $inputStringDate): ?DateTime
    {
        $inputStringDate = str_replace('/', '-', $inputStringDate);
        $inputStringDate = str_replace('.', '-', $inputStringDate);

        preg_match('/^(\d{4})\-(\d{2})-(\d{2})$/', $inputStringDate, $result);
        if (!empty($result)) {
            return DateTime::createFromFormat('Y-m-d', $inputStringDate);
        }
        preg_match('/^(\d{2})\-(\d{2})-(\d{4})$/', $inputStringDate, $result);
        if (!empty($result)) {
            return DateTime::createFromFormat('d-m-Y', $inputStringDate);
        }
        preg_match('/^(\d{2})\-(\d{2})-(\d{2})$/', $inputStringDate, $result);
        if (!empty($result)) {
            return DateTime::createFromFormat('d-m-y', $inputStringDate);
        }

        return null;
    }

    /**
     * @param string $inputStringTime
     * @return Carbon|null
     */
    protected static function getTimeFromUnknownFormat(string $inputStringTime): ?Carbon
    {
        $inputStringTime = str_replace('h', ':', $inputStringTime);
        $inputStringTime = str_replace('u', ':', $inputStringTime);
        $inputStringTime = str_replace('m', '', $inputStringTime);
        $inputStringTime = str_replace('/', ':', $inputStringTime);
        $inputStringTime = str_replace('.', ':', $inputStringTime);
        $inputStringTime = str_replace(',', ':', $inputStringTime);
        $inputStringTime = str_replace(' ', ':', $inputStringTime);

        preg_match('/^(\d{2}):(\d{2})$/', $inputStringTime, $result);
        if (!empty($result)) {
            return Carbon::createFromTimeString($inputStringTime);
        }
        preg_match('/^(\d{1}):(\d{2})$/', $inputStringTime, $result);
        if (!empty($result)) {
            return Carbon::createFromTimeString($inputStringTime);
        }
        preg_match('/^(\d{2}):(\d{1})$/', $inputStringTime, $result);
        if (!empty($result)) {
            return Carbon::createFromTimeString($inputStringTime);
        }

        return null;
    }

    /**
     * @param string $inputStringAerodrome
     * @return Enum<Aerodromes>|null
     */
    protected static function getAerodromeFromUnknownFormat(string $inputStringAerodrome): ?Enum
    {
        if (Aerodromes::tryFrom($inputStringAerodrome) == null) {
            $onlyAlphabetic = strtoupper(preg_replace("/[^A-Za-z ]/", '', $inputStringAerodrome));

            return Aerodromes::tryFrom($onlyAlphabetic);
        }

        return Aerodromes::tryFrom($inputStringAerodrome);
    }
}
