<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 * @author CENTRAL-CODE
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'reserved_domains',
            function (Blueprint $table) {
                $table->id();
                $table->string('subdomain')->index();
            }
        );

        if (env('APP_ENV') === 'testing') {
            return;
        }
        $file = fopen(dirname(__FILE__).'/words_alpha.txt', 'r');

        $batch = [];
        while (! feof($file)) {
            $line = trim(fgets($file, 4096));
            $batch[] = ['subdomain' => $line];
            if (count($batch) >= 10000) {
                $this->insertToDb($batch);
                $batch = [];
            }
        }
        // Insert rest
        $this->insertToDb($batch);
        fclose($file);
    }

    public function insertToDb(array $batch)
    {
        DB::table('reserved_domains')->insert($batch);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserved_domains');
    }
};
