<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;

/**
 * 認証のテスト
 */
class AuthorizationTest extends TestCase
{
    private static $users = [];

    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass()
    {
        // テストユーザ作成
        self::$users['success'] = User::create([
            'account' => 'test',
            'name' => 'テストユーザ',
            'password' => app('hash')->make('testtest'),
            'sex' => '男性',
            'role' => '管理者',
            'is_active' => 1,
        ]);
        self::$users['fail'] = User::create([
            'account' => 'test2',
            'name' => 'テストユーザ',
            'password' => app('hash')->make('testtest'),
            'sex' => '男性',
            'role' => '管理者',
            'is_active' => 0,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public static function tearDownAfterClass()
    {
        // テストユーザ削除
        foreach (self::$users as $user) {
            $user->delete();
        }
    }

    /**
     * ステータスチェック
     *
     * @return void
     */
    public function testStates()
    {
        $this->get('/v1')
            ->seeJsonEquals([
                'status' => 'OK',
                'environment' => $this->app->environment(),
                'logLevel' => env('APP_LOG_LEVEL'),
                'timeZone' => env('APP_TIMEZONE'),
            ]);
    }

    /**
     * トークン取得：成功
     *
     * @return void
     */
    public function testToken()
    {
        $successUser = self::$users['success'];
        $this->makeToken($successUser->account, 'testtest');

        $this->assertEquals(200, $this->response->getStatusCode());
        $json = json_decode($this->response->getContent());
        $this->assertNotNull($json->access_token);
    }

    /**
     * トークン取得：認証エラー
     *
     * @return void
     */
    public function testTokenNotActiveUser()
    {
        $failUser = self::$users['fail'];
        $this->makeToken($failUser->account, 'testtest');

        $this->assertEquals(401, $this->response->getStatusCode());
    }

    /**
     * トークン取得：バリデーションエラー
     *
     * @return void
     */
    public function testTokenInvalid()
    {
        $this->makeToken();

        $this->assertEquals(400, $this->response->getStatusCode());
    }

    /**
     * ユーザ取得
     *
     * @return void
     */
    public function testUsers()
    {
        $successUser = self::$users['success'];
        $token = $this->getToken($successUser->account, 'testtest');
        $this->get('/v1/users?access_token='.$token);
        $this->assertEquals(200, $this->response->getStatusCode());

        $json = json_decode($this->response->getContent());
        $this->assertNotNull($json->id);
        $this->assertEquals($json->userId, $successUser->account);
        $this->assertEquals($json->userName, $successUser->name);
        $this->assertEquals($json->sex, $successUser->sex);
        $this->assertEquals($json->role, $successUser->role);
    }

    /**
     * ユーザ取得：失敗（トークン不正）
     *
     * @return void
     */
    public function testUsersInvalidToken()
    {
        $this->get('/v1/users');
        $this->assertEquals(401, $this->response->getStatusCode());
    }

    /**
     * ユーザ取得：失敗（トークン無し）
     *
     * @return void
     */
    public function testUsersNotHasToken()
    {
        $this->get('/v1/users?access_token='.random_bytes(50));
        $this->assertEquals(401, $this->response->getStatusCode());
    }
}
