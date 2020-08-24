<?php

namespace core;

class Router {
	protected $requestUri;
	protected $controller;
	protected $base = [];
	protected $params = [];

	public function addUrlToBase( string $path, string $controller ) {
		if ( isset( $this->base[ $path ] ) ) {
			throw new InvalidArgumentException(
				'The argument exists in the base '
			);
		} else {
			$this->base[ $path ] = $controller;
		}
	}

	public function run() {
		if ( $this->match() ) {
			$controller = 'controller\\' . $this->controller;
			if ( class_exists( $controller ) ) {
				new $controller;
			}
		}
	}

	public function match() {
		$url = trim( $this->getRequestUri(), '/' );
		foreach ( $this->base as $rout => $controller ) {
			if ( preg_match( '#^' . $rout . '$#', $url, $match ) ) {
				$this->controller = str_replace( '/', '\\', $controller );

				return true;
			}
		}

		return false;
	}

	public function getRequestUri() {
		return $this->requestUri;
	}

	public function setRequestUri( $requestUri ): void {
		$this->requestUri = $requestUri;
	}

	public function setBase( array $base ): void {
		$this->base = $base;
	}
}