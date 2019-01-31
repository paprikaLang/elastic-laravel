
<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=emulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prikango搜索引擎</title>
<link href="/css/style.css" rel="stylesheet" type="text/css" />
<link href="/css/index.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="container">
	<div id="bd">
        <div id="main">
        	<h1 class="title">
            	<div class="logo large"></div>
            </h1>
            <div class="inputArea">
            	<input type="text" class="searchInput" />
                <input type="button" class="searchButton" onclick="add_search()" />
                @if(session()->has('message'))
                    <div style="color:red;">
                        {{ session()->get('message') }}
                    </div>
                @endif
                <ul class="dataList">
                </ul>
            </div>
        </div><!-- End of main -->
    </div><!--End of bd-->
</div>
</body>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/global.js"></script>
<script type="text/javascript">
    function removeByValue(arr, val) {
      for(var i=0; i<arr.length; i++) {
        if(arr[i] == val) {
          arr.splice(i, 1);
          break;
        }
      }
    }
    // 搜索建议
    $(function(){
        $('.searchInput').bind(' input propertychange ',function(){
            var searchText = $(this).val();
            var tmpHtml = ""
            $.ajax({
                cache: false,
                type: 'get',
                dataType:'json',
                url: "/api/suggest?s="+searchText+"&s_type=article",
                async: true,
                success: function(data) {
                    for (var i=0;i<data.length;i++){
                        tmpHtml += '<li><a href="search?q='+data[i]+'&s_type=article&p=1">'+data[i]+'</a></li>'
                    }
                    $('.dataList').html("");
                    $('.dataList').append(tmpHtml);
                    if (data.length){
                        $('.dataList').show()
                    }else {
                        $('.dataList').hide()
                    }
                }
            });
        } );
    })

    hideElement($('.dataList'), $('.searchInput'));

</script>
<script>
    function add_search(){
        var val = $(".searchInput").val();
        window.location.href='search?q='+val+"&s_type=article&p=1"
    }
</script>
</html>