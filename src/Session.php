<?php

declare(strict_types=1);

namespace Baraja\Cms;


final class Session
{
	public static function get(string $key): mixed
	{
		return $_SESSION[self::getKey($key)] ?? null;
	}


	public static function set(string $key, mixed $value): void
	{
		if ($value === null) {
			self::remove($key);
		} else {
			$_SESSION[self::getKey($key)] = $value;
		}
	}


	public static function remove(string $key): void
	{
		$sessionKey = self::getKey($key);
		if (isset($_SESSION[$sessionKey]) === true) {
			unset($_SESSION[$sessionKey]);
		}
	}


	public static function getKey(string $key): string
	{
		return '__BRJ_CMS--' . $key;
	}
}
