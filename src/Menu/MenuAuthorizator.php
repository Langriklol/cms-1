<?php

declare(strict_types=1);

namespace Baraja\Cms;


use Baraja\Cms\User\UserManagerAccessor;
use Nette\Security\User as NetteUser;

final class MenuAuthorizator
{
	private const ALWAYS_ALLOWED = ['homepage' => true, 'cms' => true, 'error' => true];

	private ?int $id;

	/** @var array<string, int> */
	private array $roles;

	/** @var array<string, int> */
	private array $privileges;


	public function __construct(
		NetteUser $userService,
		UserManagerAccessor $userManager,
	) {
		if ($userService->isLoggedIn() === false) {
			$this->id = null;
			$this->roles = [];
			$this->privileges = [];

			return;
		}

		/** @var array<int, array{id: int, roles: array<int, string>|null, privileges: array<int, string>|null}> $user */
		$user = $userManager->get()->getDefaultUserRepository()
			->createQueryBuilder('user')
			->select('PARTIAL user.{id, roles, privileges}')
			->where('user.id = :id')
			->setParameter('id', $userService->getId())
			->setMaxResults(1)
			->getQuery()
			->getArrayResult();

		if (isset($user[0])) {
			$this->id = $user[0]['id'];
			$this->roles = array_flip($user[0]['roles'] ?? []);
			$this->privileges = array_flip($user[0]['privileges'] ?? []);
		} else {
			throw new \RuntimeException(
				'User (type of "' . $userManager->get()->getDefaultEntity() . '", id "' . $userService->getId() . '") '
				. 'does not exist or is not logged in.',
				404,
			);
		}
	}


	public function getId(): ?int
	{
		return $this->id;
	}


	/**
	 * @return array<int, string>
	 */
	public function getRoles(): array
	{
		return array_keys($this->roles);
	}


	/**
	 * @return array<int, string>
	 */
	public function getPrivileges(): array
	{
		return array_keys($this->privileges);
	}


	public function isAllowedPlugin(string $name): bool
	{
		return isset($this->roles['admin'])
			|| isset(self::ALWAYS_ALLOWED[$name])
			|| isset($this->privileges['plugin-' . $name]);
	}


	public function isAllowedComponent(string $pluginName, string $componentName): bool
	{
		return isset($this->roles['admin'])
			|| isset(self::ALWAYS_ALLOWED[$pluginName])
			|| ($this->isAllowedPlugin($pluginName) && isset($this->privileges['component-' . $componentName]));
	}


	public function isAdmin(): bool
	{
		return isset($this->roles['admin']);
	}
}
