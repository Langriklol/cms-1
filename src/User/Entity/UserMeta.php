<?php

declare(strict_types=1);

namespace Baraja\Cms\User\Entity;


use Baraja\Doctrine\UUID\UuidIdentifier;
use Doctrine\ORM\Mapping as ORM;
use Nette\SmartObject;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cms__user_meta")
 */
class UserMeta
{
	use UuidIdentifier;
	use SmartObject;

	/**
	 * @var User
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="metas")
	 */
	private $user;

	/**
	 * @var string
	 * @ORM\Column(type="string", name="`key`", length=64)
	 */
	private $key;

	/**
	 * @var string|null
	 * @ORM\Column(type="text", name="`value`", nullable=true)
	 */
	private $value;


	public function __construct(User $user, string $key, ?string $value)
	{
		$this->user = $user;
		$this->key = $key;
		$this->value = $value;
	}


	public function getUser(): User
	{
		return $this->user;
	}


	public function getKey(): string
	{
		return $this->key;
	}


	public function getValue(): ?string
	{
		return $this->value;
	}


	public function setValue(?string $value): void
	{
		$this->value = $value;
	}
}