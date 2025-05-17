<script type="text/javascript">
  function randQr (length) {
    let result = '';
    const characters = 'AaBb1CcDd2EeFf3GgHh4IiJj5KkLl6MmNn7OoPp8QqRr9SsTt0UuVvWwXxYyZz';
    // const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYabcdefghijklmnopqrstuvwxyzZ0123456789';
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
</script>