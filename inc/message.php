<?php
// all
include('../inc/const.php');

$data_save_no = '登録されたデータがありません。';
$save_success = '入力情報保存しました。';
$autosave_success = '自動入力情報保存しました。';
$update_success = '入力情報が更新されました。';
$connect_error = '接続エラー: ';
$save_fail = '入力情報保存できませんでした。';
$delete_success = '削除成功しました。';
$delete_all_success = 'すべて削除成功しました。';
$select_message = '勤務時間タイプを選択してください。';
$image_upload_error = 'ファイルのアップロード中にエラーが発生しました。';
$image_type_error = '無効なファイルタイプです。 画像(JPEG、PNG、JPG)をアップロードしてください。';
$image_size_error = 'アップロードされた画像は最大許容サイズ (2 MB) を超えています。';
$image_empty_error = '写真を入力してください。';
$signstamp_empty_error = '印鑑を入力してください。';

// index
$login_is_not = '詳細情報はログイン後可能です。';

// loginout
$login_success = 'ログインに成功しました。';
$logout_success = 'ログアウトに成功しました。';
$login_fail = 'メールまたはパスワードが正しくありません。もう一度お試しください。';

// userList
$user_id_empty = 'IDを入力してください。';
$user_pwd_empty = 'Passwordを入力してください。';
$user_name_empty = '社員名を入力してください。';
$user_email_empty = 'メールを入力してください。';
$user_dept_empty = '部署を入力してください。';
$user_grade_empty = '区分を入力してください。';
$user_genba_list_empty = '勤務時間タイプを選択してください。';
$file_size_isvalid_STAMP = 'ファイルサイズが無効です。ファイルサイズが' . round($STAMP_MAXSIZE / 1000000, 0) . 'MB未満のファイルを選択してください。';
$file_extension_invalid_STAMP = 'ファイルの拡張子無効です。ファイルの拡張子が [ ' . implode(', ', $ALLOWED_TYPES_STAMP) . ' ] です';
$min_invalid_useremail_length = 'メールが短すぎます。@の前に最低'.$MIN_LENGTH_ID_EMAIL_USER.'桁以上入力してください。';
$min_invalid_userid_length = 'IDが短すぎます。@の前に最低'.$MIN_LENGTH_ID_EMAIL_USER.'桁以上入力してください。';
$email_is_dupplicate = 'メールが存在されています。他のメールを登録してください。';

// kyukaReg
$kyuka_no_receive = '休暇はまだもらえません。';
$kyuka_ymdcnt_01 = '休暇の申込日は(';
$kyuka_ymdcnt_02 = '日)を超えるわけにはいきません。';
$kyuka_name_select = '休暇区分を選択してください。';
$kyuka_time_limit_01 = '休暇の申込時間は(';
$kyuka_time_limit_02 = '時間)を超えるわけにはいきません。';
$kyuka_endymd_01 = '休暇の申込は(';
$kyuka_endymd_02 = 'の内だけに可能です。';
$kyuka_strymd = '残数(日)を超える休暇は申し込む事はできません。';
$kyuka_type_select = '申込区分を選択してください。';
$kyuka_strymd_empty = '期間(F)を入力してください。';
$kyuka_endymd_empty = '期間(T)を入力してください。';
$kyuka_strtime_empty = '時間(F)を入力してください。';
$kyuka_endtime_empty = '時間(T)を入力してください。';
$kyuka_destcode_select = '暇中居る場所を選択してください。';
$kyuka_destplace_empty = '場所を入力してください。';
$kyuka_desttel_empty = '電話番号を入力してください。';
$kyuka_vacationstr_empty = '休暇開始日を入力してください。';
$kyuka_vacationend_empty = '休暇終了日を入力してください。';

// vacationReg
$kyuka_oldcnt_empty = '休暇使用数を入力してください。';
$kyuka_newcnt_empty = '休暇新しい数を入力してください。';
$kyuka_usecnt_empty = '休暇使用数を入力してください。';
$kyuka_usetime_empty = '休暇使用時間を入力してください。';
$kyuka_restcnt_empty = '休暇休憩回数を入力してください。';
$kyuka_oldcnt_no = '休暇古い数を番号で入力してください。';
$kyuka_newcnt_no = '休暇新しい数を番号で入力してください。';
$kyuka_usecnt_no = '休暇使用数を番号で入力してください。';
$kyuka_usetime_no = '休暇使用時間を番号で入力してください。';
$kyuka_restcnt_no = '休暇休憩回数を番号で入力してください。';

// kintaiReg
$kintai_start_empty = '出社時刻を入力してください。';
$kintai_start_no = '出社時刻を番号で入力してください。';
$kintai_end_empty = '退社時刻を入力してください。';
$kintai_end_no = '退社時刻を番号で入力してください。';
$kintai_bstart_empty = '業務開始を入力してください。';
$kintai_bstart_no = '業務開始を番号で入力してください。';
$kintai_bend_empty = '業務終了を入力してください。';
$kintai_bend_no = '業務終了を番号で入力してください。';
$kintai_offtime_empty = '休憩時間を入力してください。';
$kintai_offtime_no = '休憩時間を番号で入力してください。';
$kintai_click_month = '月合計登録まだ保存されてないです。確認してください。';
$kintai_bigo_or_comment = 'コメントか備考を入力してください。';
$kintai_check_input_err = ' 桁まで入力してください';
$kintai_reg_workmonth = '勤務日登録されてないです。確認してください。';
$kakutei_success = '提出確定しました。変更がある場合は管理者へ連絡してください';
$modoshi_success = '編集中に戻しました。';
$shonin_success = '承認成功しました。変更がある場合は編集中に戻し編集してください';
$sekininshonin_success = '責任者承認成功しました。変更がある場合は編集中に戻し編集してください';
$kakutei_fail = '月勤務表がまだ提出されていないです。ご確認お願い致します。';
$shonin_notkakutei_fail = '月勤務表がまだ提出されていないです。ご確認お願い致します。';
$sekininshonin_notkakutei_fail = '月勤務表がまだ提出されていないです。ご確認お願い致します。';
$submised_not_change = '  *提出した勤務表を編集又は登録不可です。！';
$is_not_registed_WorkMonth = '  月合計登録まだ登録されてないです。確認してください。！\n 登録されていない場合は提出出来ません';
$is_submissed_notchange ='勤務表提出しましたので編集出来ません、管理者へ連絡してください。';
$Shonin_KanriSha_Undefine = '選択した管理者はエラー発生したました。システム管理者へご連絡ください';
$Shonin_SekininSha_Undefine = '選択した責任者はエラー発生したました。システム管理者へご連絡ください';
$is_admin_changed_saveWaiting = '責任者、管理者の変更があります、「承認」ボタン押すと保存します。';

// genbaList
$user_genbaname_empty = '勤務時間タイプを入力してください。';
$user_genbacompany_empty = '勤務会社名を入力してください。';
$user_strymd_empty = '勤務作業期間を入力してください。';
$user_endymd_empty = '勤務作業期間を入力してください。';
$user_workstr_empty = '業務開始時間を入力してください。';
$user_workend_empty = '業務終了時間を入力してください。';
$user_offtime1_empty = '昼休(時:分)を入力してください。';
$user_offtime2_empty = '夜休(時:分)を入力してください。';
$user_workstr_incorrect = '業務開始時間を正しく入力してください。';
$user_workend_incorrect = '業務終了時間を正しく入力してください。';
$user_offtime1_incorrect = '昼休(時:分)を正しく入力してください。';
$user_offtime2_incorrect = '夜休(時:分)を正しく入力してください。';

// workdayList
$info_workyear_empty = '勤務年は必須です。';
$info_workyear_no = '勤務年を番号で入力してください。';
$info_workyear_have = '登録済み年月です。';
$info_workday01_no = '01月に項目を番号で入力してください。';
$info_workday02_no = '02月に項目を番号で入力してください。';
$info_workday03_no = '03月に項目を番号で入力してください。';
$info_workday04_no = '04月に項目を番号で入力してください。';
$info_workday05_no = '05月に項目を番号で入力してください。';
$info_workday06_no = '06月に項目を番号で入力してください。';
$info_workday07_no = '07月に項目を番号で入力してください。';
$info_workday08_no = '08月に項目を番号で入力してください。';
$info_workday09_no = '09月に項目を番号で入力してください。';
$info_workday10_no = '10月に項目を番号で入力してください。';
$info_workday11_no = '11月に項目を番号で入力してください。';
$info_workday12_no = '12月に項目を番号で入力してください。';

// holidayReg
$info_holiday_empty = '祝日を正しく入力してください。';
$info_holiday_have = '登録済み祝日です。';

// uservacationList
$info_uvl_joincompany_empty = '入社日が登録されていない社員は休暇登録はできません。';
$info_uvlvacationstr_empty = '年次開始日を入力してください。';
$info_uvlvacationend_empty = '年次終了日を入力してください。';

// manageInfo
$manage_magamym_empty = '締切（月）を入力してください。';
$manage_magamymd_empty = '締切（日）を入力してください。';
$manage_kyukatimelimit_empty = '年間休暇時間を入力してください。';
$manage_kyukatimelimit_no = '年間休暇時間を番号で入力してください。';

// noticeList
$content_noteT_empty = 'タイトルを入力してください。';
$content_noteC_empty = '内容を入力してください。';
$content_noteR_empty = '確認者を入力してください。';
$content_noteRegdt_empty = '作成日を入力してください。';
$content_noteViewcnt_empty = 'view Cntを入力してください。';
$file_size_isvalid = 'ファイルサイズが無効です。ファイルサイズが' . round($NOTICE_IMAGE_MAXSIZE / 1000000, 0) . 'MB未満のファイルを選択してください。';
$file_extension_invalid = 'ファイルの拡張子無効です。ファイルの拡張子が [ ' . implode(', ', $ALLOWED_TYPES) . ' ] です';

// companyList
$manage_Ccode_empty = '会社コードを入力してください。';
$manage_Ccode_no = '会社コードを番号で入力してください。';
$manage_Ccode_have = '登録済み会社コードです。';
$manage_Cname_empty = '会社名を入力してください。';
$manage_staff_empty = '担当者名を入力してください。';
$manage_telno_empty = '電話番号を入力してください。';
$manage_strymd_empty = '契約期間(F)を入力してください。';
$manage_endymd_empty = '契約期間(T)を入力してください。';
$manage_address_empty = '住所を入力してください。';
$manage_joken_empty = '契約条件を入力してください。';

// adminList
$manage_id_empty = 'IDを入力してください。';
$manage_id_alphabet = 'IDをアルファベットで入力してください。';
$manage_Uid_have = '登録済みIDです。';
$manage_pwd_empty = 'Passwordを入力してください。';
$manage_name_empty = '社員名を入力してください。';
$manage_grade_empty = '区分を入力してください。';
$manage_email_empty = 'メールを入力してください。';
$manage_dept_empty = '部署を入力してください。';
$manage_companyid_empty = '会社名を選択してください。';

// codemasterList
$content_choice_empty = '部署または休暇種類のType Nameをどちらか選んでください。';
$content_cmlC_empty = 'Codeを入力してください。';
$content_cmlC_no = 'Codeをを番号で入力してください。';
$content_cmlN_empty = '名を入力してください。';
$content_cmlC_duplicate = 'コードが既存しましたので登録出来ませんでした。';

// codetypeList
$content_ctlC_empty = 'Type Codeを入力してください。';
$content_ctlC_no = 'Type Codeをを番号で入力してください。';
$content_ctlN_empty = 'Type Nameを入力してください。';
$content_ctlC_duplicate = 'Type Codeが既存しましたので登録出来ませんでした。';