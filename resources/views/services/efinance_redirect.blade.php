<html>

<head>
    <title>Redirecting...</title>
</head>

<body onload="document.forms['payform'].submit();">
    <div style="text-align: center; margin-top: 100px; font-family: Cairo;">
        <h3>جاري توجيهك بأمان إلى بوابة دفع e-finance</h3>
        <p>يرجى عدم إغلاق الصفحة أو عمل تحديث...</p>
    </div>

    <form method="post" id="initiationForm" action="{{ $url }}">
        <input type="hidden" name="SenderID" value="<?= $params['SenderID'] ?>">
        <input type="hidden" name="RandomSecret" value="<?= $params['RandomSecret'] ?>">
        <input type="hidden" name="RequestObject" value="<?= $params['RequestObject'] ?>">
        <input type="hidden" name="HasedRequestObject" value="<?= $params['HasedRequestObject'] ?>">
        <input type="submit" id="sendButton" value="send">
    </form>
    <script type="text/javascript">
        document.getElementById("sendButton").style.display = "none";
        document.getElementById("initiationForm").submit();
    </script>
</body>

</html>
