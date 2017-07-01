<?php

/**
 * テストの基底クラス
 */
abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->createApplication();
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * トークン取得
     *
     * @param string $account
     * @return string
     */
    protected function getToken(string $account): string
    {
        $this->makeToken($account);
        $json = json_decode($this->response->getContent());
        return $json->access_token;
    }

    /**
     * トークン生成URL呼び出し
     *
     * @param string $account
     * @return void
     */
    protected function makeToken(string $account)
    {
        $this->post('/v1/auth/login', [
            'account' => $account,
            'password' => 'testtest',
        ]);
    }
}
