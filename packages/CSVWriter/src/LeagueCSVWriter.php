<?php

namespace CSVWriter;

use Illuminate\Support\Collection;
use League\Csv\Writer as LeagueWriter;

class LeagueCSVWriter implements CSVWriter
{
    public function collectionToCsv(Collection $collection): string
    {
        $items = $collection->toArray();

        $csvWriter = LeagueWriter::createFromString('');
        $csvWriter->insertOne(array_keys($items[0]));
        $csvWriter->insertAll($items);

        $csv = $csvWriter->getContent();

        return $csv;
    }
}
