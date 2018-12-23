<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Http\Requests\Api\AuthorizationRequest;
use Zend\Diactoros\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\AuthorizationServer;

class AuthorizationsController extends Controller
{
    public function socialStore($type,SocialAuthorizationRequest $request)
    {
    	if(!in_array($type,['weixin'])){
    		return $this->response->errorBadRequest();
    	}

    	$driver = \Socialite::driver($type);
    	try{

    		if($code = $request->code){
    			$response = $driver->getAccessTokenResponse($code);
    			$token = array_get($response,'access_token');

    		}else{
    			$token = $request->access_token;

    			if($type == 'weixin'){
    				$driver->setOpenId($request->openid);
    			}
    		}

    		$oauthUser = $driver->userFromToken($token);
    		}catch(\Exception $e){
    			return $this->response->errorUnauthorized('参数错误，未获取用户信息');
    		}
    		switch($type){
    			case 'weixin':
    			$unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid'):null;

    			if($unionid){
    				$user = User::where('weixin_openid',$unionid)->first();
    			} else{
    				$user = User::where('weixin_openid',$oauthUser->getId())->first();
    			}
    			//如果没有用户，默认创建一个用户
    			if(!$user){
    				$user = User::create([
    					'name' => $oauthUser->getNickname(),
    					'avatar' => $oauthUser->getAvatar(),
    					'weixin_openid' => $oauthUser->getId(),
    					'weixin_unionid' => $unionid,
    				]);
    			}
    			break;
    		}

    		return $this->respondWithToken($token)->setStatusCode(201);
    }//socialStore函数结束

    public function store(AuthorizationRequest $originRequest,AuthorizationServer $server,ServerRequestInterface $serverRequest)
    {
        try{
            return $server->respondToAccessTokenRequest($serverRequest,new Psr7Response)->withStatus(201);
        } catch(OAuthServerException $e){
            return $this->response->errorUnauthorized($e->getMessage());
        }
    }
    //封装respondWithToken($token)
    public function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL()*60
        ]);
    }
    //刷新token
    public function update(AuthorizationServer $server,ServerRequestInterface $serverRequest)
    {
       try{
        return $server->respondToAccessTokenRequest($serverRequest,new Psr7Response);
       } catch(OAuthServerException $e){
        return $this->response->errorUnauthorized($e->getMessage());
       }
    }
    //销毁token
    public function destroy()
    {
        $this->user()->token()->revoke();
        return $this->response->noContent();
    }
}
