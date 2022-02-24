<?php 
namespace futureactivities\rest\controllers\v1;

use Craft;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\data\ActiveDataFilter;
use yii\web\ForbiddenHttpException;
use craft\elements\User;

use futureactivities\rest\traits\ActionRemovable;
use futureactivities\rest\errors\BadRequestException;
use futureactivities\rest\Plugin;

class UserController extends ActiveController
{
    use ActionRemovable;
    
    public $modelClass = 'craft\records\User';
 
    /**
     * User Login
     * Creates and returns a token
     */
    public function actionAuth()
    {
        $request = Craft::$app->request;
        
        $loginName = $request->getParam('username');
        $password = $request->getParam('password');
        
        $user = Craft::$app->getUsers()->getUserByUsernameOrEmail($loginName);
        
        if (!$user || $user->password === null)
            throw new \Exception('Invalid user and/or password.');
            
        if (!$user->authenticate($password)) {
            Craft::$app->users->handleInvalidLogin($user);
            throw new \Exception('Invalid user and/or password.');
        }
        
        Craft::$app->users->handleValidLogin($user);
        
        $token = Plugin::getInstance()->user->generateToken($user->id);
        
        return [
            'token' => $token 
        ];
    }
    
    /**
     * List all users
     */
    public function actionIndex()
    {
        $this->checkAccess(null);
        
        return User::find()->all();
    }
    
    /**
     * Creates a new user
     */
    public function actionCreate()
    {
        $request = \Craft::$app->request;
        $customerData = !empty($request->getBodyParams()) ? $request->getBodyParams() : json_decode($request->getRawBody(), true);
        
        $user = new User();
        $user->username = isset($customerData['customer']['username']) ? $customerData['customer']['username'] : $customerData['customer']['email'];
        $user->email = $customerData['customer']['email'];
        $user->firstName = $customerData['customer']['firstName'];
        $user->lastName = $customerData['customer']['lastName'];
        
        foreach($user->getFieldLayout()->getFields() AS $field) {
            $fieldHandle = $field->handle;
            if (isset($customerData['customer'][$fieldHandle]))
                $user->$fieldHandle = $customerData['customer'][$fieldHandle];
        }
        
        if ($password = $customerData['password'])
            $user->newPassword = $password;
        
        if (!\Craft::$app->elements->saveElement($user))
            throw new BadRequestException('Please correct any errors and try again.', $user->getErrors());
            
        return [
            'success' => true        
        ];
    }
    
    /**
     * Get a users account
     */
    public function actionView($id)
    {
        $this->checkAccess($id);
        
        return \Craft::$app->users->getUserById($id);
    }
    
    /**
     * Update a users account
     */
    public function actionUpdate($id)
    {
        $this->checkAccess($id);
        
        $request = \Craft::$app->request;
        $user = \Craft::$app->users->getUserById($id);
        
        $customerData = !empty($request->getBodyParams()) ? $request->getBodyParams() : json_decode($request->getRawBody(), true);
        if (empty($customerData))
            throw new BadRequestException('Missing user data.');
            
        if (isset($customerData['customer']) && isset($customerData['customer']['email']))
            $user->email = $customerData['customer']['email'];
            
        if (isset($customerData['customer']) && isset($customerData['customer']['firstName']))
            $user->firstName = $customerData['customer']['firstName'];
            
        if (isset($customerData['customer']) && isset($customerData['customer']['lastName']))
            $user->lastName = $customerData['customer']['lastName'];
        
        foreach($user->getFieldLayout()->getFields() AS $field) {
            $fieldHandle = $field->handle;
            if (isset($customerData['customer'][$fieldHandle]))
                $user->$fieldHandle = $customerData['customer'][$fieldHandle];
        }
            
        if (isset($customerData['password']))
            $user->newPassword = $customerData['password'];
        
        if (!Craft::$app->elements->saveElement($user))
            throw new BadRequestException('Please correct any errors and try again.', $user->getErrors());
            
        return [
            'success' => true
        ];
    }
    
    /**
     * Verify the user token.
     * 
     * @throws \BadRequestException
     * 
     * @returns Boolean
     */
    public function actionVerify()
    {
        if (! array_key_exists('verifyToken', \Craft::$app->request->getBodyParams()))
            throw new BadRequestException('verify_token parameter not found in the body.');
        
        return Plugin::getInstance()->user->verifyToken(\Craft::$app->request->getBodyParam('verifyToken'));
    }
    
    /**
     * Send password reset link
     */
    public function actionSendPasswordReset()
    {
        $loginName = \Craft::$app->getRequest()->getBodyParam('username');
        $user = \Craft::$app->getUsers()->getUserByUsernameOrEmail($loginName);
        if (!$user)
            throw new BadRequestException('Invalid user.');
        
        return \Craft::$app->getUsers()->sendPasswordResetEmail($user);
    }
    
    /**
     * Reset a users password
     */
    public function actionDoPasswordReset()
    {
        $code = \Craft::$app->getRequest()->getRequiredBodyParam('code');
        $uid = \Craft::$app->getRequest()->getRequiredParam('id');
        $userToProcess = \Craft::$app->getUsers()->getUserByUid($uid);
        $isCodeValid = \Craft::$app->getUsers()->isVerificationCodeValidForUser($userToProcess, $code);

        if (!$userToProcess || !$isCodeValid)
            throw new BadRequestException('Invalid password reset token.');
            
        $userToProcess->newPassword = \Craft::$app->getRequest()->getRequiredBodyParam('newPassword');
        $userToProcess->setScenario(User::SCENARIO_PASSWORD);
        
        return \Craft::$app->getElements()->saveElement($userToProcess);
    }
    
    /**
     * Options request
     */
    public function actionOptions()
    {
        return true;
    }
    
    /**
     * Check user is authorised to edit users.
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        $currentUser = Plugin::getInstance()->user->auth();
        
        if ($currentUser->id == $action)
            return true;
            
        if (!Craft::$app->userPermissions->doesUserHavePermission($currentUser->id, 'editUsers'))
            throw new ForbiddenHttpException();
    }
}