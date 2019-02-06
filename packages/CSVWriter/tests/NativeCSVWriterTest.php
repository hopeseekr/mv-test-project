<?php

namespace CSVWriter\Tests;

use App\Company;
use CSVWriter\NativeCSVWriter;
use Illuminate\Support\Collection;
use Tests\TestCase;

class NativeCSVWriterTest extends TestCase
{
    public function testCanWriteCollectionToCSV()
    {
        // Create Test Companies.
        $companies = new Collection();
        for ($a = 1; $a <= 2; ++$a) {
            $c = Company::make(['name' => "Company $a", 'logo' => "Logo $a"]);
            $companies->push($c);
        }

        $csvWriter = new NativeCSVWriter();
        $generatedCSV = $csvWriter->collectionToCsv($companies);

        $expectedCSV = <<<TEXT
name,logo
"Company 1","Logo 1"
"Company 2","Logo 2"

TEXT;
        $this->assertEquals($expectedCSV, $generatedCSV, 'The Native CSV generator malfunctioned.');

    }
}
