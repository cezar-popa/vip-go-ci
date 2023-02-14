<?php
/**
 * Test vipgoci_cache().
 *
 * @package Automattic/vip-go-ci
 */

declare(strict_types=1);

namespace Vipgoci\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Class that implements the testing.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
final class CacheVipgociCacheTest extends TestCase {
	/**
	 * Setup function. Require files, etc.
	 *
	 * @return void
	 */
	protected function setUp() :void {
		require_once __DIR__ . './../../defines.php';
		require_once __DIR__ . './../../cache.php';
	}

	/**
	 * Test caching random data and test if is correctly retrieved.
	 *
	 * @covers ::vipgoci_cache
	 *
	 * @return void
	 */
	public function testCache1() :void {
		$cache_id1 =
			__CLASS__ .
			'_' .
			__FUNCTION__ .
			'_mytest1';

		$cache_id2 =
			__CLASS__ .
			'_' .
			__FUNCTION__ .
			'_mytest2';

		$r1 = openssl_random_pseudo_bytes(
			100
		);

		$r2 = $r1 . $r1;

		vipgoci_cache(
			$cache_id1,
			$r1
		);

		vipgoci_cache(
			$cache_id2,
			$r2
		);

		$r1_retrieved = vipgoci_cache(
			$cache_id1
		);

		$r2_retrieved = vipgoci_cache(
			$cache_id2
		);

		$this->assertSame(
			$r1,
			$r1_retrieved
		);

		$this->assertSame(
			$r2,
			$r2_retrieved
		);

		$this->assertNotEquals(
			$r1,
			$r2
		);

		$this->assertNotEquals(
			$r1_retrieved,
			$r2_retrieved
		);
	}

	/**
	 * Test clearing of cache.
	 *
	 * @covers ::vipgoci_cache
	 *
	 * @return void
	 */
	public function testCache2() :void {
		$cache_id =
			__CLASS__ .
			'_' .
			__FUNCTION__;

		/*
		 * Clear cache.
		 */
		vipgoci_cache(
			VIPGOCI_CACHE_CLEAR
		);

		/*
		 * Cache something,
		 * be sure it cached properly.
		 */
		vipgoci_cache(
			$cache_id,
			'mytext'
		);

		$cached_data = vipgoci_cache(
			$cache_id
		);

		$this->assertSame(
			'mytext',
			$cached_data
		);

		/*
		 * Clear the cache,
		 * make sure it cleared.
		 */
		vipgoci_cache(
			VIPGOCI_CACHE_CLEAR
		);

		$cached_data = vipgoci_cache(
			$cache_id
		);

		$this->assertSame(
			false,
			$cached_data
		);
	}
}
