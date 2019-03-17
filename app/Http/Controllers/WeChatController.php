<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Payment\Order;

class WeChatController extends Controller
{
    /**
     * ����΢�ŵ�������Ϣ
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.');

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "��ӭ��ע overtrue��";
        });


        $app = $this->payment();

        $response = $app->handlePaidNotify(function($message, $fail){

            if ($message['return_code'] === 'SUCCESS') { // return_code ��ʾͨ��״̬��������֧��״̬
                // �û��Ƿ�֧���ɹ�
                echo 'success';
                Log::info('�ɹ���');
            } else {
                Log::error('ʧ����');
                return $fail('ͨ��ʧ�ܣ����Ժ���֪ͨ��');
            }

            return true; // ���ش������
        });

        $response->send(); // return $response;

        return $app->server->serve();
    }



}
