<?php

namespace NotificationChannels\Cmsms\Test;

use Illuminate\Support\Arr;
use NotificationChannels\Cmsms\CmsmsMessage;
use NotificationChannels\Cmsms\Exceptions\InvalidMessage;

class CmsmsMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $message = new CmsmsMessage;

        $this->assertInstanceOf(CmsmsMessage::class, $message);
    }

    /** @test */
    public function it_can_accept_body_content_when_created()
    {
        $message = new CmsmsMessage('Foo');

        $this->assertEquals('Foo', Arr::get($message->toXmlArray(), 'BODY'));
    }

    /** @test */
    public function it_supports_create_method()
    {
        $message = CmsmsMessage::create('Foo');

        $this->assertInstanceOf(CmsmsMessage::class, $message);
        $this->assertEquals('Foo', Arr::get($message->toXmlArray(), 'BODY'));
    }

    /** @test */
    public function it_can_set_body()
    {
        $message = (new CmsmsMessage)->body('Bar');

        $this->assertEquals('Bar', Arr::get($message->toXmlArray(), 'BODY'));
    }

    /** @test */
    public function it_can_set_originator()
    {
        $message = (new CmsmsMessage)->originator('APPNAME');

        $this->assertEquals('APPNAME', Arr::get($message->toXmlArray(), 'FROM'));
    }

    /** @test */
    public function it_cannot_set_an_empty_originator()
    {
        $this->setExpectedException(InvalidMessage::class);

        (new CmsmsMessage)->originator('');
    }

    /** @test */
    public function it_cannot_set_an_originator_thats_too_long()
    {
        $this->setExpectedException(InvalidMessage::class);

        (new CmsmsMessage)->originator('0123456789ab');
    }

    /** @test */
    public function it_can_set_reference()
    {
        $message = (new CmsmsMessage)->reference('REFERENCE123');

        $this->assertEquals('REFERENCE123', Arr::get($message->toXmlArray(), 'REFERENCE'));
    }

    /** @test */
    public function it_cannot_set_an_empty_reference()
    {
        $this->setExpectedException(InvalidMessage::class);

        (new CmsmsMessage)->reference('');
    }

    /** @test */
    public function it_cannot_set_a_reference_thats_too_long()
    {
        $this->setExpectedException(InvalidMessage::class);

        (new CmsmsMessage)->reference('UmSM7h8I1nySJm0A8IqcU3LDswO7ojfJn');
    }

    /** @test */
    public function it_cannot_set_a_reference_that_contains_non_alpha_numeric_values()
    {
        $this->setExpectedException(InvalidMessage::class);

        (new CmsmsMessage)->reference('@#$*A*Sjks87');
    }

    /** @test */
    public function it_xml_contains_only_filled_parameters()
    {
        $message = new CmsmsMessage('Foo');

        $this->assertEquals([
            'BODY' => 'Foo',
        ], $message->toXmlArray());
    }
}
