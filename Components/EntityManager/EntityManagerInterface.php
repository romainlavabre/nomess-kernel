<?php


namespace Nomess\Components\EntityManager;


interface EntityManagerInterface
{
    public function find(string $classname, ?string $idOrSql = NULL, ?array $parameters = NULL);

    public function create(object $object): self;

    public function update(object $object): self;

    public function delete(object $object): self;

    public function setCascade(string $classname, bool $apply): self;

    public function register(): bool;
}
