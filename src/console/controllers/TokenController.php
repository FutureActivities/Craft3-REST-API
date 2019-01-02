<?php
namespace futureactivities\rest\console\controllers;

use Craft;
use craft\helpers\Db;
use craft\helpers\Console;
use yii\console\Controller;
use yii\console\ExitCode;

use futureactivities\rest\records\UserToken;

class TokenController extends Controller
{

    /**
     * Expire user authentication tokens older than X hours
     *
     * @return int
     */
    public function actionExpire($seconds = 3600): int
    {
        $dateTime = new \DateTime();
        $dateMinus = $dateTime->sub(new \DateInterval('PT'.$seconds.'S'));
        $expireBefore = Db::prepareDateForDb($dateMinus);

        $count = UserToken::deleteAll('dateCreated < :date', ['date' => $expireBefore]);
        
        $this->stdout($count." Tokens Expired." . PHP_EOL, Console::FG_YELLOW);
        return ExitCode::OK;
    }
}
