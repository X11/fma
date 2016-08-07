<?php

namespace App\Repositories;

use Illuminate\Support\Facades\App;
use App\Serie;

Class TvdbRepository
{

    protected $tvdb;

    /**
     * @param mixed 
     */
    public function __construct()
    {
        $this->tvdb = App::make('tvdb');
    }
    
    /**
     * Search TVDB
     *
     * @return void
     */
    public function search($query)
    {
        try {
            $tvdbResults = $this->tvdb->search()->seriesByName($query);
            $tvdbResults = $tvdbResults->getData();

            $results = $this->filterSearchResults($tvdbResults);
        } catch (\Exception $e) {
            $results = collect();
        }

        return $results;
    }
    
    private function filterSearchResults($results)
    {
        $series_tvdbids = Serie::select('tvdbid')->get()->pluck('tvdbid')->toArray();

        $results= $results->filter(function ($value) use ($series_tvdbids) {
                                        if ($value->getFirstAired() != '' && intval(substr($value->getFirstAired(), 0, 4)) < 2000) {
                                            return false;
                                        }
                                        if (in_array($value->getId(), $series_tvdbids)) {
                                            return false;
                                        }
                                        if (substr($value->getSeriesName(), 0, 2) == '**') {
                                            return false;
                                        }
                                        if (stripos($value->getSeriesName(), 'JAPANESE') !== false) {
                                            return false;
                                        }
                                        if ($value->getStatus() == '') {
                                            return false;
                                        }
                                        if ($value->getNetwork() == '') {
                                            return false;
                                        }

                                        return true;
                                    })->sortByDesc(function ($value) {
                                        $add = ($value->getBanner() != '' ? 10000 : 0);
                                        if (preg_match('/\(([\d]{4})\)$/', $value->getSeriesName(), $matches)) {
                                            $add += intval($matches[1].'0');
                                        }

                                        return $add + $value->getId();
                                    });
        return $results;
    }
    

}
