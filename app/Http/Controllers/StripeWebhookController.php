<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, env('STRIPE_WEBHOOK_SECRET'));
        } catch(\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'transfer.created':
              $transfer = $event->data->object;
            case 'transfer.reversed':
              $transfer = $event->data->object;
            case 'transfer.updated':
              $transfer = $event->data->object;
            // ... handle other event types
            default:
              echo 'Received unknown event type ' . $event->type;
          }

        return response('Webhook received', 200);
    }
}
