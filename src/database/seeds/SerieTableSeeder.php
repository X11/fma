<?php

use Illuminate\Database\Seeder;

class SerieTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seriesids = [ 164541, 257655, 295683, 279201, 269586, 281551, 295515, 295759, 281709, 281776, 279121, 266189, 295647, 83462, 274431, 295680, 281470, 263365, 281630, 295743, 72108, 278125, 95441, 295642, 267970, 291517, 265074, 281623, 248861, 299139, 280619, 281485, 269533 ];

        $series = [];
        for ($i = 0; $i < count($seriesids); $i++) {
            $tvdbid = $seriesids[$i];
            $tvshow = TVDB::getTvShow($tvdbid);

            $series[] = [
                'name' => $tvshow->getName(),
                'overview' => $tvshow->getOverview(),
                'tvdbid' => (int) $tvdbid,
                'poster' => $tvshow->getPosterUrl()
            ];
        }
    
        DB::table('series')->insert($series);
    }
}
