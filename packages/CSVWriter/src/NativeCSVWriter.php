<?php

namespace CSVWriter;

use Illuminate\Support\Collection;

class NativeCSVWriter implements CSVWriter
{
    public function collectionToCsv(Collection $collection): string
    {
        $items = $collection->toArray();

        // Open up a temporary memory file for the CSV.
        $fh = fopen('php://memory', 'w+');

        // Output the columns to CSV:
        fputcsv($fh, array_keys($items[0]));

        // Output it to CSV.
        foreach ($items as $item) {
            fputcsv($fh, $item);
        }

        // Read it back.
        rewind($fh);
        $csv = stream_get_contents($fh);

        // Don't forget to close, or you'll run out of file handlers eventually and
        // have a memleak.
        fclose($fh);

        return $csv;
    }
}
