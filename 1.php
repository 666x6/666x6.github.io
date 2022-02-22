<html>
<head>
    <title>847默认播放器</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=11" />
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" id="viewport" name="viewport">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dplayer@latest/dist/DPlayer.min.css" /> 
    <style type="text/css">
        body,html{width:100%;height:100%;background:#000;padding:0;margin:0;overflow-x:hidden;overflow-y:hidden}
        *{margin:0;border:0;padding:0;text-decoration:none}
        #stats{position:fixed;top:5px;left:10px;font-size:9px;color:#fdfdfd;z-index:20719029;text-shadow:1px 1px 1px #000, 1px 1px 1px #000}
        #dplayer{position:inherit}
        .dplayer-logo{left:5px; top:12px;}
        .dplayer-logo img{width:28px;}
    </style>
</head>
<body style="background:#000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" oncontextmenu=window.event.returnValue=false>
    <div id="dplayer"></div>
    <div id="stats"></div>
    <script src="https://cdn.jsdelivr.net/npm/cdnbye@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/dplayer@latest"></script>
    <script>
        var url = '<?php echo($_REQUEST['url']);?>';
        var isWap = false; 
        if(!isWap){
            if(url.indexOf(".m3u8") > 0){          
                var _peerId = '', _peerNum = 0, _totalP2PDownloaded = 0, _totalP2PUploaded = 0;        
                var type = 'customHls';
            }
            else {
                var type = 'normal'; //MP4格式P2P兼容性不好，不调用P2P。
            }
            type = "customHls";
            var dp = new DPlayer({
            container: document.getElementById('dplayer'),
            autoplay: true,//自动播放
            lang:"zh-cn",//语言
            screenshot:true,//截图
            hotkey: true,  //热键
            preload:"auto",//预加载
            logo: '/favicon.png',
            video: {
                url:url,
                pic: '',  //封面
                type: type,
                customType: {
                    'customHls': function (video, player) {
                        const hls = new Hls({
                            debug: false,
                            p2pConfig: {
                                logLevel: true,
                                live: false,        // 如果是直播设为true
                            }
                        });
                        hls.loadSource(video.src);
                        hls.attachMedia(video);
                        hls.p2pEngine.on('stats', function (stats) {
                            _totalP2PDownloaded = stats.totalP2PDownloaded;
                            _totalP2PUploaded = stats.totalP2PUploaded;
                            updateStats();
                        }).on('peerId', function (peerId) {
                            _peerId = peerId;
                        }).on('peers', function (peers) {
                            _peerNum = peers.length;
                            updateStats();
                        });
    
                    }
                }
            },                        
            danmaku: {
            id: 'efbd974184da4819ab0cc068234a50d3', //弹幕id，随意一个唯一的
            api: 'https://dplayer.moerats.com/',//弹幕api 固定
            bottom: '25%',
            unlimited: true,
            },                
            contextmenu: [
            {
                text: '847影视',
                link: 'https://movie.i847.cn',
            },
            ],
        });
        var webdata = {
            set:function(key,val){
                window.sessionStorage.setItem(key,val);
            },
            get:function(key){
                return window.sessionStorage.getItem(key);
            },
            del:function(key){
                window.sessionStorage.removeItem(key);
            },
            clear:function(key){
                window.sessionStorage.clear();
            }
        };
        dp.seek(webdata.get('vod'+url));
        setInterval(function(){
            webdata.set('vod'+url,dp.video.currentTime);
        },1000);
        dp.on('ended',function() {
        dp.notice("视频播放已结束");   
            if(parent.MacPlayer.PlayLinkNext!=''){
                top.location.href = parent.MacPlayer.PlayLinkNext;}
            });        
        }
        else{
            document.getElementById('dplayer').innerHTML='<video src="'+url+'" controls="controls" preload="preload" poster="" width="100%" height="100%" autoplay="autoplay"></video>';
        }
        function updateStats() {
            var text = '847正在为您P2P加速' + (_totalP2PDownloaded/1024).toFixed(2)
                + 'MB 连接节点' + _peerNum + '个';
            document.getElementById('stats').innerText = text ;
        }
    </script>
</body>
</html>