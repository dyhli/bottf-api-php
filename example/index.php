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

    // let's get a nice list of our bots!
    $bots = $api->bots->list();

    // let's convert 50 scraps to refined
    $refined = $api->helpers->toRefined(50); // will return 5.55

    // let's convert the refined back to scraps
    $scraps = $api->helpers->toScrap($refined); // will return 50

    // let's check if this user is a scammer by checking if
    // they are marked on Steamrep.com
    // requires a SteamID64
    $isScammer = $api->helpers->isScammer('76561198083083810');

    // let's get a trade offer
    $offer = $api->offers->get(0000000);

    // Here you can execute your code to check what to do with the
    // offer. Simply add ->accept() / ->decline() / ->cancel() to $offer
    // and you're good to go! Example:
    $offer->accept();

    // if you already knew you'd accept this offer without retrieving
    // it from the API first, you can accept the offer immediately:
    $api->offers->accept(0000000);

    // we can check our API usage (rate limits)
    $rate = $api->getRate();

} catch(BotTF\Exceptions\GeneralException $e) {
    echo 'General error: ' . $e->getMessage();
} catch(BotTF\Exceptions\RequestException $e) {
    echo 'HTTP request error: ' . $e->getMessage();
} catch(BotTF\Exceptions\HelperException $e) {
    echo 'Helper error: ' . $e->getMessage();
}
?>
