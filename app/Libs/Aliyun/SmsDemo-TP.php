<?php
/**
 * 短信验证码类
 * Env可配置是否打印sms信息
 * [log]
 * log_sms_record = true;
 * 
 * // 调用示例：
 * $response = Sms::sendSms($phone);
 * echo "发送短信(sendSms)接口返回的结果:\n";
 * print_r($response);
 *
 * //验证
 * Sms::check($phone, $code);
 */

namespace app\common\model;

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
use think\Env;
use think\Log;
use think\Session;

// 加载区域结点配置 必须
Config::load();

class Sms {

  public static $acsClient = null;
  private static $key = 'Viker';
  // private static $expire = 300; //过期时间设为5min

  /**
   * 取得AcsClient
   *
   * @return DefaultAcsClient
   */
  public static function getAcsClient() {
      //产品名称:云通信流量服务API产品,开发者无需替换
      $product = "Dysmsapi";

      //产品域名,开发者无需替换
      $domain = "dysmsapi.aliyuncs.com";

      // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
      $accessKeyId = getMergeConf('aliyun.sms_key'); // AccessKeyId

      $accessKeySecret = getMergeConf('aliyun.sms_secret'); // AccessKeySecret

      // 暂时不支持多Region
      $region = "cn-hangzhou";

      // 服务结点
      $endPointName = "cn-hangzhou";


      if(static::$acsClient == null) {

          //初始化acsClient,暂不支持region化
          $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

          // 增加服务结点
          DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

          // 初始化AcsClient用于发起请求
          static::$acsClient = new DefaultAcsClient($profile);
      }
      return static::$acsClient;
  }

  /**
   * 发送短信
   * @return stdClass
   */
  public static function sendSms($phone, $tempCode = '', $signName = '') {

      // 初始化SendSmsRequest实例用于设置发送短信的参数
      $request = new SendSmsRequest();

      //可选-启用https协议
      //$request->setProtocol("https");

      // 必填，设置短信接收号码
      $request->setPhoneNumbers($phone);

      // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
      $signName = !empty($signName) ? $signName : getMergeConf('aliyun.sms_sign_name');
      $request->setSignName($signName);

      // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
      $tempCode = !empty($tempCode) ? $tempCode : getMergeConf('aliyun.sms_temp_code');
      $request->setTemplateCode($tempCode);

      // 生成验证码字符串
      $code = self::randStr();

      // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
      $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
          "code"=>$code,
          // "product"=>"dsd"
      ), JSON_UNESCAPED_UNICODE));

      // 可选，设置流水号
      // $request->setOutId("yourOutId");

      // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
      // $request->setSmsUpExtendCode("1234567");

      // 发起访问请求
      $acsResponse = static::getAcsClient()->getAcsResponse($request);

      if ($acsResponse->Code == 'OK') {
        self::save($phone, $code);
      }

      Env::get('log.log_sms_record') ? 
      Log::record("Sms Send Info: [ Phone => {$phone}, Code => {$code}, ResCode => {$acsResponse->Code}, Message => {$acsResponse->Message} ]") 
      : '';

      return $acsResponse;
  }


  /*保存验证码到session*/
  private static function save($phone, $code) {
    $seKey = self::authcode(self::$key);
    $seCode = self::authcode($code);
    $seValue['phone'] = $phone;
    $seValue['code'] = $seCode;
    // $seValue['time'] = time();
    Session::set($seKey, $seValue);
  }


  // 验证
  public static function check($phone, $code) {
    if (empty($phone) || empty($code)) {
      return false;
    }
    $seKey = self::authcode(self::$key);
    $seValue = Session::get($seKey);
    if (empty($seValue)) {
      return false;
    }
    if ($seValue['phone'] == $phone && $seValue['code'] == self::authcode($code)) {
      return true;
    }
    return false;
  }


  // 加密 仿照think\Captcha
  private static function authcode($str)
  {
    $key = substr(md5(self::$key), 5, 8);
    $str = substr(md5($str), 8, 10);
    return md5($key . $str);
  }


  // 获取随机字符串
  public static function randStr($type = 1, $len = 6) {
    switch ($type) {
      case 1:
        $arr = range(0, 9);
        break;
      case 2:
        $arr = array_merge(range('a', 'z'), range('A', 'Z'));
        break;
      case 3:
      default:
        $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        break;
    }
    return substr(str_shuffle(implode('', $arr)), 0, $len);
  }

}