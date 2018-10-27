<?php


namespace CandleLight;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Capsule\Manager as DB;

/**
 * Basic DB-Migration Template
 * @package CandleLight\Artisan
 */
abstract class Migration{

    private $app;

    /**
     * Migration constructor.
     * @param App $app
     */
    public function __construct(App $app){
        $this->app = $app;
    }

    /**
     * Returns the current CDL application instance
     * @return App
     */
    public function getApp(): App{
        return $this->app;
    }

    /**
     * Returns the CDL applications database instance
     * @return Database
     */
    public function getDb(): Database{
        return $this->app->getDb();
    }

    /**
     * Returns the Schema Builder for the Applications Database instance
     * @param string $connection Connection name
     * @return Builder
     */
    public function getSchema(string $connection){
        return $this->app->getDb()->getBuilder($connection);
    }

    /**
     * Kicks of the Migration-Up Process
     */
    public function execUp(string $name): void{
        DB::connection('default')->table('migrations')->insert(['name' => $name]);
        $this->up();
    }

    /**
     * Kicks of the Migration-Down Process
     */
    public function execDown(string $name): void{
        $this->down();
        DB::connection('default')->table('migrations')->where('name', $name)->delete();
    }

    /**
     * Method called, when the migration is executed
     */
    public abstract function up(): void;

    /**
     * Method called, when the migration is rolled back
     */
    public abstract function down(): void;

    /**
     * Checks if the given migration has been done already
     * @param string $name
     * @return bool
     */
    public static function hasMigrated(string $name){
        $res = DB::connection('default')->table('migrations')->where('name', $name)->first();
        return !is_null($res);
    }

    /**
     * Returns the last migration-name, which has been done
     * @param App $app CDL Application instance
     * @return string|bool migration name or false if no migrations have been found
     */
    public static function getLastMigration(App $app){
        $res = DB::connection('default')->table('migrations')->orderBy('id', 'desc')->first();
        if (is_null($res) || !$app->hasMigration($res->name)){
            return false;
        }
        return $res->name;
    }

    /**
     * Prepares the migration table
     * @param App $app
     */
    public static function prepareMigrationTable(App $app): void{
        $migration = new class($app) extends Migration{

            /**
             * Create the migration table if it does not exist yet
             */
            public function up(): void{
                $schema = $this->getSchema('default');
                if (!$schema->hasTable('migrations')) {
                    $schema->create('migrations', function (Blueprint $table){
                        $table->increments('id');
                        $table->string('name', 255);
                    });
                }
            }

            /**
             * Is not needed, but add it nevertheless
             */
            public function down(): void{
                $this->getSchema('default')->drop('migrations');
            }
        };
        $migration->up();
    }


}