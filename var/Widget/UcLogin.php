<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 登录动作
 *
 * @category typecho
 * @package Widget
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 * @version $Id$
 */

/**
 * 登录组件
 *
 * @category typecho
 * @package Widget
 * @copyright Copyright (c) 2008 Typecho team (http://www.typecho.org)
 * @license GNU General Public License 2.0
 */
class Widget_UcLogin extends Widget_Abstract_Users implements Widget_Interface_Do
{
    /**
     * 初始化函数
     *
     * @access public
     * @return void
     */
    public function action()
    {
        /** 如果已经登录 */
        if ($this->user->hasLogin()) {
            /** 直接返回 */

            $this->response->redirect($this->options->index);
        }

        $accessToken = isset($_GET['access_token'])?$_GET['access_token']:'';
        if($accessToken){
            $url = 'http://121.41.85.236:9527/api/getuserinfo?appid=1011&appkey=2a9cc51a7055b308&token='.$accessToken;
            $resp = file_get_contents($url);
            $obj = json_decode($resp,1);
		if($obj['code']==0){
                $ucid = $obj['data'][0]['uid'];

                $select = $this->db->select()
                    ->from('table.uc')
                    ->where('ucid = ?', $ucid)
                    ->limit(1);
                $map = $this->db->fetchRow($select);
	        if(!empty($map)){
                    //old user
                    $uid = $map['uid'];
                    $this->user->simpleLogin($uid);
                    $user = $this->user->stack[0];
    Typecho_Cookie::set('__typecho_uid', $user['uid'], 0);
                    Typecho_Cookie::set('__typecho_authCode', Typecho_Common::hash($user['authCode']), 0);

                }else{

                    //create
                    $dataStruct = array(
                        'name'      =>  'UC用户_'.$obj['data'][0]['tel'],
                        'mail'      =>  $obj['data'][0]['email'],
                        'screenName'=>  'UC用户_'.$obj['data'][0]['tel'],
                        'password'  =>  md5(time()),
                        'created'   =>  time(),
                        'group'     =>  'subscriber'
                    );

                    $dataStruct = $this->pluginHandle()->register($dataStruct);

                    $uid = $this->insert($dataStruct);

                    $this->insert(array('uid'=>$uid,'ucid'=>$ucid),'uc');
                    $this->user->simpleLogin($uid);
                    $user = $this->user->stack[0];
                    Typecho_Cookie::set('__typecho_uid', $user['uid'], 0);
                    Typecho_Cookie::set('__typecho_authCode', Typecho_Common::hash($user['authCode']), 0);
                }
            }else{
                $this->response->redirect($this->request->referer);
            }
        }

        /** 跳转验证后地址 */
	if (NULL != $this->request->referer) {
            $this->response->redirect($this->request->referer);
        } else if (!$this->user->pass('contributor', true)) {
            /** 不允许普通用户直接跳转后台 */
            $this->response->redirect($this->options->index);
        } else {
            $this->response->redirect($this->options->adminUrl);
        }
    }
}
