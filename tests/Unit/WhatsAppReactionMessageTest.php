<?php

namespace Axolotesource\LaravelWhatsappApi\Tests\Unit;

use Axolotesource\LaravelWhatsappApi\Tests\TestCase;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Reaction\ReactionMessage;
use Exception;

class WhatsAppReactionMessageTest extends TestCase
{
    public function test_can_create_reaction_message()
    {
        $reaction = WhatsAppMessages::reaction('521234567890');
        $this->assertInstanceOf(ReactionMessage::class, $reaction);
    }

    public function test_reaction_message_to_array_contains_message_id_and_emoji()
    {
        $reaction = WhatsAppMessages::reaction('521234567890')
            ->messageId('wamid.HBgNMTIzNDU2Nzg5MBUCABEYEjQxRjcwNjdFQUE')
            ->emoji('👍');

        $payload = $reaction->toArray();

        $this->assertEquals('reaction', $payload['type']);
        $this->assertEquals('wamid.HBgNMTIzNDU2Nzg5MBUCABEYEjQxRjcwNjdFQUE', $payload['reaction']['message_id']);
        $this->assertEquals('👍', $payload['reaction']['emoji']);
    }

    public function test_reaction_message_with_love_emoji()
    {
        $reaction = WhatsAppMessages::reaction('521234567890')
            ->messageId('wamid.ABC123')
            ->emoji('❤️');

        $payload = $reaction->toArray();

        $this->assertEquals('❤️', $payload['reaction']['emoji']);
    }

    public function test_reaction_message_to_remove_reaction_with_empty_emoji()
    {
        $reaction = WhatsAppMessages::reaction('521234567890')
            ->messageId('wamid.ABC123')
            ->emoji('');

        $payload = $reaction->toArray();

        $this->assertEquals('wamid.ABC123', $payload['reaction']['message_id']);
        $this->assertEquals('', $payload['reaction']['emoji']);
    }

    public function test_reaction_message_includes_recipient()
    {
        $reaction = WhatsAppMessages::reaction('521234567890')
            ->messageId('wamid.ABC123')
            ->emoji('🎉');

        $payload = $reaction->toArray();

        $this->assertEquals('521234567890', $payload['to']);
        $this->assertEquals('whatsapp', $payload['messaging_product']);
    }

    public function test_reaction_message_throws_exception_without_message_id()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Message id is required for reaction messages");

        WhatsAppMessages::reaction('521234567890')
            ->emoji('👍')
            ->toArray();
    }

    public function test_reaction_message_throws_exception_without_emoji()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Emoji is required for reaction messages");

        WhatsAppMessages::reaction('521234567890')
            ->messageId('wamid.ABC123')
            ->toArray();
    }

    public function test_reaction_message_throws_exception_without_any_data()
    {
        $this->expectException(Exception::class);

        WhatsAppMessages::reaction('521234567890')->toArray();
    }

    public function test_reaction_message_supports_fluent_chaining()
    {
        $reaction = WhatsAppMessages::reaction('521234567890')
            ->messageId('wamid.ABC123')
            ->emoji('🔥');

        $this->assertInstanceOf(ReactionMessage::class, $reaction);
    }
}
