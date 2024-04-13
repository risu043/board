// ページが再読み込みされた時、セッションリセット用のフォームを送信する
document.addEventListener('DOMContentLoaded', function () {
  const navigationTiming = window.performance.getEntriesByType('navigation')[0];
  if (navigationTiming && navigationTiming.type === 'reload') {
    document.getElementById('reload').submit();
  }
});
