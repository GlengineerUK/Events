<?php

use App\WorkerType;
use Illuminate\Database\Seeder;

class WorkerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Shift Manager', 'Supervisor', 'Bar Staff', 'Collectors', 'Chefs', 'Waiters'];
        foreach ($types as $type)
        {
            $record = new WorkerType();
            $record['type'] = $type;
            $record['description'] = 'Sample description';
            $record->save();
        }
    }



}
