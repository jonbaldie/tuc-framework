<?php

namespace Tuc\Data;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

abstract class Model
{
    /**
     * EntityManagerInterface
     */
    protected static $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public static function setEntityManager(EntityManagerInterface $manager)
    {
        static::$manager = $manager;
    }

    /**
     * @return EntityRepository
     */
    public static function getRepository(): EntityRepository
    {
        return static::$manager->getRepository(static::class);
    }

    /**
     * @param mixed $id
     * @return self|static
     */
    public static function find($id): self
    {
        return static::getRepository()->find($id);
    }

    /**
     * @param array $conditions
     * @return self[]|static[]
     */
    public static function where(array $conditions): array
    {
        return static::getRepository()->findBy($conditions);
    }

    /**
     * @param array $conditions
     * @return self|static
     */
    public static function first(array $conditions): self
    {
        return static::getRepository()->findOneBy($conditions);
    }

    /**
     * @param array $conditions
     * @return int
     */
    public static function count(array $conditions): int
    {
        return static::getRepository()->count($conditions);
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function __get(string $field)
    {
        return $this->{$field} ?? null;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @return void
     */
    public function __set(string $field, $value): void
    {
        $this->{$field} = $value;
    }

    /**
     * @return void
     */
    public function save(): void
    {
        static::$manager->flush($this);
    }
}
