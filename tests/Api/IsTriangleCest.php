<?php


namespace Api;

use Codeception\Util\HttpCode;
use Tests\Support\ApiTester;
use Codeception\Example;

class IsTriangleCest
{
    /** @dataProvider isTriangeProvider */
    public function testTriangleRealOrNot(ApiTester $I, Example $provider): void
    {
        $I->wantToTest($provider['name']);
        $I->sendGet('triangle/possible', $provider['sizesSides']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson($provider['possibleOrNot']);
    }

    private function isTriangeProvider(): iterable
    {
        yield [
            'name' => 'Get real Triangle with different sizes',
            'sizesSides' => ['a' => 3, 'b' => 4, 'c' => 5],
            'possibleOrNot' => ["isPossible" => "true"]
        ];

        yield [
            'name' => 'Get real Triangle with same sizes of sides',
            'sizesSides' => ['a' => 3, 'b' => 3, 'c' => 3],
            'possibleOrNot' => ["isPossible" => "true"]
        ];

        yield [
            'name' => 'Not real Triangle where one side equal sum others',
            'sizesSides' => ['a' => 3, 'b' => 4, 'c' => 7],
            'possibleOrNot' => ["isPossible" => "false"]
        ];

        yield [
            'name' => 'Not real Triangle where one side more sum others',
            'sizesSides' => ['a' => 3, 'b' => 4, 'c' => 8],
            'possibleOrNot' => ["isPossible" => "false"]
        ];

    }

    /** @dataProvider incorrectInsertDataProvider */
    public function testTriangleWithIncorrectData(ApiTester $I, Example $provider): void
    {
        $I->wantToTest($provider['name']);
        $I->sendGet('/triangle/possible', $provider['sizesSides']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(["message" => ["error" => "Not valid data"]]);
    }


    private function incorrectInsertDataProvider(): iterable
    {
        yield [
            'name' => 'Get Triangle with zero size',
            'sizesSides' => ['a' => 0, 'b' => 4, 'c' => 5],
        ];

        yield [
            'name' => 'Get Triangle negative value',
            'sizesSides' => ['a' => 3, 'b' => -4, 'c' => 5],
        ];

        yield [
            'name' => 'Get Triangle with word instead of size',
            'sizesSides' => ['a' => 3, 'b' => 4, 'c' => "Boris"],
        ];

        yield [
            'name' => 'Get Triangle with float size',
            'sizesSides' => ['a' => 3, 'b' => 4.1, 'c' => 5],
        ];

        yield [
            'name' => 'Get Triangle without one side',
            'sizesSides' => ['a' => 3, 'b' => 4],
        ];

        yield [
            'name' => 'Get Triangle with one side',
            'sizesSides' => ['a' => 3],
        ];

        yield [
            'name' => 'Get Triangle without parameters',
            'sizesSides' => [],
        ];

        yield [
            'name' => 'Get Triangle with false third key',
            'sizesSides' => ['a' => 3, 'b' => 4, 'd' => 5],
        ];

        yield [
            'name' => 'Get figure with 4 sides',
            'sizesSides' => ['a' => 3, 'b' => 4, 'c' => 5, 'd'=>6],
        ];

    }
}

