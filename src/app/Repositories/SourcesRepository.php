<?php

namespace App\Repositories;

use TorrentSearch\TorrentSearch;
use Illuminate\Support\Facades\Cache;
use Sources\Sources;

Class SourcesRepository
{

    /**
     * undocumented function
     *
     * @return void
     */
    public function searchMagnets($query, $filter_function)
    {
        return Cache::get('magnets_'.$query, function () use ($query, $filter_function) {
            $magnets = [];

            try {
                $ts = new TorrentSearch();
                $magnets = $ts->search(strtolower($query), '1');

                $magnets = array_filter($magnets, $filter_function);

                $magnets = array_map(function($magnet) {
                    return [
                        'seeds' => $magnet->getSeeds(),
                        'peers' => $magnet->getPeers(),
                        'name' => $magnet->getName(),
                        'size' => $magnet->getSize(),
                        'magnet' => $magnet->getMagnet()
                    ];
                }, $magnets);

                Cache::put('magnets_'.$query, $magnets, 600);
            } catch (\Exception $e) {
                // Fall throu, Nothing we can do.
            }

            return $magnets;
        });
    }
    
    /**
     * undocumented function
     *
     * @return void
     */
    public function searchLinks($serie, $season, $number)
    {
        return Cache::get('links_'.$serie.$season.$number, function () use ($serie, $season, $number) {
            $links = [];
            try {
                $links = ((new Sources())->search($serie, $season, $number));

                $links = collect($links)->map(function($link){
                                                $url = parse_url($link);
                                                $url['href'] = $link;
                                                return $url;
                                            })
                                            ->groupBy('host')
                                            ->toArray();

                Cache::put('links_'.$serie.$season.$number, $links, 600);
            } catch (\Exception $e) {
                // Fall throu, Nothing we can do.
            }

            return $links;
        });
    }
                
}
