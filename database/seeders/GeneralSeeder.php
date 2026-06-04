<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Institution;
use App\Models\Klass;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\DB;

class GeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            Board::insert([
                [
                    'id' => 1,
                    'name' => "DHAKA BOARD",
                ],
                [
                    'id' => 2,
                    'name' => "CHATTOGRAM BOARD",
                ],
                [
                    'id' => 3,
                    'name' => "RAJSHAHI BOARD",
                ],
                [
                    'id' => 4,
                    'name' => "JASHORE BOARD",
                ],
                [
                    'id' => 5,
                    'name' => "CUMILLA BOARD",
                ],
                [
                    'id' => 6,
                    'name' => "BARISHAL BOARD",
                ],
                [
                    'id' => 7,
                    'name' => "SYLHET BOARD",
                ],
                [
                    'id' => 8,
                    'name' => "DINAJPUR BOARD",
                ],
                [
                    'id' => 9,
                    'name' => "MYMENSINGH BOARD",
                ],

            ]);

            $klass = new Klass();
            $klass->name = 'HSC';
            $klass->save();
            $klass = new Klass();
            $klass->name = 'SSC';
            $klass->save();

            $subject = new Subject();
            $subject->name = 'ICT';
            $subject->save();
            $subject = new Subject();
            $subject->name = 'MATH';
            $subject->save();
            $subject = new Subject();
            $subject->name = 'PHYSICS';
            $subject->save();
            $subject = new Subject();
            $subject->name = 'CHEMISTRY';
            $subject->save();


            // Institution Seeder Start
            DB::disableQueryLog();
            LazyCollection::make(function(){
                $handle = fopen(public_path('csv_files/ctg_colleges.csv'),'r');
                while(($line = fgetcsv($handle,4096)) != false){
                    $dataString = implode(', ',$line);
                    $row = explode(',',$dataString);
                    yield $row;
                }
                fclose($handle);
            })
            ->chunk(100)
            ->each(function(LazyCollection $chunk){
                $chunk->each(function($row){                
                    $institution = new Institution();
                    $institution->eiin = trim($row[0]); 
                    $institution->name = trim($row[1]); 
                    $institution->save();
                });
            });
            // Institution Seeder End



    }
}
