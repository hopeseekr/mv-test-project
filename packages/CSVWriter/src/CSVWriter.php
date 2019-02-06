<?php

namespace CSVWriter;

use Illuminate\Support\Collection;

interface CSVWriter
{
    public function collectionToCsv(Collection $collection): string;
}
