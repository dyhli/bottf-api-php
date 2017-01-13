<?php
/**
 * Require the PHP library using Composer's autoloader
 *
 * If you are not using Composer, you need to load the PHP library with your own
 * PSR-4 autoloader.
 */
require_once '../vendor/autoload.php';

/**
 * Create an instance of the API object
 *
 * In order to use Bot.tf's API, you require an API key. You can obtain one by
 * visiting https://panel.bot.tf/
 * Keep in mind that only customers can obtain a key.
 */
use Dyhli\BotTF;

$api = new BotTF\API('INSERT-YOUR-API-KEY-HERE');

/**
 * Now we are ready to use the API!
 *
 * In this example we use all the possible `Exception` types, however you can
 * also catch all exceptions at once by using:
 *
 * try {
 *     // your code here  
 * } catch(Exception $e) {
 *     // exception here
 * }
 */
try {

    // Create a webhook instance to process any
    // incoming webhooks
    $webhook = new BotTF\Webhook($api);

    // Let's run the webhook listener, if no webhook was found
    // or if the webhook was invalid, it will throw a `WebhookException`
    $webhook->run();

    // Example usage:
    $data = $webhook->eventData();

    switch($data->type) {

        // We have received a trade offer
        case 'tradeReceived':
            // Your code here to check if we should accept the offer
            $api->offers->accept($data->tradeOfferId);
        break;

        // Trade offer state has changed
        case 'tradeStateChanged':
            $offer = $api->offers->get($data->tradeOfferId);

            if($offer->isPending()) {
                // Trade offer is pending, do something
            } elseif($offer->hasFailed()) {
                // Trade offer has failed, do something
            } elseif($offer->isCompleted()) {
                // Trade offer is finished (completed), do something
            }
        break;

        // Please read the docs for more event types

    }

    /**
     * Please keep in mind that Bot.tf will stop sending a webhook if a
     * HTTP status code of 2xx was returned.
     *
     * If something went wrong on your server to process the webhook, make
     * sure to return a different HTTP status code! Please read the
     * docs for more information.
     *
     * @link https://github.com/dyhli/bottf-api-php/wiki
     */

} catch(BotTF\Exceptions\WebhookException $e) {
    echo 'Webhook error: ' . $e->getMessage();
} catch(BotTF\Exceptions\GeneralException $e) {
    echo 'General error: ' . $e->getMessage();
} catch(BotTF\Exceptions\RequestException $e) {
    echo 'HTTP request error: ' . $e->getMessage();
} catch(BotTF\Exceptions\HelperException $e) {
    echo 'Helper error: ' . $e->getMessage();
}
?>
