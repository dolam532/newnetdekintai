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
$login_email_fail = 'メールアドレスまたパスワードを正しく入力してください。';
$login_pwd_fail = 'パスワードは半角英数字のみを許可します。';

// userList
$user_email_empty = 'Emailを入力してください。';
$user_pwd_empty = 'Passwordを入力してください。';
$user_name_empty = '社員名を入力してください。';
$user_email_empty = 'メールを入力してください。';
$user_dept_empty = '部署を入力してください。';
$user_grade_empty = '区分を入力してください。';
$user_inymd_empty = '入社日を入力してください。';
$user_genba_list_empty = '勤務時間タイプを選択してください。';
$file_size_isvalid_STAMP = 'ファイルサイズが無効です。ファイルサイズが' . round($STAMP_MAXSIZE / 1000000, 0) . 'MB未満のファイルを選択してください。';
$file_extension_invalid_STAMP = 'ファイルの拡張子無効です。ファイルの拡張子が [ ' . implode(', ', $ALLOWED_TYPES_STAMP) . ' ] です。';
$email_is_dupplicate = 'メールが存在されています。他のメールを登録してください。';
$user_type_undefined = '権限がありませんでした。担当者まで連絡してください。';

// kyukaReg
$kyuka_type_select = '申込区分を選択してください。';
$kyuka_name_select = '休暇区分を選択してください。';
$kyuka_destcode_select = '暇中居る場所を選択してください。';
$kyuka_vacation_empty = '年度算定期間を入力してください。';
$kyuka_strymd_empty = '期間(From)を入力してください。';
$kyuka_endymd_empty = '期間(To)を入力してください。';
$kyuka_strtime_empty = '時間(From)を入力してください。';
$kyuka_endtime_empty = '時間(To)を入力してください。';
$kyuka_tothday_empty = '総有給休暇を入力してください。';
$kyuka_oldcnt_empty = '前年度の繰越残を入力してください。';
$kyuka_newcnt_empty = '当該年度付与を入力してください。';
$kyuka_usefinishcnt_empty = '使用済数を入力してください。';
$kyuka_usefinishcnt_edit = '使用済数を修正してください。';
$kyuka_usebeforecnt_empty = '使用前残を入力してください。';
$kyuka_usenowcnt_empty = '今回使用を入力してください。';
$kyuka_usenowcnt_edit = '今回使用を修正してください。';
$kyuka_usefinishaftercnt_empty = '使用後済を入力してください。';
$kyuka_useafterremaincnt_empty = '使用後残を入力してください。';
$kyuka_reason_empty = '事由を入力してください。';
$kyuka_destcode_select = '暇中居る場所を選択してください。';
$kyuka_destplace_empty = '場所を入力してください。';
$kyuka_desttel_empty = '電話番号を入力してください。';
$kyuka_submit = '休暇届を登録してよろしいでしょうか？';
$kyuka_delete_mgs = '休暇届を削除してよろしいでしょうか？';
$user_kyuka_data_not_found='選択したデータが見つかりませんでした。再度ログインして、操作してください。';
$user_kyuka_kakutei_success='提出確定しました。変更がある場合は管理者へ連絡してください。';
$user_kyuka_kakutei_fail='休暇届がまだ提出されていないです。ご確認お願い致します。';
$user_kyuka_modoshi_submit='編集中に戻します。選択した休暇届が編集可能になります。\nよろしいでしょうか？';
$user_kyuka_modoshi_success='編集中に戻しました。これから、編集可能です。';
$user_kyuka_modoshi_fail='編集に戻しエラー発生しました。';
$tanto_shonin_success = '担当者承認成功しました。変更がある場合は編集中に戻し編集してください。';
$tanto_shonin_error = '担当者承認失敗しました。最初からやり直してください。';
$sekinin_shonin_success = '責任者承認成功しました。変更がある場合は編集中に戻し編集してください。';
$sekinin_shonin_error = '責任者承認失敗しました。最初からやり直してください。';
$user_kyuka_tantosha_submit ='担当者承認確定します。\nよろしいでしょうか？';
$user_kyuka_sekininsha_submit='責任者承認確定します。\nよろしいでしょうか？';
$multi_select_is_empty='休暇届を選択してください';
$same_kyuka_status_select_msg='同じ状態の休暇届を選択してください。';
$miteishutsu_kyuka_selected_error_msg='提出していない休暇届を操作できません。\n提出してかかもう一度やり直してください。';


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
$kintai_click_month = '月合計がまだ登録されていません。「月合計登録」ボタンを押してください。';
$kintai_bigo_or_comment = 'コメントか備考を入力してください。';
$kintai_check_input_err = ' 桁まで入力してください。';
$kintai_reg_workmonth = '勤務日登録されてないです。確認してください。';
$kakutei_success = '提出確定しました。変更がある場合は管理者へ連絡してください。';
$kakutei_success_admin = '提出確定しました。変更がある場合は「編集中に戻す」ボタンを押下して修正してください。';
$modoshi_success = '編集中に戻しました。';
$shonin_success = '承認成功しました。変更がある場合は編集中に戻し編集してください。';
$sekininshonin_success = '責任者承認成功しました。変更がある場合は編集中に戻し編集してください。';
$kakutei_fail = '月勤務表がまだ提出されていないです。ご確認お願い致します。';
$shonin_notkakutei_fail = '月勤務表がまだ提出されていないです。ご確認お願い致します。';
$sekininshonin_notkakutei_fail = '月勤務表がまだ提出されていないです。ご確認お願い致します。';
$submised_not_change = '  *提出した勤務表を編集又は登録不可です。';
$is_not_registed_WorkMonth = '月合計がまだ登録されていません。「月合計登録」ボタンを押してください。\n 登録されていない場合は提出出来ません。';
$is_submissed_notchange = '勤務表提出しましたので編集出来ません、管理者へ連絡してください。';
$Shonin_KanriSha_Undefine = '選択した管理者はエラー発生したました。システム管理者へご連絡ください。';
$Shonin_SekininSha_Undefine = '選択した責任者はエラー発生したました。システム管理者へご連絡ください。';
$is_admin_changed_saveWaiting = '責任者、管理者の変更があります、「承認」ボタン押すと保存します。';
$kakutei_ninsho_message = '提出確定してよろしいでしょうか？ \n確定したら編集できません。';


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

// noticeList
$content_noteT_empty = 'タイトルを入力してください。';
$content_noteC_empty = '内容を入力してください。';
$content_noteR_empty = '確認者を入力してください。';
$content_noteRegdt_empty = '作成日を入力してください。';
$content_noteViewcnt_empty = 'view Cntを入力してください。';
$file_size_isvalid = 'ファイルサイズが無効です。ファイルサイズが' . round($NOTICE_IMAGE_MAXSIZE / 1000000, 0) . 'MB未満のファイルを選択してください。';
$file_extension_invalid = 'ファイルの拡張子無効です。ファイルの拡張子が [ ' . implode(', ', $ALLOWED_TYPES) . ' ] です。';

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

// companywList
$manage_CWname_empty = '会社名を選んでください。';
$manage_CWstarttime_empty = '業務開始時間を入力してください。';
$manage_CWendtime_empty = '業務終了時間を入力してください。';
$manage_CWBreakstarttime_empty = '休憩開始時間を入力してください。';
$manage_CWBreakendtime_empty = '休憩終了時間を入力してください。';
$manage_CWcompany_have = '登録済み業務時間です。';

// kyukaNotice
$kyuka_info_not_existing='年次有給休暇日数登録を登録されていないですので、先に年次有給休暇日数登録を登録してください。';


// kyukaInfo
$kyuka_info_min_workYm = 'こちらは最低限として登録してください。';
$kyuka_info_max_workYm = 'こちらは最大限限として登録してください。';
