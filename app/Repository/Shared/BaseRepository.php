<?php

namespace App\Repository\Shared;

use App\Database\QueryBuilder;

/**
 * @template TEntity of object
 */
abstract class BaseRepository
{
  /**
   * @param class-string<TEntity> $entityClass
   */
  public function __construct(
    protected QueryBuilder $queryBuilder,
    protected string $table,
    private string $entityClass
  ) {
  }

  public function queryBuilder(): QueryBuilder
  {
    return $this->queryBuilder;
  }

  /**
   * @param int $id
   * @return TEntity|null
   */
  public function find(int $id): ?object
  {
    $row = $this->queryBuilder
      ->from($this->table)
      ->andWhere('id', '=', $id)
      ->first();

    $class = $this->entityClass;
    return $row ? $class::fromDatabase($row) : null;
  }

  /**
   * @param TEntity $entity
   * @param array $fields
   */
  public function insert(object $entity, array $fields): ?int
  {
    $result = $this->queryBuilder
      ->from($this->table)
      ->insert($entity->toDatabase($fields))
      ->get();

    return reset($result)['id'] ?? null;
  }

  /**
   * @param TEntity $entity
   * @param array $fields
   * @return bool
   */
  public function update(object $entity, array $fields): bool
  {
    $fields[] = 'id';
    $data = $entity->toDatabase($fields);
    unset($data['id']);
    $id = $entity->idValue();

    return $this->queryBuilder
      ->from($this->table)
      ->andWhere('id', '=', $id)
      ->update($data)
      ->execute();
  }

  /**
   * @param TEntity $entity
   * @return bool
   */
  public function delete(object $entity): bool
  {
    if (!method_exists($entity, 'idValue')) {
      throw new \RuntimeException("Entity must have idValue() method.");
    }

    return $this->queryBuilder
      ->from($this->table)
      ->andWhere('id', '=', $entity->idValue())
      ->delete()
      ->execute();
  }
}