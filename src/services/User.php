<?php
namespace futureactivities\rest\services;

use yii\base\Component;
use futureactivities\rest\records\UserToken as UserTokenRecord;
use futureactivities\rest\Plugin;
use yii\web\ForbiddenHttpException;

class User extends Component
{
    /**
     * Check if an auth token is valid and return the associated user
     */
    public function auth()
    {
        $request = \Craft::$app->request;
        
        if (!isset($request->headers['authorization']))
            throw new ForbiddenHttpException('Missing authorization header.');
        
        // Find Bearer token
        preg_match('/Bearer\s(\S+)/', $request->headers['authorization'], $matches);
        $token = $matches[1];
        
        // Get user ID associated with this token
        $tokenRecord = UserTokenRecord::find()->where(['token' => $token])->one();
        if (!$tokenRecord)
            throw new ForbiddenHttpException('Invalid token.');
        
        // Find the user
        $user = \Craft::$app->users->getUserById($tokenRecord->userId);
        if (!$user)
            throw new ForbiddenHttpException('Invalid token.');
        
        return $user;
    }
    
    /**
     * Check if a token is valid
     */
    public function verifyToken($token)
    {
        $tokenRecord = UserTokenRecord::find()
            ->where(['token' => $token])
            ->one();
            
        if (!$tokenRecord)
            return false;
            
        return true;
    }
    
    /**
     * Generate and save an authorisation token
     */
    public function generateToken($userId)
    {
        $token = bin2hex(random_bytes(16));
        
        $userTokenRecord = UserTokenRecord::find()->where(['userId' => $userId])->one();
        
        if (!$userTokenRecord)
            $userTokenRecord = new UserTokenRecord();
        
        $userTokenRecord->token = $token;
        $userTokenRecord->userId = $userId;
        $userTokenRecord->save();
        
        return $token;
    }
}