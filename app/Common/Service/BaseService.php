<?php

namespace App\Common\Service;


abstract class BaseService {
    /**
     *  @param string $class_name
     *  @param string $column
     *  @param string $prefix
     *  @param string $numbers
     *
     *  @return string $auto_number
    */
    protected function generateAutoNumber(string $class_name, string $prefix, string $numbers, string $column): string
    {
        $numberLength = substr_count($numbers, '?');

        do {
            // Get the latest number with the same prefix
            $lastRecord = $class_name::where($column, 'LIKE', $prefix . '%')
                ->orderBy($column, 'desc')
                ->first();

            // Extract numeric part and increment
            if ($lastRecord && preg_match('/\d+$/', $lastRecord->$column, $matches)) {
                $nextNumber = (int) $matches[0] + 1;
            } else {
                $nextNumber = 1;
            }

            // Pad number with zeros to fit the pattern (e.g., 000001)
            $paddedNumber = str_pad($nextNumber, $numberLength, '0', STR_PAD_LEFT);

            $autoNumber = $prefix . $paddedNumber;

            // Check if this auto number is unique
            $exists = $class_name::where($column, $autoNumber)->exists();

        } while ($exists);

        return $autoNumber;
    }
}
