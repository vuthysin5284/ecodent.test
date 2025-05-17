  function setNow() {
    var now = new Date($.now()), year, month, date, hours, minutes, formattedDateTime;
    year = now.getFullYear();
    month = now.getMonth().toString().length === 1 ? '0' + (now.getMonth() + 1).toString() : now.getMonth() + 1;
    date = now.getDate().toString().length === 1 ? '0' + (now.getDate()).toString() : now.getDate();
    hours = '09';
    minutes = '00';
    formattedDateTime = year + '-' + month + '-' + date + 'T' + hours + ':' + minutes;
    return formattedDateTime;
  }

  function randQr (length) {
    let result = '';
    const characters = 'AaBb1CcDd2EeFf3GgHh4IiJj5KkLl6MmNn7OoPp8QqRr9SsTt0UuVvWwXxYyZz';
    const charactersLength = characters.length;
    let counter = 0;
    while (counter < length) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
      counter += 1;
    }
    return result; 
  }

  function padZero (str, max) {
    str = str.toString();
    return str.length < max ? padZero("0" + str, max) : str;
  }
  
  function alertText(text, color) {
    var style = color + " w-auto text-center text-nowrap";
    $.bootstrapGrowl(text, {
      type : style,
      offset : { from:"top", amount: 100 },
      align : "center",
      delay : 3000,
      allow_dismiss: false,
    });
  }