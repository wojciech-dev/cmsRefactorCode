<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<img class="imageNews" src="http://placekitten.com/200/300" />
<br />
<br />

<select id="layout" onChange="imageUpdate();">
    <option value="http://placekitten.com/200/300">http://placekitten.com/200/300</option>
    <option value="http://placekitten.com/100/300">http://placekitten.com/100/300</option>
    <option value="http://placekitten.com/100/100">http://placekitten.com/100/100</option>
    <option value="http://placekitten.com/20/100">http://placekitten.com/20/100</option>
</select>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    function imageUpdate() {
    var image = $("select#layout").val();
    var path = "";
    var src = $("img.imageNews").attr({
        src: path + image,
        title: "Image",
        alt: "Image"
    });
}
</script>

</body>
</html>