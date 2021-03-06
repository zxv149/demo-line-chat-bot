<?php

namespace Tests\Feature\Services;

use App\Services\LineBotService;
use Tests\TestCase;

class LineBotServiceTest extends TestCase
{
    /** @var  LineBotService */
    private $lineBotService;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->lineBotService = app(LineBotService::class);
    }

    public function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    public function testPushMessage()
    {
        $this->markTestSkipped('OK!');
        $response = $this->lineBotService->pushMessage('Test from laravel.');

        $this->assertEquals(200, $response->getHTTPStatus());
    }

    public function testPushMessageWithObjectDeprecated()
    {
        $target = $this->lineBotService->buildTemplateMessageBuilderDeprecated(
            'https://i.imgur.com/BlBH2HE.jpg',
            'https://github.com/Tai-ch0802/php-crawler-chat-bot',
            '自己玩的linebot'
        );
        $response = $this->lineBotService->pushMessage($target);

        $this->assertEquals(200, $response->getHTTPStatus());
    }

    public function testPushMessageWithObject()
    {
        if (empty(env('LINEBOT_TOKEN'))) {
            $this->markTestSkipped('Invalid LINEBOT_TOKEN');
        }
        $data = [
            [
                'imagePath' => 'https://i.imgur.com/BlBH2HE.jpg',
                'directUri' => 'https://github.com/Tai-ch0802/php-crawler-chat-bot',
                'label' => '自己玩的linebot',
            ],
            [
                'imagePath' => 'https://i.imgur.com/XJkiup5.jpg',
                'directUri' => 'https://zh.wikipedia.org/wiki/%E7%8C%AB',
                'label' => '喵星人',
            ],
            [
                'imagePath' => 'https://pbs.twimg.com/profile_images/839721704163155970/LI_TRk1z_400x400.jpg',
                'directUri' => 'https://www.google.com.tw/',
                'label' => 'Google',
            ],
            [
                'imagePath' => 'https://www.codeforest.net/wp-content/uploads/2013/04/laravel-logo-big-570x398.png',
                'directUri' => 'https://laravel.com/',
                'label' => 'laravel',
            ],
            [
                'imagePath' => 'https://cdn2.ettoday.net/images/2471/2471912.jpg',
                'directUri' => 'https://ithelp.ithome.com.tw/ironman',
                'label' => '鐵人賽',
            ],
            [
                'imagePath' => 'https://cdn2.ettoday.net/images/2709/2709694.jpg',
                'directUri' => 'https://login.apiary.io/login',
                'label' => 'Blueprint',
            ],
            [
                'imagePath' => 'https://d.line-scdn.net/stf/line-lp/1200x630.png',
                'directUri' => 'https://developers.line.me/en/',
                'label' => 'LINE',
            ],
        ];

        $targets = $this->lineBotService->buildTemplateMessageBuilder($data, 'test push!');

        foreach ($targets as $target) {
            $response = $this->lineBotService->pushMessage($target);
            $this->assertEquals(200, $response->getHTTPStatus());
        }
    }
}
