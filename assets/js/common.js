/**
* 입력값이  Date type 인지 체크한다
*/

function isValidDate(param) {

       try {
              param = param.replace(/-/g, '');
              param = param.replace(/\//g, '');

              // 자리수가 맞지않을때
              if (isNaN(param) || param.length != 8) {
                     return false;
              }

              var year = Number(param.substring(0, 4));
              var month = Number(param.substring(4, 6));
              var day = Number(param.substring(6, 8));
              var dd = day / 0;

              if (month < 1 || month > 12) {
                     return false;
              }

              var maxDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
              var maxDay = maxDaysInMonth[month - 1];

              // 윤년 체크
              if (month == 2 && (year % 4 == 0 && year % 100 != 0 || year % 400 == 0)) {
                     maxDay = 29;
              }

              if (day <= 0 || day > maxDay) {
                     return false;
              }
              return true;

       } catch (err) {
              return false;
       }
}

/**
* 입력값이  Date(yyyy/mm) type 인지 체크한다
*/
function isValidDateYM(param) {

       try {
              param = param.replace(/-/g, '');
              param = param.replace(/\//g, '');

              // 자리수가 맞지않을때
              if (isNaN(param) || param.length != 6) {
                     return false;
              }

              var year = Number(param.substring(0, 4));
              var month = Number(param.substring(4, 6));
              var day = Number(param.substring(6, 8));

              var dd = day / 0;


              if (month < 1 || month > 12) {
                     return false;
              }

              return true;

       } catch (err) {
              return false;
       }
}


/**
 * 입력값이  null 인지 체크한다
 */
function isNull(input) {
       if (input.value == null || input.value == "") {
              return true;
       } else {
              return false;
       }
}

/**
 * 입력값이 스페이스 이외의 의미있는 값이 있는지 체크한다
 * if (isEmpty(form.keyword)){
 *       alert('값을 입력하여주세요');
 * }
 */
function isEmpty(input) {
       if (input.value == null || input.value.replace(/ /gi, "") == "") {
              return true;
       } else {
              return false;
       }
}

/**
 * 입력값에 특정 문자가 있는지 체크하는 로직이며
 * 특정문자를 허용하고 싶지 않을때 사용할수도 있다
 * if (containsChars(form.name, "!,*&^%$#@~;")){
 *       alert("특수문자를 사용할수 없습니다");
 * }
 */
function containsChars(input, chars) {
       for (var i = 0; i < input.value.length; i++) {
              if (chars.indexOf(input.value.charAt(i)) != -1) {
                     return true;
              }
       }
       return false;
}

/**
 * 입력값이 특정 문자만으로 되어있는지 체크하며
 * 특정문자만을 허용하려 할때 사용한다.
 * if (containsChars(form.name, "ABO")){
 *    alert("혈액형 필드에는 A,B,O 문자만 사용할수 있습니다.");
 * }
 */
function containsCharsOnly(input, chars) {
       for (var i = 0; i < input.length; i++) {
              if (chars.indexOf(input.charAt(i)) == -1) {
                     return false;
              }
       }
       return true;
}

/**
 * 입력값이 알파벳인지 체크
 * 아래 isAlphabet() 부터 isNumComma()까지의 메소드가 자주 쓰이는 경우에는
 * var chars 변수를 global 변수로 선언하고 사용하도록 한다.
 * var uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
 * var lowercase = "abcdefghijklmnopqrstuvwxyz";
 * var number = "0123456789";
 * function isAlphaNum(input){
 *       var chars = uppercase + lowercase + number;
 *    return containsCharsOnly(input, chars);
 * }
 */
function isAlphabet(input) {
       var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
       return containsCharsOnly(input, chars);
}

/**
 * 입력값이 알파벳 대문자인지 체크한다
 */
function isUpperCase(input) {
       var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
       return containsCharsOnly(input, chars);
}

/**
 * 입력값이 알파벳 소문자인지 체크한다
 */
function isLowerCase(input) {
       var chars = "abcdefghijklmnopqrstuvwxyz";
       return containsCharsOnly(input, chars);
}

/**
 * 입력값이 숫자만 있는지 체크한다.
 */
function isNumer(input) {
       var chars = "0123456789";
       return containsCharsOnly(input, chars);
}

/**
 * 입려값이 알파벳, 숫자로 되어있는지 체크한다
 */
function isAlphaNum(input) {
       var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
       return containsCharsOnly(input, chars);
}

/**
 * 입력값이 숫자, 대시"-" 로 되어있는지 체크한다
 * 전화번호나 우편번호, 계좌번호에 -  체크할때 유용하다
 */
function isNumDash(input) {
       var chars = "-0123456789";
       return containsCharsOnly(input, chars);
}

/**
 * 입력값이 숫자, 콤마',' 로 되어있는지 체크한다
 */
function isNumComma(input) {
       var chars = ",0123456789";
       return containsCharsOnly(input, chars);
}

/**
 * 입력값이 사용자가 정의한 포맷 형식인지 체크
 * 자세한 format 형식은 자바스크립트의 'reqular expression' 참고한다
 */

function isValidFormat(input, format) {
       if (input.value.search(format) != -1) {
              return true; // 올바른 포멧형식
       }
       return false;
}

/**
 * 입력값이 이메일 형식인지 체크한다
 * if (!isValidEmail(form.email)){
 *       alert("올바른 이메일 주소가 아닙니다");
 * }
 */
function isValidEmail(input) {
       var format = /^((\w|[\-\.])+)@((\w|[\-\.])+)\.([A-Za-z]+)$/;
       return isValidFormat(input, format);
}

/**
 * 입력값이 전화번호 형식(숫자-숫자-숫자)인지 체크한다
 */
function isValidPhone(input) {
       var format = /^(\d+)-(\d+)-(\d+)$/;
       return isValidFormat(input, format);
}

/**
 * 입력값의 바이트 길이를 리턴한다.
 * if (getByteLength(form.title) > 100){
 *    alert("제목은 한글 50자 (영문 100자) 이상 입력할수 없습니다");
 * }
 */

function getByteLength(input) {
       var byteLength = 0;
       for (var inx = 0; inx < input.value.charAt(inx); inx++) {
              var oneChar = escape(input.value.charAt(inx));
              if (oneChar.length == 1) {
                     byteLength++;
              } else if (oneChar.indexOf("%u") != -1) {
                     byteLength += 2;
              } else if (oneChar.indexOf("%") != -1) {
                     byteLength += oneChar.length / 3;

              }
       }
       return byteLength;
}

/**
 * 입력값에서 콤마를 없앤다
 */
function removeComma(input) {
       return input.value.replace(/,/gi, "");
}

/**
 * 선택된 라디오버튼이 있는지 체크한다
 */
function hasCheckedRadio(input) {
       if (input.length > 1) {
              for (var inx = 0; inx < input.length; inx++) {
                     if (input[inx].checked) return true;
              }
       } else {
              if (input.checked) return true;
       }
       return false;
}

/**
 * 선택된 체크박스가 있는지 체크
 */
function hasCheckedBox(input) {
       return hasCheckedRadio(input);
}

/**
* 유효한(존재하는) 시(時)인지 체크
*/
function isValidHour(hh) {
       var h = parseInt(hh, 10);
       return (h >= 0 && h <= 24 && hh.length == 2);
}

/**
* 유효한(존재하는) 분(分)인지 체크
*/
function isValidMin(mi) {
       var m = parseInt(mi, 10);
       return (m >= 0 && m <= 60 && mi.length == 2);
}

/**
* 유효하는(존재하는) Time 인지 체크
* ex) var time = form.time.value; //'200102310000'
*     if (!isValidTime(time)) {
*         alert("올바른 날짜가 아닙니다.");
*     }
*/
function isValidTime(time) {
       var hour = time.substring(0, 2);
       var min = time.substring(3, 5);
       var spchar = time.substring(2, 3);

       if (isValidHour(hour) && isValidMin(min) && spchar == ":") {
              return true;
       }
       return false;
}


/**
 * 오늘(today) 값 가져오기
 * ex) var today = getToday(); //'2029/12/31'
 */
function getToday() {
       var today = new Date();
       var dd = today.getDate();
       var mm = today.getMonth() + 1; //January is 0!
       var yyyy = today.getFullYear();

       if (dd < 10) {
              dd = '0' + dd
       }

       if (mm < 10) {
              mm = '0' + mm
       }

       today = yyyy + '/' + mm + '/' + dd;
       return today;
}

// Alert auto close
$(function () {
       var alert = $('div.alert[auto-close]');
       alert.each(function () {
              var that = $(this);
              var time_period = that.attr('auto-close');
              setTimeout(function () {
                     that.alert('close');
              }, time_period);
       });
});
