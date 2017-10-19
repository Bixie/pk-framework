<?php

namespace Bixie\PkFramework\Stats;

use Doctrine\Common\Cache\Cache;
use Pagekit\Application as App;
use Pagekit\Util\Arr;


class StatsBase {

    const CACHE_KEY = 'pkframework.stats';

    /**
     * @var App
     */
    protected $app;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var array
     */
    protected $_cache;

    /**
     * @var array
     */
    protected $_items;

    /**
     * @var bool
     */
    protected $_cache_dirty;

    /**
     * StatsBase constructor.
     * @param App $app
     */
    public function __construct (App $app) {
        $this->app = $app;
        $this->cache = $app['cache'];
    }

    /**
     * @param string $period_label
     * @return bool
     */
    public function getCache ($period_label) {
        $this->loadCache();
        return Arr::get($this->_cache, $period_label);
    }

    /**
     * @param string $period_label
     * @param        $data
     */
    public function setCache ($period_label, $data) {
        $this->loadCache();
        Arr::set($this->_cache, $period_label, $data);
        $this->_cache_dirty = true;
    }

    /**
     */
    public function loadCache () {
        if (!isset($this->_cache)) {
            $this->_cache = $this->cache->fetch(static::CACHE_KEY) ? : [];
            $this->_cache_dirty = false;
        }
    }

    /**
     *
     */
    public function __destruct () {
        if ($this->_cache_dirty) {
            $this->cache->save(static::CACHE_KEY, $this->_cache);
        }
    }

    /**
     *
     */
    public function clearCache () {
        $this->cache->delete(static::CACHE_KEY);
    }

    /**
     * @param array $period
     * @param array $filter
     * @return array|bool
     */
    public function overview ($period, $filter = []) {

        $cache_key = md5('overview' . serialize([$period, $filter]));

        if (!$stats = $this->getCache($cache_key)) {

            $stats = $this->getOverview($period, $filter);

            $this->setCache($cache_key, $stats);
        }

        return $stats;
    }

    /**
     * Override this method
     * @param array $period
     * @param array $filter
     * @return array
     */
    protected function getOverview ($period, $filter) {

        $items = $this->getItems($period, $filter);

        $stats = [
            'bruto_total' => 0,
            'total_purchase' => 0,
            'bruto_margin' => 0,
        ];
        return $stats;
    }

    /**
     * @param array $period
     * @param array $filter
     * @return array|bool
     */
    public function detailed ($period, $filter = []) {

        $cache_key = md5('detailed' . serialize([$period, $filter]));

        if (!$stats = $this->getCache($cache_key)) {

            $stats = $this->getDetailed($period, $filter);

            $this->setCache($cache_key, $stats);
        }

        return $stats;
    }

    /**
     * Override this method
     * @param array $period
     * @param array $filter
     * @return array
     */
    protected function getDetailed ($period, $filter) {

        $items = $this->getItems($period, $filter);

        $stats = [
            'nr_users' => 0,
            'bruto_total' => 0,
            'graphs' => [
                'revenue' => [],
                'purchase' => [],
                'margin' => [],
            ],
        ];
        return $stats;
    }

    /**
     * Override this method
     * @param $period
     * @param $filter
     * @return array
     */
    protected function getItems ($period, $filter) {

        $key = md5(serialize([$period, $filter]));

        if (isset($this->_items[$key])) {
            return $this->_items[$key];
        }
        $this->_items[$key] = [];


        return $this->_items[$key];

    }


}