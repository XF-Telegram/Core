window.addEventListener("message", function(message) {
  if (message.origin !== 'https://t.me') {
    return;
  }
  var data = JSON.parse(message.data);
  if (data.event !== 'resize') {
    return;
  }
  var iframes = [].slice.call(document.getElementsByTagName('iframe')).filter(function(iframe) {
    return iframe.contentWindow === message.source;
  });

  if (iframes.length < 1) {
    return;
  }

  iframes[0].style.height = data.height + 'px';
}, false);
