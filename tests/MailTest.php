<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\MailHistory;

/**
 * メール送信のテスト
 */
class MailTest extends TestCase
{
    /**
     * テストユーザ
     *
     * @var User
     */
    private static $user = null;

    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass()
    {
        // テストユーザ作成
        self::$user = User::create([
            'account' => 'test',
            'name' => 'テストユーザ',
            'password' => app('hash')->make('testtest'),
            'mail_address' => 'test@example.com',
            'sex' => '男性',
            'role' => '管理者',
            'is_active' => 1,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public static function tearDownAfterClass()
    {
        // テストユーザ削除
        MailHistory::where('user_id', self::$user->id)->delete();
        self::$user->delete();
    }

    /**
     * 送信失敗（TOなし）
     *
     * @return void
     */
    public function testNotHasTo()
    {
        $data = [
            'subject' => 'test',
            'body' => 'testtest',
            'access_token' => $this->getToken(self::$user->account, 'testtest'),
        ];
        $this->post('/v1/mail', $data);

        $this->assertEquals(400, $this->response->getStatusCode());
    }

    /**
     * 送信失敗（件名なし）
     *
     * @return void
     */
    public function testNotHasSubject()
    {
        $data = [
            'to' => self::$user->mail_address,
            'body' => 'testtest',
            'access_token' => $this->getToken(self::$user->account, 'testtest'),
        ];
        $this->post('/v1/mail', $data);

        $this->assertEquals(400, $this->response->getStatusCode());
    }

    /**
     * 送信失敗（本文なし）
     *
     * @return void
     */
    public function testNotHasBody()
    {
        $data = [
            'to' => self::$user->mail_address,
            'subject' => 'test',
            'access_token' => $this->getToken(self::$user->account, 'testtest'),
        ];
        $this->post('/v1/mail', $data);

        $this->assertEquals(400, $this->response->getStatusCode());
    }

    /**
     * 送信失敗（TO不正）
     *
     * @return void
     */
    public function testInvalidTo()
    {
        $data = [
            'to' => str_random(501),
            'subject' => 'test',
            'body' => 'testtest',
            'access_token' => $this->getToken(self::$user->account, 'testtest'),
        ];
        $this->post('/v1/mail', $data);

        $this->assertEquals(400, $this->response->getStatusCode());
    }

    /**
     * 送信失敗（CC不正）
     *
     * @return void
     */
    public function testInvalidCc()
    {
        $data = [
            'to' => self::$user->mail_address,
            'cc' => str_random(501),
            'subject' => 'test',
            'body' => 'testtest',
            'access_token' => $this->getToken(self::$user->account, 'testtest'),
        ];
        $this->post('/v1/mail', $data);

        $this->assertEquals(400, $this->response->getStatusCode());
    }

    /**
     * 送信失敗（CC不正）
     *
     * @return void
     */
    public function testInvalidBcc()
    {
        $data = [
            'to' => self::$user->mail_address,
            'bcc' => str_random(501),
            'subject' => 'test',
            'body' => 'testtest',
            'access_token' => $this->getToken(self::$user->account, 'testtest'),
        ];
        $this->post('/v1/mail', $data);

        $this->assertEquals(400, $this->response->getStatusCode());
    }

    /**
     * 送信失敗（件名不正）
     *
     * @return void
     */
    public function testInvalidSubject()
    {
        $data = [
            'to' => self::$user->mail_address,
            'subject' => str_random(51),
            'body' => 'testtest',
            'access_token' => $this->getToken(self::$user->account, 'testtest'),
        ];
        $this->post('/v1/mail', $data);

        $this->assertEquals(400, $this->response->getStatusCode());
    }

    /**
     * 送信失敗（本文不正）
     *
     * @return void
     */
    public function testInvalidBody()
    {
        $data = [
            'to' => self::$user->mail_address,
            'subject' => 'test',
            'body' => str_random(501),
            'access_token' => $this->getToken(self::$user->account, 'testtest'),
        ];
        $this->post('/v1/mail', $data);

        $this->assertEquals(400, $this->response->getStatusCode());
    }

    /**
     * 送信失敗（アドレス不正）
     *
     * @return void
     */
    public function testSendFailed()
    {
        $data = [
            'to' => self::$user->mail_address,
            'subject' => 'PHPUnit test',
            'body' => 'This mail sent from PHPUnit.',
            'access_token' => $this->getToken(self::$user->account, 'testtest'),
        ];
        $this->post('/v1/mail', $data);

        $this->assertEquals(500, $this->response->getStatusCode());
    }

    /**
     * 送信成功
     *
     * @return void
     */
    public function testSendSuccess()
    {
        // phpunit.xmlに実際にメールを送信するメールアドレスを定義してください。
        $address = env('SEND_ADDRESS');
        $data = [
            'to' => $address,
            'subject' => 'PHPUnit test',
            'body' => 'This mail sent from PHPUnit.',
            'access_token' => $this->getToken(self::$user->account, 'testtest'),
        ];
        $this->post('/v1/mail', $data);

        $this->assertEquals(200, $this->response->getStatusCode());
    }
}
