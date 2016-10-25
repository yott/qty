<?php namespace Qtx;

/**
 * Class Url
 * @package Qtx
 * @property $path
 * @property $host
 * @property $query
 */
class Url {
	protected $scheme;
	protected $host;
	protected $base;
	protected $path;
	protected $query;

	/**
	 * Url constructor.
	 * @param $urlinfo
	 *   Deprecated array structure for url info from qtranslate-x
	 */
	public function __construct( $urlinfo = [] ) {
		$this->scheme = $urlinfo['scheme'];
		$this->host = $urlinfo['host'];
		$this->base = $urlinfo['path-base'];
		$this->path = $urlinfo['wp-path'];
		$this->query = $urlinfo['query'];
	}

	/**
	 * Set path-based language recognition for this url.
	 * @param $lang
	 *   Language to encode
	 * @return string
	 *   Encoded path
	 */
	public function setPath( $lang ) {
		$path = explode( '/', $this->path );
		if ( $path[0] !== $lang ) {
			array_unshift( $path, $lang );
			$this->path = implode('/', $path);
		}
		return $this->path;
	}

	/**
	 * Set host-based language recognition for this url.
	 * @param $lang
	 *   Language to encode
	 * @return string
	 *   Encoded host
	 */
	public function setHost( $lang ) {
		$host = explode( '.', $this->host );
		if ( $host[0] !== $lang ) {
			array_unshift( $host, $lang );
			$this->host = implode( '.', $host );
		}
		return $this->host;
	}

	/**
	 * Set query-based language recognition for this url.
	 * @param $lang
	 *   Language to encode
	 * @return string
	 *   Encoded query
	 */
	public function setQuery( $lang ) {
		// Check existing path first
		$get = [];
		$request = explode( '/', $this->path );
		$basename = explode( '?', array_pop( $request ) );
		$query = array_pop( $basename );
		parse_str( $query, $get );
		if ( isset( $get['lang'] ) ) {
			if ( $get['lang'] === $lang ) {
				// Lang already set in path
				return $this->query = '';
			} else {
				// Lang in path, but incorrect
				unset($get['lang']);
				$query = implode( '&', array_filter( $get ) );
				$basename[] = $query;
				$basename = implode( '?', array_filter( $basename ) );
				$request[] = $basename;
				$this->path = implode( '/' , $request );
			}
		}

		// Check existing query
		$get = [];
		parse_str( $this->query, $get );
		if ( !isset( $get['lang'] ) || ( $get['lang'] !== $lang ) ) {
			$this->query = "lang={$lang}";
		}
		return $this->query;
	}

	/**
	 * Magic method to retrieve property values.
	 *
	 * Keeping most properties of this class protected keeps the values safe from accidental overwrites
	 * without first sanitizing the set value.
	 * @param $name
	 *   Property to retrieve
	 * @return mixed
	 */
	public function __get( $name ) {
		if ( property_exists( $this, $name ) ) {
			return $this->$name;
		}
		return null;
	}
}
