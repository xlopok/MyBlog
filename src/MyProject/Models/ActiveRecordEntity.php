<?php

namespace MyProject\Models;

use MyProject\Services\Db;

abstract class ActiveRecordEntity
{
    /** @var int */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function __set($name, $value)
    {
//        echo 'Пытаюсь задать для свойства ' . $name . ' значение ' . $value . '<br>';
//        $this->$name = $value;
        $camelCaseName = $this-> underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    private function camelCaseToUnderscore(string $source): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }

    public function save(): void
    {
        $mappedProperties = $this->mapPropertiesToDbFormat();
//        var_dump($mappedProperties);
        if ($this->id !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
//        var_dump($mappedProperties);
    }

    private function mapPropertiesToDbFormat(): array
    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }

        return $mappedProperties;
    }

    private function update(array $mappedProperties): void
    {
        //здесь мы обновляем существующую запись в базе
        $columns2params = [];
        $params2values = [];
        $index =1;

        foreach ($mappedProperties as $column => $value) {
            $param = ':param' . $index; // :param1
            $columns2params[] = $column . ' = ' . $param;
            $params2values[':param' . $index] = $value;
            $index++;
        }

        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $columns2params) . ' WHERE id = ' . $this->id;
        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
    }

    private function insert(array $mappedProperties): void
    {
        //здесь мы создаём новую запись в базе
        $filteredProperties = array_filter($mappedProperties);
//        var_dump($mappedPropertiesNotNull);
        $columns = [];
        $paramsNames = [];
        $params2values = [];

        foreach ($filteredProperties as $column => $value) {
            $columns[] = '`'. $column . '`';
            $paramName = ':' . $column;
            $paramsNames[] = $paramName;
            $params2values[$paramName] = $value;
        }

        $columnsViaSemicolon = implode(', ', $columns);
        $paramsNamesViaSemicolon = implode(', ', $paramsNames);

        $sql = 'INSERT INTO ' . static::getTableName() . ' ('. $columnsViaSemicolon . ') ' . 'VALUES (' . $paramsNamesViaSemicolon . ' )';
        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
        $this->id = $db->getLastInsertId();
        $this->refresh();
    }

    public function refresh(): void
    {
        $objFromDb = static::getById($this->id);
//        var_dump($objFromDb);

        $properties = get_object_vars($objFromDb);
//        var_dump($properties);
        foreach ($properties as $key=>$value) {
            $this->$key = $value;
        }
    }

    public function delete() {
        $db = Db::getInstance();
        $db->query(
            'DELETE FROM `' . static::getTableName() . '` WHERE id = :id ',
            [':id' => $this->id]
            );
        $this->id = null;
    }


    public static function findAll() :array
    {
        $db =  Db::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
    }

    /**
     * @param int $id
     * @return static|null
     */
    public static function getById(int $id): ?self
    {
//        var_dump($id);
        $db = Db::getInstance();
        $entities = $db->query(
          'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id;',
            [':id' => $id],
            static::class
        );
//        var_dump($entities);
        return $entities? $entities[0] : null;
    }

    abstract protected static function getTableName(): string;

    public static function findOneByColumn(string $columnName, $value): ?self
    {
        $db = Db::getInstance();
        $result = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE `' . $columnName . '` = :value LIMIT 1;',
            [':value' => $value],
            static::class
        );
        if($result === []) {
            return null;
        }
        return $result[0];
    }
}