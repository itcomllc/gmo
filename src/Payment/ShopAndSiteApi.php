<?php

/**
 * @file
 * Shop and Site API for GMO SDK.
 */

namespace GMO\Payment;

/**
 * Shop and Site API of GMO Payment.
 *
 * Shop ID (ショップ ID)
 * --ShopID string(13) not null.
 *
 * Shop password (ショップパスワード)
 * --ShopPass string(10) not null.
 *
 * Site ID (サイト ID)
 * --SiteID string(13) not null.
 *
 * Site password (サイトパスワード)
 * --SitePass string(20) not null.
 *
 * $data = array('key' => 'value', ...)
 *   It contains not required and conditional required fields.
 *
 * Return result
 *   It will be return only one or multiple records.
 *   Multiple records joined with '|' whatever success or failed.
 */
class ShopAndSiteApi extends Api
{

  /**
   * Object constructor.
   */
  public function __construct($params = array())
  {
    $params['shop_id']   = config('gmo.shop.id');
    $params['shop_pass'] = config('gmo.shop.password');
    $params['site_id']   = config('gmo.site.id');
    $params['site_pass'] = config('gmo.site.password');
    parent::__construct($params);
  }

  /**
   * Register the card that was used to trade in the specified order ID.
   *
   * @Input parameters
   *
   * Order ID (オーダーID)
   * --OrderID string(27) not null.
   *
   * Member ID (会員 ID)
   * --MemberID string(60) not null.
   *
   * Card registration serial number mode (カード登録連番モード)
   * --SeqMode string(1) null default 0.
   *
   *   Allowed values:
   *     0: Logical mode (default)
   *     1: Physical mode
   *
   * Default flag (デフォルトフラグ)
   * --DefaultFlag string(1) null default 0.
   *
   *   Allowed values:
   *     0: it is not the default card (default)
   *     1: it will be the default card
   *
   * Holder name (名義人)
   * --HolderName string(50) null.
   *
   * @Output parameters
   *
   * Card registration serial number (カード登録連番)
   * --CardSeq integer(1)
   *
   * Card number (カード番号)
   * --CardNo string(16)
   *   Asterisk with the exception of the last four digits.
   *   下 4 桁を除いて伏字
   *
   * Destination code (仕向先コード)
   * --Forward string(7)
   *   Destination code when performing a validity check.
   *   有効性チェックを行ったときの仕向先 コード
   */
  public function tradedCard($order_id, $member_id, $data = array())
  {
    if (!is_array($data)) {
      $data = array();
    }
    $data['order_id']  = $order_id;
    $data['member_id'] = $member_id;
    return $this->callApi('tradedCard', $data);
  }

  /**
   * It will return the token that is required in subsequent settlement deal.
   *
   * @Input parameters
   *
   * SiteID and SitePass are required if MemberID exist.
   *
   * Access ID (取引 ID)
   * --AccessID string(32) not null.
   *
   * Access Pass (取引パスワード)
   * --AccessPass string(32) not null.
   *
   * Order ID (オーダーID)
   * --OrderID string(27) not null.
   *
   * Member ID (会員 ID)
   * --MemberID string(60) conditional null.
   *
   *   MemberID is required if need CreateMember.
   *
   * Member Name (会員名)
   * --MemberName string(255) null.
   *
   * Members create flag (会員作成フラグ)
   * --CreateMember string(1) conditional null.
   *
   *   It will specify the operation when the member does not exist.
   *   Allowed values:
   *     0: Don't create. If a member does not exist, it returns an error.
   *     1: Create member. If a member does not exist, I will create new.
   *
   * Client Field 1 (加盟店自由項目 1)
   * --ClientField1 string(100) null.
   *
   * Client Field 2 (加盟店自由項目 2)
   * --ClientField2 string(100) null.
   *
   * Client Field 3 (加盟店自由項目 3)
   * --ClientField3 string(100) null.
   *
   * Commodity (摘要)
   * --Commodity string(48) not null.
   *
   *   Set the information of the products that customers buy.
   *   And that is displayed at the time of the settlement in the KDDI center.
   *   Possible characters are next to "double-byte characters".
   *   お客様が購入する商品の情報を設定。KDDI センターでの決済時に表示される。
   *   設定可能な文字は「全角文字」となります。全角文字についての詳細は、「別 紙:制限事項一覧」を参照下さい。
   *
   * Settlement result back URL (決済結果戻し URL)
   * --RetURL string(256) not null.
   *
   *   Set the result receiving URL for merchants to receive a
   *   settlement result from this service.
   *
   *   Customer authentication on the KDDI center, if you cancel the payment
   *   operations and to send the results to the specified URL when you run
   *   the settlement process in this service via a redirect.
   *
   *   加盟店様が本サービスからの決済結果を受信する為の結果受信 URL を設定。
   *   KDDI センター上でお客様が認証、支払操作をキャンセルした場合や、
   *   本サービスにて決済処理を実行した場合に指定された URL に結果をリダイレクト経由で送信。
   *
   * Payment start date in seconds (支払開始期限秒)
   * --PaymentTermSec integer(5) null.
   *
   *   Deadline of customers from the [settlement] run until
   *   you call the [payment procedure completion IF].
   *   Up to 86,400 seconds (1 day)
   *   If the call parameter is empty, it is processed in 120 seconds
   *   お客様が【決済実行】から【支払手続き完了 IF】を呼び出すまでの期限。
   *   最大 86,400 秒(1 日)
   *   呼出パラメータが空の場合、120 秒で処理される
   *
   * Service Name (表示サービス名)
   * --ServiceName string(48) not null.
   *
   *   Service names of merchants. Displayed on your purchase history.
   *   Possible characters are next to "double-byte characters".
   *   加盟店様のサービス名称。お客様の購入履歴などに表示される。
   *   設定可能な文字は「全角文字」となります。
   *
   * Service Tel (表示電話番号)
   * --ServiceName string(15) not null.
   *
   *   Telephone number of merchants. Displayed on your purchase history.
   *   Possible characters are "single-byte numbers" - "(hyphen)".
   *   加盟店様の電話番号。お客様の購入履歴などに表示される。
   *   設定可能な文字は「半角数字と”-“(ハイフン)」となります。
   *
   * @Output parameters
   *
   * Access ID (アクセス ID)
   * --AccessID string(32)
   *
   * Token (トークン)
   * --Token string(256)
   *
   * Start URL (支払手続き開始 IF のURL)
   * --StartURL string(256)
   *
   * Start Limit Date (支払開始期限日時)
   * --StartLimitDate string(14)
   *   Format: yyyyMMddHHmmss
   */
  public function execTranAu($access_id, $access_pass, $order_id, $commodity, $ret_url, $service_name, $service_tel, $data = array())
  {
    if (!is_array($data)) {
      $data = array();
    }
    $data['access_id']    = $access_id;
    $data['access_pass']  = $access_pass;
    $data['order_id']     = $order_id;
    $data['commodity']    = $commodity;
    $data['ret_url']      = $ret_url;
    $data['service_name'] = $service_name;
    $data['service_tel']  = $service_tel;
    return $this->callApi('execTranAu', $data);
  }

  /**
   * It will return the token that is required in subsequent settlement deal.
   *
   * SiteID and SitePass are required if MemberID exist.
   *
   * @Input parameters
   *
   * Access ID (取引 ID)
   * --AccessID string(32) not null.
   *
   * Access Pass (取引パスワード)
   * --AccessPass string(32) not null.
   *
   * Order ID (オーダーID)
   * --OrderID string(27) not null.
   *
   * Member ID (会員 ID)
   * --MemberID string(60) conditional null.
   *
   *   MemberID is required if need CreateMember.
   *
   * Member Name (会員名)
   * --MemberName string(255) null.
   *
   * Members create flag (会員作成フラグ)
   * --CreateMember string(1) conditional null.
   *
   *   It will specify the operation when the member does not exist.
   *   Allowed values:
   *     0: Don't create. If a member does not exist, it returns an error.
   *     1: Create member. If a member does not exist, I will create new.
   *
   * Client Field 1 (加盟店自由項目 1)
   * --ClientField1 string(100) null.
   *
   * Client Field 2 (加盟店自由項目 2)
   * --ClientField2 string(100) null.
   *
   * Client Field 3 (加盟店自由項目 3)
   * --ClientField3 string(100) null.
   *
   * Commodity (摘要)
   * --Commodity string(48) not null.
   *
   *   Description of the end user can recognize the continued billing,
   *   and I will specify the timing of billing.
   *   Possible characters are next to "double-byte characters".
   *   エンドユーザが継続課金を認識できる説明、および課金のタイミングを明記します。
   *   設定可能な文字は「全角文字」となります。
   *
   * Billing timing classification (課金タイミング区分)
   * --AccountTimingKbn string(2) not null.
   *
   *   "01": specified in the accounting timing
   *   "02": the end
   *   “01”: 課金タイミングで指定
   *   “02”: 月末
   *
   * Billing timing (課金タイミング)
   * --AccountTiming string(2) not null.
   *
   *   Set in the 1-28. (29.30,31 can not be specified)
   *   1~28 で設定。(29.30,31 は指定不可)
   *
   * First billing date (初回課金日)
   * --FirstAccountDate string(8) not null.
   *
   *   It specifies the day until six months away from
   *   the day in yyyyMMdd format.
   *
   *   Maximum value example of (6 months ahead)
   *   6/17 → 12 / 17,8 / 31 → 2/28 (29)
   *
   *   当日から 6 ヶ月先までの間の日を yyyyMMdd フォーマットで指定。
   *   最大値(6 ヶ月先)の例 6/17→12/17、8/31→2/28(29)
   *
   * Settlement result back URL (決済結果戻し URL)
   * --RetURL string(256) not null.
   *
   *   Set the result receiving URL for merchants to receive a
   *   settlement result from this service.
   *
   *   Customer authentication on the KDDI center, if you cancel the payment
   *   operations and to send the results to the specified URL when you run
   *   the settlement process in this service via a redirect.
   *
   *   加盟店様が本サービスからの決済結果を受信する為の結果受信 URL を設定。
   *   KDDI センター上でお客様が認証、支払操作をキャンセルした場合や、
   *   本サービスにて決済処理を実行した場合に指定された URL に結果をリダイレクト経由で送信。
   *
   * Payment start date in seconds (支払開始期限秒)
   * --PaymentTermSec integer(5) null.
   *
   *   Deadline of customers from the [settlement] run until
   *   you call the [payment procedure completion IF].
   *   Up to 86,400 seconds (1 day)
   *   If the call parameter is empty, it is processed in 120 seconds
   *   お客様が【決済実行】から【支払手続き完了 IF】を呼び出すまでの期限。
   *   最大 86,400 秒(1 日)
   *   呼出パラメータが空の場合、120 秒で処理される
   *
   * Service Name (表示サービス名)
   * --ServiceName string(48) not null.
   *
   *   Service names of merchants. Displayed on your purchase history.
   *   Possible characters are next to "double-byte characters".
   *   加盟店様のサービス名称。お客様の購入履歴などに表示される。
   *   設定可能な文字は「全角文字」となります。
   *
   * Service Tel (表示電話番号)
   * --ServiceName string(15) not null.
   *
   *   Telephone number of merchants. Displayed on your purchase history.
   *   Possible characters are "single-byte numbers" - "(hyphen)".
   *   加盟店様の電話番号。お客様の購入履歴などに表示される。
   *   設定可能な文字は「半角数字と”-“(ハイフン)」となります。
   *
   * @Output parameters
   *
   * Access ID (アクセス ID)
   * --AccessID string(32)
   *
   * Token (トークン)
   * --Token string(256)
   *
   * Start URL (支払手続き開始 IF のURL)
   * --StartURL string(256)
   *
   * Start Limit Date (支払開始期限日時)
   * --StartLimitDate string(14)
   *   Format: yyyyMMddHHmmss
   */
  public function execTranAuContinuance($access_id, $access_pass, $order_id, $commodity, $account_timing_kbn, $account_timing, $first_account_date, $ret_url, $service_name, $service_tel, $data = array())
  {
    if (!is_array($data)) {
      $data = array();
    }
    $data['access_id']          = $access_id;
    $data['access_pass']        = $access_pass;
    $data['order_id']           = $order_id;
    $data['commodity']          = $commodity;
    $data['account_timing_kbn'] = $account_timing_kbn;
    $data['account_timing']     = $account_timing;
    $data['first_account_date'] = $first_account_date;
    $data['ret_url']            = $ret_url;
    $data['service_name']       = $service_name;
    $data['service_tel']        = $service_tel;
    return $this->callApi('execTranAuContinuance', $data);
  }

  /**
   * RegisterRecurringCredit
   *
   * @Input parameters
   *
   * Recurring ID (自動売上ID)
   * --RecurringID string(15) not null.
   *
   * Amount (利用金額)
   * --Amount integer(7) not null.
   *
   * Tax (税送料)
   * --Tax integer(7) null.
   *
   * Charge day (課金日)
   * --ChargeDay string(2) not null.
   *   Set day between "01" to "31".
   *   自動売上を行う日を01～31で指定します。
   *   指定した日が月末日よりも大きい場合は、月末日に処理されます。
   *
   * Charge month (課金月)
   * --ChargeMonth string(36) null.
   *   Set day between "01" to "31".
   *   自動売上を行う月を01～12で指定します。
   *   "|"で区切ることにより複数の月を指定可能です。
   *   省略した場合は、毎月として扱われます。
   *
   * Charge Start Date (課金開始日)
   * --ChargeStartDate string(8) null.
   *   Set format "yyyyMMdd".
   *   自動売上処理を開始する日をyyyyMMdd形式で指定します。
   *   ３ヶ月以内の日付を指定してください。
   *   省略した場合は、翌日が指定されます。
   *
   * Charge Stop Date (課金停止日)
   * --ChargeStopDate string(8) null.
   *   Format: yyyyMMdd
   *   自動売上処理を停止する日をyyyyMMdd形式で指定します。
   *   省略した場合は、無期限として扱われます。
   *
   * Regist Type (売上対象種別)
   * --RegistType string(1) not null.
   *   売上対象の指定方法を以下のいずれかから選択します。
   *   1:会員ID指定
   *   2:カード番号指定
   *   3:取引指定
   *
   * Member ID (会員ID)
   * --MemberID string(60) conditional null.
   *   MemberID is required if regist type is 1.
   *
   * Card No (カード番号)
   * --CardNo string(60) conditional null.
   *   CardNo is required if regist type is 2.
   *
   * Expire (有効期限)
   * --Expire string(60) conditional null.
   *   Format: YYMM
   *   Expire is required if regist type is 2.
   *
   * Src order ID (オーダーID)
   * --SrcOrderID string(27) conditional null.
   *   SrcOrderID is required if regist type is 3.
   *
   * Client Field 1 (加盟店自由項目1)
   * --ClientField1 string(100) null.
   *
   * Client Field 2 (加盟店自由項目2)
   * --ClientField2 string(100) null.
   *
   * Client Field 3 (加盟店自由項目3)
   * --ClientField3 string(100) null.
   **/
  public function registerRecurringCredit($recurring_id, $amount, $charge_day, $regist_type, $data = array())
  {
    if (!is_array($data)) {
      $data = array();
    }
    $data['recurring_id']  = $recurring_id;
    $data['amount']      = $amount;
    $data['charge_day']    = $charge_day;
    $data['regist_type']  = $regist_type;

    return $this->callApi('registerRecurringCredit', $data);
  }

  /**
   * RegisterRecurringAccounttrans
   *
   * @Input parameters
   *
   * Recurring ID (自動売上ID)
   * --RecurringID string(15) not null.
   *
   * Amount (利用金額)
   * --Amount integer(7) not null.
   *
   * Tax (税送料)
   * --Tax integer(7) null.
   *
   * Charge month (課金月)
   * --ChargeMonth string(36) null.
   *   Set day between "01" to "31".
   *   自動売上を行う月を01～12で指定します。
   *   "|"で区切ることにより複数の月を指定可能です。
   *   省略した場合は、毎月として扱われます。
   *
   * Charge Start Date (課金開始日)
   * --ChargeStartDate string(8) null.
   *   Set format "yyyyMMdd".
   *   自動売上処理を開始する日をyyyyMMdd形式で指定します。
   *   ３ヶ月以内の日付を指定してください。
   *   省略した場合は、翌日が指定されます。
   *
   * Charge Stop Date (課金停止日)
   * --ChargeStopDate string(8) null.
   *   Format: yyyyMMdd
   *   自動売上処理を停止する日をyyyyMMdd形式で指定します。
   *   省略した場合は、無期限として扱われます。
   *
   * Member ID (会員ID)
   * --MemberID string(60) not null.
   *
   * Print Str (通帳記載内容)
   * --PrintStr string(15) null.
   *   引き落とし時に、通帳に印字する内容
   *   ※以下の文字が利用可能です。
   *     ・ １バイト数字
   *     ・ 一部※を除く半角カナ文字
   *     ・ 半角濁点
   *     ・ 半角半濁点
   *     ・ ,.()/-
   *     ※除外される半角カナ: ｦ ｧ ｨ ｩ ｪ ｫ ｬ ｭ ｮ ｯ
   *   また、以下の埋込変数が使用可能です。
   *   %Y : 課金年(西暦下2桁)に展開されます。
   *   %M : 課金月(2桁)に展開されます。
   *   %D : 課金日(2桁)に展開されます。
   *
   * Client Field 1 (加盟店自由項目1)
   * --ClientField1 string(100) null.
   *
   * Client Field 2 (加盟店自由項目2)
   * --ClientField2 string(100) null.
   *
   * Client Field 3 (加盟店自由項目3)
   * --ClientField3 string(100) null.
   **/
  public function registerRecurringAccounttrans($recurring_id, $amount, $member_id, $print_str, $data = array())
  {
    if (!is_array($data)) {
      $data = array();
    }
    $data['recurring_id']  = $recurring_id;
    $data['amount']      = $amount;
    $data['member_id']    = $member_id;
    $data['print_str']    = $print_str;

    return $this->callApi('registerRecurringAccounttrans', $data);
  }
}
