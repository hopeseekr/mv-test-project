<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesInvestmentsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Unfortunately, Taylor Otwell -hates- SQL views and has refused both of
        // my MySQL and PostgreSQL view PRs :-/
        // Yet, it's still the bestest way I've ever found for allowing complex and
        // performant SQL to be run exceptionally easily by Eloquent, without getting
        // into DB Builder hell.

        // Just make that the view SQL is SQL1992 and you'll be fine.
        $sql = <<<SQL
CREATE VIEW companies_investments AS 
  SELECT
    c.id, 
    c.name, 
    COALESCE(SUM(i.amount), 0) AS total_invested 
  FROM companies c 
  LEFT JOIN investments i ON i.company_id=c.id 
GROUP BY c.id;
SQL;
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW companies_investments');
    }
}
