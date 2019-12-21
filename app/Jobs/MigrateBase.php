<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

use App\Models\Address;
use App\Models\Degree;
use App\Models\Category;
use App\Models\Institution;

class MigrateBase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Create degrees
        Degree::firstOrCreate(['text' => 'None'] , []);
        Degree::firstOrCreate(['text' => 'High school'], []);
        Degree::firstOrCreate(['text' => 'Bachelor (college)'], []);
        Degree::firstOrCreate(['text' => 'Masters'], []);
        Degree::firstOrCreate(['text' => 'Doctoral'], []);
        Degree::firstOrCreate(['text' => 'Post-doctoral'], []);
        \Debugbar::error('Created degrees.');

        // Migrate categories
        $categories = DB::connection('mysqlold')->table('categories')
            ->get()->keyBy('id');
        $subcategories = DB::connection('mysqlold')->table('subcategories')
            ->get()->keyBy('id');

        foreach ($categories as $category) {
            Category::firstOrCreate([ 'abbreviation' => $category->label ],
            [
                'title' => $category->description,
            ]);
        }

        foreach ($subcategories as $subcategory) {
            $parentcategory = $categories[$subcategory->category_id];
            $pc = Category::where('abbreviation', '=', $parentcategory->label)->first();
            Category::firstOrCreate([ 'abbreviation' => $subcategory->label ],
            [
                'title' => $subcategory->description,
                'parent_id' => $pc->id
            ]);
        }
        \Debugbar::error('Migrated categories.');

        // Migrate Institutions
        $affiliations = DB::connection('mysqlold')->table('affiliations')
            ->get()->keyBy('id');
        foreach ($affiliations as $affiliation) {
            if(Institution::where('content','=',$affiliation->institution)->count() == 0) {
                $address = Address::create([
                    'country_id' => 8,
                    'province' => '',
                    'street' => '',
                    'addressable_type' => 'App\Models\Institution',
                    'city' => ''
                ]);
                $i = Institution::create([
                    'content' => $affiliation->institution,
                    'address_id' => $address->id
                ]);
                $address->addressable_id = $i->id;
                $address->save();
            }
        }
        \Debugbar::error('Migrated institutions.');
    }
}
