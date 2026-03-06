<?php

namespace App\Http\Controllers;

use App\Models\Central\Tenant;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    /**
     * Stripe Webhook for Tenants
     *
     * @return array
     *
     * @throws RequestException
     */
    public function stripeConnect(Request $request)
    {
        $this->log(
            "Stripe Connect Webhook: \n"
            ."\n```".json_encode($request->header()).'```'
            ."\n```".$request->getContent().'```'
        );
        $payload = $request->getContent();
        $sig_header = $request->header('stripe-signature');

        $event = null;

        // Verify webhook signature and extract the event.
        // See https://stripe.com/docs/webhooks/signatures for more information.
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                env('STRIPE_WEBHOOK_CONNECT_SECRET')
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload.
            $this->log('Invalid Payload', 'critical');
            abort(400, 'Invalid Payload');
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid Signature.
            $this->log('Invalid Signature', 'critical');
            abort(400, 'Invalid Signature');
        }

        $session = $event->data->object;
        $stripe_account_id = $event->account;

        // Fulfill the purchase.
        /** @var Tenant $tenant */
        $tenant = app(Tenant::class)->where('data->stripe_account_id', $stripe_account_id)->first();
        if (! $tenant) {
            $this->log('Webhook Tenant Not Found. Stripe Account ID: '.$stripe_account_id, 'critical');
            abort(400, 'Tenant Not Found');
        }

        $url = $tenant->primaryDomain()->getFullDomainUrl('/webhook/stripe-connect');

        return Http::withHeaders([
            'user-agent' => 'Simi Central - '.config('app.env'),
            'signature' => Hash::make(env('STRIPE_WEBHOOK_CONNECT_SECRET').intval(time() / 1000)),
        ])
            ->post($url, $request->all())
            ->throw()
            ->json();
    }
}
