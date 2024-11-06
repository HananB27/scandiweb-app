<?php

namespace App\Controller;

use App\Controller\Mutation\OrderMutation;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use App\Controller\Query\ProductQuery;
use RuntimeException;
use Throwable;

class GraphQL {
    static public function handle() {
        try {
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => array_merge(
                    [
                        'echo' => [
                            'type' => Type::string(),
                            'args' => [
                                'message' => ['type' => Type::string()],
                            ],
                            'resolve' => static fn ($rootValue, array $args): string => $rootValue['prefix'] . $args['message'],
                        ],
                    ],
                    (new ProductQuery())->config['fields'] // Add ProductQuery fields directly
                ),
            ]);
        
            // Add OrderMutation to the Mutation Type
            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => array_merge(
                    [
                        'sum' => [
                            'type' => Type::int(),
                            'args' => [
                                'x' => ['type' => Type::int()],
                                'y' => ['type' => Type::int()],
                            ],
                            'resolve' => static fn ($calc, array $args): int => $args['x'] + $args['y'],
                        ],
                    ],
                    (new OrderMutation())->config['fields'] // Add OrderMutation fields directly
                ),
            ]);
        
            // See docs on schema options:
            // https://webonyx.github.io/graphql-php/schema-definition/#configuration-options
            $schema = new Schema(
                (new SchemaConfig())
                ->setQuery($queryType)
                ->setMutation($mutationType)
            );
        
            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }
        
            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variableValues = $input['variables'] ?? null;
        
            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output);
    }
}