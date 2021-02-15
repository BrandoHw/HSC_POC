<?php

namespace App\Http\Controllers;

use Aws\Laravel\AwsFacade;
use Illuminate\Http\Request;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\Iot\Exception\IotException;
use Exception;

class AwsController extends Controller
{
    //
    public function test(){
        $client = AwsFacade::createClient('DynamoDb');
        $params = [
            'TableName' => 'Movies',
            'KeySchema' => [
                [
                    'AttributeName' => 'year',
                    'KeyType' => 'HASH'  //Partition key
                ],
                [
                    'AttributeName' => 'title',
                    'KeyType' => 'RANGE'  //Sort key
                ]
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'year',
                    'AttributeType' => 'N'
                ],
                [
                    'AttributeName' => 'title',
                    'AttributeType' => 'S'
                ],
        
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 10,
                'WriteCapacityUnits' => 10
            ]
        ];
        
        try {
            $result = $client->createTable($params);
            echo 'Created table.  Status: ' . 
                $result['TableDescription']['TableStatus'] ."\n";
        
        } catch (DynamoDbException $e) {
            echo "Unable to create table:\n";
            echo $e->getMessage() . "\n";
        }
    }

    public function createThing(){
        $client = AwsFacade::createClient('Iot');
        try{
            $result = $client->createThing([
                'thingName' => 'test',
            ]);
            echo $result['thingName'];
        } catch (IotException $e){
            echo "Unable to create thing:\n";
            echo $e->getMessage() . "\n";
        }

    }

    public function createRule(){
        $client = AwsFacade::createClient('Iot');
        try{
            $result = $client->createTopicRule([
                'ruleName' => 'testRule', // REQUIRED
                'topicRulePayload' => [ // REQUIRED
                    'actions' => [ // REQUIRED
                        [
                            'lambda' => [
                                'functionArn' => 'arn:aws:lambda:us-east-2:382326809873:function:insertIotCatalog', // REQUIRED
                            ],
                        ],
                    ],
                    'sql' => "SELECT * FROM 'my/greenhouse'",
                ],
            ]);
        } catch (Exception $e){
            echo "Unable to create rule:\n";
            echo $e->getMessage() . "\n";
        }

    }

    public function createAction(){
        $client = AwsFacade::createClient('Lambda');
        echo file_get_contents('C:\xampp\htdocs\awstest\index.zip');
        try{
            $result = $client->createFunction(array(
                // FunctionName is required
                'FunctionName' => 'testFunc',
                // Runtime is required
                'Runtime' => 'nodejs12.x',
                // Role is required
                'Role' => 'arn:aws:iam::382326809873:role/service-role/insertIotCatalog-role-jyvw393l',
                // Handler is required
                'Handler' => 'index.handler',
                // Code is required
                'Code' => array(
                    'ZipFile' => file_get_contents('C:\xampp\htdocs\awstest\index.zip'),
                ),
                'Description' => 'SDK Test',
                'Publish' => true,
            ));
        } catch (Exception $e){
            echo "Unable to create thing:\n";
            echo $e->getMessage() . "\n";
        }

    }
}
