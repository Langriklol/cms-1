<?php

declare(strict_types=1);

namespace Baraja\Cms\User\Entity;


use Baraja\Cms\Helpers;
use Baraja\Doctrine\UUID\UuidIdentifier;
use Doctrine\ORM\Mapping as ORM;
use Nette\SmartObject;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cms__user_login_attempt")
 */
class UserLoginAttempt
{
	use UuidIdentifier;
	use SmartObject;

	/**
	 * @var User|null
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="loginAttempts")
	 */
	private $user;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=64)
	 */
	private $username;

	/**
	 * @var bool
	 * @ORM\Column(type="boolean")
	 */
	private $password = false;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=2048, nullable=true)
	 */
	private $loginUrl;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=39, nullable=true)
	 */
	private $ip;

	/**
	 * @var \DateTime
	 * @ORM\Column(type="datetime")
	 */
	private $insertedDateTime;

	/**
	 * @var string|null
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $notice;


	public function __construct(?User $user, string $username)
	{
		$this->user = $user;
		$this->username = Strings::substring($username, 0, 64);
		$this->ip = Helpers::userIp();
		$this->insertedDateTime = DateTime::from('now');
	}


	public function getUser(): ?User
	{
		return $this->user;
	}


	public function setUser(User $user): void
	{
		$this->user = $user;
	}


	public function getUsername(): string
	{
		return $this->username;
	}


	public function isPasswordOk(): bool
	{
		return $this->password;
	}


	public function setOkPassword(): bool
	{
		return $this->password = true;
	}


	public function getLoginUrl(): ?string
	{
		return $this->loginUrl;
	}


	public function setLoginUrl(?string $loginUrl): void
	{
		$this->loginUrl = $loginUrl !== null
			? Strings::substring($loginUrl, 0, 2000)
			: null;
	}


	public function getIp(): ?string
	{
		return $this->ip;
	}


	public function getInsertedDateTime(): \DateTime
	{
		return $this->insertedDateTime;
	}


	public function getNotice(): ?string
	{
		return $this->notice;
	}


	public function setNotice(?string $notice): void
	{
		$this->notice = $notice;
	}


	public function addNotice(?string $notice): void
	{
		$this->notice = trim($this->notice . "\n" . $notice);
	}
}